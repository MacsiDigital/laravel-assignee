<?php

namespace Roles\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the role resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Role Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'roles-migrations']);

        $this->comment('Publishing Role Config...');
        $this->callSilent('vendor:publish', ['--tag' => 'roles-config']);

        $this->info('Role scaffolding installed successfully.');
    }
}
