Work In Progress. This means that the API may change at any time.

# Afterflow Chisel

Chisel is a lightweight, extensible docker compose wrapper written in PHP.

## Requirements

Laravel, Docker and docker-compose.

## Installation

Grab a fresh installation of Laravel:

```bash
laravel new chisel-project
```

Install Chisel via composer:

```bash
composer require afterflow/chisel
```

Then run:
```bash
php artisan chisel:up
```

Done! You have a Caddy Web server at http://localhost, phpMyAdmin at http://localhost:8080.

MySQL, Queue Worker and Workspace with cron are running on background.


### Chisel vs Vessel

Vessel by Chris Fidao is a nice little wrapper around docker-compose. It's written in bash and doesn't require php to be installed on local machine.

Extending behind basic containers is done through editing docker-compose file.

Chisel defines it's services using php classes (still relying on docker-compose under the hood).
In Chisel, the project configuration is defined in `docker/docker.php`. 
You can easily extend or reconfigure services, and now you can also ship some Chisel Service classes with your composer package or reuse them when scaffolding new projects.

### Chisel vs Laradock

Laradock is all-in-one all-transparent all-purpose collection of Docker images for Laravel. In fact, Chisel is using some of it's developments but in a slightly opinionated way.

Because of the nature of Laradock's images, build times may be really big and you would need to build and publish intermediate images to make this work with multiple projects/servers.
Also, configuring Laradock might get a little overwhelming. Instead, you can build an image with Laradock and then use it with Chisel.

### Chisel vs Laravel Valet

Docker on Mac uses slow virtualization and hence, native server software runs faster than dockerized version. Because of this, you might prefer using Valet for the Web stack. Chisel will still be helpful though. For example, you can run Browserless or Redis or Kibana with Chisel and everything else with Valet / native.

### Chisel vs Laravel Homestead

Ideally, Chisel should be used over Homestead in all cases. The goal of Chisel is to provide the same level of convenience Homestead provides, but with a production-ready Docker environment and much better extensibility.

### Chisel vs docker-compose

Currently, Chisel is using docker-compose under the hood, providing another level of abstraction to make compose configurable with PHP, and hence, extensible through Composer packages.

Using Chisel over Compose just makes scaffolding easier and faster when using on multiple projects on multiple servers, because you can just `composer require` everything you need without the need to bundle compose files and copy/paste them over and over into new projects.

## Basic Usage Examples

Try to migrate the database from your host machine:

```bash
php artisan migrate
```

Same but from inside dockerized workspace

```bash
php artisan chisel exec workspace migrate
```

Notice how it just works with MySQL smoothly out of the box from both host machine and container with a single default `.env` file.

Compile frontend assets from workspace container:
```bash
php artisan chisel:workspace
npm install
npm run dev
```

See MySQL logs:
```bash
php artisan chisel:logs mysql
```

Execute arbitrary command on container:
```bash
php artisan chisel:exec caddy sh
```

Execute a shortcut command:
```bash
php artisan chisel:exec mysql @dump
```

## Configuration

Run the following command to publish everything:
```bash
php artisan chisel:install
```
This will create a `docker` directory in the project root.

`docker/docker.php`
```php
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
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT).
