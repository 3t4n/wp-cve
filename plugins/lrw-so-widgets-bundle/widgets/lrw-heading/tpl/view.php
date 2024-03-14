<?php
echo '<div class="lrw-heading">
		<' . $instance['heading_type'] . ' class="custom-heading heading-align' . ( $instance['url_active'] == 'yes' && ! empty( $instance['url_settings']['hover'] ) ? ' has-hover' : '' ) . '">' .
			( $instance['url_active'] == 'yes' && $instance['url_settings']['url'] ? '<a class="heading-link" href="' . sow_esc_url( $instance['url_settings']['url'] ) . '" ' . ( $instance['url_settings']['new_window'] ? 'target="_blank"' : '' ) . '>' : '' ) .
				wp_kses_post( $instance['title'] ) .
			( $instance['url_active'] == 'yes' && $instance['url_settings']['url'] ? '</a>' : '' ) .
		'</' . $instance['heading_type'] . '>' .
	'</div>';