<?php


namespace Afterflow\Chisel\Docker;


class Facade extends \Illuminate\Support\Facades\Facade {

    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'docker';
    }

}
