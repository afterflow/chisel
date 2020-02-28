<?php


namespace Afterflow\Chisel\Docker;


use Afterflow\Chisel\Docker\Services\Service;
use Afterflow\Chisel\Docker\Services\Workspace\Workspace;

class Docker {

    protected $networks = [];
    protected $services = [];

    public function network( $network, $driver = 'bridge' ) {
        $this->networks [ $network ] = [
            'driver' => $driver,
        ];
    }

    public function workspace() {
        $service = Workspace::make();
        $this->service( 'workspace', $service );

        return $service;
    }

    public function getService( $name ) {
        return $this->services[ $name ];
    }

    public function services() {
        return $this->services;
    }

    public function service( string $name, Service $service ) {
        $this->services[ $name ] = $service->name( $name );
    }

    public function toCompose() {

        $compose = [
            'version'  => '3',
            'networks' => $this->networks,
            'services' => collect( $this->services )->map->toCompose()->toArray(),
        ];

        return $compose;
    }
}
