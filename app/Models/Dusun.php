<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Dusun
 *
 * @property int $id
 * @property int $desa_id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Desa $desa
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rw> $rws
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Dusun newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dusun newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dusun query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dusun whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dusun whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dusun whereCode($value)

 * 
 * @mixin \Eloquent
 */
class Dusun extends Model
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
    ];

    /**
     * Get the village that owns this hamlet.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Get all RWs in this hamlet.
     */
    public function rws(): HasMany
    {
        return $this->hasMany(Rw::class);
    }
}