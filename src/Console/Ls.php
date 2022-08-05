<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Afterflow\Chisel\Docker\Docker;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Process\Process;

class Ls extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List services';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $services = Docker::load();

        $this->table( [
            'service',
            'state',
            //            'ports' . PHP_EOL . 'host => container',
            'ports',
            'volumes',
            'networks',
        ], collect( $services )->map( function ( $v, $k ) {
            return [
                $k,
                $v->state(),

                collect( Arr::get( $v->toArray(), 'ports', [] ) )->map( function ( $c, $h ) {
                    return $h . ' => ' . $c;
                } )->implode( PHP_EOL ),

                collect( Arr::get( $v->toArray(), 'volumes', [] ) )->map( function ( $c, $h ) {
                    return $h . ' => ' . $c;
                } )->implode( PHP_EOL ),

                collect( $v->toArray()[ 'networks' ] )->values()->implode( ', ' ),
            ];

        } )->toArray() );

    }
}
