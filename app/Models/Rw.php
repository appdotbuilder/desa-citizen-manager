<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Rw
 *
 * @property int $id
 * @property int $desa_id
 * @property int $dusun_id
 * @property string $number
 * @property string|null $name
 * @property int|null $ketua_user_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Desa $desa
 * @property-read \App\Models\Dusun $dusun
 * @property-read \App\Models\User|null $ketua
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rt> $rts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $citizens
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Rw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rw query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rw whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rw whereNumber($value)

 * 
 * @mixin \Eloquent
 */
class Rw extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'desa_id',
        'dusun_id',
        'number',
        'name',
        'ketua_user_id',
        'description',
    ];

    /**
     * Get the village that owns this RW.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Get the hamlet that owns this RW.
     */
    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    /**
     * Get the RW head.
     */
    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_user_id');
    }

    /**
     * Get all RTs in this RW.
     */
    public function rts(): HasMany
    {
        return $this->hasMany(Rt::class);
    }

    /**
     * Get all citizens in this RW.
     */
    public function citizens(): HasMany
    {
        return $this->hasMany(User::class, 'rw_id');
    }

    /**
     * Get the full RW identifier (RW 001).
     */
    public function getFullNameAttribute(): string
    {
        return 'RW ' . $this->number;
    }
}