<?php

namespace Assignee\Test;

use Assignee\Contracts\Role;
use Assignee\Exceptions\RoleAlreadyExists;
use Assignee\Exceptions\RoleDoesNotExist;

class RoleTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
    }

    /** @test */
    public function it_has_user_models_of_the_right_class()
    {
        $this->testAdmin->assignRole($this->testAdminRole);

        $this->testUser->assignRole($this->testUserRole);

        $this->assertCount(1, $this->testUserRole->users);
        $this->assertTrue($this->testUserRole->users->first()->is($this->testUser));
        $this->assertInstanceOf(User::class, $this->testUserRole->users->first());
    }

    /** @test */
    public function it_throws_an_exception_when_the_role_already_exists()
    {
        $this->expectException(RoleAlreadyExists::class);

        app(Role::class)->create(['name' => 'test-role']);
        app(Role::class)->create(['name' => 'test-role']);
    }

    /** @test */
    public function it_creates_a_role_with_findOrCreate_if_the_named_role_does_not_exist()
    {
        $this->expectException(RoleDoesNotExist::class);

        $role1 = app(Role::class)->findByName('non-existing-role');

        $this->assertNull($role1);

        $role2 = app(Role::class)->findOrCreate('yet-another-role');

        $this->assertInstanceOf(Role::class, $role2);
    }

    /** @test */
    public function it_belongs_to_a_guard()
    {
        $role = app(Role::class)->create(['name' => 'admin', 'guard_name' => 'admin']);

        $this->assertEquals('admin', $role->guard_name);
    }

    /** @test */
    public function it_belongs_to_the_default_guard_by_default()
    {
        $this->assertEquals($this->app['config']->get('auth.defaults.guard'), $this->testUserRole->guard_name);
    }
}
