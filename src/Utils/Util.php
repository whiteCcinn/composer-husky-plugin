<?php

namespace Husky\Utils;

class Util
{
    const PHP5 = 5;

    const PHP7 = 7;

    public static $vendor = 'vendor';

    /**
     * @param $dir
     *
     * @return array
     */
    public static function getAllFiles($dir)
    {
        $fileArr = [];
        if (is_dir($dir)) {
            if ($dh = @opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $fileArr[] = $file;
                    }
                }
                //关闭
                closedir($dh);
            }
        }

        return $fileArr;
    }

    /**
     * @return array
     */
    public static function getAllCommands()
    {
        $root = self::getDirName(__DIR__);
        $path = $root . DIRECTORY_SEPARATOR . 'Commands';

        return self::getAllFiles($path);
    }

    /**
     * @param     $path
     * @param int $level
     *
     * @return string
     */
    public static function getDirName($path, $level = 1)
    {
        if ((int)substr(PHP_VERSION, 0, 1) === static::PHP5) {
            $dirName = $path;
            while ($level > 0) {
                $dirName = dirname($dirName);
                $level--;
            }
        } else if ((int)substr(PHP_VERSION, 0, 1) === static::PHP7) {
            $dirName = dirname($path, $level);
        } else {
            die('Not support php version');
        }

        return $dirName;
    }

    public static function getPhpDir()
    {
        return str_replace('\\', '/', getcwd());
    }

    public static function getGitDir($path)
    {
        if (!self::existsGitDir($path)) {
            $path = self::getDirName($path);

            return self::getGitDir($path);
        }

        if ($path === '/') {
            return false;
        }

        return $path;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public static function existsGitDir($path)
    {
        $dir = '.git';

        if (file_exists($path . DIRECTORY_SEPARATOR . $dir)) {
            return true;
        }

        return false;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public static function existsComposerFile($path)
    {
        $file = 'composer.json';

        if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
            return true;
        }

        return false;
    }
}
