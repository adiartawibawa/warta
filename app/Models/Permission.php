<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permission extends Model
{
    use HasUuids;

    protected $table = 'permissions';

    protected $fillable = ['name', 'slug', 'description'];

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
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign the given role to the permission.
     *
     * @param  \App\Models\Role  $role
     * @return $this
     */
    public function assignRole(Role $role)
    {
        $this->roles()->save($role);

        return $this;
    }

    /**
     * Remove the given role from the permission.
     *
     * @param  \App\Models\Role  $role
     * @return $this
     */
    public function removeRole(Role $role)
    {
        $this->roles()->detach($role);

        return $this;
    }

    /**
     * Determine if the permission has the given role.
     *
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function hasRole(Role $role)
    {
        return $this->roles->contains($role);
    }

    /**
     * Determine if the permission has the given role by slug.
     *
     * @param  string  $slug
     * @return bool
     */
    public function hasRoleSlug(string $slug)
    {
        return $this->roles->contains('slug', $slug);
    }

    /**
     * Determine if the permission has the given role by name.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasRoleName(string $name)
    {
        return $this->roles->contains('name', $name);
    }

    /**
     * Determine if the permission has the given role by id.
     *
     * @param  string  $id
     * @return bool
     */
    public function hasRoleId(string $id)
    {
        return $this->roles->contains('id', $id);
    }

    /**
     * Determine if the permission has any of the given roles.
     *
     * @param  \App\Models\Role[]|string[]  $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        return $this->roles->contains('name', $roles);
    }

    /**
     * Determine if the permission has all of the given roles.
     *
     * @param  \App\Models\Role[]|string[]  $roles
     * @return bool
     */
    public function hasAllRoles($roles)
    {
        return $this->roles->contains('name', $roles);
    }
}
