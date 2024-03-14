<?php
/**
 * Plugin Name: Worth The Read
 * Plugin URI: http://www.welldonemarketing.com
 * Description: Adds read length progress bar to single posts and pages, as well as an optional reading time commitment label to post titles.
 * Version: 1.14.2
 * Author: Well Done Marketing
 * Author URI: http://www.welldonemarketing.com
 * License: GPL2
 */

// Load the embedded Redux Framework
if ( file_exists( dirname( __FILE__ ).'/options/framework.php' ) ) {
    require_once dirname(__FILE__).'/options/framework.php';
}

// Load the plugin settings
if ( file_exists( dirname( __FILE__ ).'/wtr-config.php' ) ) {
    require_once dirname(__FILE__).'/wtr-config.php';
}

// add custom metaboxes
function wtr_custom_meta() {
    $options = get_option( 'wtr_settings' );
    $types_builtin = is_array($options['progress-display']) ? $options['progress-display'] : array();
	$types_cpts = array();
	$types_cpts_manual = array();
	if(isset($options['progress-cpts'])) {
		if(is_array($options['progress-cpts'])) $types_cpts = $options['progress-cpts'];
	}
	if(isset($options['progress-cpts-manual'])) {
		$val = preg_replace('/\s+/', '', $options['progress-cpts-manual']);
		if(strpos($val, ',') !== false) {
			$types_cpts_manual = explode(',', $val);
		} else {
			$types_cpts_manual = array($val);
		}
	}
	$types = array_merge($types_builtin, $types_cpts, $types_cpts_manual);
	if(isset($types)) {
		if(is_array($types)) {
		    foreach ($types as $type) {
		        add_meta_box(
		            'wtr_custom',           // Unique ID
		            'Worth The Read',  		// Box title
		            'wtr_custom_html',  	// Content callback, must be of type callable
		            $type,                // Post type
		            'side',					// Context
		            'default'				// Priority
		        );
		    }
		}
	}
}
add_action('add_meta_boxes', 'wtr_custom_meta');

function wtr_custom_html($post) {
	wp_nonce_field( basename( __FILE__ ), 'wtr_nonce' );
    $wtr_stored_meta = get_post_meta( $post->ID );
    ?>
    <p><label class="post-attributes-label" for="menu_order">Disable:</label></p>
    <p>
    <label for="wtr-disable-reading-progress">
        <input type="checkbox" name="wtr-disable-reading-progress" id="wtr-disable-reading-progress" value="yes" <?php if ( isset ( $wtr_stored_meta['wtr-disable-reading-progress'] ) ) checked( $wtr_stored_meta['wtr-disable-reading-progress'][0], 'yes' ); ?> />
        <?php _e( 'Reading progress bar', 'worth-the-read' )?>
    </label>
    </p>
    <p>
    <label for="wtr-disable-time-commitment">
        <input type="checkbox" name="wtr-disable-time-commitment" id="wtr-disable-time-commitment" value="yes" <?php if ( isset ( $wtr_stored_meta['wtr-disable-time-commitment'] ) ) checked( $wtr_stored_meta['wtr-disable-time-commitment'][0], 'yes' ); ?> />
        <?php _e( 'Time commitment label', 'worth-the-read' )?>
    </label>
	</p>
	<p>
    <label class="post-attributes-label" for="wtr-custom-time-label">Time Commitment Format:</label>
    <input style="width:100%" type="text" name="wtr-custom-time-label" id="wtr-custom-time-label" value="<?php if ( isset ( $wtr_stored_meta['wtr-custom-time-label'] ) ) echo $wtr_stored_meta['wtr-custom-time-label'][0]; ?>" /><br />
	</p>
	<p class="howto"><?php _e( 'Use # as a placeholder for the number. Example: "# min read" becomes "12 min read"', 'worth-the-read' )?></p>
    <?php
}

