<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 18:52
 */

namespace reenekt\LaravelModules;

/**
 * Class AbstractModule
 * Base class for application modules
 * @package reenekt\LaravelModules
 * @author reenekt
 */
abstract class AbstractModule
{
    abstract public function GetModuleInfo(): ModuleInfo;
}