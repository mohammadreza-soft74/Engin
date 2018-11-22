<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 1:05 PM
 */

namespace App\Clasess\Base\BaseResultHandler;


class BaseResultHandler
{

    /**
     * todo : bash result handler . rewrite at bash training course
     *
     */
    public function  bash()
    {

    }

    /**
     * @brief reset code and final code result handler
     * @param $result
     * @return array
     * @throws \Exception
     */
    public function codes($result)
    {

        if ($result['error'] == true)
            throw new \Exception("Container => ". $result["message"]);

        return[
            'error'=>false,
            'result'=> $result['result']
        ];

    }

    /**
     * @brief create action result handler
     * @param $result
     * @return array
     * @throws \Exception
     */
    public function create($result)
    {

        if ($result['running'] == false)
            throw new \Exception("An error occurred on Container running!\n"."The container may not have started yet" );

        $res=[
            'error'=> false,
            'result'=> $result
        ];

        return $res;

    }

    /**
     * @brief run action result handler
     * @param $result
     * @return array
     * @throws \Exception
     */
    public function run($result)
    {


        $display = $result["display"];
        $result = $result['result'];

        if (!isset($result['error']))
            throw new \Exception("container internal error: " .json_encode($result));


        if ($result['error'] == true)
            throw new \Exception("An error occurred on Container!\n" . 'Error Message:' . $result['message']);





        return [
            "error" => false,
            "result" => $result["result"],
            "display" => $display

        ];


    }

    /**
     * @brief pageload action result handler
     * @param $result
     * @return array|string
     * @throws \Exception
     */
    public function pageLoad($result)
    {


        if (is_bool($result))
            return (string)$result;


         if ($result['error'] == true)
             throw new \Exception("An error occurred on Container!\n" .$result["message"]);


        return [
            "error" => false,
            "watcher" => $result["watcher"]
        ];


    }
}