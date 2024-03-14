<?php
  /*
    Plugin Name: miniOrange Limit Login Attempts
    Plugin URI: http://miniorange.com
    Description: Security against Login, Brute force attacks by tracking and Blacklisting IPs.
    Author: miniOrange
    Version: 5.0.2
    Author URI: http://miniorange.com
    License: MIT
    */
	
	require('integrations/class_buddypress.php');
	require('integrations/class_icegram_email_subscription.php');
    define( 'MO2F_TEST_MODE_LIMIT_LOGIN_LIMIT_LOGIN', false );
	define('MOWPNS_VERSION','5.0.2');
    $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);

    $plugin_version = $plugin_data['Version'];
    define('LIMITLOGIN_VERSION',$plugin_version);

class WPSecurityPro{


		function __construct()
		{

		register_deactivation_hook(__FILE__		 , array( $this, 'mo_lla_deactivate'		       )		);
		register_activation_hook  (__FILE__		 , array( $this, 'mo_lla_activate'			       )		);
		add_action( 'admin_menu'				 , array( $this, 'mo_lla_widget_menu'		  	   )		);
		add_action( 'admin_enqueue_scripts'		 , array( $this, 'mo_lla_settings_style'	       )		);
		add_action( 'admin_enqueue_scripts'		 , array( $this, 'mo_lla_settings_script'	       )	    );
		add_action( 'lla_show_message'		 	 , array( $this, 'mo_lla_show_message' 			   ), 1 , 2 );
		add_action( 'wp_footer'					 , array( $this, 'mo_lla_footer_link'			   ),100	);
        add_action( 'admin_footer'				 , array( $this, 'mo_lla_feedback_request' 		   ) 		);
        add_action(	'bp_signup_validate'		 , array('Mo_BuddyPress', 'signup_errors'		   )		);
        //add_action( 'upgrader_process_complete'  , array( $this, 'mo_lla_migration_update'		   )		);

            if(get_option('disable_file_editing')) 	 
			define('DISALLOW_FILE_EDIT', true);
			
			$this->includes();
			if(get_option('mo_lla_logout_time')){
				add_filter( 'login_footer', array( $this, 'add_js' ) );
				add_filter('auth_cookie_expiration', array($this,'my_expiration_filter'), 10, 3);
			}
			if (get_option('mo_lla_activate_recaptcha')) {
			    if (get_option('mo_lla_activate_recaptcha_for_buddypress_registration')) {
                    add_action('bp_signup_profile_fields', array($this, 'bp_signup_with_captcha'));
                }
            }
            add_action( 'wp_dashboard_setup', array( $this, 'mollm_register_dashboard_widgets' ) );   
			add_action('admin_notices', array( $this, 'mo_lla_notices' ) );  
		}


