<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class ReferrerTrigger extends TriggerBase {
	public function __construct() {
		parent::__construct('referrer');
	}
	
	public function handle($trigger_data) { //$trigger_data == All the values from the associative array originated in class-if-so-setting.php - the SETTED one is triggerred - at this "contains" case it sets one value of the array to: "contains"["compare"]=> string(5) "if-so". muliCohen.
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();

        $request = $trigger_data->get_HTTP_request();
		
		$referrer = $this->get_referrer($request);
		
		if ($rule['trigger'] == 'common-referrers') {
			$chose_common_referrers = $rule['chosen-common-referrers'];
			
			if($chose_common_referrers == 'facebook') {
				if(strpos($referrer, 'facebook.com') !== false)
				return $content;
			}
			else if($chose_common_referrers == 'google') {
				if(strpos($referrer, 'google.') !== false)
				return $content;
			}
			
			// TODO - check twitter referrer not working ($_SERVER['HTTP_REFERER'] is empty)
			else if($chose_common_referrers == 'twitter') {
				if(strpos($referrer, 'twitter.') !== false)
				return $content;
			}
			else if($chose_common_referrers == 'youtube') {
				if(strpos($referrer, 'youtube.') !== false)
				return $content;
			}
		} else if($rule['trigger'] == 'page-on-website' && $rule['page']) {
			
			$page_id = (int)$rule['page'];
			$page_link = get_permalink($page_id);
			$page_link = trim($page_link, '/');
			$page_link = str_replace('https://', '', $page_link);
			$page_link = str_replace('http://', '', $page_link);
			$page_link = str_replace('www.', '', $page_link);
			
			if($referrer == $page_link)
			return $content;
		}
		else if($rule['trigger']==='page-category' && !empty($rule['page-category'])&& !empty($rule['page-category-operator']) ){
		    $operator = $rule['page-category-operator'];
		    $referrer_postid = url_to_postid($request->getReferrer());
		    if($referrer_postid!==0){
		        $in_category = in_category($rule['page-category'],$referrer_postid);
                if(($in_category && $operator === 'is') || (!$in_category && $operator === 'is-not')){
                    return $content;
                }
            }
        }
		else {
			// custom referrer
			// handle url custom referrer - currently the only one
			if($rule['custom'] == 'url' || $rule['trigger'] === 'custom-url') {
				
				if($rule['operator'] == 'is' || $rule['operator'] == 'is-not' || $rule['operator'] == 'contains' || $rule['operator'] == 'not-containes') {// added contains & not-contains. muliCohen
					// remove trailing slashes and http from comparition when exact match is requested
					$rule['compare'] = trim($rule['compare'], '/');
					$rule['compare'] = str_replace('https://', '', $rule['compare']);
					$rule['compare'] = str_replace('http://', '', $rule['compare']);
					$rule['compare'] = str_replace('www.', '', $rule['compare']);
					
					$referrer = trim($referrer, '/');
				}
				
				if($rule['operator'] == 'contains' && (strpos($referrer, $rule['compare']) !== false))
				return $content;
				else if($rule['operator'] == 'is' && $referrer == $rule['compare']) {
					return $content;
				}
				else if($rule['operator'] == 'is-not' && $referrer != $rule['compare'])
				return $content;
				else if($rule['operator'] == 'not-containes' && (strpos($referrer, $rule['compare']) === false))
				return $content;
			}
		}
		
		return false; // Will return the default content. muliCohen.
	}
	
	private function get_referrer($request) {
	    $referrer = $request->getReferrer();
		$referrer = trim($referrer, '/');
		$referrer = str_replace('https://', '', $referrer);
		$referrer = str_replace('http://', '', $referrer);
		$referrer = str_replace('www.', '', $referrer);
		
		return $referrer;
	}
}

