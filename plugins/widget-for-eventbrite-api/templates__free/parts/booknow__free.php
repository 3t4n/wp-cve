<?php
/**
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */
if ( property_exists( $data->event, 'cta' ) ) {
	$cta_text = $data->event->cta->text;
} else {
	$cta_text = $data->utilities->get_element( 'booknow_text', $data->args );
}
if ( $data->utilities->get_element( 'booknow', $data->args ) ) {
	?>
    <div class="eaw-booknow"> <?php
	switch ( $data->template ) {
		case 'divi':
			$button_markup = '<a %1$s class="wfea-button submit et_pb_button" %3$s  aria-label="%2$s %5$ %4$s">%2$s</a>';
			break;
		default:
			$button_markup = '<a %1$s class="wfea-button button" %3$s  aria-label="%2$s %5$ %4$s">%2$s</a>';
	}
	printf( $button_markup,
		$data->event->booknow,
		wp_kses_post( $cta_text ),
		( $data->utilities->get_element( 'newtab', $data->args ) ) ? 'target="_blank"' : '',
		esc_attr( get_the_title() ),
		__( 'on Eventbrite for', 'widget-for-eventbrite-api' )
	);
	?></div><?php
}
