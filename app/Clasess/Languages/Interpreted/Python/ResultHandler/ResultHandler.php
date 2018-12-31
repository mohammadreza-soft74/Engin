<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:49 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\ResultHandler;


use App\Clasess\Base\BaseResultHandler\BaseResultHandler;

class ResultHandler extends BaseResultHandler
{
    public function run($result)
    {
       return[
           "error"=>false,
           "execId"=>$result
       ];
    }

    public function pageLoad($result)
    {
        return
            [
                "error"=>false,
                "execId"=>$result["execId"]
            ];
    }
}