// save the custom meta
function wtr_meta_save( $post_id ) {
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'wtr_nonce' ] ) && wp_verify_nonce( $_POST[ 'wtr_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'wtr-disable-reading-progress' ] ) ) {
	    update_post_meta( $post_id, 'wtr-disable-reading-progress', 'yes' );
	} else {
	    update_post_meta( $post_id, 'wtr-disable-reading-progress', '' );
	}
	 
	// Checks for input and saves
	if( isset( $_POST[ 'wtr-disable-time-commitment' ] ) ) {
	    update_post_meta( $post_id, 'wtr-disable-time-commitment', 'yes' );
	} else {
	    update_post_meta( $post_id, 'wtr-disable-time-commitment', '' );
	}

	// Save the custom time commitment text field
	if ( array_key_exists( 'wtr-custom-time-label', $_POST ) ) {
        update_post_meta( $post_id, 'wtr-custom-time-label', sanitize_text_field( $_POST['wtr-custom-time-label'] ) );
    }

}
add_action( 'save_post', 'wtr_meta_save' );


# load front-end assets
add_action( 'wp_enqueue_scripts', 'wtr_enqueued_assets' );
function wtr_enqueued_assets() {
	# don't load js and css on homepage unless this is set to display there
	$options = get_option( 'wtr_settings' );
	$types = is_array($options['progress-display']) ? $options['progress-display'] : array();
	$load_scripts = true;
	if(is_front_page() && !in_array('home', $types)) {
		$load_scripts = false;
		wtr_debug('scripts not loaded');
	}
	if($load_scripts) {
		wp_enqueue_script( 'wtr-js', plugin_dir_url( __FILE__ ) . 'js/wtr.js', array( 'jquery' ), false, true );
		wp_enqueue_style( 'wtr-css', plugin_dir_url( __FILE__ ) . 'css/wtr.css', false, false, 'all');
		wtr_debug('scripts loaded');
	}
}

