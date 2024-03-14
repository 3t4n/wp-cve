<?php
/*
Plugin Name: Heateor Social Comments
Plugin URI: https://www.heateor.com
Description: Integrate Facebook Comments, Vkontakte Comments and Disqus Comments along with default WordPress Comments
Version: 1.6.3
Author: Team Heateor
Author URI: https://www.heateor.com
Text Domain: heateor-social-comments
Domain Path: /languages
License: GPL2+
*/
defined( 'ABSPATH' ) or die( "Cheating........Uh!!" );
define( 'HEATEOR_SOCIAL_COMMENTS_VERSION', '1.6.3' );

$heateor_sc_options = get_option( 'heateor_sc' );

// include shortcode
require 'inc/shortcode.php';

/**
 * Hook the plugin function on 'init' event
 */
function heateor_sc_init() {
	add_action( 'wp_enqueue_scripts', 'heateor_sc_frontend_styles' );
	global $heateor_sc_options;
	if( isset( $heateor_sc_options['enable_facebookcomments'] ) || isset( $heateor_sc_options['enable_disquscomments'] ) || isset( $heateor_sc_options['enable_vkontaktecomments'] ) ) {
		add_filter( 'comments_template', 'heateor_sc_social_commenting' );
	}
	load_plugin_textdomain( 'heateor-social-comments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'heateor_sc_init' );

/**
 * Render Social Commenting
 */
function heateor_sc_social_commenting( $file ) {
	if ( ( is_single() || is_page() || is_singular() ) && comments_open() ) {
		// if password is required, return
		if ( post_password_required() ) {
			echo '<p>'.__( 'This is password protected.', 'heateor-social-comments' ).'</p>';
			return plugin_dir_path( __FILE__ ) . '/inc/comments.php';
		}

		// check if social comments are enabled at this post type
		global $post, $heateor_sc_options;
		
		$comments_meta = '';
		if ( ! is_front_page() || ( is_front_page() && 'page' == get_option( 'show_on_front' ) ) ) {
			$comments_meta = get_post_meta( $post->ID, '_heateor_sc_meta', true );
			if ( isset( $comments_meta['disable_comments'] ) ) {
				return $file;
			}
		}

		$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
		if ( count( $post_types ) > 0 && isset( $post->post_type ) && ! isset( $heateor_sc_options['enable_' . $post->post_type] ) ) {
			return $file;
		}

		global $heateor_sc_options;
		$commentsOrder = $heateor_sc_options['commenting_order'];
		$commentsOrder = explode( ',', $commentsOrder );
		
		$tabs = '';
		$divs = '';
		foreach( $commentsOrder as $key => $order ) {
			$commentsOrder[$key] = trim( $order );
			if ( ! isset( $heateor_sc_options['enable_' .$order. 'comments'] ) ) { unset($commentsOrder[$key]); }
		}
		$orderCount = 0;
		foreach( $commentsOrder as $order ) {
			
			$comment_div = '';
			if ( $order == 'wordpress' ) {
				if ( isset( $heateor_sc_options['counts'] ) ) {
					$comments_count = heateor_sc_get_wp_comments_count();
				}
				$comment_div = '<div style="clear:both"></div>' . heateor_sc_render_wp_comments( $file ) . '<div style="clear:both"></div>';
			} elseif ( $order == 'facebook' ) {
				$comment_div = heateor_sc_render_fb_comments();
			} elseif ( $order == 'disqus' ) {
				if ( isset( $heateor_sc_options['counts'] ) ) {
					$comments_count = heateor_sc_get_dq_comments_count();
				}
				$comment_div = heateor_sc_render_dq_comments();
			} elseif ( $order == 'vkontakte' ) {
				$comment_div = heateor_sc_render_vk_comments();
			}

			$divs .= '<div ' . ( $orderCount != 0 ? 'style="display:none"' : '' ) . ' id="heateor_sc_' . $order . '_comments">' . ( isset( $heateor_sc_options['commenting_layout'] ) && $heateor_sc_options['commenting_layout'] == 'stacked' && isset( $heateor_sc_options['label_' . $order . '_comments'] ) ? '<h3 class="comment-reply-title">' . $heateor_sc_options['label_' . $order . '_comments'] . ( isset( $comments_count ) ? ' (' . intval( $comments_count ) . ')' : '' ) . '</h3>' : '' );
			$divs .= $comment_div;
			$divs .= '</div>';

			if ( ! isset( $heateor_sc_options['commenting_layout'] ) || $heateor_sc_options['commenting_layout'] == 'tabbed' ) {
				$tabs .= '<li><a ' . ( $orderCount == 0 ? 'class="heateor-sc-ui-tabs-active"' : '' ) . ' id="heateor_sc_' . $order . '_comments_a" href="javascript:void(0)" onclick="this.setAttribute(\'class\', \'heateor-sc-ui-tabs-active\');document.getElementById(\'heateor_sc_' . $order . '_comments\').style.display = \'block\';';
				foreach ($commentsOrder as $commenting) {
					if($commenting != $order){
						$tabs .= 'document.getElementById(\'heateor_sc_' . $commenting . '_comments_a\').setAttribute(\'class\', \'\');document.getElementById(\'heateor_sc_' . $commenting . '_comments\').style.display = \'none\';';
					}
				}
				$tabs .= '">';
				// icon
				if ( isset( $heateor_sc_options['enable_' . $order . 'icon'] ) || ( ! isset( $heateor_sc_options['enable_' . $order . 'icon'] ) && ! isset( $heateor_sc_options['label_' . $order . '_comments'] ) ) ) {
					$alt = isset( $heateor_sc_options['label_' . $order . '_comments'] ) ? $heateor_sc_options['label_' . $order . '_comments'] : ucfirst( $order ) . ' Comments';
					$tabs .= '<div title="'. $alt .'" class="heateor_sc_' . $order . '_background"><i class="heateor_sc_' . $order . '_svg"></i></div>';
				}
				// label
				if ( isset( $heateor_sc_options['label_' . $order . '_comments'] ) ) {
					$tabs .= '<span class="heateor_sc_comments_label">' . $heateor_sc_options['label_' . $order . '_comments'] . '</span>';
				}
				if ( $order != 'facebook' && $order != 'vkontakte' ) {
					$tabs .= ( isset( $comments_count ) ? ' (' . $comments_count . ')' : '' );
				}
				$tabs .= '</a></li>';
				
				$orderCount++;
			}
			
		}
		$commentingHtml = '<div class="heateor_sc_social_comments">';
		if ( $tabs ) {
			$commentingHtml .= ( isset( $heateor_sc_options['commenting_label'] ) ? '<div style="clear:both"></div><h3 class="comment-reply-title">' . $heateor_sc_options['commenting_label'] . '</h3><div style="clear:both"></div>' : '' ) . '<ul class="heateor_sc_comments_tabs">' . $tabs . '</ul>';
		}
		$commentingHtml .= $divs;
		$commentingHtml .= '</div>';
		echo $commentingHtml;
		// hack to return empty string
		return plugin_dir_path( __FILE__ ) . '/inc/comments.php';
	}
	return $file;
}

/**
 * Get WordPress Comments count
 */
function heateor_sc_get_wp_comments_count() {
	global $post;
	$comments_count = wp_count_comments( $post->ID );
	return ( $comments_count && isset( $comments_count -> approved ) ? $comments_count -> approved : 0 );
}

/**
 * Get Disqus Comments count
 */
function heateor_sc_get_dq_comments_count(){
	global $heateor_sc_options;
	if ( ! $heateor_sc_options['dq_key'] || ! $heateor_sc_options['dq_shortname'] ) {
		return 0;
	}
	$response = wp_remote_get( 'https://disqus.com/api/3.0/threads/set.json?api_key=' . $heateor_sc_options['dq_key'] . '&forum=' . $heateor_sc_options["dq_shortname"] . '&thread=link:' . urlencode( heateor_sc_get_current_page_url() ) );
	if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
		return '0';
	}
	$json = json_decode( $response['body'] );
	return isset( $json->response[0] ) && isset( $json->response[0]->posts ) ? $json->response[0]->posts : 0;	
}

/**
 * Get current page url
 */
function heateor_sc_get_current_page_url() {
	global $post;
	if ( isset( $post -> ID ) && $post -> ID ) {
		return get_permalink( $post -> ID );
	} else {
		return html_entity_decode( esc_url( heateor_sc_get_http().$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
	}
}

/**
 * Get http/https protocol at the website
 */
function heateor_sc_get_http() {
	if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
		return "https://";
	} else {
		return "http://";
	}
}

/**
 * Render Disqus Comments
 */
function heateor_sc_render_dq_comments() {
	global $heateor_sc_options;
	$shortname = isset( $heateor_sc_options['dq_shortname'] ) && $heateor_sc_options['dq_shortname'] != '' ? $heateor_sc_options['dq_shortname'] : '';
	return '<div class="embed-container clearfix" id="disqus_thread">' . ( $shortname != '' ? $shortname : '<div style="font-size: 14px;clear: both;">' . __( 'Specify a Disqus shortname at Social Comments options page in admin panel', 'heateor-social-comments' ) . '</div>' ) . '</div><script type="text/javascript">var disqus_shortname = "' . $shortname . '";(function(d) {var dsq = d.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;dsq.src = "//" + disqus_shortname + ".disqus.com/embed.js"; (d.getElementsByTagName("head")[0] || d.getElementsByTagName("body")[0]).appendChild(dsq); })(document);</script>';
}

/**
 * Render Facebook Comments
 */
function heateor_sc_render_fb_comments() {
	global $heateor_sc_options;
	if ( isset( $heateor_sc_options['urlToComment'] ) && $heateor_sc_options['urlToComment'] != '' ) {
		$url = $heateor_sc_options['urlToComment'];
	} else {
		$url = heateor_sc_get_current_page_url();
	}
	$commentingHtml = '<style type="text/css">.fb-comments,.fb-comments span,.fb-comments span iframe[style]{min-width:100%!important;width:100%!important}</style><div id="fb-root"></div><script type="text/javascript">';
	if ( ( defined( 'HEATEOR_FB_COM_NOT_VERSION' ) && version_compare( '1.1.4', HEATEOR_FB_COM_NOT_VERSION ) > 0 ) || ( defined( 'HEATEOR_FB_COM_MOD_VERSION' ) && HEATEOR_FB_COM_MOD_VERSION == '1.1.4' ) ) {
		$commentingHtml .= 'window.fbAsyncInit=function(){FB.init({appId:"'. ( $heateor_sc_options['fb_app_id'] != '' ? $heateor_sc_options["fb_app_id"] : '' ) .'",channelUrl:"'. site_url() .'//channel.html",status:!0,cookie:!0,xfbml:!0,version:"v3.2"}),FB.Event.subscribe("comment.create",function(e){if(typeof e.commentID != "undefined" && e.commentID){';
		if ( defined( 'HEATEOR_FB_COM_NOT_VERSION' ) && version_compare( '1.1.4', HEATEOR_FB_COM_NOT_VERSION ) > 0 ) {
			$commentingHtml .= 'jQuery.ajax({
	            type: "POST",
	            dataType: "json",
	            url: "' . site_url() . '/index.php",
	            data: {
	                action: "heateor_sc_moderate_fb_comments",
	                data: e
	            },
	            success: function(a,b,c) {}
	        });';
		}
    	if ( defined( 'HEATEOR_FB_COM_MOD_VERSION' ) && HEATEOR_FB_COM_MOD_VERSION == '1.1.4' ) {
    		$commentingHtml .= 'jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: "' . site_url() . '/index.php",
                data: {
                    action: "heateor_fcm_save_fb_comment",
                    data: e
                },
                success: function(a,b,c) {}
            });';
    	}
        $commentingHtml .= '}})};';
	}
	$commentingHtml .= '!function(e,n,t){var o,c=e.getElementsByTagName(n)[0];e.getElementById(t)||(o=e.createElement(n),o.id=t,o.src="//connect.facebook.net/' . ( isset($heateor_sc_options['comment_lang']) && $heateor_sc_options['comment_lang'] != '' ? $heateor_sc_options["comment_lang"] : 'en_US' ) . '/sdk.js#xfbml=1&version=v17.0' . ( $heateor_sc_options['fb_app_id'] != '' ? '&appId=' . $heateor_sc_options["fb_app_id"] : '' ) . '",c.parentNode.insertBefore(o,c))}(document,"script","facebook-jssdk");</script>';
	$commentingHtml .= '<div style="clear:both"></div>' . heateor_sc_facebook_comments_moderation_optin() . '<div style="clear:both"></div>';
	$commentingHtml .= '<div style="clear:both"></div>' . heateor_sc_facebook_comments_notifier_optin() . '<div style="clear:both"></div>';
	$commentingHtml .= '<div class="fb-comments" data-href="' . $url . '" data-colorscheme="' . ( isset($heateor_sc_options['comment_color']) && $heateor_sc_options['comment_color'] != '' ? $heateor_sc_options["comment_color"] : '' ) . '" data-numposts="' . ( isset($heateor_sc_options['comment_numposts']) && $heateor_sc_options['comment_numposts'] != '' ? $heateor_sc_options["comment_numposts"] : '' ) . '" data-width="' . ( isset( $heateor_sc_options['comment_width'] ) && $heateor_sc_options['comment_width'] != '' ? $heateor_sc_options["comment_width"] : '100%' ) . '" data-order-by="' . ( isset($heateor_sc_options['comment_orderby']) && $heateor_sc_options['comment_orderby'] != '' ? $heateor_sc_options["comment_orderby"] : '' ) . '" ></div>';
	$commentingHtml .= heateor_sc_facebook_comments_moderation_optin_script();
	$commentingHtml .= heateor_sc_facebook_comments_notifier_optin_script();
	return $commentingHtml;
}

