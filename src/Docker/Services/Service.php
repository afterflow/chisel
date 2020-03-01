<?php


namespace Afterflow\Chisel\Docker\Services;


use Afterflow\Chisel\Docker\Services\Concerns\BuildsDockerComposeService;
use Afterflow\Chisel\Docker\Services\Concerns\PublishesFixtures;
use Illuminate\Support\Arr;

class Service {
    use BuildsDockerComposeService;

    public function __construct( $image = null ) {
        if ( $image ) {
            $this->image( $image );
        }
        if ( method_exists( $this, 'register' ) ) {
            $this->register();
        }

        return $this->configure();
    }

    public function shortcuts() {
        return [];
    }

    public function shortcut( $name ) {
        if ( ! isset( $this->shortcuts()[ $name ] ) ) {
            return $name;
        }

        $expanded = $this->shortcuts()[ $name ];

        return $expanded;

    }

    public function state() {
        $dockerName = basename( base_path() ) . '_' . $this->name . '_1';
        $r          = `docker ps -f name=$dockerName`;
        $r          = explode( PHP_EOL, $r );
        array_shift( $r );
        $r = array_shift( $r );
        $r = preg_split( '~\s{2,1000}~ims', $r );

        //        dd($r);
        return ( $f = Arr::get( $r, 4, false ) ) ? '<fg=green>' . $f . '</>' : '<fg=red>Down</>';
        //        $r = array_pop($r);
        //        dd($r);
    }

    public static function make( $image = null ) {
        return new static( $image );
    }

    public function configure() {
        return $this;
    }
}
