<?php


namespace Afterflow\Chisel;


use Afterflow\Chisel\Docker\Docker;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class Chisel {

    public static function load() {
        self::requireConfiguration();

        return app( 'docker' )->services();
    }

    public static function exec( $subcommand, $noInteraction = false ) {

        static::load();

        // Build compose file

        /**
         * @var $docker Docker
         */
        $docker = app( 'docker' );

        $compose = $docker->toCompose();
        $yaml    = Yaml::dump( $compose, 10, 2 );

        file_put_contents( storage_path( 'framework/chisel-docker-compose.yml' ), $yaml );

        // Build command
        $base_command = self::generateBaseCommand();
        $command      = $base_command . ' ' . $subcommand;

        if ( config( 'chisel.sudo' ) ) {
            $command = 'sudo ' . $command;
        }

        $p = Process::fromShellCommandline( $command, base_path() )
                    ->setTimeout( 900000 )
                    ->setTty( ! $noInteraction && Process::isTtySupported() );
        $p->run( function ( $o, $e ) {
            // only works without TTY
            echo( $e );
        } );

    }

    protected static function requireConfiguration(): void {
        if ( file_exists( base_path( 'docker/docker.php' ) ) ) {
            require base_path( 'docker/docker.php' );
        } else {
            require __DIR__ . '/../docker/docker.php';
        }
    }

    /**
     * @return string
     */
    protected static function generateBaseCommand(): string {
        return 'docker-compose --project-dir ' . base_path() . ' -f ' . storage_path( 'framework/chisel-docker-compose.yml' );
    }

}
