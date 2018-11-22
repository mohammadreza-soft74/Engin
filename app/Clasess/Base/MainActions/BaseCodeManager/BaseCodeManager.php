<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:47 PM
 */

namespace App\Clasess\Base\MainActions\BaseCodeManager;

use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\FileManager\FileManager;


class BaseCodeManager
{
    /**
     * @brief return final code from host.
     * @param String $path
     * @param String $key
     * @return array
     * @throws \Exception
     */
    protected function  finalCode(String $path, String $key)
    {

        KeyManager::updateTimeStamp($key);

        $request['path'] = $path;

        $file = new FileManager();
        $files = $file->getDefaultFiles($path, 'last',$key);

        $result =  [
            'error' => false,
            'result'=> $files

        ];
        return $result;


    }
}