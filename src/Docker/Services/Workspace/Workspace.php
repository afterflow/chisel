<?php


namespace Afterflow\Chisel\Docker\Services;


class Workspace extends Service {

    protected $image = 'exbox/workspace:4.0';
    protected $name = 'workspace';
    protected $networks = [ 'frontend', 'backend' ];
    protected $custom = [ 'tty' => true ];

    public function configure() {

        return $this->volumes( [
            base_path()                 => '/var/www',
            $this->fixture( 'php.ini' ) => '/usr/local/etc/php/php.ini',
        ] );

    }

}
