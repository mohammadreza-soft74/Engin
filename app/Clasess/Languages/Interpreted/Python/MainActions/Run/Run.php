<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:49 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\MainActions\Run;


use App\Clasess\Base\MainActions\BaseRun\BaseRun;
use App\Clasess\Base\Managers\FileManager\FileManager;
use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;

class Run extends BaseRun
{
    /**
     * @brief  run python code
     *
     * @param $req
     * @return mixed
     * @throws \Exception
     */
    public function run($req)
    {
		parent::run($req);

		$key = $req["key"];
		$path = $req["path"];

		$courseConfig = KeyManager::getCourseConfig($key);
		$container_shared_files = $courseConfig['container_shared_files'];

		FileManager::createFiles($req["files"],$container_shared_files.$key.$path);

		$workDir = $courseConfig["ContainerFiles"].$req['path'];
		$command = "{$courseConfig["exec"]} {$courseConfig["defaultFileForExecute"]}";

		$execId = ContainerManager::exec($key, $command, $workDir);

		return $execId;
    }
}