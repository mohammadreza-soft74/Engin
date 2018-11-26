<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 1:10 PM
 */

namespace App\Clasess\Update;

use App\Clasess\Base\Managers\ContainerManager\ContainerManager;
use App\Clasess\Base\Memory\RedisClientFactory;
use App\Clasess\Base\Managers\KeyManager\KeyManager;


class Update
{

    /**
     * @param $courseId
     * @param $runnerTarFile
     * @param string $type
     * @return string
     * @throws \Exception
     */
    public function updateRunnerApplicationOnContainer($courseId, $runnerTarFile, $type = "key")
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