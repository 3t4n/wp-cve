<?php
/**
 * Demo Data class
 *
 * @author  Christopher Graham
 * @package Card_Oracle/Admin/DemoData
 * @version 1.0
 */

/**
 * CardOracleDemoData Class.
 *
 * @since 1.1.2
 */
class CardOracleDemoData {
	/**
	 * Get the Demo Data images and add them as the featured image.
	 *
	 * @since 0.13.0
	 * @param int    $post_id The ID of the post.
	 * @param string $image_name The filename of the image.
	 * @return $success If the image was successfully added.
	 */
	private function feature_image( $post_id, $image_name ) {
		global $co_logs;

		$filename = 'card-oracle-' . basename( $image_name );
		$image    = CARD_ORACLE_DIR . $image_name;
		$title    = get_the_title( $post_id );

		if ( file_exists( $image ) ) {
			if ( post_exists( $image_name ) ) {
				$page            = get_page_by_title( $image_name, OBJECT, 'attachment' );
				$attachment_id   = $page->ID;
				$destination     = get_attached_file( $attachment_id );
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $destination );
			} else {
				$upload    = wp_upload_bits( $filename, null, file_get_contents( $image, FILE_USE_INCLUDE_PATH ) );  // @codingStandardsIgnoreLine
				$file      = $upload['file'];
				$file_type = wp_check_filetype( $file, null );
				// Attachment atrributes for the file.
				$attachment = array(
					'post_mime_type' => $file_type['type'],
					'post_title'     => sanitize_text_field( 'Card Oracle ' . $title ),
					'post_content'   => $title . ' image.',
					'post_status'    => 'inherit',
				);
				// Insert and return attachment id.
				$attachment_id = wp_insert_attachment( $attachment, $file, $post_id );
				// Insert and return attachment metadata.
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $file );
			}

			// Update and return attachment metadata.
			wp_update_attachment_metadata( $attachment_id, $attachment_data );

			// Update the Alt Text.
			update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

