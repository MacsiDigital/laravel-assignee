<?php

namespace Assignee\Test;

use Assignee\Models\Role;
use Illuminate\Support\Facades\Artisan;

class CommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_role()
    {
        Artisan::call('assignee:create-role', ['name' => 'new-role']);

        $this->assertCount(1, Role::where('name', 'new-role')->get());
    }

    /** @test */
    public function it_can_create_a_role_with_a_specific_guard()
    {
        Artisan::call('assignee:create-role', [
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
        Artisan::call('assignee:create-role', ['name' => 'new-role']);
        Artisan::call('assignee:create-role', ['name' => 'new-role']);

        $this->assertCount(1, Role::where('name', 'new-role')->get());
    }
}
