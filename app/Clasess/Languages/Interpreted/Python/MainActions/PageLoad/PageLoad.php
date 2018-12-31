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

class PageLoad extends BasePageLoad
{
    // global variable for container id
    private $containerId;

    /**
     * @brief python PageLoad() function derived from PageLaod base class
     *
     * @param $req
     * @return bool|mixed
     * @throws \Exception
     */
    public function PageLoad($req)
    {

        $this->containerId = parent::PageLoad($req);

        $path = "/home/violin/python".$req['path'];

        $execId = ContainerManager::exec( $this->containerId, "bash files.sh $path");

        $result["execId"] = $execId;

        return $result;
    }
}