			// Associate the image to the post.
			return set_post_thumbnail( $post_id, $attachment_id );
		}
	}

	/**
	 * Get the json data.
	 *
	 * @since 0.26.0
	 * @param string $filename Name of the file to import starting from the plugin base directory.
	 * @return array $json Return the json data from the file.
	 */
	public function import_json_data( $filename ) {
		global $co_logs, $co_notices;

		$json       = array();
		$data_file  = CARD_ORACLE_URL . $filename;
		$local_file = CARD_ORACLE_DIR . $filename;
		$co_logs->add( 'Importing File', $data_file, null, 'event' );

		$response      = wp_remote_get( $data_file );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			// Log the failure.
			$error = sprintf( 'Unable to access URL [%s]. Code [%d].', $data_file, $response_code );
			$co_logs->add( 'URL Demo Data', $error, null, 'error' );

			// Failed to get the data file via the URL, try using the filesystem.
			$local_json = file_get_contents( $local_file );  // @codingStandardsIgnoreLine

			if ( false === $local_json ) {
				// Log the failure.
				/* translators: %s is a filename. */
				$error = sprintf( __( 'Unable to access file [%s].', 'card-oracle' ), $local_file );
				$co_logs->add( 'Local Demo Data', $error, null, 'error' );

				/* translators: %1$d is a number, %2$s is a URL, %3$s is a filename. */
				$notice = esc_html( sprintf( __( 'Error Code: %1$d; URL: %2$s; File: %3$s', 'card-oracle' ), $response_code, $data_file, $local_file ) );
				$co_notices->add( 'demo-data-file-missing', $notice, 'error' );
			} else {
				$json = json_decode( $local_json, true );
			}
		} else {
			$json = json_decode( $response['body'], true );
		}

		return $json;
	}

	/**
	 * Insert the json data into the custom post types.
	 *
	 * @since 0.16.0
	 * @param array $data Data to insert in json format.
	 */
	public function insert_data( $data ) {
		global $co_logs, $co_notices;

		$co_logs->add( DEMO_DATA, 'Inserting data.', null, 'event' );

		$percentage   = isset( $data['reading']['reversed'] ) ? $data['reading']['reversed'] : 0;
		$reading_args = array(
			'post_title'  => $data['reading']['name'],
			'post_type'   => 'co_readings',
			'post_status' => 'publish',
			'meta_input'  => array(
				CO_REVERSE_PERCENT => $percentage,
			),
		);

		// Insert the Reading.
		$co_logs->add( DEMO_DATA, 'Inserting Reading.', null, 'event' );

		$reading_id = wp_insert_post( $reading_args );

		if ( isset( $data['reading']['image'] ) ) {
			$this->feature_image( $reading_id, $data['reading']['image'] );
		}

		// Insert the Positions.
		foreach ( $data['positions'] as $position ) {
			$co_logs->add( DEMO_DATA, 'Inserting Position [' . $position['name'] . '].', null, 'event' );

			$position_id = wp_insert_post(
				array(
					'post_title'  => $position['name'],
					'post_type'   => 'co_positions',
					'post_status' => 'publish',
					'meta_input'  => array(
						CO_READING_ID => $reading_id,
						CO_CARD_ORDER => $position['order'],
					),
				)
			);

			$positions[ $position['name'] ] = $position_id;
		}

		// Insert the Cards.
		foreach ( $data['cards'] as $card ) {
			$co_logs->add( DEMO_DATA, 'Inserting Card [' . $card['name'] . '].', null, 'event' );

			$content = isset( $card['description'] ) ? $card['description'] : '';

			$card_id = wp_insert_post(
				array(
					'post_title'   => $card['name'],
					'post_type'    => 'co_cards',
					'post_status'  => 'publish',
					'post_content' => $content,
					'meta_input'   => array(
						CO_READING_ID => $reading_id,
					),
				)
			);

			if ( isset( $card['image'] ) ) {
				$this->feature_image( $card_id, $card['image'] );
			}

			// Insert a Description for each Card for each of the Positions.
			foreach ( $positions as $key => $value ) {
				$co_logs->add( DEMO_DATA, 'Inserting Descriptions [' . $card['name'] . ' - ' . $key . '].', null, 'event' );

				$upright  = isset( $card['upright'][0][ $key ] ) ? $card['upright'][0][ $key ] : '';
				$reversed = isset( $card['reverse'][0][ $key ] ) ? $card['reverse'][0][ $key ] : '';

				wp_insert_post(
					array(
						'post_title'   => $card['name'] . ' - ' . $key,
						'post_type'    => 'co_descriptions',
						'post_status'  => 'publish',
						'post_content' => $upright,
						'meta_input'   => array(
							CO_CARD_ID             => $card_id,
							CO_POSITION_ID         => $value,
							CO_REVERSE_DESCRIPTION => $reversed,
						),
					)
				);
			}
		}
	}

	/**
	 * Insert all the card images.
	 *
	 * @since 1.1.2
	 * @param array $images The array of images.
	 */
	public function insert_images( $images ) {
		global $co_logs;

		foreach ( $images['images'] as $element ) {
			$image_name = $element['image'];

			$filename  = 'card-oracle-' . basename( $image_name );
			$image     = CARD_ORACLE_DIR . $image_name;
			$card_name = $element['name'];
			$title     = 'Card Oracle ' . $card_name;

			if ( file_exists( $image ) && ! post_exists( $title ) ) {
				$upload    = wp_upload_bits( $filename, null, file_get_contents( $image, FILE_USE_INCLUDE_PATH ) );  // @codingStandardsIgnoreLine
				$file      = $upload['file'];
				$file_type = wp_check_filetype( $file, null );
				// Attachment atrributes for the file.
				$attachment = array(
					'post_mime_type' => $file_type['type'],
					'post_title'     => sanitize_text_field( $title ),
					'post_content'   => sanitize_text_field( $title ) . ' image.',
					'post_status'    => 'inherit',
				);
				// Insert and return attachment id.
				$attachment_id = wp_insert_attachment( $attachment, $file );
				// Insert and return attachment metadata.
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $file );

				// Update and return attachment metadata.
				wp_update_attachment_metadata( $attachment_id, $attachment_data );

				// Update the Alt Text.
				update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $card_name ) );
			}
		}
	}
}