/**
 * Show opt-in for Facebook Comments Moderation add-on
 */
function heateor_sc_facebook_comments_moderation_optin() {
	global $heateor_fcm_options;
	if ( defined( 'HEATEOR_FB_COM_MOD_VERSION' ) && version_compare( '1.2.4', HEATEOR_FB_COM_MOD_VERSION ) < 0 && isset( $heateor_fcm_options['gdpr_enable'] ) ) {
		return '<div class="heateor_ss_fb_comments_optin_container"><label><input type="checkbox" class="heateor_ss_fb_comments_optin" value="1" />' . str_replace( $heateor_fcm_options['ppu_placeholder'], '<a href="'. $heateor_fcm_options['privacy_policy_url'] .'" target="_blank">' . $heateor_fcm_options['ppu_placeholder'] . '</a>', wp_strip_all_tags( $heateor_fcm_options['privacy_policy_optin_text'] ) ) . '</label></div>';
	}
	return '';
}

/**
 * Show opt-in for Facebook Comments Notifier add-on
 */
function heateor_sc_facebook_comments_notifier_optin() {
	global $heateor_fcn_options;
	if ( defined( 'HEATEOR_FB_COM_NOT_VERSION' ) && version_compare( '1.1.6', HEATEOR_FB_COM_NOT_VERSION ) < 0 && isset( $heateor_fcn_options['gdpr_enable'] ) ) {
		return '<div class="heateor_ss_fb_comments_notifier_optin_container"><label><input type="checkbox" class="heateor_ss_fb_comments_notifier_optin" value="1" />' . str_replace( $heateor_fcn_options['ppu_placeholder'], '<a href="' . $heateor_fcn_options['privacy_policy_url'] . '" target="_blank">' . $heateor_fcn_options['ppu_placeholder'] . '</a>', wp_strip_all_tags( $heateor_fcn_options['privacy_policy_optin_text'] ) ) . '</label></div>';
	}
	return '';
}

