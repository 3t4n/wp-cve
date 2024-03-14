<?php if ( ! defined( 'ABSPATH' )  ) { die; }

$tp_codstar_prefix = 'xltab';

CSF::createOptions( $tp_codstar_prefix, [
  'menu_title' => 'XL Tab',
  'menu_slug'  => 'xl-tab',
  'theme' => 'light',
  'menu_icon' => 'dashicons-buddicons-community',
  'framework_title' => 'XL Tab <small>by XLDevelopment</small>',
] );

CSF::createSection( $tp_codstar_prefix, [
  'id'    => 'xltabfree',
  'title' => 'Free widgets',
  'icon'  => 'dashicons dashicons-dashboard',
  'fields' => [

    [
      'id'    => 'accordion',
      'type'  => 'switcher',
      'title' =>  esc_html__('Accordion', 'xltab'),
      'default' => 'yes',
    ],

    [
      'id'    => 'tab-vertical',
      'type'  => 'switcher',
      'title' =>  esc_html__('Vertical tab', 'xltab'),
      'default' => 'yes',
    ],
    [
      'id'    => 'tab1',
      'type'  => 'switcher',
      'title' =>  esc_html__('Tab', 'xltab'),
      'default' => 'yes',
    ],
    [
      'id'    => 'tab-switch',
      'type'  => 'switcher',
      'title' =>  esc_html__('Tab switch', 'xltab'),
      'default' => 'yes',
    ],

  ]

] );
 
if ( class_exists( 'XL_Tab_Pro' ) ) {



}


