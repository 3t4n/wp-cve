<?php
class MaxGalleriaCommon {
	public function format_seconds_to_time($seconds) {
		$h = (int)($seconds / 3600);
		$m = (int)(($seconds - $h * 3600) / 60);
		$s = (int)($seconds - $h * 3600 - $m * 60);
		
		return (($h) ? (($h < 10) ? ("0" . $h) : $h) : "00") . ":" . (($m) ? (($m < 10) ? ("0" . $m) : $m) : "00") . ":" . (($s) ? (($s < 10) ? ("0" . $s) : $s) : "00");
	}
	
	public function get_api_url() {
		if ($this->url_contains('localhost')) {
			return 'http://localhost/maxgalleria/api/api.php';
		}
		
		return 'http://maxgalleria.com/api/api.php';
	}
	
	public function get_browser() {
		// http://www.php.net/manual/en/function.get-browser.php#101125.
		// Cleaned up a bit, but overall it's the same.

		$user_agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
		$browser_name = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		// First get the platform
		if (preg_match('/linux/i', $user_agent)) {
			$platform = 'Linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
			$platform = 'Mac';
		}
		elseif (preg_match('/windows|win32/i', $user_agent)) {
			$platform = 'Windows';
		}
		
		// Next get the name of the user agent yes seperately and for good reason
		if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
			$browser_name = 'Internet Explorer';
			$browser_name_short = "MSIE";
		}
		elseif (preg_match('/Firefox/i', $user_agent)) {
			$browser_name = 'Mozilla Firefox';
			$browser_name_short = "Firefox";
		}
		elseif (preg_match('/Chrome/i', $user_agent)) {
			$browser_name = 'Google Chrome';
			$browser_name_short = "Chrome";
		}
		elseif (preg_match('/Safari/i', $user_agent)) {
			$browser_name = 'Apple Safari';
			$browser_name_short = "Safari";
		}
		elseif (preg_match('/Opera/i', $user_agent)) {
			$browser_name = 'Opera';
			$browser_name_short = "Opera";
		}
		elseif (preg_match('/Netscape/i', $user_agent)) {
			$browser_name = 'Netscape';
			$browser_name_short = "Netscape";
		}
		
		// Finally get the correct version number
		$known = array('Version', $browser_name_short, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $user_agent, $matches)) {
			// We have no matching number just continue
		}
		
		// See how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			// We will have two since we are not using 'other' argument yet
			// See if version is before or after the name
			if (strripos($user_agent, "Version") < strripos($user_agent, $browser_name_short)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// Check if we have a number
		if ($version == null || $version == "") { $version = "?"; }
		
		return array(
			'user_agent' => $user_agent,
			'name' => $browser_name,
			'version' => $version,
			'platform' => $platform,
			'pattern' => $pattern
		);
	}

	public function get_cart_url() {
		if ($this->url_contains('localhost')) {
			return 'http://localhost/maxgalleria/shop/cart/';
		}
		
		return 'http://maxgalleria.com/shop/cart/';
	}
	
	public function get_next_menu_order($gallery_id) {
		global $wpdb;
		$menu_order = 0;

		$highest_ordered_image = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_parent = %d ORDER BY menu_order DESC LIMIT 1", $gallery_id));
		
		if (isset($highest_ordered_image)) {
			// We already have images in the gallery, so increment
			$menu_order = $highest_ordered_image->menu_order + 1;
		}
		else {
			// This is the first image in the gallery, so start at 1.
			$menu_order = 1;
		}
		
		return $menu_order;
	}
	
	public function get_url() {
		$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
		$url .= sanitize_text_field($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);	
		return $url;
	}

	public function log_me($message) {
		if (WP_DEBUG === true) {
			if (is_array($message) || is_object($message)) {
				error_log(print_r($message, true));
			} else {
				error_log($message);
			}
		}
	}
	
	public function string_contains($haystack, $needle) {
		// Notice the use of the === operator
		if (strpos($haystack, $needle) === false) {
			return false;
		}
		
		return true;
	}
	
	public function string_contains_embeddable_element($string) {
		if ($this->string_contains($string, '<iframe') ||
			$this->string_contains($string, '<object') ||
			$this->string_contains($string, '<embed')) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function string_starts_with($haystack, $needle) {
		// Notice the use of the === operator
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public function string_ends_with($haystack, $needle) {
		// Notice the use of the === operator
		$length = strlen($needle);
		return (substr($haystack, -$length) === $needle);
	}
	
	public function url_matches_patterns($url, $patterns) {
		foreach ($patterns as $regex) {
			if (preg_match($regex, $url) == 1) {
				return true;
			}
		}
		
		return false;
	}
	
	public function url_contains($string) {
		$url = $this->get_url();
		return $this->string_contains($url, $string);
	}
}
?>