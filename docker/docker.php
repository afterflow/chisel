<?php

use Afterflow\Chisel\Docker\Services\Caddy\Caddy;
use Afterflow\Chisel\Docker\Services\MySQL\MySQL;
use Afterflow\Chisel\Docker\Services\PhpFpm\PhpFpm;
use Afterflow\Chisel\Docker\Services\PhpMyAdmin\PhpMyAdmin;
use Afterflow\Chisel\Docker\Services\Worker\Worker;

/*
|----------------------------------------------------------------------------
| Docker Networks
|----------------------------------------------------------------------------
|
| Docker networks are good way to separate access to your services. Each
| network has it's own containers. Containers can only "see" other containers
| from their own network. You can add container to multiple networks though.
|
*/

Docker::network( 'frontend' );
Docker::network( 'backend' );

/*
|----------------------------------------------------------------------------
| Workspace Container
|----------------------------------------------------------------------------
|
| There is a special "workspace" container stuffed with developer tools like
| bash, fish, node, npm, mysql-client and much more, so that you can use all
| of them without installing them to your local system. This service is also
| responsible for running scheduled tasks. Get a workspace bash shell:
|
| php artisan chisel:workspace
|
*/

Docker::workspace()->networks( [ 'frontend', 'backend' ] );

/*
|----------------------------------------------------------------------------
| Docker Services
|----------------------------------------------------------------------------
|
| This section describes local, production and common containers of your
| application. You can add arbitrary Docker containers or override the
| defaults. Use php artisan chisel:publish to tweak these services.
|
*/

// Web Server with automatic HTTPS
Docker::service( 'caddy', Caddy::class );

// PHP-FPM backend for the web server
Docker::service( 'php-fpm', PhpFpm::class );

// MySQL Database
Docker::service( 'mysql', MySQL::class )->networks( [ 'backend' ] );

// MySQL Administration Tool
Docker::service( 'phpmyadmin', PhpMyAdmin::class );

// Queue worker service.
Docker::service( 'worker', Worker::class )->restart( 'always' );

/*
|----------------------------------------------------------------------------
| Pre-Built Chisel Services Collection
|----------------------------------------------------------------------------
|
| Here you can find a collection of pre-configured drop-in examples for more
| Docker services. You can comment them out and everything should work out of
| the box. With new versions of Chisel, this section will probably get more
| and more examples. Contributions are welcome!
|
*/

//    Docker::image( 'browserless', 'browserless/chrome' )
//          ->networks( [ 'frontend', 'backend' ] )
//          ->ports( [ 3000 => 3000 ] );

/*
|----------------------------------------------------------------------------
| Environment Specific Configuration Example
|----------------------------------------------------------------------------
|
| Here you can define some local-only services like mailtrap, selenium, etc.
| or define the same services with different volumes and so on...
|
*/

if ( app()->environment( 'local' ) ) {

    // Local services

} else {

    // Other services

}
