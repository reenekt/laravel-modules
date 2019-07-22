<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 16:26
 */

namespace reenekt\LaravelModules\Tests\Unit\Commands;

use Illuminate\Support\Str;
use reenekt\LaravelModules\Tests\Unit\LaravelModuleTestCase;

class ModuleGenerateCommandTest extends LaravelModuleTestCase
{

    /**
     * Test artisan command for generating module
     */
    public function testModuleGenerateCommand()
    {
        try {
            $moduleName = 'example';
            $moduleClassFileName = Str::ucfirst(Str::lower($moduleName)) . 'Module.php';
            $this->artisan('module:generate', ['name' => $moduleName]);
            $this->assertTrue(is_dir($this->path . '/' . $moduleName), 'module\'s dir is not created');

            $this->assertTrue(file_exists($this->path . '/' . $moduleName . '/' . $moduleClassFileName), 'module\'s Class is not created');
            $contentExpected = file_get_contents(dirname(__DIR__) . '/data/ExampleModule.txt');
            $this->assertEquals($contentExpected, file_get_contents($this->path . '/' . $moduleName . '/' . $moduleClassFileName), 'Module class\'s file contents is wrong');

            $this->assertTrue(is_dir($this->path . '/' . $moduleName . '/routes'), 'module\'s routes dir is not created');
            $this->assertTrue(is_dir($this->path . '/' . $moduleName . '/Controllers'), 'module\'s Controllers dir is not created');
            $this->assertTrue(is_dir($this->path . '/' . $moduleName . '/views'), 'module\'s views dir is not created');
            $this->assertTrue(is_dir($this->path . '/' . $moduleName . '/migrations'), 'module\'s migrations dir is not created');
            $this->assertTrue(is_dir($this->path . '/' . $moduleName . '/lang'), 'module\'s lang dir is not created');

            $this->assertTrue(file_exists($this->path . '/' . $moduleName . '/routes/routes.php'), 'routes.php is not created');
            $contentExpected = file_get_contents(dirname(__DIR__) . '/data/routes.txt');
            $this->assertEquals($contentExpected, file_get_contents($this->path . '/' . $moduleName . '/routes/routes.php'), 'content of routes.php is wrong');

            $this->assertTrue(file_exists($this->path . '/' . $moduleName . '/Controllers/ExampleController.php'), 'ExampleController.php is not created');
            $contentExpected = file_get_contents(dirname(__DIR__) . '/data/ExampleController.txt');
            $this->assertEquals($contentExpected, file_get_contents($this->path . '/' . $moduleName . '/Controllers/ExampleController.php'), 'content of ExampleController.php is wrong');

            $this->assertTrue(file_exists($this->path . '/' . $moduleName . '/views/index.blade.php'), 'index.blade.php is not created');

        } catch (\Exception $exception) {
            $this->fail($exception->getMessage());
        } finally {
            $this->ClearSandbox($moduleName, $moduleClassFileName);
        }
    }
}
