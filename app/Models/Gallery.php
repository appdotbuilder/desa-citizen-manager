<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Gallery
 *
 * @property int $id
 * @property int $desa_id
 * @property string $title
 * @property string|null $description
 * @property string $category
 * @property array $images
 * @property int $uploaded_by
 * @property \Illuminate\Support\Carbon|null $event_date
 * @property array|null $tags
 * @property bool $is_featured
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Desa $desa
 * @property-read \App\Models\User $uploader
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gallery featured()

 * 
 * @mixin \Eloquent
 */
class Gallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'desa_id',
        'title',
        'description',
        'category',
        'images',
        'uploaded_by',
        'event_date',
        'tags',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'event_date' => 'date',
        'tags' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the village that owns this gallery.
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Get the user who uploaded this gallery.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope a query to only include featured galleries.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        $labels = [
            'activities' => 'Kegiatan',
            'facilities' => 'Fasilitas',
            'development' => 'Pembangunan',
            'events' => 'Acara',
            'others' => 'Lainnya',
        ];

        return $labels[$this->category] ?? 'Unknown';
    }

    /**
     * Get the main image (first image).
     */
    public function getMainImageAttribute(): string|null
    {
        return $this->images[0] ?? null;
    }

    /**
     * Get images count.
     */
    public function getImagesCountAttribute(): int
    {
        return count($this->images ?? []);
    }
}