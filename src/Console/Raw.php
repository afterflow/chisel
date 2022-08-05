<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Afterflow\Chisel\Docker\Docker;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Raw extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:raw {cmd?} {--n|no-interaction : Non-interactive shell}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile docker-compose string and spit it out';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->setHidden( true );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        echo Docker::compile( $this->argument( 'cmd' ),true );
    }
}
