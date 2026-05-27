<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reader extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email'];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    public function hasUnpaidFines(): bool
    {
        return $this->fines()->whereNull('paid_at')->exists();
    }
}
