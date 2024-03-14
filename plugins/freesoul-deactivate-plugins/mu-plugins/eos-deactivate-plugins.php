<?php
/*
  Plugin Name: freesoul deactivate plugins [fdp]
  Description: mu-plugin automatically installed by freesoul deactivate plugins
  Version: 2.2.1
  Plugin URI: https://freesoul-deactivate-plugins.com/
  Author: Jose Mortellaro
  Author URI: https://josemortellaro.com/
  License: GPLv2
*/

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$active_plugins = eos_dp_get_option( 'active_plugins' );

if( $active_plugins && is_array( $active_plugins ) && ! in_array( 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php', $active_plugins ) && is_multisite() ) return;

if( !defined( 'FDP_PRO_IS_ACTIVE' ) ){
	define( 'FDP_PRO_IS_ACTIVE', $active_plugins && is_array( $active_plugins ) && in_array( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php', $active_plugins ) );
}

if( !( $active_plugins && is_array( $active_plugins ) ) ){
	$active_plugins = array();
}
if( isset( $_REQUEST['action'] ) && 'eos_dp_save_firing_order' === $_REQUEST['action'] && isset( $_POST['eos_dp_plugins'] ) && is_array( $_POST['eos_dp_plugins'] ) ){
	$fdp_plugins = array( 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php' );
	if( in_array( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',$active_plugins ) ){
		$fdp_plugins[] = 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php';
	}
	$active_plugins = array_unique( array_merge( $fdp_plugins,array_map( 'sanitize_text_field',$_POST['eos_dp_plugins'] ) ) );
}
$GLOBALS['fdp_all_plugins'] = $active_plugins;
if( defined( 'FDP_EMERGENCY_ADMIN_THEME_OFF' ) && FDP_EMERGENCY_ADMIN_THEME_OFF ){
	add_filter( 'theme_root','__return_false',20 );
	add_filter( 'stylesheet','__return_false',20 );
	add_filter( 'template','__return_false',20 );
}

if( !is_admin() && defined( 'FDP_EXCLUDE_MU' ) && FDP_EXCLUDE_MU && isset( $_GET[FDP_EXCLUDE_MU] ) && 'true' === $_GET[FDP_EXCLUDE_MU] ){
	return;
}

if( is_admin() && isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( $_REQUEST['action'] ),array( 'activate-selected','deactivate-selected' ) ) ){
	return;
}

define( 'EOS_DP_MU_VERSION','2.2.1' );
define( 'EOS_DP_MU_PLUGIN_DIR',untrailingslashit( dirname( __FILE__ ) ) );


foreach( array(
	'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php'
) as $fdp_addon ){
	if( defined( 'WP_PLUGIN_DIR' ) && in_array( $fdp_addon, $active_plugins )  && file_exists( WP_PLUGIN_DIR . '/' . dirname( $fdp_addon ) . '/inc/mu-plugin.php' ) ){
		// Require mu-plugin file of the FDP addon.
		require_once WP_PLUGIN_DIR . '/' . dirname( $fdp_addon ) . '/inc/mu-plugin.php';
	}
}

add_filter( 'fdp_active_by_addon', function( $plugins ) {
	$fdp_addons = eos_dp_get_option( 'fdp_addons', array() );
	if( ! empty( $fdp_addons ) ) {
		global $eos_dp_paths;
		if( ! $eos_dp_paths || empty( $eos_dp_paths ) ) {
			$eos_dp_paths = array();
		}
		foreach( $fdp_addons as $fdp_addon ){
			if( defined( 'WP_PLUGIN_DIR' ) && in_array( $fdp_addon, $plugins )  && file_exists( WP_PLUGIN_DIR . '/' . dirname( $fdp_addon ) . '/conditions.php' ) ){
				// Require conditions of the FDP addon.
				require WP_PLUGIN_DIR . '/' . dirname( $fdp_addon ) . '/conditions.php';
				if( isset( $conditions ) && $conditions ) {
					$disabled_by_addon = eos_dp_get_option( 'eos_dp_' . sanitize_text_field( dirname( $fdp_addon ) ), array() );
					if( $disabled_by_addon && is_array( $disabled_by_addon ) && ! empty( $disabled_by_addon ) ) {
						global $eos_dp_debug;
						$eos_dp_debug['info'][] = sprintf( '%s disabled by the FDP add-on %s', implode( ', ', array_map( 'eos_dp_get_plugin_name_by_slug', $disabled_by_addon ) ), esc_attr( eos_dp_get_plugin_name_by_slug( $fdp_addon ) ) );
						$eos_dp_paths = array_unique( array_merge( $eos_dp_paths, $disabled_by_addon ) );
						return array_unique( array_values( array_diff( $plugins,$disabled_by_addon ) ) );
					}
				}
			}
		}	
	}
	return $plugins;
} );
if( isset( $_GET['fdp-autosuggestion'] ) && 'on' === $_GET['fdp-autosuggestion'] &&  get_site_transient( 'eos_dp_pro_scanning_unused_plugins' ) ) {
	// We need more an higher memory limit during the auto-suggestion.
	@ini_set( 'memory_limit', '2048M' );
}

/**
 * Update options in case of single or multisite installation.
 *
 * @since 1.9.0
 *
 */
function eos_dp_update_option( $option, $newvalue ) {
	if ( ! is_multisite() ) {
		$autoload = in_array(
			$option,
			apply_filters(
				'fdp_autoloaded_options',
				array(
					'eos_dp_archives',
					'eos_dp_desktop',
					'eos_dp_mobile',
					'eos_dp_frontend_everywhere',
					'eos_dp_by_url',
					'eos_post_types_plugins',
					'eos_dp_opts',
					'eos_dp_pro_main',
					'fdp_addons',
					'active_plugins',
					'eos_dp_by_plugin'
				)
			)
		);
		return update_option( $option, $newvalue, $autoload );
	} else {
		return update_blog_option( get_current_blog_id(), $option, $newvalue );
	}
}

if(
	!isset( $_REQUEST['fdp-refilling-rules'] )
	&& !did_action( 'activated_plugin' )
	&& !did_action( 'deactivated_plugin' )
	&& !did_action( 'upgrader_process_complete' )
	&& !did_action( 'core_upgrade_preamble' )
	&& !did_action( 'update_option_WPLANG' )
){
	add_action( 'update_option',function( $option,$old_value,$value ){
		/**
		 * Check if any plugin, themes, or mu-plugins change the Rewrite Rules during the same request.
		 *
		 * @since 1.9.0
		 *
		 */
		static $fdp_rewrite = false;
		if( !$fdp_rewrite ){
			$time = microtime(1);
			$fdp_rewrite = true;
	 	  if( 'rewrite_rules' === $option && $value !== $old_value ){
				$plugindir = defined( PLUGINDIR ) ? PLUGINDIR.'/' : 'wp-content/plugins/';
				$themedir = str_replace( ABSPATH,'',get_theme_root() );
		    $trace = debug_backtrace();
		    $trace1 = $trace[1];
		    $trace = array_reverse( $trace );
		    $output = $code = '';
				$cause = $line = $file = false;
		    foreach( $trace as $arr ){
		      if( isset( $arr['file'] )  ){
		        if( false !== strpos( $arr['file'],$plugindir  ) ){
		          $pArr = explode( $plugindir,$arr['file'] );
		          if( isset( $pArr[1] ) ){
								$line = $arr['line'];
								$file = $arr['file'];
		            $cause = strtoupper( str_replace( '-',' ',dirname( $pArr[1],substr_count( $pArr[1],'/' ) ) ) );
		            $output .= PHP_EOL.sprintf( 'Rewrite Rules updated by the function "%s" in the file <strong>"%s" at line %s of the plugin %s</strong>',$arr['function'],str_replace( ABSPATH,'',$arr['file'] ),$arr['line'],esc_attr( $cause ) );
								break;
		          }
		        }
		        elseif( false !== strpos( $arr['file'],$themedir ) ){
							$line = $arr['line'];
							$file = $arr['file'];
		          $tArr = explode( $themedir,$arr['file'] );
		          if( isset( $tArr[1] ) ){
		            $cause = strtoupper( ltrim( str_replace( '-',' ',dirname( $tArr[1],substr_count( $tArr[1],'/' ) - 1 ) ),'/' ) );
		            $output .= PHP_EOL.sprintf( 'Rewrite Rules updated by the function "%s" in the file <strong>"%s" at line %s of the theme %s</strong>',$arr['function'],str_replace( ABSPATH,'',$arr['file'] ),$arr['line'],esc_attr( $cause ) );
								break;
		          }
		        }
		        elseif( false !== strpos( $arr['file'],WPMU_PLUGIN_DIR ) ){
							$line = $arr['line'];
							$file = $arr['file'];
		          $muArr = explode( WPMU_PLUGIN_DIR,$arr['file'] );
		          if( isset( $muArr[1] ) ){
		            $cause = strtoupper( rtrim( ltrim( str_replace( '-',' ',$muArr[1] ),'/' ),'.php' ) );
		            $output .= PHP_EOL.sprintf( 'Rewrite Rules are updated by the function "%s" in the file <strong>"%s" at line %s</strong> of the <strong>MU-plugin %s</strong>',$arr['function'],str_replace( ABSPATH,'',$arr['file'] ),$arr['line'],esc_attr( $cause ) );
								$break;
		          }
		        }
		      }
		    }
				if( $cause && '' !== $output ){
					$output .= PHP_EOL.PHP_EOL.sprintf( 'The rewrite rules where updated requesting %s',sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
					if( $line && $file ){
						$output .= eos_dp_get_code_extract( $line,$file );
					}
					$msg = sprintf( 'Be careful! It looks like <strong>%s updates the rewrite rules during the same HTTP request</strong>.',$cause );
					$msg .= PHP_EOL.sprintf( 'FDP rebuilt again the rewrite rules with all plugins active. You should not have issues due to missing rewrite rules, but you may have more load on your server and FDP has to keep all the plugins active to avoid missing rewrite rules when %s saves them into the database. If %s frequently updates the rewrite rules you will have issues with the performance.',$cause,$cause );
					$msg .= PHP_EOL.'<strong>'.sprintf( 'If after dismissing this notice, it appears over again and again, please, open a thread on the FDP support forum, and give us the information below.' ).'</strong>';
					$msg .= PHP_EOL.PHP_EOL.wp_kses_post( $output ).$code;
					$msg .= PHP_EOL.PHP_EOL.sprintf( 'If it is a recurring issue, we also suggest you to contact the support of %s',$cause );
					eos_dp_update_admin_notices( 'rewrite_rules',$msg );
					do_action( 'fdp_flush_rewrite_rules' );
					eos_dp_update_option( 'rewrite_rules','' );

					wp_remote_get( add_query_arg( array( 'fdp-refilling-rules' => 1,'action' => 'deactivate','plugin' => 'none','t' => time() ),home_url() ),array( 'sslverify' => false ) );
				}

		  }
	  }
	},10,3 );
}
$rewrite_rules = eos_dp_get_option( 'rewrite_rules' );

if( !$rewrite_rules ){
	add_action( 'send_headers', function(){
		header( 'Disabled-Plugins: none because of empty rewrite rules' );
	}, 100 );
	return;
}

if( !defined( 'FDP_REMOVE_FILTERS_BEFORE_FIRST_PLUGIN' ) ){
	define( 'FDP_REMOVE_FILTERS_BEFORE_FIRST_PLUGIN',false );
}
foreach( array( 'activate_plugin','deactivate_plugin','deactivated_plugin','pre_update_option_active_plugins' ) as $action ){
	// Prevent saving wrong set of active plugins if programmatically activating or deactivating plugins.
	if( did_action( $action ) ){
		return;
	}
}

if( !isset( $_SERVER['HTTP_HOST'] ) || !isset( $_SERVER['REQUEST_URI'] ) ){
	add_action( 'send_headers', function(){
		global $eos_dp_paths;
		$n = $eos_dp_paths ? count( array_unique( array_filter( $eos_dp_paths ) ) ) : 'none';
		header( 'Disabled-plugins: none because REQUEST_HOST or REQUEST_URI not set' );
	}, 100 );
	return;
}

if( isset( $_REQUEST['CODE_PROFILER_ON'] ) || isset( $_REQUEST['CODE_PROFILER_PRO_ON'] ) ){
	$opts = eos_dp_get_option( 'fdp_code_profiler' );
	if( isset( $opts['fdp_cp'] ) && 'cp' === $opts['fdp_cp'] ){
		add_action( 'muplugins_loaded',function() {
			eos_dp_filter_active_plugins( 'eos_dp_code_profiler',0 );
		} );
	}
}
if( isset( $_GET['page'] ) && false !== strpos( sanitize_text_field( $_GET['page'] ),'eos_dp_' ) ){
	if( !defined( 'QM_DISABLED' ) ){
		define( 'QM_DISABLED',true );
	}
	if( defined( 'FDP_ONLY_FDP' ) && isset( $_GET['only-fdp'] ) && FDP_ONLY_FDP === $_GET['only-fdp'] ){
		add_action( 'muplugins_loaded',function() {
			eos_dp_filter_active_plugins(  'eos_dp_only_fdp',0 );
		} );
	}
}
$opts = eos_dp_get_option( 'eos_dp_pro_main' );

if( $opts && isset(  $opts['eos_dp_general_setts'] ) && isset(  $opts['eos_dp_general_setts']['dev_mode'] ) && '1' ===  $opts['eos_dp_general_setts']['dev_mode'] && !isset( $_GET['fdp-assets'] ) ){
	$dev = substr( md5( sanitize_text_field( $_SERVER['HTTP_HOST'] ) ),0,8 );
	if( !isset( $_GET['fdp-dev'] ) || $dev !== $_GET['fdp-dev'] ){
		if( !is_admin() && !defined( 'FDP_OFF' ) ){
			define( 'FDP_OFF',true );
			add_action( 'send_headers', function(){
				global $eos_dp_paths;
				$n = $eos_dp_paths ? count( array_unique( array_filter( $eos_dp_paths ) ) ) : 'none';
				header( 'Disabled-plugins: none because development mode active' );
			}, 100 );
			return;
		}
		elseif( is_admin() && !defined( 'FDP_DEV_MODE' ) ){
			define( 'FDP_DEV_MODE',$dev );
		}
	}
	else{
		if( !defined( 'FDP_DEV_MODE' ) ){
			define( 'FDP_DEV_MODE',$dev );
		}
	}
}

$GLOBALS['eos_dp_debug'] = array( 'info' => array(),'log' => array(),'error' => array() );

if( isset( $eos_dp_debug ) && $eos_dp_debug ){
	extract( $eos_dp_debug );
}
if(
	(
		( !isset( $_REQUEST['s'] ) || ( defined( 'EOS_DP_URL_APPLY_ON_SEARCH' ) && true === EOS_DP_URL_APPLY_ON_SEARCH ) )
		|| isset( $_GET['eos_dp_preview'] )
	)
	&& ! ( wp_doing_ajax() || isset( $_REQUEST['wc-ajax'] ) )
){
	$opts_by_path = false;
	$eos_dp_disabled_plugins = array();
	global $eos_dp_disabled_plugins;
	$post_types_matrix = eos_dp_get_option( 'eos_post_types_plugins' );
	$post_types = is_array( $post_types_matrix ) ? array_keys( $post_types_matrix ) : array();
	$uri = remove_query_arg( 'show_disabled_plugins',remove_query_arg( 'eos_dp_debug_options',apply_filters( 'fdp_request_uri',sanitize_text_field( $_SERVER['HTTP_HOST'] ).sanitize_text_field(  $_SERVER['REQUEST_URI'] ) ) ) );
	$GLOBALS['fdp_uri'] = $uri;
	if( defined( 'FDP_SKIP_URLS' ) && is_array( FDP_SKIP_URLS ) && !empty( FDP_SKIP_URLS ) ){
		foreach( FDP_SKIP_URLS as $fdp_no_url ){
			if( false !== strpos( $uri, $fdp_no_url ) ){
				if( !defined( 'FDP_STANDARD_DISABLED' ) ){
					define( 'FDP_STANDARD_DISABLED', true );
				}
				// Don't run FDP on the URLs defined in wp-config.php.
				return;
			}
		}
	}
	if( $opts && isset( $opts['general_bloat'] ) ){
		$bloat = $opts['general_bloat'];
		if( isset( $bloat['favicon'] ) && 'false' === $bloat['favicon'] && 'favicon.ico' === str_replace( '/','',sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ){
			add_action( 'muplugins_loaded',function() {
				eos_dp_filter_active_plugins( '__return_empty_array',0,1 );
				add_filter( 'theme_root','__return_false',20 );
				add_filter( 'stylesheet','__return_false',20 );
				add_filter( 'template','__return_false',20 );
			} );
			return;
		}
	}
	if( isset( $opts['translation_urls'] ) && $opts['translation_urls'] && !empty( $opts['translation_urls'] ) ){
		$ignore_parts = explode( ',',sanitize_text_field( $opts['translation_urls'] ) );
		$uri_close = $uri.'/';
		foreach( $ignore_parts as $str ){
			$str = str_replace( array( '/','?','&' ),array( '','','' ),$str );
			$uri_close = str_replace( '&'.$str.'/','/',str_replace( '?'.$str.'/','/',str_replace( '/'.$str.'/','/',$uri_close ) ) );
		}
		$uri = rtrim( rtrim( $uri_close,'//' ),'/' );
	}
	if( !is_admin() ){
		$translators = array(
			'transposh' => array( 'transposh-translation-filter-for-wordpress/transposh.php','transposh_options','viewable_languages','string' ),
			'wpglobus' => array( 'wpglobus/wpglobus.php','wpglobus_option','enabled_languages','array' )
		);
		foreach( $translators as $translator ){
			if( isset( $GLOBALS['fdp_all_plugins'] ) && is_array( $GLOBALS['fdp_all_plugins'] ) && in_array( $translator[0],$GLOBALS['fdp_all_plugins'], true ) ){
				$trans_opts = get_option( $translator[1] );
				if( $trans_opts && isset( $trans_opts[$translator[2]] ) && '' !== $trans_opts[$translator[2]] ){
					$trans_langs = 'string' === $translator[3] ? explode( ',',sanitize_text_field( $trans_opts[$translator[2]] ) ) : array_keys( $trans_opts[$translator[2]] );
					foreach( $trans_langs as $trans_lang ){
						$uri = trim( str_replace( '/'.$trans_lang.' ','/',str_replace( '/'.$trans_lang.'/','/',$uri.' ' ) ),' ' );
					}
				}
			}
		}
	}
	if( !is_admin() && is_array( $post_types ) && !wp_doing_cron() ){
		$home_page = false;
		$clean_uri = '';
		$arr = array();
		$from_url = $from_url_filter = $rest_api = false;
		if(
			( !empty( $_POST ) && ( !defined( 'EOS_DP_ALLOW_POST' ) || false === EOS_DP_ALLOW_POST ) && ( !defined( 'FDP_ALLOW_POST' ) || false === FDP_ALLOW_POST ) )
		 || defined( 'REST_REQUEST' )
		 || ( isset( $_SERVER['REQUEST_URI'] ) && false !== strpos( sanitize_text_field( $_SERVER['REQUEST_URI'] ),'/wp-json/' ) )
	 ){
			$option = 'eos_dp_by_rest_api';
			$opts_theme = eos_dp_get_option( 'eos_dp_by_rest_api_theme' );
			$rest_api = true;
		}
		else{
			$option = 'eos_dp_by_url';
		}
		$urlsA = apply_filters( 'fdp_url_front_options',eos_dp_get_option( $option ) );

		if( !isset( $_GET['eos_dp_preview'] ) ){
			if( is_array( $urlsA ) && is_array( $urlsA ) && !empty( $urlsA ) ){
				foreach( $urlsA as $urlA ){
					if( isset( $urlA['url'] ) && '' !== $urlA['url'] ){
						if( eos_dp_is_url_matched( $urlA['url'], $uri ) ){
							$eos_dp_paths = explode( ',',$urlA['plugins'] );
							if( isset( $urlA['f'] ) && '1' === $urlA['f'] ){
								$from_url_filter = defined( 'FDP_PRO_IS_ACTIVE' ) && 'FDP_PRO_IS_ACTIVE' && isset( $urlA['plugins'] ) ? $urlA['plugins'] : false;
							}
							$from_url = true;

							if( $rest_api ){
								if( isset( $opts_theme ) && $opts_theme && !empty( $opts_theme ) && isset( $opts_theme[$urlA['url']] ) ){
									if( false === $opts_theme[$urlA['url']] ){
										add_action( 'plugins_loaded','eos_dp_replace_theme',99 );
									}
								}
								$info[] = 'Plugins disabled according to the Rest API settings';
							}
							else{
								$maybe_singles = isset( $urlA['needs_url'] ) && absint( $urlA['needs_url'] ) > 0 ? ' Ignored URL query arguments because the post ID '.$urlA['needs_url'].' needs a custom URL.' : '';
								if( $from_url_filter ){
									$info[] = sprintf( 'Plugins filtered by Custom URLs. Matched %s.%s',$urlA['url'],$maybe_singles );
								}
								else{
									$info[] = sprintf( 'Plugins disabled by Custom URLs. Matched %s.%s',$urlA['url'],$maybe_singles );
								}
							}
							break;
						}
					}
				}
			}
		}
		$permalink_structure = eos_dp_get_option( 'permalink_structure' );
		$permalink_structure_base = basename( $permalink_structure );
		if( ( !isset( $rest_api ) || !$rest_api ) && ( !$from_url || $from_url_filter ) && false !== strpos( $permalink_structure_base,'%postname%' ) ){
			$uriArr = explode( '?',$uri );
			$uri = $clean_uri = $uriArr[0];
			if( !isset( $_GET['page_id'] ) && !isset( $_GET['p'] ) ){
				$home_uri = str_replace( 'https://','',str_replace( 'http://','',home_url( '/' ) ) );
				$wpml_lang_query = false;
				if( isset( $_GET['lang'] ) && fdp_is_plugin_globally_active( 'sitepress-multilingual-cms/sitepress.php' ) ){
					$fdp_wpml = eos_dp_get_option( 'icl_sitepress_settings' );
					$fdpneg = isset( $fdp_wpml['language_negotiation_type'] ) ? $fdp_wpml['language_negotiation_type'] : 3;
					if( '3' === $fdpneg || 3 === $fdpneg ){
						$wpml_lang_query = true;
					}
				}
				if( $uri !== $home_uri || $wpml_lang_query ){
					$arr = array_filter( explode( '/',$uri ) );
					$after_home_uri = str_replace( rtrim( $home_uri,'/' ),'',implode( '/',$arr ) );
					$after_home_uriArr = explode( '?',$after_home_uri );
					$after_home_uri = $after_home_uriArr[0];
					$after_home_uriArr = explode( '#',$after_home_uri );
					$after_home_uri = str_replace( '%','',untrailingslashit( $after_home_uriArr[0] ) );
					$after_postnameArr = explode( '%',$permalink_structure_base );
					$last_index = count( $after_postnameArr );
					if( !empty( $after_postnameArr ) && $last_index > 0 && isset( $after_postnameArr[$last_index - 1] ) ){
						$after_home_uri = str_replace( $after_postnameArr[$last_index - 1],'',$after_home_uri );
					}
					if( $wpml_lang_query ){
						$after_home_uri .= '/?lang='.esc_attr( sanitize_text_field( $_GET['lang'] ) );
					}
					if( !defined( 'FDP_FORCE_DB_READING' ) || true !== FDP_FORCE_DB_READING ){
						$opts_by_path = eos_dp_get_opts_by_url( $after_home_uri );
					}
					if( $opts_by_path && isset( $opts_by_path['post_id'] ) && ( ! isset( $opts_by_path['post_status'] ) || ! in_array( $opts_by_path['post_status'], array( 'draft') ) || eos_dp_is_user_logged() ) ){
						if( absint( $opts_by_path['post_id'] ) > 0 ){
							$eos_page_id = absint( $opts_by_path['post_id'] );
							$info[] = sprintf( 'Evaluated Singles Settings. ID: %s. Post type: %s',$eos_page_id,$opts_by_path['post_type'] );
						}
						elseif( 'archive' === $opts_by_path['post_id'] ){
							$eos_page_id = esc_attr( $opts_by_path['post_id'] );
						}
					}
					else{
						$p = false;
						$post_types_query = $post_types;
						if( '' !== $after_home_uri ){
							if( false !== strpos( $after_home_uri,'/' ) && $uri !== str_replace( '//', '/', $home_uri . $after_home_uri ) ) {
								// the URL looks like https://example-dommain.com/level1/level2/ or https://example-dommain.com/level1/level2/level3/...
								// Maybe child page or post or custom post
								if( substr_count( $permalink_structure,'/' ) > 1 ){
									// The URL looks like https://example-dommain.com/blog/level2/ with a permalink structure that looks like /blog/%postname%/ or /year/month/day/%postname%.
									// Blog post
									$p = get_page_by_path( basename( $after_home_uri ),'OBJECT',$post_types );
									if( is_object( $p ) && ( ! isset( $p->post_status ) || ! in_array( $p->post_status, array( 'draft' ) ) || eos_dp_is_user_logged() ) ){
										$eos_page_id =  $p->ID;
									}
									
								}
								else{
									// the URL looks like https://example-dommain.com/level1/level2/ or https://example-dommain.com/level1/level2/level3/...
									// but level1 is not included in the permalink structure.
									// Maybe child page or custom post.
									if( in_array( 'post',array_values( $post_types_query ) ) ){
										unset( $post_types_query[array_search( 'post',$post_types_query )] );
									}
									// First we try with child page or child post.
									$p = get_page_by_path( $after_home_uri,'OBJECT',$post_types_query );
									if( is_object( $p ) && isset( $p->post_type ) && 'page' === $p->post_type && isset( $p->post_parent ) && absint( $p->post_parent ) > 0 ){
										// It's a child page or a child post.
										$eos_page_id =  $p->ID;
									}
									else{
										// Maybe custom post.
										if( in_array( 'page',array_values( $post_types_query ) ) ){
											unset( $post_types_query[array_search( 'page',$post_types_query )] );
										}
										$p = get_page_by_path( basename( $after_home_uri ),'OBJECT',$post_types_query );
										if( is_object( $p ) && isset( $p->post_type ) ){
											$first_id = $p->ID;
											// We have retrieved a custom post type, we still need to check if different custom post types have the same slug.
											unset( $post_types_query[array_search( $p->post_type,$post_types_query )] );
											$second_p = get_page_by_path( basename( $after_home_uri ),'OBJECT',$post_types_query );
											if( !is_object( $second_p ) ){
												// Only a customm post type is retrieved.
												$eos_page_id =  $p->ID;
												$info[] = sprintf( 'Evaluated Singles Settings. ID: %s. Post type: %s',$p->ID,$p->post_type );
											}
											else{
												// More than a custom post type has the same slug, and we still don't know wich one is the right one.
												$error[] = sprintf( 'No plugin was disabled because more than a custom post type have the same slug. IDs: %s (%s), %s (%s)',$first_id,$p->post_type,$second_p->ID,$second_p->post_type );
												$eos_dp_debug['error'] = $error;
											}
										}
									}
								}
							}
							else{
								// The URL looks like https://example-domain.com/page-example/.
								$first_id = $second_id = false;
								// It's a blog post or a page.
								$p = get_page_by_path( $after_home_uri,'OBJECT',array( 'post','page' ) );
								if( is_object( $p ) && isset( $p->post_type ) ){
									$first_id = $p->ID;
									$first_post_type = $p->post_type;
									// We still need to check if both a page and a post have the same slug.
									unset( $post_types_query[array_search( $p->post_type,$post_types_query )] );
									$second_p = get_page_by_path( $after_home_uri,'OBJECT',$post_types_query );
									if( !is_object( $second_p ) ){
											$eos_page_id =  $p->ID;
											$info[] = sprintf( 'Evaluated Singles Settings. ID %s',$p->ID );
									}
									else{
										$second_id = $second_p->ID;
										$second_post_type = $second_p->post_type;
										$double_solved = false;
										if( $first_id && $second_id ){
											$doublesIDs = array( $first_post_type => $first_id,$second_post_type => $second_id );
											$doublesObj = array( $first_post_type => $p,$second_post_type => $second_p );
											if( isset( $doublesIDs['post'] ) && isset( $doublesObj['post'] ) ){
												// A blog post and a page have the same slug.
												$double_solved = false;
											}
										}
										if( !$double_solved ){
											// A page and a post have the same slug. Impossible to decide which one is the right one.
											$ids = $first_id && $second_id ? sprintf( ' IDs: %s (%s), %s (%s)',$first_id,$first_post_type,$second_id,$second_post_type ) : '';
											$error[] = 'No plugin was disabled because a page and a post have the same slug and it is not possible to know which one is the right one.'.$ids;
											$eos_dp_debug['error'] = $error;
										}
									}
								}
							}
						}
					}
				}
				else{
					// It's the front page.
					if(
						isset( $_REQUEST )
						&& !empty( $_REQUEST )
						&& !isset( $_REQUEST['eos_dp_preview'] )
						&& !isset( $_REQUEST['show_disabled_plugins'] )
						&& !isset( $_REQUEST['fdp_post_id'] )
						&& !isset( $_REQUEST['fdp_assets'] )
						&& !isset( $_REQUEST['fdp-assets'] )
						&& !isset( $_GET['fbclid'] )
						&& !isset( $_GET['site_in_progress'] )
						&& !isset( $_REQUEST['eos_dp_debug_options'] )
						&& !isset( $_REQUEST['CODE_PROFILER_ON'] )
						&& !isset( $_REQUEST['CODE_PROFILER_PRO_ON'] )
						&& !( isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' )
					){
						if( eos_dp_is_maybe_ajax() ){
							return;
						}
				 	}
					if( isset( $_POST ) && !empty( $_POST ) && !( isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ) ){
						return;
					}
					$eos_page_id = eos_dp_get_option( 'page_on_front' );
					$info[] = sprintf( 'Plugins disabled according to the homepage ID %s.',$eos_page_id );
					$p = get_page( $eos_page_id );
					$home_page = true;
				}
			}
			else{
				$eos_page_id = isset( $_GET['page_id'] ) ? absint( $_GET['page_id'] ) : absint( $_GET['p'] );
				$p = get_page( $eos_page_id );
				global $eos_page_id;
			}
			$eos_page_id = isset( $eos_page_id ) && absint( $eos_page_id ) !== 0 ? $eos_page_id : false;
			if( eos_dp_is_mobile() ){
				$mobile_page_id = absint( get_post_meta( $eos_page_id,'eos_scfm_mobile_post_id',true ) );
				if( $mobile_page_id > 0 ){
					$eos_page_id = $mobile_page_id;
				}
			}
			$eos_dp_paths = '';
			if( $eos_page_id || ( isset( $_REQUEST['test_id'] ) && isset( $_REQUEST['eos_dp_preview'] ) ) ){
				if( isset( $_GET['fdp_post_type'] ) ){
					$transient_name = 'fdp_test_'.sanitize_key( $_GET['fdp_post_type'] ).'_'.sanitize_text_field( $_REQUEST['test_id'] );
					$eos_dp_paths = explode( ';pn:',esc_attr( get_transient( $transient_name ) ) );
					delete_transient( $transient_name );
				}
				elseif( isset( $_GET['fdp_tax'] ) && isset( $_GET['eos_dp_preview'] ) ){
					$transient_name = 'fdp_test_'.sanitize_key( $_GET['fdp_tax'] ).'_'.sanitize_text_field( $_REQUEST['test_id'] );
					$eos_dp_paths = explode( ';pn:',esc_attr( get_transient( $transient_name ) ) );
					delete_transient( $transient_name );
				}
				elseif( isset( $_REQUEST['fdp_post_id'] ) && isset( $_REQUEST['eos_dp_preview'] ) ){
					$eos_page_id = absint( $_REQUEST['fdp_post_id'] );
					$cron = isset( $_REQUEST['internal_call'] ) && 'true' === $_REQUEST['internal_call'] ? 'cron_' : '';
					$after_save = isset( $_REQUEST['after_save'] ) && 'true' === $_REQUEST['after_save'] ? 'after_save_' : '';
					$tool = isset( $_REQUEST['tool'] ) && in_array( $_REQUEST['tool'],array( 'gtmetrix','gpsi' ) ) ? sanitize_key( $_REQUEST['tool'] ) : '';
					$transient_name = 'fdp_test_'.$after_save.$cron.$tool.sanitize_key( $_REQUEST['fdp_post_id'].'_'.sanitize_text_field( $_REQUEST['test_id'] ) );
					$eos_dp_paths = explode( ';pn:',esc_attr( get_transient( $transient_name ) ) );
					if( isset( $_SERVER['HTTP_REFERER'] ) && 0 === strpos( esc_url( sanitize_text_field( $_SERVER['HTTP_REFERER'] ) ),get_home_url() ) ){
						if( !isset( $_REQUEST['is_home'] ) || '1' !== sanitize_text_field( $_REQUEST['is_home'] ) && '' === $tool ){
							delete_transient( $transient_name );
						}
					}
				}
				elseif( isset( $_REQUEST['test_id'] ) && isset( $_REQUEST['eos_dp_preview'] ) && isset( $_REQUEST['tool'] ) ){
					$transient_name = 'fdp_test_'.sanitize_key( $_REQUEST['tool'] ).'_'.sanitize_text_field( $_REQUEST['test_id'] );
					$eos_dp_paths = explode( ';pn:',esc_attr( get_transient( $transient_name ) ) );
					delete_transient( $transient_name );
				}
				else{
					if( $opts_by_path && isset( $opts_by_path['post_type'] ) ){
						$post_type = isset( $opts_by_path['post_type'] ) && '' !== sanitize_text_field( $opts_by_path['post_type'] ) ? sanitize_text_field( $opts_by_path['post_type'] ) : 'page';
						$post_types_matrix_pt = $post_types_matrix[sanitize_key( $post_type )];
					}
					elseif( $post_types_matrix ){
						$post_type = isset( $p ) && $p && is_object( $p ) && isset( $p->post_type ) ? $p->post_type : false;
						$post_types_matrix_pt = $post_type && isset( $post_types_matrix[$post_type] ) ? $post_types_matrix[$post_type] : 0;
					}
					$fdp_key_suffix = isset( $p ) &&  $p && is_object( $p ) && isset( $p->post_status ) && in_array( $p->post_status, array( 'draft' ) ) && ! eos_dp_is_user_logged() ? '_draft' : '';
					$fdp_post_meta = $opts_by_path ? $opts_by_path['plugins'] : get_post_meta( $eos_page_id,'_eos_deactive_plugins_key' . sanitize_key( $fdp_key_suffix ),true );
					if( isset( $post_types_matrix_pt ) && isset( $post_types_matrix_pt[0] ) && '0' == $post_types_matrix_pt[0] ){
						$eos_dp_paths = explode( ',',$fdp_post_meta );

					}
					else{
						if( ( $opts_by_path || is_object( $p ) ) && $post_types_matrix && isset( $post_types_matrix[$post_type] ) ){
							if( isset( $post_types_matrix_pt[3] ) ){
								$ids = $post_types_matrix_pt[3];
								if( in_array( $eos_page_id,$ids ) ){
									$eos_dp_paths = explode( ',',$fdp_post_meta );
									$info[] = sprintf( 'Plugins disabled according to Singles Settings. ID: %s.',$eos_page_id );
								}
								elseif( isset( $post_types_matrix_pt[1] ) ){
									$eos_dp_paths = explode( ',',$post_types_matrix_pt[1] );
									if( !in_array( sprintf( 'Evaluated Singles Settings. ID: %s. Post type: %s',$eos_page_id,$post_type ),$info ) ){
										$info[] = sprintf( 'Evaluated Singles Settings. ID: %s. Post type: %s',$eos_page_id,$post_type );
									}
									$info[] = sprintf( 'Plugins disabled according to the Post Types settings. Post type: %s.',$post_type );
								}
							}
							elseif( isset( $post_types_matrix_pt[1] ) ){
								$eos_dp_paths = explode( ',',$post_types_matrix_pt[1] );
								$info[] = sprintf( 'Settings overridden by the Post Types Settings. Post type: %s.',$post_type );
							}
						}
					}
				}
				if( $from_url_filter && !empty( $from_url_filter ) ){
					$from_url_filterA = explode( ',',$from_url_filter );
					if( !empty( $from_url_filterA ) ){
						$from_url_filterA = array_values( array_filter( $from_url_filterA ) );
						if( !is_array( $eos_dp_paths ) || empty( $eos_dp_paths ) ) $eos_dp_paths = array();
						$eos_dp_paths = array_values( array_filter( array_merge( $eos_dp_paths,$from_url_filterA ) ) );
					}
				}
				global $eos_page_id,$eos_dp_paths;
			}
			else{
				// It's an archive page.
				$archive_found = false;
				if( $opts_by_path && isset( $opts_by_path['post_id'] ) && 'archive' === $opts_by_path['post_id'] ){
					$eos_dp_paths = explode( ',',esc_attr( $opts_by_path['plugins'] ) );
					if( !empty( array_filter( $eos_dp_paths ) ) ){
						$archive_found = true;
						$info[] = sprintf( 'Plugins disabled according to the archive settings, archive: %s',$opts_by_path['post_type'] );
					}
				}
				else{
					if( !defined( 'FDP_SKIP_DB_FOR_ARCHIVES' ) || !FDP_SKIP_DB_FOR_ARCHIVES ){
						$archives = eos_dp_get_option( 'eos_dp_archives' );
						if( $archives && is_array( $archives ) ){
							$clean_uri = str_replace( '/','__',rtrim( $clean_uri,'/' ) );
							$key = sanitize_key( $clean_uri );
							if( isset( $archives[$key] ) ){
								$eos_dp_paths = explode( ',',$archives[$key] );
								if( !empty( array_filter( $eos_dp_paths ) ) ){
									$archive_found = true;
									$info[] = 'Plugins disabled according to the archive settings';
								}
							}
						}
					}
				}
				if( !$archive_found && $clean_uri ){
					$cuA = explode( '__',str_replace( '/','__',rtrim( $clean_uri,'/' ) ) );
					$home_url_A = explode( '://', get_home_url() );
					if( isset( $home_url_A[1] ) && $cuA && isset( $cuA[1] ) ){
						$home_url_A = explode( '/', $home_url_A[1] );
						$key = 'all_archives_' . sanitize_key( $cuA[count( $home_url_A )] );
						if( !isset( $archives ) ){
							$archives = eos_dp_get_option( 'eos_dp_archives' );
						}
						if( isset( $archives[$key] ) ){
							$eos_dp_paths = explode( ',',$archives[$key] );
							$archive_found = true;
							$info[] = sprintf( 'Plugins disabled according to the archive settings, all %s archives',esc_attr( $cuA[1] ) );
						}
						elseif( isset( $archives[str_replace( 'all_archives_', 'all_archives___', $key )] ) ){
							$eos_dp_paths = explode( ',',$archives[str_replace( 'all_archives_', 'all_archives___', $key )] );
							$archive_found = true;
							$info[] = sprintf( 'Plugins disabled according to the archive settings, all %s archives',esc_attr( $cuA[1] ) );
						}
					}
				}
			}
			if( !is_array( $eos_dp_paths ) && '' === $eos_dp_paths && isset( $after_home_uri ) ){
				// Let's check if it's a translated post.
				$eos_page_id = eos_dp_translated_id( $uri,$after_home_uri,$urlsA,$post_types );
				if( $eos_page_id ){
					$pt = get_post_type( $eos_page_id );
					$post_types_matrix_pt = $pt && isset( $post_types_matrix[$pt] ) ? $post_types_matrix[$pt] : 0;
					if( isset( $post_types_matrix_pt[3] ) ){
						$ids = $post_types_matrix_pt[3];
						if( in_array( $eos_page_id,$ids ) ){
							$fdp_post_meta = get_post_meta( $eos_page_id,'_eos_deactive_plugins_key',true );
							$eos_dp_paths = explode( ',',$fdp_post_meta );
							$info[] = sprintf( 'Plugins disabled according to Singles Settings. ID: %s.',$eos_page_id );
						}
						else{
							$eos_dp_paths = explode( ',',$post_types_matrix_pt[1] );
							$info[] = sprintf( 'Plugins disabled according to the Post Types settings. Post type: %s.',$pt );
						}
					}
					else{
						$eos_dp_paths = explode( ',',$post_types_matrix_pt[1] );
						$info[] = sprintf( 'Settings overridden by the Post Types Settings. Post type: %s.',$pt );
					}
					global $eos_page_id;
				}
			}
		}
		$plugins_iframes = array(
			'give-embed' => 'give/give.php',
			'elementor-preview' => 'elementor/elementor.php'
		);
		foreach( $plugins_iframes as $iframe_arg => $plugin_iframe ){
			if( isset( $_GET[$iframe_arg] ) && isset( $eos_dp_paths ) && is_array( $eos_dp_paths ) && in_array( $plugin_iframe,$eos_dp_paths ) ){
				unset( $eos_dp_paths[array_search( $plugin_iframe,$eos_dp_paths )] );
			}
		}
		global $eos_dp_paths;
		
		if( !defined( 'EOS_DEACTIVE_PLUGINS' ) ) define( 'EOS_DEACTIVE_PLUGINS',true );
		add_action( 'muplugins_loaded',function() {
			eos_dp_filter_active_plugins(  'eos_option_active_plugins',0 );
		} );
		if( !$from_url || $from_url_filter ){
			add_action( 'muplugins_loaded',function() {
				eos_dp_filter_active_plugins(  'eos_dp_front_untouchables',9999999 );
			} );
		}
	}
	if( isset( $info ) && isset( $eos_dp_debug ) ) $eos_dp_debug['info'] = $info;
}
/**
 * Filter Plugin By URL.
 *
 * @param array $plugins
 * @since 2.1.5
 *
 */
function eos_dp_one_place( $plugins, $option_key = 'eos_dp_one_place', $keep_if_matched = true, $update_info = true ){
	if( isset( $GLOBALS['fdp_uri'] ) ) {
		$one_place_plugins = eos_dp_get_option( $option_key );
		if( $one_place_plugins ) {
			$one_place_plugins = json_decode( stripslashes( str_replace( '[home]', get_home_url(), sanitize_text_field( $one_place_plugins ) ) ), true );
		}
		if( $one_place_plugins && is_array( $one_place_plugins ) && ! empty( $one_place_plugins ) ) {
			global $eos_dp_debug, $eos_dp_paths;
			$eos_dp_paths = $eos_dp_paths ? $eos_dp_paths : array();
			$info = isset( $eos_dp_debug['info'] ) ? $eos_dp_debug['info'] : array();
			foreach( $one_place_plugins as $p => $urls ) {
				$remove = $keep_if_matched;
				$urls = array_filter( $urls );
				if( ! empty( $urls ) ) {
					foreach( $urls as $url ) {
						if( ! empty( $url ) ) {
							if( eos_dp_is_url_matched( $url, sanitize_text_field( $GLOBALS['fdp_uri'] ) ) ) {
								$remove = ! $keep_if_matched;
								break;
							}
						}
					}
					if( $remove && in_array( $p, $plugins ) ) {
						unset( $plugins[ array_search( $p, $plugins ) ] );
						$eos_dp_paths[] = $p;
						if( $update_info ) {
							$info[] = sprintf( '%s disabled bacause of the Plugin By URL settings', esc_attr( eos_dp_get_plugin_name_by_slug( $p ) ) );
						}
					}					
				}
			}
			$eos_dp_debug['info'] = $info;
		}
	}
	return $plugins;
}
/**
 * Deactivate Plugin By User Agent.
 *
 * @param array $plugins
 * @since 2.1.7
 *
 */
function eos_dp_browser( $plugins ){
	if( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$browser_plugins = eos_dp_get_option( 'eos_dp_browser' );
		if( $browser_plugins ) {
			$browser_plugins = json_decode( stripslashes( sanitize_text_field( $browser_plugins ) ), true );
		}
		if( $browser_plugins && is_array( $browser_plugins ) && ! empty( $browser_plugins ) ) {
			global $eos_dp_debug, $eos_dp_paths;
			$eos_dp_paths = $eos_dp_paths ? $eos_dp_paths : array();
			$info = isset( $eos_dp_debug['info'] ) ? $eos_dp_debug['info'] : array();
			foreach( $browser_plugins as $p => $user_agents ) {
				$remove = false;
				foreach( $user_agents as $user_agent ) {
					if( ! empty( $user_agent ) && false !== strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ), strtolower( $user_agent ) ) ) {
						$remove = true;
						break;
					}
				}
				if( $remove && in_array( $p, $plugins ) ) {
					unset( $plugins[ array_search( $p, $plugins ) ] );
					$eos_dp_paths[] = $p;
					$info[] = sprintf( '%s disabled bacause of the User Agent settings', esc_attr( eos_dp_get_plugin_name_by_slug( $p ) ) );
				}
			}
			$eos_dp_debug['info'] = $info;
		}
	}
	return $plugins;
}

/**
 * Prevent untouchable plugins to be disabled on frontend.
 *
 * @since 1.9.0
 *
 */
function eos_dp_front_untouchables( $plugins ){
	return eos_dp_untouchables( $plugins,'eos_dp_frontend_unthouchable' );
}

/**
 * Prevent untouchable plugins to be disabled on backend.
 *
 * @since 1.9.0
 *
 */
function eos_dp_back_untouchables( $plugins ){
	return eos_dp_untouchables( $plugins,'eos_dp_backend_unthouchable' );
}

/**
 * Callback for frotnend and backend untouchable plugins.
 *
 * @param array $plugins
 * @param string $option
 * 
 * @since 1.9.0
 *
 */
function eos_dp_untouchables( $plugins,$option ){
	$untouchables = eos_dp_get_option( $option );
	if( $untouchables && is_array( $untouchables ) && !empty( $untouchables ) ){
		global $eos_dp_paths;
		if( $eos_dp_paths && is_array( $eos_dp_paths ) ){
			foreach( $untouchables as $untouchable ){
				if( in_array( $untouchable,$eos_dp_paths ) ){
					unset( $eos_dp_paths[array_search( $untouchable,$eos_dp_paths )] );
				}
			}
		}
		$plugins = array_unique( array_values( array_merge( $plugins,$untouchables ) ) );
	}
	return $plugins;
}

/**
 * Disable pugins during cron jobs.
 *
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_cron_active_plugins( $plugins ){
	$disabled = eos_dp_get_option( 'eos_dp_cron' );
	if( $disabled && is_array( $disabled ) && !empty( $disabled ) ){
		return eos_dp_filter_paths( $disabled,$plugins );
	}
	return $plugins;
}
/**
 * Disable specific plugins when Code Profiler is running.
 *
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_code_profiler( $plugins ){
	$opts = eos_dp_get_option( 'fdp_code_profiler' );
	$disabled = isset( $opts['plugins'] ) && is_array( $opts['plugins'] ) ? $opts['plugins'] : array();
	if( !empty( $disabled ) ){
		return eos_dp_filter_paths( $disabled,$plugins );
	}
	return $plugins;
}

if( is_admin()
	&& isset( $_SERVER['HTTP_HOST'] )
	&& isset( $_SERVER['REQUEST_URI'] )
	&& ( empty( $_POST ) || ( defined( 'FDP_ALLOW_POST' ) && FDP_ALLOW_POST ) )
	&& !defined( 'DOING_AJAX' )
){
	add_action( 'wp_loaded',function(){
		/**
		 * Assign the initialization time to the global variable $eos_dp_wp_loaded.
		 * 
		 * @since 1.9.0
		 *
		 */	
		$GLOBALS['eos_dp_wp_loaded'] = round( microtime(true) - sanitize_text_field( $_SERVER['REQUEST_TIME_FLOAT'] ),2 );
	} );
	add_action( 'muplugins_loaded',function() {
		/**
		 * Add filter to disable specific plugins on backend pages.
		 *
		 * @param array $plugins
		 * 
		 * @since 1.9.0
		 *
		 */
		eos_dp_filter_active_plugins( 'eos_dp_admin_option_active_plugins',0,1 );
	} );
	$adminTheme = eos_dp_get_option( 'eos_dp_admin_theme' );
	$adminThemeUrl = eos_dp_get_option( 'eos_dp_admin_url_theme' );
	$base_url = basename( sanitize_text_field( $_SERVER['HTTP_HOST'] ).sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
	if( false !== strpos( $base_url,'post.php?post=' ) && false !== strpos( $base_url,'&action=edit' ) ){
		$base_urlA = explode( 'post.php?post=',$base_url );
		if( isset( $base_urlA[1] ) ){
			$base_urlA = explode( '&',$base_urlA[1] );
			$base_url = 'single_'.get_post_type( $base_urlA[0] );
		}
	}
	if( isset( $adminTheme[$base_url] ) && !$adminTheme[$base_url] ){
		add_action( 'plugins_loaded','eos_dp_replace_theme',99 );
	}
}

/**
 * Disable specific plugins on backend pages.
 *
 * @param array $plugins
 * 
 * @since 1.0.0
 *
 */
function eos_dp_admin_option_active_plugins( $plugins ){
	if( defined( 'FDP_EMERGENCY_LOG_ADMIN' ) && ( ( isset( $_GET[FDP_EMERGENCY_LOG_ADMIN] ) && 'false' !== $_GET[FDP_EMERGENCY_LOG_ADMIN] ) || ( isset( $_GET['debug'] ) && FDP_EMERGENCY_LOG_ADMIN === $_GET['debug'] ) ) ){
		if( file_exists( WP_PLUGIN_DIR.'/freesoul-deactivate-plugins/admin/eos-dp-emergency-mode.php' ) ){
			require WP_PLUGIN_DIR.'/freesoul-deactivate-plugins/admin/eos-dp-emergency-mode.php';
		}
	}
	if( defined( 'FDP_EMERGENCY_LOG_ADMIN' ) && isset( $_GET['page'] ) && 'eos_dp_admin' === $_GET['page'] && isset( $_GET['emergency'] ) && 'false' !== $_GET['emergency'] ){
		return 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php';
	}
	if( defined( 'FDP_EMERGENCY_ADMIN_PLUGINS' ) && is_array( FDP_EMERGENCY_ADMIN_PLUGINS ) ) return FDP_EMERGENCY_ADMIN_PLUGINS;
	if( !is_array( $plugins ) ){
		return $plugins;
	}
	if( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'],array( 'autoupdater_api' ) ) ){
		return $plugins;
	}
	$plugins = apply_filters( 'fdp_backend_plugins',$plugins );
	$plugins = apply_filters( 'fdp_active_by_addon',$plugins );
	$disabled_everywhere = eos_dp_get_option( 'eos_dp_backend_everywhere' );
	if( $disabled_everywhere && is_array( $disabled_everywhere ) ){
		foreach( $disabled_everywhere as $de_p ){
			if( is_array( $plugins ) && in_array( $de_p,$plugins ) ){
				unset( $plugins[array_search( $de_p,$plugins )] );
			}
		}
	}
	if( isset( $_GET['page'] ) && false !== strpos( sanitize_text_field( $_GET['page'] ),'eos_dp_' ) ){
		$disable_on_fdp_pageA = array(
			'query-monitor/query-monitor.php',
			'uipress/uipress.php',
			'buddypress/bp-loader.php'
		);
		$fatal_error_handler = get_site_transient( 'fdp_plugin_disabledd_fatal_error' );
		if( $fatal_error_handler && isset( $fatal_error_handler['plugin'] ) && in_array( $fatal_error_handler['plugin'],$GLOBALS['fdp_all_plugins'] ) && 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php' !== $fatal_error_handler['plugin'] ){
			$disable_on_fdp_pageA[] = sanitize_text_field( $fatal_error_handler['plugin'] );
			add_action( 'fdp_top_bar_notifications','eos_dp_fatal_error_notice' );
			add_action( 'fdp_after_general_inline_style',function(){
				$fatal_error_handler = get_site_transient( 'fdp_plugin_disabledd_fatal_error' );
				echo '.eos-dp-plugin-name[data-path="'.esc_attr( $fatal_error_handler['plugin'] ).'"] a{color:red !important}';
			} );
		}
		else{
			delete_site_transient( 'fdp_plugin_disabledd_fatal_error' );
		}
		foreach(  $disable_on_fdp_pageA as $disable_on_fdp_page ){
			if( in_array( $disable_on_fdp_page,$plugins ) ){
				unset( $plugins[array_search( $disable_on_fdp_page,$plugins )] );
			}
		}
		if( defined( 'FDP_DISABLE_IN_FDP_PAGE' ) ){
			if( is_string( FDP_DISABLE_IN_FDP_PAGE ) && is_array( $plugins ) && in_array( FDP_DISABLE_IN_FDP_PAGE,$plugins ) ){
				unset( $plugins[array_search( FDP_DISABLE_IN_FDP_PAGE,$plugins )] );
			}
			elseif( is_array( FDP_DISABLE_IN_FDP_PAGE ) ){
				foreach( FDP_DISABLE_IN_FDP_PAGE as $disable_on_fdp_page ){
					if( is_string( $disable_on_fdp_page ) && in_array( $disable_on_fdp_page,$plugins ) ){
						unset( $plugins[array_search( $disable_on_fdp_page,$plugins )] );
					}
				}
			}
		}
		if( is_array( $plugins ) && in_array( 'enable-jquery-migrate-helper/enable-jquery-migrate-helper.php',$plugins ) ){
			unset( $plugins[array_search( 'enable-jquery-migrate-helper/enable-jquery-migrate-helper.php',$plugins )] );
		}
		return $plugins;
	}
	$plugins = eos_dp_unshift_fdp( $plugins );
	$all_plugins = $GLOBALS['fdp_all_plugins'];
	foreach( $plugins as $p => $const ){
		if( !defined( 'EOS_ADMIN_'.str_replace( '-','_',strtoupper( dirname( $const ) ) ).'_ACTIVE' ) ) define( 'EOS_ADMIN_'.str_replace( '-','_',strtoupper( dirname( $const ) ) ).'_ACTIVE','true' );
		$const = str_replace( '-','_',strtoupper( str_replace( '.php','',basename( $const ) ) ) );
		if( !defined( 'EOS_ADMIN_'.$const.'_ACTIVE' ) ) define( 'EOS_ADMIN_'.$const.'_ACTIVE','true' );
	}
	if( isset( $_REQUEST['eos_dp_preview'] ) && isset( $_REQUEST['admin_page_key'] ) ){
		add_action( 'wp_loaded',function(){
			$GLOBALS['eos_dp_wp_loaded'] = round( microtime(true) - sanitize_text_field( $_SERVER['REQUEST_TIME_FLOAT'] ),2 );
		} );
		$GLOBALS['eos_dp_all_plugins'] = array_filter( $all_plugins );
		register_shutdown_function( 'eos_dp_display_usage' );
		if( isset( $_REQUEST['eos_dp_debug'] ) ){
			if( 'no_error' === $_REQUEST['eos_dp_debug'] ){
				@ini_set( 'display_error',0 );
			}
		}
		$transient_name = 'fdp_test_'.sanitize_key( $_REQUEST['admin_page_key'] ).'_'.esc_attr( sanitize_text_field( $_REQUEST['test_id'] ) );
		$disabled_plugins = explode( ';pn:',esc_attr( get_transient( $transient_name ) ) );
		delete_transient( $transient_name );
		foreach( $disabled_plugins as $path ){
			$k = array_search( $path, $plugins );
			if( false !== $k ){
				$const = str_replace( '-','_',strtoupper( dirname( $path ) ) );
				if( !defined( 'EOS_'.$const.'_ACTIVE' ) ) define( 'EOS_'.$const.'_ACTIVE','true' );
				$const = str_replace( '-','_',strtoupper( str_replace( '.php','',basename( $path ) ) ) );
				if( !defined( 'EOS_'.$const.'_ACTIVE' ) ) define( 'EOS_'.$const.'_ACTIVE','true' );
				unset( $plugins[$k] );
			}
		}
		$last = $disabled_plugins[absint( count( $disabled_plugins ) - 1 )];
		if( 'undefined' === $last ){
			unset( $disabled_plugins[absint( count( $disabled_plugins ) - 1 )] );
		}
		if( isset( $_REQUEST['theme'] ) && 'false' === $_REQUEST['theme'] ){
			eos_dp_replace_theme();
		}
		if( !isset( $GLOBALS['eos_dp_paths'] ) ){
			$GLOBALS['eos_dp_paths'] = array_filter( $disabled_plugins );
		}
		$untouchables = array(
			'fdp_query_menu' => 'plugversions/plugversions.php'
		);
		foreach( $untouchables as $untouchable_arg => $plugin_untouchable ){
			if( isset( $_GET[$untouchable_arg] ) && in_array( $plugin_untouchable,$eos_dp_paths ) ){
				unset( $eos_dp_paths[array_search( $plugin_untouchable,$eos_dp_paths )] );
			}
		}
		return $plugins;
	}
	$base_url = basename( sanitize_text_field( $_SERVER['HTTP_HOST'] ).sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
	if( 'wp-admin' === str_replace( '/','', sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ){
		$base_url = str_replace( '//','/',basename( sanitize_text_field(  $_SERVER['HTTP_HOST'] ).sanitize_text_field( $_SERVER['REQUEST_URI'] ).'/index.php' ) );
	}
	$admin_page = false !== strpos( $base_url,'.php' ) ?  admin_url( $base_url ) : admin_url( 'admin.php'.$base_url );
	$from_admin_url = false;
	$urlsA = eos_dp_get_option( 'eos_dp_by_admin_url' );
	$admin_plugins = array();
	if( is_array( $urlsA ) && !empty( is_array( $urlsA ) ) ){
		foreach( $urlsA as $urlA ){
			if( isset( $urlA['url'] ) ){
				foreach( array( 'https://','http://','www.' ) as $search ){
					$urlA['url'] = str_replace( $search,'',$urlA['url'] );
				}

				$pattern = '/'.str_replace( '/','\/',str_replace( '*','(.*)',str_replace( '**','*',$urlA['url'] ) ) ).'\s/i';
				$pattern = str_replace( '?','\?',$pattern );
				$pattern = str_replace( '&','\&',$pattern );
				preg_match( $pattern,$admin_page.' ',$matches );
				if( !empty( $matches ) && count( $matches ) - 1 === substr_count( $pattern,'(.*)' ) ){
					$admin_plugins[$admin_page] = $urlA['plugins'];
					$from_admin_url = true;
					break;
				}
			}
		}
	}
	if( !$from_admin_url ){
		$urlArr =  parse_url( $base_url );
		if( isset( $urlArr['path'] ) && 'edit.php' === sanitize_text_field( $urlArr['path'] ) && isset( $urlArr['query'] ) ){
			if( isset(  $urlArr['query'] ) && 'post_type=post' === $urlArr['query'] ){
				 $base_url = remove_query_arg( 'post_type',$base_url );
			}
			if( isset( $_GET['s'] ) && isset( $_GET['post_status'] ) && isset( $_GET['post_type'] ) ){
				$base_url = 'post' === $_GET['post_type'] ? $urlArr['path'] : $urlArr['path'].'?post_type='.sanitize_text_field( $_GET['post_type'] );
			}
		}
		$admin_plugins = eos_dp_get_option( 'eos_dp_admin_setts' );
		if( false !== strpos( $base_url,'post.php' ) && isset( $_GET['post'] ) && absint( $_GET['post'] ) > 0 && isset( $_GET['action'] ) && 'edit' === $_GET['action'] ){
			$post_type = get_post_type( absint( $_GET['post'] ) );
			if( $post_type ){
				$admin_page = sanitize_key( 'single_'.$post_type );
			}
		}
		add_action( 'muplugins_loaded',function() {
			eos_dp_filter_active_plugins( 'eos_dp_back_untouchables',9999999,1 );
		} );
	}
	if( isset( $_GET['page'] ) || isset( $admin_plugins[$admin_page] ) || isset( $admin_plugins[$base_url] ) ){
		if( isset( $_GET['page'] ) && isset( $admin_plugins[ sanitize_text_field( $_GET['page'] ) ] ) ){
			$key = $admin_plugins[sanitize_text_field( $_GET['page'] )];
		}
		else{
			if( !isset( $admin_plugins[$admin_page] ) && !isset( $admin_plugins[$base_url] ) ){
				return $plugins;
			}
			$key = isset( $admin_plugins[$admin_page] ) ? $admin_plugins[$admin_page] : $admin_plugins[$base_url];
		}
		$disabled_plugins = explode( ',',$key );
		if( in_array( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',$disabled_plugins ) ){
			unset( $disabled_plugins[array_search( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',$disabled_plugins )] );
		}
		foreach( $disabled_plugins as $path ){
			$k = array_search( $path, $plugins );
			if( false !== $k ){
				if( !defined( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $path ) ) ).'_ACTIVE' ) ) define( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $path ) ) ).'_ACTIVE','true' );
				$const = str_replace( '-','_',strtoupper( str_replace( '.php','',basename( $path ) ) ) );
				if( !defined( 'EOS_'.$const.'_ACTIVE' ) ) define( 'EOS_'.$const.'_ACTIVE','true' );
				unset( $plugins[$k] );
			}
		}
	}
	$GLOBALS['eos_dp_paths'] = array_diff( $all_plugins,$plugins );
	add_action( 'admin_footer','eos_dp_print_disabled_plugins',9999 );
	return $plugins;
}


/**
 * Return active plugins in according with the options for the frontend.
 *
 * @param array $plugins
 * 
 * @since 1.0.0
 *
 */
function eos_option_active_plugins( $plugins ){
	if( isset( $_REQUEST['CODE_PROFILER_ON'] ) || isset( $_REQUEST['CODE_PROFILER_PRO_ON'] ) ){
		$opts = eos_dp_get_option( 'fdp_code_profiler' );
		if( isset( $opts['fdp_cp'] ) && 'cp' === $opts['fdp_cp'] ){
			return $plugins;
		}
	}
	if( isset( $_REQUEST['fdp-assets'] ) ){
		return $plugins;
	}

	if( ! is_array( $plugins ) || is_admin() || wp_doing_ajax() || isset( $_REQUEST['wc-ajax'] ) || class_exists( 'FS_Plugin_Updater' ) ) return $plugins;

	$plugins = apply_filters( 'fdp_frontend_plugins',eos_dp_unshift_fdp( $plugins ) );
	$plugins = apply_filters( 'fdp_active_by_addon',eos_dp_unshift_fdp( $plugins ) );

	if( defined( 'FDP_PRO_IS_ACTIVE' ) && FDP_PRO_IS_ACTIVE ){
		// Run only if FDP PRO is active.
		$disabled_on_frontend = eos_dp_get_option( 'eos_dp_frontend_everywhere' );
		if( $disabled_on_frontend && is_array( $disabled_on_frontend ) ){
			foreach( $disabled_on_frontend as $dof_p ){
				if( is_array( $plugins ) && in_array( $dof_p,$plugins ) ){
					unset( $plugins[array_search( $dof_p,$plugins )] );
					$frontend_everywhere_info = PHP_EOL.ucwords( eos_dp_get_plugin_name_by_slug( $dof_p ) ).' disabled cause the Frontend Everywhere settings';
					if( !in_array( $frontend_everywhere_info, $GLOBALS['eos_dp_debug']['info'] ) ){
						$GLOBALS['eos_dp_debug']['info'][] = $frontend_everywhere_info;
					}
				}
			}
		}
		if( ! eos_dp_is_user_logged() ){
			$disabled_by_unlogged = eos_dp_get_option( 'eos_dp_unlogged' );
			if( $disabled_by_unlogged && !empty( $disabled_by_unlogged ) && is_array( $disabled_by_unlogged ) ){
				$info_unlogged= '';
				foreach( $disabled_by_unlogged as $dbup ){
					if( in_array( $dbup,$plugins ) ){
						unset( $plugins[array_search( $dbup,$plugins )] );
						$info_unlogged.= PHP_EOL.ucwords( eos_dp_get_plugin_name_by_slug( $dbup ) ).' disabled cause the unlogged settings';
					}
				}
				if( isset( $GLOBALS['eos_dp_paths'] ) && is_array( $GLOBALS['eos_dp_paths'] ) && is_array( $disabled_by_unlogged) ){
					$GLOBALS['eos_dp_paths'] = array_unique( array_merge( $GLOBALS['eos_dp_paths'],$disabled_by_unlogged) );
				}
				$info[] = $info_unlogged;
			}
		}
	}
	if( isset( $_REQUEST['eos_dp_preview'] ) ){
		$GLOBALS['eos_dp_all_plugins'] = $plugins;
		if( isset( $_REQUEST['eos_dp_debug'] ) ){
			if( 'no_error' === $_REQUEST['eos_dp_debug'] ){
				@ini_set( 'display_error',0 );
				@ini_set( 'log_errors',0 );
			}
		}
		add_action( 'plugins_loaded','eos_check_dp_preview_nonce' );
		if( isset( $_REQUEST['eos_dp_display_error'] ) && 'display_error' === $_REQUEST['eos_dp_display_error'] ){
			@ini_set( 'display_error',1 );
		}
		add_action( 'wp_loaded',function(){
			$GLOBALS['eos_dp_wp_loaded'] = round( microtime(true) - sanitize_text_field( $_SERVER['REQUEST_TIME_FLOAT'] ),2 );
		} );
		register_shutdown_function( 'eos_dp_display_usage' );
		$themeA = explode( '&theme=',parse_url( urldecode( basename( sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ),PHP_URL_QUERY ) );
		if( isset( $themeA[1] ) ){
			$themeA = explode( '&',$themeA[1] );
			if( '' !== $themeA[0] ){
				if( in_array( $themeA[0],array( 'empty_theme','fdp_naked' ) ) ){
					add_action( 'plugins_loaded','eos_dp_replace_theme' );
				}
				else{
					if( 'false' !== $themeA[0] ){
						$GLOBALS['eos_dp_theme'] = $themeA[0];
						add_filter( 'stylesheet','eos_dp_get_theme' );
						add_filter( 'template','eos_dp_get_parent_theme' );
					}
				}
			}
		}
		if( isset( $_GET['js'] ) && 'off' === $_GET['js'] ){
			add_action( 'wp_head','eos_dp_disable_javascript',10 );
		}
	}
	else{
		if( defined( 'EOS_DP_DEBUG' ) && true === EOS_DP_DEBUG || ( isset( $_SERVER['REMOTE_ADDR'] ) && isset( $_GET['show_disabled_plugins'] ) && $_GET['show_disabled_plugins'] === md5( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ).( absint( time()/1000 ) ) ) ) ){
			$GLOBALS['eos_dp_user_can_preview'] = true;
			add_action( 'wp_footer','eos_dp_print_disabled_plugins',9999 );
		}
		if( isset( $_REQUEST['eos_dp_debug_options'] ) ){
			add_action( 'wp_footer','eos_dp_debug_options_wrapper',9999 );
		}
	}
	global $eos_dp_paths,$eos_dp_disabled_plugins;
	if( $eos_dp_paths === '' ){
		return $plugins;
	}
	global $eos_dp_debug;
	if( $eos_dp_debug && isset( $eos_dp_debug['info'] ) ){
		$qm_log = $eos_dp_paths && is_array( $eos_dp_paths ) && !empty( $eos_dp_paths ) ? 'Freesoul Deactivate Plugins'.PHP_EOL.PHP_EOL.implode( PHP_EOL,$eos_dp_debug['info'] ).PHP_EOL.PHP_EOL.PHP_EOL.'Disabled plugins:'.PHP_EOL.PHP_EOL.implode( PHP_EOL,array_unique( array_filter( $eos_dp_paths ) ) ) : 'FDP has disabled no plugins on this page';
		do_action( 'qm/debug',$qm_log );
	}
	$eos_dp_paths = $eos_dp_paths ? $eos_dp_paths : array();
	return eos_dp_filter_paths( $eos_dp_paths,$plugins );
}

/**
 * Disable by Post requests.
 *
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_mu_deactivate_by_post_requests( $plugins ){
	$opts = eos_dp_get_option( 'eos_dp_pro_post_setts' );
	if( $opts ){
		$disabled = false;
		$opts = json_decode( str_replace( '\\','',$opts ),true );
		if( is_array( $opts ) && !empty( $opts ) ){
			$o = 0;
			foreach( $opts as $url => $post_plugins ){
					$n = 0;
					$bools = array();
					$c = strpos( $url,'(' );
					if( $c ){
						$url = rtrim( substr( $url,$c + 1 ),')' );
					}
					foreach( explode( '&',$url ) as $value){
						if( false !== strpos( $value,'=') ){
							$arr2 = explode( '=',$value );
							if( isset( $arr2[1] ) && in_array( $arr2[0],array_keys( $_POST ) ) ){
								$bools[] = '*' === $arr2[1] || $arr2[1] === $_POST[$arr2[0]] ? 1 : 0;
							}
							else{
								$bools[] = 0;
							}
						}
						++$n;
					}
					if( !empty( $bools ) && count( $bools ) === array_sum( $bools ) ){
						$disabled = array_unique( array_filter( explode( ',',$post_plugins ) ) );
						$disabled = apply_filters( 'fdp_active_by_addon',$disabled );
						break;
					}
				++$o;
			}
			if( $disabled ){
				$postTheme = eos_dp_get_option( 'eos_dp_pro_post_theme' );
				if( isset( $postTheme[$url] ) && !$postTheme[$url] ){
					add_action( 'plugins_loaded','eos_dp_replace_theme',99 );
				}

				foreach( $disabled as $plugin ){

					if( in_array( $plugin,$plugins ) ){
						unset( $plugins[array_search( $plugin,$plugins )] );
					}
				}
			}
		}
	}
	return $plugins;
}
/**
 * Remove disabled plugins.
 *
 * @param array $eos_dp_paths
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_filter_paths( $eos_dp_paths,$plugins ){
	$eos_dp_paths = eos_dp_prevent_disabling_wrong_plugins( $eos_dp_paths );
	$e = 0;
	if( $eos_dp_paths && is_array( $eos_dp_paths ) && in_array( 'woocommerce/woocommerce.php',$eos_dp_paths ) ){
		if( isset( $_GET['download_link'] ) || ( isset( $_GET['order'] ) && false !== strpos( sanitize_text_field( $_GET['order'] ),'wc_order' ) ) ){
			unset( $eos_dp_paths[array_search( 'woocommerce/woocommerce.php',$eos_dp_paths )] );
		}
		foreach( $plugins as $plugin ){
			if( 'woocommerce/woocommerce.php' !== $plugin && !in_array( $plugin,$eos_dp_paths ) && ( false !== strpos( $plugin,'_woo_' ) || false !== strpos( $plugin,'-woo.' ) || false !== strpos( $plugin,'-woo-' ) || false !== strpos( $plugin,'woocommerce' ) ) ){
				$eos_dp_paths[] = $plugin;
			}
		}
		$eos_dp_paths = array_unique( $eos_dp_paths );
	}
	if( $eos_dp_paths && is_array( $eos_dp_paths ) && in_array( 'elementor/elementor.php',$eos_dp_paths ) ){
		foreach( $plugins as $plugin ){
			if( 'elementor/elementor.php' !== $plugin && !in_array( $plugin,$eos_dp_paths ) && ( false !== strpos( $plugin,'elementor' ) ) ){
				$eos_dp_paths[] = $plugin;
			}
		}
		$eos_dp_paths = array_unique( $eos_dp_paths );
	}
	foreach( $eos_dp_paths as $path ){
		$k = array_search( $path, $plugins );
		if( false !== $k ){
			if( !defined( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $path ) ) ).'_ACTIVE' ) ) define( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $path ) ) ).'_ACTIVE','true' );
			$const = str_replace( '-','_',strtoupper( str_replace( '.php','',basename( $path ) ) ) );
			if( !defined( 'EOS_'.$const.'_ACTIVE' ) ) define( 'EOS_'.$const.'_ACTIVE',true );
			unset( $plugins[$k] );
			if( in_array( $path,$plugins ) ){
				$eos_dp_disabled_plugins[] = $path;
			}
		}
		else{
			unset( $eos_dp_paths[$e] );
		}
		++$e;
	}
	if( defined( 'EOS_WOOCOMMERCE_ACTIVE' ) ){
		$plugins[] = 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php';
		$plugins = eos_dp_unshift_fdp( $plugins );
	}
	add_action( 'wp_footer','eos_dp_comment' );
	return $plugins;
}

/**
 * Replace the theme for preview.
 *
 * @param string $stylesheet
 * 
 * @since 1.9.0
 *
 */
function eos_dp_get_theme( $stylesheet ){
	if( !isset( $GLOBALS['eos_dp_theme'] ) ) return $stylesheet;
	return esc_attr( $GLOBALS['eos_dp_theme'] );
}

/**
 * Return parent theme.
 *
 * @param string $template
 * 
 * @since 1.9.0
 *
 */
function eos_dp_get_parent_theme( $template ){
	if( !isset( $GLOBALS['eos_dp_theme'] ) ) return $stylesheet;
	$themes = wp_get_themes();
	$child_theme = sanitize_key( $GLOBALS['eos_dp_theme'] );
	if( !isset( $themes[$child_theme] ) ) return $template;
	$theme = $themes[$child_theme];
	if( isset( $theme->template ) ){
		return $theme->template;
	}
	return $template;
}

/**
 * Replace the theme with an almost empty theme provided by FDP.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_replace_theme(){
	if( defined( 'EOS_DP_PLUGIN_DIR' ) ){
		if( !isset( $_REQUEST['action'] ) || 'upload-theme' !== $_REQUEST['action'] ){
			add_filter( 'stylesheet_directory','eos_dp_stylesheet_directory',999999,3 );
			add_filter( 'theme_root','eos_dp_theme_root',999999 );
			add_filter( 'stylesheet','eos_dp_template',999999 );
			add_filter( 'template','eos_dp_template',999999 );
		}
	}
}

/**
 * Return stylesheet directory of the FDP theme.
 *
 * @param string $stylesheet_dir
 * @param string $styesheet
 * @param string $theme_root
 * 
 * @since 1.9.0
 *
 */
function eos_dp_stylesheet_directory( $stylesheet_dir,$stylesheet,$theme_root ){
	return EOS_DP_PLUGIN_DIR.'/inc/fdp-theme';
}

/**
 * Return the theme root of the FDP theme.
 *
 * @param string $theme_root
 * 
 * @since 1.9.0
 *
 */
function eos_dp_theme_root( $theme_root ){
	return EOS_DP_PLUGIN_DIR.'/inc';
}

/**
 * Return the template of the FDP theme.
 *
 * @param string $template
 * 
 * @since 1.9.0
 *
 */
function eos_dp_template( $template ){
	return 'fdp-theme';
}

/**
 * Check the nonce for the preview.
 *
 * 
 * @since 1.9.0
 *
 */
function eos_check_dp_preview_nonce(){
	if( defined( 'EOS_DP_PRO_TESTING_UNIQUE_ID' ) ){
		if( isset( $_REQUEST['eos_dp_pro_id' ] ) && md5( EOS_DP_PRO_TESTING_UNIQUE_ID ) === $_REQUEST['eos_dp_pro_id'] ){
			$GLOBALS['eos_dp_user_can_preview'] = true;
			return true;
		}
	}
	if( !wp_verify_nonce( sanitize_text_field( $_REQUEST['eos_dp_preview'] ),'eos_dp_preview' ) ){
		$nonce = get_transient( 'fdp_testing_nonce_'.sanitize_key( $_REQUEST['fdp_post_id'] ) );
		if( $nonce ){
			if( 1000*absint( time()/1000 ) === absint( $nonce ) ){
				$GLOBALS['eos_dp_user_can_preview'] = true;
				return true;
			}
		}
		echo '<p>It looks you are not allowed to see this preview.</p>';
		echo '<p>Be sure you have the rights to activate and deactivate plugins.</p>';
		echo '<p>Log out, log in, and try again.</p>';
		if( isset( $_SERVER['SERVER_NAME'] ) ){
			echo '<p>Delete all the cookies written by '.esc_html( sanitize_text_field( $_SERVER['SERVER_NAME'] ) ).' and then log in again.</p>';
		}
		else{
			echo '<p>Delete all the cookies written by this domain and then log in again.</p>';
		}
		echo '<p>If deleting the cookies does not help, try with a different browser.</p>';
		echo '<p>If you still have problems, ask for help on the <a href="https://wordpress.org/support/plugin/freesoul-deactivate-plugins/">Freesoul Deactivate Plugins support forum</a>.</p>';
		exit; // Exit after warning the user.
	}
	$GLOBALS['eos_dp_user_can_preview'] = true;
}

/**
 * Display the memory usage.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_display_usage(){
	if( isset( $_REQUEST['display_usage'] ) && 'false' === $_REQUEST['display_usage'] ){
		return;
	}
	if( !isset( $GLOBALS['eos_dp_wp_loaded'] ) ){
		return;
	}
	if(  isset( $_REQUEST['tool'] ) && in_array( $_REQUEST['tool'],array( 'gtmetrix','gpsi' ) ) ){
		return;
	}
  static $foo_called = false;
  if( $foo_called ) return;
  $foo_called = true;
	global $wpdb;
	$precision = 0;
	$memory_usage = memory_get_peak_usage() / 1048576;
	if( $memory_usage < 10 ){
		$precision = 3;
	}
	else if( $memory_usage < 100 ) {
		$precision = 2;
	}
	$usage = array(
		'queries' => array( 'Number of database queries','Q',$wpdb->num_queries ),
		'wp_loaded' => array( 'Initialization Time. At this time WordPress, all plugins, and the theme are fully loaded and instantiated','IT',$GLOBALS['eos_dp_wp_loaded'].'s' ),
		'loading_time' => array( 'Page Generation Time. At this time the server has generated the entire HTML.','PGT',( strval( round(microtime(true) - sanitize_text_field( $_SERVER['REQUEST_TIME_FLOAT'] ),2 ) ) ).'s' ),
		'memory' => array( 'Memory Usage. This is the memory consumed for generating the entire HTML.','MU',sprintf( '%s %s (%s)',round( $memory_usage, $precision ),'M',round( 100*$memory_usage/absint( ini_get( 'memory_limit' ) ),1 ).'%' ) )
	);
	echo '<div id="eos-dp-usage" style="z-index:9999999999;text-align:center;position:fixed;bottom:0;left:0;right:0">';
	echo '<div style="display:inline-block;padding:10px;background-color:#e5dada;background-color:rgba(229,218,218,0.8)">';
	$n = 0;
	$separators = array( ' | ',' | ',' | ','' );
	global $eos_dp_paths,$eos_dp_all_plugins;
	$eos_dp_paths = is_array( $eos_dp_paths ) && !empty( $eos_dp_paths ) ? array_filter( $eos_dp_paths ) : array();
	$plugins_str = '[p]DISABLED PLUGINS ('.count( $eos_dp_paths ).'):[pp]';
	foreach( $eos_dp_paths as $plugin ){
		if( false === strpos( $plugin,'freesoul-deactivate-plugins' ) ){
			$plugins_str .= '[p]'.ucwords( eos_dp_get_plugin_name_by_slug( $plugin ) ).'[pp]';
		}
	}
	if( is_array( $eos_dp_all_plugins ) && array( $eos_dp_paths ) ){
		$still_enable = array_diff( $eos_dp_all_plugins,$eos_dp_paths );
		$plugins_str .= '[br][br][p]ACTIVE PLUGINS ('.count( $still_enable ).'):[pp]';
		foreach( $still_enable as $plugin ){
			if( false === strpos( $plugin,'freesoul-deactivate-plugins' ) ){
				$plugins_str .= '[p]'.ucwords( eos_dp_get_plugin_name_by_slug( $plugin ) ).'[pp]';
			}
		}
		$plugins_str .= '[br]';
	}
	$left = is_rtl() ? 'right' : 'left';
	$right = is_rtl() ? 'left' : 'right';
	echo '<span title="Disabled plugins" data-content="'.esc_attr( $plugins_str ).'" style="cursor:pointer;color:#000;font-size:20px;font-family:Arial" class="eos-dp-open-win eos-dp-disabled-plugins">Disabled Plugins: '.count( $eos_dp_paths ).'</span>';
	if( !isset( $_REQUEST['show_files'] ) || 'true' !== $_REQUEST['show_files'] ){
		echo '<span style="color:#000;font-size:20px;font-family:Arial" class="eos-dp-separator">' . esc_html( $separators[$n] ) .'</span>';
		foreach( $usage as $key => $arr ){
			$value = $arr[2];

			$desc = $arr[0];

			echo '<span title="'.esc_attr( $desc ).'" style="cursor:pointer;color:#000;font-size:20px;font-family:Arial" class="eos-dp-' . esc_attr( $key ) . '">'.esc_html( sprintf( '%s: %s',$arr[1],$value ) ).'</span>';
			echo '<span style="color:#000;font-size:20px;font-family:Arial" class="eos-dp-separator">' . esc_html( $separators[$n] ) .'</span>';
			++$n;
		}
	}
	else{
		global $template;
		$called_by_pluginsA = array();
		$template_file_name = basename( $template );
		$included_files = get_included_files();
		$plugindir = defined( WP_PLUGIN_DIR ) ? WP_PLUGIN_DIR.'/' : 'wp-content/plugins/';
		$plugins_folder_name = basename( $plugindir );
		$themedir = str_replace( ABSPATH,'',get_theme_root() );
		$wp_content_name = dirname( $plugindir );
		$template_relative_path  = str_replace( ABSPATH.$wp_content_name.'/','',$template );
		sort( $included_files );
		$theme_files = $plugin_files = '';
		$plugin_names = array();
		$n = $tn = $pn = 0;
		$called_by_plugins = '';
		foreach ( $included_files as $filename ) {
			if ( strstr( $filename,$themedir ) || strstr( $filename, str_replace( '/','\\',$themedir ) ) ){
				$filepath = strstr( $filename,$wp_content_name );
				if ( $template_relative_path !== $filepath ) {
					$theme_files .=  '[p]'.$filepath.'[pp]';
					++$tn;
				}
			}
			elseif ( strstr( $filename,$plugins_folder_name ) ) {
				$slugA = explode( $plugindir,str_replace( '\\','/',$filename ) );
				if( isset( $slugA[1] ) ){
					$slugA = explode( '/',$slugA[1] );
					$plugin_slug = $slugA[0];
					if( !in_array( $plugin_slug,array_keys( $called_by_pluginsA ) ) ){
						$plugin_files .=  '[br][br][p][b]'.strtoupper( str_replace( '-',' ',$plugin_slug ) ).'[bb][pp]';
					}
					$called_by_pluginsA[$plugin_slug] = isset( $called_by_pluginsA[$plugin_slug] ) ? $called_by_pluginsA[$plugin_slug] + 1 : 1;
				}
				$filepath = strstr( $filename,'wp-content' );
				$plugin_files .=  '[p]'.$filepath.'[pp]';
				++$pn;
			}
		}
		foreach( $called_by_pluginsA as $plugin_slug => $cbpn ){
			$called_by_plugins .= '[p]'.ucwords( str_replace( '-',' ',$plugin_slug ) ).' ('.$cbpn.')[pp]';
		}
		$n = $tn + $pn;
		$included_plugins = '[p][b]FILES CALLED BY THE THEME:[bb][pp][d]'.$theme_files.'[dd][br][br][p][b]FILES CALLED BY PLUGINS ('.( $pn - 1 ).'):[bb]'.$called_by_plugins.'[br][br][pp][d]'.$plugin_files.'[dd]';
		echo ' | <span id="eos-dp-files" title="Files called by the theme and plugins" data-title="Included files" data-content="'.esc_attr( $included_plugins ).'" style="cursor:pointer;color:#000;font-size:20px;font-family:Arial" class="eos-dp-open-win eos-dp-files">Included Files: '.esc_html( $n - 1 ).'<input class="buttton" style="margin:0 10px" type="submit" value="Show Files" /></span>';
	}
	echo '<span title="Close" style="position:relative;margin-'.esc_attr( $right ).':-8px;margin-'.esc_attr( $left ).':20px;display:inline-block;top:-8px;padding:4px 8px 8px 8px;cursor:pointer;color:#000;font-size:20px;font-family:Arial" class="eos-dp-close" onclick="javascript:this.parentNode.parentNode.style.display = \'none\'">X</span>';
	echo '</div>';
	echo '</div>';
	echo '<script>var eos_open_wins = document.getElementsByClassName("eos-dp-open-win");for(var n=0;n<eos_open_wins.length;++n){eos_open_wins[n].addEventListener("click",function(){eos_dp_open_window(this);});}';
	echo 'function eos_dp_open_window(el){var win=window.open("",el.dataset.title,"toolbar=0,location=0,menubar=0");win.document.write(el.dataset.content.split("[p]").join("<p>").split("[pp]").join("</p>").split("[d]").join("<div>").split("[dd]").join("</div>").split("[br]").join("<br/>").split("[b]").join("<strong>").split("[bb]").join("</strong>"));setTimeout(function(){win.document.title = el.dataset.title;},200);}';
	echo '</script>';
	eos_dp_print_disabled_plugins();
}

/**
 * Print usage in the JS console.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_console_usage(){
	if( defined( 'DOING_AJAX' ) && DOING_AJAX ) return;
    static $cu_called = false;
    if( $cu_called ) return;
    $cu_called = true;
	global $wpdb;
	$precision = 0;
	$memory_usage = memory_get_peak_usage() / 1048576;
	if( $memory_usage < 10 ){
		$precision = 2;
	}
	else if( $memory_usage < 100 ) {
		$precision = 1;
	}
	$usage = array(
		'queries' => sprintf( 'Queries: %s',$wpdb->num_queries ),
		'wp_loaded' => sprintf( 'Initialization Time: %s %s',$GLOBALS['eos_dp_wp_loaded'],'s' ),
		'loading_time' => sprintf( 'Page Generation Time: %s %s',strval( round(microtime(true) - sanitize_text_field( $_SERVER['REQUEST_TIME_FLOAT'] ),2 ) ),'s' ),
		'memory' => sprintf( 'Memory Usage: %s %s (%s)',round( $memory_usage, $precision ),'M',round( 100*$memory_usage/absint( ini_get( 'memory_limit' ) ),1 ).'%' )
	);
	$n = 0;
	$output = PHP_EOL.'*************************************'.PHP_EOL;
	$output .= 'Usage measured by Freesoul Deactivate Plugins'.PHP_EOL.PHP_EOL;
	foreach( $usage as $key => $value ){
		$output .= esc_html( $value ).PHP_EOL;
		++$n;
	}
	$output .= '************************************'.PHP_EOL;
	echo '<script>if("undefined" === typeof(window.eos_dp_printed))console.log("'.esc_js( $output ).'");window.eos_dp_printed = true;</script>';
}

/**
 * Print the HTML comment in the footer.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_comment(){
	static $comment = false;
	if( $comment ) return;
	global $eos_dp_paths;
	if( is_array( $eos_dp_paths ) ){
		if( !empty( $eos_dp_paths ) ){
			$comment = sprintf( 'Freesoul Deactivate Plugins has disabled %s plugins on this page.',count( array_unique( $eos_dp_paths ) ) );
		}
		else{
			$comment = 'Freesoul Deactivate Plugins has disabled no plugins on this page.';
		}
		?>
		<!-- <?php echo esc_html( $comment ); ?> -->
		<?php
	}
}

/**
 * Get options in case of single or multisite installation.
 * 
 * @param string $option
 * 
 * @since 1.9.0
 *
 */
function eos_dp_get_option( $option ){
	if( !is_multisite() ){
		return get_option( $option );
	}
	else{
		return get_blog_option( get_current_blog_id(),$option );
	}
}

/**
 * Check if it's a mobile device.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_is_mobile() {
	if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) return false;
	if ( strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ),'mobile' ) !== false
		|| strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ),'android' ) !== false
		|| strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ),'silk/' ) !== false
		|| strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ),'kindle' ) !== false
		|| strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ),'blackBerry' ) !== false
		|| strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ),'opera mini' ) !== false
		|| strpos( strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ),'opera mobi' ) !== false
	) {
		return true;
	}
	return false;
}


/**
 * Return the disabled plugins according to the mobile settings.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disabled_plugins_by_device() {
	$opts = eos_dp_is_mobile() ? eos_dp_get_option( 'eos_dp_mobile' ) : eos_dp_get_option( 'eos_dp_desktop' );
	if( $opts ){
		return array_values( $opts );
	}
	return false;
}

/**
 * Return the disabled plugins according to the search settings.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disabled_plugins_on_search() {
	$search_options = eos_dp_get_option( 'eos_dp_search' );
	if( $search_options ){
		return array_values( eos_dp_get_option( 'eos_dp_search' ) );
	}
	return false;
}


/**
 * Filter the lugisn on mobile.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disabled_plugins_by_device_filter( $plugins ) {
	if( isset( $_REQUEST['fdp-assets'] ) || isset( $_REQUEST['eos_dp_preview'] ) ){
		return $plugins;
	}
	if ( defined( 'DOING_AJAX' ) || is_admin() || class_exists( 'FS_Plugin_Updater' ) ) {
		return $plugins;
	}
	if( ( !defined( 'FDP_PRO_IS_ACTIVE' ) || false === FDP_PRO_IS_ACTIVE ) && !eos_dp_is_mobile() ){
		return $plugins;
	}
	$plugins = eos_dp_unshift_fdp( $plugins );
	global $eos_dp_debug;
	$info = isset( $eos_dp_debug['info'] ) && !empty( $eos_dp_debug['info'] ) ? $eos_dp_debug['info'] : array();

	extract( $eos_dp_debug );
	$disabled_by_device = eos_dp_disabled_plugins_by_device();
	if( $disabled_by_device ){
		$info_device = '';
		foreach( $disabled_by_device as $p => $const ){
			$info_device .= PHP_EOL.ucwords( eos_dp_get_plugin_name_by_slug( $const ) ).' disabled cause the device settings';
			if( !defined( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $const ) ) ).'_ACTIVE' ) ) define( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $const ) ) ).'_ACTIVE','true' );
			$const = str_replace( '-','_',strtoupper( str_replace( '.php','',basename( $const ) ) ) );
			if( !defined( 'EOS_'.$const.'_ACTIVE' ) ) define( 'EOS_'.$const.'_ACTIVE',true );
		}
		if( isset( $GLOBALS['eos_dp_paths'] ) && is_array( $GLOBALS['eos_dp_paths'] ) && is_array( $disabled_by_device ) ){
			$GLOBALS['eos_dp_paths'] = array_unique( array_merge( $GLOBALS['eos_dp_paths'],$disabled_by_device ) );
		}
		$info[] = $info_device;
		$eos_dp_debug['info'] = array_unique( $info );
		return array_unique( array_values( array_diff( $plugins,$disabled_by_device ) ) );
	}
	return $plugins;
}

/**
 * Filter the lugisn on search.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disabled_plugins_on_search_filter( $plugins ) {
	if( isset( $_REQUEST['fdp-assets'] ) ){
		return $plugins;
	}
	if ( !isset( $_REQUEST['s'] ) || is_admin() || class_exists( 'FS_Plugin_Updater' ) ) {
		return $plugins;
	}
	$plugins = eos_dp_unshift_fdp( $plugins );
	$disabled_on_search = eos_dp_disabled_plugins_on_search();
	if( $disabled_on_search ){
		foreach( $disabled_on_search as $p => $const ){
			if( !defined( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $const ) ) ).'_ACTIVE' ) ) define( 'EOS_'.str_replace( '-','_',strtoupper( dirname( $const ) ) ).'_ACTIVE','true' );
			$const = str_replace( '-','_',strtoupper( str_replace( '.php','',basename( $const ) ) ) );
			if( !defined( 'EOS_'.$const.'_ACTIVE' ) ) define( 'EOS_'.$const.'_ACTIVE',true );
		}
		if( isset( $GLOBALS['eos_dp_paths'] ) && is_array( $GLOBALS['eos_dp_paths'] ) && is_array( $disabled_on_search ) ){
			$GLOBALS['eos_dp_paths'] = array_unique( array_merge( $GLOBALS['eos_dp_paths'],$disabled_on_search ) );
		}
		else{
			$GLOBALS['eos_dp_paths'] = $disabled_on_search;
		}
		if( defined( 'EOS_DP_DEBUG' ) && true === EOS_DP_DEBUG || ( isset( $_SERVER['REMOTE_ADDR'] ) && isset( $_GET['show_disabled_plugins'] ) && $_GET['show_disabled_plugins'] === md5( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) ) ) ){
			$GLOBALS['eos_dp_user_can_preview'] = true;
			add_action( 'wp_footer','eos_dp_print_disabled_plugins',9999 );
		}
		return array_values( array_diff( $plugins,$disabled_on_search ) );
	}
	return $plugins;
}

if( wp_doing_ajax() || isset( $_REQUEST['wc-ajax'] ) ){
	$action = false;
	if( isset( $_REQUEST['action'] ) ){
		$action = sanitize_text_field( $_REQUEST['action'] );
	}
	elseif( isset( $_REQUEST['wc-ajax'] ) ){
		$action = sanitize_text_field( $_REQUEST['wc-ajax'] );
	}
	if( $action && false !== strpos( $action,'eos_dp_' ) ){
		add_action( 'muplugins_loaded',function() {
			/**
			 * Let FDP alone during its Ajax requests.
			 * 
			 * @since 1.9.0
			 *
			 */
			eos_dp_filter_active_plugins(  'eos_dp_only_fdp',0 );
		} );
		add_action( 'plugins_loaded','eos_dp_replace_theme',99 );
		return;
	}
	else{
		add_action( 'muplugins_loaded',function() {
			/**
			 * Add filter to disable specific plugins during Ajax requests of other plugins.
			 * 
			 * @since 1.9.0
			 *
			 */
			eos_dp_filter_active_plugins(  'eos_dp_integration_actions_plugins',0 );
		} );
		$integration_actions_theme = eos_dp_get_option( 'eos_dp_integretion_actions_theme' );
		if( isset( $integration_actions_theme[$action] ) && !$integration_actions_theme[$action] ){
			add_action( 'plugins_loaded','eos_dp_replace_theme',99 );
		}
	}

	/**
	 * Disable specific plugins during Ajax requests of other plugins.
	 * 
	 * @since 1.9.0
	 *
	 */	
	function eos_dp_integration_actions_plugins( $plugins ){
		$plugins_actions = eos_dp_get_option( 'eos_dp_integration_actions' );
		$plugins = eos_dp_unshift_fdp( $plugins );
		$action = false;
		if( isset( $_REQUEST['action'] ) ){
			$action = sanitize_text_field( $_REQUEST['action'] );
		}
		elseif( isset( $_REQUEST['wc-ajax'] ) ){
			$action = sanitize_text_field( $_REQUEST['wc-ajax'] );
		}
		if( $action && is_array( $plugins_actions ) && in_array( $action,array_keys( $plugins_actions ) ) && isset( $plugins_actions[$action] ) ){
			$disabled_plugins = array_filter( explode( ',',$plugins_actions[sanitize_key( $action )] ) );
			return array_values( array_diff( $plugins,$disabled_plugins ) );
		}
		return $plugins;
	}
}

