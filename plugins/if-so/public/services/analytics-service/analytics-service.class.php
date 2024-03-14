<?php
/**
 *The main service for working with analytics data(reading,writing,etc)
 *
 * @author Nick Martianov
 *
 **/

namespace IfSo\PublicFace\Services\AnalyticsService;

use IfSo\PublicFace\Helpers\CookieConsent;

require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');


class AnalyticsService {
    private static $instance;

    public static $analytics_fields = ['views','conversion'];

    private $trigger_rules_field_name = 'ifso_trigger_rules';

    public $last_viewed_version_cookie_name = 'ifso_last_viewed';

    public $currently_viewing_cookie_name = 'ifso_viewing_triggers';

    private static $viewed_triggers =[];

    private static $already_had_conversion = [];

    public $isOn = true;

    public $useAjax = true;

    public $allow_counting = true;   //Allow counting of current user's views/conversions

    protected $temp_field;

    protected $settings_service;

    private function __construct(){
        $this->settings_service = \IfSo\Services\PluginSettingsService\PluginSettingsService::get_instance();
        $this->isOn = !$this->settings_service->disableAnalytics->get();
        if(defined('REST_REQUEST') && REST_REQUEST ) $this->isOn =  false;   //Disable analytics if its a request to the wp REST API (to avoid gutenberg from activating analytics)
        $this->useAjax = $this->settings_service->ajaxAnalytics->get();

        $th = $this;
        add_action('plugins_loaded',function() use (&$th){
            if (current_user_can('administrator')) $th->allow_counting = false;   //Disalow counting for administrators
        });

    }

    public static function get_instance(){
        if (NULL == self::$instance)
            self::$instance = new AnalyticsService();

        return self::$instance;
    }


    private function get_rules_data($postid){
        if($this->isOn && isset($postid)){
            $dataStr = get_post_meta($postid,$this->trigger_rules_field_name,true);
            if($dataStr){
                $data = json_decode($dataStr,true);
                if($data) return $data;
            }
        }
        return false;
    }

    private function set_rules_data($postid,$data){
        if($this->isOn && isset($postid) && isset($data)){
            $dataStr = json_encode($data,JSON_UNESCAPED_UNICODE);
            if($dataStr){
                $prepared_dataStr = str_replace("\\", "\\\\\\",$dataStr);
                update_post_meta($postid, $this->trigger_rules_field_name,$prepared_dataStr);
            }
        }
    }

    /*functions for handling  the default case start*/
    private function get_default_an_data($postid){
        $this->temp_field = $this->trigger_rules_field_name;
        $this->trigger_rules_field_name = 'ifso_default_analytics';
        $ret =  $this->get_rules_data($postid);
        $this->trigger_rules_field_name = $this->temp_field;
        if(!empty($ret)) return $ret;
        return [];
    }

    private function set_default_an_data($postid,$data){
        $this->temp_field = $this->trigger_rules_field_name;
        $this->trigger_rules_field_name = 'ifso_default_analytics';
        $this->set_rules_data($postid,$data);
        $this->trigger_rules_field_name = $this->temp_field;
    }

    public function get_default_analytics_field($postid,$field){
        if(isset($postid) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_default_an_data($postid);
            if($data && isset($data[$field])){
                return $data[$field];
            }
        }
        return false;
    }

    public function get_default_analytics_fields($postid){
        if(isset($postid)){
            $res = [];
            $data = $this->get_default_an_data($postid);
            //if($data){
                foreach(self::$analytics_fields as $field){
                    if(isset($data[$field]))
                        $res[$field] = $data[$field];
                    else
                        $res[$field] = 0;
                }
            //}
            return $res;
        }
        return false;
    }

    public function update_default_analytics_field($postid,$field,$val=1){
        if(isset($postid) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_default_an_data($postid);
            if(!$data) $data = [];
            $data[$field] = $val;
            $this->set_default_an_data($postid,$data);
        }
        return false;
    }

    public function increment_default_analytics_field($postid,$field){
        if(isset($postid) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_default_an_data($postid);
            if($data && isset($data[$field]) && is_int($data[$field])) $data[$field] =  ++$data[$field];
            else $data[$field] = 1;
            $this->set_default_an_data($postid,$data);
        }
    }

    public function decrement_default_analytics_field($postid,$field){
        if(isset($postid) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_default_an_data($postid);
            if($data && isset($data[$field]) && is_int($data[$field]) && $data[$field]>0) $data[$field] =  --$data[$field];
            else $data[$field] = 0;
            $this->set_default_an_data($postid,$data);
        }
    }