		/**
 * remove footer thank you from wordpress
 */
function mo_lla_notices(){
	$curr_date = new DateTime("today");
	$end_date = new DateTime("2022-12-15");
	if((!get_site_option('mo_lla_remove_offer_banner',false) && !class_exists('MOPPM')) && ($curr_date<=$end_date)){
		?>
		 <div class="notices mo_lla-black-friday" > 
		 <div class="mo_lla-offer-logo    "></div>
		 <div style="flex:7;display:flex;height:100%;align-items:center">
		 <div class="mo_lla-bf-support-content">
			<strong>End of the year sale! Upto 50% off</strong>
			<div>On our <a href="admin.php?page=upgrade">Total Website Security</a> plugin</div> 
		</div>
		<div id="countdowns" class="mo_lla-countdown">
				<div class="mo_lla-bf-days">
					<span id="mo_lla-days" class="mo_lla-bf-time"></span> 
					Days
				</div> 
				<div class="mo_lla-bf-days">
					<span id="mo_lla-hours" class="mo_lla-bf-time"></span>
					Hours    
				</div>
				<div class="mo_lla-bf-days">
					<span id="mo_lla-minutes" class="mo_lla-bf-time"></span>
					minutes   
				</div>
				<div class="mo_lla-bf-days">
					<span id="mo_lla-seconds" class="mo_lla-bf-time"></span>
					seconds  
				</div>
			</div>
	</div>
	
	<div class="mo_lla-bf-support-btn">
		<a class="link-banner-btn" href="admin.php?page=upgrade" target="_blank" rel="nofollow">
				<a class="link-banner-btn" href="https://mail.google.com/mail/u/0/?fs=1&amp;tf=cm&amp;source=mailto&amp;su=+miniOrange+Total+Website+Security+Enquiry&amp;to=securityteam@xecurify.com&amp;body=Hi+there,%0d%0a %0d%0aFirst+ Name:%0d%0a %0d%0aLast+ Name:  %0d%0a %0d%0aCompany:    %0d%0a  %0d%0aPhone+ Number: %0d%0a %0d%0aI+would+like+to+get+discount+for+miniOrange+Total+Website+Security+Plugin." target="_blank" rel="nofollow"><button class="trial-banner-btn">Get in touch with us</button></a>
		</a>  
		
	</div> 
	<div class="mo_lla_dismiss_bf"> 
		<div id="mo_lla-bf-dissmiss-permanent" class="mo_lla_dismiss_bf_bg dashicons dashicons-no-alt" title="do not show again"></div>
		<div id="mo_lla-bf-dissmiss" class="mo_lla_dismiss_bf_bg dashicons dashicons-minus" title="dismiss"></div>
	</div>
</div>

<script>
(function () {
const second = 1000,
	  minute = second * 60,
	  hour = minute * 60,
	  day = hour * 24;


let today = new Date(),
	dd = String(today.getDate()).padStart(2, "0"),
	mm = String(today.getMonth() + 1).padStart(2, "0"),
	yyyy = today.getFullYear(),
	nextYear = yyyy + 1,
	dayMonth = "12/15/",
	offers = dayMonth + yyyy;

today = mm + "/" + dd + "/" + yyyy;
if (today > offers) {
  offers = dayMonth + nextYear;
}

const countDown = new Date(offers).getTime(),
	x = setInterval(function() {    
	  const now = new Date().getTime(),
		distance = countDown - now;

		document.getElementById("mo_lla-days").innerText = Math.floor(distance / (day)),
		document.getElementById("mo_lla-hours").innerText = Math.floor((distance % (day)) / (hour));
		document.getElementById("mo_lla-minutes").innerText = Math.floor((distance % (hour)) / (minute)),
		document.getElementById("mo_lla-seconds").innerText = Math.floor((distance % (minute)) / second);


		if (distance < 0) {
			document.getElementById("countdown").style.display = "none";
			document.getElementById("content").style.display = "block";
			clearInterval(x);
		  }
		  //seconds
	}, 1000)
}());


jQuery("#mo_lla-bf-dissmiss").click(()=>{
		jQuery(".mo_lla-black-friday").slideToggle();
})

jQuery("#mo_lla-bf-dissmiss-permanent").click(()=>{
		var data = {
		'action'					: 'lla_login_security',
		'lla_loginsecurity_ajax' 	: 'mo_lla_black_friday_remove',
		'nonce'						:  '<?php echo wp_create_nonce('mo_lla-remove-offer-banner'); ?>'
		};
		jQuery.post(ajaxurl, data, function(response) {
				var response = response.replace(/\s+/g,' ').trim();
				if(response == 'ERROR')
				{
				}
				else
				{
					jQuery(".mo_lla-black-friday").slideToggle();
				}
		   });
        });

        </script> 
		<?php
	}
}
		function mollm_register_dashboard_widgets(){
			 if( !current_user_can( 'manage_options' ) ) return;
              
			 wp_add_dashboard_widget(
            'mollam_stats_widget',
            __( 'miniOrange Limit Login Attempts', 'miniOrange-limit-login-attempts' ),array( $this, 'mollm_dashboard_widgets_content' ),
           					 null,
            				 null,
            				'normal',
            				'high');
		}
		function mollm_dashboard_widgets_content (){
			global $mo_lla_dirName;
			$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
			$lla_database = new Mo_lla_MoWpnsDB;
			$lla_attacks_blocked = $lla_database->get_count_of_attacks_blocked();
			$sqlC 			= $mo_lla_handler->get_blocked_attacks_count("SQL");
			$rceC 			= $mo_lla_handler->get_blocked_attacks_count("RCE");
			$rfiC 			= $mo_lla_handler->get_blocked_attacks_count("RFI");
			$lfiC 			= $mo_lla_handler->get_blocked_attacks_count("LFI");
			$xssC 			= $mo_lla_handler->get_blocked_attacks_count("XSS");
			$totalAttacks	= $sqlC+$lfiC+$rfiC+$xssC+$rceC;
			$totalAttacks	= $sqlC+$lfiC+$rfiC+$xssC+$rceC;
			$countryBlocked = $mo_lla_handler->get_blocked_countries();
			$manualBlocks 	= $mo_lla_handler->get_manual_blocked_ip_count();
			$realTime		= 0;
			$IPblockedByWAF = $mo_lla_handler->get_blocked_ip_waf();
			$totalIPBlocked = $manualBlocks+$realTime+$IPblockedByWAF;
			$lla_count_ips_whitelisted = $lla_database->get_number_of_whitelisted_ips();

			echo'<style>
				  .molla_stat_container{
					margin-top:10px;
					display: flex;
					justify-content: center;
					align-items: center;
					gap:10px;
					padding:10px;
				  }
				  .molla_stat_card{
					flex:1;
					display:flex;
					background:#fff;
					padding:4px;
					border:1px solid #c3c4c7;
					justify-content:center;
					border-radius:10px;
					font-size:3rem;
					font-weight:bold;
					align-items:center;
					flex-direction:column;
				  }
				  .molla_stat_card_footer{
					font-size:0.7rem;
					display:flex;
					padding-top:8px;
				  }

				  .molla_stat_card_footer .dashicons{
					font-size:0.8rem;
				  }
			</style>
			<div class="postbox">
				<div class="molla_stat_container">
					<div class ="molla_stat_card"> '.esc_html($lla_count_ips_whitelisted).'<div class ="molla_stat_card_footer">Whitelisted IPs <a class="dashicons dashicons-admin-generic" href="admin.php?page=mo_lla_login_and_spam"></a></div></div>
					<div class ="molla_stat_card">'. esc_html($totalIPBlocked).'<div class ="molla_stat_card_footer">Blocked IPs <a class="dashicons dashicons-admin-generic" href="admin.php?page=mo_lla_login_and_spam"></a></div></div>
					<div class ="molla_stat_card">' .esc_attr($lla_attacks_blocked).'<div class ="molla_stat_card_footer">Failed Logins <a class="dashicons dashicons-admin-generic" href="admin.php?page=mo_lla_login_and_spam"></a></div></div>
				</div>
			</div>
		';

		}

