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
use App\Clasess\Base\Communication\CommunicateFactory;


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

    /**
     * reset codes in specified path
     * replace Default codes with user code in container
     * @param String $path
     * @param String $key
     * @return array|false|null|string
     * @throws \Exception
     */
    protected function resetCode(String $path, String $key)
    {
        KeyManager::updateTimeStamp($key);
        $request['requestType'] = 'reset';
        $request['path'] = $path;

        $file = new FileManager();
        $request['files'] = $file->getDefaultFiles($path,'first',$key);

        $containerId = KeyManager::checkContainerIdWithKey($key);

        if (!$containerId)
            throw new \Exception("Error: Container is not available \n.check Key !" );

        $webSocket = CommunicateFactory::communicateMethod("socket", $containerId);
        $data = json_encode($request)."\n";
        $webSocket->write($data);
        $result = $webSocket->read(5);
        $result = json_decode($result, true);

        return $result;
    }
}