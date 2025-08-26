<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\LetterHistory
 *
 * @property int $id
 * @property int $letter_id
 * @property string $status_from
 * @property string $status_to
 * @property int $changed_by
 * @property string|null $notes
 * @property array|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Letter $letter
 * @property-read \App\Models\User $changedBy
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHistory whereLetterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHistory whereChangedBy($value)

 * 
 * @mixin \Eloquent
 */
class LetterHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'letter_id',
        'status_from',
        'status_to',
        'changed_by',
        'notes',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the letter that owns this history.
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    /**
     * Get the user who changed the status.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}