<?php


namespace Afterflow\Chisel;


use Illuminate\Support\Arr;
use Symfony\Component\Process\Process;

class Chisel {

    public function __construct() {
    }


    public static function up() {

        static::exec( 'up -d --build' );

    }

    public static function exec( $subcommand, $interactive = false ) {

        $env   = collect( config( 'chisel.env' ) );
        $files = collect( [ __DIR__ . '/../docker/docker-compose.yml' ] );
        collect( config( 'chisel.services.common' ) )
            ->merge( config( 'chisel.services.' . app()->environment() ) )
            ->each( function ( $s ) use ( $files, &$env ) {

                $dockerSettings = [
                    'env'  => Arr::get( $s, 'env', [] ),
                    'file' => Arr::get( $s, 'file', false ),
                ];

                if ( $class = Arr::get( $s, 'service' ) ) {
                    $dockerSettings = app( $class )->configure( Arr::get( $s, 'params' ) );
                }

                $env = $env->replace( $dockerSettings[ 'env' ] );
                $files->push( $dockerSettings[ 'file' ] );

            } );

        $envLine = collect( $env )->map( function ( $v, $k ) {
            return $k . '=' . $v;
        } )->implode( ' ' );


        $filesLine = collect( $files )->map( function ( $v ) {
            return '-f ' . $v;
        } )->implode( ' ' );


        $compiledString = "sudo $envLine docker-compose --project-directory " . base_path() . " " . $filesLine;
        $compiledString .= ' ' . $subcommand;

        if ( $interactive ) {
            return static::execInteractive( $compiledString );
        }

        $descriptorspec = [ [ 'pipe', 'r' ], [ 'pipe', 'w' ], [ 'pipe', 'w' ] ];
        $process        = proc_open( $compiledString, $descriptorspec, $pipes, base_path() );
        fclose( $pipes[ 0 ] );
        while ( ! feof( $pipes[ 1 ] ) ) {
            echo fgets( $pipes[ 1 ], 1024 );
        }
        fclose( $pipes[ 1 ] );
        while ( ! feof( $pipes[ 2 ] ) ) {
            echo fgets( $pipes[ 2 ], 1024 );
        }
        fclose( $pipes[ 2 ] );

        return proc_close( $process );

    }

    public static function execInteractive( $cmd ) {

        $descriptorspec = [
            [ 'file', '/dev/tty', 'r' ],
            [ 'file', '/dev/tty', 'w' ],
            [ "file", "/tmp/error-output.txt", "a" ],
        ];

        $process = proc_open( $cmd, $descriptorspec, $pipe, base_path() );

        while ( is_resource( $process ) ) {
            usleep( 10000 );
        }
    }

}