/**
 * Script for GDPR optin of Facebook Comments Moderation add-on
 */
function heateor_sc_facebook_comments_moderation_optin_script() {
	if ( defined( 'HEATEOR_FB_COM_MOD_VERSION' ) && version_compare( '1.2.3', HEATEOR_FB_COM_MOD_VERSION ) < 0 ) {
		return '<script type="text/javascript">jQuery(window).load(function(){null!=heateorFcmGetCookie("heateorFcmOptin")&&jQuery("input.heateor_ss_fb_comments_optin").prop("checked",!0),jQuery("input.heateor_ss_fb_comments_optin").click(function(){if(jQuery(this).is(":checked")){if(heateorFcmOptin=1,null==heateorFcmGetCookie("heateorFcmOptin")){var e=new Date;e.setTime(e.getTime()+31536e6),document.cookie="heateorFcmOptin=1; expires="+e.toUTCString()+"; path=/"}}else heateorFcmOptin=0,document.cookie="heateorFcmOptin=; expires=Fri, 02 Jan 1970 00:00:00 UTC; path=/"});});</script>';
	}
	return '';
}

/**
 * Script for GDPR optin of Facebook Comments Notifier add-on
 */
function heateor_sc_facebook_comments_notifier_optin_script() {
	if ( defined( 'HEATEOR_FB_COM_NOT_VERSION' ) && version_compare( '1.1.5', HEATEOR_FB_COM_NOT_VERSION ) < 0 ) {
	    return '<script type="text/javascript">jQuery(window).load(function(){null!=heateorFcnGetCookie("heateorFcnOptin")&&jQuery("input.heateor_ss_fb_comments_notifier_optin").prop("checked",!0),jQuery("input.heateor_ss_fb_comments_notifier_optin").click(function(){if(jQuery(this).is(":checked")){if(heateorFcnOptin=1,null==heateorFcnGetCookie("heateorFcnOptin")){var e=new Date;e.setTime(e.getTime()+31536e6),document.cookie="heateorFcnOptin=1; expires="+e.toUTCString()+"; path=/"}}else heateorFcnOptin=0,document.cookie="heateorFcnOptin=; expires=Fri, 02 Jan 1970 00:00:00 UTC; path=/"});});</script>';
	}
	return '';
}

