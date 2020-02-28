<?php

namespace Afterflow\Chisel\Console;

use Afterflow\Chisel\Chisel;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Process\Process;

class Main extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chisel';

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

        $services = Chisel::load();

        $this->table( [
            'service',
            'state',
            //            'ports' . PHP_EOL . 'host => container',
            'ports',
            'networks',
        ], collect( $services )->map( function ( $v, $k ) {
            return [
                $k,
                $v->state(),

                collect( Arr::get( $v->toArray(), 'ports', [] ) )->map( function ( $c, $h ) {
                    return $h . ' => ' . $c;
                } )->implode( ', ' ),

                collect( $v->toArray()[ 'networks' ] )->values()->implode( ', ' ),
            ];

        } )->toArray() );

    }
}
