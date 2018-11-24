<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:49 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\MainActions\PageLoad;


use App\Clasess\Base\MainActions\BasePageLoad\BasePageLoad;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;
use App\Clasess\Base\Managers\KeyManager\KeyManager;

class PageLoad extends BasePageLoad
{
    // global variable for container id
    private $containerId;

    /**
     * python onLoadeComplete() function derived from PageLaod base class
     * @param $data
     * @return bool|mixed
     * @throws \Exception
     */
    public function PageLoad($req)
    {

        $this->containerId = parent::PageLoad($req);

        $processes = ContainerManager::getProcessInContainer($this->containerId);
        foreach ($processes->getProcesses() as $process) {
            if ($process[7] == "node /home/violin/xterm/demo/files.js")
                exec("kill -9 $process[1]");
        }

        $path = ['path'];
        $path = "/home/violin/python$path";
        ContainerManager::dockerExecStart( $this->containerId, "PORT=7682 START=$path node /home/violin/xterm/demo/files.js 2>> /home/violin/log.txt",$path);

        $this->checkProcess($processes);

        $ports = KeyManager::mappedPortsOnContainer( $this->containerId);

        $result["watcher"] = $ports["watcher"];

        return $result;
    }

    /**
     * check if process is up or not
     * @param $processes
     * @throws \Exception
     */
    private function checkProcess($processes)
    {
        foreach ($processes->getProcesses() as $item) {
            if ($item[7] == "node /home/violin/xterm/demo/files.js")
                return ;
        }
        $containerProcess = ContainerManager::getProcessInContainer( $this->containerId);
        $this->checkProcess($containerProcess);
    }
}