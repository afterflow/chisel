<?php


namespace Afterflow\Chisel\Docker;


use Afterflow\Chisel\Docker\Services\Service;
use Afterflow\Chisel\Docker\Services\Workspace;

class Docker {

    public function network() {

    }

    public function workspace() {
        $service = Workspace::make();
        $this->service( 'workspace', $service );

        return $service;
    }

    public function service( string $string, Service $service ) {

    }
}
