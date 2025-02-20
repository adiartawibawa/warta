<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Province extends Model
{
    use HasUuids;

    protected $table = 'provinces';

    protected $fillable = [
        'name',
        'slug',
        'description',
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
     * Get the districts for the province.
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get the regencies for the province.
     */
    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

    /**
     * Get the villages for the province.
     */
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    /**
     * Get the places for the province.
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