/**
 * Exclude all other plugins during Singles options saving process.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_only_fdp( $plugins ){
	$fdp_plugins = array();
	if( isset( $GLOBALS['fdp_all_plugins'] ) && is_array( $GLOBALS['fdp_all_plugins'] ) ){
		if( in_array( 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php',$GLOBALS['fdp_all_plugins'] ) ){
			$fdp_plugins[] = 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php';
		}
		if( in_array( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',$plugins ) ){
			$fdp_plugins[] = 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php';
		}
	}
	if( wp_doing_ajax() && $plugins && is_array( $plugins ) ) {
		foreach( $plugins as $plugin ) {
			if( false !== strpos( $plugin, 'editor-cleanup-for-' ) && false !== strpos( $plugin, '/editor-cleanup-for-' ) ) {
				$fdp_plugins[] = $plugin;
			}
		}
	}	
	if( !empty( $fdp_plugins ) ){
		return $fdp_plugins;
	}
	return $plugins;
}

/**
 * Filter the disabled plugins on ajax.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disabled_plugins_on_ajax_filter( $plugins ) {
	if( ( ( wp_doing_ajax() && isset( $_REQUEST['action'] ) ) || isset( $_REQUEST['wc-ajax'] ) ) || ( ! empty( $_POST ) && ! defined( 'DOING_CRON' ) ) ){
		$plugins = apply_filters( 'fdp_ajax_plugins',$plugins );
		$disabled_on_ajax = eos_dp_disabled_plugins_on_ajax();
		if( is_array( $plugins ) && $disabled_on_ajax ){
			if( isset( $_REQUEST['action'] ) && false !== strpos( sanitize_text_field( $_REQUEST['action'] ),'eos_dp_' ) ){
				if( in_array( 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php',$disabled_on_ajax ) ){
					unset( $disabled_on_ajax[array_search( 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php',$disabled_on_ajax )] );
				}
				if( in_array( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',$disabled_on_ajax ) ){
					unset( $disabled_on_ajax[array_search( 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',$disabled_on_ajax )] );
				}
			}
			$plugins = eos_dp_unshift_fdp( $plugins );

			foreach( $disabled_on_ajax as $path ){
				$k = array_search( $path, $plugins );
				if( false !== $k ){
					unset( $plugins[$k] );
				}
			}
		}
	}
	return $plugins;
}


/**
 * Return the disabled plugins according to the ajax options.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disabled_plugins_on_ajax() {
	$ajax_options = eos_dp_get_option( 'eos_dp_pro_ajax_setts' );
	if( $ajax_options && '' !== $ajax_options ){
		$actions = json_decode( str_replace( '\\','',$ajax_options ),true );
		$action = false;
		if( isset( $_REQUEST['action'] ) ){
			$action = sanitize_text_field( $_REQUEST['action'] );
		} 
		elseif( isset( $_REQUEST['wc-ajax'] ) ) {
			$action = sanitize_text_field( $_REQUEST['wc-ajax'] );
		};
		if( $action && isset( $actions[$action] ) ){
			$disabled_plugins = array_filter( explode( ',',$actions[sanitize_text_field( $action ) ] ),'strlen' );
			return $disabled_plugins;
		}
		elseif( !empty( $_POST ) && !defined( 'DOING_CRON' ) ){
			$key = sanitize_text_field( implode( '--',array_keys( $_POST ) ) );
			if( isset( $actions[$key] ) ){
				$disabled_plugins = array_filter( explode( ',',$actions[$key] ),'strlen' );
				return array_unique( $disabled_plugins );
			}
		}
	}
	return false;
}


if ( wp_doing_ajax() || isset( $_POST['wc-ajax'] ) ){
	$action = false;
	if( isset( $_REQUEST['action'] ) ){
		$action = sanitize_text_field( $_REQUEST['action'] );
	}
	elseif( isset( $_REQUEST['wc-ajax'] ) ){
		$action = sanitize_text_field( $_REQUEST['wc-ajax'] );
	}
	$ajaxTheme = eos_dp_get_option( 'eos_dp_pro_ajax_theme' );
	if( isset( $ajaxTheme[$action] ) && !$ajaxTheme[$action] ){
		add_action( 'plugins_loaded','eos_dp_replace_theme',99 );
	}
	
}

/**
 * Filter the disabled plugins on mobile.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disabled_plugins_for_logged_users( $plugins ) {
	if( isset( $_REQUEST['fdp-assets'] ) ){
		return $plugins;
	}
	if(
		( defined( 'DOING_AJAX' ) && DOING_AJAX )
		|| ( defined( 'WP_CLI' ) && WP_CLI ) || ( defined( 'WP_SANDBOX_SCRAPING' ) && true === WP_SANDBOX_SCRAPING )
	){
		return $plugins;
	}
	if( isset( $_COOKIE['wordpress_test_cookie'] ) && false !== strpos( implode( '',array_keys( $_COOKIE ) ),'wordpress_logged_in' ) ){
		$opts = eos_dp_get_option( 'eos_dp_pro_main' );
		
		if( $opts && isset( $opts['eos_dp_logged_conditions'] ) && is_array( $opts['eos_dp_logged_conditions'] ) && !empty( $opts['eos_dp_logged_conditions'] ) ){
			$conditions_opts = $opts['eos_dp_logged_conditions'];
			$disabled_plugins_for_user = array();
			$user = eos_dp_get_current_user();
			if( $user ){
					$disabled_plugins = array();
					$conditions = eos_dp_logged_user_conditions();
					foreach( $conditions_opts as $e => $string ){
						if( '' === $e || false !== strpos( $e,'_off' ) || '' === $string ) continue;
						$arr = json_decode( str_replace( '\\','',$string ),true );
						if( !is_array( $arr ) || !isset( $arr['value'] ) || !isset( $arr['plugins'] ) ) continue;
						$expression = $arr['value'];
						if( $expression && '' !== $expression && substr_count( $expression,'(' ) === substr_count( $expression,')' ) ){
							if( eos_dp_parse_expression( $expression,$user ) ){
								$disabled_plugins = array_unique( explode( ';',str_replace( 'pn:','',$arr['plugins'] ) ) );
								$plugins = array_diff( $plugins,$disabled_plugins );
								if( isset( $conditions_opts[$e.'_off'] ) && 'true' === $conditions_opts[$e.'_off'] ){
									$disabled_plugins_for_user = array_merge( $disabled_plugins_for_user,$disabled_plugins );
								}
							}
						}
					}
					if( !is_array( $disabled_plugins ) ) $disabled_plugins = array();
					if( !isset( $GLOBALS['eos_dp_paths'] ) || !is_array( $GLOBALS['eos_dp_paths'] ) ) $GLOBALS['eos_dp_paths'] = array();
					$GLOBALS['eos_dp_paths'] = isset( $GLOBALS['eos_dp_paths'] ) ? array_unique( array_merge( $GLOBALS['eos_dp_paths'],$disabled_plugins ) ) : $disabled_plugins;
					$GLOBALS['fdp_disabled_plugins_for_user'] = $GLOBALS['eos_dp_paths'];
			}
		}
	}
	if( $plugins && is_array( $plugins ) ){
		$plugins = array_unique( $plugins );
	}
	return $plugins;
}
add_action( 'plugins_loaded','eos_dp_remove_filters',9999 );
add_action( 'activate_plugin','eos_dp_remove_filters',9999 );
add_action( 'deactivated_plugin','eos_dp_remove_filters',9999 );

add_filter( 'pre_update_site_option_active_plugins','eos_dp_return_all_plugins' );
add_filter( 'pre_update_option_active_plugins','eos_dp_return_all_plugins' );


/**
 * Prevent disabling plugins before updating the rewrite rules or the option active_plugins.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_return_all_plugins( $plugins ){
	eos_dp_remove_filters();
	if( $plugins && is_array( $plugins ) ){
		$plugins = array_unique( $plugins );
	}
	if( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'],array( 'activate','deactivate','delete','delete-plugin' ) ) && isset( $_REQUEST['plugin'] ) ){
		return $plugins;
	}
	return $GLOBALS['fdp_all_plugins'];
}

if( false !== FDP_REMOVE_FILTERS_BEFORE_FIRST_PLUGIN ) add_action( 'fdp_loaded','eos_dp_remove_filters',9999 );

/**
 * Remove the active plugins filters to avoid any issue with plugins that save the active_plugins option in the database.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_remove_filters(){
	foreach( apply_filters( 'fdp_deactivation_callbacks', 
		array(
			'eos_dp_only_fdp' => 0,
			'eos_dp_code_profiler' => 0,
			'eos_option_active_plugins' => 0,
			'eos_dp_one_place' => 0,
			'eos_dp_integration_actions_plugins' => 0,
			'eos_dp_admin_option_active_plugins' => 0,
			'eos_dp_disabled_plugins_by_device_filter' => 10,
			'eos_dp_disabled_plugins_on_search_filter' => 20,
			'eos_dp_disabled_plugins_on_ajax_filter' => 30,
			'eos_dp_disabled_plugins_for_logged_users' => 40,
			'eos_dp_mu_deactivate_by_post_requests' => 50,
			'eos_dp_front_untouchables' => 50,
			'eos_dp_back_untouchables' => 50 
		)
	) as $callback => $priority ) {
		remove_filter( 'option_active_plugins', $callback, $priority );	
	}
}

/**
 * Print the disabled plugins in the JavaScript console in case of preview and debug.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_print_disabled_plugins(){
	if( defined( 'EOS_DP_DEBUG_PLUGIN' ) && EOS_DP_DEBUG_PLUGIN && ! is_admin() ) return;
	if( is_admin() && ! current_user_can( 'activate_plugins' ) && ( ! defined( 'EOS_DP_DEBUG' ) || ! EOS_DP_DEBUG ) ) return;
	if( isset( $GLOBALS['eos_dp_paths'] ) && is_array( $GLOBALS['eos_dp_paths'] ) ){
		echo '<script>';
		echo 'if("undefined" === typeof(window.fdp_printed)){';
		echo 'console.log("*** PLUGINS DISABLED BY FREESOUL DEACTIVATE PLUGINS ***\n\r");';
		$n = 1;
		foreach( $GLOBALS['eos_dp_paths'] as $path ){
			echo '' !== $path ? 'console.log("'.esc_attr( esc_js( $n.') ' . eos_dp_get_plugin_name_by_slug( $path ) ) ) . '");' : '';
			++$n;
		}
		echo 'console.log("\n\r*************************************\n\r");';
		echo 'window.fdp_printed = true;}';
		echo '</script>';
	}
}

/**
 * Print disabled plugins in a hidden div if the page is called by the debug  button.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_debug_options_wrapper(){
	$disabled = array();
	global $eos_dp_debug;
	?>
	<div id="eos-dp-debug-options-wrapper" style="display:none;opacity:0;visibility:hidden;height:0;position:absolute;left:-99999px">
	<?php
	if( isset( $GLOBALS['eos_dp_paths'] ) && is_array( $GLOBALS['eos_dp_paths'] ) ){
		$n = 1;
		global $fdp_all_plugins;
		foreach( $GLOBALS['eos_dp_paths'] as $path ){
			if( '' !== $path && in_array( $path,$fdp_all_plugins ) ){
				$disabled[] = esc_attr( ucwords( eos_dp_get_plugin_name_by_slug( ( $path ) ) ) );
			}
			++$n;
		}
	}
	echo json_encode( array( 'disabled' => $disabled,'eos_dp_debug' => $eos_dp_debug ) );
	?>
	</div>
	<?php
}

/**
 * Send JavaScript on modern browsers with the Content Security Policy.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_disable_javascript(){
	?>
	<meta http-equiv="Content-Security-Policy" content="script-src 'none'">
	<?php
}

/**
 * Get the ID of the translated page.
 * 
 * @param string $page_path
 * @param string $after_home_uri
 * @param array $urlsA
 * @param array $post_types
 * 
 * @since 1.9.0
 *
 */