# wrap content in div with php variables in data attributes
add_filter( 'the_content', 'wtr_wrap_content', 10, 2 ); 
function wtr_wrap_content( $content ) { 
	global $post;
	wtr_debug('wtr_wrap_content() called');
	$options = get_option( 'wtr_settings' );
	$placement = $options['progress-placement'];
	$placement_offset = empty($options['progress-offset']) ? 0 : $options['progress-offset'];
	$content_offset = empty($options['content-offset']) ? 0 : $options['content-offset'];
	$placement_touch = $options['progress-placement-touch'];
	$placement_offset_touch = empty($options['progress-offset-touch']) ? 0 : $options['progress-offset-touch'];
	$width = $options['progress-thickness'];
	$fgopacity = $options['progress-foreground-opacity'];
	$mutedopacity = $options['progress-muted-opacity'];
	$mute = isset($options['progress-fixed-opacity']) ? $options['progress-fixed-opacity'] : '';
	$transparent = isset($options['progress-transparent-background']) ? $options['progress-transparent-background'] : '';
	$shadow = isset($options['progress-shadow']) ? $options['progress-shadow'] : '';
	$touch = isset($options['progress-touch']) ? $options['progress-touch'] : '';
	$non_touch = isset($options['progress-non-touch']) ? $options['progress-non-touch'] : '';
	$rtl = isset($options['progress-rtl']) ? $options['progress-rtl'] : '';
	if(is_object($post)) {
		$comments = get_comment_count($post->ID)['approved'] > 0 ? $options['progress-comments'] : 0;
	} else {
		$comments = 0;
	}
	$comments_bg = $options['progress-comments-background'];
	$fg = $options['progress-foreground'];
	$bg = $options['progress-background'];
	$fg_muted = $options['progress-muted-foreground'];
	$fg_end = $options['progress-end-foreground'];
	$types_builtin = is_array($options['progress-display']) ? $options['progress-display'] : array();
	$types_cpts = array();
	$types_cpts_manual = array();
	if(isset($options['progress-cpts'])) {
		if(is_array($options['progress-cpts'])) $types_cpts = $options['progress-cpts'];
	}
	if(isset($options['progress-cpts-manual'])) {
		$val = preg_replace('/\s+/', '', $options['progress-cpts-manual']);
		if(strpos($val, ',') !== false) {
			$types_cpts_manual = explode(',', $val);
		} else {
			$types_cpts_manual = array($val);
		}
	}
	$types = array_merge($types_builtin, $types_cpts, $types_cpts_manual);
	// override with single post/page settings
	$disable = false;
	if(is_object($post)) {
		$disable = get_post_meta($post->ID, 'wtr-disable-reading-progress', true);
	}
	// determine if progress bar should be displayed
	$display = false;
	if(!empty($types) && is_singular($types) && !$disable) {
		$display = true;
	} else {
		// no general types are active, check for specific posts or pages
		$posts_manual = is_array($options['progress-posts-manual']) ? $options['progress-posts-manual'] : array();
		$pages_manual = is_array($options['progress-pages-manual']) ? $options['progress-pages-manual'] : array();
		if(!empty($posts_manual) && is_single($posts_manual)) {
			$display = true;
		}
		if(!empty($pages_manual) && is_page($pages_manual)) {
			$display = true;
		}
	}
	// display it
	if ($display) {
		$content = '<div id="wtr-content" 
	    	data-bg="' . $bg . '" 
	    	data-fg="' . $fg . '" 
	    	data-width="' . $width . '" 
	    	data-mute="' . $mute . '" 
	    	data-fgopacity="' . $fgopacity . '" 
	    	data-mutedopacity="' . $mutedopacity . '" 
	    	data-placement="' . $placement . '" 
	    	data-placement-offset="' . $placement_offset . '" 
	    	data-content-offset="' . $content_offset . '" 
	    	data-placement-touch="' . $placement_touch . '" 
		    data-placement-offset-touch="' . $placement_offset_touch . '" 
	    	data-transparent="' . $transparent . '" 
	    	data-shadow="' . $shadow . '" 
	    	data-touch="' . $touch . '" 
	    	data-non-touch="' . $non_touch . '" 
	    	data-comments="' . $comments . '" 
	    	data-commentsbg="' . $comments_bg . '" 
	    	data-location="page" 
	    	data-mutedfg="' . $fg_muted . '" 
	    	data-endfg="' . $fg_end . '" 
	    	data-rtl="' . $rtl . '" 
	    	>' . $content . '</div>';
	    wtr_debug('the_content() wrapped');
	} else {
		wtr_debug('the_content() not wrapped');
	}
	return $content;
}

