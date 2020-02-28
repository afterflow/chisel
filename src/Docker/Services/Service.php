<?php


namespace Afterflow\Chisel\Docker\Services;


use Afterflow\Chisel\Docker\Services\Concerns\BuildsDockerComposeService;
use Afterflow\Chisel\Docker\Services\Concerns\PublishesFixtures;

class Service {
    use BuildsDockerComposeService, PublishesFixtures;

    public function __construct() {
        if ( method_exists( $this, 'register' ) ) {
            call_user_func( $this, 'register' );
        }
    }

    public static function make() {
        return new static();
    }
}
