<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Afterflow\Chisel\Docker\Docker;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Logs extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:logs {container}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'See logs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        Docker::exec( 'logs ' . $this->argument( 'container' ) );

    }
}
