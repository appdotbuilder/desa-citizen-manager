<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\LetterType
 *
 * @property int $id
 * @property int $desa_id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property array|null $required_fields
 * @property string|null $template
 * @property bool $is_active
 * @property float $fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Desa $desa
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Letter> $letters
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|LetterType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterType whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LetterType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LetterType active()

 * 
 * @mixin \Eloquent
 */
class LetterType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'desa_id',
        'name',
        'code',
        'description',
        'required_fields',
        'template',
        'is_active',
        'fee',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'required_fields' => 'array',
        'is_active' => 'boolean',
        'fee' => 'decimal:2',
    ];

    /**
     * Get the village that owns this letter type.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Get all letters of this type.
     */
    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    /**
     * Scope a query to only include active letter types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}