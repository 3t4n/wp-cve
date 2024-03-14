<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

new Math_Captcha_Core();

class Math_Captcha_Core {

	public $session_number = 0;
	public $login_failed = false;
	public $error_messages;
	public $errors;

	/**
	 * 
	 */
	public function __construct() {
		// set instance
		Math_Captcha()->core = $this;

		// actions
		add_action( 'init', array( $this, 'load_actions_filters' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'load_defaults' ) );
		add_action( 'admin_init', array( $this, 'flush_rewrites' ) );

		// filters
		add_filter( 'shake_error_codes', array( $this, 'add_shake_error_codes' ), 1 );
		add_filter( 'mod_rewrite_rules', array( $this, 'block_direct_comments' ) );
	}


	public function counter_add_alert() 
    {
        $wp_content_dir = WP_CONTENT_DIR.'/uploads';
        
        $folder = $wp_content_dir.'/logs';
        if (!file_exists($folder))
        {
            mkdir($folder);
            $fp = fopen($folder.'/.htaccess', 'w');
            fwrite($fp, 'deny from all');
            fclose($fp);
        }
        
        $folder = $wp_content_dir.'/logs/mathcaptcha';
        if (!file_exists($folder))
        {
            mkdir($folder);
            $fp = fopen($folder.'/.htaccess', 'w');
            fwrite($fp, 'deny from all');
            fclose($fp);
        }
        
        $file = $folder.'/'.date("Y-m-d").'.log';
        $fp = fopen($file, 'a');
        fwrite($fp, '0');
        fclose($fp);
	}
    
	/**
	 * Load defaults.
	 */
	public function load_defaults() {
		$this->error_messages = array(
			'fill'	 => '<strong>' . __( 'ERROR', 'math-captcha' ) . '</strong>: ' . __( 'Please enter captcha value.', 'math-captcha' ),
			'wrong'	 => '<strong>' . __( 'ERROR', 'math-captcha' ) . '</strong>: ' . __( 'Invalid captcha value.', 'math-captcha' ),
			'time'	 => '<strong>' . __( 'ERROR', 'math-captcha' ) . '</strong>: ' . __( 'Captcha time expired.', 'math-captcha' )
		);
	}

	/**
	 * Load required filters.
	 */
	public function load_actions_filters() {
		// Contact Form 7
		if ( Math_Captcha()->options['general']['enable_for']['contact_form_7'] && class_exists( 'WPCF7_ContactForm' ) )
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			include_once(MATH_CAPTCHA_PATH . 'includes/integrations/contact-form-7.php');
        }

		if ( is_admin() )
			return;

		$action = isset( $_GET['action'] ) && $_GET['action'] !== '' ? $_GET['action'] : null;

		// comments
		if ( Math_Captcha()->options['general']['enable_for']['comment_form'] ) {
			if ( ! is_user_logged_in() )
            {
                // Check IP rules
                if (Math_Captcha()->options['general']['ip_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
                }
                // Check GEO rules
                if (Math_Captcha()->options['general']['geo_captcha_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
                }
                
				add_action( 'comment_form_after_fields', array( $this, 'add_captcha_form' ) );
            }
			elseif ( ! Math_Captcha()->options['general']['hide_for_logged_users'] )
            {
                // Check IP rules
                if (Math_Captcha()->options['general']['ip_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
                }
                // Check GEO rules
                if (Math_Captcha()->options['general']['geo_captcha_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
                }
                
				add_action( 'comment_form_logged_in_after', array( $this, 'add_captcha_form' ) );
            }

			add_filter( 'preprocess_comment', array( $this, 'add_comment_with_captcha' ) );
		}

		// registration
		if ( Math_Captcha()->options['general']['enable_for']['registration_form'] && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) && $action === 'register' ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'register_form', array( $this, 'add_captcha_form' ) );
			add_action( 'register_post', array( $this, 'add_user_with_captcha' ), 10, 3 );
			add_action( 'signup_extra_fields', array( $this, 'add_captcha_form' ) );
			add_filter( 'wpmu_validate_user_signup', array( $this, 'validate_user_with_captcha' ) );
		}

		// lost password
		if ( Math_Captcha()->options['general']['enable_for']['reset_password_form'] && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) && $action === 'lostpassword' ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'lostpassword_form', array( $this, 'add_captcha_form' ) );
			add_action( 'lostpassword_post', array( $this, 'check_lost_password_with_captcha' ) );
		}

