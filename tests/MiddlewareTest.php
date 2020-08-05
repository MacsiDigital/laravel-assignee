<?php

namespace Assignee\Test;

use Assignee\Exceptions\UnauthorizedException;
use Assignee\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MiddlewareTest extends TestCase
{
    protected $roleMiddleware;

    public function setUp() : void
    {
        parent::setUp();

        $this->roleMiddleware = new RoleMiddleware($this->app);
    }

    /** @test */
    public function a_guest_cannot_access_a_route_protected_by_rolemiddleware()
    {
        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware,
                'testRole'
            ),
            403
        );
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_role_middleware_if_have_this_role()
    {
        Auth::login($this->testUser);

        $this->testUser->assignRole('testRole');

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware,
                'testRole'
            ),
            200
        );
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_this_role_middleware_if_have_one_of_the_roles()
    {
        Auth::login($this->testUser);

        $this->testUser->assignRole('testRole');

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware,
                'testRole|testRole2'
            ),
            200
        );

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware,
                ['testRole2', 'testRole']
            ),
            200
        );
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_the_role_middleware_if_have_a_different_role()
    {
        Auth::login($this->testUser);

        $this->testUser->assignRole(['testRole']);

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware,
                'testRole2'
            ),
            403
        );
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_role_middleware_if_have_not_roles()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware,
                'testRole|testRole2'
            ),
            403
        );
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_role_middleware_if_role_is_undefined()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware,
                ''
            ),
            403
        );
    }

    /** @test */
    public function the_required_roles_can_be_fetched_from_the_exception()
    {
        Auth::login($this->testUser);

        $requiredRoles = [];

        try {
            $this->roleMiddleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, 'some-role');
        } catch (UnauthorizedException $e) {
            $requiredRoles = $e->getRequiredRoles();
        }

        $this->assertEquals(['some-role'], $requiredRoles);
    }

    protected function runMiddleware($middleware, $parameter)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, $parameter)->status();
        } catch (UnauthorizedException $e) {
            return $e->getStatusCode();
        }
    }
}
