<?php
/**
 * Include the standard card selection screen.
 */

require_once CARD_ORACLE_DIR . 'public/includes/card-oracle-selection-standard.php';
require_once CARD_ORACLE_DIR . 'includes/class-card-oracle-email.php';

// Post submitted display cards and descriptions.
if ( isset( $_POST['readingsubmit'] ) && isset( $_POST['_wpnonce'] ) && ( wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'card-oracle-reading-nonce' ) ) ) {
	$cards               = isset( $_POST['card-oracle-picks'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['card-oracle-picks'] ) ) ) : array();
	$card_count          = count( $cards );
	$reverse_cards       = isset( $_POST['reverse'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['reverse'] ) ) ) : array();
	$description_content = '';
	$user_question       = isset( $_POST['question'] ) ? sanitize_text_field( wp_unslash( $_POST['question'] ) ) : '';

	$wrapper_class = STANDARD_MOBILE === $presentation ? 'mobile-wrapper' : 'cotd-wrapper alignwide';

	// Process order if required.
	$paid_reading = card_oracle_process_order();

	// Get the key of the Presentation array to be used to get the filename.
	$key = array_search( $presentation, array_column( $presentation_layouts, 'uid' ) );
	// Get the filename from the Presentation array in get_presentation_layouts function.
	$filename = $presentation_layouts[ $key ]['file'];

	echo '<div class="wrap alignwide">';
	if ( ! empty( $user_question ) ) {
		echo '<h2>' . esc_html( $question_text ) . '</h2><h3>' . esc_html( $user_question ) . '</h3>';
	}

	for ( $i = 0; $i < $card_count; $i++ ) {
		$args = array(
			'post_type'   => 'co_descriptions',
			'post_status' => 'publish',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => CO_CARD_ID,
					'value' => $cards[ $i ],
				),
				array(
					'key'   => CO_POSITION_ID,
					'value' => $positions[ $i ]->ID,
				),
			),
		);

		// Get the position text to display to the user.
		switch ( $positions[ $i ]->_co_position_text ) {
			case 'posttext':
				$position_text = html_entity_decode( $positions[ $i ]->post_content );
				break;

			case 'notext':
				$position_text = '';
				break;

			default:
				$position_text = esc_html( $positions[ $i ]->post_title );
				break;
		}

		$description_content = '';
		$description_id      = get_posts( $args );
		$image               = wp_get_attachment_url( get_post_thumbnail_id( $cards[ $i ] ) );
		$reverse_class       = in_array( $cards[ $i ], $reverse_cards, true ) ? ' card-oracle-rotate-image' : '';

		// If there is a description add it to the page and email otherwise skip it.
		if ( $description_id ) {
			$description_content = apply_filters(
				'the_content',
				( $reverse_class ? $description_id[0]->_co_reverse_description : $description_id[0]->post_content )
			);
		}

		$main_text = '<div class="cotd-main"><h3>' . get_the_title( $cards[ $i ] ) . '</h3>' . $description_content . '</div>';

		// Add the Image, Card title, and the Position description to the page.
		echo '<div class="' . esc_attr( $wrapper_class ) . '"><div class="cotd-header">' . esc_attr( $position_text ) . '</div><div class="cotd-aside' .
		esc_attr( $reverse_class ) . '"><img src="' . esc_attr( $image ) . '" loading="lazy" alt="front of card"></div>' . wp_kses_post( $main_text ) . '</div>';

		/* Create the partial page after the first card */
		if ( 0 === $i ) {
			$paid_display = ob_get_contents();
		}
	}
	echo '</div>';

	$description_html = ob_get_contents();

	// Add email button to page if option enabled.
	echo wp_kses( ( new CardOracleEmail() )->add_email_button( ob_get_contents(), $reading_id ), card_oracle_allowed_html() );

} // End POST submit
