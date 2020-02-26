<?php


namespace Afterflow\Chisel\Services;


use Illuminate\Support\Arr;

class Caddy implements ServiceInterface {

    public function configure( array $params ) {
        $vhosts = Arr::get( $params, 'hosts', [ '0.0.0.0:80' ] );
        file_put_contents( storage_path( 'framework/cache/vhosts' ), implode( ', ' , $vhosts ) );

        return [

            'file' => base_path( 'vendor/afterflow/chisel/docker/caddy/docker-compose.yml' ),
            'env'  => [
                'CADDY_IMAGE_PATH'      => base_path( 'vendor/afterflow/chisel/docker/caddy/' ),
                'CADDY_HOST_HTTP_PORT'  => Arr::get( $params, 'http.port', 80 ),
                'CADDY_HOST_HTTPS_PORT' => Arr::get( $params, 'https.port', 80 ),
                'CADDY_HOST_LOG_PATH'   => Arr::get( $params, 'logs', storage_path( 'logs' ) ),
                'CADDY_VHOSTS_FILE'     => storage_path( 'framework/cache/vhosts' ),
            ],
        ];
    }

}
