<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $desa_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $nik
 * @property string|null $no_kk
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $birth_place
 * @property string|null $gender
 * @property string|null $religion
 * @property string|null $marital_status
 * @property string|null $occupation
 * @property string|null $education
 * @property string|null $address
 * @property int|null $rt_id
 * @property int|null $rw_id
 * @property string|null $phone
 * @property array|null $documents
 * @property string $citizen_status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Desa|null $desa
 * @property-read \App\Models\Rt|null $rt
 * @property-read \App\Models\Rw|null $rw
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Letter> $letters
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Letter> $createdLetters
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\News> $news
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCitizenStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User citizens()
 * @method static \Illuminate\Database\Eloquent\Builder|User officials()

 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'desa_id',
        'name',
        'email',
        'password',
        'role',
        'nik',
        'no_kk',
        'birth_date',
        'birth_place',
        'gender',
        'religion',
        'marital_status',
        'occupation',
        'education',
        'address',
        'rt_id',
        'rw_id',
        'phone',
        'documents',
        'citizen_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'documents' => 'array',
    ];

    /**
     * Get the village this user belongs to.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Get the RT this user belongs to.
     */
    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    /**
     * Get the RW this user belongs to.
     */
    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    /**
     * Get letters submitted by this user.
     */
    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class, 'citizen_id');
    }

    /**
     * Get letters created by this user (for officials).
     */
    public function createdLetters(): HasMany
    {
        return $this->hasMany(Letter::class, 'created_by');
    }

    /**
     * Get news created by this user.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /**
     * Scope a query to only include citizens.
     */
    public function scopeCitizens($query)
    {
        return $query->where('role', 'warga');
    }

    /**
     * Scope a query to only include officials.
     */
    public function scopeOfficials($query)
    {
        return $query->whereIn('role', ['admin_desa', 'kepala_desa', 'ketua_rw', 'ketua_rt']);
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is village admin.
     */
    public function isAdminDesa(): bool
    {
        return $this->role === 'admin_desa';
    }

    /**
     * Check if user is village head.
     */
    public function isKepala(): bool
    {
        return $this->role === 'kepala_desa';
    }

    /**
     * Check if user is RW head.
     */
    public function isKetuaRw(): bool
    {
        return $this->role === 'ketua_rw';
    }

    /**
     * Check if user is RT head.
     */
    public function isKetuaRt(): bool
    {
        return $this->role === 'ketua_rt';
    }

    /**
     * Check if user is a citizen.
     */
    public function isCitizen(): bool
    {
        return $this->role === 'warga';
    }

    /**
     * Get user's full name with title if official.
     */
    public function getFullNameWithTitleAttribute(): string
    {
        $titles = [
            'kepala_desa' => 'Kepala Desa',
            'ketua_rw' => 'Ketua RW',
            'ketua_rt' => 'Ketua RT',
            'admin_desa' => 'Admin Desa',
        ];

        if (isset($titles[$this->role])) {
            return $titles[$this->role] . ' ' . $this->name;
        }

        return $this->name;
    }

    /**
     * Get user's age.
     */
    public function getAgeAttribute(): int|null
    {
        if (!$this->birth_date) {
            return null;
        }

        return (int) $this->birth_date->diffInYears(now());
    }
}