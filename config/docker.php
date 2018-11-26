<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 4:02 PM
 */

/*
 * This file is used for configuring docker connection, container, ... configs
 */

return [

    /*
    | Connection Configurations
    */

    'connection' => [
        'remote_socket' => env('DOCKER_REMOTE_SOCKET', 'tcp://192.168.85.34:2375'),
        'ssl' => env('DOCKER_SSL', false)
    ],

    'container_stop_time' => [

        'minutes' => '59',
        'houres' => '1'

    ],

    /*
     | ContainerHelper Config
     |
     | This list is based on instance-php/generated/Model/ContainerConfig.php file
     | We have used the function names instead of variable names to make it easy to retrieve and call them
     |         , so the values in the right side are arguments for the functions in the left side.
     */

    'container_config' => [
        //'setHostname' => 'Violin',
        // 'setDomainname' => null,
        // 'setUser' => null,
        'setAttachStdin' => false,
        'setAttachStdout' => false,
        'setAttachStderr' => false,
        'setTty' => false,
        'setOpenStdin' => true,
        'setStdinOnce' => false,
        // 'setOpenStdin' => true,
        // 'setEnv' => null,
        // 'setCmd' => null,
        // 'setEntrypoint' => null,
        // 'setImage' => null,
        // 'setLabels' => null,
        // 'setVolumes' => null,
        // 'setWorkingDir' => null,
        'setNetworkDisabled' => false,
        // 'setMacAddress' => null,
        // 'setExposedPorts' => null,
        // 'setStopSignal' => null,
        // 'setHostConfig' => null,
        // 'setNetworkingConfig' => null,
    ],

    "violin_http_config"=>[
        //'setHostname' => 'Violin',
        // 'setDomainname' => null,
        // 'setUser' => null,
        'setAttachStdin' => false,
        'setAttachStdout' => false,
        'setAttachStderr' => false,
        'setTty' => false,
        'setOpenStdin' => true,
        'setStdinOnce' => true,
        // 'setEnv' => null,
        // 'setCmd' => null,
        // 'setEntrypoint' => null,
        // 'setImage' => null,
        // 'setLabels' => null,
        // 'setVolumes' => null,
        // 'setWorkingDir' => null,
        'setNetworkDisabled' => false,
        // 'setMacAddress' => null,
        // 'setExposedPorts' => null,
        // 'setStopSignal' => null,
        // 'setHostConfig' => null,
        // 'setNetworkingConfig' => null,

    ]
];