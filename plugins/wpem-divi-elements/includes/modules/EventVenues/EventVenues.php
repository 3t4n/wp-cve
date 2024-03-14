<?php

class WPEM_Event_Venues extends ET_Builder_Module {
	
	public $slug       = 'wpem_event_venues';
	public $vb_support = 'off';

	protected $module_credits = array(
		'module_uri' => 'www.wp-eventmanager.com',
		'author'     => 'WPEM Team',
		'author_uri' => 'www.wp-eventmanager.com',
	);

	public function init() {
		$this->name = esc_html__( 'Event Venues', 'wp-event-manager-divi-elements' );
	}

	public function get_fields() {
		return array(
			'order'             => array(
				'label'            => esc_html__( 'Order', 'wp-event-manager-divi-elements' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'ASC' => esc_html__( 'Ascending', 'wp-event-manager-divi-elements' ),
					'DESC' => esc_html__( 'Descending', 'wp-event-manager-divi-elements' ),
					
				),
				'default_on_front' => 'ASC',
				'description'      => esc_html__( 'Choose how your events should be ordered.', 'wp-event-manager-divi-elements' ),
				'toggle_slug'      => 'main_content',
			),
			'orderby'             => array(
				'label'            => esc_html__( 'Orderby', 'wp-event-manager-divi-elements' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'ID' => esc_html__( 'ID', 'wp-event-manager-divi-elements' ),
					'title' => esc_html__( 'Title', 'wp-event-manager-divi-elements' ),
					'name' => esc_html__( 'Name', 'wp-event-manager-divi-elements' ),
					'modified' => esc_html__( 'Modified', 'wp-event-manager-divi-elements' ),
					'rand' => esc_html__( 'Random', 'wp-event-manager-divi-elements' ),
				),
				'default_on_front' => 'event_start_date',
				'description'      => esc_html__( 'Choose how your events should be ordered.', 'wp-event-manager-divi-elements' ),
				'toggle_slug'      => 'main_content',
			),
			'show_thumb'     => array(
				'label'            => esc_html__( 'Show Thumb', 'wp-event-manager-divi-elements' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'true'  => esc_html__( 'Yes', 'wp-event-manager-divi-elements' ),
					'false' => esc_html__( 'No', 'wp-event-manager-divi-elements' ),
				),
				'default'          => 'true',
				'description'      => esc_html__( 'Show Thumb on listing page or not.', 'wp-event-manager-divi-elements' ),
				'toggle_slug'      => 'main_content',
			),
			'show_count'     => array(
				'label'            => esc_html__( 'Show Count', 'wp-event-manager-divi-elements' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'true'  => esc_html__( 'Yes', 'wp-event-manager-divi-elements' ),
					'false' => esc_html__( 'No', 'wp-event-manager-divi-elements' ),
				),
				'default'          => 'true',
				'description'      => esc_html__( 'Show Count on listing page or not.', 'wp-event-manager-divi-elements' ),
				'toggle_slug'      => 'main_content',
			),
		);
	}
	public function render( $attrs, $content, $render_slug ) {

		$order = $this->props['order'];
		$orderby = $this->props['orderby'];
		$show_thumb = $this->props['show_thumb'];
		$show_count = $this->props['show_count'];

		$shortcode = sprintf(
			'[event_venues order="%1$s" orderby="%2$s" show_thumb="%3$s" show_count="%4$s"]',
			esc_attr( $order ),
			esc_attr( $orderby ),
			esc_attr( $show_thumb ),
			esc_attr( $show_count ),
		);
		
		$output_events = do_shortcode( $shortcode );
		
		$output = sprintf(
			'<div>
				%1$s
			</div>',
			$output_events
		);
		
		return $output;
	}
}

new WPEM_Event_Venues;