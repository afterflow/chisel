<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Main Configuration
    |--------------------------------------------------------------------------
    |
    | Common environment configuration required by workspace and most of the
    | other built-in containers. It's recommended to set data_path
    | outside the project folder to keep your database safe and prevent
    | messed up permissions. data_path must be unique per project to prevent
    | data collisions.
    |
    */

    'data_path'      => env( 'CHISEL_DATA_PATH', '~/.chisel/' . basename( base_path() ) ),
    'docker_host_ip' => '172.17.0.1',

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
