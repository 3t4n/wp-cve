<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_EventListener
{
    public $sensors = array();

    public function __construct(){
        $sensorSearchPath = realpath(__DIR__.'/..').'/Sensors/*.php';
        $sensorClassPrefix = 'WADA_Sensor_';

        $coreSensorConfig = array($sensorClassPrefix, $sensorSearchPath);
        $extensionSensorConfigs = apply_filters( 'wp_admin_audit_collect_extension_sensor_configs', array() );
        $allSensorsConfig = array();
        $allSensorsConfig[] = $coreSensorConfig;
        if(count($extensionSensorConfigs)){
            $allSensorsConfig = array_merge($allSensorsConfig, $extensionSensorConfigs);
        }

        WADA_Log::debug('EventListener allSensorsConfig: '.print_r($allSensorsConfig, true));
        foreach($allSensorsConfig as $sensorsConfig){
            if(!is_array($sensorsConfig) || count($sensorsConfig) < 2){
                continue;
            }
            list($sensorClassPrefix, $sensorSearchPath) = $sensorsConfig;
            WADA_Log::debug('EventListener check for '.$sensorClassPrefix.' classes in '.$sensorSearchPath);
            $nrSensorsAddedInSearchPath = 0;
            $sensorFiles = glob($sensorSearchPath);
            foreach ($sensorFiles as $sensorFile) {
                $pathParts = pathinfo($sensorFile);
                if($pathParts && array_key_exists('filename', $pathParts) && $pathParts['filename'] !== 'Base'){
                    $sensorClassName = $sensorClassPrefix.$pathParts['filename'];
                    if(class_exists($sensorClassName)){
                        // WADA_Log::info('EventListener adding '.$sensorClassName);
                        $sensor = new $sensorClassName();
                        $this->sensors[] = $sensor;
                        $nrSensorsAddedInSearchPath++;
                    }
                }
            }
            WADA_Log::debug('EventListener added '.$nrSensorsAddedInSearchPath.' sensor classes from '.$sensorSearchPath);
        }

        // WADA_Log::debug('EventListener all sensors: '.print_r($this->sensors, true));
    }

    public function startListening(){
        /** @var WADA_Sensor_Base $sensor */
        foreach($this->sensors AS $sensor){
            $sensor->registerSensor();
        }
    }
}