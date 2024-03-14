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
	$description_html    = '';
	$user_question       = isset( $_POST['question'] ) ? sanitize_text_field( wp_unslash( $_POST['question'] ) ) : '';
	$layout_table        = get_post_meta( $reading_id, CO_LAYOUT_TABLE, true );


	// Process order if required.
	$paid_reading = card_oracle_process_order();

	// Get the key of the Presentation array to be used to get the filename.
	$key = array_search( $presentation, array_column( $presentation_layouts, 'uid' ) );
	// Get the filename from the Presentation array in get_presentation_layouts function.
	$filename = $presentation_layouts[ $key ]['file'];
	$class    = $presentation_layouts[ $key ]['class'];
	$layout   = $presentation_layouts[ $key ]['layout'];

	echo '<div class="wrap alignwide">';
	if ( ! empty( $user_question ) ) {
		echo '<h2>' . esc_html( $question_text ) . '</h2><h3>' . esc_html( $user_question ) . '</h3>';
	}

	// Create Header html.
	$header_html = '<div class="wrap alignwide">';
	$grid_html   = '<div class="wrap alignwide"><div class="card-oracle-1-column-grid"><div class="card-oracle-' . $class . '-layout-' . $layout . '">';

	if ( 'yes' === $layout_table ) {
		$grid_html       = '<div class="wrap alignwide"><div class="card-oracle-2-column-grid"><div class="card-oracle-2-column-left"><div class="card-oracle-' . $class . '-layout-' . $layout . '">';
		$grid_table_html = '<div class="card-oracle-2-column-right"><table class="card-oracle-table"><thead><tr><th>Position</th><th>Card</th></tr></thead><tbody>';
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

		$description_id = get_posts( $args );
		$the_title      = get_the_title( $cards[ $i ] );
		$image          = wp_get_attachment_url( get_post_thumbnail_id( $cards[ $i ] ) );
		$reverse_class  = in_array( $cards[ $i ], $reverse_cards, true ) ? ' card-oracle-rotate-image' : '';

		$grid_html .= '<div class="card-oracle-' . $class . '-layout-card-' . ( $i + 1 ) . ' ' . $reverse_class . '"><img src="' . $image . '" alt="tarot card" /></div>';

		if ( 'yes' === $layout_table ) {
			$grid_table_html .= '<tr><td>' . $positions[ $i ]->post_title . '</td><td>' . $the_title . '</td></tr>';
		}

		// If there is a description add it to the page and email otherwise skip it.
		if ( $description_id ) {
			$description_content = apply_filters(
				'the_content',
				( $reverse_class ? $description_id[0]->_co_reverse_description : $description_id[0]->post_content )
			);
			$main_text           = '<div class="card-oracle-presentation-main"><h3>' . $the_title . '</h3>' . $description_content . '</div>';
		} else {
			$main_text = '<div class="card-oracle-presentation-main"><h3>' . $the_title . '</h3></div>';
		}

		// Add the Image, Card title, and the Position description to the page.
		$description_html .= '<div class="card-oracle-presentation-grid alignwide"><div class="card-oracle-presentation-header">' .
			$position_text . '</div><div class="card-oracle-presentation-image' . $reverse_class . '"><img src="' . $image . '" loading="lazy" alt="front of card"></div>' . $main_text . '</div>';

		/* Create the partial page after the first card */
		if ( 0 == $i ) {
			$first_card = $description_html;
		}
	}

	$grid_html .= '</div>';

	if ( 'yes' === $layout_table ) {
		$grid_html       .= '</div>';
		$grid_table_html .= '</tbody></table></div></div>';
	}

	// Create Paid display.
	$paid_display .= $grid_html;

	// Display code.
	echo wp_kses_post( $grid_html );

	if ( 'yes' === $layout_table ) {
		$paid_display .= $grid_table_html;
		echo wp_kses_post( $grid_table_html );
	} else {
		$paid_display .= '</div>';
		echo '</div>';
	}

	echo wp_kses_post( $description_html );

	$paid_display .= $first_card . '</div>';
	echo '</div>';

	echo wp_kses( ( new CardOracleEmail() )->add_email_button( $description_html, $reading_id ), card_oracle_allowed_html() );
} // End POST submit
