<?php


namespace Afterflow\Chisel\Docker\Services\Worker;


use Afterflow\Chisel\Docker\Services\Concerns\PublishesFixtures;
use Afterflow\Chisel\Docker\Services\Service;

class Worker extends Service {

    use PublishesFixtures;

    protected $name = 'worker';
    protected $networks = [ 'backend' ];

    public function register() {
        $this->publishesFixtures( __DIR__ . '/fixtures' );
    }

    public function configure() {

        return $this->volumes( [
            base_path()                 => '/var/www',
            $this->fixture( 'php.ini' ) => '/usr/local/etc/php/php.ini',
        ] );

    }

}
