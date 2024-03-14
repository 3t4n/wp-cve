<?php
defined( 'ABSPATH' ) || exit;

class YAHMAN_ADDONS_Polylang{

  public function __construct(){
    add_action( 'plugins_loaded', array( $this, 'init' ) );
  }

  public function init() {

    // Define YAHMAN Add-ons settings to translate
    add_filter( 'plugins_loaded', array( $this, 'define_strings' ), 20 );

    // Translate YAHMAN Add-ons Strings
    foreach( $this->strings() as $key => $string) {
      foreach ($string as $type => $value) {
        add_filter( 'yahman_addons_' . $key . '_' .$type, array( $this, 'translate_string' ), 10, 2 );
      }
    }







  }


  public function translate_string( $value ) {
    return pll__( $value );
  }



  public function strings() {
    return array(
      'share' => array( 'title' => esc_html( 'Social share title' , 'yahman-add-ons') ),
      'cta_social' => array(
        'heading' => esc_html( 'CTA social heading' , 'yahman-add-ons'),
        'ending' => esc_html( 'CTA social ending' , 'yahman-add-ons') ),
      'profile' => array(
        'title' => esc_html( 'Title of Profile' , 'yahman-add-ons'),
        'name' => esc_html( 'Name of Profile' , 'yahman-add-ons'),
        'text' => esc_html( 'Text of Profile' , 'yahman-add-ons'),
        'read_more_url' => esc_html( 'Read more url of Profile' , 'yahman-add-ons'),
        'read_more_text' => esc_html( 'Read more text of Profile' , 'yahman-add-ons') ),
      'related_posts' => array(
        'post_title' => esc_html( 'Title of Related Posts' , 'yahman-add-ons'),
        'page_title' => esc_html( 'Title of Related Pages' , 'yahman-add-ons') ),
      'toc' => array(
        'title' => esc_html( 'Title of Table of contents' , 'yahman-add-ons') ),
    );
  }

  public function define_strings($input) {

    $settings = $this->strings();

    $multiline_settings = array(
      'text'
    );

    $option = get_option('yahman_addons');

    foreach( $settings as $key => $string ){
      foreach ($string as $type => $value ) {
        $multiline = false;
        if( in_array( $type, $multiline_settings ) ) {
          $multiline = true;
        }
        if ( function_exists( 'pll_register_string' ) ) {
          pll_register_string( $type, $option[$key][$type], 'YAHMAN Add-ons', $multiline );
        }
      }

    }

  }




}

