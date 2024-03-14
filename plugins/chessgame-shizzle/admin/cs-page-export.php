<?php /*
 *
 * Export page for Chessgame Shizzle admin.
 * Lets the user export chessgames from the chessgame post_type.
 *
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


function chessgame_shizzle_menu_export() {
	add_submenu_page('edit.php?post_type=cs_chessgame', esc_html__('Export', 'chessgame-shizzle'), esc_html__('Export', 'chessgame-shizzle'), 'manage_options', 'cs_export', 'chessgame_shizzle_page_export');
}
add_action( 'admin_menu', 'chessgame_shizzle_menu_export', 19 );


/*
 * Export all chessgames to PGN file(s).
 *
 * @since 1.1.8
 */
function chessgame_shizzle_page_export() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	}

	/*
	 * Build the Page and add a metabox.
	 */
	?>
	<div class="wrap chessgame_shizzle">
		<h1><?php esc_html_e('Export chessgames to a PGN file', 'chessgame-shizzle'); ?></h1>

		<div id="poststuff" class="chessgame_shizzle_export metabox-holder">
			<div class="postbox-container">

					<?php
					add_meta_box('chessgame_shizzle_export_postbox', esc_html__('Export chessgames to a PGN file', 'chessgame-shizzle'), 'chessgame_shizzle_export_postbox', 'chessgame_shizzle_export', 'normal');
					do_meta_boxes( 'chessgame_shizzle_export', 'normal', '' );
					?>

			</div>
		</div>
	</div>

	<?php
}


