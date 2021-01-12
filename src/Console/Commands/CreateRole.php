<?php

namespace Roles\Console\Commands;

use Roles\Facades\Role;
use Illuminate\Console\Command;

class CreateRole extends Command
{
    protected $signature = 'roles:create
        {name : The name of the role}
        {guard? : The name of the guard}';

    protected $description = 'Create a role';

    public function handle()
    {
        $role = Role::findOrCreate($this->argument('name'), $this->argument('guard'));

        $this->info("Role `{$role->name}` created");
    }
}
