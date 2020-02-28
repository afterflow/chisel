<?php

namespace Afterflow\Chisel;

use Afterflow\Chisel\Console\Compose;
use Afterflow\Chisel\Console\Down;
use Afterflow\Chisel\Console\Exec;
use Afterflow\Chisel\Console\Install;
use Afterflow\Chisel\Console\Logs;
use Afterflow\Chisel\Console\Ps;
use Afterflow\Chisel\Console\Publish;
use Afterflow\Chisel\Console\Raw;
use Afterflow\Chisel\Console\Restart;
use Afterflow\Chisel\Console\Up;
use Afterflow\Chisel\Console\Workspace;
use Afterflow\Chisel\Docker\Docker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ChiselServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return  void
     */
    public function register() {

        $this->app->singleton( 'docker', function () {
            return $this->app->make( Docker::class );
        } );


    }

    public function runningInDocker() {

        return @file_exists( '/.dockerenv' );
    }

    /**
     * Bootstrap services.
     *
     * @return  void
     */
    public function boot() {

        if ( $this->app->runningInConsole() ) {
            $this->publishes( [
                __DIR__ . '/../config/config.php' => config_path( 'chisel.php' ),
            ], 'config' );
        }

        $this->commands( [
            Workspace::class,
            Publish::class,
            Compose::class,
            Restart::class,
            Install::class,
            Exec::class,
            Down::class,
            Logs::class,
            Raw::class,
            Up::class,
            Ps::class,
        ] );

        $this->mergeConfigFrom( __DIR__ . '/../config/config.php', 'chisel' );

        $ovi = config( 'chisel.database_connection_override' );

        if ( $this->runningInDocker() && $ovi ) {
            $conn = DB::getDefaultConnection();
            $cn   = config( 'database.connections.' . $conn );

            $new = array_replace( $cn, $ovi );

            config( [ 'database.connections.chisel' => $new ] );
            DB::setDefaultConnection( 'chisel' );
        }

    }
}
