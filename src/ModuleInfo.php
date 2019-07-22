<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 18:48
 */

namespace reenekt\LaravelModules;

/**
 * Class ModuleInfo
 * Contains information about module
 * @package reenekt\LaravelModules
 * @author reenekt
 */
class ModuleInfo
{
    /** @var string $id Identifier of module. Will be used as views and routes names prefix */
    public $id;

    /** @var string $name Name of module */
    public $name;

    /** @var string $description Description of module */
    public $description;

    /** @var string $version Version of module */
    public $version;
}