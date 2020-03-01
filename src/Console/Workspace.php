<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Workspace extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:workspace {--root}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a bash session on the workspace';

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

        Chisel::exec( 'exec --user ' . ( $this->option( 'root' ) ? 'root' : 'laradock' ) . ' workspace bash', false );

        //        `$compiledString`;

    }
}