# homepage start div
add_action( 'wp_body_open', 'wtr_wrap_home', 10, 2 ); 
function wtr_wrap_home() {
	global $post;
	wtr_debug('wtr_wrap_home() called');
	$options = get_option( 'wtr_settings' );
	$placement = $options['progress-placement'];
	$placement_offset = empty($options['progress-offset']) ? 0 : $options['progress-offset'];
	$content_offset = empty($options['content-offset']) ? 0 : $options['content-offset'];
	$placement_touch = $options['progress-placement-touch'];
	$placement_offset_touch = empty($options['progress-offset-touch']) ? 0 : $options['progress-offset-touch'];
	$width = $options['progress-thickness'];
	$fgopacity = $options['progress-foreground-opacity'];
	$mutedopacity = $options['progress-muted-opacity'];
	$mute = isset($options['progress-fixed-opacity']) ? $options['progress-fixed-opacity'] : '';
	$transparent = isset($options['progress-transparent-background']) ? $options['progress-transparent-background'] : '';
	$shadow = isset($options['progress-shadow']) ? $options['progress-shadow'] : '';
	$touch = isset($options['progress-touch']) ? $options['progress-touch'] : '';
	$non_touch = isset($options['progress-non-touch']) ? $options['progress-non-touch'] : '';
	$rtl = isset($options['progress-rtl']) ? $options['progress-rtl'] : '';
	if(is_object($post)) {
		$comments = get_comment_count($post->ID)['approved'] > 0 ? $options['progress-comments'] : 0;
	} else {
		$comments = 0;
	}
	$comments_bg = $options['progress-comments-background'];
	$fg = $options['progress-foreground'];
	$bg = $options['progress-background'];
	$fg_muted = $options['progress-muted-foreground'];
	$fg_end = $options['progress-end-foreground'];
	if(wtr_should_wrap_home()) {
		echo '<div id="wtr-content" 
		    	data-bg="' . $bg . '" 
		    	data-fg="' . $fg . '" 
		    	data-width="' . $width . '" 
		    	data-mute="' . $mute . '" 
		    	data-fgopacity="' . $fgopacity . '" 
		    	data-mutedopacity="' . $mutedopacity . '" 
		    	data-placement="' . $placement . '" 
		    	data-placement-offset="' . $placement_offset . '" 
		    	data-content-offset="' . $content_offset . '" 
		    	data-placement-touch="' . $placement_touch . '" 
		    	data-placement-offset-touch="' . $placement_offset_touch . '" 
		    	data-transparent="' . $transparent . '" 
		    	data-shadow="' . $shadow . '" 
		    	data-touch="' . $touch . '" 
		    	data-non-touch="' . $non_touch . '" 
		    	data-comments="' . $comments . '" 
		    	data-commentsbg="' . $comments_bg . '" 
		    	data-location="home" 
		    	data-mutedfg="' . $fg_muted . '" 
		    	data-endfg="' . $fg_end . '" 
		    	data-rtl="' . $rtl . '" 
		    	></div>';
	}
}
# homepage end div
add_action( 'wp_footer', 'wtr_wrap_home_end', 10, 2 ); 
function wtr_wrap_home_end() {
	wtr_debug('wtr_wrap_home_end() called');
	if(wtr_should_wrap_home()) {
		echo do_shortcode('[wtr-end]');
	}
}
# checks to see if wtr should display on the homepage
function wtr_should_wrap_home() {
	global $post;
	$options = get_option( 'wtr_settings' );
	$types_home = false;
	if(isset($options['progress-display'])) {
		if(is_array($options['progress-display'])) {
			if(in_array('home', $options['progress-display'])) {
				$types_home = true;
			}
		}
	}
	// override with single post/page settings
	if(is_object($post)) {
		$disable = get_post_meta($post->ID, 'wtr-disable-reading-progress', true);
	} else {
		$disable = false;
	}
	# only do this if the home page is not showing a static page
	# because this would fall under the "page" post type instead
	if(is_front_page() && is_home() && $types_home && !$disable) {
		wtr_debug('wtr_should_wrap_home() returns true');
		return true;
	} else {
		wtr_debug('wtr_should_wrap_home() returns false');
		return false;
	}
}

# adds a handle to where the progress bar should end
function wtr_end() {
	wtr_debug('wtr_end() called');
	return '<span class="wtr-end"></span>';
}
# create the end progress shortcode
add_shortcode( 'wtr-end', 'wtr_end' );

# wrap comments in div so we can get ahold of a total comment section height
# one of these two actions will usually run, but never at the same time
add_action( 'comment_form_after', 'wtr_wrap_comments' );
add_action( 'comment_form_closed', 'wtr_wrap_comments' );
function wtr_wrap_comments() {
	global $post;
	wtr_debug('wtr_wrap_comments() called');
	if(is_object($post)) {
		if(get_comment_count($post->ID)['approved'] > 0) {
			echo '<div id="wtr-comments-end"></div>';
			wtr_debug('wtr-comments-end div added after comments');
		} else {
			wtr_debug('wtr-comments-end div not added after comments');
		}
	}
}
# if the theme doesn't use either of those actions, try another one
if(has_action( 'comment_form_after', 'wtr_wrap_comments' ) === false && has_action( 'comment_form_closed', 'wtr_wrap_comments' ) === false) {
	add_action( 'wp_footer', 'wtr_wrap_comments_footer' );
}

