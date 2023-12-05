<?php

namespace Xite\Wireforms\Traits;

trait HasComponentName
{
    public static function getComponentName(): string
    {
        $namespace = str_replace(
            ['/', '\\'],
            '.',
            trim(trim(config('livewire.class_namespace')), '\\')
        );

        $class = str_replace(
            ['/', '\\'],
            '.',
            trim(trim(get_class(new static), '/'), '\\')
        );

        $namespace = collect(explode('.', $namespace))
            ->map(fn ($i) => \Illuminate\Support\Str::kebab($i))
            ->implode('.');

        $fullName = str(collect(explode('.', $class))
            ->map(fn ($i) => \Illuminate\Support\Str::kebab($i))
            ->implode('.'));

        if ($fullName->startsWith('.')) {
            $fullName = $fullName->substr(1);
        }

        // If using an index component in a sub folder, remove the '.index' so the name is the subfolder name...
        if ($fullName->endsWith('.index')) {
            $fullName = $fullName->replaceLast('.index', '');
        }

        if ($fullName->startsWith($namespace)) {
            return (string) $fullName->substr(strlen($namespace) + 1);
        }

        return (string) $fullName;
    }
}
