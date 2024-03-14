<?php

class n360_SplashScreen {

	private $config;
	private $total_time;
	private $version_cookie;
	private $cookie_name;
	private $cookie_expiration;
	public $run;

	function __construct() {
		$this->config = get_option( 'n360_config' );
		$this->version_cookie = get_option( 'n360_version_cookie' );
		$this->cookie_name = $this->version_cookie['cookie_name'];
		$this->cookie_expiration = time() + (60*60*24*30); // cookie expires in 30 days
		$this->run = false;
		$this->n360_check_run();
	}

	function n360_splash_page() {
		if ( isset( $this->config['enable_n360_ss'] ) && $this->config['enable_n360_ss'] == 1 ) {
			$domain = ($_SERVER['SERVER_NAME'] != 'localhost') ? $_SERVER['SERVER_NAME'] : false;
			setCookie( $this->cookie_name, "n360_splash_screen", $this->cookie_expiration, "/", $domain, $_SERVER['HTTPS'] );
			require_once ( N360_SPLASH_PAGE_ROOT_PATH . 'templates/default-template.php' );
            exit;
		}
	}

	public function n360_get_cookie_name() {
		return $this->cookie_name;
	}

	public function n360_check_run() {
		if ( ! isset($_COOKIE[$this->cookie_name]) ) { // first time here, never ran
			$this->run = true;
        } elseif ( ! isset($_SESSION['splash']) ) { // we have a cookie but it's a new session
            $this->run = isset(get_option('n360_config')['run_always']) ? true : false;
        } elseif ( isset(get_option('n360_config')['run_always']) ) { // we have a cookie and a session
            if ( $_SESSION['splash'] == false ) {
                $this->run = true;
            } else {
                $_SESSION['splash'] = false;
            }    
        }
	}

	public function n360_option( $name ) {
		$config = $this->config;
		return ( isset($config[$name] ) && $config[$name] != '' ? $config[$name] : '' );
	}

	public function n360_set_keyframes () {
		$config = $this->config;
		$delay = $config['timing']['delay'];
		$fadein = $config['timing']['fadein'];
		$sustain = $config['timing']['sustain'];
		$fadeout = $config['timing']['fadeout'];
		$resume = $config['timing']['resume'];
		$this->total_time = $delay + $fadein + $sustain + $fadeout + $resume;
		$delay_percent = $delay / $this->total_time * 100;
		$fadein_percent = $fadein / $this->total_time * 100 + $delay_percent;
		$sustain_percent = $sustain / $this->total_time * 100 + $fadein_percent;
		$fadeout_percent = $fadeout / $this->total_time * 100 + $sustain_percent;
		$resume_percent = $resume / $this->total_time * 100 + $fadeout_percent;
		echo '0% { opacity:0; } '
		. number_format($delay_percent, 1) . '% { opacity:0; } '
		. number_format($fadein_percent, 1) . '% { opacity:1; } '
		. number_format($sustain_percent, 1) . '% { opacity:1; } '
		. number_format($fadeout_percent, 1) . '% { opacity:0; } '
		. number_format($resume_percent, 1) . '% { opacity:0; }' . PHP_EOL;
	}
}