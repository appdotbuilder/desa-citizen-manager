<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Rt
 *
 * @property int $id
 * @property int $desa_id
 * @property int $rw_id
 * @property string $number
 * @property string|null $name
 * @property int|null $ketua_user_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Desa $desa
 * @property-read \App\Models\Rw $rw
 * @property-read \App\Models\User|null $ketua
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $citizens
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Rt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rt whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rt whereRwId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rt whereNumber($value)

 * 
 * @mixin \Eloquent
 */
class Rt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'desa_id',
        'rw_id',
        'number',
        'name',
        'ketua_user_id',
        'description',
    ];

    /**
     * Get the village that owns this RT.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Get the RW that owns this RT.
     */
    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    /**
     * Get the RT head.
     */
    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_user_id');
    }

    /**
     * Get all citizens in this RT.
     */
    public function citizens(): HasMany
    {
        return $this->hasMany(User::class, 'rt_id');
    }

    /**
     * Get the full RT identifier (RT 001/RW 002).
     */
    public function getFullNameAttribute(): string
    {
        return 'RT ' . $this->number . '/RW ' . $this->rw->number;
    }
}