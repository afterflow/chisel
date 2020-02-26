<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Environment Configuration
    |--------------------------------------------------------------------------
    |
    | Common environment configuration required by workspace and most of the
    | other built-in containers. It's recommended to set DATA_PATH_HOST
    | outside the project folder to keep your database safe and prevent
    | messed up permissions.
    |
    */

    'env' => [
        'DATA_PATH_HOST'     => env( 'CHISEL_DATA_PATH', '~/.chisel/' ),
        'APP_CODE_PATH_HOST' => './',
        'DOCKER_HOST_IP'     => '172.17.0.1',
        'TIMEZONE'           => config( 'app.timezone' ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connection Override
    |--------------------------------------------------------------------------
    |
    | Since Laravel now runs inside Docker container, you are expected to use
    | the name of the db container as the database host. This configuration
    | section, if enabled, allows you to keep localhost as the db host in
    | and still have a way to properly connect to the database when running
    | Artisan commands from the host machine.
    |
    */

    'database_connection_override' => [
        'host' => 'mysql',
        'port' => 3306,
    ],

    /*
    |--------------------------------------------------------------------------
    | Services Configuration
    |--------------------------------------------------------------------------
    |
    | This section describes local, production and common containers of your
    | application. You can add arbitrary Docker containers or override the
    | defaults.
    |
    */

    'services' => [
        'common' => [

            /*

             Common Services
             Here are must-have containers that run regardless of environment.

            */

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
            'caddy'      => [
                'service' => \Afterflow\Chisel\Services\Caddy::class,
                'params'  => [
                    'hosts' => [
                        /**
                         * Respond to any hostname for HTTP by default.
                         */
                        '0.0.0.0:80',
                        /**
                         * If port not specified, Caddy will try to bring up both HTTP and HTTPS.
                         * For HTTPS to work, the specified domain should really resolve to this machine,
                         * otherwise automatic TLS will fail.
                         * You'll probably only want HTTPS on production/staging.
                         */

                        //                        'mywebsite.com',
                    ],
                    'http'  => [
                        'port' => env( 'CHISEL_HTTP_PORT', 80 ),
                    ],
                    'https' => [
                        'port' => env( 'CHISEL_HTTPS_PORT', 443 ),
                    ],
                    'logs'  => storage_path( 'logs' ),
                ],
            ],

        ],

        /*

         Local Services
         These containers will only exist in a local environment. Add mailtrap, selenium, etc.

        */

        'local' => [
            /**
             * Define any local-specific containers
             */
        ],


        /*

         Production Services
         These containers will only exist on production environment.

        */

        'production' => [
            /**
             * Define any production-specific containers
             */
        ],


    ],

];
