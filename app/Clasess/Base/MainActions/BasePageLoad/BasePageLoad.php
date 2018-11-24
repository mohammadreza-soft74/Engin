<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:35 PM
 */

namespace App\Clasess\Base\MainActions\BasePageLoad;

use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;


class BasePageLoad
{
    /**
     * @brief on moodle page load this function just check container availability and its state
     * @param $data
     * @return bool
     * @throws \Exception
     */
    protected function PageLoad($req)
    {

        $key = $req['key'];

        if (!$containerId = KeyManager::checkContainerIdWithKey($key))
            throw new \Exception("Error: Container is not available \n.check Key " );

        if (!ContainerManager::getContainerState($containerId))
            ContainerManager::startContainer($containerId);

        KeyManager::updateTimeStamp($key);

        return $containerId;
    }
}