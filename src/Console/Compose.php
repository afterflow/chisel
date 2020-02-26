<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Compose extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:compose {cmd : "compose command"} {--i|interactive : For interactive shell}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute docker-compose command as is';

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

        Chisel::exec( $this->argument( 'cmd' ), $this->option( 'interactive' ) );

    }
}
