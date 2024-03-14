<?php
/**
 * Get the question and display text.
 */

$question_layout = get_post_meta( $reading_id, CO_QUESTION_LAYOUT, true );
$question_text   = get_post_meta( $reading_id, 'question_text', true );
$target_blank    = get_post_meta( $reading_id, CO_TARGET_BLANK, true ) ? 'target=_blank' : '';

// If the layout is blank set it left style as default.
if ( ! $question_layout ) {
	$question_layout = 'card-oracle-form-left';
}

// Is reading an auto submit reading.
if ( get_post_meta( $reading_id, CO_AUTO_SUBMIT, true ) === 'yes' ) {
	$auto_submit = 'hiddenreadingsubmit';
} else {
	$auto_submit = '';
}

// Initial screen show question (if required) and backs of cards.
if ( getenv( 'REQUEST_METHOD' ) === 'GET' ) {

	$new_card_ids = array();

	// Get the image for the back of the card.
	$card_back_url = wp_get_attachment_url( get_post_thumbnail_id( $reading_id ) );

	if ( empty( $card_back_url ) ) {
		$card_back_url = plugin_dir_url( __DIR__ ) . 'assets/images/cardback.png';
	}

	// Get all the published cards for this reading.
	$card_ids = get_cards_for_reading( $reading_id );

	// The number of cards returned.
	$card_count = count( $card_ids );

	if ( 0 !== $card_count ) {
		$i       = 0;
		$percent = (int) get_post_meta( $reading_id, CO_REVERSE_PERCENT, true );

		foreach ( $card_ids as $card_id ) {
			$is_upright = wp_rand( 0, 100 ) < $percent ? 0 : 1;

			$new_card_ids[ $i ] = array(
				'ID'      => $card_id,
				'Upright' => $is_upright,
				'Image'   => wp_get_attachment_url( get_post_thumbnail_id( $card_id ) ),
			);
			$i++;
		}
	}

	// Get just the card ids and shuffle them.
	shuffle( $new_card_ids );

	// Display the form.
	echo '<div class="card-oracle-container alignwide">';
	echo '<div class="data" data-positions="' . esc_attr( $positions_count ) . '"></div>';
	echo '<div class="' . esc_attr( $question_layout ) . '">';

	echo '<form name="card-oracle-question" method="post" ' . esc_attr( $target_blank ) . '>';
	wp_nonce_field( 'card-oracle-reading-nonce' );

	if ( get_post_meta( $reading_id, DISPLAY_QUESTION, true ) === 'yes' ) {
		echo '<input name="question" id="question" type="text" size="40" placeholder="' . esc_attr( $question_text ) . '" required/>';
	}

	echo '<input name="card-oracle-picks" id="card-oracle-picks" type="hidden"><input name="reverse" id="reverse" type="hidden">';

	// Display the text in the Reading Text to display before Cards field.
	echo '<div>' . wp_kses_post( html_entity_decode( get_post_meta( $reading_id, 'before_cards_text', true ) ) ) . '</div>';

	// Get the layout to display the back of the cards.
	$layout = get_post_meta( $reading_id, CO_DECK_LAYOUT, true );

	echo card_oracle_layout_html( $new_card_ids, $card_count, $card_back_url, $layout ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */

	// Display the button at the bottom.
	echo '<div id="submitbuttondiv" class="btn-block ' . esc_attr( $auto_submit ) . '">';
	echo '<button name="readingsubmit" type="submit" id="readingsubmit">' . esc_html__( 'Submit', 'card-oracle' ) . '</button>';
	echo '</div></form></div>';
}
