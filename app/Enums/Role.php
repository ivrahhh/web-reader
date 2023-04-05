<?php

namespace App\Enums;

enum Role : string
{
    case Admin = 'Admin';
    case Author = 'Author';
    case Reader = 'Reader';

    /**
     * Get the values of the enum.
     */
    public static function values() : array
    {
        $cases = self::cases();

        return array_map(fn ($case) => $case->value, $cases);
    }
}