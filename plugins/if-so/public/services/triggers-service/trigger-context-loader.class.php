<?php

namespace IfSo\PublicFace\Services\TriggersService;

use IfSo\PublicFace\Helpers\IfSoHttpGetRequest\IfSoHttpGetRequest;
use IfSo\PublicFace\Services\AjaxTriggersService\AjaxTriggersService;

require_once('trigger-context.class.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'public/helpers/ifso-request/If-So-Http-Get-Request.php');

class TriggerContextLoader {
	public static function load_context($atts, $http_request) {
		$trigger_id = self::load_trigger_id($atts);
		$data_rules = self::load_data_rules($trigger_id);
		$data_versions = self::load_data_versions($trigger_id);
		$default_content = self::load_default_content($trigger_id);
        $http_request = self::load_http_request($http_request);

        $extra_opts = self::load_context_opts_from_shortcode_atts($atts);

		return new TriggerContext($trigger_id, $data_rules, $data_versions, $default_content, $http_request, $extra_opts);
	}

	public static function load_context_from_data($trigger_id, $data_rules, $data_versions, $default_content,$http_request){
        $http_request = self::load_http_request($http_request);

        return new TriggerContext($trigger_id, $data_rules, $data_versions, $default_content, $http_request);
    }
	
	private static function load_trigger_id($atts) {
		return $atts['id'];
	}
	
	private static function load_data_rules($trigger_id) {
		$data_rules_json = get_post_meta( $trigger_id, 'ifso_trigger_rules', true );
		$data_rules = json_decode($data_rules_json, true);
		return $data_rules;
	}
	
	private static function load_data_versions($trigger_id) {
		$data_versions = get_post_meta( $trigger_id, 'ifso_trigger_version', false );
		return $data_versions;
	}
	
	public static function load_default_content($trigger_id) {
		$default_content = get_post_meta( $trigger_id, 'ifso_trigger_default', true );
		return $default_content;
	}

    private static function load_http_request($http_request){
        if(empty($http_request)){
            if(AjaxTriggersService::get_instance()->is_inside_ajax_triggers_request())
                $http_request = AjaxTriggersService::get_instance()->get_request();
            else
                $http_request = \IfSo\PublicFace\Helpers\IfSoHttpGetRequest\IfSoHttpGetRequest::create();
        }
        return $http_request;
    }

    public static function load_context_opts_from_shortcode_atts($atts){
        $context_opts_keys = AjaxTriggersService::get_instance()->get_atts_for_ajax();
        $ret = [];
        if(!empty($atts) && is_array($atts)){
            foreach($atts as $key=>$val){
                if(in_array($key,$context_opts_keys))
                    $ret[$key] = ($val==='yes' || $val==='true') || !($val==='false' || $val==='no');
            }
        }
        return $ret;
    }
}