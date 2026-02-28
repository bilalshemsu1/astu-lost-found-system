<?php

namespace App\Services;

use App\Mail\SimilarityMatchFoundMail;
use App\Models\Item;
use App\Models\SimilarityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ItemMatcher
{
    private int $threshold = 44;
    
    private array $stopWords = [
        'a', 'an', 'the', 'is', 'are', 'was', 'were', 'of', 'to', 'for', 'and', 'or',
        'on', 'in', 'at', 'with', 'from', 'by', 'my', 'your', 'our', 'has', 'have',
        'had', 'this', 'that', 'it', 'as', 'be', 'been', 'very', 'near', 'around',
    ];

    private array $tokenAliases = [
        'textbook' => 'book',
        'notebook' => 'book',
        'cellphone' => 'phone',
        'smartphone' => 'phone',
        'mobile' => 'phone',
        'bldg' => 'building',
        'block' => 'building',
        'blk' => 'building',
        'eng' => 'engineering',
        'lib' => 'library',
        'floor' => 'level',
        'flr' => 'level',
    ];

    private array $colors = [
        'black', 'white', 'blue', 'red', 'green', 'yellow', 'gray', 'grey',
        'silver', 'gold', 'pink', 'purple', 'brown', 'orange',
    ];

    private array $modelModifiers = ['pro', 'max', 'plus', 'ultra', 'mini', 'lite'];

    /**
     * Find matches for an item when it's approved
     */
    public function findMatches(Item $item): array
    {
        // Get opposite type items (active only)
        $oppositeType = $item->type === 'lost' ? 'found' : 'lost';
        
        $candidates = Item::where('type', $oppositeType)
            ->where('status', 'active')
            ->where('id', '!=', $item->id)
            ->get();

        $matches = [];

        foreach ($candidates as $candidate) {
            $scores = $this->calculateSimilarity($item, $candidate);
            
            // Match threshold is tuned for recall/precision balance.
            if ($scores['total'] >= $this->threshold) {
                $matches[] = [
                    'candidate' => $candidate,
                    'scores' => $scores,
                ];
            }
        }

        // Sort by highest similarity
        usort($matches, fn($a, $b) => $b['scores']['total'] <=> $a['scores']['total']);

        // When confidence is very high, keep only very high-confidence candidates.
        if ($matches !== [] && $matches[0]['scores']['total'] >= 95.0) {
            $matches = array_values(array_filter(
                $matches,
                static fn (array $match): bool => ($match['scores']['total'] ?? 0) >= 95.0
            ));
        }

        return $matches;
    }

    private function calculateSimilarity(Item $item1, Item $item2): array
    {
        $titleScore = $this->titleSimilarity($item1->title, $item2->title);
        $categoryScore = $this->categorySimilarity($item1->category, $item2->category);
        $descScore = $this->descriptionSimilarity($item1, $item2);
        $locationScore = $this->locationSimilarity($item1->location, $item2->location);
        $dateScore = $this->dateScore($item1, $item2);
        $attributeScore = $this->attributeConsistencyScore($item1, $item2);

        $baseTotal = (
            ($titleScore * 0.30) +
            ($categoryScore * 0.22) +
            ($descScore * 0.16) +
            ($locationScore * 0.14) +
            ($dateScore * 0.13) +
            ($attributeScore * 0.05)
        );

        $total = $this->applyConfidenceCalibration(
            $baseTotal,
            $titleScore,
            $categoryScore,
            $descScore,
            $locationScore,
            $dateScore,
            $attributeScore
        );

        return [
            'total' => round($total, 2),
            'title' => $titleScore,
            'category' => $categoryScore,
            'description' => $descScore,
            'location' => $locationScore,
            'date' => $dateScore,
            'attributes' => $attributeScore,
        ];
    }

    private function titleSimilarity(?string $left, ?string $right): float
    {
        $normalizedLeft = $this->normalizeText((string) $left);
        $normalizedRight = $this->normalizeText((string) $right);

        if ($normalizedLeft === '' || $normalizedRight === '') {
            return 0.0;
        }

        $base = $this->textSimilarity($normalizedLeft, $normalizedRight);
        $containment = $this->tokenContainmentScore($normalizedLeft, $normalizedRight) * 100;
        $substring = str_contains($normalizedLeft, $normalizedRight) || str_contains($normalizedRight, $normalizedLeft)
            ? 100.0
            : 0.0;
        $model = $this->modelTokenScore($normalizedLeft, $normalizedRight);
        $modifier = $this->modifierConsistencyScore($normalizedLeft, $normalizedRight);

        return round(
            ($base * 0.32) +
            ($containment * 0.32) +
            ($substring * 0.14) +
            ($model * 0.12) +
            ($modifier * 0.10),
            2
        );
    }

    private function descriptionSimilarity(Item $item1, Item $item2): float
    {
        $desc1 = (string) ($item1->description ?? '');
        $desc2 = (string) ($item2->description ?? '');
        $title1 = (string) ($item1->title ?? '');
        $title2 = (string) ($item2->title ?? '');

        $full1 = trim($title1 . ' ' . $desc1);
        $full2 = trim($title2 . ' ' . $desc2);

        $direct = $this->textSimilarity($desc1, $desc2);
        $fullText = $this->textSimilarity($full1, $full2);
        $cross1 = $this->textSimilarity($title1, $desc2);
        $cross2 = $this->textSimilarity($title2, $desc1);

        $candidateScores = [$direct, $fullText, $cross1, $cross2];
        rsort($candidateScores);
        $best = $candidateScores[0];
        $secondBest = $candidateScores[1] ?? 0.0;

        // Use top 2 signals so noisy descriptions don't collapse the score.
        return round(($best * 0.65) + ($secondBest * 0.35), 2);
    }

    private function textSimilarity(?string $left, ?string $right): float
    {
        $left = $this->normalizeText((string) $left);
        $right = $this->normalizeText((string) $right);

        if ($left === '' || $right === '') {
            return 0.0;
        }

        $leftVector = $this->tokenVector($left);
        $rightVector = $this->tokenVector($right);

        $jaccard = $this->jaccardScore(array_keys($leftVector), array_keys($rightVector)) * 100;
        $cosine = $this->cosineScore($leftVector, $rightVector) * 100;
        $editDistance = $this->editDistanceScore($left, $right);
        $bigram = $this->ngramDiceScore($left, $right, 2) * 100;

        return round(
            ($jaccard * 0.30) +
            ($cosine * 0.25) +
            ($editDistance * 0.20) +
            ($bigram * 0.15) +
            ($this->tokenContainmentScore($left, $right) * 100 * 0.10),
            2
        );
    }

    private function locationSimilarity(?string $left, ?string $right): float
    {
        $normalizedLeft = $this->normalizeText((string) $left);
        $normalizedRight = $this->normalizeText((string) $right);
        $baseScore = $this->textSimilarity($normalizedLeft, $normalizedRight);
        $containment = $this->tokenContainmentScore($normalizedLeft, $normalizedRight) * 100;

        $floorLeft = $this->extractFloor((string) $left);
        $floorRight = $this->extractFloor((string) $right);

        $floorScore = 85.0;
        if ($floorLeft !== null && $floorRight !== null) {
            $distance = abs($floorLeft - $floorRight);
            $floorScore = match (true) {
                $distance === 0 => 100.0,
                $distance === 1 => 85.0,
                $distance === 2 => 70.0,
                default => 40.0,
            };
        }

        return round(
            ($baseScore * 0.45) +
            ($containment * 0.35) +
            ($floorScore * 0.20),
            2
        );
    }

    private function categorySimilarity(string $left, string $right): float
    {
        $left = $this->normalizeToken($left);
        $right = $this->normalizeToken($right);

        if ($left === '' || $right === '') {
            return 0.0;
        }

        return $left === $right ? 100.0 : 20.0;
    }

    private function dateScore(Item $item1, Item $item2): float
    {
        if (!$item1->item_date || !$item2->item_date) {
            return 0.0;
        }

        $dateDiff = abs($item1->item_date->diffInDays($item2->item_date));

        return match (true) {
            $dateDiff === 0 => 100.0,
            $dateDiff <= 1 => 95.0,
            $dateDiff <= 3 => 85.0,
            $dateDiff <= 7 => 70.0,
            $dateDiff <= 14 => 45.0,
            $dateDiff <= 30 => 20.0,
            default => 0.0,
        };
    }

    private function attributeConsistencyScore(Item $item1, Item $item2): float
    {
        $text1 = $this->normalizeText($item1->title . ' ' . ($item1->description ?? ''));
        $text2 = $this->normalizeText($item2->title . ' ' . ($item2->description ?? ''));

        $colorScore = $this->colorScore($text1, $text2);
        $modelNumberScore = $this->modelTokenScore($text1, $text2);

        return round(($colorScore * 0.5) + ($modelNumberScore * 0.5), 2);
    }

    private function colorScore(string $left, string $right): float
    {
        $leftColors = $this->extractTokenSet($left, $this->colors);
        $rightColors = $this->extractTokenSet($right, $this->colors);

        if ($leftColors === [] && $rightColors === []) {
            return 70.0;
        }

        if ($leftColors === [] || $rightColors === []) {
            return 55.0;
        }

        return array_intersect($leftColors, $rightColors) !== [] ? 100.0 : 20.0;
    }

    private function modelTokenScore(string $left, string $right): float
    {
        preg_match_all('/\b[a-z]*\d+[a-z]*\b/i', $left, $leftMatches);
        preg_match_all('/\b[a-z]*\d+[a-z]*\b/i', $right, $rightMatches);

        $leftTokens = array_values(array_unique($leftMatches[0]));
        $rightTokens = array_values(array_unique($rightMatches[0]));

        if ($leftTokens === [] && $rightTokens === []) {
            return 70.0;
        }

        if ($leftTokens === [] || $rightTokens === []) {
            return 40.0;
        }

        if (array_intersect($leftTokens, $rightTokens) !== []) {
            return 100.0;
        }

        foreach ($leftTokens as $leftToken) {
            foreach ($rightTokens as $rightToken) {
                if ($this->editDistanceScore($leftToken, $rightToken) >= 80.0) {
                    return 75.0;
                }
            }
        }

        return 10.0;
    }

    private function modifierConsistencyScore(string $left, string $right): float
    {
        $leftTokens = array_keys($this->tokenVector($left));
        $rightTokens = array_keys($this->tokenVector($right));

        $leftModifiers = array_values(array_intersect($leftTokens, $this->modelModifiers));
        $rightModifiers = array_values(array_intersect($rightTokens, $this->modelModifiers));

        if ($leftModifiers === [] && $rightModifiers === []) {
            return 100.0;
        }

        if ($leftModifiers === [] || $rightModifiers === []) {
            return 60.0;
        }

        return array_intersect($leftModifiers, $rightModifiers) !== [] ? 100.0 : 20.0;
    }

    private function normalizeText(string $value): string
    {
        $value = strtolower($value);
        $value = preg_replace_callback(
            '/\b(\d+)(st|nd|rd|th)\b/u',
            static fn (array $matches): string => $matches[1],
            $value
        ) ?? $value;
        $value = preg_replace('/[^a-z0-9\s]/u', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? $value;

        return $value;
    }

    private function tokenVector(string $text): array
    {
        preg_match_all('/[a-z0-9]+/u', $text, $matches);
        $vector = [];

        foreach ($matches[0] as $token) {
            $token = $this->normalizeToken($token);

            if ($token === '' || in_array($token, $this->stopWords, true)) {
                continue;
            }

            $vector[$token] = ($vector[$token] ?? 0) + 1;
        }

        return $vector;
    }

    private function normalizeToken(string $token): string
    {
        $token = strtolower(trim($token));

        if ($token === '') {
            return '';
        }

        if (isset($this->tokenAliases[$token])) {
            $token = $this->tokenAliases[$token];
        }

        if (preg_match('/\d/u', $token)) {
            return $token;
        }

        return $this->stemToken($token);
    }

    private function stemToken(string $token): string
    {
        if (strlen($token) <= 4) {
            return $token;
        }

        foreach (['ingly', 'edly', 'ing', 'ed', 'ies', 'es', 's'] as $suffix) {
            if (str_ends_with($token, $suffix) && strlen($token) > strlen($suffix) + 2) {
                return substr($token, 0, -strlen($suffix));
            }
        }

        return $token;
    }

    private function jaccardScore(array $tokens1, array $tokens2): float
    {
        $set1 = array_values(array_unique($tokens1));
        $set2 = array_values(array_unique($tokens2));
        $union = array_unique(array_merge($set1, $set2));

        if ($union === []) {
            return 0.0;
        }

        $intersection = array_intersect($set1, $set2);

        return count($intersection) / count($union);
    }

    private function cosineScore(array $vector1, array $vector2): float
    {
        if ($vector1 === [] || $vector2 === []) {
            return 0.0;
        }

        $dot = 0.0;
        foreach ($vector1 as $token => $count) {
            $dot += $count * ($vector2[$token] ?? 0);
        }

        $norm1 = sqrt(array_sum(array_map(static fn ($count) => $count * $count, $vector1)));
        $norm2 = sqrt(array_sum(array_map(static fn ($count) => $count * $count, $vector2)));

        if ($norm1 == 0.0 || $norm2 == 0.0) {
            return 0.0;
        }

        return $dot / ($norm1 * $norm2);
    }

    private function editDistanceScore(string $left, string $right): float
    {
        $maxLen = max(strlen($left), strlen($right));

        if ($maxLen === 0) {
            return 0.0;
        }

        $distance = levenshtein($left, $right);
        $score = (1 - (min($distance, $maxLen) / $maxLen)) * 100;

        return max(0.0, min(100.0, $score));
    }

    private function ngramDiceScore(string $left, string $right, int $n = 2): float
    {
        $leftNgrams = $this->ngrams($left, $n);
        $rightNgrams = $this->ngrams($right, $n);

        if ($leftNgrams === [] || $rightNgrams === []) {
            return 0.0;
        }

        $leftCount = array_count_values($leftNgrams);
        $rightCount = array_count_values($rightNgrams);
        $overlap = 0;

        foreach ($leftCount as $gram => $count) {
            $overlap += min($count, $rightCount[$gram] ?? 0);
        }

        return (2 * $overlap) / (count($leftNgrams) + count($rightNgrams));
    }

    private function ngrams(string $text, int $n): array
    {
        $text = str_replace(' ', '', $text);
        $length = strlen($text);

        if ($length < $n) {
            return [];
        }

        $grams = [];
        for ($i = 0; $i <= $length - $n; $i++) {
            $grams[] = substr($text, $i, $n);
        }

        return $grams;
    }

    private function extractFloor(string $location): ?int
    {
        if (preg_match('/\b(\d+)\s*(?:floor|level)?\b/i', strtolower($location), $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function extractTokenSet(string $text, array $allowedTokens): array
    {
        $tokens = array_keys($this->tokenVector($text));
        $set = [];

        foreach ($tokens as $token) {
            if (in_array($token, $allowedTokens, true)) {
                $set[] = $token;
            }
        }

        return array_values(array_unique($set));
    }

    private function tokenContainmentScore(string $left, string $right): float
    {
        $leftTokens = array_keys($this->tokenVector($left));
        $rightTokens = array_keys($this->tokenVector($right));

        if ($leftTokens === [] || $rightTokens === []) {
            return 0.0;
        }

        $intersection = array_intersect($leftTokens, $rightTokens);
        $minLength = min(count($leftTokens), count($rightTokens));

        if ($minLength === 0) {
            return 0.0;
        }

        return count($intersection) / $minLength;
    }

    private function applyConfidenceCalibration(
        float $baseTotal,
        float $titleScore,
        float $categoryScore,
        float $descScore,
        float $locationScore,
        float $dateScore,
        float $attributeScore
    ): float {
        $boost = 0.0;

        if ($categoryScore >= 100.0) {
            $boost += 8.0;
        }

        if ($titleScore >= 90.0) {
            $boost += 9.0;
        } elseif ($titleScore >= 80.0) {
            $boost += 6.0;
        } elseif ($titleScore >= 70.0) {
            $boost += 3.0;
        }

        if ($descScore >= 70.0) {
            $boost += 4.0;
        } elseif ($descScore >= 55.0) {
            $boost += 2.0;
        }

        if ($locationScore >= 80.0) {
            $boost += 3.0;
        } elseif ($locationScore >= 65.0) {
            $boost += 1.5;
        }

        if ($dateScore >= 95.0) {
            $boost += 3.0;
        } elseif ($dateScore >= 85.0) {
            $boost += 2.0;
        }

        if ($attributeScore >= 85.0) {
            $boost += 2.0;
        }

        $penalty = 0.0;
        if ($titleScore < 35.0) {
            $penalty += 10.0;
        }
        if ($categoryScore < 100.0) {
            $penalty += 8.0;
        }

        return max(0.0, min(100.0, round($baseTotal + $boost - $penalty, 2)));
    }

    /**
     * Save matches to similarity_logs table
     */
    public function saveMatches(Item $item, array $matches): void
    {
        foreach ($matches as $match) {
            $candidate = $match['candidate'];
            $scores = $match['scores'];

            $lostItem = $item->type === 'lost' ? $item : $candidate;
            $foundItem = $item->type === 'found' ? $item : $candidate;

            if (($lostItem->type ?? null) !== 'lost' || ($foundItem->type ?? null) !== 'found') {
                continue;
            }

            $similarityLog = SimilarityLog::firstOrNew([
                'lost_item_id' => $lostItem->id,
                'found_item_id' => $foundItem->id,
            ]);

            $isNew = !$similarityLog->exists;

            $similarityLog->fill([
                'similarity_percentage' => $scores['total'],
                'title_match' => $scores['title'],
                'category_match' => $scores['category'],
                'description_match' => $scores['description'],
                'location_match' => $scores['location'],
                'date_match' => $scores['date'],
            ]);

            // Preserve existing notification state; only defaults to false for new matches.
            if ($isNew) {
                $similarityLog->notified = false;
            }

            $similarityLog->save();

            if (!$similarityLog->notified && $this->sendSimilarityMatchNotification($similarityLog)) {
                $similarityLog->forceFill(['notified' => true])->save();
            }
        }
    }

    private function sendSimilarityMatchNotification(SimilarityLog $similarityLog): bool
    {
        $similarityLog->loadMissing(['lostItem.user', 'foundItem.user']);

        $lostItem = $similarityLog->lostItem;
        $foundItem = $similarityLog->foundItem;
        $lostOwner = $lostItem?->user;
        $foundOwner = $foundItem?->user;

        $recipients = collect([$lostOwner, $foundOwner])
            ->filter(fn ($user) => $user && filter_var((string) $user->email, FILTER_VALIDATE_EMAIL))
            ->unique('email')
            ->values();

        if ($recipients->isEmpty()) {
            return true;
        }

        $allQueued = true;

        foreach ($recipients as $recipient) {
            $role = ((int) $recipient->id === (int) $lostOwner?->id) ? 'lost_owner' : 'found_owner';
            $myItemTitle = $role === 'lost_owner'
                ? ($lostItem?->title ?? 'Your lost item')
                : ($foundItem?->title ?? 'Your found item');
            $otherItemTitle = $role === 'lost_owner'
                ? ($foundItem?->title ?? 'Matched found item')
                : ($lostItem?->title ?? 'Matched lost item');

            $payload = [
                'receiver_name' => $recipient->name,
                'my_item_title' => $myItemTitle,
                'other_item_title' => $otherItemTitle,
                'similarity_percentage' => number_format((float) $similarityLog->similarity_percentage, 1),
                'matches_url' => route('student.matches'),
            ];

            try {
                Mail::to($recipient->email)->queue(new SimilarityMatchFoundMail($payload));
            } catch (\Throwable $exception) {
                $allQueued = false;
                Log::warning('Failed to queue similarity match email.', [
                    'similarity_log_id' => $similarityLog->id,
                    'recipient_email' => $recipient->email,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $allQueued;
    }
}
