<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:38 PM
 */

namespace App\Clasess\Base\MainActions\BaseRun;

use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;
use App\Clasess\Base\Communication\CommunicateFactory;

class BaseRun
{
    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    protected function run($data)
    {
        try {

            $key = $data["key"];

            //courseName(ex: python , java , ...) is an array that contain some configs stored in config/files.php
            //return each language config with course id.
            $courseConfig = KeyManager::getCourseConfig($key);

            //check existence of the given path on host . if its not valid throw an Exeption
            $path = $courseConfig["files_on_host"] . $data["path"];
            if (!is_dir($path))
                throw  new \Exception("directory is not available ! \n may be this is not valid path!");

            //check for container availability with key
            //it checks that container id is available on key file on host or not.
            if (!$containerId = KeyManager::checkContainerIdWithKey($key))
                throw new \Exception("container is not available ");

            //check container state (Running(1)/Existed(0)).
            if (!ContainerManager::getContainerState($containerId))
                ContainerManager::startContainer($containerId);

            KeyManager::updateTimeStamp($key);

            //get apache mapped port on container to send post request.
            $mappedPorts = KeyManager::mappedPortsOnContainer($containerId);


            //create WebSocket object to send and receive data to container.
            $webSocket = CommunicateFactory::communicateMethod("socket", $containerId);
            $send = json_encode($data) . "\n";
            $webSocket->write($send);
            $result = $webSocket->read(5);
            $result = json_decode($result, true);


            //ports return from host.
            $ret["result"] = $result;
            $ret["display"] = $mappedPorts["display"];

        }catch (\Exception $e){
            throw $e;
        }


        return $ret;


    }
}