<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 11:57 AM
 */

namespace App\Clasess\Base\Communication;

use GuzzleHttp\Client;

class Post
{

    /**
     *  @brief send post to container.
     * todo: need some changes because of structure changes (change POST to websocket)
     * @param $port
     * @param $data
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendPost($port, $data)
    {
        try {
            $client = new Client();

            $Url = "http://127.0.0.1:{$port}/PythonRunner/App.php";
            // $Url = "http://127.0.0.1/ViolinRunner/App.php";
            $res = $client->request('POST', $Url, [
                'form_params' => [
                    "data" => json_encode($data)
                ]
            ]);


            return $res->getBody()->__toString();
        }catch (\Exception $e){

            throw new \Exception("Error: An error occurred while sending post \n".$e->getMessage());
        }
    }
}