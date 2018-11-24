<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 11:08 AM
 */

namespace App\Clasess\Languages\Interpreted\Python;


use App\Clasess\Languages\Interpreted\Python\MainActions\Create\Create;
use App\Clasess\Languages\Interpreted\Python\MainActions\PageLoad\PageLoad;
use App\Clasess\Languages\Interpreted\Python\MainActions\Run\Run;

class Python
{

    /**
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function create($request)
    {
        $create = new Create();
        $result = $create->createContainer($request);

        return $result;
    }

    /**
     * @param $request
     * @return bool|mixed
     * @throws \Exception
     */
    public function pageLoad($request)
    {
        $pageLoad = new PageLoad();
        $result = $pageLoad->PageLoad($request);

        return $result;
    }
}