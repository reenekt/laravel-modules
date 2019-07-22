<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 20.07.2019
 * Time: 18:51
 */

namespace reenekt\LaravelModules;

/**
 * Class AbstractLaravelModule
 * Base class for Laravel modules
 *
 * @package reenekt\LaravelModules
 * @author reenekt
 */
abstract class AbstractLaravelModule extends AbstractModule
{
    /**
     * Returns module routes
     * Returns module routes as [route type] => [route file path]
     * @example `return ['web' => 'routes/web.php']`
     * @return array
     * @author reenekt
     */
    public function GetRoutes(): array
    {
        return [];
    }

    /**
     * Returns module migrations folders
     * @example `return ['migrations']`
     * @return array
     * @author reenekt
     */
    public function GetMigrations(): array
    {
        return [];
    }

    /**
     * Returns module translations folders
     * @example `return ['lang']`
     * @return array
     * @author reenekt
     */
    public function GetTranslations(): array
    {
        return [];
    }

    /**
     * Returns module views folders
     * @example `return ['views']`
     * @return array
     * @author reenekt
     */
    public function GetViews(): array
    {
        return [];
    }

    /**
     * Returns module controllers folder
     * @return string
     * @author reenekt
     */
    public function GetControllersPath(): string
    {
        return 'Controllers';
    }
}