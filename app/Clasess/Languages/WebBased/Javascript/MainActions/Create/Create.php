<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:28 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\MainActions\Create;


use App\Clasess\Base\MainActions\BaseCreate\BaseCreate;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;

class Create extends BaseCreate
{
    /**
     * create container
     * start apache
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function createContainer($request)
    {
        $parent = parent::createContainer($request['key']);   // create container for user or start, stopped container
        $command = "service apache2 start";
        ContainerManager::dockerExecStart($request['key'], $command);  // start apache in container

        return $parent;
    }
}