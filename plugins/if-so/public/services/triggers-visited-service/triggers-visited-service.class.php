<?php

namespace IfSo\PublicFace\Services\TriggersVisitedService;

require_once (IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'public/services/analytics-service/analytics-service.class.php');

use IfSo\PublicFace\Helpers\CookieConsent;
use IfSo\Services\PluginSettingsService\PluginSettingsService;
use IfSo\PublicFace\Services\AnalyticsService;

class TriggersVisitedService {

    private static $instance;

    protected $cookie_name;
    protected $analytics_cookie_name;
    protected $visited_triggers;
    protected $max_saved_triggers;
    protected $use_cookie;



    private function __construct() {
        $settings_service = PluginSettingsService::get_instance();
        $this->max_saved_triggers = (int) $settings_service->triggersVisitedNumber->get();
        $this->use_cookie = $settings_service->triggersVisitedOn->get();

        $this->cookie_name = 'ifso_viewed_triggers';
        $this->analytics_cookie_name = AnalyticsService\AnalyticsService::get_instance()->last_viewed_version_cookie_name;

        $cookie_content = (!empty($_COOKIE[$this->cookie_name])) ? $_COOKIE[$this->cookie_name] : '';
        $visited_triggers = (json_decode($cookie_content)) ? json_decode($cookie_content) : [];
        $last_visited_triggers = (!empty($_COOKIE[$this->analytics_cookie_name]) && json_decode(stripslashes($_COOKIE[$this->analytics_cookie_name]))) ? array_keys(json_decode(stripslashes($_COOKIE[$this->analytics_cookie_name]),true)) : [];

        $this->visited_triggers = array_merge($visited_triggers,array_filter($last_visited_triggers,function($tid) use ($visited_triggers) { return (!in_array($tid,$visited_triggers));   }));
        $this->visited_triggers = array_slice($this->visited_triggers,-$this->max_saved_triggers);

        if($cookie_content !== json_encode($this->visited_triggers) && $this->use_cookie)
            CookieConsent::get_instance()->set_cookie($this->cookie_name,json_encode($this->visited_triggers),time()+60*60*24*365,'/');
    }


    public static function get_instance() {
        if ( NULL == self::$instance )
            self::$instance = new TriggersVisitedService();

        return self::$instance;
    }

    public function add_trigger($id){
        if(!in_array($id,$this->visited_triggers)){
            $this->visited_triggers[] = $id;
        }
    }

    public function get_visited($json=true){
        if($json)
            $this->visited_triggers;

        return $this->visited_triggers;
    }

    public function set_visited_triggers(){
        if($this->use_cookie)
            CookieConsent::get_instance()->set_cookie($this->cookie_name,json_encode($this->visited_triggers),0,'/');
    }


}