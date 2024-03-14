<?php

namespace IfSo\Services\GeolocationService;

class GeoData{
    public $countryCode;
    public $countryName;
    public $city;
    public $stateProv;
    public $continentCode;
    public $continentName;
    public $timeZone;
    public $ipAddress;
    public $coords;

    public function __construct($countryCode,$countryName,$city,$stateProv,$continentCode,$continentName,$timezone,$ip,$coords){
        $this->countryCode = $countryCode;
        $this->countryName = $countryName;
        $this->city = $city;
        $this->stateProv = $stateProv;
        $this->continentCode = $continentCode;
        $this->continentName = $continentName;
        $this->timeZone = $timezone;
        $this->ipAddress = $ip;
        $this->coords = $coords;
    }

    public static function make_from_data_array($data){
        $countryCode = isset($data['countryCode']) ? $data['countryCode'] : null;
        $countryName = isset($data['countryName']) ? $data['countryName'] : null;
        $city = isset($data['city']) ? $data['city'] : null;
        $stateProv = isset($data['stateProv']) ? $data['stateProv'] : null;
        $continent = isset($data['continentCode']) ? $data['continentCode'] : null;
        $continentName = isset($data['continentName']) ? $data['continentName'] : null;
        $timezone = isset($data['timeZone']) ? $data['timeZone'] : null;
        $ip = isset($data['ipAddress']) ? $data['ipAddress'] : null;
        $coords = isset($data['coords']) ? $data['coords'] : null;

        return new static($countryCode,$countryName,$city,$stateProv,$continent,$continentName,$timezone,$ip,$coords);
    }

    public function get($field){
        if(!empty($this->$field))
            return $this->$field;
        return null;
    }

    public function set($field,$val){
        $this->$field = $val;
    }
}

class GeoDataOverride extends GeoData{
    protected $real_user_geoData;

    public function get($field){
        if(property_exists($this,$field) && $this->$field===null && !empty($this->get_real_geo_data()->$field))
            return $this->get_real_geo_data()->$field;
        return parent::get($field);
    }

    private function get_real_geo_data(){
        if($this->real_user_geoData===null)
            $this->real_user_geoData = GeolocationService::get_instance()->get_user_location(false);

        return $this->real_user_geoData;
    }
}