<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 15:58
 */

namespace reenekt\LaravelModules\Tests\Unit;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use reenekt\LaravelModules\LaravelModulesServiceProvider;

abstract class LaravelModuleTestCase extends OrchestraTestCase
{
    /** @var string $path Path to modules folder */
    protected $path;

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelModulesServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->useAppPath(dirname(dirname(__DIR__)) . '/test_sandbox/app');
        $app['config']->set('laravelModules.modules_folder', 'Modules');
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->path = app_path(config('laravelModules.modules_folder'));
        if (!is_dir($this->path)) {
            mkdir($this->path);
        }
    }

    /**
     * Clear modules folder after tests running
     *
     * @param string $moduleName Module name
     * @param string $moduleClassFileName Name of module class
     */
    protected function ClearSandbox($moduleName, $moduleClassFileName)
    {
        unlink($this->path . '/' . $moduleName . '/routes/routes.php');
        unlink($this->path . '/' . $moduleName . '/Controllers/ExampleController.php');
        unlink($this->path . '/' . $moduleName . '/views/index.blade.php');

        rmdir($this->path . '/' . $moduleName . '/routes');
        rmdir($this->path . '/' . $moduleName . '/Controllers');
        rmdir($this->path . '/' . $moduleName . '/views');
        rmdir($this->path . '/' . $moduleName . '/migrations');
        rmdir($this->path . '/' . $moduleName . '/lang');

        unlink($this->path . '/' . $moduleName . '/' . $moduleClassFileName);
        rmdir($this->path . '/' . $moduleName);
    }
}
