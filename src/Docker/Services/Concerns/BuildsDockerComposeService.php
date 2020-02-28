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

    /**
     * @param string $name
     *
     * @return BuildsDockerComposeService
     */
    public function name( string $name ): BuildsDockerComposeService {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $volumes
     *
     * @return BuildsDockerComposeService
     */
    public function volumes( array $volumes ): BuildsDockerComposeService {
        $this->volumes = $volumes;

        return $this;
    }

    /**
     * @param array $ports
     *
     * @return BuildsDockerComposeService
     */
    public function ports( array $ports ): BuildsDockerComposeService {
        $this->ports = $ports;

        return $this;
    }

    /**
     * @param array $expose
     *
     * @return BuildsDockerComposeService
     */
    public function expose( array $expose ): BuildsDockerComposeService {
        $this->expose = $expose;

        return $this;
    }

    /**
     * @param array $env
     *
     * @return BuildsDockerComposeService
     */
    public function env( array $env ): BuildsDockerComposeService {
        $this->env = $env;

        return $this;
    }

    /**
     * @param array $networks
     *
     * @return BuildsDockerComposeService
     */
    public function networks( array $networks ): BuildsDockerComposeService {
        $this->networks = $networks;

        return $this;
    }

    /**
     * @param array $depends
     *
     * @return BuildsDockerComposeService
     */
    public function depends( array $depends ): BuildsDockerComposeService {
        $this->depends = $depends;

        return $this;
    }

    /**
     * @param string $restart
     *
     * @return BuildsDockerComposeService
     */
    public function restart( string $restart ): BuildsDockerComposeService {
        $this->restart = $restart;

        return $this;
    }

    /**
     * @param array $custom
     *
     * @return BuildsDockerComposeService
     */
    public function custom( array $custom ): BuildsDockerComposeService {
        $this->custom = $custom;

        return $this;
    }

    /**
     * @param string $image
     *
     * @return BuildsDockerComposeService
     */
    public function image( string $image ): BuildsDockerComposeService {
        $this->image = $image;

        return $this;
    }

    /**
     * @param string $context
     *
     * @return BuildsDockerComposeService
     */
    public function context( string $context ): BuildsDockerComposeService {
        $this->context = $context;

        return $this;
    }

    /**
     * @param array|string $command
     *
     * @return BuildsDockerComposeService
     */
    public function command( $command ) {
        $this->command = $command;

        return $this;
    }


}
