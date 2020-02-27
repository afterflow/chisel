<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Exec extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:exec {container : "e.g. caddy"} {cmd : "linux command"} {--n|no-interaction : Non-interactive shell}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute docker-compose exec command on container';

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
        Chisel::exec( 'exec ' . ( $this->option( 'no-interaction' ) ? '-T ' : '' ) . $this->argument( 'container' ) . ' ' . $this->argument( 'cmd' ), $this->option( 'no-interaction' ) );
    }
}