function eos_dp_translated_id( $page_path,$after_home_uri,$urlsA,$post_types ) {
	global $wpdb;
    $page_path     = rawurlencode( urldecode( $page_path ) );
    $page_path     = str_replace( '%2F', '/', $page_path );
    $page_path     = str_replace( '%20', ' ', $page_path );
    $parts         = explode( '/', trim( $page_path, '/' ) );
    $parts         = array_map( 'sanitize_title_for_query', $parts );
    $escaped_parts = esc_sql( $parts );
    $in_string = "'" . implode( "','", $escaped_parts ) . "'";
    $post_types          = esc_sql( $post_types );
    $post_type_in_string = "'" . implode( "','", $post_types ) . "'";
    $sql                 = "
        SELECT ID, post_name, post_parent, post_type
        FROM $wpdb->posts
        WHERE post_name IN ($in_string)
        AND post_type IN ($post_type_in_string)
    ";
	$unsets = array();
    $pages = $wpdb->get_results( $sql, OBJECT_K );
	foreach( (array) $pages as &$page ){
		if( $page->post_name !== basename( $page_path ) ){
			$unsets[] = $page->ID;
		}
		elseif( isset( $pages[$page->post_parent] ) ){
			$page_parent = $pages[$page->post_parent];
			$page->post_parent_name = $page_parent->post_name;
		}
	}
	foreach( $unsets as $unset ){
		if( isset( $pages[$unset] ) ){
			unset( $pages[$unset] );
		}
	}
    $revparts = array_reverse( $parts );
    $foundid = 0;
	$foundids = array();
	$arr = array( sanitize_text_field( $_SERVER['HTTP_HOST'] ) );
    foreach ( (array) $pages as $page ) {
		$arr[] = isset( $page->post_parent_name ) ? $page->post_parent_name : $page->post_name;
    }
	$requestA = explode( '/',sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
	if( isset( $requestA[1] ) ){
		$arr[] = $requestA[1];
	}
	$diff = array_diff( $parts,$arr );
	if( isset( $urlsA['need_url'] ) && !empty( $diff ) ){
		$need_url = $urlsA['need_url'];
		$maybe_lang = implode( '',$diff );
		$ids = array_keys( $pages );
		foreach( $ids as $id ){
			if( isset( $need_url[$id] ) ){
				$url = $need_url[$id];
				foreach( array( 'https://','http://','www.' ) as $search ){
					$url = str_replace( $search,'',$url );
				}
				if( $url === $page_path ) return $id;
			}
		}
	}
	return false;
}

/**
 * Prevent disabling wrong plugins.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_prevent_disabling_wrong_plugins( $plugins ){
	if( in_array( 'woocommerce/woocommerce.php',$plugins ) && isset( $_REQUEST['order'] ) && 0 === strpos( sanitize_text_field( $_GET['order'] ),'wc_order_' ) ){
		unset( $plugins[array_search( 'woocommerce/woocommerce.php',$plugins)] );
	}
	return $plugins;
}

/**
 * Move FDP to the first position in the plugins array.
 * 
 * @param array $plugins
 * 
 * @since 1.9.0
 *
 */
function eos_dp_unshift_fdp( $plugins ){
	if( is_array( $plugins ) ){
		if( in_array( 'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php',$plugins ) ){
			array_unshift( $plugins,'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php' );
		}
		$plugins = array_unique( $plugins );
	}
	return $plugins;
}

/**
 * Return array of conditions.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_logged_user_conditions(){
	return array(
		array( 'role','hammer',__( 'Role' ) ),
		array( 'capability','admin-tools',__( 'Capability','eos-dp-pro' ) ),
		array( 'username','businessperson',__( 'Username' ) ),
		array( 'email','email','Email' ),
		array( 'language','translation',__( 'Language' ) ),
		array( 'registered_before','backup',__( 'Registered before','eos-dp-pro' ) ),
		array( 'registered_after','clock',__( 'Registered after','eos-dp-pro' ) ),
		array( 'has_bought','cart',__( 'Has bought something','eos-dp-pro' ) ),
		array( 'usermeta','nametag',__( 'User meta','eos-dp-pro' ) )
	);
}

/**
 * Return $a or $b.
 * 
 * @param int|string|array|obect $a
 * @param int|string|array|obect $b
 * 
 * @since 1.9.0
 *
 */
function eos_dp_or( $a,$b ){
	return $a || $b;
}

/**
 * Return $a && $b.
 * 
 * @param int|string|array|obect $a
 * @param int|string|array|obect $b
 * @since 1.9.0
 *
 */
function eos_dp_and( $a,$b ){
	return $a && $b;
}

/**
 * Return not of $a.
 * 
 * @param int|string|array|obect $a
 * @since 1.9.0
 *
 */
function eos_dp_not( $a ){
	return !$a;
}

/**
 * Check if user logged in when the WP core function not available.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_is_user_logged(){
	if( defined( 'LOGGED_IN_COOKIE' ) && isset( $_COOKIE[sanitize_text_field( LOGGED_IN_COOKIE )] ) ){
		return true;
	}
	if( isset( $_COOKIE ) && !empty( $_COOKIE ) && is_array( $_COOKIE ) ){
		foreach( $_COOKIE as $ck => $cv ){ // @codingStandardsIgnoreLine.
			if( false !== strpos( $ck,'wordpress_logged_in_' ) ){
				return true;
			}
		}
	}
	return false;
}

/**
 * Get current user when core function not available.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_get_current_user() {
		if( function_exists( 'wp_get_current_user' ) ) return wp_get_current_user(); // If the core function is availablle we use it and return.
		if( !defined( 'LOGGED_IN_COOKIE' ) || !isset( $_COOKIE[LOGGED_IN_COOKIE] ) ) return false;
		$cookie = $_COOKIE[LOGGED_IN_COOKIE]; // @codingStandardsIgnoreLine.
    if ( empty( $cookie ) ) {
        if ( empty( $_COOKIE[ LOGGED_IN_COOKIE] ) ) { // @codingStandardsIgnoreLine.
            return false;
        }
    }
    $cookie_elements = explode( '|', $cookie );
    if ( count( $cookie_elements ) !== 4 ) {
        return false;
    }
		if( isset( $cookie_elements[0] ) && $cookie_elements[0] && '' !== $cookie_elements[0] ){
			global $wpdb;
			$user = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $wpdb->users WHERE user_login = %s LIMIT 1",
					sanitize_user( $cookie_elements[0] )
				)
			);
			if(
				$user
				&& is_object( $user )
				&& isset( $user->user_login )
				&& sanitize_user( $user->user_login ) === $user->user_login
				&& isset( $user->user_email )
				&& $user->user_email === sanitize_email( $user->user_email )
				&& isset( $user->ID )
				&& ''.absint( $user->ID ) === ''.$user->ID
			){
				return $user;
			}
		}
		return false;
}

/**
 * Parse expression for logged-in user_status.
 * 
 * @param string $expression
 * @param object $user
 * 
 * @since 1.9.0
 *
 */
function eos_dp_parse_expression( $expression,$user ){
	$f= substr( $expression,0,strpos( $expression,'(' ) );
	$not = $f === 'not';
	if( $not ){
		$expression = rtrim( ltrim( $expression,'not(' ),')' );
		$f= substr( $expression,0,strpos( $expression,'(' ) );
	}
	if( !in_array( $f,array( 'and','or' ) ) ){
		$value = str_replace( ')','',str_replace( '(','',ltrim( $expression,$f ) ) );
		switch( $f ){
			case 'role':
			case 'capability':
				$roles = get_user_meta( $user->ID,'wp_capabilities' );
				if( 'role' === $f ){
					if( $roles && is_array( $roles ) ){
						foreach( $roles as $roleA ){
							if( in_array( $value,array_keys( $roleA ) ) && $roleA[$value] ){
								return !$not;
							}
							else{
								return $not;
							}
						}
					}
				}
				elseif( 'capability' === $f ){
					$user_roles = eos_dp_get_option( 'wp_user_roles' );
					$roleA = array_keys( $roles[0] );
					$role = $roleA[0];
					if( isset( $user_roles[$role] ) ){
						$caps = $user_roles[$role];
						if( isset( $caps['capabilities'] ) ){
							$caps = $caps['capabilities'];
							if( isset( $caps[$value] ) && $caps[$value] ){
								return !$not;
							}
						}
					}
				}
				break;
			case 'username':
				return !$not && strtolower( $user->user_login ) === strtolower( $value );
				break;
			case 'email':
				return !$not && $user->user_email === $value;
				break;
			case 'language':
				$user_locale = get_user_meta( $user->ID,'locale' );
				return !$not && $user_locale && $user_locale === $value;
				break;
			case 'registered_before':
				return !$not && strtotime( $user->user_registered ) < strtotime( $value );
				break;
			case 'registered_after':
				return !$not && strtotime( $user->user_registered ) > strtotime( $value );
				break;
			case 'has_bought':
				return !$not && eos_dp_user_has_bought( $user->ID );
				break;
			case 'usermeta':
				return !$not && eos_dp_has_usermeta( $user->ID,$value );
				break;
		}
	}
}

/**
 * Parse expression for logged-in user_status.
 * 
 * @param int $user_id
 * 
 * @since 1.9.0
 *
 */
function eos_dp_user_has_bought( $user_id ){
    $customer_orders = get_posts( array(
        'numberposts' => 1,
        'meta_key' => '_customer_user',
        'meta_value' => $user_id,
        'post_type' => 'shop_order',
        'post_status' => 'wc-completed',
        'fields' => 'ids',
    ) );
   return $customer_orders && count( $customer_orders ) > 0 ? true : false;
}

/**
 * Check if the usermeta value matches the current user.
 * 
 * @param int $user_id
 * @param string $key_value
 * 
 * @since 1.9.0
 *
 */
function eos_dp_has_usermeta( $user_id,$key_value ){
	if( $key_value && '' !== $key_value ){
		if( false === strpos( $key_value,'=' ) ){
			$key_value .= '=any';
		}
		$arr = explode( '=',$key_value );
		if( $arr[0] && '' !== $arr[0] ){
			if( 'not_defined' === $arr[1] && !$usermeta ){
				return false;
			}
			$usermeta = get_user_meta( $user_id,sanitize_key( $arr[0] ),true );
			return $usermeta && ( 'any' === $arr[1] || ( str_replace( '0','false',str_replace( '1','true',$usermeta ) ) == str_replace( '0','false',str_replace( '1','true',$arr[1] ) ) ) );
		}
	}
	return false;
}

/**
 * Warn the user the mu-plugin is still installed.
 * 
 * @since 1.0.0
 *
 */
function eos_dp_missing_fdp_notice(){
	?>
	<div class="fdp-notice notice notice-error" style="display:block !important">
		<p style="font-size:20px;font-weight:bold">Freesoul Deactivate Plugins is not active, but its mu-plugin was not deleted.</p>
		<p>Normally, when you deactivate FDP, it automatically deletes its  mu-plugin file.</p>
		<p>If you still have that file, it means you disabed FDP via FTP or something went wrong during the deactivation process.</p>
		<p>Delete the file <strong><?php echo esc_html( str_replace( ABSPATH,'',WPMU_PLUGIN_DIR ).'/eos-deactivate-plugins.php' ); ?></strong> if you want to completely disable FDP.</p>
	</div>
	<?php
}

/**
 * Get options by URL.
 * 
 * @param string $url
 * 
 * @since 1.0.0
 *
 */
function eos_dp_get_opts_by_url( $url ){
	if(
		!defined( 'EOS_DP_MU_PLUGIN_DIR' )
		|| false !== strpos( $url,basename( dirname( EOS_DP_MU_PLUGIN_DIR ) ) )
		|| false !== strpos( $url,'.js' )
		|| false !== strpos( $url,'.css' )
	){
		return false;
	}
	$url = str_replace( 'www.','',$url );
	$upload_dirs = wp_upload_dir();
	$path1 = ltrim( rtrim( $url,'/' ),'/' );
	$arr = array(
		array( $path1.'-mobile',eos_dp_is_mobile() ),
		array( $path1,true )
	);

	foreach(  $arr as $arr2 ){
		$parts = explode( '/',$arr2[0] );
		$path = $upload_dirs['basedir'].'/FDP/fdp-single-options';
		foreach( $parts as $part ){
			$path .= '/'.substr( md5( eos_dp_sanitize_file_name( $part ) ),0,8 );
		}
		if( $arr2[1] && file_exists( $path.'/opts.json' ) ){
			return json_decode( stripslashes( sanitize_text_field( file_get_contents( $path.'/opts.json' ) ) ),true );
		}
	}
	return false;
}
if( isset( $_REQUEST['eos_dp_pro_id'] ) ){
	add_filter( 'show_admin_bar','__return_false',999999 );
}
add_action( 'init',function(){
	/**
	 * Fire if FDP is disabled.
	 * 
	 * @since 1.0.0
	 *
	 */
	if( ! defined( 'EOS_DP_VERSION' ) ){
		add_action( 'admin_notices','eos_dp_missing_fdp_notice' );
		// If FDP is disabled fire the action fdp_disabled.
		do_action( 'fdp_disabled' );
	}
} );

/**
 * Return true if the plugin is active.
 * 
 * @param string $plugin
 * 
 * @since 1.0.0
 *
 */
function fdp_is_plugin_globally_active( $plugin ){
	return is_array( $GLOBALS['fdp_all_plugins'] ) && in_array( $plugin,$GLOBALS['fdp_all_plugins'] );
}

/**
 * Return true if the request is done via Ajax.
 * 
 * @since 1.0.0
 *
 */
function eos_dp_is_maybe_ajax(){
	if( wp_doing_ajax() || isset( $_REQUEST['wc-ajax'] ) ) return true;
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'] ) && 'xmlhttprequest' === strtolower( sanitize_text_field( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ){
		return true;
	}
	return false;
}

