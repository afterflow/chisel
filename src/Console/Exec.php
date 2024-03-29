<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Afterflow\Chisel\Docker\Docker;
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

        $exec = 'exec';


        $no_interaction = $this->option( 'no-interaction' );

        if ( $no_interaction ) {
            $exec .= ' -T';
        }

        $command   = $this->argument( 'cmd' );
        $container = $this->argument( 'container' );
        $resolved  = app( 'docker' )->getService( $container )->shortcut( $command );

        if ( $resolved == $command ) {
            $resolved2 = app( 'docker' )->getService( $container )->shortcut( 'tty:' . $command );
            if ( ( $resolved2 != $command ) && ( $resolved2 != ( 'tty:' . $command ) ) ) {
                $no_interaction = false;
                $resolved       = $resolved2;
            } else {
            }
        }
        $exec .= ' ' . $container . ' ' . $resolved;

//        dump($exec);
        Docker::exec( $exec, $no_interaction );
    }
}
