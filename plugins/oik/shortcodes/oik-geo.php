<?php // (C) Copyright Bobbing Wide 2012-2017

/**
 * Implement [bw_geo] shortcode
 *
 * @param array $atts 
 * @param string $content
 * @param string $tag
 * @return string expanded shortcode
 *
 */
function bw_geo( $atts=null, $content=null, $tag=null ) {
  sp("geo");
  span( "geo");
    e( __( "Lat.", "oik" ) ); 
    span( "latitude" );
    e( bw_get_option_arr( "lat", "bw_options", $atts ) );
    epan();
    // I think we should have a space between the lat. and long. values
    e( "&nbsp;");
    e( __( "Long.", "oik" ) );
    span( "longitude" );
    e( bw_get_option_arr( "long", "bw_options", $atts ) );
    epan();
  epan();
  ep(); 
  return( bw_ret());
}

/**
 * Implement the [bw_directions] shortcode to generate a button to get directions from Google Maps 
 * 
 * e.g. * http://maps.google.co.uk/maps?f=d&hl=en&daddr=50.887856,-0.965113
 *
 */
function bw_directions( $atts=null ) {
  $lat = bw_get_option_arr( "lat", "bw_options", $atts );
  $long = bw_get_option_arr( "long", "bw_options", $atts );
  $company = bw_get_option_arr( "company", "bw_options", $atts );
  $extended = bw_get_option_arr( "extended-address", "bw_options", $atts );
  $postcode = bw_get_option_arr( "postal-code", "bw_options", $atts );
  $link = "http://maps.google.co.uk/maps?f=d&hl=en&daddr=" . $lat . "," . $long;  
  $text = __( "Google directions", "oik" );
  /* translators: %s: company name */
  $title = sprintf( __( 'Get directions to %s', "oik" ), $company );
  if ( $extended && ($company <> $extended) )
    $title .= " - " . $extended;
  if ( $postcode )
    $title .= " - " . $postcode;
  $class = NULL;
  art_button( $link, $text, $title, $class ); 
  return( bw_ret());
}

    
/**
 * Help hook for bw_directions
 */    
function bw_directions__help() {
  return( __( "Display a 'Google directions' button.", "oik" ) );
}

/**
 * Example hook for bw_directions
 */
function bw_directions__example() {

  BW_::br( __( "e.g. ", "oik" ) );
  e( __( "The Google directions button will enable the user to get directions to you.", "oik" ) );
  e( bw_directions() );
}

/**
 * Syntax for [bw_directions] shortcode
 */
function bw_directions__syntax( $shortcode="bw_directions" ) {
  $syntax = array( "alt" => BW_::bw_skv( null, "1", __( "Use alternative value", "oik" ) ) );
  return( $syntax );
}


/**
 * Syntax for [bw_geo] shortcode
 */
function bw_geo__syntax( $shortcode="bw_geo" ) {
  $syntax = array( "alt" => BW_::bw_skv( null, "1", __( "Use alternative value", "oik" ) ) );
  return( $syntax );
}
                  