/**
 * Sanitize file name.
 * 
 * @since 1.9.0
 *
 */
function eos_dp_sanitize_file_name( $filename ){
	$filename_raw = $filename;
	$filename     = remove_accents( $filename );
	$special_chars = array( '?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', '’', '«', '»', '”', '“', chr( 0 ) );
	static $utf8_pcre = null;
	if ( ! isset( $utf8_pcre ) ) {
		$utf8_pcre = @preg_match( '/^./u', 'a' );
	}
	if ( ! seems_utf8( $filename ) ) {
		$_ext     = pathinfo( $filename, PATHINFO_EXTENSION );
		$_name    = pathinfo( $filename, PATHINFO_FILENAME );
		$filename = sanitize_title_with_dashes( $_name ) . '.' . $_ext;
	}
	if ( $utf8_pcre ) {
		$filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
	}
	$filename = str_replace( $special_chars, '', $filename );
	$filename = str_replace( array( '%20', '+' ), '-', $filename );
	$filename = preg_replace( '/[\r\n\t -]+/', '-', $filename );
	$filename = trim( $filename, '.-_' );
	if ( false === strpos( $filename, '.' ) ) {
		$mime_types = wp_get_mime_types();
		$filetype   = wp_check_filetype( 'test.' . $filename, $mime_types );
		if ( $filetype['ext'] === $filename ) {
			$filename = 'unnamed-file.' . $filetype['ext'];
		}
	}
	$parts = explode( '.', $filename );
	if ( count( $parts ) <= 2 ) {
		return $filename;
	}
	$filename  = array_shift( $parts );
	$extension = array_pop( $parts );
	$mimes     = function_exists( 'wp_get_current_user' ) ? get_allowed_mime_types() : false;
	foreach ( (array) $parts as $part ) {
		$filename .= '.' . $part;
		if ( preg_match( '/^[a-zA-Z]{2,5}\d?$/', $part ) ) {
			$allowed = false;
			if( $mimes ){
				foreach ( $mimes as $ext_preg => $mime_match ) {
					$ext_preg = '!^(' . $ext_preg . ')$!i';
					if ( preg_match( $ext_preg, $part ) ) {
						$allowed = true;
						break;
					}
				}
			}
			if ( ! $allowed ) {
				$filename .= '_';
			}
		}
	}
	$filename .= '.' . $extension;
	return $filename;
}