        // As on plugins.php page not in the plugin
        function mo_lla_feedback_request() {

            if ( 'plugins.php' != basename( sanitize_text_field($_SERVER['PHP_SELF'] ) )) {
                return;
            }
            global $mo_lla_dirName;
            $email = get_option("mo_lla_admin_email");
            if(empty($email)){
                $user = wp_get_current_user();
                $email = $user->user_email;
            }
            $imagepath=plugins_url( '/includes/images/', __FILE__ );

            wp_enqueue_style( 'wp-pointer' );
            wp_enqueue_script( 'wp-pointer' );
            wp_enqueue_script( 'utils' );
            //wp_enqueue_style( 'mo_wpns_admin_plugins_page_style', plugins_url( '/includes/css/molla_feedback_style.css', __FILE__,MOWPNS_VERSION ) );

            include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'feedback.php';
            

        }
        
        public function add_js() {
		echo '<script type="text/javascript">
			var checkbox = document.getElementById("rememberme");
			if ( null != checkbox )
				checkbox.checked = true;
			 document.getElementsByClassName("forgetmenot")[0].style.display = "none";
		</script>';
		}
		function my_expiration_filter($seconds, $user_id, $remember){
			if(get_option('mo_lla_logout_time')){
			$expiration=get_option('mo_lla_logout_time');
			$expiration=$expiration*24*60*60;
			return $expiration;
			}
			if ( $remember ) {
				$expiration = 14*24*60*60;
			} else {
				$expiration = 2*24*60*60; //2 days
			}
			if ( PHP_INT_MAX - time() < $expiration ) {
				$expiration =  PHP_INT_MAX - time() - 5;
			}
			return $expiration;
		}