/**
 * Render WordPress Comments
 */
function heateor_sc_render_wp_comments( $file ) {
	ob_start();
	if ( file_exists( $file ) ) {
		require $file;
	} elseif ( file_exists( TEMPLATEPATH . $file ) ) {
		require( TEMPLATEPATH . $file );
	} elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/comments.php' ) ) {
		require( ABSPATH . WPINC . '/theme-compat/comments.php');
	}
	return ob_get_clean();
}

/**
 * Stylesheets to load at front end.
 */
function heateor_sc_frontend_styles() {
	global $heateor_sc_options;
	if(isset($heateor_sc_options['css']) && $heateor_sc_options['css']){
		?>
		<style type="text/css"><?php echo $heateor_sc_options['css'] ?></style>
		<?php
	}
	wp_enqueue_style( 'heateor-sc-frontend-css', plugins_url( 'css/front.css', __FILE__ ), false, HEATEOR_SOCIAL_COMMENTS_VERSION );
}

/**
 * Create plugin menu in admin.
 */	
function heateor_sc_create_admin_menu() {
	$options_page = add_menu_page( 'Heateor - Social Comments', '<b>Social Comments</b>', 'manage_options', 'heateor-sc', 'heateor_sc_option_page', plugins_url( 'images/logo.png', __FILE__ ) );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_sc_admin_scripts' );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_sc_admin_style' );
	add_action( 'admin_print_scripts-' . $options_page, 'heateor_sc_fb_sdk_script' );
}
add_action( 'admin_menu', 'heateor_sc_create_admin_menu' );

