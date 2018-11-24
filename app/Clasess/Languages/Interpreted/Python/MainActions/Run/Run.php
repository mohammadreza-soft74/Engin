<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:49 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\MainActions\Run;


use App\Clasess\Base\MainActions\BaseRun\BaseRun;
use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;

class Run extends BaseRun
{
    public function run($req)
    {
        $result = parent::run($req);
        $key = $req["key"];
        $courseConfig = KeyManager::getCourseConfig($key);

        if(!$containerId = KeyManager::checkContainerIdWithKey($key))
            throw new \Exception("container is not available ");

        $processes = ContainerManager::getProcessInContainer($containerId);
        foreach ($processes->getProcesses() as $process) {
            if ($process[7] == "node /home/violin/xterm/demo/server.js python script.py")
                exec("kill -9 $process[1]");

        }

        $workDir = $courseConfig["ContainerFiles"].$req['path'];
        $command = "START=$workDir PORT=7681 node /home/violin/xterm/demo/server.js {$courseConfig["exec"]} {$courseConfig["defaultFileForExecute"]} 2>> /home/violin/log.txt";
        ContainerManager::dockerExecStart($containerId, $command, $workDir);

        return $result;

    }
}