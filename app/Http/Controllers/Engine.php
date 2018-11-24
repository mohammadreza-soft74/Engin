<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\RequestValidate\RequestValidate;
use App\Clasess\Calender\Calender;
use DateTime;
use DateTimeZone;
use Config;

define("1" , Config::get("languages_config.python"));
define("3" , Config::get("languages_config.java"));
define("2", Config::get("languages_config.javascript"));

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



            $req = RequestValidate::createValidator($request);
            $key = $req["key"];

            $courseConfig = KeyManager::getCourseConfig($key);


            $languageActions = new $courseConfig["LanguageActions"];
            $result = $languageActions->create($req);

            $resultHandler = new $courseConfig["ResultHandler"];
            $result = $resultHandler->create($result);

            return $result;


        }catch (\Exception $e){

            return $this->generateRunError($e->getMessage()." | ". $e->getFile() . " | ". $e->getLine(),$key);
        }
    }


    /**
     * @param Request $request
     * @return array
     */
    public function pageLoad(Request $request)
    {


        try {

            $key= null;
            //validate incoming request with defined rule
            $req = RequestValidate::pageloadValidator($request);

            $key = $req['key'];

            $courseConfig = KeyManager::getCourseConfig($key);
            //call onLoadComplete() function to something on moodle page load
            //Returns the user's code unless the code exists

            $languageActions = new $courseConfig["LanguageActions"];
            $result = $languageActions->pageLoad($req);


            $resultHandler = new $courseConfig["ResultHandler"];
            $result = $resultHandler->pageLoad($result);

        }catch (\Exception $e){

            return $this->generateRunError($e->getMessage(),$key);
        }

        return $result;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    function run(Request $request)
    {
        $key= null;
        // Validate Request
        $req = RequestValidate::runValidator($request);

        $key = $req["key"];

        // Get type from config, so we will know how we should run this language codes
        $courseConfig = KeyManager::getCourseConfig($req["key"]);

        // Run Request
        try {

            $run = new $courseConfig["LanguageActions"];
            $result = $run->run($req);

            // Handle(Manage) the result
            $resultHandler = new $courseConfig["ResultHandler"];
            $result = $resultHandler->run($result);

        } catch (\Exception $e) {
            return $this->generateRunError($e->getMessage(),$key);
        }

        // OK

        return $result;

    }

    /**
     * @param Request $request
     * @return array
     */
    public function resetUserCode(Request $request)
    {
        try
        {
            $key = null;
            //validate incoming request with defined rule
            $req = RequestValidate::resetFinalValidator($request);
            $key = $req['key'];

            $courseConfig = KeyManager::getCourseConfig($key);

            $languageActions = new $courseConfig["LanguageActions"];
            $result = $languageActions->resetCode($req);

            $resultHandler = new $courseConfig["ResultHandler"];
            $result = $resultHandler->codes($result);

        }catch (\Exception $e){

            return $this->generateRunError($e->getMessage(), $key);
        }
        return $result;
    }


    /**
     * @param Request $request
     * @return array
     */
    public function setFinalCode(Request $request)
    {
        try {

            $key = null;
            //validate incoming request with defined rule
            $req = RequestValidate::resetFinalValidator($request);
            $key = $req['key'];

            $courseConfig = KeyManager::getCourseConfig($key);

            $languageActions = new $courseConfig["LanguageActions"];
            $result = $languageActions->finalCode($req);

            $resultHandler = new $courseConfig["ResultHandler"];
            $result = $resultHandler->codes($result);

        }catch (\Exception $e){

            return $this->generateRunError($e->getMessage(),$key);
        }

        return $result;
    }


    /**
     * @param $message
     * @param $key
     * @return array
     */
    function generateRunError($message, $key)
    {

        if ($key == null)
            $key = 'keyless_error';

        $persianDate = new Calender();
        $now = now();
        $year= $now->year;
        $month = $now->month;
        $day = $now->day;
        $time = new DateTime(null, new DateTimeZone('Asia/tehran'));


        $time = $time->format("H:i:s");
        $date = $persianDate->gregorian_to_jalali($year, $month, $day ,"/");


        $error = "$message\n\n|time>> $time <<time| ----- |Date>> $date <<Date| \n******************************************************************\n";
        error_log($error,3,"/home/mohammadreza/violin/log/$key");
        return [
            "error" => true,
            "message" => $message
        ];
    }
}
