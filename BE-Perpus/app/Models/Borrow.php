<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Borrow extends Model
{
    use HasFactory, HasUuids;

    protected $table = "borrows";

    protected $fillable = [
        'load_date',
        'barrow_date',
        'book_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function books()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
