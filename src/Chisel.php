<?php


namespace Afterflow\Chisel;


use Illuminate\Support\Arr;

class Chisel {

    public static function up() {
        static::exec( 'up -d --build' );
    }

    public static function compile( $subcommand, $toString = false ) {

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

        $envs = collect( $env )->map( function ( $v, $k ) {
            return $k . '=' . $v;
        } )->values();

        $files = collect( $files )->map( function ( $v ) {
            return [ '-f', $v ];
        } )->flatten();

        $cmd = $envs->merge( [
            'docker-compose',
        ] )->merge( $files )->merge( [
            '--project-directory',
            base_path(),
        ] )->merge( explode( ' ', $subcommand ) )->toArray();

        if ( $toString ) {
            return static::cmdToString( $cmd );
        }

        return $cmd;
    }


    protected static function cmdToString( $cmd ) {
        return 'sudo ' . implode( ' ', $cmd );
    }

    public static function exec( $subcommand, $noInteraction = false ) {

        $cmd = static::compile( $subcommand );

        if ( $noInteraction ) {
            return static::execNoInteractive( $cmd );
        }

        if ( function_exists( 'pcntl_exec' ) ) {
            return static::pcntl( $cmd );
        }

        return static::execInteractive( $cmd );

    }

    protected static function execNoInteractive( $cmd ) {

        $descriptorspec = [ [ 'pipe', 'r' ], [ 'pipe', 'w' ], [ 'pipe', 'w' ] ];
        $process        = proc_open( static::cmdToString( $cmd ), $descriptorspec, $pipes, base_path() );
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

    protected static function pcntl( $cmd ) {

        $pid = pcntl_fork();
        if ( $pid ) {
            pcntl_waitpid( $pid, $status );
            if ( pcntl_wifexited( $status ) ) {
                return;
            }
        } else {
            if ( $pid = pcntl_fork() ) {
                pcntl_exec( '/usr/bin/sudo', $cmd );
            }
        }
    }

    public static function execInteractive( $cmd ) {

        $compiledString = static::cmdToString( $cmd );
        $descriptorspec = [
            [ 'file', '/dev/tty', 'r' ],
            [ 'file', '/dev/tty', 'w' ],
            [ 'file', '/dev/tty', 'w' ],
            //            [ "file", "/tmp/error-output.txt", "a" ],
            //            [ 'pipe', 'w' ],
        ];

        $process = proc_open( $compiledString, $descriptorspec, $pipes, base_path() );

        while ( is_resource( $process ) ) {
            usleep( 10000 );
        }
    }

}
