<?php

namespace Roles\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Roles\Console\Commands\CreateRole;
use Roles\Console\Commands\Install;
use Roles\Contracts\Package as PackageContract;
use Roles\Contracts\Role as RoleContract;
use Roles\Http\Middleware\RoleMiddleware;
use Roles\Package;

class RolesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerMiddleware();
        $this->registerMacroHelpers();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config.php' => config_path('roles.php'),
            ], 'roles-config');

            $this->publishes($this->getNewMigrations(), 'roles-migrations');

            $this->commands([
                CreateRole::class,
                Install::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php',
            'roles'
        );

        $this->registerModelBindings();

        $this->registerBladeExtensions();
    }

    protected function registerModelBindings()
    {
        $this->app->bind('roles', PackageContract::class);
        $this->app->bind(PackageContract::class, Package::class);

        $this->app->bind('roles.role', RoleContract::class);
        $this->app->bind(RoleContract::class, config('roles.models.role'));
    }

    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('role', function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('elserole', function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('endrole', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('hasrole', function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('endhasrole', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('hasanyrole', function ($arguments) {
                list($roles, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAnyRole({$roles})): ?>";
            });
            $bladeCompiler->directive('endhasanyrole', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('hasallroles', function ($arguments) {
                list($roles, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAllRoles({$roles})): ?>";
            });
            $bladeCompiler->directive('endhasallroles', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('unlessrole', function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                return "<?php if(!auth({$guard})->check() || ! auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('endunlessrole', function () {
                return '<?php endif; ?>';
            });
        });
    }

    protected function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware(config('roles.middleware_key'), RoleMiddleware::class);
    }

    protected function registerMacroHelpers()
    {
        Route::macro('role', function ($roles = []) {
            if (! is_array($roles)) {
                $roles = [$roles];
            }

            $roles = implode('|', $roles);

            $this->middleware("role:$roles");

            return $this;
        });
    }

    protected function getNewMigrations()
    {
        $global_migrations = collect((new Filesystem)->files(database_path('/migrations')));

        $migrations = [];
        foreach ((new Filesystem)->files(__DIR__.'/../../database/migrations') as $migration) {
            if (! $global_migrations->contains(function ($value, $key) use ($migration) {
                if (Str::contains($value->getRelativePathname(), $migration->getFilenameWithoutExtension())) {
                    return true;
                }
            })) {
                $migrations[__DIR__.'/../../database/migrations/'.$migration->getRelativePathname()] = database_path('migrations/'.date('Y_m_d_His').'_'.$migration->getFilenameWithoutExtension());
            }
        }

        return $migrations;
    }
}
