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

    /**
     * WebSocket constructor.
     * @param $containerId
     * @throws \Exception
     */
    public function __construct($containerId)
    {
        $this->webSocketStream = ContainerManager::containerAttachWebSocket($containerId);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function write($data)
    {

        ContainerManager::writeToWebSocket($this->webSocketStream, $data);

    }

    /**
     * @param $wait
     * @return array|false|null|string
     * @throws \Exception
     */
    public function read($wait)
    {
        $response = ContainerManager::readFromWebSocket($this->webSocketStream, $wait);

        return $response;
    }
}