<?php

namespace App\Enums\Book;

use Illuminate\Support\Arr;

enum Status : string
{
    case Ongoing = 'Ongoing';
    case Completed = 'Completed';
    case Hiatus = 'Hiatus';
    case Dropped = 'Dropped';
    case Archived = 'Archived';

    /**
     * Get the value of the enums.
     */
    public static function values () : array
    {
        $cases = self::cases();

        return array_map(fn ($case) => $case->value, $cases);
    }

    /**
     * Get a random enum value from the enums.
     */
    public static function random() : string
    {
        $cases = self::cases();

        return Arr::random($cases)->value;
    }
}