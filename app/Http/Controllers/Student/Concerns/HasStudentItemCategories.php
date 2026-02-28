<?php

namespace App\Http\Controllers\Student\Concerns;

trait HasStudentItemCategories
{
    protected function categories(): array
    {
        return config('items.categories', []);
    }
}
