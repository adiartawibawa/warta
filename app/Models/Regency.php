<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Regency extends Model
{
    use HasUuids;

    protected $table = 'regencies';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'province_id',
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
     * Get the villages for the regency.
     */
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    /**
     * Get the districts for the regency.
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get the province that owns the regency.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the places for the regency.
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
