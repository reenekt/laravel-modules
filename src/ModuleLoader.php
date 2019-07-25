<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 15:11
 */

namespace reenekt\LaravelModules;


use reenekt\LaravelModules\Helpers\ReflectionHelper;

/**
 * Class ModuleLoader
 * @package reenekt\LaravelModules
 * @author reenekt
 */
class ModuleLoader
{
    /**
     * Returns all laravel modules
     * Return all laravel modules as [module directory => module class instance (object)]
     * @return array
     * @author reenekt
     * @throws \ReflectionException
     */
    public static function LoadModules()
    {
        $path = app_path(config('laravelModules.modules_folder'));
        if (!is_dir($path)) {
            mkdir($path);
        }
        $modules = [];
        $modulesFolders = glob($path . '/*', GLOB_ONLYDIR);
        if (count($modulesFolders) > 0) {
            foreach ($modulesFolders as $modulesFolder) {
                $laravelModuleClass = static::GetLaravelModule($modulesFolder);
                if ($laravelModuleClass) {
                    $modules[$modulesFolder] = $laravelModuleClass;
                }
            }
        }
        return $modules;
    }

    /**
     * Return laravel module class
     * Return laravel module class that contains all module data or null if module class not exists
     * @param $moduleFolder
     * @return AbstractLaravelModule|null
     * @author reenekt
     * @throws \ReflectionException
     */
    protected static function GetLaravelModule($moduleFolder)
    {
        $phpFiles = glob($moduleFolder . '/*.php');
        if (count($phpFiles) > 0) {
            foreach ($phpFiles as $phpFile) {
                $reflectionClass = ReflectionHelper::GetReflectionClassFromFile($phpFile);
                if ($reflectionClass) {
                    if (is_subclass_of($reflectionClass->getName(), AbstractLaravelModule::class)) {
                        $moduleClass = $reflectionClass->getName();
                        return new $moduleClass();
                    }
                }
            }
        }
        return null;
    }
}