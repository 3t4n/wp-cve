<?php
/*
Plugin Name: Security Headers
Plugin URI: https://www.firstpagedigital.sg/ 
Description: Security headers are directives used by web applications to configure security defenses.  
Version: 1.0.0
Text Domain: firstpage-sg-security-headers
Author: Joseph Mendez
Author URI: https://www.linkedin.com/in/joseph-m-3a133a29/                                                                                                                                                                                          
*/
// no direct access allowed
defined( 'ABSPATH' ) or die( 'Not alowed here.' );

/**
* plugin activation
* @since 1.0.0 
*/
register_activation_hook( __FILE__, 'fpd_headers_enable_flush_rules' );

/**
* plugin deactivation
* @since 1.0.0 
*/
register_deactivation_hook( __FILE__, 'fpd_headers_deactivate' );

/**
* filter mod_rewrite_rules
* @since 1.0.0 
* filter the list of rewrite rules formatted for output to an .htaccess file. 
*/
add_filter( 'mod_rewrite_rules', 'add_fpd_security_headers' );

/**
* add_action send_headers
* @since 1.0.0 
* fires once the requested HTTP headers have been sent e,g Strict transport security.
*/
add_action( 'send_headers', 'fpd_headers_send_header' );

/**
* add_filter wp_headers
* @since 1.0.0 
* filter the HTTP headers before they are sent to the browser.
*/
add_filter( 'wp_headers', 'fpd_headers_wp_headers' );


/**
* flush rewrite rules
* @since 1.0.0 
* flush rewrite rules and then recreate rewrite rules. 
*/
function fpd_headers_enable_flush_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
* uninstall/remove
* @since 1.0.0 
* uninstall/remove plugin's generated rewrite rules and headers
*/
function fpd_headers_deactivate() {
  remove_filter( 'mod_rewrite_rules', 'add_fpd_security_headers' );
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
* send headers 
* @since 1.0.0 
* e.g strict transport security
*/
function fpd_headers_send_header() {
  header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
}

/**
* add mod headers
* @since 1.0.0 
*/
function add_fpd_security_headers( $rules ) {

  // $websiteurl = sanitize_url(get_site_url());
  $urlparts = parse_url(sanitize_url(home_url()));
  $websiteurl = $urlparts['host'];

  $content = "#FPD - Custom Headers Security\n";
  $content .= "<IfModule mod_headers.c>\n";
  $content .= "Header always set Strict-Transport-Security \"max-age=31536000; includeSubDomains; preload\" \"expr=%{HTTPS} == 'on'\"\n";
  $content .= "Header always set X-XSS-Protection \"1; mode=block\"\n";
  $content .= "Header always set X-Content-Type-Options \"nosniff\"\n";
  $content .= "Header always set Referrer-Policy \"strict-origin-when-cross-origin\"\n";
  $content .= "Header always set Expect-CT \"max-age=7776000, enforce\"\n";
  $content .= "Header set Access-Control-Allow-Origin \"*\"\n";
  $content .= "Header set Access-Control-Allow-Methods \"GET,PUT,POST,DELETE\"\n";
  $content .= "Header set Access-Control-Allow-Headers \"Content-Type, Authorization\"\n";
  $content .= "Header set X-Content-Security-Policy \"img-src *; media-src * data:;\"\n";
  $content .= "Header always set Content-Security-Policy \"report-uri https://$websiteurl\"\n";
  $content .= "Header always set X-Frame-Options \"SAMEORIGIN\"\n";
  $content .= "Header always set Permissions-Policy \"accelerometer=(), autoplay=(), camera=(), fullscreen=*, geolocation=(self), gyroscope=(), microphone=(), payment=*\"\n";
  $content .= "Header set X-Permitted-Cross-Domain-Policies \"none\"\n";
  $content .= "</IfModule>\n";
  $content .= "#FPD - Custom Headers Security\n";

  return $content.$rules;
}

/**
* add to wp headers
* @since 1.0.0 
*/
function fpd_headers_wp_headers( $headers ) {

  //$websiteurl = sanitize_url(get_site_url());
  $urlparts = parse_url(sanitize_url(home_url()));
  $websiteurl = $urlparts['host'];

  $headers['X-XSS-Protection'] = '1; mode=block';
  $headers['Expect-CT'] = 'max-age=7776000, enforce';
  $headers['Access-Control-Allow-Origin'] = 'null';
  $headers['Access-Control-Allow-Methods'] = 'GET,PUT,POST,DELETE';
  $headers['Access-Control-Allow-Headers'] = 'Content-Type, Authorization';
  $headers['X-Content-Security-Policy'] = 'default-src \'self\'; img-src *; media-src * data:;';
  $headers['X-Content-Type-Options'] = 'nosniff';
  $headers['Content-Security-Policy'] = "report-uri $websiteurl";
  $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
  $headers['Cross-Origin-Embedder-Policy-Report-Only'] = 'unsafe-none; report-to="default"';
  $headers['Cross-Origin-Embedder-Policy'] = 'unsafe-none; report-to="default"';
  $headers['Cross-Origin-Opener-Policy-Report-Only'] = 'same-origin; report-to="default"';
  $headers['Cross-Origin-Opener-Policy'] = 'same-origin-allow-popups; report-to="default"';
  $headers['Cross-Origin-Resource-Policy'] = 'cross-origin';
  $headers['X-Frame-Options'] = 'SAMEORIGIN';
  $headers['Permissions-Policy'] = "accelerometer=(), autoplay=(), camera=(), cross-origin-isolated=(), document-domain=(), encrypted-media=(), fullscreen=*, geolocation=(self), gyroscope=(), keyboard-map=(), magnetometer=(), microphone=(), midi=(), payment=*, picture-in-picture=(), publickey-credentials-get=(), screen-wake-lock=(), sync-xhr=(), usb=(), xr-spatial-tracking=(), gamepad=(), serial=(), window-placement=()";
  $headers['Feature-Policy'] = "display-capture 'self'";
  $headers['X-Permitted-Cross-Domain-Policies'] = "none";  

  return $headers;
}