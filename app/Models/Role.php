<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasUuids;

    protected $table = 'roles';

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
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Assign the given permission to the role.
     *
     * @param  \App\Models\Permission  $permission
     * @return $this
     */
    public function assignPermission(Permission $permission)
    {
        $this->permissions()->save($permission);

        return $this;
    }

    /**
     * Remove the given permission from the role.
     *
     * @param  \App\Models\Permission  $permission
     * @return $this
     */
    public function removePermission(Permission $permission)
    {
        $this->permissions()->detach($permission);

        return $this;
    }

    /**
     * Determine if the role has the given permission.
     *
     * @param  \App\Models\Permission  $permission
     * @return bool
     */
    public function hasPermission(Permission $permission)
    {
        return $this->permissions->contains($permission);
    }

    /**
     * Determine if the role has the given permission by name.
     *
     * @param  string  $permissionName
     * @return bool
     */
    public function hasPermissionTo($permissionName)
    {
        return $this->permissions->contains('name', $permissionName);
    }

    /**
     * Determine if the role has the given permission by slug.
     *
     * @param  string  $permissionSlug
     * @return bool
     */
    public function hasPermissionToSlug($permissionSlug)
    {
        return $this->permissions->contains('slug', $permissionSlug);
    }

    /**
     * Determine if the role has any of the given permissions.
     *
     * @param  \App\Models\Permission[]|string[]  $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions)
    {
        return $this->permissions->contains('name', $permissions);
    }

    /**
     * Determine if the role has all of the given permissions.
     *
     * @param  \App\Models\Permission[]|string[]  $permissions
     * @return bool
     */
    public function hasAllPermissions($permissions)
    {
        return $this->permissions->contains('name', $permissions);
    }

    /**
     * Determine if the role has any of the given permissions by slug.
     *
     * @param  \App\Models\Permission[]|string[]  $permissions
     * @return bool
     */
    public function hasAnyPermissionToSlug($permissions)
    {
        return $this->permissions->contains('slug', $permissions);
    }

    /**
     * Determine if the role has all of the given permissions by slug.
     *
     * @param  \App\Models\Permission[]|string[]  $permissions
     * @return bool
     */
    public function hasAllPermissionsToSlug($permissions)
    {
        return $this->permissions->contains('slug', $permissions);
    }
}
