<?php

namespace Remeritus\LivewireRruleGenerator\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Remeritus\LivewireRruleGenerator\LivewireRruleGeneratorServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Remeritus\\LivewireRruleGenerator\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LivewireRruleGeneratorServiceProvider::class,
        ];


    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:MEN6dYh4I+H9slhb8LDhjZMY7bvU2Hme1EeHg9U789o=');

        /*
        $migration = include __DIR__.'/../database/migrations/create_livewire-rrule-generator_table.php.stub';
        $migration->up();
        */
    }
}
