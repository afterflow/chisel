<?php

/*
|----------------------------------------------------------------------------
| Docker Networks
|----------------------------------------------------------------------------
|
| Docker networks are good way to control access to your services. Each
| network has it's own containers. Containers can only "see" other containers
| from their own network. You can add container to multiple networks.
|
*/


use Afterflow\Chisel\Docker\Services\Caddy\Caddy;
use Afterflow\Chisel\Docker\Services\MySQL\MySQL;
use Afterflow\Chisel\Docker\Services\PhpFpm\PhpFpm;
use Afterflow\Chisel\Docker\Services\PhpMyAdmin\PhpMyAdmin;
use Afterflow\Chisel\Docker\Services\Service;

Docker::network( 'frontend' );
Docker::network( 'backend' );

/*
|----------------------------------------------------------------------------
| Workspace Container
|----------------------------------------------------------------------------
|
| There is a special "workspace" container stuffed with developer tools like
| bash, fish, node, npm, mysql-client and much more, so that you can use all
| of them without installing them to your local system. This service can be
| safely disabled e.g. in production. Instantiate a workspace bash shell:
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
| defaults. For environment control, simply use if( app()->environment(...) )
|
*/

Docker::service( 'caddy', Caddy::class );
Docker::service( 'php-fpm', PhpFpm::class );
Docker::service( 'mysql', MySQL::class );
Docker::service( 'phpmyadmin', PhpMyAdmin::class );

//Docker::image( 'browserless', 'browserless/chrome' )->networks( [ 'frontend', 'backend' ] )->ports( [ 3000 => 3000 ] );
