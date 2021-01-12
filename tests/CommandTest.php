<?php

namespace Roles\Test;

use Illuminate\Support\Facades\Artisan;
use Roles\Models\Role;

class CommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_role()
    {
        Artisan::call('roles:create', ['name' => 'new-role']);

        $this->assertCount(1, Role::where('name', 'new-role')->get());
    }

    /** @test */
    public function it_can_create_a_role_with_a_specific_guard()
    {
        Artisan::call('roles:create', [
            'name' => 'new-role',
            'guard' => 'api',
        ]);

        $this->assertCount(1, Role::where('name', 'new-role')
            ->where('guard_name', 'api')
            ->get());
    }

    /** @test */
    public function it_can_create_a_role_without_duplication()
    {
        Artisan::call('roles:create', ['name' => 'new-role']);
        Artisan::call('roles:create', ['name' => 'new-role']);

        $this->assertCount(1, Role::where('name', 'new-role')->get());
    }
}
