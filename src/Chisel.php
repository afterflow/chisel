<?php


namespace Afterflow\Chisel;


class Chisel {

    public static function exec( $subcommand, $noInteraction = false ) {

        $command = [
            '/usr/bin/sudo',
            'docker-compose',
            '--project-dir',
            base_path(),
            '-f',
            storage_path( 'framework/chisel-docker-compose.yml' ),
        ];

        $command = array_merge( $command, ShellCommand::fromString( $subcommand )->getCommand() );

        return ( new ShellCommand( $command ) )->exec( ! $noInteraction );

    }

}
