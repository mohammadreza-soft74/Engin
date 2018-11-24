<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 2:50 PM
 */

namespace App\Clasess\Base\Update;

use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Memory\RedisClientFactory;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;

class Update
{
    protected function updateRunnerApplication($courseId, $runnerTarFile, $type = "key")
    {
        $courseConfig = KeyManager::getCourseConfig($courseId."-1");    // get python config
        $redis = RedisClientFactory::redis($type);

        $keys = $redis->keys($courseConfig["keysCacheName"]."*");   // get all keys of specified course(ex: python, ...)

        $file="";
        $tarFile = gzopen($runnerTarFile, 'r');

        while ($line = gzgets($tarFile,1024)) {
            $file .= $line;
        }

        foreach ($keys as $key)
        {
            $containerId = $redis->hget($key,"id");
            ContainerManager::copyFileToSpecifiedPathInContainer($containerId, $file, $courseConfig["runner_path"]);
        }

        return "updating ".$courseConfig["lang"] ." containers done successfully";


    }
}