		// login
		if ( Math_Captcha()->options['general']['enable_for']['login_form'] && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) && $action === null ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'login_form', array( $this, 'add_captcha_form' ) );
			add_filter( 'login_redirect', array( $this, 'redirect_login_with_captcha' ), 10, 3 );
			add_filter( 'authenticate', array( $this, 'authenticate_user' ), 1000, 3 );
		}

		// bbPress
		if ( Math_Captcha()->options['general']['enable_for']['bbpress'] && class_exists( 'bbPress' ) && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'bbp_theme_after_reply_form_content', array( $this, 'add_bbp_captcha_form' ) );
			add_action( 'bbp_theme_after_topic_form_content', array( $this, 'add_bbp_captcha_form' ) );
			add_action( 'bbp_new_reply_pre_extras', array( $this, 'check_bbpress_captcha' ) );
			add_action( 'bbp_new_topic_pre_extras', array( $this, 'check_bbpress_captcha' ) );
		}
	}

	/**
	 * Add lost password errors.
	 * 
	 * @param array $errors
	 * @return array
	 */
	public function add_lostpassword_captcha_message( $errors ) {
		return $errors . $this->errors->errors['math-captcha-error'][0];
	}

	/**
	 * Add lost password errors (special way)
	 * 
	 * @return array
	 */
	public function add_lostpassword_wp_message() {
		return $this->errors;
	}

	/**
	 * Validate lost password form.
	 */
	public function check_lost_password_with_captcha() {
		$this->errors = new WP_Error();
		$user_error = false;
		$user_data = null;

		// checks captcha
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
                { $this->counter_add_alert(); $this->errors->add( 'math-captcha-error', $this->error_messages['wrong'] ); }
			} else
				{ $this->counter_add_alert(); $this->errors->add( 'math-captcha-error', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); $this->errors->add( 'math-captcha-error', $this->error_messages['fill'] ); }

		// checks user_login (from wp-login.php)
		if ( empty( $_POST['user_login'] ) )
			$user_error = true;
		elseif ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );

			if ( empty( $user_data ) )
				$user_error = true;
		} else
			$user_data = get_user_by( 'login', trim( $_POST['user_login'] ) );

		if ( ! $user_data )
			$user_error = true;

		// something went wrong?
		if ( ! empty( $this->errors->errors ) ) {
			// nasty hack (captcha is invalid but user_login is fine)
			if ( $user_error === false )
				add_filter( 'allow_password_reset', array( $this, 'add_lostpassword_wp_message' ) );
			else
				add_filter( 'login_errors', array( $this, 'add_lostpassword_captcha_message' ) );
		}
	}

	/**
	 * Validate registration form.
	 * 
	 * @param string $login
	 * @param string $email
	 * @param array $errors
	 * @return array
	 */
	public function add_user_with_captcha( $login, $email, $errors ) {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
					{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages['wrong'] ); }
			} else
		 	{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages['fill'] ); }

		return $errors;
	}

	/**
	 * Validate registration form.
	 * 
	 * @param array $result
	 * @return array
	 */
	public function validate_user_with_captcha( $result ) {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
					{ $this->counter_add_alert(); $result['errors']->add( 'math-captcha-error', $this->error_messages['wrong'] ); }
			} else
				{ $this->counter_add_alert(); $result['errors']->add( 'math-captcha-error', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); $result['errors']->add( 'math-captcha-error', $this->error_messages['fill'] ); }

		return $result;
	}

	/**
	 * Posts login form
	 * 
	 * @param string $redirect
	 * @param bool $bool
	 * @param array $errors
	 * @return array
	 */
	public function redirect_login_with_captcha( $redirect, $bool, $errors ) {
		if ( $this->login_failed === false && ! empty( $_POST ) ) {
			$error = '';

			if ( ! empty( $_POST['mc-value'] ) ) {
				$mc_value = (int) $_POST['mc-value'];

				if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
					if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
						$error = 'wrong';
				} else
					$error = 'time';
			} else
				$error = 'fill';

			if ( is_wp_error( $errors ) && ! empty( $error ) )
				{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages[$error] ); }
		}

		return $redirect;
	}

	/**
	 * Authenticate user.
	 * 
	 * @param WP_Error $user
	 * @param string $username
	 * @param string $password
	 * @return object WP_Error
	 */
	public function authenticate_user( $user, $username, $password ) {
		// user gave us valid login and password
		if ( ! is_wp_error( $user ) ) {
			if ( ! empty( $_POST ) ) {
				if ( ! empty( $_POST['mc-value'] ) ) {
					$mc_value = (int) $_POST['mc-value'];

					if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
						if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
							$error = 'wrong';
					} else
						$error = 'time';
				} else
					$error = 'fill';
			}

			if ( ! empty( $error ) ) {
				// destroy cookie
				wp_clear_auth_cookie();

				$user = new WP_Error();
                $this->counter_add_alert(); 
				$user->add( 'math-captcha-error', $this->error_messages[$error] );

				// inform redirect function that we failed to login
				$this->login_failed = true;
			}
		}

		return $user;
	}

	/**
	 * Add shake.
	 * 
	 * @param array $codes
	 * @return array
	 */
	public function add_shake_error_codes( $codes ) {
		$codes[] = 'math-captcha-error';

		return $codes;
	}

	/**
	 * Add captcha to comment form.
	 * 
	 * @param array $comment
	 * @return array
	 */
	public function add_comment_with_captcha( $comment ) {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) && ( $comment['comment_type'] === '' || $comment['comment_type'] === 'comment' || $comment['comment_type'] === 'review' ) ) {
				if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
					if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) === 0 )
						return $comment;
					else
						{ $this->counter_add_alert(); wp_die( $this->error_messages['wrong'] ); }
				} else
					{ $this->counter_add_alert(); wp_die( $this->error_messages['time'] ); }
			} else
				{ $this->counter_add_alert(); wp_die( $this->error_messages['fill'] ); }
		} else
			{ $this->counter_add_alert(); wp_die( $this->error_messages['fill'] ); }
	}

	/**
	 * Display and generate captcha.
	 * 
	 * @return mixed
	 */
	public function add_captcha_form() {
		if ( is_admin() )
			return;

		$captcha_title = apply_filters( 'math_captcha_title', Math_Captcha()->options['general']['title'] );

		echo '
		<p class="math-captcha-form">';

		if ( ! empty( $captcha_title ) )
			echo '
			<label>' . $captcha_title . '<br/></label>';

		echo '
			<span>' . $this->generate_captcha_phrase( 'default' ) . '</span>
		</p>';
	}

	/**
	 * Display and generate captcha for bbPress forms.
	 * 
	 * @return mixed
	 */
	public function add_bbp_captcha_form() {
		if ( is_admin() )
			return;

		$captcha_title = apply_filters( 'math_captcha_title', Math_Captcha()->options['general']['title'] );

		echo '
		<p class="math-captcha-form">';

		if ( ! empty( $captcha_title ) )
			echo '
			<label>' . $captcha_title . '<br/></label>';

		echo '
			<span>' . $this->generate_captcha_phrase( 'bbpress' ) . '</span>
		</p>';
	}

	/**
	 * Validate bbpress topics and replies.
	 */
	public function check_bbpress_captcha() {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'bbp_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'bbp_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
					{ $this->counter_add_alert(); bbp_add_error( 'math-captcha-wrong', $this->error_messages['wrong'] ); }
			} else
				{ $this->counter_add_alert(); bbp_add_error( 'math-captcha-wrong', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); bbp_add_error( 'math-captcha-wrong', $this->error_messages['fill'] ); }
	}

	/**
	 * Encode chars.
	 * 
	 * @param string $string
	 * @return string
	 */
	private function encode_operation( $string ) {
		$chars = str_split( $string );
		$seed = mt_rand( 0, (int) abs( crc32( $string ) / strlen( $string ) ) );

		foreach ( $chars as $key => $char ) {
			$ord = ord( $char );

			// ignore non-ascii chars
			if ( $ord < 128 ) {
				// pseudo "random function"
				$r = ($seed * (1 + $key)) % 100;

				if ( $r > 60 && $char !== '@' ) {
					
				} // plain character (not encoded), if not @-sign
				elseif ( $r < 45 )
					$chars[$key] = '&#x' . dechex( $ord ) . ';'; // hexadecimal
				else
					$chars[$key] = '&#' . $ord . ';'; // decimal (ascii)
			}
		}

		return implode( '', $chars );
	}

	/**
	 * Convert numbers to words.
	 * 
	 * @param int $number
	 * @return string
	 */
	private function numberToWords( $number ) {
		$words = array(
			1	 => __( 'one', 'math-captcha' ),
			2	 => __( 'two', 'math-captcha' ),
			3	 => __( 'three', 'math-captcha' ),
			4	 => __( 'four', 'math-captcha' ),
			5	 => __( 'five', 'math-captcha' ),
			6	 => __( 'six', 'math-captcha' ),
			7	 => __( 'seven', 'math-captcha' ),
			8	 => __( 'eight', 'math-captcha' ),
			9	 => __( 'nine', 'math-captcha' ),
			10	 => __( 'ten', 'math-captcha' ),
			11	 => __( 'eleven', 'math-captcha' ),
			12	 => __( 'twelve', 'math-captcha' ),
			13	 => __( 'thirteen', 'math-captcha' ),
			14	 => __( 'fourteen', 'math-captcha' ),
			15	 => __( 'fifteen', 'math-captcha' ),
			16	 => __( 'sixteen', 'math-captcha' ),
			17	 => __( 'seventeen', 'math-captcha' ),
			18	 => __( 'eighteen', 'math-captcha' ),
			19	 => __( 'nineteen', 'math-captcha' ),
			20	 => __( 'twenty', 'math-captcha' ),
			30	 => __( 'thirty', 'math-captcha' ),
			40	 => __( 'forty', 'math-captcha' ),
			50	 => __( 'fifty', 'math-captcha' ),
			60	 => __( 'sixty', 'math-captcha' ),
			70	 => __( 'seventy', 'math-captcha' ),
			80	 => __( 'eighty', 'math-captcha' ),
			90	 => __( 'ninety', 'math-captcha' )
		);

		if ( isset( $words[$number] ) )
			return $words[$number];
		else {
			$reverse = false;

			switch ( get_bloginfo( 'language' ) ) {
				case 'de-DE':
					$spacer = 'und';
					$reverse = true;
					break;

				case 'nl-NL':
					$spacer = 'en';
					$reverse = true;
					break;

				case 'ru-RU':
				case 'pl-PL':
				case 'en-EN':
				default:
					$spacer = ' ';
			}

			$first = (int) (substr( $number, 0, 1 ) * 10);
			$second = (int) substr( $number, -1 );

			return ($reverse === false ? $words[$first] . $spacer . $words[$second] : $words[$second] . $spacer . $words[$first]);
		}
	}

	/**
	 * Generate captcha phrase.
	 * 
	 * @param string $form
	 * @return array
	 */
	public function generate_captcha_phrase( $form = '' ) {
		$ops = array(
			'addition'		 => '+',
			'subtraction'	 => '&#8722;',
			'multiplication' => '&#215;',
			'division'		 => '&#247;',
		);

		$operations = $groups = array();
		$input = '<input type="text" size="2" length="2" id="mc-input" class="mc-input" name="mc-value" value="" aria-required="true"/>';

		// available operations
		foreach ( Math_Captcha()->options['general']['mathematical_operations'] as $operation => $enable ) {
			if ( $enable === true )
				$operations[] = $operation;
		}

		// available groups
		foreach ( Math_Captcha()->options['general']['groups'] as $group => $enable ) {
			if ( $enable === true )
				$groups[] = $group;
		}

		// number of groups
		$ao = count( $groups );

		// operation
		$rnd_op = $operations[mt_rand( 0, count( $operations ) - 1 )];
		$number[3] = $ops[$rnd_op];

		// place where to put empty input
		$rnd_input = mt_rand( 0, 2 );

		// which random operation
		switch ( $rnd_op ) {
			case 'addition':
				if ( $rnd_input === 0 ) {
					$number[0] = mt_rand( 1, 10 );
					$number[1] = mt_rand( 1, 89 );
				} elseif ( $rnd_input === 1 ) {
					$number[0] = mt_rand( 1, 89 );
					$number[1] = mt_rand( 1, 10 );
				} elseif ( $rnd_input === 2 ) {
					$number[0] = mt_rand( 1, 9 );
					$number[1] = mt_rand( 1, 10 - $number[0] );
				}

				$number[2] = $number[0] + $number[1];
				break;

			case 'subtraction':
				if ( $rnd_input === 0 ) {
					$number[0] = mt_rand( 2, 10 );
					$number[1] = mt_rand( 1, $number[0] - 1 );
				} elseif ( $rnd_input === 1 ) {
					$number[0] = mt_rand( 11, 99 );
					$number[1] = mt_rand( 1, 10 );
				} elseif ( $rnd_input === 2 ) {
					$number[0] = mt_rand( 11, 99 );
					$number[1] = mt_rand( $number[0] - 10, $number[0] - 1 );
				}

				$number[2] = $number[0] - $number[1];
				break;

			case 'multiplication':
				if ( $rnd_input === 0 ) {
					$number[0] = mt_rand( 1, 10 );
					$number[1] = mt_rand( 1, 9 );
				} elseif ( $rnd_input === 1 ) {
					$number[0] = mt_rand( 1, 9 );
					$number[1] = mt_rand( 1, 10 );
				} elseif ( $rnd_input === 2 ) {
					$number[0] = mt_rand( 1, 10 );
					$number[1] = ($number[0] > 5 ? 1 : ($number[0] === 4 && $number[0] === 5 ? mt_rand( 1, 2 ) : ($number[0] === 3 ? mt_rand( 1, 3 ) : ($number[0] === 2 ? mt_rand( 1, 5 ) : mt_rand( 1, 10 )))));
				}

				$number[2] = $number[0] * $number[1];
				break;

			case 'division':
				$divide = array( 1 => 99, 2 => 49, 3 => 33, 4 => 24, 5 => 19, 6 => 16, 7 => 14, 8 => 12, 9 => 11, 10 => 9 );

				if ( $rnd_input === 0 ) {
					$divide = array( 2 => array( 1, 2 ), 3 => array( 1, 3 ), 4 => array( 1, 2, 4 ), 5 => array( 1, 5 ), 6 => array( 1, 2, 3, 6 ), 7 => array( 1, 7 ), 8 => array( 1, 2, 4, 8 ), 9 => array( 1, 3, 9 ), 10 => array( 1, 2, 5, 10 ) );
					$number[0] = mt_rand( 2, 10 );
					$number[1] = $divide[$number[0]][mt_rand( 0, count( $divide[$number[0]] ) - 1 )];
				} elseif ( $rnd_input === 1 ) {
					$number[1] = mt_rand( 1, 10 );
					$number[0] = $number[1] * mt_rand( 1, $divide[$number[1]] );
				} elseif ( $rnd_input === 2 ) {
					$number[2] = mt_rand( 1, 10 );
					$number[0] = $number[2] * mt_rand( 1, $divide[$number[2]] );
					$number[1] = (int) ($number[0] / $number[2]);
				}

				if ( ! isset( $number[2] ) )
					$number[2] = (int) ($number[0] / $number[1]);

				break;
		}

		// words
		if ( $ao === 1 && $groups[0] === 'words' ) {
			if ( $rnd_input === 0 ) {
				$number[1] = $this->numberToWords( $number[1] );
				$number[2] = $this->numberToWords( $number[2] );
			} elseif ( $rnd_input === 1 ) {
				$number[0] = $this->numberToWords( $number[0] );
				$number[2] = $this->numberToWords( $number[2] );
			} elseif ( $rnd_input === 2 ) {
				$number[0] = $this->numberToWords( $number[0] );
				$number[1] = $this->numberToWords( $number[1] );
			}
		}
		// numbers and words
		elseif ( $ao === 2 ) {
			if ( $rnd_input === 0 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$number[1] = $this->numberToWords( $number[1] );
					$number[2] = $this->numberToWords( $number[2] );
				} else
					$number[$tmp = mt_rand( 1, 2 )] = $this->numberToWords( $number[$tmp] );
			}
			elseif ( $rnd_input === 1 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$number[0] = $this->numberToWords( $number[0] );
					$number[2] = $this->numberToWords( $number[2] );
				} else
					$number[$tmp = array_rand( array( 0 => 0, 2 => 2 ), 1 )] = $this->numberToWords( $number[$tmp] );
			}
			elseif ( $rnd_input === 2 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$number[0] = $this->numberToWords( $number[0] );
					$number[1] = $this->numberToWords( $number[1] );
				} else
					$number[$tmp = mt_rand( 0, 1 )] = $this->numberToWords( $number[$tmp] );
			}
		}

		if ( in_array( $form, array( 'default', 'bbpress' ), true ) ) {
			// position of empty input
			if ( $rnd_input === 0 )
				$return = $input . ' ' . $number[3] . ' ' . $this->encode_operation( $number[1] ) . ' = ' . $this->encode_operation( $number[2] );
			elseif ( $rnd_input === 1 )
				$return = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ' . $input . ' = ' . $this->encode_operation( $number[2] );
			elseif ( $rnd_input === 2 )
				$return = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ' . $this->encode_operation( $number[1] ) . ' = ' . $input;

			$transient_name = ($form === 'bbpress' ? 'bbp' : 'mc');
			$session_id = Math_Captcha()->cookie_session->session_ids['default'];
		} elseif ( $form === 'cf7' ) {
			$return = array();

			if ( $rnd_input === 0 ) {
				$return['input'] = 1;
				$return[2] = ' ' . $number[3] . ' ' . $this->encode_operation( $number[1] ) . ' = ';
				$return[3] = $this->encode_operation( $number[2] );
			} elseif ( $rnd_input === 1 ) {
				$return[1] = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ';
				$return['input'] = 2;
				$return[3] = ' = ' . $this->encode_operation( $number[2] );
			} elseif ( $rnd_input === 2 ) {
				$return[1] = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ';
				$return[2] = $this->encode_operation( $number[1] ) . ' = ';
				$return['input'] = 3;
			}

			$transient_name = 'cf7';

			if ( array_key_exists( $this->session_number, Math_Captcha()->cookie_session->session_ids['multi'] ) )
				$session_id = Math_Captcha()->cookie_session->session_ids['multi'][$this->session_number];
			else
				$session_id = '';

			$this->session_number++;
		}

		set_transient( $transient_name . '_' . $session_id, sha1( AUTH_KEY . $number[$rnd_input] . $session_id, false ), apply_filters( 'math_captcha_time', Math_Captcha()->options['general']['time'] ) );

		return $return;
	}

	/**
	 * Flush rewrite rules.
	 */
	public function flush_rewrites() {
		if ( Math_Captcha()->options['general']['flush_rules'] ) {
			global $wp_rewrite;

			$wp_rewrite->flush_rules();

			Math_Captcha()->options['general']['flush_rules'] = false;
			update_option( 'math_captcha_options', Math_Captcha()->options['general'] );
		}
	}

	/**
	 * Block direct comments.
	 * 
	 * @param string $rules
	 * @return string
	 */
	public function block_direct_comments( $rules ) {
		if ( Math_Captcha()->options['general']['block_direct_comments'] ) {
			$new_rules = <<<EOT
\n# BEGIN Math Captcha
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_METHOD} POST
RewriteCond %{REQUEST_URI} .wp-comments-post.php*
RewriteCond %{HTTP_REFERER} !.*{$this->get_host()}.* [OR]
RewriteCond %{HTTP_USER_AGENT} ^$
RewriteRule (.*) ^http://%{REMOTE_ADDR}/$ [R=301,L]
</IfModule>
# END Math Captcha\n\n
EOT;

			return $new_rules . $rules;
		}

		return $rules;
	}

	/**
	 * Get host.
	 *
	 * @return string
	 */
	private function get_host() {
		$host = '';

		foreach ( array( 'HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR' ) as $source ) {
			if ( ! empty( $host ) )
				break;

			if ( empty( $_SERVER[$source] ) )
				continue;

			$host = $_SERVER[$source];

			if ( $source === 'HTTP_X_FORWARDED_HOST' ) {
				$elements = explode( ',', $host );
				$host = trim( end( $elements ) );
			}
		}

		// remove port number from host and return it
		return trim( preg_replace( '/:\d+$/', '', $host ) );
	}
}