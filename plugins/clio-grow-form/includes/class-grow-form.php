<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Grow_Form {

	/**
	 * The single instance of Grow_Form.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'Grow_Form';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

        // Register shortcodes
        add_shortcode('grow-contact-form', array($this, 'setup_contact_form'));

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'lf_css_register_style' ), 99 );
		add_action( 'plugins_loaded', array( $this, 'lf_css_maybe_print_css') );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Grow_Form_Admin_API();
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct ()

	/**
	 * Wrapper function to register a new post type
	 * @param  string $post_type   Post type name
	 * @param  string $plural      Post type item plural name
	 * @param  string $single      Post type item single name
	 * @param  string $description Description of post type
	 * @return object              Post type class object
	 */
	public function register_post_type ( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) return;

		$post_type = new Grow_Form_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy
	 * @param  string $taxonomy   Taxonomy name
	 * @param  string $plural     Taxonomy single name
	 * @param  string $single     Taxonomy plural name
	 * @param  array  $post_types Post types to which this taxonomy applies
	 * @return object             Taxonomy class object
	 */
	public function register_taxonomy ( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) return;

		$taxonomy = new Grow_Form_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );

		if(get_option('lf_recaptcha_site_key')) wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null);
	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'grow-form', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'grow-form';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Grow_Form Instance
	 *
	 * Ensures only one instance of Grow_Form is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Grow_Form()
	 * @return Main Grow_Form instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()


	/**
	 * Enqueue link to add CSS through PHP.
	 *
	 * This is a typical WP Enqueue statement, except that the URL of the stylesheet is simply a query var.
	 * This query var is passed to the URL, and when it is detected by scss_maybe_print_css(),
	 * it writes its PHP/CSS to the browser.
	 * thanks to: https://wordpress.org/plugins/simple-custom-css/
	 */
	public function lf_css_register_style()
	{
		$url = home_url();

		if(is_ssl()) $url = home_url( '/', 'https' );

		wp_register_style( 'lf-css_style', add_query_arg( array( 'lf-css' => 1 ), $url ) );
		wp_enqueue_style( 'lf-css_style' );
	}
	/**
	 * If the query var is set, print the custom css rules.
	 */
	public function lf_css_maybe_print_css()
	{
		// Only print CSS if this is a stylesheet request
		if( ! isset( $_GET['lf-css'] ) || intval( $_GET['lf-css'] ) !== 1 ) return;

		ob_start();
		header( 'Content-type: text/css' );
		$raw_content	= get_option( 'lf_custom_css' );
		$content     = wp_kses( $raw_content, array( '\'', '\"' ) );
		$content     = str_replace( '&gt;', '>', $content );
		echo $content; //xss okay
		die();
	}

    /**
	 * Setup the contact form html and submit logic
	 * @access  public
	 * @since   1.0.0
	 * @return  html contact form
	 */
    public function setup_contact_form()
    {
	    if(!$this->check_auth_token())
            return "<strong>Please configure the Clio Grow Contact Form plugin in the settings panel</strong>";
        else
        {
            ob_start();
                $this->contact_form_submit_logic();
                $this->load_contact_form_html();
            return ob_get_clean();
        }
    }

    /**
	 * Generate the contact form html
	 * @access  public
	 * @since   1.0.0
	 * @return  html
	 */
    public function load_contact_form_html()
    {
        include(dirname(__FILE__).'/templates/form.php');
    }

    /**
	 * Verify the auth token is configured
	 * @access  public
	 * @since   1.0.0
	 * @return  boolean
	 */
    public function check_auth_token()
    {
        $token = get_option('lf_authorization_token');
        #probaby needs to be made more robust
        if(!empty($token))
            return true;
        else
            return false;
    }

    /**
	 * Generate the contact form html
	 * @access  public
	 * @since   1.0.8
	 * @return  html contact form
	 */
    public function contact_form_submit_logic()
    {
        if(isset($_POST['lf_submit']))
        {
            //sanitize form values
            $lf_first_name = sanitize_text_field( $_POST["lf_first_name"] );
            $lf_last_name = sanitize_text_field( $_POST["lf_last_name"] );
            $lf_email = sanitize_email( $_POST["lf_email"] );
            $lf_phone = sanitize_text_field( $_POST["lf_phone"] );
            $lf_message = esc_textarea( stripslashes( $_POST["lf_message"] ));
            $lf_disclaimer = "";
			if(isset($_POST["lf_disclaimer_checkbox"]))
			{
				$lf_disclaimer = esc_textarea( stripslashes( $_POST["lf_disclaimer_checkbox"] ));
			}
            $lf_honeypot = sanitize_text_field( $_POST["leave_this_blank_url"] );
            $lf_honeypot_time = sanitize_text_field( $_POST["leave_this_alone"] );
			$lf_recaptcha_response = "";
			if(isset($_POST['g-recaptcha-response']))
			{
				$lf_recaptcha_response = $_POST['g-recaptcha-response'];
			}

			#cant check get_option for empty directly with older versions of php so we assign it first
			$disclaimer_text = get_option('lf_disclaimer_text');

            //manual validation
            if(empty($lf_first_name))
                $errors['first_name'] = "<li>First Name is invalid</li>";
            if(empty($lf_last_name))
                $errors['first_name'] = "<li>Last Name is invalid</li>";
            if(empty($lf_email))
                $errors['email'] = "<li>Email is invalid</li>";
            if(empty($lf_message))
                $errors['message'] = "<li>Message is invalid</li>";
            if(empty($lf_disclaimer) && !empty($disclaimer_text))
                $errors['disclaimer'] = "<li>Must agree to the disclaimer</li>";
            if(!empty($lf_phone) && strlen(preg_replace('/\D/','',$lf_phone)) == 0) #allow blank but not garbage
                $errors['phone'] = "<li>Phone is invalid</li>";
			if(get_option('lf_recaptcha_site_key') && !$this->check_recaptcha($lf_recaptcha_response))
				$errors['recaptcha'] = "<li>Please confirm you are not a robot</li>";

            if(!empty($errors))
            {
                $html = '<ul class="lf_errors">';
                foreach($errors as $key => $value)
                   $html .= $value;
                $html .= '</ul>';

                echo $html;
                return false;
            }
            elseif($this->check_honeypot(compact('lf_honeypot','lf_honeypot_time')))
            {
                $this->log_error("Bot Detected; submission denied; lead dump: ".print_r(compact('lf_first_name','lf_last_name','lf_email','lf_phone','lf_message','lf_referrer','lf_honeypot','lf_honeypot_time'),true));

                #pretend it was successful
                echo "<h3 class='lf_success'>Invalid submission</h3>";
                unset($_POST);
            }
			elseif($this->check_domainblacklist($lf_email))
			{
				$this->log_error("Domain Blacklist Detected; submission denied; lead dump: ".print_r(compact('lf_first_name','lf_last_name','lf_email','lf_phone','lf_message','lf_referrer','lf_honeypot','lf_honeypot_time'),true));

				#pretend it was successful
				echo "<h3 class='lf_success'>Invalid submission</h3>";
				unset($_POST);
			}
            else
            {
                $lf_phone = preg_replace('/\D/','',$lf_phone);
                $lf_referrer = $_SERVER['HTTP_REFERER'];
                $lead = compact('lf_first_name','lf_last_name','lf_email','lf_phone','lf_message','lf_referrer');
                if($this->submit_lead($lead))
                {
                    if(get_option('lf_google_analytics_id'))
                    {
                        echo "
                        <!-- Google Analytics -->
                        <script>
                        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                        ga('create', '".get_option('lf_google_analytics_id')."', 'auto');
                        ga('send', 'event', {
                          'eventCategory': 'Clio GrowForm',
                          'eventAction': 'SuccessfulSubmission',
                          //'eventLabel': 'Label',
                          //'eventValue': 55
                        });
                        </script>
                        <!-- End Google Analytics -->
                        ";
                    }

                    unset($_POST);

					$this->handle_thankyou();
                }
                else
                {
                    echo "<h3 class='lf_failure'>We were unable to send your message. Please try again. If the issue persists please reach out to the firm directly</h3>";
                }
            }
        }
    }

    /**
	 * Return region specific Grow URL
	 * @access  private
	 * @since   1.0.2
	 * @return  string
	 */
    private function get_url($region)
    {
        if($region == 'us')
        {
            return 'https://grow.clio.com/inbox_leads';
        }
        elseif($region == 'eu')
        {
            return 'https://eu.grow.clio.com/inbox_leads';
        }
        elseif($region == 'au')
        {
            return 'https://au.grow.clio.com/inbox_leads';
        }
    }

    /**
	 * Submit lead to Clio Grow API
	 * @access  private
	 * @since   1.0.6
	 * @return  boolean
	 */
    private function submit_lead($lead)
    {
        $region = get_option('lf_grow_region', 'us');
        $url = $this->get_url($region);

        $array = Array(
            'auth_token' => get_option('lf_authorization_token'),
            'inbox_lead' => Array(
                'from_first' => $lead['lf_first_name'],
                'from_last' => $lead['lf_last_name'],
                'from_message' => $lead['lf_message'],
                'from_email' => $lead['lf_email'],
                'from_phone' => $lead['lf_phone'],
                'referring_url' => $lead['lf_referrer']
                )
            );
        $json = json_encode($array);

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json;'
            ),
            'body' => $json
        );

        $response = wp_remote_post($url,$args);

        if($response['response']['code'] == 201)
            return true;
        elseif(is_wp_error($response))
        {
            $error_message = $response->get_error_message();
            $this->log_error("wp http_api error: http status: {$response['response']['code']}; error message: $error_message");
            $this->log_error("wp http_api response dump: ".print_r($response,true));
            return false;
        }
        else
        {
            #currently no documented error codes from the Clio Grow api, so this will need to be made more robust
            #once that happens
            $this->log_error("Not a wp http_api error; response dump: ".print_r($response,true));
            return false;
        }
    }

    /**
	 * Log errors
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */

    public static function log_error($error)
    {
        #file usually located at /wp-content/debug.log
        #make sure the following are uncommented in wp-config.php
        #define('WP_DEBUG', true);
        #define('WP_DEBUG_LOG', true);

       error_log("==grow-form-ERROR==: ".$error);
    }

    /**
	 * Check Honeypot
	 * @access  public
	 * @since   1.0.4
	 * @return  bool
	 */

    public static function check_honeypot($array)
    {
        #check to see if submission was made by a bot:
        #1.) if form was completed in less time than it would take a human; or
        #2.) if an input field, that should be hidden by css, has a value

        $time_to_complete_form_for_human = '5'; #seconds; need to account for auto fill

        $current_completion_time = time() - base64_decode($array['lf_honeypot_time']);

        if($current_completion_time < $time_to_complete_form_for_human)
            return true;

        if(!empty($array['lf_honeypot']))
            return true;

        return false;
    }

	/**
	 * Check reCAPTCHA response
	 * @param $recaptcha_response: provided by reCAPTCHA library
	 * @return  bool
	 */

	private function check_recaptcha($recaptcha_response)
	{
		if(empty($recaptcha_response))
		{
			$this->log_error("reCAPTCHA check failed: empty g-recaptcha-response passed to POST");
			return false;
		}

	    $url = "https://www.google.com/recaptcha/api/siteverify";

        $array = Array(
            'secret' => get_option('lf_recaptcha_secret_key'),
			'response' => $recaptcha_response,
			'remoteip' => $_SERVER['REMOTE_ADDR'],
		);

        $args = array(
            'method' => 'POST',
            'body' => $array
        );

        $response = wp_remote_post($url,$args);
		$body = json_decode($response['body'],true);

        if($body['success'] === true)
            return true;
        else
		{
			if(is_array($body['error_codes']))
				$this->log_error("reCAPTCHA response failed: ".implode(", ", $body['error-codes']));
			else
				$this->log_error("reCAPTCHA response failed: no response codes provided");

			return false;
		}
	}

	/*either redirect or show thank you message depending on settings*/
	protected function handle_thankyou()
	{
		$thankyou_uri = get_option('lf_thankyou_uri');
		if($thankyou_uri)
		{
			#if full fledged URL
			if(filter_var($thankyou_uri, FILTER_VALIDATE_URL) !== FALSE)
			{
				$url = $thankyou_uri;
				//wp_redirect($url); exit; #headers already sent so we have to meta redirect
				echo 'redirecting...';
				echo '<meta http-equiv="refresh" content="0;url='.$url.'">'; exit();

			}
			else #if permalink path
			{
				$page = get_page_by_path( $thankyou_uri );
				if($page)
				{
					$url = get_permalink( $page->ID );
					//wp_redirect($url); exit; #headers already sent so we have to meta redirect
					echo 'redirecting...';
					echo '<meta http-equiv="refresh" content="0;url='.$url.'">'; exit();
				}
			}
		}

		#show thankyou message text
		if( $thankyou_text = get_option('lf_successful_submit_message') )
			echo "<h3 class='lf_success'>$thankyou_text</h3>";
		else
			echo "<h3 class='lf_success'>Thank you! Your inquiry has been successfully submitted</h3>";
	}

	protected function check_domainblacklist($email)
	{
		$domain = substr(strrchr($email, "@"), 1);
		$blacklist = explode("\n",get_option('lf_domain_blacklist'));
		$blacklist = array_map('rtrim',$blacklist); #remove whitespace

		return (in_array($domain,$blacklist)) ? true : false;
	}


	/* for use with <option selected> */
	protected function is_selected($needle,$haystack,$type='')
	{
		$verb = in_array($type,Array('radio','checkbox')) ? 'checked' : 'selected';

		if(is_array($haystack))
			return (in_array($needle,$haystack)) ? "$verb=\"$verb\"" : '';
		else
			return ($needle == $haystack) ? "$verb=\"$verb\"" : '';
	}
}