		function mo_lla_widget_menu()
		{
			$menu_slug = 'Limit_Login_Attempts';
			add_menu_page (	'Limit Login Attempts' , 'Limit Login Attempts' , 'activate_plugins', $menu_slug , array( $this, 'mo_lla'), plugin_dir_url(__FILE__) . 'includes/images/miniorange_icon.png' );

			add_submenu_page( $menu_slug	,'Limit Login Attempts'	,'Dashboard'		,'administrator','dashboard'		, array( $this, 'mo_lla'));
			add_submenu_page( $menu_slug	,'Limit Login Attempts'	,'Settings'			,'administrator','mo_lla_login_and_spam'	, array( $this, 'mo_lla'));
			add_submenu_page( $menu_slug	,'Limit Login Attempts'	,'Reports'			,'administrator','reports'			, array( $this, 'mo_lla'));	 
            add_submenu_page( $menu_slug	,'Limit Login Attempts'	,'Notifications' 	,'administrator','notifications'	, array( $this, 'mo_lla'));
            add_submenu_page( $menu_slug	,'Limit Login Attempts'	,'Account'			,'administrator','wpnsaccount'			, array( $this, 'mo_lla'));
            add_submenu_page( $menu_slug	,'Limit Login Attempts'	,'Premium Features'	,'administrator','advancedblocking'	, array( $this, 'mo_lla'));
            add_submenu_page( $menu_slug	,'Limit Login Attempts'	,'Upgrade','administrator','upgrade'			, array( $this, 'mo_lla'));

			// global $molla_hook_suffix;
    		// $molla_hook_suffix = add_options_page('Limit Login Attempts', 'Limit Login Attempts', 'manage_options', $menu_slug, 'molla');
        }

		function mo_lla()
		{
			global $Mo_lla_wpnsDbQueries;
			$Mo_lla_wpnsDbQueries->mo_plugin_activate();	
			//add_option( 'mo_lla_enable_brute_force' , true);
			add_option( 'mo_lla_show_remaining_attempts' , true);
			add_option( 'mo_lla_enable_ip_blocked_email_to_admin', true);
			add_option('SQLInjection', 1);
			add_option('WAFEnabled' ,0);
			add_option('XSSAttack' ,1);
			add_option('RFIAttack' ,0);
			add_option('LFIAttack' ,0);
			add_option('RCEAttack' ,0);
			add_option('actionRateL',0);
			add_option('Rate_limiting',0);
			add_option('Rate_request',240);
			add_option('limitAttack',10);
			add_option( 'mo_inactive_logout_duration' ,30);
			include 'controllers/main_controller.php';
		}

