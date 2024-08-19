<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Site;
use App\Models\Typology;
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

        User::factory()->create([
            'name' => 'Writer 1',
            'email' => 'writer1@cwebdesign.ro',
            'password' => 'testtest',
            'is_writer' => true,
            'commission' => 10
        ]);

        Location::create([
            'name' => 'Location 1'
        ]);

        Site::create([
            'name' => 'Site 1'
        ]);

        Typology::create([
            'name' => 'Tipologia 1'
        ]);
    }
}
