<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:28 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\MainActions\CodeManage;


use App\Clasess\Base\MainActions\BaseCodeManager\BaseCodeManager;

class CodeManage extends BaseCodeManager
{
    /**
     * @param String $path
     * @param String $key
     * @return array|false|null|string
     * @throws \Exception
     */
    public function resetCode(String $path, String $key)
    {
        return parent::resetCode($path, $key);
    }

    /**
     * @param String $path
     * @param String $key
     * @return array
     * @throws \Exception
     */
    public function finalCode(String $path, String $key)
    {
        return parent::finalCode($path, $key);
    }
}