/**
 * Include javascript files in admin.
 */	
function heateor_sc_admin_scripts(){
	?>
	<script>var heateorScWebsiteUrl = '<?php echo site_url() ?>', heateorScHelpBubbleTitle = "<?php echo __( 'Click to toggle help', 'heateor-social-comments' ) ?>"; </script>
	<?php
	wp_enqueue_script( 'heateor_sc_admin_scripts', plugins_url( 'js/admin/admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-sortable' ), HEATEOR_SOCIAL_COMMENTS_VERSION );
}

/**
 * Include CSS files in admin.
 */	
function heateor_sc_admin_style(){
	wp_enqueue_style( 'heateor_sc_admin_style', plugins_url( 'css/admin.css', __FILE__ ), false, HEATEOR_SOCIAL_COMMENTS_VERSION );
}

/**
 * Include Javascript SDK in admin.
 */	
function heateor_sc_fb_sdk_script(){
	wp_enqueue_script( 'heateor_sc_fb_sdk_script', plugins_url( 'js/admin/fb_sdk.js', __FILE__ ), false, HEATEOR_SOCIAL_COMMENTS_VERSION );
}

function heateor_sc_plugin_settings_fields() {
	register_setting( 'heateor_sc_options', 'heateor_sc', 'heateor_sc_validate_options' );
	// show option to disable sharing on particular page/post
	if ( current_user_can( 'manage_options' ) ) {
		$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
		if ( count( $post_types ) ) {
			foreach( $post_types as $type ) {
				add_meta_box( 'heateor_sc_meta', 'Heateor Social Comments', 'heateor_sc_comments_meta_setup', $type );
		}
	}
		// save sharing meta on post/page save
		add_action( 'save_post', 'heateor_sc_save_comments_meta' );
	}
}
add_action( 'admin_init', 'heateor_sc_plugin_settings_fields' );

/**
 * Show social comments meta options
 */
function heateor_sc_comments_meta_setup(){
	global $post;
	$post_type = $post->post_type;
	$comments_meta = get_post_meta( $post->ID, '_heateor_sc_meta', true );
	?>
	<p>
		<label for="heateor_sc_comments">
			<input type="checkbox" name="_heateor_sc_meta[disable_comments]" id="heateor_sc_comments" value="1" <?php echo is_array( $comments_meta ) && isset( $comments_meta['disable_comments'] ) && $comments_meta['disable_comments'] == '1' ? 'checked' : ''; ?> />
			<?php _e( 'Disable Social Comments on this '.$post_type, 'heateor-social-comments' ) ?>
		</label>
	</p>
	<?php
    echo '<input type="hidden" name="heateor_sc_meta_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
}

