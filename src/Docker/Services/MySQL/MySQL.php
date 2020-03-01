<?php


namespace Afterflow\Chisel\Docker\Services\MySQL;


use Afterflow\Chisel\Docker\Services\Concerns\BuildsFromDockerfile;
use Afterflow\Chisel\Docker\Services\Concerns\PublishesFixtures;
use Afterflow\Chisel\Docker\Services\Service;

class MySQL extends Service {

    use PublishesFixtures;

    protected $name = 'mysql';
    protected $networks = [ 'backend' ];

    protected $ports = [ 3306 => 3306 ];

    public function shortcuts() {
        return [
            '@dump' => 'mysqldump -uroot laravel > laravel.sql',
        ];
    }


    public function register() {
        $this->publishesFixtures( __DIR__ . '/fixtures' );
    }

    public function configure() {

        return $this->env( [
            'MYSQL_DATABASE'             => 'laravel',
            'MYSQL_USER'                 => 'root',
            'MYSQL_PASSWORD'             => '',
            'MYSQL_ROOT_PASSWORD'        => '',
            'MYSQL_ALLOW_EMPTY_PASSWORD' => true,
            'TZ'                         => config( 'app.timezone' ),
        ] )->volumes( [
            base_path()                                    => '/var/www',
            chisel_project_data_path( 'mysql' )            => '/var/lib/mysql',
            $this->fixture( 'docker-entrypoint-initdb.d' ) => '/docker-entrypoint-initdb.d',
        ] );

    }

}
