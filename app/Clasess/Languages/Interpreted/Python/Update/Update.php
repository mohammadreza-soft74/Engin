<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 2:58 PM
 */

namespace App\Clasess\Languages\Interpreted\Python\Update;


class Update extends \App\Clasess\Base\Update\Update
{
    public function updateRunnerApplication($courseId, $runnerTarFile, $type = "key")
    {
        return parent::updateRunnerApplication($courseId, $runnerTarFile, $type);
    }
}