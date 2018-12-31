<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:42 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\MainActions\CodeManage;


use App\Clasess\Base\MainActions\BaseCodeManager\BaseCodeManager;
use App\Clasess\Base\Managers\FileManager\FileManager;

class CodeManage extends BaseCodeManager
{
    /**
     * @param String $path
     * @param String $key
     * @return array
     * @throws \Exception
     */
    public function finalCode(String $path, String $key)
    {
        return parent::finalCode($path, $key);
    }

    /**
     * @param String $path
     * @param String $key
     * @return array|false|null|string
     * @throws \Exception
     */
    public function resetCode(String $path, String $key)
    {

        parent::resetCode($path, $key);

        FileManager::recurse_copy("/home/mohammadreza/python/default_files/python$path/first","/home/mohammadreza/$key$path");

        return[
          "error" => false,
          "result" => "Files were successfully resetted!",
        ];

    }
}