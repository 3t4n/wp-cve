<?php
	
class Captionpix_API extends Captionpix_Module {
    const OPTION_NAME = 'licence';  

	private $default_action='updates';	
	private $transient_expiry = 86400;
	private $initialized=false;
	private $key='-';
	private $upgrader;
	private $version;
	private $package;
	private $expiry;
	private $last_updated;
	private $sections;
	private $homepage;
	private $url;
	private $author;
	private $requires;
	private $tested;	
	private $notice;
	private $updates;
	private $valid;

	function get_options_name() { return false; }    
	function get_defaults() { return false; }


	function init() {
    	if (!$this->initialized) $this->update(); 
	}	
	
	function update($cache = true, $action = '') {
		if (empty($action)) $action = $this->default_action;
		return ($cache && $this->initialized && ($action==$this->default_action)) ? $this->updates : $this->fetch_updates($action,$cache); 
	}

	function set_key($new_key, $md5) {
		$this->key = empty($new_key) ? '' : ($md5 ? md5($new_key) : $new_key);
    }

	function get_key($cache=true) {
    	if (!$cache || ('-'== $this->key)) $this->key = get_option($this->add_plugin_prefix(self::OPTION_NAME));
    	return $this->key;
	}
	
	function has_key($cache=true) {
		$key = $this->get_key($cache);
    	return !empty($key) && (strlen($key) == 32); //has a key worth checking
	}	

	function empty_key($cache=true) {
		$key = $this->get_key($cache);
    	return empty($key);
	}
	
	function save_key($new_key, $md5 = true, $save_if_unchanged = false) {
		$updated = false;
  		$old_key = $this->get_key(false); //fetch old key from database
		if ($save_if_unchanged || ($new_key != $old_key)) {
		    $this->initialized = false;
			$this->set_key($new_key,$md5);
   			$updated = update_option($this->add_plugin_prefix(self::OPTION_NAME),$this->key) ;
   			if ($save_if_unchanged || $updated) $this->update(false); //get updates for new key
   		}
   		return $updated;
	}

	function check_validity(){
    	if ($this->get_key()) {
    		$this->init(); 
    		return $this->valid;
    		}
    	else 
    		return false;
	}

  	function get_version(){
    	$this->init(); 
    	return $this->version;
    }
   
 	function get_package(){
    	$this->init(); 
    	return $this->package;
    }   

 	function get_url(){
    	$this->init(); 
    	return $this->url;
    }  
    
	function get_notice(){
    	$this->init(); 
    	return $this->notice;
    }

 	function get_requires(){
    	$this->init(); 
    	return $this->requires;
    }
    
 	function get_tested(){
    	$this->init(); 
    	return $this->tested;
    }

 	function get_expiry(){
    	$this->init(); 
    	return $this->expiry;
    }

 	function get_last_updated(){
    	$this->init(); 
    	return $this->last_updated;
    }

 	function get_sections(){
    	$this->init(); 
    	return $this->sections;
   	}
   
 	function get_homepage(){
    	$this->init(); 
    	return $this->homepage;
   	}   

 	function get_author(){
    	$this->init(); 
    	return $this->author;
   	}

 	function get_updates(){
    	$this->init(); 
    	return $this->updates;
   	}

	function get_transient($transient) {
    	if (false !== ($value = get_transient($transient))) return $value;
    	/**transients may not be working so use homespun alternative ***/
		$last_update = get_option($transient.'_date');
    	if ($last_update && (abs(time() - $last_update) < $this->transient_expiry) )
    		return get_option($transient);
    	else
    		return false;
    }
	
	function set_transient($transient, $value) {
    	if (set_transient($transient, $value, $this->transient_expiry) 
    	&& ( false !== ($value == get_transient($transient)))) return true;
    	/**transients not working so use alternative ***/   
    	update_option( $transient, $value); 	
    	update_option( $transient.'_date', time()); 
    	return true;
	}

	function is_old_version() {
    	$currarray = explode('.', $this->plugin->get_version());
		$currint = $currarray[0] * 10000 + $currarray[1] * 100 + (count($currarray)>=3 ? $currarray[2] : 0);
    	$verarray = explode('.', $this->version);
		if (count($verarray) < 2) return false;
		$verint = $verarray[0] * 10000 + $verarray[1] * 100 + (count($verarray)>=3 ? $verarray[2] : 0);
		return $currint < $verint;
    }
   	
