<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    protected static ?string $password;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = $this->command->ask('Enter roles (comma separated)', 'super-admin,admin,user');
        $rolesArray = explode(',', $roles);

        foreach ($rolesArray as $role) {
            Role::create(['name' => trim($role)]);
        }

        $permissions = [
            'manage users',
            'manage roles',
            'manage permissions'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $userCount = $this->command->ask('How many users would you like to create?', 10);

        $superAdminUser = User::factory()->create([
            'name' => 'Adi Arta Wibawa',
            'email' => 'surat.buat.adi@gmail.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $superAdminUser->assignRole(Role::where('name', 'super-admin')->first());

        for ($i = 1; $i < $userCount; $i++) {
            $user = User::factory()->create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ]);

            $randomRole = Role::where('name', '!=', 'super-admin')->inRandomOrder()->first();
            $user->assignRole($randomRole);
        }

        $this->command->info('Super Admin User Details:');
        $this->command->info('Name: ' . $superAdminUser->name);
        $this->command->info('Email: ' . $superAdminUser->email);
    }
}
