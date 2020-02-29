<?php


namespace Afterflow\Chisel\Docker\Services\Concerns;


trait BuildsDockerComposeService {

    /**
     * Name for the service.
     * @var string
     */
    protected $name;

    /**
     * Volume definiton array, host => container
     * @example [ '/work/project/logs/caddy' => '/var/log/caddy' ]
     * @var array
     */
    protected $volumes = [];

    /**
     * Port mapping array, host => container
     * @example [ 8080 => 80 ]
     * @var array
     */
    protected $ports = [];

    /**
     * Ports to expose
     * @example [ 8080, 3306 ]
     * @var array
     */
    protected $expose = [];

    /**
     * Environment variables, key => value
     * @example [ 'MYSQL_PORT' => 3306 ]
     * @var array
     */
    protected $env = [];

    /**
     * Networks to attach to.
     * @example [ 'frontend', 'backend' ]
     * @var array
     */
    protected $networks = [];

    /**
     * Array of container's dependencies.
     * @example [ 'php-fpm', 'mysql' ]
     * @var array
     */
    protected $depends = [];

    /**
     * Container restart policy.
     * @example 'always'
     * @example 'never'
     * @var string
     */
    protected $restart;

    /**
     *  raw compose options not included with this class.
     * @example [ 'tty' => true ]
     * @var array
     */
    protected $custom = [];

    /**
     * Build from image. Not compatible with $context
     * @example mysql:8.0
     * @var string
     */
    protected $image;

    /**
     * Build from context. Not compatible with $image
     * @example './fixtures/build'
     * @var string
     */
    protected $context;

    /**
     * Foreground command
     * @example /usr/bin/caddy
     * @example ["--conf", "/etc/caddy/Caddyfile", "--log", "stdout", "--agree=true"]
     * @var string|array
     */
    protected $command;

    //
    public function toArray() {
        return $this->mapOptions( [], [
            'image',
            'networks',
            'restart',
            'expose',
            'command',
            'depends',
            'env',
            'ports',
            'networks',
            'volumes',
        ] );
    }

    public function toCompose() {

        $compose = [];

        if ( $this->image ) {
            $compose[ 'image' ] = $this->image;
        } elseif ( file_exists( $this->fixture( 'Dockerfile' ) ) ) {
            $compose[ 'build' ] = [ 'context' => $this->fixture() ];
        }
        if ( $this->env ) {
            $compose[ 'environment' ] = $this->concat( $this->env, '=' );
        }

        if ( $this->ports ) {
            $compose[ 'ports' ] = $this->concat( $this->ports );
        }

        if ( $this->volumes ) {
            $compose[ 'volumes' ] = $this->concat( $this->volumes );
        }


        if ( $this->depends ) {
            $compose[ 'depends_on' ] = $this->depends;
        }

        $compose = $this->mapOptions( $compose, [
            'networks',
            'restart',
            'expose',
            'command',
        ] );

        $compose = array_replace( $compose, $this->custom );

        return $compose;
    }

    protected function mapOptions( $compose, array $names ) {
        foreach ( $names as $name ) {
            if ( $this->$name ) {
                $compose[ $name ] = $this->$name;
            }
        }

        return $compose;
    }

    protected function concat( $arr, $sign = ':' ) {

        return collect( $arr )->map( function ( $v, $k ) use ( $sign ) {
            return $k . $sign . $v;
        } )->values()->toArray();
    }

    /**
     * @param string $name
     *
     * @return BuildsDockerComposeService
     */
    public function name( string $name ) {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $volumes
     *
     * @return BuildsDockerComposeService
     */
    public function volumes( array $volumes ) {
        $this->volumes = $volumes;

        return $this;
    }

    /**
     * @param array $ports
     *
     * @return BuildsDockerComposeService
     */
    public function ports( array $ports ) {
        $this->ports = $ports;

        return $this;
    }

    /**
     * @param array $expose
     *
     * @return BuildsDockerComposeService
     */
    public function expose( array $expose ) {
        $this->expose = $expose;

        return $this;
    }

    /**
     * @param array $env
     *
     * @return BuildsDockerComposeService
     */
    public function env( array $env ) {
        $this->env = $env;

        return $this;
    }

    /**
     * @param array $networks
     *
     * @return BuildsDockerComposeService
     */
    public function networks( array $networks ) {
        $this->networks = $networks;

        return $this;
    }

    /**
     * @param array $depends
     *
     * @return BuildsDockerComposeService
     */
    public function depends( array $depends ) {
        $this->depends = $depends;

        return $this;
    }

    /**
     * @param string $restart
     *
     * @return BuildsDockerComposeService
     */
    public function restart( string $restart ) {
        $this->restart = $restart;

        return $this;
    }

    /**
     * @param array $custom
     *
     * @return BuildsDockerComposeService
     */
    public function custom( array $custom ) {
        $this->custom = $custom;

        return $this;
    }

    /**
     * @param string $image
     *
     * @return BuildsDockerComposeService
     */
    public function image( string $image ) {
        $this->image = $image;

        return $this;
    }

    /**
     * @param string $context
     *
     * @return BuildsDockerComposeService
     */
    public function context( string $context ) {
        $this->context = $context;

        return $this;
    }

    /**
     * @param array|string $command
     *
     * @return BuildsDockerComposeService
     */
    public function command( $command ) {
        $this->command = is_array( $command ) ? json_encode( $command ) : $command;

        return $this;
    }


}
