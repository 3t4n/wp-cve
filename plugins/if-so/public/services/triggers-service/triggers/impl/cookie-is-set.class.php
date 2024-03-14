<?php
namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class CookieIsSet extends TriggerBase {
	public function __construct() {
		parent::__construct('Cookie');
	}
	
	public function handle($trigger_data) { 
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();

        $source = !empty($rule['cookie-or-session']) && $rule['cookie-or-session']==='session' && isset($_SESSION)  ? $_SESSION : $_COOKIE ;
        $relationship = !empty($rule['cookie-relationship']) ? $rule['cookie-relationship'] : 'is';

        $cookie_name = isset($rule['cookie-input']) ? $rule['cookie-input'] : '' ;
        $cookie_value = isset($rule['cookie-value-input']) ? $rule['cookie-value-input'] : '';
        $match = false;


		if(!empty($cookie_name) || !empty($cookie_value)){
            if(!empty($cookie_name) && isset($cookie_value) && $this->cookie_exists($cookie_name,$source) && ($relationship==='is-more' || $relationship==='is-less')){
                if(is_numeric($source[$cookie_name]) && (($relationship==='is-more' && intval($source[$cookie_name])>intval($cookie_value)) || ($relationship==='is-less' && intval($source[$cookie_name])<intval($cookie_value))))
                    return $content;
            }
            if(!empty($cookie_name) && empty($cookie_value))
                $match = $this->cookie_exists($cookie_name,$source);

            if(!empty($cookie_value) && empty($cookie_name))
                $match = $this->cookie_value_exists($cookie_value,$source);

            if(!empty($cookie_name) && !empty($cookie_value))
                $match = ($this->cookie_exists($cookie_name,$source) && $source[$cookie_name] == $cookie_value);


            if(($relationship==='is' && $match) || ($relationship==='is-not'&&!$match))
                return $content;
        }
        return false;

	}
	
	private function cookie_exists($cookie_name,$source) {
		if(isset($source[$cookie_name]))
			return true;	
		return false;
	}

	private function cookie_value_exists($cookie_val,$source){
	    if(in_array($cookie_val,$source))
            return true;
	    return false;
    }

	private function contains_or_not($arg, $f, $t, $source) {
		foreach ($source as $key=>$val) {
			if(strpos($key, $arg) !== false)
				return $f;
		}
		return $t; 
	}
}

