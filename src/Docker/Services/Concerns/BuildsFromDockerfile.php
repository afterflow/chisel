<?php


namespace Afterflow\Chisel\Docker\Services\Concerns;


trait BuildsFromDockerfile {
    use PublishesFixtures;

    protected $build = true;


}