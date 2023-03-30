<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'poem_id',
        'viewed_at',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poem()
    {
        return $this->belongsTo(Poem::class);
    }
}
