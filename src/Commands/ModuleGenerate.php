<?php

namespace reenekt\LaravelModules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class ModuleGenerate
 * Artisan command for generating new module
 * @package reenekt\LaravelModules\Commands
 * @author reenekt
 */
class ModuleGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // maybe better make $this->moduleName (to do later)
        $moduleName = $this->argument('name');
        $folder = $this->CreateFolder($moduleName);
        $this->CreateLaravelModuleClass($moduleName, $folder);
        if ($this->CreateModuleFolders($moduleName)) {
            $this->CreateLaravelModuleController($moduleName, $folder);
            $this->CreateRoutes($moduleName);
            $this->CreateView($moduleName);
        }
    }

    /**
     * Creates module's folder
     *
     * @param string $moduleName Module name
     * @return string
     */
    protected function CreateFolder($moduleName)
    {
        $path = app_path(config('laravelModules.modules_folder'));
        if (!is_dir($path . '/' . $moduleName)) {
            if (mkdir($path . '/' . $moduleName)) {
                $this->info('Module ' . $moduleName . ' created');
            } else {
                $this->error('Module ' . $moduleName . ' cannot be created');
            }
        } else {
            $this->error('Module ' . $moduleName . ' already exists');
        }
        return $path . '/' . $moduleName;
    }

    /**
     * Creates module's default structure (folders)
     *
     * @param string $moduleName Module name
     * @return bool
     */
    protected function CreateModuleFolders($moduleName)
    {
        // maybe better make $this->path (to do later)
        $path = app_path(config('laravelModules.modules_folder'));
        $result = true;
        if (is_dir($path . '/' . $moduleName)) {
            $result = $result && mkdir($path . '/' . $moduleName . '/' . 'routes');
            $result = $result && mkdir($path . '/' . $moduleName . '/' . 'Controllers');
            $result = $result && mkdir($path . '/' . $moduleName . '/' . 'views');
            $result = $result && mkdir($path . '/' . $moduleName . '/' . 'migrations');
            $result = $result && mkdir($path . '/' . $moduleName . '/' . 'lang');
        } else {
            $this->error('Module ' . $moduleName . ' not exists');
        }
        return $result;
    }

    /**
     * Creates module class
     * Creates module class that contains all module data
     *
     * @param string $moduleName Module name
     * @param string $moduleFolder Module folder
     * @return string
     */
    protected function CreateLaravelModuleClass($moduleName, $moduleFolder)
    {
        $className = Str::ucfirst(Str::lower($moduleName)) . 'Module';
        $namespace = 'App\\' . str_replace('/', '\\', config('laravelModules.modules_folder')) . '\\' . $moduleName;

        file_put_contents(
            $moduleFolder . '/' . $className . '.php',
            $this->compileModuleClassStub($className, $namespace, $moduleName, $moduleName)
        );

        return $namespace;
    }

    /**
     * Compile stub for module class file
     *
     * @param string $className Class name
     * @param string $namespace Class namespace
     * @param string $moduleId Module id (for module info)
     * @param string $moduleName Module name (for module info)
     * @return bool|mixed|string
     */
    protected function compileModuleClassStub($className, $namespace, $moduleId, $moduleName)
    {
        $content = file_get_contents(__DIR__ . '/stubs/ExampleModule.stub');

        $content = str_replace(
            '{{namespace}}',
            $namespace,
            $content
        );

        $content = str_replace(
            '{{className}}',
            $className,
            $content
        );

        $content = str_replace(
            '{{moduleId}}',
            $moduleId,
            $content
        );

        $content = str_replace(
            '{{moduleName}}',
            $moduleName,
            $content
        );

        return $content;
    }

    /**
     * Creates default module controller
     *
     * @param string $moduleName Module name
     * @param string $moduleFolder Module folder
     * @return string
     */
    protected function CreateLaravelModuleController($moduleName, $moduleFolder)
    {
        $controllerName = Str::ucfirst(Str::lower($moduleName)) . 'Controller';
        $namespace = 'App\\' . str_replace('/', '\\', config('laravelModules.modules_folder')) . '\\' . $moduleName . '\\Controllers';

        file_put_contents(
            $moduleFolder . '/Controllers/' . $controllerName . '.php',
            $this->compileModuleControllerStub($controllerName, $namespace, $moduleName)
        );

        return $namespace;
    }

    /**
     * Compile stub for module class file
     *
     * @param string $controllerName Controller class name
     * @param string $namespace Controller class namespace
     * @param string $moduleName Module name
     * @return bool|mixed|string
     */
    protected function compileModuleControllerStub($controllerName, $namespace, $moduleName)
    {
        $content = file_get_contents(__DIR__ . '/stubs/Controllers/ExampleController.stub');

        $content = str_replace(
            '{{namespace}}',
            $namespace,
            $content
        );

        $content = str_replace(
            '{{controllerName}}',
            $controllerName,
            $content
        );

        $content = str_replace(
            '{{moduleName}}',
            $moduleName,
            $content
        );

        return $content;
    }

    /**
     * Create module default routes file
     *
     * @param string $moduleName Module name
     */
    protected function CreateRoutes($moduleName)
    {
        $path = app_path(config('laravelModules.modules_folder'));
        $routesFolder = $path . '/' . $moduleName . '/' . 'routes';
        $moduleName = Str::ucfirst(Str::lower($moduleName));
        file_put_contents(
            $routesFolder . '/routes.php',
            $this->compileRoutesStub($moduleName)
        );
    }

    /**
     * Compile stub for module routes file
     *
     * @param $moduleName
     * @return bool|mixed|string
     */
    protected function compileRoutesStub($moduleName)
    {
        $content = file_get_contents(__DIR__ . '/stubs/routes/routes.stub');

        $content = str_replace(
            '{{moduleName}}',
            $moduleName,
            $content
        );

        return $content;
    }

    /**
     * Creates default view file
     *
     * @param string $moduleName Module name
     */
    protected function CreateView($moduleName)
    {
        $path = app_path(config('laravelModules.modules_folder'));
        $viewsFolder = $path . '/' . $moduleName . '/' . 'views';
        file_put_contents(
            $viewsFolder . '/index.blade.php',
            file_get_contents(__DIR__ . '/stubs/views/index.blade.stub')
        );
    }
}