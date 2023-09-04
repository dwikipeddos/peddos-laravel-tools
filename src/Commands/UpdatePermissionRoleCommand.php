<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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
     * All Roles in database
     * 
     * @var Collection
     */
    protected $allRoles;

    /**
     * All permission in database
     * 
     * @var Collection
     */
    protected $allPermissions;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $permissions = config('peddoslaraveltools.available_permissions');

        $this->allPermissions = Permission::all();
        $this->allRoles = Role::all();

        $this->insertMissingRoles($permissions);

        //refresh roles
        $this->allRoles = Role::all();

        foreach ($permissions as $permission) {
            $permissionModel = $this->allPermissions->where('name', $permission['name'])->first();
            if (!$permissionModel) {
                $this->line('permission not found, creating permission ' . $permission['name'] . '...');
                $permissionModel = Permission::create(['name' => $permission['name']]);
                $this->line('permission created!');
            }
            $roles = $permission['roles'];
            $roleModels = $this->allRoles->whereIn('name', $roles)->pluck('name')->toArray();
            if ($roleModels == null) {
                $this->warn('role is not found when searching for permission ' . $permission['name']);
            }
            $this->line("syncing role for permission " . $permissionModel->name);
            $permissionModel->syncRoles($roleModels);
        }

        $this->info('Roles and permissions synced successfully!');
        return Command::SUCCESS;
    }

    function insertMissingRoles(array $configRoles): void
    {
        $missingRoles = collect($configRoles)->pluck('roles')->flatten()->unique()->diff($this->allRoles->pluck('name'));
        if ($missingRoles->count() > 0) {
            $newRoles = [];
            foreach ($missingRoles as $missingRole) {
                $this->line('found new role ' . $missingRole);
                $newRoles[] = [
                    'name' => $missingRole,
                    'guard_name' => 'api',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Role::insert($newRoles);
            $this->info("added new " . count($newRoles) . " roles");
        }
    }
}
