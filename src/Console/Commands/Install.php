<?php

namespace Assignee\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignee:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Assignee resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Assignee Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'assignee-migrations']);

        $this->comment('Publishing Assignee Config...');
        $this->callSilent('vendor:publish', ['--tag' => 'assignee-config']);

        $this->info('Assignee scaffolding installed successfully.');
    }
}
