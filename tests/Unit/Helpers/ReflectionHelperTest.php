<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 21.07.2019
 * Time: 15:17
 */

namespace reenekt\LaravelModules\Tests\Unit\Helpers;

use reenekt\LaravelModules\Helpers\ReflectionHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class ReflectionHelperTest
 * @package reenekt\LaravelModules\Tests\Unit\Helpers
 * @skip
 */
class ReflectionHelperTest extends TestCase
{
    public function setUp(): void
    {
        $this->markTestSkipped('Test classes was replaced by their stubs');
    }

    public function testGetClassName()
    {
        $filepath = 'D:\OpenServer_5_3_0\OSPanel\domains\laravel-modules\src\Commands\stubs\ExampleModule.stub';
        try {
            $className = ReflectionHelper::GetClassName($filepath);
        } catch (\Exception $exception) {
            $this->fail($exception->getMessage());
        }
        $expectedClassName = 'ExampleModule';
        $this->assertEquals($expectedClassName, $className, 'Class name is wrong');
    }

    public function testGetNamespace()
    {
        $filepath = 'D:\OpenServer_5_3_0\OSPanel\domains\laravel-modules\src\Commands\stubs\ExampleModule.stub';
        try {
            $namespace = ReflectionHelper::GetNamespace($filepath);
        } catch (\Exception $exception) {
            $this->fail($exception->getMessage());
        }
        $expectedNamespace = 'reenekt\LaravelModules\Commands\stubs';
        $this->assertNotNull($namespace, 'Namespace must be bot null');
        $this->assertEquals($expectedNamespace, $namespace, 'Namespace is wrong');
    }

    public function testGetReflectionClassFromFile()
    {
        $filepath = 'D:\OpenServer_5_3_0\OSPanel\domains\laravel-modules\src\Commands\stubs\ExampleModule.php';
        try {
            $reflectionClass = ReflectionHelper::GetReflectionClassFromFile($filepath);
        } catch (\Exception $exception) {
            $this->fail($exception->getMessage());
        }
        $expectedReflectionClass = new \ReflectionClass('reenekt\LaravelModules\Commands\stubs\ExampleModule');
        $this->assertEquals($expectedReflectionClass->getName(), $reflectionClass->getName(), 'Namespace is wrong');
    }
}
