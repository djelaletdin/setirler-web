<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Poem extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($poem) {
            $slug = Str::slug($poem->title);
            $originalSlug = $slug;
            $count = 2;

            while (static::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $poem->slug = $slug;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(ViewCount::class);
    }

    public function totalViews()
    {
        return $this->views()->count();
    }

    public function uniqueViews()
    {
        return $this->views()
            ->selectRaw('COUNT(DISTINCT CASE WHEN user_id IS NOT NULL THEN user_id ELSE ip_address END) as unique_views')
            ->value('unique_views');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_poems');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likeCount()
    {
        return $this->likes()->count();
    }

    public function contentBreak(){
        $parts = explode("\r\n", $this->content);
        return isset($parts[1]) ? $parts[0] . "\r\n" . $parts[1] : $parts[0];
    }
}