function chessgame_shizzle_export_postbox() {

	$count_object = wp_count_posts( 'cs_chessgame', '' );
	$count_array = (array) $count_object;
	$count_total = 0;
	foreach ( $count_array as $count ) {
		$count_total = ( $count_total + $count );
	}
	$num_entries = 1000;
	$parts = (int) ceil( $count_total / $num_entries );

	$eco_dropdown = chessgame_shizzle_get_dropdown_openingcodes( '', 'cs-export-eco' );

	$category_args = array(
		'show_option_all'   => '',
		'show_option_none'  => esc_html__( 'Select...', 'chessgame-shizzle'),
		'orderby'           => 'name',
		'order'             => 'DESC',
		'show_count'        => 1,
		'hide_empty'        => 1,
		'child_of'          => 0,
		'exclude'           => '',
		'echo'              => 0,
		'selected'          => 0,
		'hierarchical'      => 0,
		'name'              => 'cs-export-category',
		'id'                => '',
		'class'             => 'cs-export-category',
		'depth'             => 0,
		'tab_index'         => 0,
		'taxonomy'          => 'cs_category',
		'hide_if_empty'     => true,
		'option_none_value' => 0,
		'value_field'       => 'term_id',
		'required'          => false,
	);
	$category_dropdown = wp_dropdown_categories( $category_args );
	if ( strlen($category_dropdown) === 0 ) {
		$category_dropdown = esc_html__( 'No Categories found', 'chessgame-shizzle');
	}

	$tag_args = array(
		'show_option_all'   => '',
		'show_option_none'  => esc_html__( 'Select...', 'chessgame-shizzle'),
		'orderby'           => 'name',
		'order'             => 'DESC',
		'show_count'        => 1,
		'hide_empty'        => 1,
		'child_of'          => 0,
		'exclude'           => '',
		'echo'              => 0,
		'selected'          => 0,
		'hierarchical'      => 0,
		'name'              => 'cs-export-tag',
		'id'                => '',
		'class'             => 'cs-export-tag',
		'depth'             => 0,
		'tab_index'         => 0,
		'taxonomy'          => 'cs_tag',
		'hide_if_empty'     => true,
		'option_none_value' => 0,
		'value_field'       => 'term_id',
		'required'          => false,
	);
	$tag_dropdown = wp_dropdown_categories( $tag_args );
	if ( strlen($tag_dropdown) === 0 ) {
		$tag_dropdown = esc_html__( 'No Tags found', 'chessgame-shizzle');
	}

	?>

	<form name="chessgame_shizzle_export" id="chessgame_shizzle_export" method="POST" action="#" accept-charset="UTF-8">
		<input type="hidden" name="chessgame_shizzle_page" value="chessgame_shizzle_export" />
		<input type="hidden" name="chessgame_shizzle_export_part" class="chessgame_shizzle_export_part" value="1" />
		<input type="hidden" name="chessgame_shizzle_export_parts" class="chessgame_shizzle_export_parts" value="<?php echo (int) $parts; ?>" />

		<?php
		/* Nonce */
		$nonce = wp_create_nonce( 'chessgame_shizzle_nonce_export' );
		echo '<input type="hidden" id="chessgame_shizzle_nonce_export" name="chessgame_shizzle_nonce_export" value="' . esc_attr( $nonce ) . '" />';

		if ( $count_total === 0 ) { ?>
			<p><?php esc_html_e('No chessgames were found.', 'chessgame-shizzle'); ?></p><?php
		} else {
			?>
			<p>
				<?php
				/* translators: %d is the number of entries */
				echo esc_html( sprintf( _n( '%d chessgame was found and can be exported.', '%d chessgames were found and can be exported.', (int) $count_total, 'chessgame-shizzle' ), (int) $count_total ) );
				echo '<br />';
				/* translators: %d is the number of file parts */
				echo esc_html( sprintf( _n( 'The download will happen in a PGN file in maximum %d part.', 'The download will happen in a PGN file in maximum %d parts.', (int) $parts, 'chessgame-shizzle' ), (int) $parts ) );
				?>
			</p>

		<table>
			<tbody>

				<tr>
					<td>
						<label class="cs-export-category" for="cs-export-category">
							<span class="cs-chessgame-title"><?php esc_html_e('Category', 'chessgame-shizzle' ); ?></span>
						</label>
					</td>
					<td>
						<?php echo $category_dropdown; ?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="cs-export-tag" for="cs-export-tag">
							<span class="cs-chessgame-title"><?php esc_html_e('Tag', 'chessgame-shizzle' ); ?></span>
						</label>
					</td>
					<td>
						<?php echo $tag_dropdown; ?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="cs-export-eco" for="cs-export-eco">
							<span class="cs-chessgame-title"><?php esc_html_e('Opening code', 'chessgame-shizzle' ); ?></span>
						</label>
					</td>
					<td>
						<?php echo $eco_dropdown; ?>
					</td>
				</tr>

				<tr>
					<td>
						<label class="cs-export-puzzle" for="cs-export-puzzle">
							<span class="cs-chessgame-title"><?php esc_html_e('Puzzle', 'chessgame-shizzle' ); ?></span>
						</label>
					</td>
					<td>
						<input type="checkbox" name="cs-export-puzzle" class="cs-export-puzzle" />
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<p>
							<label for="start_export_enable" class="selectit">
								<input id="start_export_enable" name="start_export_enable" type="checkbox">
								<?php esc_html_e('Export chessgames from this website.', 'chessgame-shizzle'); ?>
							</label>
						</p>
						<p class="chessgame_shizzle_export_gif_container">
							<input name="chessgame_shizzle_start_export" class="chessgame_shizzle_start_export button" type="submit" disabled value="<?php esc_attr_e('Start export', 'chessgame-shizzle'); ?>" />
							<span class="chessgame_shizzle_export_gif"></span>
						</p>
					</td>
				</tr>

			</tbody>
		</table><?php

		}
		?>

	</form>

	<?php
}


function chessgame_shizzle_export_action() {
	if ( is_admin() ) {
		if ( isset( $_POST['chessgame_shizzle_page']) && $_POST['chessgame_shizzle_page'] === 'chessgame_shizzle_export' ) {
			chessgame_shizzle_export_callback();
		}
	}
}
add_action( 'admin_init', 'chessgame_shizzle_export_action' );



/*
 * Callback function for request generated from the Export page.
 */