/**
 * Save social comments meta fields.
 */
function heateor_sc_save_comments_meta( $post_id ) {
    // make sure data came from our meta box
    if ( ! isset( $_POST['heateor_sc_meta_nonce'] ) || ! wp_verify_nonce( $_POST['heateor_sc_meta_nonce'], __FILE__ ) ) {
		return $post_id;
 	}
    // Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	// Return if it's a post revision
	if ( false !== wp_is_post_revision( $post_id ) ) return;
    
    // check user permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

    if ( isset( $_POST['_heateor_sc_meta'] ) && is_array( $_POST['_heateor_sc_meta'] ) && isset( $_POST['_heateor_sc_meta']['disable_comments'] ) ) {
		foreach ( $_POST['_heateor_sc_meta'] as $key => $value ) {
			unset( $_POST['_heateor_sc_meta'][$key] );
		}
		$_POST['_heateor_sc_meta']['disable_comments'] = '1';
		$options = $_POST['_heateor_sc_meta'];
	} else {
		$options = array();
	}
	update_post_meta( $post_id, '_heateor_sc_meta', $options );

    return $post_id;
}

/**
 * Display notification message when plugin options are saved
 */
function heateor_sc_settings_saved_notification() {
	if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
		return '<div class="notice notice-success is-dismissible"><p><strong>' . __( 'Settings saved', 'heateor-social-comments' ) . '</strong></p></div>';
	}
}

/**
 * Plugin options page.
 */	
function heateor_sc_option_page() {
	global $heateor_sc_options;
	echo heateor_sc_settings_saved_notification();
	require 'admin/plugin-options.php';
}

/** 
 * Validate plugin options
 */ 
function heateor_sc_validate_options( $options ) {
	foreach( $options as $k => $v ) {
		if( is_string( $v ) ) {
			$options[$k] = trim( esc_attr( $v ) );
		} elseif( trim( $v ) == '' ) {
			unset( $options[$k] );
		}
	}
	return $options;
}

/**
 * When plugin is activated
 */
function heateor_sc_save_default_options() {
	// default options
	add_option( 'heateor_sc', array(
	   'commenting_layout' => 'tabbed',
	   'commenting_label' => 'Leave a Reply',
	   'commenting_order' => 'wordpress,facebook,vkontakte,disqus',
	   'enable_post' => '1',
	   'enable_page' => '1',
	   'enable_wordpresscomments' => '1',
	   'label_wordpress_comments' => 'Default Comments',
	   'enable_wordpressicon' => '1',
	   'enable_facebookcomments' => '1',
	   'fb_app_id' => '',
	   'label_facebook_comments' => 'Facebook Comments',
	   'enable_facebookicon' => '1',
	   'comment_lang' => get_locale(),
	   'vk_app_id' => '',
	   'label_vkontakte_comments' => 'Vkontakte Comments',
	   'enable_vkontakteicon' => '1',
	   'vkcomment_numposts' => '5',
	   'label_disqus_comments' => 'Disqus Comments',
	   'enable_disqusicon' => '1',
	   'delete_options' => '1'
	) );

	add_option( 'heateor_sc_version', HEATEOR_SOCIAL_COMMENTS_VERSION );
}

/**
 * Plugin activation function
 */