function wtr_wrap_comments_footer() {
	global $post;
	wtr_debug('wtr_wrap_comments_footer() called');
	# don't add this on homepage unless this is set to display there
	$options = get_option( 'wtr_settings' );
	$types_home = false;
	if(isset($options['progress-display'])) {
		if(is_array($options['progress-display'])) {
			if(in_array('home', $options['progress-display'])) {
				$types_home = true;
			}
		}
	}
	$show_div = true;
	if(is_front_page() && !$types_home) {
		$show_div = false;
	}
	if(is_object($post)) {
		if(get_comment_count($post->ID)['approved'] > 0 && $show_div) {
			echo '<div id="wtr-comments-end" class="at-footer"></div>';
			wtr_debug('wtr-comments-end div added to footer');
		} else {
			wtr_debug('wtr-comments-end div not added to footer');
		}
	}
}

# time commitment placement
add_action('loop_start','wtr_conditional_title');
function wtr_conditional_title($query){
	global $wp_query;
	if($query === $wp_query) {
		add_filter( 'the_title', 'wtr_filter_title', 10, 2);
		wtr_debug('wtr_filter_title() added');
	} else {
		remove_filter( 'the_title', 'wtr_filter_title', 10, 2);
		wtr_debug('wtr_filter_title() removed');
	}
}
function wtr_filter_title( $title, $post_id = NULL ) {
	if($post_id==NULL) return false;
	$options = get_option( 'wtr_settings' );
	$types_builtin = is_array($options['time-display']) ? $options['time-display'] : array();
	$types_cpts = array();
	$types_cpts_manual = array();
	if(isset($options['time-cpts'])) {
		if(is_array($options['time-cpts'])) $types_cpts = $options['time-cpts'];
	}
	if(isset($options['time-cpts-manual'])) {
		$val = preg_replace('/\s+/', '', $options['time-cpts-manual']);
		if(strpos($val, ',') !== false) {
			$types_cpts_manual = explode(',', $val);
		} else {
			$types_cpts_manual = array($val);
		}
	}
	$types = array_merge($types_builtin, $types_cpts, $types_cpts_manual);
	$placement = $options['time-placement'];
    global $post;
    if(is_object($post)) {
	    if($post->ID == $post_id && in_the_loop()) {
			// determine if reading time should be displayed
			$display = false;
			if((in_array('archives', $types) || is_singular($types)) && !empty($types)) {
				$display = true;
			} else {
				// no general types are active, check for specific posts or pages
				$posts_manual = is_array($options['time-posts-manual']) ? $options['time-posts-manual'] : array();
				$pages_manual = is_array($options['time-pages-manual']) ? $options['time-pages-manual'] : array();
				if(!empty($posts_manual) && is_single($posts_manual)) {
					$display = true;
				}
				if(!empty($pages_manual) && is_page($pages_manual)) {
					$display = true;
				}
			}
	    	if($display) {
	    	    if($placement=='before-title') {
	    	    	$title = wtr_time_commitment() . $title;
	    	    	wtr_debug('wtr_time_commitment() placed before title');
	    	    }elseif($placement=='after-title') {
	    	    	$title = $title . wtr_time_commitment();
	    	    	wtr_debug('wtr_time_commitment() placed after title');
	    	    }
	    	}
	    }
	} else {
		$title = false;
	}
    return $title;
}
add_filter( 'the_content', 'wtr_filter_content', 10, 2);
function wtr_filter_content( $content ) {
	wtr_debug('wtr_filter_content() called');
	$options = get_option( 'wtr_settings' );
	$types_builtin = is_array($options['time-display']) ? $options['time-display'] : array();
	$types_cpts = array();
	$types_cpts_manual = array();
	if(isset($options['time-cpts'])) {
		if(is_array($options['time-cpts'])) $types_cpts = $options['time-cpts'];
	}
	if(isset($options['time-cpts-manual'])) {
		$val = preg_replace('/\s+/', '', $options['time-cpts-manual']);
		if(strpos($val, ',') !== false) {
			$types_cpts_manual = explode(',', $val);
		} else {
			$types_cpts_manual = array($val);
		}
	}
	$types = array_merge($types_builtin, $types_cpts, $types_cpts_manual);
	$placement = $options['time-placement'];
	// determine if reading time should be displayed
	$display = false;
	if(is_singular($types) && !empty($types)) {
		$display = true;
	} else {
		// no general types are active, check for specific posts or pages
		$posts_manual = is_array($options['time-posts-manual']) ? $options['time-posts-manual'] : array();
		$pages_manual = is_array($options['time-pages-manual']) ? $options['time-pages-manual'] : array();
		if(!empty($posts_manual) && is_single($posts_manual)) {
			$display = true;
		}
		if(!empty($pages_manual) && is_page($pages_manual)) {
			$display = true;
		}
	}
	if($display) {
	    if($placement=='before-content') {
	    	$content = wtr_time_commitment() . $content;
	    	wtr_debug('wtr_time_commitment() placed before content');
	    }
	}
    return $content;
}
add_filter( 'get_the_excerpt', 'wtr_filter_excerpt', 10, 2);
function wtr_filter_excerpt( $excerpt ) {
	wtr_debug('wtr_filter_excerpt() called');
	$options = get_option( 'wtr_settings' );
	$types_builtin = is_array($options['time-display']) ? $options['time-display'] : array();
	$types_cpts = array();
	$types_cpts_manual = array();
	if(isset($options['time-cpts'])) {
		if(is_array($options['time-cpts'])) $types_cpts = $options['time-cpts'];
	}
	if(isset($options['time-cpts-manual'])) {
		$val = preg_replace('/\s+/', '', $options['time-cpts-manual']);
		if(strpos($val, ',') !== false) {
			$types_cpts_manual = explode(',', $val);
		} else {
			$types_cpts_manual = array($val);
		}
	}
	$types = array_merge($types_builtin, $types_cpts, $types_cpts_manual);
	$placement = $options['time-placement'];
	// determine if reading time should be displayed
	$display = false;
	if(in_array('archives', $types) && !empty($types)) {
		$display = true;
	} else {
		// no general types are active, check for specific posts or pages
		$posts_manual = is_array($options['time-posts-manual']) ? $options['time-posts-manual'] : array();
		$pages_manual = is_array($options['time-pages-manual']) ? $options['time-pages-manual'] : array();
		if(!empty($posts_manual) && is_single($posts_manual)) {
			$display = true;
		}
		if(!empty($pages_manual) && is_page($pages_manual)) {
			$display = true;
		}
	}
	if($display) {
	    if($placement=='before-content') {
	    	$excerpt = wtr_time_commitment() . $excerpt;
	    	wtr_debug('wtr_time_commitment() placed before excerpt');
	    }
	}
    return $excerpt;
}

