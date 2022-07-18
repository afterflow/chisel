<?php


namespace Afterflow\Chisel\Docker\Services\Workspace;


use Afterflow\Chisel\Docker\Services\Concerns\PublishesFixtures;
use Afterflow\Chisel\Docker\Services\Service;

class Workspace extends Service {

    use PublishesFixtures;

    protected $image = 'exbox/workspace:5.0';
    protected $name = 'workspace';
    protected $networks = [ 'frontend', 'backend' ];
    protected $custom = [ 'tty' => true ];

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
