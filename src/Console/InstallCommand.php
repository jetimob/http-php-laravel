<?php

namespace Jetimob\Http\Console;

use Illuminate\Console\Command;
use Jetimob\Http\HttpServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'http:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes Http configuration files';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Publishing Http configuration file.');
        $this->call('vendor:publish', [
            '--provider' => HttpServiceProvider::class,
            '--tag'      => 'config'
        ]);
        $this->output->success('Configuration file published!');
    }
}
