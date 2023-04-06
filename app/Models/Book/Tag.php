<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_name',
        'description',
    ];

    public function books() : BelongsToMany
    {
        return $this->belongsToMany(Book::class,'books_tags','tag_id','book_id');
    }
}
