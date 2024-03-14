<?php
/**
 *The main service for working with if-so groups
 *
 * @author Nick Martianov
 *
 **/

namespace IfSo\PublicFace\Services\GroupsService;

require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

use IfSo\Services\PluginSettingsService\PluginSettingsService as SettingsService;

use IfSo\PublicFace\Services\TriggersService\TriggerContextLoader as ContextLoader;

if ( ! defined( 'ABSPATH' ) ) exit;

class GroupsService{
    private static $instance;

    private $groups_data_option_name = 'ifso_groups_data';

    private $groups_cookie_name = 'ifso_group_name';

    private $user_group_limit;

    private $group_cookie_lifespan;

    protected $settings_service;

    private function __construct(){
        $this->settings_service = SettingsService::get_instance();
        $this->user_group_limit = (int) $this->settings_service->userGroupLimit->get();
        $this->group_cookie_lifespan = (int) $this->settings_service->groupsCookieLifespan->get();
    }

    public static function get_instance(){
        if(NULL == self::$instance)
            self::$instance = new GroupsService();

        return self::$instance;
    }

    public function get_groups(){
        //Cache this value or rely on WP's caching for options?
        $groupsList = [];
        $dataStr = get_option($this->groups_data_option_name);
        if($dataStr){
            $groupsList = json_decode($dataStr,true);
        }
        return $groupsList;
    }

    public function add_group($name){
        if(isset($name) && !empty($name)){
            if(preg_match('/[\,]|[\']|[\"]/',$name))
                throw new \Exception('illegal-group-name');
            $ret = $this->get_groups();
            if(array_search($name,$ret) === false){
                $ret[]= esc_html($name);
                update_option($this->groups_data_option_name,json_encode($ret));
            }
            else throw new \Exception('already-exists');
        }
        else throw new \Exception('no-name-to-add');
    }

    public function remove_group($name){
        if(isset($name) && !empty($name)){

            $current = $this->get_groups();

            if(($rem = array_search($name,$current)) !== false){
                unset($current[$rem]);
            }

            update_option($this->groups_data_option_name,json_encode($current));

        }
    }

    public function group_exists($name){
        $groups = $this->get_groups();

        if(array_search($name,$groups) === false){
            return false;
        }

        return true;
    }

    public function scanTriggersForGroupOccurence($name=''){
        $ret=[];
        $args = [
            'post_type'=>'ifso_triggers',
            'posts_per_page' => -1,
        ];
        $query = new \WP_Query($args);
        if($query->have_posts()){
            while($query->have_posts()) {
                $query->the_post();
                // Loop in here
                $context = ContextLoader::load_context(['id'=>get_the_ID()],null);
                $rules = $context->get_data_rules();

                $id = get_the_ID();
                $title = (!empty(get_the_title())) ? get_the_title() : "Unnamed Trigger (ID: {$id})";
                $toAdd = [
                    'id'=>$id,
                    'link'=>get_edit_post_link($id),
                    'title'=>$title,
                    'versions'=>[]
                ];

                if(is_array($rules)){
                    foreach($rules as $version => $rule){
                        $adding = (isset($rule['add_to_group']) && is_array($rule['add_to_group']) &&  in_array($name,$rule['add_to_group']));
                        $removing = (isset($rule['remove_from_group']) && is_array($rule['remove_from_group']) &&  in_array($name,$rule['remove_from_group']));
                        $action = [];
                        if($adding) $action[] = 'add';
                        if($removing) $action[] = 'remove';
                        if($adding || $removing){
                            $toAdd['versions'][$version] = implode(' & ',$action);
                        }
                    }

                }

                if(!empty($toAdd['versions']))  $ret[] = $toAdd;
            }
        }
        wp_reset_postdata();
        return($ret);
    }

    public function get_user_groups(){
        if(isset($_COOKIE[$this->groups_cookie_name])){
            $dataStr = stripslashes($_COOKIE[$this->groups_cookie_name]);
            return json_decode($dataStr,true);
        }
        return [];
    }

    public function add_user_to_group($grpName){
        if(!$this->group_exists($grpName)) return false;

        $groups = $this->get_user_groups();
        $duration = $this->group_cookie_lifespan * 24 * 60 * 60 ;     //The duration comes from the settings, where the it uses the ammount of days
        if(!$this->is_user_in_group($grpName))
            $groups[] = $grpName;
        $dataStr = json_encode(array_slice($groups,-$this->user_group_limit));    //Limit the user to {{value from settings, defaults to 5}} groups, "cut off" the earliest group added to
        \IfSo\PublicFace\Helpers\CookieConsent::get_instance()->set_cookie($this->groups_cookie_name,$dataStr,time()+$duration,'/');
        $_COOKIE[$this->groups_cookie_name] = $dataStr;
    }

    public function add_user_to_groups($grps){
        if(isset($grps) && !empty($grps) && is_array($grps)){
            foreach($grps as $grp){
                $this->add_user_to_group($grp);
            }
        }
    }

    public function is_user_in_group($grpName){
        $groups = $this->get_user_groups();
        if($this->group_exists($grpName) && in_array($grpName,$groups))
            return true;
        else
            return false;
    }

    public function remove_user_from_group($grpName){
        if(!$this->group_exists($grpName)) return false;

        $groups = $this->get_user_groups();
        $duration = $this->group_cookie_lifespan * 24 * 60 * 60;     //The duration comes from the settings, where the it uses the ammount of days
        if(($rem = array_search($grpName,$groups)) !== false) {
            unset($groups[$rem]);
        }
        $dataStr = json_encode(array_values($groups));
        \IfSo\PublicFace\Helpers\CookieConsent::get_instance()->set_cookie($this->groups_cookie_name,$dataStr,time()+$duration,'/');
        $_COOKIE[$this->groups_cookie_name] = $dataStr;
    }

    public function remove_user_from_groups($grps){
        if(isset($grps) && !empty($grps) && is_array($grps)){
            foreach($grps as $grp){
                $this->remove_user_from_group($grp);
            }
        }
    }

    public function handle($data_rules){
        $rule = $data_rules->get_rule();

        if(isset($rule['add_to_group']) && !empty($rule['add_to_group'])){
            $grpsToAdd = $rule['add_to_group'];
            $this->add_user_to_groups($grpsToAdd);
        }

        if(isset($rule['remove_from_group']) && !empty($rule['remove_from_group'])){
            $grpsToRemove = $rule['remove_from_group'];
            $this->remove_user_from_groups($grpsToRemove);
        }
    }



}