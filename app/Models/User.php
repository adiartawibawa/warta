<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user avatar url from storage or get the default avatar by gravatar.
     */
    public function getAvatarAttribute($value)
    {
        return $value ? asset('storage/' . $value) : 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email)) . '?d=mp';
    }

    /**
     * Get the issues that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issues()
    {
        return $this->hasMany(Issue::class, 'assignee_id');
    }

    /**
     * Get the issues that belong to the user as a reporter.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reportedIssues()
    {
        return $this->hasMany(Issue::class, 'reporter_id');
    }

    /**
     * Get the votes that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the roles that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function hasRole(Role $role)
    {
        return $this->roles->contains($role);
    }

    /**
     * Assign the given role to the user.
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
     * Remove the given role from the user.
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
     * Determine if the user has the given role by slug.
     *
     * @param  string  $slug
     * @return bool
     */
    public function hasRoleSlug(string $slug)
    {
        return $this->roles->contains('slug', $slug);
    }

    /**
     * Determine if the user has the given role by name.
     *
     * @param  string  $roleName
     * @return bool
     */
    public function hasRoleName(string $roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    /**
     * Determine if the user has the given permission.
     *
     * @param  \App\Models\Permission  $permission
     * @return bool
     */
    public function hasPermission(Permission $permission)
    {
        return $this->roles->flatMap->permissions->contains($permission);
    }

    /**
     * Determine if the user has the given permission by name.
     *
     * @param  string  $permissionName
     * @return bool
     */
    public function hasPermissionTo(string $permissionName)
    {
        return $this->roles->flatMap->permissions->contains('name', $permissionName);
    }

    /**
     * Determine if the user has the given permission by slug.
     *
     * @param  string  $permissionSlug
     * @return bool
     */
    public function hasPermissionToSlug(string $permissionSlug)
    {
        return $this->roles->flatMap->permissions->contains('slug', $permissionSlug);
    }

    /**
     * Determine if the user has any of the given permissions.
     *
     * @param  \App\Models\Permission[]|string[]  $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions)
    {
        return $this->roles->flatMap->permissions->contains('name', $permissions);
    }

    /**
     * Determine if the user has all of the given permissions.
     *
     * @param  \App\Models\Permission[]|string[]  $permissions
     * @return bool
     */
    public function hasAllPermissions($permissions)
    {
        return $this->roles->flatMap->permissions->contains('name', $permissions);
    }

    /**
     * Determine if the user has the given role by id.
     *
     * @param  string  $id
     * @return bool
     */
    public function hasRoleId(string $id)
    {
        return $this->roles->contains('id', $id);
    }

    /**
     * Determine if the user has any of the given roles.
     *
     * @param  \App\Models\Role[]|string[]  $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        return $this->roles->contains('name', $roles);
    }

    /**
     * Determine if the user has all of the given roles.
     *
     * @param  \App\Models\Role[]|string[]  $roles
     * @return bool
     */
    public function hasAllRoles($roles)
    {
        return $this->roles->contains('name', $roles);
    }

    /**
     * Determine if the user has any of the given roles by slug.
     *
     * @param  string[]  $slugs
     * @return bool
     */
    public function hasAnyRoleSlug(array $slugs)
    {
        return $this->roles->contains('slug', $slugs);
    }
}
