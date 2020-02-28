<?php


namespace Afterflow\Chisel\Docker\Services;


class Service {

    protected $compose = [];
    protected $name;


    public function withComposeOption( $key, $value ) {
        $this->compose[ $key ] = $value;
    }


}
