<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 11:16 AM
 */



return[

    //file that container code
    "max_files_count" => 10,
    "max_file_size" => 1000,


    /*
    |-----------------------------------------------
    |*********python language configuration*********
    ------------------------------------------------
    |image : docker that python use
    |type: windows base / web base
    |defaultFileForExecute: main file that execute first (ex: main.py , script.py)
    |exec: command used to execute python file(script.py) in command line (ex: python script.py)
    |LanguageActions: a class contain language , run() ,pageload(), resetCode(), finalCode() and create() functions
    |ResultHandler: a class implement ResultHandler base class to handle each language result separately
    |lang:language name
    |files_on_host: default files path on host
    |keys id prefix in Redis
    |if language need file watcher it must be true
    |-----------------------------------------------
     */
    "python"=> [

        "image" => "test:1",
        "type"=>"local",
        "defaultFileForExecute"=>"script.py",
        "exec"=>"python",
        "LanguageActions" => \App\Clasess\Languages\Interpreted\Python\Python::class,
        "ResultHandler" => \App\Clasess\Languages\Interpreted\Python\ResultHandler\ResultHandler::class,
        "lang" => "python",
        "files_on_host" =>getenv("HOME").DIRECTORY_SEPARATOR."python/default_files/python",
        "ContainerFiles" =>"/home/violin/python",
        "keysCacheName" => "python_elementary",
        "container_default_files" => "/home/mohammadreza/Desktop/Python_Runner/Runner/python",
        "container_shared_files" => "/home/mohammadreza/PythonContainerts/",
        "file_watcher"=> true,
    ],

    /*
   |-----------------------------------------------
   |java language configuration
   |-----------------------------------------------
    */
    "java" => [

        "lang"=>"java",
        "key" => getenv("HOME").DIRECTORY_SEPARATOR."java/key/java_keys.json",
        "java_files_on_host"=>getenv("HOME").DIRECTORY_SEPARATOR."java/default_files/java",
        "path" => getenv("HOME").DIRECTORY_SEPARATOR."java/path/java_path.json",
        "ContainerFiles" =>"/home/violin/java",
        "pathCacheName" => "java_path",
        "keysCacheName" => "java_keys",

    ],

    /*
   |-----------------------------------------------
   |javascript language configuration
   |-----------------------------------------------
    */
    "javascript" => [

        "image" => "test:2",
        "type"=>"web",
        "defaultFileForExecute"=>"index.html",
        "exec"=> "",
        "LanguageActions" => App\Clasess\Languages\WebBased\Javascript\Javascript::class,
        "ResultHandler" => \App\Clasess\Languages\WebBased\Javascript\ResultHandler\ResultHandler::class,
        "lang"=>"javascript",
        "files_on_host"=>getenv("HOME").DIRECTORY_SEPARATOR."javascript/default_files/javascript",
        "path" => getenv("HOME").DIRECTORY_SEPARATOR."javascript/path/javascript_path.json",
        "ContainerFiles" =>"/var/www/html/js",
        "runner_path"=> "/home/violin",
        "keysCacheName" => "javascript_keys",
        "container_files" => "/home/mohammadreza/Desktop/Python_Runner/Runner/python",
        "file_watcher"=>true,

    ],




];