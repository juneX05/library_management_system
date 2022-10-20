<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookStatus;

class Book extends Model
{
    use HasFactory;

    public function book_statuses() {
        return $this->hasMany(BookStatus::class);
    }
}
