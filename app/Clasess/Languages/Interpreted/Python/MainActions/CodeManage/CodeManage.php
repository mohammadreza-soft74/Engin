<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:42 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\MainActions\CodeManage;


use App\Clasess\Base\MainActions\BaseCodeManager\BaseCodeManager;

class CodeManage extends BaseCodeManager
{
    // return final codes , derived from CodeManager base class
    public function finalCode(String $path, String $key)
    {
        return parent::finalCode($path, $key);
    }

    // reset user codes in container , derived from CodeManager base class
    public function resetCode(String $path, String $key)
    {
        return parent::resetCode($path, $key);
    }
}