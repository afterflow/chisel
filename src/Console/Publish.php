<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Afterflow\Chisel\Docker\Docker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Publish extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:publish {service? : Service name, e.g. mysql}';

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

        //        if ( file_exists( base_path( 'docker/docker.php' ) ) ) {
        //            $this->hidden = true;
        //        }

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $services = Docker::load();

        if ( ! $name = $this->argument( 'service' ) ) {

            $name = $this->choice( 'Services with registered fixtures:', collect( $services )->filter( function ( $v ) {
                return method_exists( $v, 'fixture' );
            } )->keys()->toArray() );

        }

        $this->line( 'Publishing fixtures from ' . $name );


        $service = $services[ $name ];

        if ( ! method_exists( $service, 'fixture' ) ) {
            throw new \Exception( 'Service does not have fixtures to publish' );
        }

        $localPath = base_path( 'docker/' . $name );
        if ( file_exists( $localPath ) ) {
            if ( ! $this->confirm( 'docker/' . $name . ' exists. Overwrite?' ) ) {
                return;
            }
            File::deleteDirectory( $localPath );
        }

        $path = $service->fixture();

        File::makeDirectory( $localPath, 0777, true );
        File::copyDirectory( $path, $localPath );
        $this->line( 'Fixtures published to <comment>docker/' . $name . '</comment>.' );
        $this->line( 'Service ' . $name . ' will now use them by default.' );

    }
}
