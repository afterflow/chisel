<?php


namespace Afterflow\Chisel\Docker;


use Afterflow\Chisel\Docker\Services\Service;
use Afterflow\Chisel\Docker\Services\Workspace\Workspace;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class Docker {

    protected $networks = [];
    protected $services = [];

    public static function load() {
        self::requireConfiguration();

        return app( 'docker' )->services();
    }

    protected static function requireConfiguration(): void {
        if ( file_exists( base_path( 'docker/docker.php' ) ) ) {
            require base_path( 'docker/docker.php' );
        } else {
            require __DIR__ . '/../../docker/docker.php';
        }
    }

    /**
     * @return string
     */
    protected static function generateBaseCommand(): string {
        //        return 'docker-compose --project-dir ' . base_path() . ' -f ' . base_path( 'docker-compose.yml' );
        return 'docker-compose --project-dir ' . base_path();
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

        file_put_contents( base_path( 'docker-compose.yml' ), $yaml );

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

    public function network( $network, $driver = 'bridge' ) {
        $this->networks [ $network ] = [
            'driver' => $driver,
        ];
    }

    public function workspace() {
        $service = Workspace::make();
        $this->service( 'workspace', $service );

        return $service;
    }

    public function services() {
        return $this->services;
    }

    public function image( string $name, string $image ) {
        return $this->service( $name, Service::make( $image ) );

    }

    public function service( string $name, $service = null ) {
        if ( ! $service ) {
            return $this->services[ $name ];
        }

        if ( ! $service instanceof Service ) {
            $service = new $service();
        }

        $this->services[ $name ] = $service->name( $name );

        return $service;
    }

    public function toCompose() {

        $compose = [
            'version'  => '3',
            'networks' => $this->networks,
            'services' => collect( $this->services )->map->toCompose()->toArray(),
        ];

        return $compose;
    }
}
