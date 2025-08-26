<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Letter
 *
 * @property int $id
 * @property int $desa_id
 * @property int $letter_type_id
 * @property int $citizen_id
 * @property int $rt_id
 * @property int $rw_id
 * @property string|null $letter_number
 * @property string $subject
 * @property array|null $form_data
 * @property string $purpose
 * @property string $status
 * @property string $submission_type
 * @property int $created_by
 * @property string|null $notes
 * @property array|null $attachments
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Desa $desa
 * @property-read \App\Models\LetterType $letterType
 * @property-read \App\Models\User $citizen
 * @property-read \App\Models\Rt $rt
 * @property-read \App\Models\Rw $rw
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LetterHistory> $histories
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Letter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Letter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Letter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Letter whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Letter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Letter whereCitizenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Letter pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Letter completed()

 * 
 * @mixin \Eloquent
 */
class Letter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'desa_id',
        'letter_type_id',
        'citizen_id',
        'rt_id',
        'rw_id',
        'letter_number',
        'subject',
        'form_data',
        'purpose',
        'status',
        'submission_type',
        'created_by',
        'notes',
        'attachments',
        'approved_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'form_data' => 'array',
        'attachments' => 'array',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the village that owns this letter.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Get the letter type.
     */
    public function letterType(): BelongsTo
    {
        return $this->belongsTo(LetterType::class);
    }

    /**
     * Get the citizen who requested this letter.
     */
    public function citizen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    /**
     * Get the RT associated with this letter.
     */
    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    /**
     * Get the RW associated with this letter.
     */
    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    /**
     * Get the user who created this letter.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the letter histories.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(LetterHistory::class);
    }

    /**
     * Scope a query to only include pending letters.
     */
    public function scopePending($query)
    {
        return $query->whereNotIn('status', ['selesai', 'rejected']);
    }

    /**
     * Scope a query to only include completed letters.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Generate letter number.
     */
    public function generateLetterNumber(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        
        $lastNumber = static::where('desa_id', $this->desa_id)
            ->whereYear('created_at', $year)
            ->whereNotNull('letter_number')
            ->count() + 1;

        $letterTypeCode = $this->letterType->code;
        $desaCode = $this->desa->code;

        return sprintf('%03d/%s/%s/%d', $lastNumber, $letterTypeCode, $desaCode, $year);
    }

    /**
     * Check if letter can be approved by given user.
     */
    public function canBeApprovedBy(User $user): bool
    {
        switch ($this->status) {
            case 'draft':
                return $user->isKetuaRt() && $user->rt_id === $this->rt_id;
            case 'rt_approved':
                return $user->isKetuaRw() && $user->rw_id === $this->rw_id;
            case 'rw_approved':
            case 'admin_process':
                return $user->isAdminDesa() || $user->isKepala();
            default:
                return false;
        }
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        $colors = [
            'draft' => 'gray',
            'rt_approved' => 'blue',
            'rw_approved' => 'indigo',
            'admin_process' => 'yellow',
            'kepala_desa_approved' => 'green',
            'selesai' => 'green',
            'rejected' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Get human readable status.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'draft' => 'Draft',
            'rt_approved' => 'Disetujui RT',
            'rw_approved' => 'Disetujui RW',
            'admin_process' => 'Dalam Proses Admin',
            'kepala_desa_approved' => 'Disetujui Kepala Desa',
            'selesai' => 'Selesai',
            'rejected' => 'Ditolak',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }
}