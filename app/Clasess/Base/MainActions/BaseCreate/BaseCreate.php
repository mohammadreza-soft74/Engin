<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 3:06 PM
 */

namespace App\Clasess\Base\MainActions\BaseCreate;


use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;
use Docker\API\Exception\ContainerInspectNotFoundException;
use Docker\API\Model\ContainersCreatePostBody;

class BaseCreate
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
     * @throws \Exception
     */
    protected function createContainer($key)
    {

    	try{

			if (!ContainerManager::getContainerState( $key)) // get state of container(0=exited/1=running)
				ContainerManager::startContainer($key);

			$state = ContainerManager::getContainerState($key);

		}catch (\Exception $e){

    		if ($e instanceof ContainerInspectNotFoundException)
			{
				$containerConfig = new ContainersCreatePostBody();
				$courseConfig = KeyManager::getCourseConfig($key);  // Get the settings for the course(/config/files.php)
				$containerConfig->setImage($courseConfig["image"]);
				ContainerManager::setDefaultContainerConfig($containerConfig, $courseConfig, $key);
				$container = ContainerManager::createContainer( $containerConfig, $key);
				ContainerManager::startContainer($container->getId());
				KeyManager::setKeytoSpecifiedContainerId($key, $container->getId());
				$state = ContainerManager::getContainerState( $container->getId());

			}
		}
		return ['running'=>$state];
    }
}