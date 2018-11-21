<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 3:06 PM
 */

namespace App\Clasess\Base\MainActions\BaseCreate;


use App\Clasess\Base\Managers\KeyManager\KeyManager;

class Create
{
    /**
     * @brief create or start container.
     *
     * @detail create container its not exist if it exist start it and returns its state(running=1/stopped=0).
     * @fn protected createContainer
     * @see KeyManager:checkContainerIdWithKey
     * @see ContainerManager::getContainerState
     * @see KeyManager::updateTimeStamp
     * @param $key
     * @return array
     */
    protected function createContainer($key)
    {
        $containerId = KeyManager::checkContainerIdWithKey($key);  // check container availability with key on system

        switch($containerId){   // if container is available start it else create new container

            case (true):    // start container

                if (!ContainerManager::getContainerState( $containerId)) // get state of container(0=exited/1=running)
                    ContainerManager::startContainer($containerId);

                KeyManager::updateTimeStamp($key);  // update user actions(pageload, run, ...) time in redis to stop container
                $stats = ContainerManager::getContainerState($containerId); // get container state(0/1) to return to moodle

                break;

            case (false)://create container

                $containerConfig = new ContainersCreatePostBody();
                $courseConfig = KeyManager::getCourseConfig($key);  // Get the settings for the course(/config/files.php)
                $containerConfig->setImage($courseConfig["image"]);
                ContainerManager::setDefaultContainerConfig($containerConfig, $courseConfig);
                $container = ContainerManager::createContainer( $containerConfig, $key);
                ContainerManager::startContainer($container->getId());
                KeyManager::setKeytoSpecifiedContainerId($key, $container->getId());
                $stats = ContainerManager::getContainerState( $container->getId());

                break;
        }
        return ['running'=>$stats];
    }
}