function chessgame_shizzle_export_callback() {

	if ( ! current_user_can('manage_options') ) {
		echo 'error, no permission.';
		die();
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['chessgame_shizzle_nonce_export']) ) {
		$verified = wp_verify_nonce( $_POST['chessgame_shizzle_nonce_export'], 'chessgame_shizzle_nonce_export' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		esc_html_e('Nonce check failed. Please go back and try again.', 'chessgame-shizzle');
		die();
	}

	// Total count in database.
	$count_object = wp_count_posts( 'cs_chessgame', '' );
	$count_array = (array) $count_object;
	$count_total = 0;
	foreach ( $count_array as $count ) {
		$count_total = ( $count_total + $count );
	}
	$num_entries = 1000;
	$parts = (int) ceil( $count_total / $num_entries );

	if ( isset( $_POST['chessgame_shizzle_export_part']) && ( (int) $_POST['chessgame_shizzle_export_part'] < ( $parts + 1 ) ) ) {
		$part = (int) $_POST['chessgame_shizzle_export_part'];
	} else {
		echo '(Chessgame Export) Wrong part requested.';
		die();
	}

	if ( $count_total === 0 ) {
		echo '(Chessgame Export) No games found.';
		die();
	}
	$offset = ( $part * $num_entries ) - $num_entries;

	$tax_query  = array();
	$meta_query = array();

	/* Category select */
	if ( isset($_POST['cs-export-category']) && is_numeric($_POST['cs-export-category']) && $_POST['cs-export-category'] > 0 ) {
		$category = (int) $_POST['cs-export-category'];
		$tax_query[] = array(
			'taxonomy' => 'cs_category',
			'field'    => 'term_id',
			'terms'    => $category,
		);
	}

	/* Tag select */
	if ( isset($_POST['cs-export-tag']) && is_numeric($_POST['cs-export-tag']) && $_POST['cs-export-tag'] > 0 ) {
		$tag = (int) $_POST['cs-export-tag'];
		$tax_query[] = array(
			'taxonomy' => 'cs_tag',
			'field'    => 'term_id',
			'terms'    => $tag,
		);
	}

	/* ECO select */
	if ( isset($_POST['cs-export-eco']) && strlen($_POST['cs-export-eco']) === 3 ) {
		$eco = sanitize_text_field( $_POST['cs-export-eco'] );
		$meta_query[] = array(
			'key'   => 'cs_chessgame_code',
			'value' => $eco,
		);
	}

	/* puzzle checkbox */
	if ( isset($_POST['cs-export-puzzle']) ) {
		$meta_query[] = array(
			'key'   => 'cs_chessgame_puzzle',
			'value' => true,
		);
	}

	$the_query = new WP_Query( array(
		'post_type'              => 'cs_chessgame',
		'post_status'            => 'any',
		'posts_per_page'         => $num_entries,
		'paged'                  => $part,
		'offset'                 => $offset,
		'meta_query'             => $meta_query,
		'tax_query'              => $tax_query,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		) );


	// Clean everything before here
	ob_end_clean();

	// Output headers so that the file is downloaded rather than displayed
	$filename = 'chessgame_shizzle_export_' . C_SHIZZLE_VER . '_' . date('Y-m-d_H-i') . '-part_' . $part . '_of_' . $parts . '.pgn';
	header( 'Content-Type: text/txt; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . $filename );

	// Create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	if ( $the_query->have_posts() ) {

		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$post_id = get_the_ID();
			$cs_chessgame_pgn = get_post_meta( $post_id, 'cs_chessgame_pgn', true );
			$cs_chessgame_pgn = chessgame_shizzle_sanitize_pgn( $cs_chessgame_pgn );
			$cs_chessgame_pgn = chessgame_shizzle_update_pgn_from_meta( $cs_chessgame_pgn, $post_id );

			if ( strlen( $cs_chessgame_pgn ) > 0 ) {
				$pgn = "\n" . $cs_chessgame_pgn . "\n";
				fwrite( $output, $pgn );
			}
		}
	} else {
		// Assume the requested part is not needed, due to filters being used and the total number of games is lower than the total games in the database.
		// We still need to return a file otherwise the form.submit will end up in a navigation in the browser.
		$text = esc_html__('No chessgames found for this file part with these filters.', 'chessgame-shizzle');
		fwrite( $output, $text );
	}

	fclose($output);
	die();

}