function wtr_time_commitment() {
	$out = '';
	global $post;
	$options = get_option( 'wtr_settings' );
	// override with single post/page settings
	$disable = get_post_meta($post->ID, 'wtr-disable-time-commitment', true);
	$time_format_custom = get_post_meta($post->ID, 'wtr-custom-time-label', true);
	if($disable) return;
	// get raw content with no html tags
	$post_content = get_post_field('post_content', $post->ID );
	$word_count = strip_tags($post_content);
	$count_method = $options['time-method'];
	if($count_method=='space') {
		$word_count = count(preg_split('/\s+/', $word_count));
	} else {
		$word_count = str_word_count($word_count);
	}
	$wpm = empty($options['time-wpm']) ? 200 : $options['time-wpm'];
	$ppm = empty($options['time-ppm']) ? 5 : $options['time-ppm'];
	$figure_count = substr_count($post_content, '<figure');
	$time_length = round($word_count / $wpm + $figure_count / $ppm);
	// minimum read time is 1 minute
	if($time_length == 0) $time_length = 1; 
	$time_format = empty($options['time-format']) ? __('# min read','worth-the-read') : $options['time-format'];
	$time_format_singular = empty($options['time-format-singular']) ? $time_format : $options['time-format-singular'];
	// determine singular or plural version of format
	$time_format_use = $time_length==1 ? $time_format_singular : $time_format;
	// override with a custom format for this specific post/page
	if(!empty($time_format_custom)) $time_format_use = $time_format_custom;
	// create the label markup
	$time_label = str_replace('#', '<span class="wtr-time-number">' . $time_length . '</span>', $time_format_use);
    $time_typography = $options['time-typography'];
    $placement = $options['time-placement'];
    $cssblock = isset($options['time-block-level']) ? $options['time-block-level'] : '';
    $cssblock = $cssblock ? ' block' : '';
	$out .= '<span class="wtr-time-wrap' . $cssblock . ' ' . $placement . '">' . $time_label . '</span>';
	wtr_debug('time label for ' . $post->post_title  . ' (id:' . $post->ID . ') should be: ' . $time_label);
	return $out;
}

