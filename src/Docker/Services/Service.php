<?php


namespace Afterflow\Chisel\Docker\Services;


use Afterflow\Chisel\Docker\Services\Concerns\BuildsDockerComposeService;
use Afterflow\Chisel\Docker\Services\Concerns\PublishesFixtures;

class Service {
    use BuildsDockerComposeService, PublishesFixtures;

    public function __construct( $image = null ) {
        if ( $image ) {
            $this->image( $image );
        }
        if ( method_exists( $this, 'register' ) ) {
            $this->register();
        }

        return $this->configure();
    }

    public static function make( $image = null ) {
        return new static( $image );
    }

    public function configure() {
        return $this;
    }
}
