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
        $this->webSocketStream = ContainerManager::containerAttachWebSocket($containerId);
    }

    // write to web socket
    public function write($data)
    {
        ContainerManager::writeToWebSocket($this->webSocketStream,$data);
    }

    // read from web socket
    public function read($wait)
    {
        $response = ContainerManager::readFromWebSocket($this->webSocketStream,$wait);

        return $response;
    }
}