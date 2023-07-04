<?php

namespace Xite\Wireforms;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Xite\Wireforms\Commands\FormMakeCommand;

class WireformsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('wireforms')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasCommand(FormMakeCommand::class)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->loadViewComponentsAs('wireforms', [
            \Xite\Wireforms\Components\Fields\Text::class,
            \Xite\Wireforms\Components\Fields\Textarea::class,
            \Xite\Wireforms\Components\Fields\Select::class,
            \Xite\Wireforms\Components\Fields\WireSelect::class,
            \Xite\Wireforms\Components\Fields\NestedSetSelect::class,
            \Xite\Wireforms\Components\Fields\WireMultiSelect::class,
            \Xite\Wireforms\Components\Fields\DateTime::class,
            \Xite\Wireforms\Components\Fields\Boolean::class,
            \Xite\Wireforms\Components\Fields\Checkbox::class,
            \Xite\Wireforms\Components\Fields\Phone::class,
            \Xite\Wireforms\Components\Fields\Money::class,
        ]);

        Livewire::component(
            'wireforms.livewire.wire-select',
            \Xite\Wireforms\Livewire\WireSelect::class
        );

        Livewire::component(
            'wireforms.livewire.nested-set-select',
            \Xite\Wireforms\Livewire\NestedSetSelect::class
        );

        Livewire::component(
            'wireforms.livewire.wire-multi-select',
            \Xite\Wireforms\Livewire\WireMultiSelect::class
        );

        Livewire::component(
            'wireforms.livewire.options-select',
            \Xite\Wireforms\Livewire\OptionsSelect::class
        );
    }
}
