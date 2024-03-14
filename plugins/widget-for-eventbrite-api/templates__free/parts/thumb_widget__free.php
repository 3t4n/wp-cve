<?php
/**
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */
if ( $data->utilities->get_element( 'thumb', $data->args ) ) {
	?>
	<div class="eaw-thumb-wrap"
	     style="min-width:<?php echo (int) $data->utilities->get_element( 'thumb_width', $data->args ) ?>px">
                <span>
                 <?php
                 // Check if post has post thumbnail.
                 if ( ! empty( $data->events->post->logo_url ) ) {
	                 // Thumbnails
	                 printf( '<a class="eaw-img %2$s" %1$s rel="bookmark" %6$s><img class="%2$s eaw-thumb eaw-default-thumb" src="%3$s" alt="%4$s" width="%5$s"></a>',
		                 $data->event->booknow,
		                 esc_attr( $data->utilities->get_element( 'thumb_align', $data->args ) ),
		                 esc_url( $data->events->post->logo_url ),
		                 esc_attr( get_the_title() ),
		                 (int) $data->utilities->get_element( 'thumb_width', $data->args ),
		                 ( $data->utilities->get_element( 'newtab', $data->args ) ) ? 'target="_blank"' : ''
	                 );

	                 // Display default image.
                 } elseif ( ! empty( $data->utilities->get_element( 'thumb_default', $data->args ) ) ) {
	                 printf( '<a class="eaw-img %2$s" %1$s rel="bookmark" %6$s><img class="%2$s eaw-thumb eaw-default-thumb" src="%3$s" alt="%4$s" width="%5$s"></a>',
		                 $data->event->booknow,
		                 esc_attr( $data->utilities->get_element( 'thumb_align', $data->args ) ),
		                 esc_url( $data->utilities->get_element( 'thumb_default', $data->args ) ),
		                 esc_attr( get_the_title() ),
		                 (int) $data->utilities->get_element( 'thumb_width', $data->args ),
		                 ( $data->utilities->get_element( 'newtab', $data->args ) ) ? 'target="_blank"' : ''
	                 );
                 }
                 ?>
                 </span>
	</div>
	<?php
}

