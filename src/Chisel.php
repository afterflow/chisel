<?php


namespace Afterflow\Chisel;


use Afterflow\Chisel\Docker\Docker;
use Symfony\Component\Yaml\Yaml;

class Chisel {

    public static function load() {
        self::requireConfiguration();
        return app('docker')->services();
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
        $command      = array_merge( $base_command, ShellCommand::fromString( $subcommand )->getCommand() );

        return ( new ShellCommand( $command ) )->exec( ! $noInteraction );

    }

    protected static function requireConfiguration(): void {
        if ( file_exists( base_path( 'docker/docker.php' ) ) ) {
            require base_path( 'docker/docker.php' );
        } else {
            require __DIR__ . '/../docker/docker.php';
        }
    }

    /**
     * @return array
     */
    protected static function generateBaseCommand(): array {
        $command = [
            '/usr/bin/sudo',
            'docker-compose',
            '--project-dir',
            base_path(),
            '-f',
            storage_path( 'framework/chisel-docker-compose.yml' ),
        ];

        return $command;
    }

}
