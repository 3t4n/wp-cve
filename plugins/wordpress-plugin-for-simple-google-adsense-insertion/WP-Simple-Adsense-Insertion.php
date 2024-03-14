<?php
/*
Plugin Name: WP Simple Adsense Insertion
Version: v2.1
Plugin URI: https://www.tipsandtricks-hq.com/
Author: Tips and Tricks HQ, Ruhul Amin
Author URI: https://www.tipsandtricks-hq.com/
Description: A simple Wordpress plugin to insert Google Adsense ads into posts, pages, sidebars (anywhere on your site).
License: GPLv2
*/

//slug - wpsai

define('WP_ADSENSE_INSERT_PLUGIN_VERSION', '2.1');

/* Handles the in-article ad (if used) */
add_filter('the_content', 'simple_adsense_insert_in_article_ads');
function simple_adsense_insert_in_article_ads($content){
    if(is_single()){
        //Ad code
	$in_article_ad = get_option( 'wp_in_article_ad_code' );
        if(empty($in_article_ad)){
            //In-article ad is not being used.
            return $content;
        }
	$in_article_ad = html_entity_decode( $in_article_ad, ENT_COMPAT );

        //Insert after N paragraphs.
        $insertAfter = 2;

        $closingP = '</p>';
        $contentBlock = explode($closingP, $content);
        foreach($contentBlock as $key => $con){
            if(trim($con)) {
                $contentBlock[$key] .= $closingP;
            }
            if(($key + 1) == $insertAfter){
                $contentBlock[$key] .= $in_article_ad;
            }
        }
        $content = implode('', $contentBlock);
    }
    return $content;
}


/* Handles the post-article ad (if used) */
add_filter('the_content', 'simple_adsense_insert_post_article_ads');
function simple_adsense_insert_post_article_ads($content){
    if(is_single()){
        //Only do this for single posts
	$post_article_ad = get_option( 'wp_post_article_ad_code' );
        if(empty($post_article_ad)){
            //Post-article ad is not being used.
            return $content;
        }
        $post_article_ad = html_entity_decode( $post_article_ad, ENT_COMPAT );
        $spacing = '<p class="wpsai_spacing_before_adsense"></p>';
        return $content . $spacing . $post_article_ad;
    }
    return $content;
}


/* Shortcodes */
function show_ad_camp_1() {
	$ad_camp_encoded_value_1 = get_option( 'wp_ad_camp_1_code' );
	$ad_camp_decoded_value_1 = html_entity_decode( $ad_camp_encoded_value_1, ENT_COMPAT );

	if ( !empty( $ad_camp_decoded_value_1 ) ) {
		$output = " $ad_camp_decoded_value_1";
	}
	return $output;
}
add_shortcode( 'wp_ad_camp_1', 'show_ad_camp_1' );

function show_ad_camp_2() {
	$ad_camp_encoded_value_2 = get_option( 'wp_ad_camp_2_code' );
	$ad_camp_decoded_value_2 = html_entity_decode( $ad_camp_encoded_value_2, ENT_COMPAT );

	if ( !empty( $ad_camp_decoded_value_2 ) ) {
		$output = " $ad_camp_decoded_value_2";
	}
	return $output;
}
add_shortcode( 'wp_ad_camp_2', 'show_ad_camp_2' );

function show_ad_camp_3() {
	$ad_camp_encoded_value_3 = get_option( 'wp_ad_camp_3_code' );
	$ad_camp_decoded_value_3 = html_entity_decode( $ad_camp_encoded_value_3, ENT_COMPAT );

	if ( !empty( $ad_camp_decoded_value_3 ) ) {
		$output = " $ad_camp_decoded_value_3";
	}
	return $output;
}
add_shortcode( 'wp_ad_camp_3', 'show_ad_camp_3' );

function show_ad_camp_4() {
	$ad_camp_encoded_value_4 = get_option( 'wp_ad_camp_4_code' );
	$ad_camp_decoded_value_4 = html_entity_decode( $ad_camp_encoded_value_4, ENT_COMPAT );

	if ( !empty( $ad_camp_decoded_value_4 ) ) {
		$output = " $ad_camp_decoded_value_4";
	}
	return $output;
}
add_shortcode( 'wp_ad_camp_4', 'show_ad_camp_4' );

