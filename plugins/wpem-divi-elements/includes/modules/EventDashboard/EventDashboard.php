<?php

class WPEM_Event_Dashboard extends ET_Builder_Module {

	public $slug       = 'wpem_event_dashboard';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'www.wp-eventmanager.com',
		'author'     => 'Wpem Team',
		'author_uri' => 'www.wp-eventmanager.com',
	);

	public function init() {
		$this->name = esc_html__( 'Event Dashboard', 'wpem-wpem-event-dashboard' );
	}

	public function get_fields() {
		return array(
			'posts_per_page'        => array(
				'default'          => '10',
				'label'            => esc_html__( 'Events per page', 'wp-event-manager-divi-elements' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'description'      => esc_html__( 'Define the number of events that should be displayed per page.', 'wp-event-manager-divi-elements' ),
				'computed_affects' => array(
					'__event_listing',
				),
				'toggle_slug'      => 'main_content',
			),
		);
	}
	public function render( $attrs, $content, $render_slug ) {

		$posts_number            = $this->props['posts_per_page'];
		$shortcode = sprintf(
			'[event_dashboard posts_per_page="%1$s"]',
			esc_attr( $posts_number ),
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

new WPEM_Event_Dashboard;