function heateor_sc_activate_plugin($network_wide){
	global $wpdb;
	if(function_exists('is_multisite') && is_multisite()){
		//check if it is network activation if so run the activation function for each id
		if($network_wide){
			$old_blog =  $wpdb->blogid;
			//Get all blog ids
			$blog_ids =  $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

			foreach($blog_ids as $blog_id){
				switch_to_blog($blog_id);
				heateor_sc_save_default_options();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	heateor_sc_save_default_options();
}
register_activation_hook(__FILE__, 'heateor_sc_activate_plugin');

/**
 * Save default options for the new subsite created
 */
function heateor_sc_new_subsite_default_options($blog_id, $user_id, $domain, $path, $site_id, $meta){
    if(is_plugin_active_for_network('heateor-social-comments/heateor-social-comments.php')){ 
        switch_to_blog($blog_id);
        heateor_sc_save_default_options();
        restore_current_blog();
    }
}
add_action('wpmu_new_blog', 'heateor_sc_new_subsite_default_options', 10, 6);

/**
 * Upgate database and plugin version based on version check
 */
function heateor_sc_update_db_check(){
	$currentVersion = get_option('heateor_sc_version');

	if($currentVersion && $currentVersion != HEATEOR_SOCIAL_COMMENTS_VERSION){
		if(version_compare('1.5.2', $currentVersion) > 0){
			$pluginOptions = get_option('heateor_sc');
			if ( ! $pluginOptions['vkcomment_numposts'] ) {
			    $pluginOptions['vkcomment_numposts'] = '5';
			}
			update_option('heateor_sc', $pluginOptions);
		}
		if(version_compare('1.5', $currentVersion) > 0){
			$pluginOptions = get_option('heateor_sc');
			if ( $pluginOptions['commenting_order'] ) {
			    $pluginOptions['commenting_order'] .= ",vkontakte";
			}
			update_option('heateor_sc', $pluginOptions);
		}
		if(version_compare('1.4.16', $currentVersion) > 0){
			$pluginOptions = get_option('heateor_sc');
			$pluginOptions['commenting_order'] = str_replace(',googleplus', '', $pluginOptions['commenting_order']);
			$pluginOptions['commenting_order'] = str_replace('googleplus,', '', $pluginOptions['commenting_order']);
			update_option('heateor_sc', $pluginOptions);
		}
		if(version_compare('1.4.4', $currentVersion) > 0){
			$pluginOptions = get_option('heateor_sc');
			$pluginOptions['fb_app_id'] = '';
			update_option('heateor_sc', $pluginOptions);
		}

		update_option('heateor_sc_version', HEATEOR_SOCIAL_COMMENTS_VERSION);
	}
}
add_action('plugins_loaded', 'heateor_sc_update_db_check');

/**
 * Show "Settings" link below plugin name at Plugins page
 */
function heateor_sc_place_settings_link($links){	
	$addons_link = '</br><a href="https://www.heateor.com/add-ons" target="_blank">' . __('Add-Ons', 'heateor-social-comments') . '</a>';
    $support_link = '<a href="http://support.heateor.com" target="_blank">' . __('Support Documentation', 'heateor-social-comments') . '</a>';
	$settings_link = '<a href="admin.php?page=heateor-sc">' . __('Settings', 'heateor-social-comments') . '</a>';
	// place it before other links
	array_unshift($links, $settings_link);
	$links[] = $addons_link;
	$links[] = $support_link;

	return $links;
}
add_filter('plugin_action_links_heateor-social-comments/heateor-social-comments.php', 'heateor_sc_place_settings_link');

/**
 * Set flag in database if browser message notification has been read
 */
function heateor_sc_plugin_notification_read() {
	update_option( 'heateor_sc_plugin_notification_read', '1' );
	die;
}
add_action( 'wp_ajax_heateor_sc_plugin_notification_read', 'heateor_sc_plugin_notification_read' );

/**
 * Show plugin/add-on update notifications
 */
function heateor_sc_addon_update_notification() {
	if(current_user_can('manage_options')){
		if(defined('HEATEOR_FB_COM_MOD_VERSION') && version_compare('1.2.4', HEATEOR_FB_COM_MOD_VERSION) > 0){
			?>
			<div class="error notice">
				<h3>Facebook Comments Moderation</h3>
				<p><?php _e('Update "Facebook Comments Moderation" add-on for compatibility with current version of Heateor Social Comments', 'heateor-social-comments') ?></p>
			</div>
			<?php
		}

		if(defined('HEATEOR_FB_COM_NOT_VERSION') && version_compare('1.1.6', HEATEOR_FB_COM_NOT_VERSION) > 0){
			?>
			<div class="error notice">
				<h3>Facebook Comments Notifier</h3>
				<p><?php _e('Update "Facebook Comments Notifier" add-on for compatibility with current version of Heateor Social Comments', 'heateor-social-comments') ?></p>
			</div>
			<?php
		}

		$currentVersion = get_option('heateor_sc_version');

		if(version_compare('1.4.13', $currentVersion) <= 0){
			if(!get_option('heateor_sc_gdpr_notification_read')){
				?>
				<script type="text/javascript">
				function heateorScGDPRNotificationRead(){
					jQuery.ajax({
						type: 'GET',
						url: '<?php echo get_admin_url() ?>admin-ajax.php',
						data: {
							action: 'heateor_sc_gdpr_notification_read'
						},
						success: function(data, textStatus, XMLHttpRequest){
							jQuery('#heateor_sc_gdpr_notification').fadeOut();
						}
					});
				}
				</script>
				<div id="heateor_sc_gdpr_notification" class="update-nag">
					<h3>Heateor Social Comments</h3>
					<p><?php echo sprintf(__('This plugin is GDPR compliant. You need to update the privacy policy of your website regarding the personal data this plugin saves, as mentioned <a href="%s" target="_blank">here</a>', 'heateor-social-comments'), 'http://support.heateor.com/gdpr-and-our-plugins'); ?><input type="button" onclick="heateorScGDPRNotificationRead()" style="margin-left: 5px;" class="button button-primary" value="<?php _e('Okay', 'heateor-social-comments') ?>" /></p>
				</div>
				<?php
			}
		}
	}
}
add_action('admin_notices', 'heateor_sc_addon_update_notification');

/**
 * Set flag in database if GDPR notification has been read
 */
function heateor_sc_gdpr_notification_read(){
	update_option('heateor_sc_gdpr_notification_read', '1');
	die;
}
add_action('wp_ajax_heateor_sc_gdpr_notification_read', 'heateor_sc_gdpr_notification_read');

/**
 * Show notification in admin area
 */
function heateor_sc_plugin_notification() {
	if ( current_user_can( 'manage_options' ) ) {
		global $heateor_sc_options;
		if ( ! get_option( 'heateor_sc_plugin_notification_read') && ! isset( $heateor_sc_options['enable_wordpresscomments'] ) && isset( $heateor_sc_options['enable_facebookcomments'] ) && ! isset( $heateor_sc_options['enable_disquscomments'] ) ) {
			?>
			<script type="text/javascript">
			function heateorScBrowserNotificationRead(){
				jQuery.ajax({
					type: 'GET',
					url: '<?php echo get_admin_url() ?>admin-ajax.php',
					data: {
						action: 'heateor_sc_plugin_notification_read'
					},
					success: function(data, textStatus, XMLHttpRequest){
						jQuery('#heateor_sc_plugin_notification').fadeOut();
					}
				});
			}
			</script>
			<div id="heateor_sc_plugin_notification" class="update-nag">
				<h3>Heateor Social Comments</h3>
				<p><?php echo sprintf( __( 'As you are using only Facebook Comments feature of this plugin, you should switch to <a href="%s" target="_blank">Fancy Facebook Comments</a> for better performance', 'heateor-social-comments' ), 'https://wordpress.org/plugins/fancy-facebook-comments' ); ?><a href="https://wordpress.org/plugins/fancy-facebook-comments" target="_blank"><input type="button" style="margin-left: 5px;" class="button button-primary" value="<?php _e( 'Okay', 'heateor-social-comments' ) ?>" /></a><input type="button" onclick="heateorScBrowserNotificationRead()" style="margin-left: 5px;" class="button" value="<?php _e( 'Later', 'heateor-social-comments' ) ?>" /></p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'heateor_sc_plugin_notification' );

/**
 * Render Vkontakte comments
 */
function heateor_sc_render_vk_comments() { 
	global $heateor_sc_options;

	if ( isset( $heateor_sc_options['vkurlToComment'] ) && $heateor_sc_options['vkurlToComment'] != '' ) {
		$url = $heateor_sc_options['vkurlToComment'];
	} else {
		$url = heateor_sc_get_current_page_url();
	}

	$commentingHtml = '<script type="text/javascript" src="https://vk.com/js/api/openapi.js?162">';
	$commentingHtml .= '</script>';

    $commentingHtml .= '<script type="text/javascript">VK.init({apiId:' . ( $heateor_sc_options['vk_app_id'] ? $heateor_sc_options['vk_app_id'] : '""' ) . ', onlyWidgets: true});</script>';

  	$commentingHtml .= '';

  	$commentingHtml .= '<div style="clear:both"></div><div id="vk_comments"></div><div style="clear:both"></div>';
	$commentingHtml .= '<script type="text/javascript">VK.Widgets.Comments("vk_comments", {limit:' . ($heateor_sc_options['vkcomment_numposts'] ? $heateor_sc_options['vkcomment_numposts'] : 5) . ', width: "' . $heateor_sc_options['vkcomment_width'] . '", attach: false, pageUrl: "' . $url . '" });';
	$commentingHtml .= '</script>';
	return $commentingHtml;
}
