<?php
namespace Login_With_AJAX;

/*
 * This is the first version of this add-on, some structural things may change here as we make refinements.
 * Therefore, please be mindful about potential breaking changes if you're using our hooks/filters here or in verification methods.
 * Please get in touch if you're extending this add-on, so we're aware of any potential use-cases to consider.
 *
 * Things to do:
 * Add timeout
 * Add 'remember' button for devices
 * Add custom email template editor
 */

class AJAXify {
	
	public static $ajaxify_js = false;
	public static $em_js = false;
	public static $woocomerce_js = false;
	
	public static function init(){
		// load AJAXify if enabled
		if( !empty( \LoginWithAjax::$data['ajaxify']) ) {
			add_action( 'lwa_register_scripts', array( static::class, 'register_scripts_and_styles' ) );
			// WP Login form
			add_action( 'login_enqueue_scripts', array( static::class, 'enqueue_scripts' ) );
			add_action( 'login_footer', array( static::class, 'footer' ) );
			add_action( 'login_head', array( static::class, 'head' ) );
			if( !empty( \LoginWithAjax::$data['integrate']['events-manager'] ) ) {
				// Events Manager integration
				add_action( 'em_login_footer', array( static::class, 'em_login_footer' ) );
			}
			if( !empty( \LoginWithAjax::$data['integrate']['woocommerce'] ) ) {
				// woocommerce integration
				add_action( 'woocommerce_after_customer_login_form', array( static::class, 'woocommerce_ajaxify' ) );
				add_action( 'woocommerce_login_form_end', array( static::class, 'woocommerce_ajaxify' ) );
			}
			// output actions, only if enabled and not in settings page
			add_action( 'lwa_enqueue', array( static::class, 'enqueue_scripts' ) );
			// trigger loaded
			do_action( 'lwa_ajaxify_loaded' );
		}
	}
	
	public static function register_scripts_and_styles(){
		//Enqueue scripts - Only one script enqueued here.... theme CSS takes priority, then default JS
		$filename = defined('WP_DEBUG') && WP_DEBUG ? 'ajaxify' : 'ajaxify.min';
		wp_register_script("login-with-ajax-ajaxify", plugin_dir_url(__FILE__). $filename . '.js', array('login-with-ajax'), LOGIN_WITH_AJAX_VERSION);
	}
	
	public static function enqueue_scripts(){
		wp_enqueue_script('login-with-ajax-ajaxify');
		wp_enqueue_style('login-with-ajax-ajaxify');
	}
	
	/**
	 * Enqueue the JS and CSS we'd need to make AJAX work on the WP Login page.
	 * @return void
	 */
	public static function enqueue_wp_login(){
		\LoginWithAjax::enqueue_scripts_and_styles(true);
	}
	
	public static function head(){
		?>
		<style type="text/css">
			#login .lwa-status { display: none !important; }
			#login .lwa-status.lwa-status-invalid, #login .lwa-status.lwa-status-confirm { display: block !important; }
		</style>
		<?php
	}

	public static function footer(){
		?>
		<script type="text/javascript">
			document.addEventListener( 'lwa_ajaxify_init', function(){
				// add fields that'll allow LWA to work, and override the regular status element
				let addStatusElement = function ( form, statusElement ) {
					if( !statusElement.hasClass('lwa-ajaxify-status') ) {
						let el = jQuery('div.lwa-ajaxify-status');
						if (el.length === 0) {
							el = jQuery('<div class="lwa-status login lwa-ajaxify-status"></div>');
						}
						statusElement[0] = el[0];
						if( statusElement.length === 0 ){ statusElement.length = 1; }
						jQuery( Object.keys(LWA_Ajaxify.ajaxifiables).join(' span.lwa-status ,') + ' span.lwa-status' ).remove();
					}
					let lwa = form.closest('.lwa');
					statusElement.prependTo( lwa );
				};
				let handleStatus = function ( response, statusElement ) {
					if (statusElement.hasClass('lwa-ajaxify-status')) {
						if (response.result) {
							statusElement.attr('id', '');
							statusElement.addClass('success');
						} else {
							statusElement.attr('id', 'login_error');
							statusElement.removeClass('success');
						}
					}
				}
				// append
				Object.assign( LWA_Ajaxify.ajaxifiables, {
					'#loginform' : { type : 'login', handleStatus: handleStatus, addStatusElement: addStatusElement },
					'#registerform' : { type : 'register', handleStatus: handleStatus, addStatusElement: addStatusElement },
					'#lostpasswordform' : { type : 'remember', handleStatus: handleStatus, addStatusElement: addStatusElement },
				} );
			} );
		</script>
		<?php
	}
	
	public static function woocommerce_ajaxify() {
		if ( static::$woocomerce_js ) return false;
		?>
		<script type="text/javascript">
			document.addEventListener( 'lwa_ajaxify_init', function(){
				// append
				Object.assign( LWA_Ajaxify.ajaxifiables, {
					'.woocommerce-form-login' : {
						type : 'login',
						init : function( form ) {
							form.querySelector('input[name="username"]').name = 'log';
							form.querySelector('input[name="password"]').name = 'pwd';
						}
					},
					'.woocommerce-form-register' : { type : 'register' },
					'.woocommerce-form-lost-password' : { type : 'remember' },
				} );
			} );
		</script>
		<?php
		static::$woocomerce_js = true;
	}
	
	public static function em_login_footer(){
		if( static::$em_js ) return false;
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				// add fields that'll allow LWA to work, and override the regular status element
				$('.em-login-form').wrap('<div class="lwa-wrapper"></div>')
					.wrap('<div class="lwa"></div>')
					.addClass('lwa-form')
					.append( $('<input type="hidden" name="login-with-ajax" value="login">') );
				$(document).on('lwa_addStatusElement', function(e, form, statusElement){
					if( form.hasClass('em-login-form') ) {
						if( !statusElement.hasClass('lwa-ajaxify-status') ) {
							let el = $('span.lwa-ajaxify-status');
							if (el.length === 0) {
								el = $('<span class="lwa-status lwa-ajaxify-status"></div>');
							}
							statusElement[0] = el[0];
							if( statusElement.length === 0 ){ statusElement.length = 1; }
						}
						let lwa = form.closest('.lwa');
						if( !statusElement.hasClass('lwa-ajaxify-status') ) {
							lwa.find('span.lwa-status').remove();
						}
						statusElement.prependTo( lwa );
					}
				});
				document.dispatchEvent( new CustomEvent('lwa_ajaxify_loaded') );
			});
		</script>
		<?php
		static::$em_js = true;
	}
	
}
AJAXify::init();