<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Down extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:down';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        Chisel::exec( 'down' );

    }
}
