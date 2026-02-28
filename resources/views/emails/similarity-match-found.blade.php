<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potential Match Found</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <h2 style="margin-bottom: 12px;">Potential item match found</h2>

    <p>Hello {{ $payload['receiver_name'] ?? 'User' }},</p>

    <p>
        We found a potential match for <strong>{{ $payload['my_item_title'] ?? 'your item' }}</strong>.
    </p>

    <p>
        Matched item: <strong>{{ $payload['other_item_title'] ?? 'item' }}</strong><br>
        Similarity score: <strong>{{ $payload['similarity_percentage'] ?? '0.0' }}%</strong>
    </p>

    <p>
        Please review the match and take action from your matches page:
        <a href="{{ $payload['matches_url'] ?? '#' }}">{{ $payload['matches_url'] ?? 'Open matches' }}</a>
    </p>

    <p style="margin-top: 24px; color: #4b5563;">ASTU Lost & Found System</p>
</body>
</html>
