<?php

return [

    'env' => [
        'DATA_PATH_HOST'     => './',
        'APP_CODE_PATH_HOST' => './',
        'DOCKER_HOST_IP'     => '172.17.0.1',
        'TIMEZONE'           => config( 'app.timezone' ),
    ],

    'database_connection_override' => [
        'host' => 'mysql',
        'port' => 3306,
    ],

    'services' => [
        'mysql'      => [
            'file' => base_path( 'vendor/afterflow/chisel/docker/mysql/docker-compose.yml' ),
            'env'  => [
                'MYSQL_DATABASE'          => env( 'DB_DATABASE' ),
                'MYSQL_USER'              => env( 'DB_USERNAME' ),
                'MYSQL_PASSWORD'          => env( 'DB_PASSWORD' ),
                'MYSQL_PORT'              => env( 'DB_PORT' ),
                'MYSQL_ROOT_PASSWORD'     => 'root',
                'MYSQL_ENTRYPOINT_INITDB' => base_path( 'vendor/afterflow/chisel/docker/mysql/docker-entrypoint-initdb.d' ),
                'MYSQL_IMAGE_PATH'        => base_path( 'vendor/afterflow/chisel/docker/mysql/' ),
            ],
        ],
        'phpmyadmin' => [
            'file' => base_path( 'vendor/afterflow/chisel/docker/phpmyadmin/docker-compose.yml' ),
            'env'  => [
                'PMA_PORT'       => env( 'CHISEL_PMA_PORT', 8081 ),
                'PMA_MYSQL_HOST' => 'mysql',
                'PMA_IMAGE_PATH' => base_path( 'vendor/afterflow/chisel/docker/phpmyadmin/' ),
            ],
        ],
        'php-fpm'    => [
            'file' => base_path( 'vendor/afterflow/chisel/docker/php-fpm/docker-compose.yml' ),
            'env'  => [
                'PHP_IMAGE_PATH' => base_path( 'vendor/afterflow/chisel/docker/php-fpm/' ),
            ],
        ],

        'caddy' => [
            'file' => base_path( 'vendor/afterflow/chisel/docker/caddy/docker-compose.yml' ),
            'env'  => [
                'CADDY_IMAGE_PATH'      => base_path( 'vendor/afterflow/chisel/docker/caddy/' ),
                'CADDY_HOST_HTTP_PORT'  => env( 'CHISEL_HTTP_PORT', 80 ),
                'CADDY_HOST_HTTPS_PORT' => env( 'CHISEL_HTTPS_PORT', 443 ),
                'CADDY_HOST_LOG_PATH'   => storage_path( 'logs' ),
            ],
        ],

        // services end
    ],

];
