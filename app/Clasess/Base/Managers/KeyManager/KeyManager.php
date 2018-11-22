<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 3:09 PM
 */

namespace App\Clasess\Base\Managers\KeyManager;


class KeyManager
{
    /**
     * @brief check container existence.
     *
     * @detail get user key and check container existence , if its available returns containerId.
     * @fn static checkContainerIdWithKey
     * @see KeyManager:
     * @param $key
     * @return bool
     * @throws \Exception
     */
    public static function checkContainerIdWithKey($key)
    {
        //if containerId is available or exist it return container id else return false to create and start corresponding container
        $containerId = self::searchContainerIdInMemory($key);

        if ($containerId)
            return $containerId;
        else
            return false;

    }


}