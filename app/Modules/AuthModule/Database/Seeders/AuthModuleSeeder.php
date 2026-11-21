<?php

namespace App\Modules\AuthModule\Database\Seeders;

use App\Modules\AuthModule\Enums\Role;
use App\Modules\AuthModule\Models\User;
use Illuminate\Database\Seeder;

class AuthModuleSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@gudang.test'],
            ['name' => 'Administrator', 'password' => 'password', 'role' => Role::Admin],
        );

        User::query()->updateOrCreate(
            ['email' => 'staff@gudang.test'],
            ['name' => 'Staff Gudang', 'password' => 'password', 'role' => Role::Staff],
        );
    }
}
