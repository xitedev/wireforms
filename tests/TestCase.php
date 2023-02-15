<?php

namespace Xite\Wireforms\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Xite\Wireforms\WireformsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            WireformsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
