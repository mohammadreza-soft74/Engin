<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:48 PM
 */

namespace App\Clasess\Base\Managers\FileManager;

use App\Clasess\Base\Managers\KeyManager\KeyManager;

class FileManager
{
    /**
     * @brief get file from host
     * @param $path
     * @param $type
     * @param $key
     * @return array|false|string
     * @throws \Exception
     */
    public function getDefaultFiles($path, $type, $key)
    {

        $courseConfig = KeyManager::getCourseConfig($key);
        $content = [];

        $path = $courseConfig["files_on_host"] . $path ."/$type"; //requesr->path = /py1/page1
        if (!is_dir($path))
            throw  new \Exception("directory is not available !");

        if (!$dh = opendir($path))
            throw  new \Exception("opendir result must be Resource!");


        while ($file = readdir($dh)) {


            if($file ==".." or $file == ".")
                continue;

            $input = file_get_contents($path."/".$file);

            if ($input == "")
                $input=" ";

            //if ($input == null)
            // continue;

            $content[] = ["name" => $file, "content" => $input];
        }

        return $content;
    }
}