    /*functions for handling  the default case END*/

    public function create_analytics_meta_fields($postid,$current_rules){
        $rules = $current_rules;
        foreach($rules as &$ver){
            foreach(self::$analytics_fields as $field){
                $ver[$field] = 0;
            }
        }
        $this->set_rules_data($postid,$rules);
    }

    public function get_analytics_field($postid,$versionid,$field){
        if(isset($postid) && isset($versionid) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_rules_data($postid);
            if($data){
                if(isset($data[$versionid][$field])) return $data[$versionid][$field];
            }
        }
        return false;
    }

    public function get_analytics_fields($postid,$versionid=false,$inject_version_name=false){
        //Get an associative array of analytics fields and their values of a version. If versionid is not defined, get an array of such arrays for each version of a trigger
        if(isset($postid)){
            $ret = [];

            if($versionid!==false){
                foreach(self::$analytics_fields as $field){
                    $ret[$field] = $this->get_analytics_field($postid,$versionid,$field);
                }
                if($inject_version_name) $ret['version_name'] = $this->generate_version_symbol($versionid);
                return $ret;
            }
            else{
                $versions = $this->get_rules_data($postid);
                if(is_array($versions)){
                    foreach($versions as $key=>$value){
                        $ret[$key] = $this->get_analytics_fields($postid,$key,$inject_version_name);
                    }
                }
                $def = $this->get_default_analytics_fields($postid); //Add the "DEFAULT" version
                $def['version_name']='Default';
                $ret[] = $def;
                return $ret;
            }

        }
    }

    public function update_analytics_field($postid,$versionid,$field,$val){
        if(isset($postid) && isset($versionid) && isset($val) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_rules_data($postid);
            if( $data && isset($data[$versionid]) ){
                $data[$versionid][$field] = (int) $val;
                $this->set_rules_data($postid,$data);
            }
        }
        return false;
    }

    public function increment_analytics_field($postid,$versionid,$field){
        if(isset($postid) && isset($versionid) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_rules_data($postid);
            if( $data && isset($data[$versionid]) ){
                $current = $data[$versionid][$field];
                if(is_numeric($current)) $data[$versionid][$field] =  ++$current;
                else $data[$versionid][$field] = 1;
                $this->set_rules_data($postid,$data);
            }
        }
        return false;
    }

    public function decrement_analytics_field($postid,$versionid,$field){
        if(isset($postid) && isset($versionid) && isset($field) && in_array($field,self::$analytics_fields)){
            $data = $this->get_rules_data($postid);
            if( $data && isset($data[$versionid]) ){
                $current = $data[$versionid][$field];
                if(is_numeric($current) && $current>0) $data[$versionid][$field] =  --$current;
                else $data[$versionid][$field] = 0;
                $this->set_rules_data($postid,$data);
            }
        }
        return false;
    }

    public function reset_analytics_field($postid,$versionid,$field){
        if(isset($postid) && isset($versionid) && isset($field)){
            $this->update_analytics_field($postid,$versionid,$field,0);
        }
    }

    public function reset_analytics_fields($postid,$version=false){
        if(isset($postid)){
            $data = $this->get_rules_data($postid);
            if($data){
                if($version!==false){
                    if($version!='default'){
                        if(isset($data[$version])){
                            foreach(self::$analytics_fields as $field){
                                $this->reset_analytics_field($postid,$version,$field);
                            }
                        }
                    }
                    else{
                        foreach(self::$analytics_fields as $field){
                            $this->update_default_analytics_field($postid,$field,0); //reset the default version fields
                        }
                    }

                }
                else{
                    foreach($data as $key=>$value){
                        $this->reset_analytics_fields($postid,(string) $key);
                    }
                    $this->reset_analytics_fields($postid,'default');
                }

            }
        }
    }

    public function reset_all_triggers_analytics_fields(){
        $args = [
            'post_type'=>'ifso_triggers',
            'posts_per_page' => -1,
        ];
        $query = new \WP_Query($args);
        if($query->have_posts()){
            while($query->have_posts()) {
                $query->the_post();
                // Loop in here
                $this->reset_analytics_fields(get_the_id());
            }
            wp_reset_postdata();
        }
    }

