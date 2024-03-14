<?php

/**
 * Validated a given pulisher id
 * 
 * @param string $pub_id
 * @return bool
 */
function sl_validate_publisher_id( $pub_id ) {
	if( ( $id_parts = explode( 'X', $pub_id ) ) && count( $id_parts ) === 2 && is_numeric( $id_parts[0] ) && is_numeric( $id_parts[1] ) ) {
		return true;
	}	
	
	return false;
}

/**
 * Validates a subdomain against the skimlinks redirect domain (based off body content)
 * 
 * @param string $subdomain
 * @return bool
 */
function sl_validate_subdomain( $subdomain ) {
	if( $subdomain == '' )
		return false;
	
	$skimlinks_redirect_domain = 'https://go.redirectingat.com/check/domain_check.html';

	$subdomain_response = wp_remote_get( sl_get_protocol() . trailingslashit( $subdomain ) . 'check/domain_check.html' );
	$subdomain_body = wp_remote_retrieve_body( $subdomain_response );
	
	$skimlinks_response = wp_remote_get( $skimlinks_redirect_domain );
	$skimlinks_body = wp_remote_retrieve_body( $skimlinks_response );
		
	return $skimlinks_body > '' && $subdomain_body == $skimlinks_body;
}

/**
 * Get the server protocol. The Skimlnks javascript requires that the subdomain and 
 * main site protocols are the same.
 */
function sl_get_protocol() {
	return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
}

/**
 * Returns whether the current theme has been verififed to include the footer js
 * 
 * @return bool
 */
function sl_is_footer_js_verified() {
	return get_option( 'sl_footer_js_works_for' ) == get_current_theme();
}

/**
 * Verfies if the current theme works with the footer javascript (use sl_is_footer_js_verified to check if it has been verified first)
 * 
 * @access public
 * @return void
 */
function sl_verify_footer_js() {
	$request = wp_remote_get( add_query_arg( 'sl_verify_footer_js', '1', get_bloginfo( 'url' ) ) );
	$body = wp_remote_retrieve_body( $request );
}

/**
 * Returns whether the user has opted to use a custom subdomain
 * 
 * @return bool
 */
function sl_is_subdomain_enabled() {
	return (bool) get_option( 'sl_enable_subdomain' );
}

/**
 * Returns whether the plugin is configured (therefor ready to put js into footer)
 * 
 * @return bool
 */
function sl_is_plugin_configured() {
	if ( sl_validate_publisher_id( sl_get_publisher_id() ) ) {
		if( sl_is_subdomain_enabled() ) {
			return sl_get_subdomain() > '';
		}
		return true;
	}
	
	return false;
}

/**
 * Returns whether the plugin is configured and the theme has been verified to show the js
 * 
 * @return bool
 */
function sl_is_plugin_active() {
	return sl_is_plugin_configured() && sl_is_footer_js_verified();
}

function sl_is_rss_filtering_enabled() {
	return (bool) get_option( 'sl_enable_rss_filtering' );
}

/**
 * Checks if the "Add Disclosure Badge" option is on or off.
 * 
 * @return bool
 */
function sl_is_disclosure_badge_enabled() {
	return (bool) get_option( 'sl_add_disclosure_badge' );
}

/**
 * Checks if the "Append Disclosure Badge" option is on or off.
 * 
 * @return bool
 */
function sl_is_disclosure_badge_appended() {
	return get_option( 'sl_add_disclosure_badge' ) && get_option( 'sl_append_disclosure_badge' );
}

/**
 * Gets the disclosure badge colour option.
 * 
 * @return string
 */
function sl_disclosure_badge_colour() {
	/**
	 * @todo 
	 */
	return 'blue';
}

/**
 * Returns the Skimlinks Publisher ID
 * 
 * @return bool
 */
function sl_get_publisher_id() {
	return get_option( 'sl_publisher_id' );
}

/**
 * Returns the publisher_id part of Publisher ID
 * 
 * @return string || null if no pub id
 */
function sl_get_publisher_id_publisher_id() {

	$publisher_id = sl_get_publisher_id();
	
	if( !sl_validate_publisher_id( $publisher_id ) ) {
		return null;
	}
	
	return current( explode( 'X', $publisher_id ) );
	
}

/**
 * Returns the domain_id part of Publisher ID
 * 
 * @return string || null if no pub id
 */
function sl_get_publisher_id_domain_id() {

	$publisher_id = sl_get_publisher_id();
	
	if( !sl_validate_publisher_id( $publisher_id ) ) {
		return null;
	}
	
	return end( explode( 'X', $publisher_id ) );
	
}

/**
 * Returns the custom subdomain
 * 
 * @return bool
 */
function sl_get_subdomain() {
	return get_option( 'sl_subdomain' );
}

/**
 * Returns the footer js code for skimlinks
 * 
 * @return string
 */
