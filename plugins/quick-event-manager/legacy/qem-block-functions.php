<?php
function qem_block_init() {

	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	wp_register_script(
		'qem_block',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
	);

	register_block_type(
		'quick-event-manager/eventlist', array(
			'title'           => 'Event List',
			'editor_script'   => 'qem_block',
			'render_callback' => 'qem_event_shortcode',
			'attributes'      => array(
				'id' => array(
					'type' => 'string'
				),
			),
		)
	);

	register_block_type(
		'quick-event-manager/calendar', array(
			'title'           => 'Event Calendar',
			'editor_script'   => 'qem_block',
			'render_callback' => 'qem_show_calendar'
		)
	);
}