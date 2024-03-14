<?php
/*
Plugin Name: Oracle Cards
Plugin URI: https://emotionalonlinestorytelling.com/oracle-card-wordpress-plugin/
Description: Random oracle cards generation.
Author: Emotional Online Storytelling
Author URI: https://emotionalonlinestorytelling.com
Text Domain: oracle-cards
Domain Path: /languages/
Version: 1.2.0
*/
/*  Copyright 2024 Emotional Online Storytelling (email: info at emotionalonlinestorytelling.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

// Definitions

define( 'EOS_CARDS_PLUGIN_VERSION', '1.2.0'  );
define( 'EOS_CARDS_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'EOS_CARDS_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'EOS_CARDS_ASSETS_URL',EOS_CARDS_URL.'/assets-1.1.4' );
define( 'EOS_CARDS_SLUG', plugin_basename( __FILE__ ) );
define( 'EOS_CARDS_PLUGIN_FILE', __FILE__  );
define( 'EOS_CARDS_PLUGIN_BASE_NAME', untrailingslashit( plugin_basename( __FILE__ ) ) );
define( 'EOS_CARDS_PLUGIN_DOCU','https://emotionalonlinestorytelling.com/oracle-cards-wordpress-plugin/getting-start/' );
define( 'EOS_CARDS_PLUGIN_URI','https://emotionalonlinestorytelling.com/oracle-cards-wordpress-plugin/' );

$theme = wp_get_theme();
if( is_object( $theme ) && 'freesoul' === $theme->name ){
	define( 'EOS_CARDS_CURRENT_THEME','freesoul' );
}
else{
	define( 'EOS_CARDS_CURRENT_THEME','not_freesoul' );
}

// Files loading.
if( is_admin() ){
	require EOS_CARDS_DIR . '/admin/oc-admin-init.php';
}

require EOS_CARDS_DIR . '/includes/oc-helper.php';

class Eos_Oracle_Cards{

	public $shortcode_atts;
	public $default_atts;
	public $card_with;
	public $card_ratio;
	public $enqueue_assets = false;
	public $maxncards;
	public $pro_key;

	function __construct(){
		if ( wp_doing_ajax() ) {
			require EOS_CARDS_DIR . '/includes/cards-ajax-front.php';
		}
		add_shortcode( 'oracle_cards',array( $this,'shortcode' ) );
		add_action( 'init',array( $this,'init' ) );
		add_action( 'wp_footer',array( $this,'enqueue_scripts' ) );
	}

	//Actions to be fired on init
	function init(){
		add_filter( 'load_textdomain_mofile', array( $this,'load_translation_file' ), 10, 2 );
		add_action( 'wp_head',array( $this,'inline_style' ) );
		load_plugin_textdomain( 'oracle-cards',false,EOS_CARDS_DIR.'/languages/' );
		if( 'freesoul' !== EOS_CARDS_CURRENT_THEME ){
			add_filter( 'single_template', array( $this,'single_card_template' ) );
		}

		/*add costum post card*/
		register_post_type( 'card', array(
			'label' => __( 'Cards','oracle-cards' ),
			'labels' => array(
				'singular_name' => __( 'Card','ispiration-cards' ),
				'add_new' => __( 'Add single card','ispiration-cards' ),
				'add_new_item' => __( 'Add new card','ispiration-cards' ),
				'edit_item' => __( 'Edit card','ispiration-cards' ),
				'new_item' => __( 'New card','ispiration-cards' ),
				'view_item' => __( 'Show card','ispiration-cards' ),
				'search_items' => __( 'Search for cards','ispiration-cards' ),
				'not_found' => __( 'No cards were found','ispiration-cards' ),
				'not_found_in_trash' => __( 'No cards were found in Trash','ispiration-cards' ),
				'featured_image'        => __( 'Card front','oracle-cards' ),
				'set_featured_image'    => __( 'Set card front','oracle-cards' ),
				'remove_featured_image' => __( 'Remove card front','oracle-cards' ),
				'use_featured_image'    => __( 'Use as card front','oracle-cards' )
			),
			'supports' => array( 'title','thumbnail','editor' ),
			// 'public' => true,
			'show_ui' => true,
			'show_in_menu' => false,
			'show_in_nav_menus' => false,
			'capability_type' => 'post',
			'has_archive' => false,
			'taxonomies' => array( 'decks' ),
			'rewrite' => array(
				'slug' => 'card'
			),
			'query_var' => true,
			'publicly_queryable' => false
		) );
		$labels = array(
			'name'              => esc_html_x( 'Decks', 'taxonomy general name', 'oracle-cards' ),
			'singular_name'     => esc_html_x( 'Deck', 'taxonomy singular name', 'oracle-cards' ),
			'search_items'      => esc_html__( 'Search decks', 'oracle-cards' ),
			'all_items'         => esc_html__( 'All Decks', 'oracle-cards' ),
			'parent_item_colon' => esc_html__( 'Parent Deck:', 'oracle-cards'  ),
			'edit_item'         => esc_html__( 'Edit Deck', 'oracle-cards'  ),
			'update_item'       => esc_html__( 'Update Deck', 'oracle-cards'  ),
			'add_new_item'      => esc_html__( 'Add New Deck', 'oracle-cards'  ),
			'new_item_name'     => esc_html__( 'New Deck Name', 'oracle-cards'  ),
			'menu_name'         => esc_html__( 'Decks','oracle-cards' )
		);
		$args = array(
			'hierarchical'      => true,
			'show_in_nav_menus'	=> false,
			'menu_order'		=> false,
			'show_tagcloud'		=> false,
			'show_in_rest'		=> false,
			'labels'            => $labels,
			'show_ui'           => true,
			'publicly_queryable' => false,
			'show_admin_column' => true,
			'query_var'         => true,
		);
		register_taxonomy( 'decks','card', $args );
		add_filter( 'body_class',array( $this,'body_class' ) );
		if( file_exists( EOS_CARDS_DIR.'/includes/oc-settings.php' ) ){
			require_once EOS_CARDS_DIR.'/includes/oc-settings.php';
			if( '5a7942c0779572b893984dd5e9be7173' !== md5( $this->pro_key ) ){
				$this->settings();
			}
		}
		else{
			$this->settings();
		}
	}

	public function enqueue_scripts(){
		$this->enqueue_script( $this->shortcode_atts,$this->default_atts,false,$this->card_with,$this->card_ratio );
	}

	//Add plugin inline style on frontend
	public function inline_style(){
		$style = '<style id="oracle-cards-css" type="text/css">';
		$style .= '.oracle-cards-wrapper{background-repeat:no-repeat;background-size:0 0;background-position:-9999px -9999px;background-image:url('.EOS_CARDS_ASSETS_URL.'/img/ajax-loader.gif)}';
		$style .= '.eos-card-btn-wrp.center{text-align:center}.eos-cards-progress{background-size:48px 48px;background-position:center center}';
		$style .= '.eos-hidden{display:none !important}.center{text-align:center !important}.eos-margin-h-32{margin-top:32px !important}.oracle-cards .button{display:inline-block}.oracle-cards .button:hover{cursor:pointer}@media screen and (max-width:767px){.eos-card{max-width:90%;max-width:calc(100% - 100px)}.not-freesoul .eos-mix-cards-wrp .refresh-cards{margin-top:16px}}';
		$style .= '</style>';
		echo $style;
	}

	//Loads plugin translation files
	public function load_translation_file( $mofile, $domain ) {
	  if ( 'oracle-cards' === $domain ) {
	    $mofile = EOS_CARDS_DIR . '/languages/oracle-cards-' . get_locale() . '.mo';
	  }
	  return $mofile;
	}

	//Add shortcode
	public function shortcode( $atts ){
		$output = '';
		$atts = apply_filters( 'oracle_cards_shortcode_atts',$atts );
		if( $this->maxncards > 900 && isset( $atts['maxnumber'] ) ){
			$this->maxncards = absint( $atts['maxnumber'] );
		}
		require EOS_CARDS_DIR.'/templates/shortcode.php';
		return apply_filters( 'oracle_cards_shortcode_output',$output,$atts,$opts,$default_atts );
	}

	//Return default deck options
	public  function default_deck_options(){
		return array(
			'def-back-card-choice' => 1,
			'custom_back_card_id' => 0,
			'card_width' => 150,
			'card_height' => 225,
			'pickable_n' => 1,
		);
	}

	//Return array of default shortcode attributes
	public function default_atts(){
		$default_opts = $this->default_deck_options();
		$atts = array(
			'back_border_color' => '',
			'back_border' => 'no',
			'border_radius' => '',
			'button_text_mix' => esc_html__( 'Mix the cards','oracle-cards' ),
			'button_text_pick' => esc_html__( 'Pick your card','oracle-cards' ),
			'def-back-card-choice' => 1,
			'card_height' => $default_opts['card_height'],
			'card_width' => $default_opts['card_width'],
			'button_class' => '',
			'class' => '',
			'content_title' => '',
			'custom_back_id' => '',
			'custom_back_card_id' => $default_opts['custom_back_card_id'],
			'deck_from' => 930,
			'deck_type' => 'folding_fan',
			'deck' => 'none',
			'distance' => 2,
			'elementor' => 'false',
			'maxmargin' => 400,
			'maxnumber' => 100,
			'maxrand' => 100,
			'on_mobile' => 'show',
			'read_more' => __( 'Read more ...','oracle-cards' ),
			'show_title' => 'false',
			'space_top_button' => 20,
			'space_top_text' => 20,
			'space_top' => 20,
			'reverse_reading' => 'false',
			'title_alignment' => 'initial',
			'pickable_n' => 1
		);
		if( function_exists( 'et_setup_theme' ) ){
			$atts['button_class'] = 'et_pb_button et_pb_button_0';
		}
		return $atts;
	}

	//Return CSS property adding -o, -ms, -moz, -webkit
	public function add_css_prefixes( $prop,$value ){
		$props = array();
		foreach( array( '-o-','-ms-','-moz-','-webkit-','' ) as $prefix ){
			$props[] = $prefix.$prop.':'.$value.';';
		}
		return str_replace( ';;',';',implode( ';',$props ) );
	}

	//Il loads the single card template checking first in the active theme
	public function single_card_template( $template ) {
    global $post;
    if ( 'card' === $post->post_type ){
		$themeDir = get_stylesheet_directory();
    if( $template === $themeDir.'/single-card.php' ) return $template;
		if( file_exists( $themeDir.'/oracle-cards/single-card.php' ) ) return $themeDir.'/oracle-cards/single-card.php';
       return plugin_dir_path( __FILE__ ).'/templates/single-card.php';
    }
    return $template;
	}

	//Add body class
	public function body_class( $classes ){
		$classes[] = 'oracle-cards';
		if( 'freesoul' !== EOS_CARDS_CURRENT_THEME ){
			$classes[] = 'not-freesoul';
		}
		return $classes;
	}

	//Enqueue shortcode script
	public function enqueue_script( $atts,$default_atts,$admin_preview,$w,$ratio ){
		if( apply_filters( 'oracle_cards_enqueue_script',$this->enqueue_assets ) ){
			extract( shortcode_atts( $default_atts,apply_filters( 'oracle_cards_shortcode_atts',$atts ) ) );
			if( !( isset( $_REQUEST['action'] ) && 'elementor' === sanitize_text_field( $_REQUEST['action'] ) ) ){
				$js_file = absint( $pickable_n ) > 1 ? 'pickable-multi' : 'pickable-single';
				wp_enqueue_script( 'eos-cards-main',EOS_CARDS_ASSETS_URL.'/js/oc-cards-'.$js_file.'.js',array( 'jquery' ),null,true );
				$params = array(
					'type' => esc_attr( $deck_type ),
					'card_width' => absint( $w ),
					'ratio' => esc_attr( $ratio ),
					'direction' => is_rtl() ? 'right' : 'left',
					'read_more' => esc_html( $read_more ),
					'show_title' => 'true' !== $show_title ? 'false' : 'true',
					'card_title_class' => apply_filters( 'oracle_cards_card_title_class','eos-font-subtitles' ),
					'title_alignment' => in_array( $title_alignment,array( 'left','center','right' ) ) ? $title_alignment : 'initial',
					'ajax_loader' => EOS_CARDS_ASSETS_URL.'/img/ajax-loader.gif',
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'is_admin_preview' => $admin_preview,
					'deck_from' => absint( $deck_from ),
					'preview_id' => isset( $_GET['card_id'] ) ? absint( $_GET['card_id'] ) : false,
					'reverse_reading' => esc_attr( $reverse_reading ),
					'pickable_n' => absint( $pickable_n )
				);
				wp_localize_script( 'eos-cards-main','eos_cards_js',$params );
			}
		}
	}

	function settings(){
		$this->maxncards = 15;
	}

	//It retrieves multiple metadata given the $meta_key and the array of post IDs
	function get_multiple_metadata( $meta_key,$ids ){
	  if( empty( $ids ) || '' === $meta_key ) return false;
	    global $wpdb;
	    if( is_array( $ids ) ){
	       $ids = implode( ',',array_map( 'absint',$ids ) );
	    }
	    elseif( is_string( $ids ) ){
	       $ids = implode( ',',array_map( 'absint',explode( ',',$ids ) ) );
	    }
	    $ids = esc_sql( $ids );
	    $meta_key = esc_sql( $meta_key );
	    $sql = "SELECT post_id,meta_value FROM $wpdb->postmeta WHERE post_id IN ($ids) AND meta_key='$meta_key';";
	    return $wpdb->get_results( $sql,OBJECT );
	}
}

add_filter( 'generate_rewrite_rules', function ( $wp_rewrite ){
    $wp_rewrite->rules = array_merge(
        ['oracle-cards-preview/?$' => 'index.php?oracle-cards-preview=1'],
        $wp_rewrite->rules
    );
} );
add_filter( 'query_vars', function( $query_vars ){
    $query_vars[] = 'oracle-cards-preview';
    return $query_vars;
} );
add_action( 'template_redirect', function(){
	if( isset( $_GET['deck'] ) && isset( $_GET['deck_type'] ) ){
		$custom = intval( get_query_var( 'oracle-cards-preview' ) );
		if ( $custom ) {
			include EOS_CARDS_DIR . '/templates/preview.php';
			die();
			exit;
		}
	}
} );


global $oracle_cards;
$oracle_cards = new Eos_Oracle_Cards();
