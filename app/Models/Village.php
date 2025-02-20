<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Village extends Model
{
    use HasUuids;

    protected $table = 'villages';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'district_id',
        'coordinates',
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
     * Get the district that owns the village.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the regency that owns the village.
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * Get the province that owns the village.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the places for the village.
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
