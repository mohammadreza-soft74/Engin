<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:44 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\MainActions\Create;


use App\Clasess\Base\MainActions\BaseCreate\BaseCreate;

class Create extends BaseCreate
{
    /**
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function createContainer($request)
    {
        return parent::createContainer($request["key"]);
    }
}