/**
 * Check if the URL has an extension.
 * 
 * @param string $url
 * 
 * @since 1.0.0
 *
 */
function eos_dp_url_has_extension( $url ){
	$pathinfo = pathinfo( $url );
	return isset( $pathinfo ) && !empty( $pathinfo['extension'] );
}

add_action( 'send_headers', function(){
	/**
	 * Add header with the number of disabled plugins.
	 * 
	 * @since 1.0.0
	 *
	 */
	global $eos_dp_paths;
	$n = $eos_dp_paths ? count( array_unique( array_filter( $eos_dp_paths ) ) ) : 'none';
	header( 'Disabled-plugins: '.absint( $n ).' on '.date( 'Y-m-d h:i:s' ) );
}, 100 );

add_filter( 'wp_php_error_message',function( $message, $error ){
	/**
	 * Hamdle the fatal errors.
	 * 
	 * @param string $message
	 * @param array $error
	 * 
	 * @since 1.0.0
	 *
	 */
	if( !isset( $_GET['eos_dp_preview'] ) && isset( $error['file'] ) ){
		$dir = ltrim( dirname( str_replace( WP_PLUGIN_DIR,'',$error['file'] ) ),'/' );
		global $fdp_all_plugins;
		$disabled = false;
		foreach( $fdp_all_plugins as $plugin ){
			if( dirname( $plugin ) === $dir ){
				$disabled = $plugin;
				break;
			}
		}
		if( $disabled ){
			$arr = array(
				'time' => time(),
				'message' => sanitize_text_field( $error['message'] ),
				'line' => absint( $error['line'] ),
				'plugin' => sanitize_text_field( $disabled ),
				'file' => sanitize_text_field( $error['file'] )
			);
			if( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ){
				$arr['url'] = is_ssl() ? 'https://'.sanitize_text_field( $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ) : 'http://'.sanitize_text_field( $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );
			}
			set_site_transient( 'fdp_plugin_disabledd_fatal_error',$arr,60*60*2 );
			if( is_admin() ){
				$user = eos_dp_get_current_user();
				if( $user && is_object( $user ) && isset( $user->allcaps ) && in_array( 'manage_options',$user->allcaps ) ){
					$plugin_name = strtoupper( sanitize_text_field( eos_dp_get_plugin_name_by_slug( $disabled ) ) );
					$message .= '<p>'.sprintf( 'FDP has disalbed %s in all the FDP backend pages because of the fatal error.</p>',sanitize_text_field( $plugin_name ) );
					$message .= '<p>'.sprintf( 'If you need it, you can disable %s with FDP on other pages.',sanitize_text_field( $plugin_name ) ).'</p>';
					$message .= '<p><button><a style="text-decoration:none;color:inherit" href="'.admin_url( '?page=eos_dp_menu' ).'">Go to FDP backend</a></button></p>';
				}
			}
		}
	}
	return $message;
},99,2 );

