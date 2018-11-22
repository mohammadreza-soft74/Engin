<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 11:57 AM
 */

namespace App\Clasess\Base\Communication;

use App\Clasess\Base\Managers\ContainerManager\ContainerManager;

class WebSocket
{
    private $webSocketStream;

    public function __construct($containerId)
    {
        try {

            $this->webSocketStream = ContainerManager::containerAttachWebSocket($containerId);

        }catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function write($data)
    {
        try {
            ContainerManager::writeToWebSocket($this->webSocketStream, $data);
        }catch (\Exception $e){
            throw $e;
        }
    }

    // read from web socket
    public function read($wait)
    {
        try {

            $response = ContainerManager::readFromWebSocket($this->webSocketStream, $wait);
        }catch (\Exception $e){
            throw $e;
        }

        return $response;
    }
}