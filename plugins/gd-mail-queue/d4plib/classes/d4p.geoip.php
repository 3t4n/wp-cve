<?php

/*
Name:    d4pLib - Features - Geo IP
Version: v2.8.13
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) { exit; }

if (!class_exists('d4p_core_geoip')) {
    class d4p_core_geoip {
        public $status = 'invalid';

        public $error_code = null;
        public $error_message = null;

        public $ip = '';
        public $country_code = '';
        public $country_name = '';
        public $region_code = '';
        public $region_name = '';
        public $city = '';
        public $zip_code = '';
        public $time_zone = '';
        public $latitude = '';
        public $longitude = '';

        public $continent_code = '';

        public function __construct($input) {
            foreach ($input as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        public function is_error() {
            return !is_null($this->error_code);
        }

        public function location() {
            $location = '';

            if ($this->status == 'active' && isset($this->country_name) && !empty($this->country_name)) {
                $location.= $this->country_name;

                if (isset($this->city) && !empty($this->city)) {
                    $location.= ', '.$this->city;
                }
            }

            return $location;
        }

        public function flag($not_found = 'image') {
            if ($this->status == 'active') {
                if ($this->country_code != '') {
                    return '<img src="'.D4PLIB_URL.'resources/flags/blank.gif" class="flag flag-'.strtolower($this->country_code).'" title="'.$this->location().'" alt="'.$this->location().'" />';
                }
            } else if ($this->status == 'private') {
                return '<img src="'.D4PLIB_URL.'resources/flags/blank.gif" class="flag flag-localhost" title="'.__("Localhost or Private IP", "d4plib").'" alt="'.__("Localhost or Private IP", "d4plib").'" />';
            }

            if ($not_found == 'image') {
                return '<img src="'.D4PLIB_URL.'resources/flags/blank.gif" class="flag flag-invalid" title="'.__("IP can't be geolocated.", "d4plib").'" alt="'.__("IP can't be geolocated.", "d4plib").'" />';
            } else {
                return '';
            }
        }
    }
}

if (!class_exists('d4p_base_geoip')) {
    abstract class d4p_base_geoip {
        public $_url = '';
        public $_ip = '';
        public $_expire = 14;

        public $_cache_hit = false;
        public $_user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0';

        /** @var d4p_core_geoip */
        public $_data = null;

        public function __construct($ip, $expire = 14) {
            $this->_ip = $ip;
            $this->_expire = intval($expire);

            if (d4p_is_private_ip($ip)) {
                $this->_data = new d4p_core_geoip(array('status' => 'private', 'ip' => $ip));
            } else {
                $this->init();
            }
        }

        public function __get($name) {
	        return $this->_data->$name ?? '';
        }

        public function active() {
            return $this->_active;
        }

        public function error() {
            if (!$this->_active) {
                return $this->_error;
            } else {
                return false;
            }
        }

        public function location() {
            return $this->_data->location();
        }

        public function flag($not_found = 'image') {
            return $this->_data->flag($not_found);
        }

        protected function init() {
            $code = array();
            $get = false;

            $key = 'd4pfg_'.$this->_ip;

            if ($this->_expire > 0) {
                $code = get_site_transient($key);

                if ($code === false || is_null($code) || empty($code)) {
                    $get = true;
                } else {
                    $this->_cache_hit = true;
                }
            } else {
                $get = true;
            }

            if ($get) {
                $raw = wp_remote_get($this->url(), array(
                    'httpversion' => '1.1',
                    'user-agent' => $this->_user_agent
                ));

                if (!is_wp_error($raw) && $raw['response']['code'] == '200') {
                    $code = $this->process($raw['body']);

                    if ($this->_expire > 0) {
                        set_site_transient($key, $code, $this->_expire * DAY_IN_SECONDS);
                    }
                } else {
                    $code = array(
                        'error_code' => $raw['response']['code'], 
                        'error_message' => $raw['response']['message'],
                        'ip' => $this->_ip
                    );
                }
            }

            $this->_data = new d4p_core_geoip($code);
        }

        abstract protected function url();

        abstract protected function process($body);
    }
}