# add the custom css to the head
add_action('wp_head','wtr_custom_css');
function wtr_custom_css() {
	wtr_debug('wtr_custom_css() called');
	$options = get_option( 'wtr_settings' );
	$css = $options['time-css'];
	if(!empty($css)) echo '<style type="text/css">' . $css . '</style>';
	$debug = $options['progress-debug'];
	if($debug) {
		add_filter( 'body_class','wtr_body_class' );
		echo '<style type="text/css">
			/* debug bar */
			#wtr-debug-bar {
				position:fixed;
				bottom:0;
				left:0;
				z-index:999999999;
				width:100%;
				background:#000;
				color:red;
				padding:5px 10px;
				height:250px;
				overflow:auto;
			}
			.wtr-debug-label {
				font-size:20px;
				line-height:20px;
				font-weight:bold;
			}
			.wtr-debug-message {
				display:block;
				font-size:16px;
				line-height:16px;
			}
			body.wtr-debug-bar {
				margin-bottom:250px;
			}
		</style>';
	}
}

# clear debug option
add_action( 'plugins_loaded', 'wtr_clear_debug' );
function wtr_clear_debug() {
	update_option('wtr_debug_messages', array());
}

# add debug body class
function wtr_body_class( $classes ) {
    $classes[] = 'wtr-debug-bar';
    return $classes;
}

# add debug bar to the footer
add_action('wp_footer','wtr_debug_bar', 99);
function wtr_debug_bar() {
	$options = get_option( 'wtr_settings' );
	$debug = $options['progress-debug'];
	$msgs = get_option('wtr_debug_messages');
	if($debug) {
		echo '<div id="wtr-debug-bar">';
		echo '<div class="wtr-debug-label">PHP</div>';
		foreach($msgs as $msg) {
			echo '<span class="wtr-debug-message">' . $msg . '</span>';
		}
		echo '<div class="wtr-debug-label">JavaScript</div>';
		echo '</div>';
	}
}

# add message to debug queue
function wtr_debug($msg) {
	$options = get_option( 'wtr_settings' );
	$debug = $options['progress-debug'];
	if($debug) {
		$msgs = get_option('wtr_debug_messages');
		array_push($msgs, $msg);
		update_option('wtr_debug_messages', $msgs);
	}
}

# create the time commitment shortcode
add_shortcode( 'wtr-time', 'wtr_time_commitment' );

# remove redux menu under the tools
add_action( 'admin_menu', 'remove_redux_menu', 12 );
if(!function_exists('remove_redux_menu')) {
	function remove_redux_menu() {
		remove_submenu_page('tools.php','redux-about');
	}
}

?>