<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YandexOrganization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'yandex_url',
        'organization_id',
        'rating',
        'total_reviews',
        'last_synced_at',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'total_reviews' => 'integer',
        'last_synced_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'organization_id');
    }
}