/**
 * Fatal error notice.
 * 
 * @since 1.0.0
 *
 */
function eos_dp_fatal_error_notice(){
	static $called = false;
	if( !$called ){
		$called = true;
		$fatal_error_handler = get_site_transient( 'fdp_plugin_disabledd_fatal_error' );
		$line = isset( $fatal_error_handler['line'] ) ? absint( $fatal_error_handler['line'] ) : false;
		$file = isset( $fatal_error_handler['file'] ) ? $fatal_error_handler['file'] : false;
		$plugin_name = strtoupper( eos_dp_get_plugin_name_by_slug( $fatal_error_handler['plugin'] ) );
		$notice_description = sprintf( 'Be careful! %s caused a fatal error and has been deactivated in the FDP backend pages.',esc_attr( $plugin_name ) );
		$notice_description .= PHP_EOL.sprintf( 'The error was last time triggered by the URL %s%s%s','<a href="'.esc_attr( $fatal_error_handler['url'] ).'" target="_blank">',esc_attr( $fatal_error_handler['url'] ),'</a>' );
		$notice_description .= PHP_EOL.sprintf( 'It seems the cause of the error is the line %s of the file %s.',absint( $fatal_error_handler['line'] ),esc_attr( str_replace( ABSPATH,'',$fatal_error_handler['file'] ) ) );
		$notice_description .= PHP_EOL.sprintf( 'Here you have the error: %s',PHP_EOL.PHP_EOL.'<p style="background: #000;color:#fff;padding: 10px;">'.wp_kses_post( str_replace( ABSPATH,'',$fatal_error_handler['message'] ) ) ).'</p>';
		if( $line && $file ){
			$notice_description .= eos_dp_get_code_extract( $line,$file );
		}
		$after_notice = sprintf( 'Dismissing this notice FDP will activate again %s in the FDP backend pages. Maybe better you first solve the issue.',esc_attr( $plugin_name ) );
		eos_dp_display_admin_notice( 'plugin_fatal_error',sprintf( '%s caused a fatal error.',$plugin_name ),$notice_description,'error',$after_notice );
	}
}

