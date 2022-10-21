<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookStatus;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn' ,
        'title' ,
        'publisher' ,
        'number_of_pages',
        'book_status_id',
    ];

    public function book_statuses() {
        return $this->hasMany(BookStatus::class);
    }
}
