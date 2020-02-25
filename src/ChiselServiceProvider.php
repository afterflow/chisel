<?php

namespace Afterflow\Chisel;

use Afterflow\Chisel\Console\Down;
use Afterflow\Chisel\Console\Up;
use Afterflow\Chisel\Console\Workspace;
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

        $this->commands( [ Up::class, Down::class, Workspace::class ] );

    }

    /**
     * Bootstrap services.
     *
     * @return  void
     */
    public function boot() {
        $this->mergeConfigFrom( __DIR__ . '/../config/config.php', 'chisel' );
    }
}
