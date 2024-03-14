<div class="modal-window-container add-on-more-details">
	<div class="modal-window">
		<span class="close-x"></span>
		<?php
			if ( is_array( $add_on_data ) ) {
				if ( isset( $add_on_data['name'] ) ) {
					echo '<h4 class="add-on-name">' . esc_html( sanitize_text_field( $add_on_data['name'] ) ) . '</h4>';
				}

				if ( isset( $add_on_data['sections']['description'] ) ) {
					echo '<div class="more-details-section more-details-description">' . wp_kses_post( $add_on_data['sections']['description'] ) . '</div>';
				}

				if ( isset( $add_on_data['sections']['screenshots'] ) ) {
					echo '<div class="more-details-section more-details-screenshots"><h5>' . __( 'Screenshots' ) . '</h5>' . wp_kses_post( $add_on_data['sections']['screenshots'] ) . '</div>';
				}

				if ( isset( $add_on_data['sections']['licensing'] ) ) {
					echo '<div class="more-details-section more-details-licensing"><h5>' . __( 'Licensing' ) . '</h5>' . wp_kses_post( $add_on_data['sections']['licensing'] ) . '</div>';
				}
			} else if ( ! empty( $error_message ) ) {
				echo '<div class="error"><p>' . $error_message . '</p></div>';
			}
		?>
	</div>
</div>