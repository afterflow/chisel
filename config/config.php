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
        'DATA_PATH_HOST'     => env( 'CHISEL_DATA_PATH', '~/.chisel/' . basename( base_path() ) ),
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

];
