<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:28 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\MainActions\PageLoad;


use App\Clasess\Base\MainActions\BasePageLoad\BasePageLoad;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;
use App\Clasess\Base\Managers\KeyManager\KeyManager;

class PageLoad extends BasePageLoad
{
    private $processLoopCount = 0;
    private $containerId;
    public function PageLoad($request)
    {

        $key = $request['key'];

        if(!$this->containerId = KeyManager::checkContainerIdWithKey($key))
            throw new \Exception("container is not available ");

        $containerProcess = ContainerManager::getProcessInContainer( $this->containerId);

        $path = "/var/www/html/js".$request['path'];
        ContainerManager::dockerExecStart($this->containerId, "PORT=7682 START=$path node /home/violin/xterm/demo/files.js 2> /home/violin/file_err.txt", $path);

        parent::PageLoad($request);

        $ports = KeyManager::mappedPortsOnContainer($this->containerId);


        $this->checkProcess($containerProcess);

        $result["watcher"] = $ports["watcher"];

        return $result;
    }

    /**
     * @param $processes
     * @throws \Exception
     */
    private function checkProcess($processes)
    {
        if ($this->processLoopCount >= 3)
            throw new \Exception("Error: file.js couldn't start!");

        foreach ($processes->getProcesses() as $item) {
            if ($item[7] == "node /home/violin/xterm/demo/files.js")
                return ;
        }
        $containerProcess = ContainerManager::getProcessInContainer( $this->containerId);

        $this->processLoopCount++ ;
        sleep(1);

        $this->checkProcess($containerProcess);
    }
}