<?php
/**
 * Created by PhpStorm.
 * User: reenekt
 * Date: 21.07.2019
 * Time: 15:12
 */

namespace reenekt\LaravelModules\Helpers;


class ReflectionHelper
{
    /**
     * Get namespace and class name from file
     *
     * @param string $filepath Path to PHP file
     * @return null|\ReflectionClass
     * @throws \ReflectionException
     */
    public static function GetReflectionClassFromFile($filepath)
    {
        $className = self::GetClassName($filepath);
        $namespace = self::GetNamespace($filepath);
        if ($className && $namespace) {
            return new \ReflectionClass($namespace . '\\' . $className);
        } else {
            return null;
        }
    }

    /**
     * Get class name from file
     *
     * @param string $filepath Path to PHP file
     * @return string
     * @throws \Exception
     */
    public static function GetClassName($filepath)
    {
        if (!$filepath) {
            throw new \Exception('File path is not defined!');
        }
        $fp = fopen($filepath, 'r');
        $class = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false && strpos($buffer, 'namespace')) continue;

            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                        }
                    }
                }
            }
        }
        return $class;
    }

    /**
     * Get namespace from file
     *
     * @param string $filepath Path to PHP file
     * @return null|string
     * @throws \Exception
     */
    public static function GetNamespace($filepath)
    {
        if (!$filepath) {
            throw new \Exception('File path is not defined!');
        }
        $tokens = token_get_all(file_get_contents($filepath));
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
        if (!$namespace_ok) {
            return null;
        } else {
            return $namespace;
        }
    }
}