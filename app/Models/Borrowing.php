<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'borrower_name',
        'borrower_email',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
