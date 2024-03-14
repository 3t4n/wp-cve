<?php
/**
 *  File required by oracle-cards.php containing the plugin shortcodes integration with VisualComposer
 */
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

//integration with Freesoul Builder
add_action( 'eosb_before_init', 'eos_cards_folding_fan_integrateWithVC' );
function eos_cards_folding_fan_integrateWithVC() {
	$opts = eos_cards_get_option();
	if( false === $opts ) return;
	$decks = array();
	foreach( $opts as $t_id => $term ){
		$decks[$term['name']] = esc_html( $t_id );
	}
	$params = array(
	 array(
		'type' => 'dropdown',
		'holder' => 'div',
		'class' => '',
		'heading' => __( 'Deck', 'oracle-cards' ),
		'param_name' => 'deck',
		'value' => array_merge( array( __( '--Select--','oracle-cards' ) => 'none' ),$decks ),
		'description' => __( 'Select a deck for these cards.', 'oracle-cards' )
	 ),
	 array(
		'type' => 'dropdown',
		'holder' => 'div',
		'class' => '',
		'heading' => __( 'Deck Type', 'oracle-cards' ),
		'param_name' => 'deck_type',
		'value' => array( __( "Folding fan",'oracle-cards' ) => 'folding_fan' ,__( "Deck",'oracle-cards' ) => 'deck' ),
	 ),
	 array(
		'type' => 'textfield',
		'holder' => 'div',
		'class' => '',
		'heading' => __( 'Button text for picking a card', 'oracle-cards' ),
		'param_name' => 'button_text_pick',
		'value' => __( 'Pick your card','oracle-cards' )
	 ),
	 array(
		'type' => 'textfield',
		'holder' => 'div',
		'class' => '',
		'heading' => __( 'Button text for mixing cards', 'oracle-cards' ),
		'param_name' => 'button_text_mix',
		'value' => __( 'Mix the cards','oracle-cards' )
	 ),
	 array(
		'type' => 'dropdown',
		'holder' => 'div',
		'class' => '',
		'heading' => __( 'Show cart title', 'oracle-cards' ),
		'param_name' => 'show_title',
		'value' => array( __( "Don't show title",'oracle-cards' ) => 'false' ,__( "Show title",'oracle-cards' ) => 'true' ),
		'description' => __( 'Select a deck for these cards.', 'oracle-cards' )
	 )
 );
	if( defined( 'EOS_CARDS_PRO' ) && EOS_CARDS_PRO ){
		$params[] = array(
			'type' => 'attach_image',
			'heading' => __( 'Deck back', 'oracle-cards' ),
			'param_name' => 'custom_back_id',
			'description' => __( 'Select image from media library for the back of the deck. Leaving it empty the image set in Cards common options will be taken.', 'oracle-cards' ),
		);
	}
	$params = array_merge( $params,array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Space before the deck', 'oracle-cards' ),
			'param_name' => 'space_top',
			'class' => 'eos-float',
			'value' => '20',
			'std' => '20',
			'description' => __( 'Height of the empty space before the deck in pixels.','oracle-cards' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Space before the text', 'oracle-cards' ),
			'param_name' => 'space_top_text',
			'class' => 'eos-float',
			'value' => '20',
			'std' => '20',
			'description' => __( 'Height of the empty space before the text in pixels.','oracle-cards' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Space before the button', 'oracle-cards' ),
			'param_name' => 'space_top_button',
			'class' => 'eos-float',
			'value' => '20',
			'std' => '20'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Deck layout from', 'oracle-cards' ),
			'param_name' => 'deck_from',
			'class' => 'eos-float',
			'value' => '930',
			'dependency' => array(
				'element' => 'deck_type',
				'value' => array( 'folding_fan' )
			),
			'description' => __( 'When the screen width is lower than this value, show the deck layout instead of the folding fan.', 'oracle-cards' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Animation distance', 'oracle-cards' ),
			'param_name' => 'animation_distance',
			'class' => 'eos-float',
			'value' => '200',
			'dependency' => array(
				'element' => 'deck_type',
				'value' => array( 'deck' )
			),
			'description' => __( 'Maximum distance from the deck of the picked card during the animation.', 'oracle-cards' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Max number of cards', 'oracle-cards' ),
			'param_name' => 'maxnumber',
			'class' => 'eos-float',
			'value' => '100'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Cards distance', 'oracle-cards' ),
			'param_name' => 'distance',
			'class' => 'eos-float',
			'value' => '2',
			'dependency' => array(
				'element' => 'deck_type',
				'value' => array( 'folding_fan' )
			),
			'description' => __( 'Enter the distance between cards.', 'oracle-cards' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Level of randomness', 'oracle-cards' ),
			'param_name' => 'maxrand',
			'class' => 'eos-float',
			'value' => '100',
			'dependency' => array(
				'element' => 'deck_type',
				'value' => array( 'folding_fan' )
			),
			'description' => __( 'Higher is this number, higher will be the randomness of the cards distribution.', 'oracle-cards' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Maximum space (px)', 'oracle-cards' ),
			'param_name' => 'maxmargin',
			'class' => 'eos-float',
			'value' => '400',
			'dependency' => array(
				'element' => 'deck_type',
				'value' => array( 'folding_fan' )
			),
			'description' => __( 'If the cards need more space than this space, the cards will thicken.', 'oracle-cards' ),
		),
		 array(
			'type' => 'dropdown',
			'holder' => 'div',
			'class' => '',
			'heading' => __( 'Visibility on mobile', 'oracle-cards' ),
			'param_name' => 'on_mobile',
			'value' => array( __( "Show on mobile",'oracle-cards' ) => 'show',__( "Hide on mobile",'oracle-cards' ) => 'hide',__( "Remove on mobile",'oracle-cards' ) => 'remove' ),
			'description' => __( 'Hiding it means that it will be hidden by CSS. Removing it means that on mobile it does not exist. If you completely remove it on mobile, you may have problems with the full page cache if any. Be sure your caching system distinguish between mobile and desktop in that case.', 'oracle-cards' )
		 ),
		array(
			'type' => 'textfield',
			'holder' => 'div',
			'class' => 'eos-class',
			'heading' => __( 'Button class name', 'oracle-cards' ),
			'param_name' => 'button_class',
			'value' => '',
			'description' => __( 'You can add an extra CSS class to the buttons that is like those ones added by your builder.', 'oracle-cards' )
		),
		array(
			'type' => 'textfield',
			'holder' => 'div',
			'class' => 'eos-class',
			'heading' => __( 'Class name', 'oracle-cards' ),
			'param_name' => 'class',
			'value' => '',
			'description' => __( 'Insert here just a class name for your custom css code.', 'oracle-cards' )
		)
	) );
	eosb_map( array(
	  'name' => __( 'Oracle cards', 'oracle-cards' ),
	  'base' => 'oracle_cards',
	  'class' => '',
	  'icon' => 'eos_cards_eosb_element_icon',
	  'category' => __( 'Content', 'oracle-cards' ),
	  'description' => __( 'It adds a folding fan of cards.', 'oracle-cards' ),
	  'params' => $params
   ) );
}
