<?php

namespace Remeritus\LivewireRruleGenerator;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Remeritus\LivewireRruleGenerator\Http\Livewire\RruleGenerator;

class LivewireRruleGeneratorServiceProvider extends PackageServiceProvider
{
    public function bootingPackage() {
        Livewire::component('rrule-generator', RruleGenerator::class);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-rrule-generator')
            ->hasConfigFile()
            ->hasViews();
    }
}
