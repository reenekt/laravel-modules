<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 16:26
 */

namespace reenekt\LaravelModules\Tests\Unit;

use Illuminate\Support\Str;
use reenekt\LaravelModules\ModuleLoader;

class ModuleLoaderTest extends LaravelModuleTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $moduleName = 'example';
        $this->artisan('module:generate', ['name' => $moduleName]);
        // Reload app modules
        (new \reenekt\LaravelModules\LaravelModulesServiceProvider($this->app))->boot();
    }

    /**
     * Test module's working
     * Test module's route exists
     * @throws \Exception
     */
    public function testLoadModules()
    {
        $moduleName = 'example';
        $moduleClassFileName = Str::ucfirst(Str::lower($moduleName)) . 'Module.php';

        $modules = ModuleLoader::LoadModules();
        $this->assertNotEmpty($modules, 'No module has been loaded');
        $appRoutes = [];
        $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
        /** @var \Illuminate\Routing\Route $value */
        foreach ($routeCollection as $value) {
            $appRoutes[] = $value->getAction();
        }
        $this->assertNotEmpty($appRoutes, 'No module\'s routes has been loaded');
        $this->assertEquals('web', $appRoutes[0]['middleware'][0], 'Loaded example route must have web middleware');
        $this->assertEquals('App\Modules\example\Controllers', $appRoutes[0]['namespace'], 'Wrong example route\'s namespace');
        $this->assertEquals('App\Modules\example\Controllers\ExampleController@index', $appRoutes[0]['controller'], 'Wrong example route\'s controller');
//        print_r($appRoutes);

        $this->ClearSandbox($moduleName, $moduleClassFileName);
    }
}
