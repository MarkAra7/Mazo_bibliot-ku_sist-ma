<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 'reader_id', 'reserved_at',
        'status', 'fulfilled_by_borrowing_id', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'reserved_at' => 'date',
            'cancelled_at' => 'date',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function reader(): BelongsTo
    {
        return $this->belongsTo(Reader::class);
    }

    public function fulfilledByBorrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'fulfilled_by_borrowing_id');
    }
}