/**
 * Update the FDP admin notices.
 * 
 * @since 1.0.0
 *
 */
function eos_dp_update_admin_notices( $key,$msg ){
	if( function_exists( 'get_current_user_id' ) ){
		$user_id = get_current_user_id();
		if( $key && '' !== $key && $user_id && absint( $user_id ) > 0 ){
			$admin_notices = get_user_meta( $user_id,'fdp_admin_notices',true );
			if( isset( $admin_notices[sanitize_key( $key )] ) ){
				unset( $admin_notices[sanitize_key( $key )] );
			}
			update_user_meta( get_current_user_id(),'fdp_admin_notices',$admin_notices );
		}
	}
	set_site_transient( 'fdp_admin_notice_'.sanitize_key( $key ),wp_kses_post( $msg ),60*60*48 );
}

/**
 * Retrieve an extract of the code from $line and $file.
 * 
 * @param int $line
 * @param string $file
 * @since 1.0.0
 *
 */
function eos_dp_get_code_extract( $line,$file ){
	$code = '';
	$file_content = file_get_contents( $file );
	if( $file_content && '' !== $file_content ){
		$code .= PHP_EOL.PHP_EOL.'Here an extract of the code:';
		$code .= PHP_EOL.PHP_EOL.'<pre style="background:black;padding:20px;line-height:1;font-size:14px;font-family:Arial;max-height:300px;overflow-y:scroll">';
		$lines = explode( PHP_EOL,$file_content );
		$padding = strlen( round( absint( $line )/10,0 ) );
		for( $n = absint( $line ) - 3;$n <  absint( $line ) + 3;++$n ){
			if( isset( $lines[$n] ) ){
				$color = $n + 1 === $line ? 'white' : 'grey';
				$code .= PHP_EOL.'<span style="color:white"><span style="color:'.$color.'">'.substr( 1000*$padding + $n + 1,1 ).'  </span>'.esc_html( $lines[$n] ).'</span>';
			}
		}
		$code .= '</pre>';
	}
	return $code;
}

