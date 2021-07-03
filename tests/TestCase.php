<?php

namespace Ahmedjoda\JodaResources\Tests;

use Ahmedjoda\JodaResources\JodaResourcesServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            JodaResourcesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', env('APP_DEBUG', true));
    }
}