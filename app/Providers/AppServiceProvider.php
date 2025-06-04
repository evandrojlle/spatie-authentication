<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\ServiceProvider;
use Dotenv\Dotenv;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (file_exists(base_path('.env'))) {
            Dotenv::createImmutable(base_path())->load();
        }

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        Permission::create(['name' => 'manage tenants']);
        Permission::create(['name' => 'view resources']);

        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo('manage tenants');
    }
}