/**
 * Filter active plugins.
 * 
 * @param string $callback
 * @param int $priority
 * @param bool $cron
 * 
 * @since 1.0.0
 *
 */
function eos_dp_filter_active_plugins( $callback,$priority,$cron = false ){
	if( !wp_doing_cron() || $cron ){
		add_filter( 'option_active_plugins',$callback,$priority );
	}
}

add_action( 'muplugins_loaded',function(){
	/**
	 * Add filters to disable plugins according to the settings.
	 * 
	 * @since 1.9.0
	 *
	 */
	if( ! is_admin() ) {
		eos_dp_filter_active_plugins(  'eos_dp_one_place',0,1 );
	}
	eos_dp_filter_active_plugins(  'eos_dp_browser',0,1 );
	eos_dp_filter_active_plugins(  'eos_dp_disabled_plugins_by_device_filter',10,1 );
	eos_dp_filter_active_plugins(  'eos_dp_disabled_plugins_on_search_filter',20 );
	if( defined( 'FDP_PRO_IS_ACTIVE' ) &&  FDP_PRO_IS_ACTIVE ){
		// Run only if FDP PRO is active.
		eos_dp_filter_active_plugins(  'eos_dp_disabled_plugins_for_logged_users',40,1 );
		eos_dp_filter_active_plugins(  'eos_dp_disabled_plugins_on_ajax_filter',999 );
		if( isset( $_POST ) && !empty( $_POST ) && ( !isset( $_REQUEST['action'] ) || 'heartbeat' !== $_REQUEST['action'] ) ){
			eos_dp_filter_active_plugins(  'eos_dp_mu_deactivate_by_post_requests',50 );
		}
		if( wp_doing_cron() ){
			add_filter( 'option_active_plugins','eos_dp_cron_active_plugins' );
		}
	}
} );

/**
 * Get plugin name by slug.
 *
 * @since 2.0.0
 *
 */
function eos_dp_get_plugin_name_by_slug( $plugin_slug ){
	$plugin_slug_names = eos_dp_get_option( 'fdp_plugin_slug_names' );
	if( $plugin_slug_names && is_array( $plugin_slug_names  ) && array_key_exists( $plugin_slug,$plugin_slug_names ) ){
		return sanitize_text_field( $plugin_slug_names[sanitize_text_field( $plugin_slug )] );
	}
	return str_replace( '-',' ',dirname( sanitize_text_field( $plugin_slug ) ) );
}

/**
 * Get plugin name by slug.
 *
 * @since 2.1.1
 *
 */
function eos_dp_get_plugin_slug_by_name( $plugin_name ){
	$plugin_slug_names = eos_dp_get_option( 'fdp_plugin_slug_names' );
	$plugin_slug = array_search( $plugin_name, $plugin_slug_names );
	return $plugin_slug ? sanitize_text_field( $plugin_slug ) : false;
}

/**
 * Return true if URL is matched.
 *
 * @param string $url
 * @since 2.1.5
 *
 */
function eos_dp_is_url_matched( $url, $uri ){
	if( '[home]?*' === $url && false === strpos( '?', $uri ) ) return false;
	$url = str_replace( array( 'https://','http://','www.' ),array( '','','' ),$url );
	$url = str_replace( '[home]',get_home_url(),str_replace( '[home]?','[home]/?',$url ) );
	$pattern = '/'.str_replace( '/','\/',str_replace( '*','(.*)',str_replace( '**','*',$url ) ) ).'\s/';
	$pattern = '/'.str_replace( '/','\/',str_replace( '*','(.*)',str_replace( '**','*',$url ) ) ).'\s/i';
	$pattern = str_replace( '?','\?',$pattern );
	$pattern = str_replace( '&','\&',$pattern );
	preg_match( $pattern,$uri.' ',$matches );
	return 	( !empty( $matches ) && count( $matches ) - 1 === substr_count( $pattern,'(.*)' ) ) || ( str_replace( array( 'https://','http://','www.' ),array( '','','' ),$url ) === explode( '?',$uri )[0].'?*' );
}