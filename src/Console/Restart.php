<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Restart extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart all services';

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

        Chisel::exec( 'exec down' );
        Chisel::up();

    }
}
