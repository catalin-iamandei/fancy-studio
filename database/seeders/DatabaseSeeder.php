<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('shield:generate --all --ignore-existing-policies');

//        $search = '_shield::';
//        $permissions = Permission::where('name', 'LIKE', '%'.$search.'%')->get();
//        foreach ($permissions as $permission) {
//            $permission->name = str_replace($search, '_', $permission->name);
//            $permission->save();
//        }

        $super_admin_user = User::factory()->create([
            'name' => 'Catalin Iamandei',
            'email' => 'contact@cwebdesign.ro',
            'password' => 'testtest',
        ]);

        $super_admin_user?->assignRole('super_admin');
    }
}
