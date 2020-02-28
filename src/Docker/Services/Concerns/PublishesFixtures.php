<?php


namespace Afterflow\Chisel\Docker\Services\Concerns;


trait PublishesFixtures {

    protected $fixture_path;

    protected function publishesFixtures( $path ) {
        if ( ! file_exists( $path ) ) {
            throw new \InvalidArgumentException( 'Fixture path ' . $path . ' does not exist.' );
        }

        $this->fixture_path = $path;
    }

    public function fixture( $name = '' ) {

        if ( ! $this->fixture_path ) {
            throw new \LogicException( 'Service does not define fixture path. Did you register it?' );
        }

        $localFixturePath = base_path( 'docker/' . $this->name );

        if ( file_exists( $localFixturePath ) ) {
            return $localFixturePath . DIRECTORY_SEPARATOR . $name;
        }

        if ( file_exists( $this->fixture_path ) ) {
            return $this->fixture_path . DIRECTORY_SEPARATOR . $name;
        }

        throw new \Exception( 'Fixture ' . $name . ' not found' );

    }

}
