<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\RequestValidate\RequestValidate;

class Engine extends Controller
{
    /**
     * create  the specified language container
     * @param Request $request
     * @return array|false|string
     */
    public function createContainer(Request $request)
    {

        try{

            $key = $request->key;
            $request = RequestValidate::createValidator($request);

            $courseConfig = KeyManager::getCourseConfig($request->key);


            $languageActions = new $courseConfig["LanguageActions"];
            $result = $languageActions->create($request);

            $resultHandler = new $courseConfig["ResultHandler"];
            $result = $resultHandler->create($result);

            return $result;


        }catch (\Exception $e){

            return $this->generateRunError($e->getMessage()." | ". $e->getFile() . " | ". $e->getLine(),$key);
        }

    }
}