    private function add_plugin_prefix($action) {
		return str_replace('-','_', $this->plugin->get_slug()).'_'.strtolower($action);
    }  	
	
	private function fetch_updates($action,$cache) {
	    $result = $this->has_key($cache)  ? 
	    	$this->parse_updates($this->fetch_remote_or_cache($action,$cache)) :
	    	$this->set_defaults($this->empty_key($cache) ? '' : 'Invalid License Key' );
		if ($action==$this->default_action) $this->updates = $result;
		return $result;
	}
	
    private function parse_updates($response) {
         	$this->checked = true; 
 			if (is_array($response) && (count($response) >= 6)) {
    	        $this->valid = $response['valid_key']; 
    	        $this->version = $response['version']; 
    	        $this->package = $response['package'];  
    	        $this->url = isset($response['url']) ? $response['url'] : '';  
    	        $this->notice = $response['notice']; 
    	        $this->requires = isset($response['requires']) ? $response['requires'] : ''; 
    	        $this->tested = isset($response['tested']) ? $response['tested'] : ''; 
    	        $this->expiry = $response['expiry']; 
    	        $this->last_updated = isset($response['last_updated']) ? $response['last_updated'] : ''; 
    	        $this->sections = isset($response['sections']) ? (array)$response['sections'] : array(); 
       	        $this->homepage = isset($response['homepage']) ? $response['homepage'] : ''; 
       	        $this->author = isset($response['author']) ? $response['author'] : ''; 
    			return $response['updates'];
			} else {
				return $this->set_defaults('Unable to check for updates. Please try again.'); 
			}
    }

    private function set_defaults($notice = '') {
 		$this->valid = false; 
    	$this->version = $this->plugin->get_version(); 
    	$this->updates = $this->requires = $this->expiry = $this->package = $this->url = $this->author = $this->last_updated = $this->homepage = '';  
    	$this->notice = empty($notice) ? '' : ('<div class="message">'.__($notice).'</div>'); 
    	$this->sections = array('Description' => '', 'Changelog' => ''); 
    	return $this->updates;
    }

    private function fetch_remote_or_cache($action,$cache=true){
		$transient = $this->add_plugin_prefix($action);
    	$values = $cache ? $this->get_transient($transient) : false;
    	if ((false === $values)  || is_array($values) || empty($values)) {
     	    $raw_response = $this->remote_call($action, $cache);
    	    $values = (is_array($raw_response) && array_key_exists('body',$raw_response)) ? $raw_response['body'] : false;
    	    $this->set_transient($transient, $values); //cache for 24 hours
		}
		return false === $values ? false : @unserialize(@gzinflate(@base64_decode($values)));
	}

	private function remote_call($action, $cache=true, $backup = false){
        $options = array('method' => 'POST', 'timeout' => 20);
        $options['headers'] = array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
            'User-Agent' => 'WordPress/' . get_bloginfo("version"),
            'Referer' => get_bloginfo("url")
        );
        $raw_response = wp_remote_request($this->get_upgrader($cache, $backup). '&act='.$action  , $options);
        if ( is_wp_error( $raw_response ) || (200 != $raw_response['response']['code']) || empty($raw_response)){
			return $backup ? false : $this->remote_call($action, $cache, true);
        } else {
            return $raw_response;
        }
	}

	private function get_upgrader($cache = true, $backup=false){
        global $wpdb;
        if (empty($this->upgrader) || ($cache == false) || $backup)
            $this->upgrader = sprintf("%s?of=%s&key=%s&v=%s&wp=%s&php=%s&mysql=%s",
                $this->plugin->get_updater($backup), 
                urlencode($this->plugin->get_slug()), 
                urlencode($this->get_key()), 
                urlencode($this->plugin->get_version()), 
                urlencode(get_bloginfo("version")),
                urlencode(phpversion()), 
                urlencode($wpdb->db_version()));
        return $this->upgrader;
	}
   
}
