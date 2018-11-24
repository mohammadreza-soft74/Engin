<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 11:58 AM
 */

namespace App\Clasess\Base\Communication;


class CommunicateFactory
{
    /**
     * @brief choice communicate method to send data to container
     * @param string $type
     * @param null $containerId
     * @return Post|WebSocket
     * @throws \Exception
     */
    public static function communicateMethod($type = "socket", $containerId = null)
    {
        $communicateMethod = null;
        switch ($type)
        {
            case ("socket"):

                $communicateMethod = new WebSocket($containerId);
                break;


            case ("post"):

                $communicateMethod = new Post();
                break;
        }

        return $communicateMethod;
    }
}