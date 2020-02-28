<?php

namespace Afterflow\Chisel\Console;

use Illuminate\Console\Command;

class Install extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Chisel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {

        if ( file_exists( base_path( 'docker/docker.php' ) ) ) {
            $this->hidden = true;
        }

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->line( 'Installing Chisel' );
    }
}
