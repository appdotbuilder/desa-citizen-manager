<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Desa
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $kecamatan
 * @property string $kabupaten
 * @property string $provinsi
 * @property string|null $address
 * @property string|null $postal_code
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property string $status
 * @property array|null $subscription_data
 * @property \Illuminate\Support\Carbon|null $subscription_expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dusun> $dusuns
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rw> $rws
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rt> $rts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Letter> $letters
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\News> $news
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gallery> $galleries
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Desa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Desa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Desa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Desa whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Desa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Desa active()

 * 
 * @mixin \Eloquent
 */
class Desa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'address',
        'postal_code',
        'phone',
        'email',
        'website',
        'status',
        'subscription_data',
        'subscription_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subscription_data' => 'array',
        'subscription_expires_at' => 'datetime',
    ];

    /**
     * Get all users in this village.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all hamlets in this village.
     */
    public function dusuns(): HasMany
    {
        return $this->hasMany(Dusun::class);
    }

    /**
     * Get all RWs in this village.
     */
    public function rws(): HasMany
    {
        return $this->hasMany(Rw::class);
    }

    /**
     * Get all RTs in this village.
     */
    public function rts(): HasMany
    {
        return $this->hasMany(Rt::class);
    }

    /**
     * Get all letters in this village.
     */
    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    /**
     * Get all news in this village.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    /**
     * Get all galleries in this village.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Scope a query to only include active villages.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the full address.
     */
    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address,
            $this->kecamatan,
            $this->kabupaten,
            $this->provinsi,
            $this->postal_code
        ]));
    }
}