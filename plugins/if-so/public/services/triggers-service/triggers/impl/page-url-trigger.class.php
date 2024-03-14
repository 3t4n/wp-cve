<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class PageUrlTrigger extends TriggerBase {
	public function __construct() {
		parent::__construct('PageUrl');
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();

		$operator = $rule['page-url-operator'];
		$page_url = $rule['page-url-compare'];
        $request = $trigger_data->get_HTTP_request();

        $current_url = $request->getRequestURL();
        $ignore_case = (!empty($rule['page-url-ignore-case']) && $rule['page-url-ignore-case']);

		if ( $operator == 'is' || $operator == 'is-not' ) {
			// remove trailing slashes and http from comparition when exact match is requested
			$page_url = trim($page_url, '/');
			$page_url = str_replace('https://', '', $page_url);
			$page_url = str_replace('http://', '', $page_url);
			$page_url = str_replace('www.', '', $page_url);

			$current_url = trim($current_url, '/');
            $current_url = str_replace('https://', '', $current_url);
            $current_url = str_replace('http://', '', $current_url);
            $current_url = str_replace('www.', '', $current_url);
		}

		if($ignore_case){
		    $page_url = strtolower($page_url);
		    $current_url = strtolower($current_url);
        }
		
		if ( $operator == 'contains' && (strpos($current_url, $page_url) !== false) )
			return $content;
		else if ( $operator == 'is' && $current_url == $page_url )
			return $content;
		else if ( $operator == 'is-not' && $current_url != $page_url )
			return $content;
		else if ( $operator == 'not-containes' && (strpos($current_url, $page_url) === false) )
			return $content;

		return false;
	}
}