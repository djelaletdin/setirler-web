<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function poem()
    {
        return $this->belongsTo(Poem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function upvotes()
    {
        return $this->votes()->where('vote', 1);
    }

    public function downvotes()
    {
        return $this->votes()->where('vote', -1);
    }

    public function vote($user, $direction)
    {
        if ($direction != 1 && $direction != -1) {
            throw new \InvalidArgumentException('Invalid vote direction: ' . $direction);
        }

        $existingVote = $this->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            if ($existingVote->vote == $direction) {
                $this->votes_count -= $direction;
                $existingVote->delete();
            } else {
                $this->votes_count += 2 * $direction;
                $existingVote->update(['vote' => $direction]);
            }
        } else {
            $this->votes()->create([
                'user_id' => $user->id,
                'vote' => $direction,
            ]);

            $this->votes_count += $direction;
        }

        $this->save();
    }

    public function userVoteOnComment($userId)
    {
        $vote = $this->votes()->where('user_id', $userId)->first();
        if ($vote) {
            return $vote->vote; // 1 for like, -1 for dislike
        }
        return null; // User hasn't voted on this comment
    }
}
