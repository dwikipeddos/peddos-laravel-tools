<?php

namespace Dwikipeddos\PeddosLaravelTools\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdatePermissionRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peddos-permission-role:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync role permissions based on the config file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $permissions = config('PeddosPermissionConfig.available_permissions');

        $allPermissions = Permission::all();
        $allRoles = Role::all();

        foreach ($permissions as $permission) {
            $permissionModel = $allPermissions->where('name', $permission['name'])->first();
            if (!$permissionModel) {
                $this->line('permission not found, creating permission ' . $permission['name'] . '...');
                $permissionModel = Permission::create(['name' => $permission['name']]);
                $this->line('permission created!');
            }
            $roles = $permission['roles'];
            $roleModels = $allRoles->whereIn('name', $roles)->pluck('name')->toArray();
            if ($roleModels == null) {
                $this->warn('role is not found when searching for permission ' . $permission['name']);
            }
            $this->line("syncing role for permission " . $permissionModel->name);
            $permissionModel->syncRoles($roleModels);
        }

        $this->info('Roles and permissions synced successfully!');
        return Command::SUCCESS;
    }
}
