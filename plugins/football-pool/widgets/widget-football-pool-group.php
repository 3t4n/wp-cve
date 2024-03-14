<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/**
 * Widget: Group Standing Widget
 */

defined( 'ABSPATH' ) or die( 'Cannot access widgets directly.' );
add_action( 'widgets_init', function() { register_widget( 'Football_Pool_Group_Widget' ); } );

// dummy var for translation files
$fp_translate_this = __( 'Group Widget', 'football-pool' );
$fp_translate_this = __( 'this widget displays the tournament standing for a group.', 'football-pool' );
$fp_translate_this = __( 'standing', 'football-pool' );
$fp_translate_this = __( 'Show this group', 'football-pool' );
$fp_translate_this = __( 'Layout of the widget', 'football-pool' );

class Football_Pool_Group_Widget extends Football_Pool_Widget {
	protected $widget = array(
		'name' => 'Group Widget',
		'description' => 'this widget displays the tournament standing for a group.',
		'do_wrapper' => true, 
		
		'fields' => array(
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'std' => 'standing'
			),
			array(
				'name'    => 'Show this group',
				'desc'    => '',
				'id'      => 'group',
				'type'    => 'select',
				'options' => [] // get data from the database later on
			),
			array(
				'name'    => 'Layout of the widget',
				'desc'    => '',
				'id'      => 'layout',
				'type'    => 'select',
				'options' => [] // get data later on
			),
		)
	);
	
	public function html( $title, $args, $instance ) {
		extract( $args );
		
		$group = ! empty( $instance['group'] ) ? $instance['group'] : '1';
		$layouts = array( 1 => 'wide', 2 => 'small' );
		$layout = ! empty( $instance['layout'] ) ? $layouts[$instance['layout']] : $layouts[1];
		
		$output = '';
		if ( $title !== '' ) {
			/** @var string $before_title */
			/** @var string $after_title */
			$output .= $before_title . $title . $after_title;
		}
		
		$groups = new Football_Pool_Groups;
		$output .= $groups->print_group_standing( $group, $layout );
		echo apply_filters( 'footballpool_widget_html_group', $output );
	}

	public function initiate_widget_dynamic_fields() {
		// get the groups from the database
		$groups = ( new Football_Pool_Groups() )->get_group_names();
		$options = [];
		foreach ( $groups as $id => $group ) {
			$options[$id] = Football_Pool_Utils::xssafe( $group );
		}
		$this->widget['fields'][1]['options'] = $options;

		// set the layout options
		$this->widget['fields'][2]['options'] = array (
			1 => __( 'wide', 'football-pool' ),
			2 => __( 'small', 'football-pool' )
		);
	}

	public function __construct() {
		$classname = str_replace( '_', '', get_class( $this ) );
		parent::__construct(
			$classname, 
			( isset( $this->widget['name'] ) ? $this->widget['name'] : $classname ), 
			$this->widget['description']
		);
	}
}
