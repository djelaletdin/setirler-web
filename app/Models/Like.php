<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    /**
     * Check if a user liked a poem.
     *
     * @param int $userId
     * @param int $poemId
     * @return bool
     */
    public static function userLikedPoem($userId, $poemId)
    {
        return self::where('user_id', $userId)
            ->where('poem_id', $poemId)
            ->exists();
    }
}
