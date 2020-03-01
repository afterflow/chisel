<?php

namespace Afterflow\Chisel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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


        if ( file_exists( base_path( 'docker/docker.php' ) ) ) {
            if ( ! $this->confirm( 'Overwrite docker/docker.php?' ) ) {
                return;
            }
        }

        $this->line( 'Copying docker config to <comment>docker/docker.php</comment>...' );

        if ( ! file_exists( base_path( 'docker' ) ) ) {
            File::makeDirectory( base_path( 'docker' ), 0777 );
        }

        File::copy( __DIR__ . '/../../docker/docker.php', base_path( 'docker/docker.php' ) );

        $this->info( 'Done!' );

        if ( $this->confirm( 'Publish service fixtures?' ) ) {

            $this->call( Publish::class );

        }

    }
}