    private function generate_version_symbol($version_number) {
        $version_number = intval($version_number+64+1);
        $num_of_characters_in_abc = 26;
        $base_ascii = 64;
        $version_number = intval($version_number) - $base_ascii;

        $postfix = '';
        if ($version_number > $num_of_characters_in_abc) {
            $postfix = intval($version_number / $num_of_characters_in_abc) + 1;
            $version_number %= $num_of_characters_in_abc;
            if ($version_number == 0) {
                $version_number = $num_of_characters_in_abc;
                $postfix -= 1;
            }
        }

        $version_number += $base_ascii;
        return chr($version_number) . strval($postfix);
    }

    private function set_last_viewed_version_cookie($postid,$versionid){
        //Set cookie indicating the triggers/versions seen during the current session to use in bounce/conversion callbacks etc - can be moved to a separate class later on
        if(isset($postid) && isset($versionid)){
            $viewed_arr = [];
            if(isset($_COOKIE[$this->last_viewed_version_cookie_name]) && is_array(json_decode(stripslashes($_COOKIE[$this->last_viewed_version_cookie_name]),true)))
                $viewed_arr = json_decode(stripslashes($_COOKIE[$this->last_viewed_version_cookie_name]),true);
            $viewed_arr[$postid] = $versionid;
            $_COOKIE[$this->last_viewed_version_cookie_name] = json_encode($viewed_arr);
            CookieConsent::get_instance()->set_cookie($this->last_viewed_version_cookie_name,json_encode($viewed_arr),0,'/');
        }
    }

    public function do_conversion($triggers,$allowed,$disallowed,$once_per_time=null,$name=null){
        if($once_per_time!==null && $name!==null){
            $convs = [];
            $limited_conversions_cookie_name = 'ifso-limited-conversions';
            if(!empty($_COOKIE[$limited_conversions_cookie_name])){
                $convs = is_array(json_decode(stripslashes($_COOKIE[$limited_conversions_cookie_name]),true)) ? json_decode(stripslashes($_COOKIE[$limited_conversions_cookie_name]),true) : [];
                if(!(!isset($convs[$name]) || (intval($convs[$name])<time() && intval($convs[$name])!==0))){
                    return;
                }
            }
            $convs[$name] = intval($once_per_time)!==0 ? time()+intval($once_per_time) : 0;
            asort($convs);
            CookieConsent::get_instance()->set_cookie($limited_conversions_cookie_name,json_encode($convs),intval(end($convs)),'/','preferences');
        }
        foreach ($triggers as $trigger=>$version){
            if(!isset(self::$already_had_conversion[$trigger]) && !in_array($trigger,$disallowed) && (!$allowed || is_array($allowed) && in_array($trigger,$allowed))){
                if($version!=='default')
                    $this->increment_analytics_field($trigger,$version,'conversion');
                else
                    $this->increment_default_analytics_field($trigger,'conversion');
                self::$already_had_conversion[$trigger] = $version;
            }
        }
    }

    public function handle($rule_data) {
        if($this->isOn){
            if(!isset(self::$viewed_triggers[$rule_data->get_trigger_id()]) && $this->allow_counting){
                $tid = $rule_data->get_trigger_id();
                self::$viewed_triggers[$tid] = $rule_data->get_version_index();
                $this->set_last_viewed_version_cookie($tid,$rule_data->get_version_index());
                if(!$this->useAjax){
                    $this->increment_analytics_field($tid,$rule_data->get_version_index(),'views');
                }
                else{
                    CookieConsent::get_instance()->set_cookie($this->currently_viewing_cookie_name,json_encode(self::$viewed_triggers),0,'/');
                }
            }
        }
    }

    public function handle_default($rule_data) {
        if($this->isOn){
            if(!isset(self::$viewed_triggers[$rule_data->get_trigger_id()]) && $this->allow_counting){
                $tid = $rule_data->get_trigger_id();
                self::$viewed_triggers[$tid] = 'default';
                $this->set_last_viewed_version_cookie($tid, 'default');
                if(!$this->useAjax){
                    $this->increment_default_analytics_field($tid, 'views');
                }
                else{
                    CookieConsent::get_instance()->set_cookie($this->currently_viewing_cookie_name,json_encode(self::$viewed_triggers),0,'/');
                }
            }
        }
    }

    public function render_google_analytics_event_element($attrs,$event='ifso-trigger-viewed'){
        $event_data_attr = esc_attr(json_encode($attrs));
        return "<ifsoTriggerAnalyticsEvent event_data='{$event_data_attr}' event_name='{$event}'></ifsoTriggerAnalyticsEvent>";
    }



}