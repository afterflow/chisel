<?php


namespace Afterflow\Chisel\Docker\Services\Caddy;


use Afterflow\Chisel\Docker\Services\Concerns\PublishesFixtures;
use Afterflow\Chisel\Docker\Services\Service;

class Caddy extends Service {

    use PublishesFixtures;

    protected $image = 'abiosoft/caddy:no-stats';
    protected $name = 'caddy';
    protected $networks = [ 'frontend', 'backend' ];

    protected $depends = [ 'php-fpm' ];

    protected $ports = [
        80  => 80,
        443 => 443,
    ];

    protected $command = [ "--conf", "/etc/caddy/Caddyfile", "--log", "stdout", "--agree=true" ];

    public function register() {
        $this->publishesFixtures( __DIR__ . '/fixtures' );
    }

    public function configure() {

        return $this->volumes( [
            base_path()                         => '/var/www:cached',
            $this->fixture( 'Caddyfile' )       => '/etc/caddy/Caddyfile',
            $this->fixture( 'vhosts' )          => '/etc/caddy/vhosts',
            storage_path( 'logs' )              => '/var/log/caddy',
            chisel_project_data_path( 'caddy' ) => '/root/.caddy',
        ] );

    }

}
