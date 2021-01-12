<?php

namespace Roles\Test;

use Illuminate\Http\Response;

class RouteTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        if (! $this->isVersionAvailable()) {
            $this->markTestSkipped(
                'This feature available for Laravel 5.5 and higher'
            );
        }
    }

    /** @test */
    public function test_role_function()
    {
        $router = $this->getRouter();

        $router->get('role-test', $this->getRouteResponse())
                ->name('role.test')
                ->role('superadmin');

        $this->assertEquals(['role:superadmin'], $this->getLastRouteMiddlewareFromRouter($router));
    }

    protected function isVersionAvailable()
    {
        return app()->version() >= '5.5';
    }

    protected function getLastRouteMiddlewareFromRouter($router)
    {
        return last($router->getRoutes()->get())->middleware();
    }

    protected function getRouter()
    {
        return app('router');
    }

    protected function getRouteResponse()
    {
        return function () {
            return (new Response())->setContent('<html></html>');
        };
    }
}
