<?php

namespace Afterflow\Chisel;

use Afterflow\Chisel\Console\Compose;
use Afterflow\Chisel\Console\Down;
use Afterflow\Chisel\Console\Exec;
use Afterflow\Chisel\Console\Logs;
use Afterflow\Chisel\Console\Ps;
use Afterflow\Chisel\Console\Restart;
use Afterflow\Chisel\Console\Up;
use Afterflow\Chisel\Console\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ChiselServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return  void
     */
    public function register() {


        if ( $this->app->runningInConsole() ) {
            $this->publishes( [
                __DIR__ . '/../config/config.php' => config_path( 'chisel.php' ),
            ], 'config' );
        }

        $this->commands( [
            Up::class,
            Down::class,
            Workspace::class,
            Exec::class,
            Compose::class,
            Ps::class,
            Restart::class,
            Logs::class,
        ] );

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
