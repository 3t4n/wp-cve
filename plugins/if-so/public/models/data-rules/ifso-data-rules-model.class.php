<?php
/**
 * This class holds a model of the conditions (rules) availible in the plugin as well as some helper methods to work with them
 *
 * @since      1.4.4
 * @author     Nick Martianov
 */

namespace IfSo\PublicFace\Models\DataRulesModel;

class DataRulesModel {
    /**
     * An array of conditions availible to triggers in the plugin and their fields in (whatever database)
     * formatted as [CONDITION NAME=>[ARRAY OF RELEVANT FIELDS]]
     *
     * @since    1.4.4
     * @access   private
     * @var      array    $conditions    An array of conditions available to triggers in the plugin
     */
    protected $conditions = [
        'general' => ['trigger_type','testing-mode','freeze-mode','recurrence_option','recurrence_custom_units','recurrence_custom_value','recurrence-override','views','conversion','add_to_group','remove_from_group','version_name'],
        'AB-Testing' => ['AB-Testing', 'ab-testing-sessions','ab-testing-custom-no-sessions','number_of_views'],
        'advertising-platforms' => ['advertising_platforms_option', 'advertising_platforms'],
        'Cookie' => ['cookie-or-session','cookie-relationship','cookie-input','cookie-value-input'],
        'Device' => ['user-behavior-device-mobile','user-behavior-device-tablet','user-behavior-device-desktop'],
        'url' => ['compare'],
        'UserIp' => ['ip-values','ip-input'],
        'Geolocation' => ['geolocation_behaviour', 'geolocation_data'],
        'PageUrl' => ['page-url-operator','page-url-compare','page-url-ignore-case'],
        'PageVisit' => ['page_visit_data'],
        'referrer' => ['trigger','page','chosen-common-referrers','custom','operator','compare','page-category-operator','page-category'],
        'Time-Date' => ['Time-Date-Schedule-Selection','Date-Time-Schedule','Time-Date-Start','Time-Date-End','time-date-end-date','time-date-start-date'],
        'User-Behavior' => ['User-Behavior','user-behavior-browser-language-primary-lang','user-behavior-browser-language','user-behavior-logged','user-behavior-returning','user-behavior-retn-custom'],
        'Utm' => ['utm-type','utm-relation','utm-value'],
        'Groups' => ['user-group-relation','group-name'],
        'userRoles' => ['user-role-relationship','user-role'],
        'User-Details' => ['user-details-type','user-details-relationship','user-reg-before-relationship','user-reg-before'],
        'TriggersVisited' => ['triggers-visited-relationship', 'triggers-visited-id'],
        'PostCategory' => ['post-category-operator','post-category-compare']
    ];

    public function __construct(){
        $this->conditions = apply_filters('ifso_data_rules_model_filter',$this->conditions);    //For custom triggers extension
    }

    public function get_condition_fields($cond){
        if(array_key_exists($cond,$this->conditions)){
            return $this->conditions[$cond];
        }
        return [];
    }

    /**
     * Remove the unused fields for a version depending on the trigger type and return the resulting array
     *
     * @param array $source
     *
     * @return array
     */
    public function trim_version_data_rules($source){   //remove useless fields from the data rules of the version
       // if(isset($source['trigger_type']) && !empty($source['trigger_type'])){
            $type =  $source['trigger_type'];
            $allowed = (!empty($type)) ? $this->get_condition_fields($type) : [];
            $general_allowed = $this->get_condition_fields('general');
            $ret = $source;
            //if($allowed){
                foreach($ret as $conditionName => $conditionTitle){
                    if(!in_array($conditionName,$allowed) && !in_array($conditionName,$general_allowed)){
                        unset($ret[$conditionName]);
                    }
                }
           // }
            return $ret;
      //  }
       // return false;
    }

    public function get_trigger_types(){
        $ret = array_keys($this->conditions);
        $ret = array_diff($ret,['general']);
        return $ret;
    }

    public function get_data_rules(){
        return $this->conditions;
    }

    public static function get_free_conditions(){
        $free_conditions  = array("Device", "User-Behavior", "Geolocation", "UserIp", "Time-Date");
        return $free_conditions;
    }
}