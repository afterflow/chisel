<?php /** @noinspection PhpComposerExtensionStubsInspection */


namespace Afterflow\Chisel;


class ShellCommand {

    protected $sequence;

    /**
     * ShellCommand constructor.
     *
     * @param $sequence
     */
    public function __construct( $sequence ) {
        $this->sequence = $sequence;
    }

    public static function fromString( $string ) {
        return new static( explode( ' ', $string ) );
    }

    public function toString() {
        return implode( ' ', $this->sequence );
    }

    public function getCommand() {
        return $this->sequence;
    }

    public function exec( $interactive = false ) {

        if ( $interactive ) {
            return $this->execInteractive();
        }

        $descriptorspec = [ [ 'pipe', 'r' ], [ 'pipe', 'w' ], [ 'pipe', 'w' ] ];
        $process        = proc_open( $this->toString(), $descriptorspec, $pipes, base_path() );
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

    public function isSh() {
        return in_array( $this->getCommand()[ 0 ], [
            'sh',
            'ash',
            'bash',

            'zsh',
            'fish',
        ] );
    }

    public function execInteractive() {

        if ( extension_loaded( 'pcntl' ) ) {
            return $this->execPcntl();
        }

        $descriptorspec = [
            [ 'file', '/dev/tty', 'r' ],
            [ 'file', '/dev/tty', 'w' ],
            [ 'file', '/dev/tty', 'w' ],
        ];
        $process        = proc_open( $this->toString(), $descriptorspec, $pipes, base_path() );

        while ( is_resource( $process ) ) {
            usleep( 10000 );
        }

        return null;
    }

    public function execPcntl() {
        $pid = pcntl_fork();
        if ( $pid ) {
            pcntl_waitpid( $pid, $status );
            if ( pcntl_wifexited( $status ) ) {
                return;
            }
        } else {
            if ( $pid = pcntl_fork() ) {
                pcntl_exec( $this->getCommand()[ 0 ], array_slice( $this->getCommand(), 1 ) );
            }
        }

        return null;
    }

}