if (!class_exists('d4p_geoplugin_geoip')) {
    class d4p_geoplugin_geoip extends d4p_base_geoip {
        public $_url = 'http://www.geoplugin.net/json.gp?ip=';

        public static function instance($ip = '', $expire = 14) {
            static $geoplugin_ips = array();

            if ($ip == '') {
                $ip = d4p_visitor_ip();
            }

            if (!isset($geoplugin_ips[$ip])) {
                $geoplugin_ips[$ip] = new d4p_geoplugin_geoip($ip, $expire);
            }

            return $geoplugin_ips[$ip];
        }

        protected function url() {
            return $this->_url.$this->_ip;
        }

        protected function process($body) {
            $convert = array(
                'ip' => 'ip',
                'countryCode' => 'country_code',
                'countryName' => 'country_name',
                'regionCode' => 'region_code',
                'regionName' => 'region_name',
                'city' => 'city',
                'latitude' => 'latitude',
                'longitude' => 'longitude',
                'continentCode' => 'continent_code'
            );

            $raw = (array)json_decode($body);

            $code = array(
                'status' => 'active'
            );

            foreach ($raw as $key => $value) {
                $ck = substr($key, 10);

                if (isset($convert[$ck])) {
                    $real = $convert[$ck];
                    $code[$real] = $value;
                }
            }

            return $code;
        }
    }
}

if (!class_exists('d4p_geojsio_geoip')) {
    class d4p_geojsio_geoip {
        private $_ips;
        private $_expire;

        private $_data = array();
        private $_url = 'https://get.geojs.io/v1/ip/geo.json?ip=';

        private $_user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0';

        public function __construct($ips, $expire = 14, $user_agent = null) {
            $this->_ips = array_unique((array)$ips);
            $this->_expire = $expire;

            if (!is_null($user_agent)) {
                $this->_user_agent = $user_agent;
            }

            foreach ($this->_ips as $ip) {
                if (d4p_is_private_ip($ip)) {
                    $this->_data[$ip] = new d4p_core_geoip(array('status' => 'private', 'ip' => $ip));
                } else if (d4p_validate_ip($ip)) {
                    $key = $this->transient_key($ip);
                    $data = get_site_transient($key);

                    if (is_array($data) && !empty($data)) {
                        $this->_data[$ip] = new d4p_core_geoip($data);
                    }
                } else {
                    $this->_data[$ip] = new d4p_core_geoip(array('status' => 'invalid', 'ip' => $ip));
                }
            }

            $this->init();
        }

        public function get($ip) {
            return $this->_data[ $ip ] ?? 'invalid';
        }

        private function init() {
            $ips = array();

            foreach ($this->_ips as $ip) {
                if (!isset($this->_data[$ip])) {
                    $ips[] = $ip;
                }
            }

            if (!empty($ips)) {
                $raw = wp_remote_get($this->url($ips), array(
                    'httpversion' => '1.1',
                    'user-agent' => $this->_user_agent
                ));

                if (!is_wp_error($raw) && $raw['response']['code'] == '200') {
                    $raw = json_decode($raw['body']);

                    foreach ($raw as $r) {
                        $data = $this->process((array)$r);

                        if ($this->_expire > 0) {
                            set_site_transient($this->transient_key($r->ip), $data, $this->_expire * DAY_IN_SECONDS);
                        }

                        $this->_data[$r->ip] = new d4p_core_geoip($data);
                    }
                } else {
                    if (is_wp_error($raw)) {
                        $code = array(
                            'error_code' => $raw->get_error_code(),
                            'error_message' => $raw->get_error_message());
                    } else {
                        $code = array(
                            'error_code' => $raw['response']['code'] ?? 'error',
                            'error_message' => $raw['response']['message'] ?? 'Unspecified Error'
                        );
                    }

                    foreach ($ips as $ip) {
                        $code['ip'] = $ip;
                        $this->_data[$ip] = new d4p_core_geoip($code);
                    }
                }
            }
        }

        private function process($raw) {
            $convert = array(
                'ip' => 'ip',
                'continent_code' => 'continent_code',
                'country_code' => 'country_code',
                'country' => 'country_name',
                'region' => 'region_name',
                'city' => 'city',
                'latitude' => 'latitude',
                'longitude' => 'longitude',
                'timezone' => 'time_zone'
            );

            $code = array(
                'status' => 'active'
            );

            foreach ($raw as $key => $value) {
                if (isset($convert[$key])) {
                    $real = $convert[$key];
                    $code[$real] = $value;
                }
            }

            return $code;
        }

        private function transient_key($ip) {
            return 'geoip_geojs_'.$ip;
        }

        private function url($ips) {
            return $this->_url.join(',', $ips);
        }
    }
}

if (!function_exists('d4p_geoip')) {
    function d4p_geoip($ip = '', $expire = 14, $engine = 'geoplugin') {
        if ($engine == 'geoplugin') {
            return d4p_geoplugin_geoip::instance($ip, $expire);
        }

        return null;
    }
}
