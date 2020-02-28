<?php


namespace Afterflow\Chisel\Docker\Services\PhpMyAdmin;


use Afterflow\Chisel\Docker\Services\Concerns\BuildsFromDockerfile;
use Afterflow\Chisel\Docker\Services\Service;

class PhpMyAdmin extends Service {

    use BuildsFromDockerfile;

    protected $name = 'phpmyadmin';
    protected $networks = [ 'frontend', 'backend' ];

    protected $ports = [
        8080 => 80,
    ];

    public function register() {
        $this->publishesFixtures( __DIR__ . '/fixtures' );
    }

    public function configure() {

        return $this->volumes( [
            $this->fixture( 'config.user.inc.php' ) => '/etc/phpmyadmin/config.user.inc.php',
        ] );

    }

}