		function mo_lla_activate() 
		{ 	
			update_site_option('mo2f-remove-ns-acknowledged',true);
			global $Mo_lla_wpnsDbQueries,$mollaUtility, $wpdb;
            update_site_option('limitlogin_activated_time', time());
            update_site_option('mo_lla_plugin_redirect', true);
			$Mo_lla_wpnsDbQueries->mo_plugin_activate();
            $moPluginsUtility = new Mo_lla_MoWpnsHandler();
            $whitelistIPsTable = $wpdb->base_prefix.'wpns_blocked_ips';
            $sql =$wpdb->prepare( "SELECT ip_address FROM $whitelistIPsTable  WHERE 'id' = %d ", array(get_current_user_id()));
            $is_ip_present = $wpdb->get_results($sql);
            if(empty($is_ip_present) || $mollaUtility->get_client_ip() != $is_ip_present[0]->ip_address){
                set_transient('ip_whitelisted',true,5);
                $moPluginsUtility->whitelist_ip($mollaUtility->get_client_ip());
                //show message that users ip is already whitelisted
            }
		}
		function bp_signup_with_captcha()
		{
			if (!is_user_logged_in()){
				if(get_option('mo_lla_activate_recaptcha_for_buddypress_registration'))
				{
					wp_register_script( 'wpns_catpcha_js',esc_url(Mo_lla_MoWpnsConstants::RECAPTCHA_URL));
					wp_enqueue_script( 'wpns_catpcha_js' );
					echo '<div class="g-recaptcha" data-sitekey="'.esc_html(get_option("mo_lla_recaptcha_site_key")).'"></div>';
					echo '<style>#login{ width:349px;padding:2% 0 0; }.g-recaptcha{margin-bottom:5%;}#registerform{padding-bottom:20px;}</style>';
				}
			}
		}
		function mo_lla_deactivate() 
		{
			global $mollaUtility;
			if( !$mollaUtility->check_empty_or_null( get_option('mo_lla_registration_status') ) ) {
				delete_option('mo_lla_admin_email');
			}
			delete_option('mo_lla_admin_customer_key');
			delete_option('mo_lla_admin_api_key');
			delete_option('mo_lla_customer_token');
			delete_option('mo_lla_transactionId');
			delete_option('mo_lla_registration_status');
			delete_option('mo_lla_remove_offer_banner');
		}
		function mo_lla_settings_style($hook)
		{
			if( strpos( $hook, 'Limit_Login_Attempts' ) || strpos( $hook, 'limit-login-attempts' )!==false ){
				wp_register_style( 'mo_lla_admin_settings_style'			, plugins_url('includes/css/style_settings.css', __FILE__),[],MOWPNS_VERSION);
				wp_register_style( 'mo_lla_admin_settings_phone_style'		, plugins_url('includes/css/phone.css', __FILE__),[],MOWPNS_VERSION);
				wp_register_style( 'mo_lla_admin_settings_datatable_style'	, plugins_url('includes/css/jquery.dataTables.min.css', __FILE__),[],MOWPNS_VERSION);
				wp_register_style( 'mo_lla_button_settings_style'			, plugins_url('includes/css/button_styles.css',__FILE__),[],MOWPNS_VERSION);
				wp_enqueue_style('mo_lla_admin_settings_style');
				wp_enqueue_style('mo_lla_admin_settings_phone_style');
				wp_enqueue_style('mo_lla_admin_settings_datatable_style');
				wp_enqueue_style('mo_lla_button_settings_style');
			}

			wp_enqueue_style ('mo_lla_admin_offers'                     , plugins_url('includes/css/mo_lla_offers.css', __FILE__),[],MOWPNS_VERSION);
		}

		function mo_lla_settings_script()
		{
			wp_enqueue_script( 'mo_lla_admin_settings_phone_script'	    , plugins_url('includes/js/phone.js', __FILE__ ));
			wp_enqueue_script( 'mo_lla_admin_settings_script'			, plugins_url('includes/js/settings_page.js', __FILE__ ), array('jquery'));
			wp_enqueue_script( 'mo_lla_admin_datatable_script'			, plugins_url('includes/js/jquery.dataTables.min.js', __FILE__ ), array('jquery'));
		}

		function mo_lla_show_message($content,$type) 
		{
			if($type=="CUSTOM_MESSAGE")
				echo $content;
			if($type=="NOTICE")
				echo '	<div class="is-dismissible notice notice-warning"> <p>'.esc_html($content).'</p> </div>';
			if($type=="ERROR")
				echo '	<div class="notice notice-error is-dismissible"> <p>'.esc_html($content).'</p> </div>';
			if($type=="SUCCESS")
				echo '	<div class="notice notice-success is-dismissible"> <p>'.esc_html($content).'</p> </div>';
		}

		function mo_lla_footer_link()
		{
			echo Mo_lla_MoWpnsConstants::FOOTER_LINK;
			if (get_option('mo_lla_activate_recaptcha_for_email_subscription')) {
                Mo_Icegram_EmailSubscription::recaptcha_for_email_sunscription();
            }
		}

		function includes()
		{
			require('helper/pluginUtility.php');
			require('database/database_functions.php');
			require('helper/utility.php');
			require('handler/ajax.php');
			require('handler/feedback_form.php');
			require('handler/recaptcha.php');
			require('handler/login.php');
			require('handler/registration.php');
			require('handler/logger.php');
			require('handler/spam.php');
			require('helper/curl.php');
			require('helper/constants.php');
			require('helper/messages.php');
			require('views/common-elements.php');
			require('controllers/wpns-loginsecurity-ajax.php');	
		}

	}

	new WPSecurityPro;
?>