function sl_get_footer_js() {
	
	// Strip http:// from the skimlinks sub domain. We're doing this at validation but 
	// this allows backwards compatibility.
	$subdomain = (string) sl_get_subdomain();
	$subdomain = preg_replace( "|^http(s)?://|", "", $subdomain );
	
	$output = "";

	if( sl_is_subdomain_enabled() && $subdomain ){
		$output .= '<script type="text/javascript">' . "\n";
		$output .= 'var skimlinks_domain = "' . $subdomain . '";' . "\n";
		$output .= '</script>' . "\n";	
	}
	
	$output .=  '<script type="text/javascript" src="//s.skimresources.com/js/' . sl_get_publisher_id() . '.skimlinks.js"></script>' . "\n";
	
	return $output;
}

/**
 * Echoes sl_get_footer_js and also is responsible for the footer js validation
 * 
 * @return void
 */
function sl_footer_js() {	

	// set an option to say footer js is being shown (so the theme is working)
	if( isset( $_GET['sl_verify_footer_js'] ) && untrailingslashit( $_GET['sl_verify_footer_js'] ) == '1' ) {
		update_option( 'sl_footer_js_works_for', get_current_theme() );	
	}
	
	// if the plugin is not configered, don't show anything
	if( !sl_is_plugin_configured()  )
		return;

	echo sl_get_footer_js();
}

function skimlinks_footer() {
	// set an option to say footer js is being shown (so the theme is working)
	if( $_GET['sl_verify_footer_js'] == '1' ) {
		update_option( 'sl_footer_js_works_for', get_current_theme() );	
	}

	echo sl_get_footer_js();
}

/**
 * Modifes all external href's in the text.
 * 
 * @param string $content
 * @return string (modified content)
 */
function sl_modify_enternal_links( $content ) {
	
	$regex = ' href=(\"|\'|&quot;)(((?!\1).)+)(\1)';
	
	//preg_match_all( "/$regex/siU", $content, $matches, PREG_SET_ORDER );
	$content = preg_replace_callback( "/$regex/siU", 'sl_modify_external_link', $content );
	
	return $content;
}

/**
 * Modifies a link with the skimlinks version (used from preg_replace_callback
 * 
 * @param array $links
 * @return string new link
 */
function sl_modify_external_link( $links ) {

	$redirect_domain = "http://go.redirectingat.com/";
	$do_not_touch_domains = array("redirectingat.com", "go.redirectingat.com");

	// if the admin set up a custom subdomain add it to the list
	if (sl_is_subdomain_enabled() && sl_get_subdomain()){
		$redirect_domain = sl_get_subdomain();

		$url_parts = parse_url($redirect_domain);
		if (isset($url_parts['host'])){
			$do_not_touch_domains[] = $url_parts['host'];
		}
		
	}
	
    $is_encoded = $links[1] == '&quot;';    
    $link = $links[2];
	
	// get the url's domain so we can do exact mathcing
	$url_parts = parse_url($link);
	if (isset($url_parts['host'])){
		$link_domain = $url_parts['host'];
	} else {
		$link_domain = "";
	}
	
	// dont modify internal links, or not http ones, or already skimlinked ones
	if( strpos( $link, 'http' ) !== 0 || strpos( $link, get_bloginfo( 'url' ) ) !== false || in_array($link_domain, $do_not_touch_domains) ) {
	    return $links[0];
	}
	
	$link = htmlentities($redirect_domain . '?id=' . sl_get_publisher_id() . '&xs=1&url=' . urlencode( $link ) . '&sref=rss');

	// in some cases the content contains already encoded data. lets double encode it
	if ($is_encoded){
		$link = htmlentities($link);
	}
							
	return ' href='.$links[1].$link.$links[1];
}

/**
 * Add the dislcosure badge html to the end of the content (used via the_content filter)
 * 
 * @param string $content
 * @return string
 */
function sl_add_disclosure_badge_to_content( $content ) {

	global $post;
	
	//only filter posts
	if( $post->post_type !== 'post' )
		return $content;
		
	
	$disclosure_html = sl_get_disclosure_badge_html();
	
	
	return $content . "\n" . $disclosure_html;

}

/**
 * Returns the url for the disclosure badge.
 * 
 * @return string
 */
function sl_get_disclosure_badge_url($colour = false) {
	// We can just swap this out if we decide to remotely host the images
	$badge_root = plugin_dir_url( __FILE__ ) . 'assets/';
	return $badge_root . 'Disclosure_' . ($colour ? $colour : sl_disclosure_badge_colour()) . '.png';
}

/**
 * Returns the html output for the disclosure badge html (inluing wrapper div).
 * 
 * @return string
 */
function sl_get_disclosure_badge_html($colour = false) {
	return '<div class="skimlinks-disclosure-button"><p><a href="https://skimlinks.com/" target="_blank"><img src="' . sl_get_disclosure_badge_url($colour ? $colour : false) . '" style="display:block"/></a></p></div>' . "\n";
}