function show_ad_camp_5() {
	$ad_camp_encoded_value_5 = get_option( 'wp_ad_camp_5_code' );
	$ad_camp_encoded_value_5 = html_entity_decode( $ad_camp_encoded_value_5, ENT_COMPAT );

	if ( !empty( $ad_camp_encoded_value_5 ) ) {
		$output = " $ad_camp_encoded_value_5";
	}
	return $output;
}
add_shortcode( 'wp_ad_camp_5', 'show_ad_camp_5' );

/**
 * The wp_ad_camp_process function is deprecated.
 * New users should use the updated shortcode method.
 */
function wp_ad_camp_process( $content ) {
	if ( strpos( $content, "<!-- wp_ad_camp_1 -->" ) !== FALSE ) {
		$content = preg_replace( '/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content );
		$content = str_replace( '<!-- wp_ad_camp_1 -->', show_ad_camp_1(), $content );
	}
	if ( strpos( $content, "<!-- wp_ad_camp_2 -->" ) !== FALSE ) {
		$content = preg_replace( '/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content );
		$content = str_replace( '<!-- wp_ad_camp_2 -->', show_ad_camp_2(), $content );
	}
	if ( strpos( $content, "<!-- wp_ad_camp_3 -->" ) !== FALSE ) {
		$content = preg_replace( '/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content );
		$content = str_replace( '<!-- wp_ad_camp_3 -->', show_ad_camp_3(), $content );
	}
	if ( strpos( $content, "<!-- wp_ad_camp_4 -->" ) !== FALSE ) {
		$content = preg_replace( '/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content );
		$content = str_replace( '<!-- wp_ad_camp_4 -->', show_ad_camp_4(), $content );
	}
	return $content;
}
add_filter( 'the_content', 'wp_ad_camp_process' );

// Displays Simple Ad Campaign Options menu
function ad_camp_add_option_page() {
	if ( function_exists( 'add_options_page' ) ) {
		add_options_page( 'Simple Adsense Insertion', 'Adsense Insertion', 'manage_options', __FILE__, 'ad_insertion_options_page' );
	}
}

function ad_insertion_options_page() {

        if( !current_user_can('manage_options') ){
            wp_die('You do not have permission to access this settings page.');
        }

	if ( isset( $_POST['info_update'] ) ) {
                $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );
                if ( !wp_verify_nonce( $nonce, 'wpsai_settings_update_nonce' )){
                        wp_die('Error! Nonce Security Check Failed! Go back to the settings menu and save the settings again.');
                }

		$tmpCode1 = htmlentities( stripslashes( $_POST['wp_ad_camp_1_code'] ) , ENT_COMPAT );
		update_option( 'wp_ad_camp_1_code', $tmpCode1 );

		$tmpCode2 = htmlentities( stripslashes( $_POST['wp_ad_camp_2_code'] ) , ENT_COMPAT );
		update_option( 'wp_ad_camp_2_code', $tmpCode2 );

		$tmpCode3 = htmlentities( stripslashes( $_POST['wp_ad_camp_3_code'] ) , ENT_COMPAT );
		update_option( 'wp_ad_camp_3_code', $tmpCode3 );

		$tmpCode4 = htmlentities( stripslashes( $_POST['wp_ad_camp_4_code'] ) , ENT_COMPAT );
		update_option( 'wp_ad_camp_4_code', $tmpCode4 );

		$tmpCode5 = htmlentities( stripslashes( $_POST['wp_ad_camp_5_code'] ) , ENT_COMPAT );
		update_option( 'wp_ad_camp_5_code', $tmpCode5 );

                $tmpCode6 = htmlentities( stripslashes( $_POST['wp_in_article_ad_code'] ) , ENT_COMPAT );
		update_option( 'wp_in_article_ad_code', $tmpCode6 );

                $tmpCode7 = htmlentities( stripslashes( $_POST['wp_post_article_ad_code'] ) , ENT_COMPAT );
		update_option( 'wp_post_article_ad_code', $tmpCode7 );

                echo '<div id="message" class="updated fade"><p><strong>';
		echo 'Options Updated!';
		echo '</strong></p></div>';
	}

?>

	<div class="wrap">
		<h2>Adsense Insertion Options</h2>

                <div style="background:#FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">
		<p>Use this plugin to quickly and easily insert Google Adsense to your posts or pages.</p>
		<p>For information and updates, please visit the <a href="https://www.tipsandtricks-hq.com/wordpress-plugin-for-simple-google-adsense-insertion-170" target="_blank">simple Google Adsense plugin page</a></p>
		</div>

		<br>

	    <form method="post" action="">
	    <input type="hidden" name="info_update" id="info_update" value="true" />
            <?php wp_nonce_field('wpsai_settings_update_nonce'); ?>

	    <fieldset class="options">
	    <table width="100%" border="0" cellspacing="0" cellpadding="6">

	    <tr valign="top"><td width="35%" align="left">
	    <strong>Adsense Ad Campaign 1 Code:</strong>
	    <br>Copy and paste your adsense to the field on the right.
	    <br>To display this ad, use the shortcode: <code>[wp_ad_camp_1]</code>
	    </td><td align="left">
	    <textarea name="wp_ad_camp_1_code" rows="6" cols="50"><?php echo esc_attr(get_option( 'wp_ad_camp_1_code' )); ?></textarea>
	    </td></tr>

	    <tr valign="top"><td width="35%" align="left">
	    <strong>Adsense Ad Campaign 2 Code:</strong>
	    <br>Copy and paste your adsense to the field on the right.
	    <br>To display this ad, use the shortcode: <code>[wp_ad_camp_2]</code>
	    </td><td align="left">
	    <textarea name="wp_ad_camp_2_code" rows="6" cols="50"><?php echo esc_attr(get_option( 'wp_ad_camp_2_code' )); ?></textarea>
	    </td>
	    </tr>

	    <tr valign="top"><td width="35%" align="left">
	    <strong>Adsense Ad Campaign 3 Code:</strong>
	    <br>Copy and paste your adsense to the field on the right.
	    <br>To display this ad, use the shortcode: <code>[wp_ad_camp_3]</code>
	    </td><td align="left">
	    <textarea name="wp_ad_camp_3_code" rows="6" cols="50"><?php echo esc_attr(get_option( 'wp_ad_camp_3_code' )); ?></textarea>
	    </td></tr>


	    <tr valign="top"><td width="35%" align="left">
	    <strong>Adsense Ad Campaign 4 Code:</strong>
	    <br>Copy and paste your adsense to the field on the right.
	    <br>To display this ad, use the shortcode: <code>[wp_ad_camp_4]</code>
	    </td><td align="left">
	    <textarea name="wp_ad_camp_4_code" rows="6" cols="50"><?php echo esc_attr(get_option( 'wp_ad_camp_4_code' )); ?></textarea>
	    </td></tr>

	    <tr valign="top"><td width="35%" align="left">
	    <strong>Adsense Ad Campaign 5 Code:</strong>
	    <br>Copy and paste your adsense to the field on the right.
	    <br>To display this ad, use the shortcode: <code>[wp_ad_camp_5]</code>
	    </td><td align="left">
	    <textarea name="wp_ad_camp_5_code" rows="6" cols="50"><?php echo esc_attr(get_option( 'wp_ad_camp_5_code' )); ?></textarea>
	    </td></tr>

	    <tr valign="top"><td width="35%" align="left">
	    <strong>In-article Ad Code:</strong>
	    <br>Copy and paste your adsense code that you want to add inside the article.
	    <br>The ad code will be inserted automatically after the 2nd paragraph of every post.
	    </td><td align="left">
	    <textarea name="wp_in_article_ad_code" rows="6" cols="50"><?php echo esc_attr(get_option( 'wp_in_article_ad_code' )); ?></textarea>
	    </td></tr>

	    <tr valign="top"><td width="35%" align="left">
	    <strong>Post-article Ad Code:</strong>
	    <br>Copy and paste your adsense code that you want to add to the end of the article.
	    <br>The ad code will be inserted automatically after the final paragraph of every post.
	    </td><td align="left">
	    <textarea name="wp_post_article_ad_code" rows="6" cols="50"><?php echo esc_attr(get_option( 'wp_post_article_ad_code' )); ?></textarea>
	    </td></tr>

	    </table>
	    </fieldset>

	    <div class="submit">
	        <input type="submit" class="button-primary" name="info_update" value="Update options" />
	    </div>

	    </form>

            <div style="background: #D7E7F5; border: 1px solid #1166BB; color: #333333; margin: 20px 0; padding: 10px;">
                Check out our other <a href="https://www.tipsandtricks-hq.com/development-center" target="_blank">WordPress plugins</a> and <a href="https://www.appthemes.com/" target="_blank">WordPress themes</a>
            </div>

	</div>
<?php
}

// Insert the ad_camp_add_option_page in the 'admin_menu'
add_action( 'admin_menu', 'ad_camp_add_option_page' );

add_filter('the_content', 'do_shortcode');
if (!is_admin())
{
    //Front-end only
    add_filter('widget_text', 'do_shortcode');
    add_filter('the_excerpt', 'do_shortcode');
}
