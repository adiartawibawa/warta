<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Place extends Model
{
    use HasUuids;

    protected $table = 'places';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'zip',
        'category_id',
        'village_id',
        'district_id',
        'regency_id',
        'province_id'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $slug = Str::slug($model->name);
                $count = static::where('slug', 'LIKE', "{$slug}%")->count();
                $model->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });
    }

    /**
     * Get the category that owns the place.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the village that owns the place.
     */
    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Get the district that owns the place.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the regency that owns the place.
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * Get the province that owns the place.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the media for the place.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the full address of the place.
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->village->name}, {$this->district->name}, {$this->regency->name}, {$this->province->name}, {$this->zip}";
    }
}
