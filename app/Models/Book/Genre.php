<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'genre_name',
        'description',
    ];

    public function books() : BelongsToMany
    {
        return $this->belongsToMany(Book::class,'books_genres','genre_id','book_id');
    }
}
