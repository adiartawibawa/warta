<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class District extends Model
{
    use HasUuids;

    protected $table = 'districts';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'regency_id',
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
     * Get the places for the district.
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }

    /**
     * Get the villages for the district.
     */
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    /**
     * Get the regency that owns the district.
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * Get the province that owns the district.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
