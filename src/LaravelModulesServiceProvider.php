<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 14:59
 */

namespace reenekt\LaravelModules;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use reenekt\LaravelModules\Commands\ModuleGenerate;

class LaravelModulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/laravelModules.php' => config_path('laravelModules.php'),
        ]);
        /** @var AbstractLaravelModule[] $modules */
        try {
            $modules = ModuleLoader::LoadModules();
        } catch (\Exception $e) {
            Log::warning('[Laravel modules] Warning on load modules: ' . $e->getMessage());
        }
        foreach ($modules as $folder => $module) {
            $moduleInfo = $module->GetModuleInfo();
            $routes = $module->GetRoutes();
            foreach ($routes as $type => $route) {
                if (file_exists($folder . '/' . $route)) {
                    try {
                        $reflectionClass = new \ReflectionClass($module);
                    } catch (\ReflectionException $e) {
                        Log::warning('[Laravel modules] Warning on load module components: ' . $e->getMessage(), ['Module info' => $moduleInfo]);
                        continue;
                    }
                    $namespace = $reflectionClass->getNamespaceName() . '\\' . $module->GetControllersPath();
                    if ($type == 'web') {
                        Route::middleware('web')
                            ->namespace($namespace)
                            ->name($moduleInfo->id . '.')
                            ->prefix($moduleInfo->id)
                            ->group($folder . '/' . $route);
                    } elseif ($type == 'api') {
                        Route::middleware('api')
                            ->namespace($namespace)
                            ->name($moduleInfo->id . '.')
                            ->prefix($moduleInfo->id)
                            ->group($folder . '/' . $route);
                    }
                }
            }

            $views = $module->GetViews();
            foreach ($views as $view) {
                if (is_dir($folder . '/' . $view)) {
                    $this->loadViewsFrom($folder . '/' . $view, $moduleInfo->id);
                }
            }

            $migrations = $module->GetMigrations();
            foreach ($migrations as $migration) {
                if (is_dir($folder . '/' . $migration)) {
                    $this->loadMigrationsFrom($folder . '/' . $migration);
                }
            }

            $translations = $module->GetTranslations();
            foreach ($translations as $translation) {
                if (is_dir($folder . '/' . $translation)) {
                    $this->loadTranslationsFrom($folder . '/' . $translation, $moduleInfo->name);
                }
            }
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleGenerate::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/laravelModules.php',
            'laravelModules'
        );
    }
}