<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'book_title',
        'synopsis',
        'status',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function genres() : BelongsToMany
    {
        return $this->belongsToMany(Genre::class,'books_genres','book_id','genre_id');
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(Tag::class,'books_tags','book_id','tag_id');
    }
}
