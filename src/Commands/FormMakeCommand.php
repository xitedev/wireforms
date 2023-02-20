<?php

namespace Xite\Wireforms\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'make:form')]
class FormMakeCommand extends GeneratorCommand
{
    protected $name = 'make:form';

    protected $description = 'Create new Form class';

    protected $type = 'Form';

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/form.stub');
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . '/../..' . $stub;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $namespace = trim($this->option('namespace') ?? 'Http\\Wireforms', '\\');

        return trim($rootNamespace . '\\' . $namespace, '\\');
    }

    protected function qualifyClass($name): string
    {
        $suffix = trim($this->option('suffix') ?? 'Form');
        if (! empty($suffix) && ! Str::endsWith($name, $suffix)) {
            $name .= $suffix;
        }

        return parent::qualifyClass($name);
    }

    protected function buildClass($name): array|string|null
    {
        $stub = $this->files->get($this->getStub());

        $model = $this->option('model');

        return $this->replaceNamespace($stub, $name)
            ->replaceModel($stub, $model)
            ->replaceClass($stub, $name);
    }

    protected function replaceModel(&$stub, $model): self
    {
        if (!$model) {
            return $this;
        }

        $model = str_replace('/', '\\', $model);

        if (str_starts_with($model, '\\')) {
            $namespacedModel = trim($model, '\\');
        } else {
            $namespacedModel = $this->qualifyModel($model);
        }

        $model = class_basename(trim($model, '\\'));

        $replace = [
            '{{ namespacedModel }}' => $namespacedModel,
            '{{namespacedModel}}' => $namespacedModel,
            '{{ model }}' => $model,
            '{{model}}' => $model,
            '{{ modelVariable }}' => Str::camel($model),
            '{{modelVariable}}' => Str::camel($model)
        ];

        $stub = str_replace(
            array_keys($replace), array_values($replace), $stub
        );

        $stub = preg_replace(
            vsprintf('/use %s;[\r\n]+use %s;/', [
                preg_quote($namespacedModel, '/'),
                preg_quote($namespacedModel, '/'),
            ]),
            "use {$namespacedModel};",
            $stub
        );

        return $this;
    }

    protected function getOptions(): array
    {
        return [
            ['namespace', 'N', InputOption::VALUE_REQUIRED, 'The namespace (under \App) to place this Form class.', config('wireforms.namespace')],
            ['suffix', 's', InputOption::VALUE_REQUIRED, 'Suffix the class with this value.', 'Form'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Model that used in class.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the Form class even if the file already exists.']
        ];
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->isReservedName($this->getNameInput()) || $this->didReceiveOptions($input)) {
            return;
        }

        $model = $this->components->askWithCompletion(
            'What model should this form apply to?',
            $this->possibleModels(),
            'none'
        );

        if ($model && $model !== 'none') {
            $input->setOption('model', $model);
        }
    }
}
