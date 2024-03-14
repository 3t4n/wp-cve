<?php

class WPEM_Single_Event extends ET_Builder_Module {

	public $slug       = 'wpem_single_event';
	public $vb_support = 'off';

	protected $module_credits = array(
		'module_uri' => 'www.wp-eventmanager.com',
		'author'     => 'Wpem Team',
		'author_uri' => 'www.wp-eventmanager.com',
	);

	public function init() {
		$this->name = esc_html__( 'Single Event', 'wpem-wpem-event-dashboard' );
	}

	public function get_fields() {
		$args = array(
				'post_type'		=> 'event_listing',
				'post_status'	=> 'publish',
				'posts_per_page'=> -1,
				'suppress_filters' => 0,
		);

		$events = get_posts( $args );

		$options = [];
		if(!empty($events))
		{
			foreach ($events as $event) {
				$options[$event->ID] = $event->post_title;
			}
		}
		else
		{
			$options[] = __( 'Not Found Event', 'wp-event-manager' );
		}
		return array(
			'event_id'                => array(
				'label'            => esc_html__( 'Select Event', 'wp-event-manager-divi-elements' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => $options,
				'default_on_front' => '',
				'description'      => esc_html__( 'Choose which type of event view you would like to display.', 'wp-event-manager-divi-elements' ),
				'toggle_slug'      => 'main_content',
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {

		$event_id = $this->props['event_id'];
		$shortcode = sprintf(
			'[event id="%1$s"]',
			esc_attr( $event_id ),
		);
		$output_event = do_shortcode( $shortcode );
		$output = sprintf(
			'<div>
				%1$s
			</div>',
			$output_event
		);
		return $output;
	}
}

new WPEM_Single_Event;