<?php
/**
 * The file used to display the "Connections" menu in the admin area.
 *
 * @package hreflang-manager-lite
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
}

?>

<!-- process data -->

<?php

// Initialize variables -----------------------------------------------------------------------------------------------.
$dismissible_notice_a = array();

// Preliminary operations ---------------------------------------------------------------------------------------------.
global $wpdb;

// Sanitization -------------------------------------------------------------------------------------------------------.

// Actions.
$data['edit_id']        = isset( $_GET['edit_id'] ) ? intval( $_GET['edit_id'], 10 ) : null;
$data['delete_id']      = isset( $_POST['delete_id'] ) ? intval( $_POST['delete_id'], 10 ) : null;
$data['clone_id']       = isset( $_POST['clone_id'] ) ? intval( $_POST['clone_id'], 10 ) : null;
$data['update_id']      = isset( $_POST['update_id'] ) ? intval( $_POST['update_id'], 10 ) : null;
$data['form_submitted'] = isset( $_POST['form_submitted'] ) ? intval( $_POST['form_submitted'], 10 ) : null;

// Filter and search data.
$data['s'] = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : null;

// save the connection into the database.
if ( ! is_null( $data['form_submitted'] ) ) {

	// Sanitization ---------------------------------------------------------------------------------------------------.
	$url_to_connect = isset( $_POST['url_to_connect'] ) ? esc_url_raw( $_POST['url_to_connect'] ) : '';

	for ( $i = 1; $i <= 10; $i++ ) {

		${'url' . $i}      = isset( $_POST[ 'url' . $i ] ) ? esc_url_raw( $_POST[ 'url' . $i ] ) : null;
		${'language' . $i} = isset( $_POST[ 'language' . $i ] ) ? sanitize_text_field( $_POST[ 'language' . $i ] ) : null;
		${'script' . $i}   = isset( $_POST[ 'script' . $i ] ) ? sanitize_text_field( $_POST[ 'script' . $i ] ) : null;
		${'locale' . $i}   = isset( $_POST[ 'locale' . $i ] ) ? sanitize_text_field( $_POST[ 'locale' . $i ] ) : null;

	}
}

// update -------------------------------------------------------------------------------------------------------------.
if ( ! is_null( $data['form_submitted'] ) && ! is_null( $data['update_id'] ) ) {

	// Nonce verification.
	check_admin_referer( 'daexthrmal_create_update_connection', 'daexthrmal_create_update_connection_nonce' );

	// Update.
	$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
	$safe_sql   = $wpdb->prepare(
		"UPDATE $table_name SET url_to_connect = %s ,"
		. 'url1 = %s, language1 = %s, script1 = %s, locale1 = %s,'
		. 'url2 = %s, language2 = %s, script2 = %s, locale2 = %s ,'
		. 'url3 = %s, language3 = %s, script3 = %s, locale3 = %s ,'
		. 'url4 = %s, language4 = %s, script4 = %s, locale4 = %s ,'
		. 'url5 = %s, language5 = %s, script5 = %s, locale5 = %s ,'
		. 'url6 = %s, language6 = %s, script6 = %s, locale6 = %s ,'
		. 'url7 = %s, language7 = %s, script7 = %s, locale7 = %s ,'
		. 'url8 = %s, language8 = %s, script8 = %s, locale8 = %s ,'
		. 'url9 = %s, language9 = %s, script9 = %s, locale9 = %s ,'
		. 'url10 = %s, language10 = %s, script10 = %s, locale10 = %s WHERE connection_id = %d ',
		$url_to_connect,
		$url1,
		$language1,
		$script1,
		$locale1,
		$url2,
		$language2,
		$script2,
		$locale2,
		$url3,
		$language3,
		$script3,
		$locale3,
		$url4,
		$language4,
		$script4,
		$locale4,
		$url5,
		$language5,
		$script5,
		$locale5,
		$url6,
		$language6,
		$script6,
		$locale6,
		$url7,
		$language7,
		$script7,
		$locale7,
		$url8,
		$language8,
		$script8,
		$locale8,
		$url9,
		$language9,
		$script9,
		$locale9,
		$url10,
		$language10,
		$script10,
		$locale10,
		$data['update_id']
	);

	$query_result = $wpdb->query( $safe_sql );

	if ( false !== $query_result ) {
		$dismissible_notice_a[] = array(
			'message' => __( 'The connection has been successfully updated.', 'hreflang-manager-lite' ),
			'class'   => 'updated',
		);
	}
}

// Add ------------------------------------------------------------------------------------------------------------.
if ( ! is_null( $data['form_submitted'] ) && is_null( $data['update_id'] ) ) {

	// Nonce verification.
	check_admin_referer( 'daexthrmal_create_update_connection', 'daexthrmal_create_update_connection_nonce' );

	$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
	$safe_sql   = $wpdb->prepare(
		"INSERT INTO $table_name SET url_to_connect = %s ,"
		. 'url1 = %s, language1 = %s, script1 = %s, locale1 = %s,'
		. 'url2 = %s, language2 = %s, script2 = %s, locale2 = %s ,'
		. 'url3 = %s, language3 = %s, script3 = %s, locale3 = %s ,'
		. 'url4 = %s, language4 = %s, script4 = %s, locale4 = %s ,'
		. 'url5 = %s, language5 = %s, script5 = %s, locale5 = %s ,'
		. 'url6 = %s, language6 = %s, script6 = %s, locale6 = %s ,'
		. 'url7 = %s, language7 = %s, script7 = %s, locale7 = %s ,'
		. 'url8 = %s, language8 = %s, script8 = %s, locale8 = %s ,'
		. 'url9 = %s, language9 = %s, script9 = %s, locale9 = %s ,'
		. 'url10 = %s, language10 = %s, script10 = %s, locale10 = %s',
		$url_to_connect,
		$url1,
		$language1,
		$script1,
		$locale1,
		$url2,
		$language2,
		$script2,
		$locale2,
		$url3,
		$language3,
		$script3,
		$locale3,
		$url4,
		$language4,
		$script4,
		$locale4,
		$url5,
		$language5,
		$script5,
		$locale5,
		$url6,
		$language6,
		$script6,
		$locale6,
		$url7,
		$language7,
		$script7,
		$locale7,
		$url8,
		$language8,
		$script8,
		$locale8,
		$url9,
		$language9,
		$script9,
		$locale9,
		$url10,
		$language10,
		$script10,
		$locale10
	);

	$query_result = $wpdb->query( $safe_sql );

	if ( isset( $query_result ) && false !== $query_result ) {
		$dismissible_notice_a[] = array(
			'message' => __( 'The connection has been successfully added.', 'hreflang-manager-lite' ),
			'class'   => 'updated',
		);
	}

	$auto_alternate_pages = intval( get_option( 'daexthrmal_auto_alternate_pages' ), 10 );
	$query_result         = false;
	if ( 1 === $auto_alternate_pages ) {

		for ( $i = 1; $i <= 10; $i++ ) {

			if ( strlen( trim( ${'url' . $i} ) ) > 0 && ${'url' . $i} !== $url_to_connect ) {

				$safe_sql = $wpdb->prepare(
					"INSERT INTO $table_name SET url_to_connect = %s ,"
					. 'url1 = %s, language1 = %s, script1 = %s, locale1 = %s,'
					. 'url2 = %s, language2 = %s, script2 = %s, locale2 = %s ,'
					. 'url3 = %s, language3 = %s, script3 = %s, locale3 = %s ,'
					. 'url4 = %s, language4 = %s, script4 = %s, locale4 = %s ,'
					. 'url5 = %s, language5 = %s, script5 = %s, locale5 = %s ,'
					. 'url6 = %s, language6 = %s, script6 = %s, locale6 = %s ,'
					. 'url7 = %s, language7 = %s, script7 = %s, locale7 = %s ,'
					. 'url8 = %s, language8 = %s, script8 = %s, locale8 = %s ,'
					. 'url9 = %s, language9 = %s, script9 = %s, locale9 = %s ,'
					. 'url10 = %s, language10 = %s, script10 = %s, locale10 = %s',
					${'url' . $i},
					$url1,
					$language1,
					$script1,
					$locale1,
					$url2,
					$language2,
					$script2,
					$locale2,
					$url3,
					$language3,
					$script3,
					$locale3,
					$url4,
					$language4,
					$script4,
					$locale4,
					$url5,
					$language5,
					$script5,
					$locale5,
					$url6,
					$language6,
					$script6,
					$locale6,
					$url7,
					$language7,
					$script7,
					$locale7,
					$url8,
					$language8,
					$script8,
					$locale8,
					$url9,
					$language9,
					$script9,
					$locale9,
					$url10,
					$language10,
					$script10,
					$locale10
				);

				$query_result = $wpdb->query( $safe_sql );

			}
		}

		if ( isset( $query_result ) && false !== $query_result ) {
			$dismissible_notice_a[] = array(
				'message' => __( 'The connections of the alternate pages have been successfully added.', 'hreflang-manager-lite' ),
				'class'   => 'updated',
			);
		}
	}
}

// delete a Connection.
if ( ! is_null( $data['delete_id'] ) ) {

	// Nonce verification.
	check_admin_referer( 'daexthrmal_delete_connection_' . $data['delete_id'], 'daexthrmal_delete_connection_nonce' );

	$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
	$safe_sql   = $wpdb->prepare( "DELETE FROM $table_name WHERE connection_id = %d ", $data['delete_id'] );

	$query_result = $wpdb->query( $safe_sql );
	if ( false !== $query_result ) {
		$dismissible_notice_a[] = array(
			'message' => __( 'The connection has been successfully deleted.', 'hreflang-manager-lite' ),
			'class'   => 'updated',
		);
	}
}

// clone action and elements in the field.
if ( ! is_null( $data['clone_id'] ) ) {

	// Nonce verification.
	check_admin_referer( 'daexthrmal_clone_connection_' . $data['clone_id'], 'daexthrmal_clone_connection_nonce' );

	// clone action.
	$table_name     = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
	$query_result_1 = $wpdb->query( "CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM $table_name WHERE connection_id = " . $data['clone_id'] );
	$query_result_2 = $wpdb->query( 'UPDATE tmptable_1 SET connection_id = NULL' );
	$query_result_3 = $wpdb->query( "INSERT INTO $table_name SELECT * FROM tmptable_1" );
	$query_result_4 = $wpdb->query( 'DROP TEMPORARY TABLE IF EXISTS tmptable_1' );

	if ( intval( $query_result_1, 10 ) === 1 &&
		intval( $query_result_2, 10 ) === 1 &&
		intval( $query_result_3, 10 ) === 1 &&
		intval( $query_result_4, 10 ) === 1 ) {

		$dismissible_notice_a[] = array(
			'message' => __( 'The connection has been successfully cloned.', 'hreflang-manager-lite' ),
			'class'   => 'updated',
		);

	}
}

// edit a Connection.
if ( null !== $data['edit_id'] ) {

	$edit_id        = intval( $data['edit_id'], 10 );
	$table_name     = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
	$safe_sql       = $wpdb->prepare( "SELECT * FROM $table_name WHERE connection_id = %d ", $edit_id );
	$connection_obj = $wpdb->get_row( $safe_sql );

}

?>

<!-- output ******************************************************************************* -->

<div class="wrap">

	<?php if ( $this->shared->number_of_connections() > 0 ) : ?>

		<div id="daext-header-wrapper" class="daext-clearfix">

			<h2><?php esc_html_e( 'Hreflang Manager - Connections', 'hreflang-manager-lite' ); ?></h2>

			<form action="admin.php" method="get">
				<input type="hidden" name="page" value="daexthrmal_connections">
				<?php
				if ( ! is_null( $data['s'] ) ) {
					if ( mb_strlen( trim( $data['s'] ) ) > 0 ) {
						$search_string = $data['s'];
					} else {
						$search_string = '';
					}
				} else {
					$search_string = '';
				}
				?>
				<input type="text" name="s" placeholder="<?php esc_attr_e( 'Search...', 'hreflang-manager-lite' ); ?>"
						value="<?php echo esc_attr( stripslashes( $search_string ) ); ?>" autocomplete="off"
						maxlength="255">
				<input type="submit" value="">
			</form>

		</div>

	<?php else : ?>

		<div id="daext-header-wrapper" class="daext-clearfix">

			<h2><?php esc_html_e( 'Hreflang Manager - Connections', 'hreflang-manager-lite' ); ?></h2>

		</div>

	<?php endif; ?>

	<div id="daext-menu-wrapper">

		<?php $this->dismissible_notice( $dismissible_notice_a ); ?>

		<!-- table -->

		<?php

		// create the query part used to filter the results when a search is performed.
		if ( ! is_null( $data['s'] ) &&
			mb_strlen( trim( $data['s'] ) ) > 0
		) {
			$filter = $wpdb->prepare( 'WHERE (connection_id LIKE %s OR url_to_connect LIKE %s)', '%' . $search_string . '%', '%' . $search_string . '%' );
		} else {
			$filter = '';
		}

		// retrieve the total number of connections.
		$table_name  = $wpdb->prefix . $this->shared->get( 'slug' ) . '_connection';
		$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name $filter" );

		// Initialize the pagination class.
		require_once $this->shared->get( 'dir' ) . '/admin/inc/class-daexthrmal-pagination.php';
		$pag = new Daexthrmal_pagination();
		$pag->set_total_items( $total_items );// Set the total number of items.
		$pag->set_record_per_page( 10 ); // Set records per page.
		$pag->set_target_page( 'admin.php?page=daexthrmal_connections' );// Set target page.
		$pag->set_current_page();// set the current page number.

		?>

		<!-- Query the database -->
		<?php
		$dc_wp_query_limit = $pag->query_limit();
		$results           = $wpdb->get_results( "SELECT * FROM $table_name $filter ORDER BY connection_id DESC $dc_wp_query_limit ", ARRAY_A );

		?>

		<?php if ( count( $results ) > 0 ) : ?>

			<div class="daext-items-container">

				<!-- list of featured news -->
				<table class="daext-items">
					<thead>
					<tr>
						<th><?php esc_html_e( 'Connection ID', 'hreflang-manager-lite' ); ?></th>
						<th><?php esc_html_e( 'URL to Connect', 'hreflang-manager-lite' ); ?></th>
						<th><?php esc_html_e( 'Connections', 'hreflang-manager-lite' ); ?></th>
						<th></th>
					</tr>
					</thead>
					<tbody>

					<?php foreach ( $results as $result ) : ?>
						<tr>
							<td><?php echo esc_html( $result['connection_id'] ); ?></td>
							<td><a target="_blank"
									href="<?php echo esc_attr( stripslashes( $result['url_to_connect'] ) ); ?>"><?php echo esc_html( stripslashes( $result['url_to_connect'] ) ); ?></a>
							</td>

							<td>

								<?php

								for ( $i = 1; $i <= 10; $i++ ) {

									if ( strlen( $result[ 'url' . $i ] ) > 0 ) {
										echo '<a target="_blank" href="' . esc_attr( stripslashes( $result[ 'url' . $i ] ) ) . '">' . esc_html( stripslashes( $result[ 'language' . $i ] ) );
										if ( strlen( $result[ 'script' . $i ] ) > 0 ) {
											echo '-' . esc_html( stripslashes( $result[ 'script' . $i ] ) );
										}
										if ( strlen( $result[ 'locale' . $i ] ) > 0 ) {
											echo '-' . esc_html( stripslashes( $result[ 'locale' . $i ] ) );
										}
										echo '</a> ';
									}
								}

								?>


							</td>

							<td class="icons-container">
								<form method="POST"
										action="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>_connections">
									<?php wp_nonce_field( 'daexthrmal_clone_connection_' . intval( $result['connection_id'], 10 ), 'daexthrmal_clone_connection_nonce' ); ?>
									<input type="hidden" value="<?php echo esc_attr( $result['connection_id'] ); ?>"
											name="clone_id">
									<input class="menu-icon clone" type="submit" value="">
								</form>
								<a class="menu-icon edit"
									href="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>_connections&edit_id=<?php echo esc_attr( $result['connection_id'] ); ?>"></a>
								<form id="form-delete-<?php echo esc_attr( $result['connection_id'] ); ?>" method="POST"
										action="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>_connections">
									<?php wp_nonce_field( 'daexthrmal_delete_connection_' . intval( $result['connection_id'], 10 ), 'daexthrmal_delete_connection_nonce' ); ?>
									<input type="hidden" value="<?php echo esc_attr( $result['connection_id'] ); ?>"
											name="delete_id">
									<input class="menu-icon delete" type="submit" value="">
								</form>
							</td>
						</tr>
					<?php endforeach; ?>

					</tbody>
				</table>

			</div>

			<!-- Display the pagination -->
			<?php if ( $pag->total_items > 0 ) : ?>
				<div class="daext-tablenav daext-clearfix">
					<div class="daext-tablenav-pages">
						<span class="daext-displaying-num"><?php echo esc_html( $pag->total_items ); ?>&nbsp<?php esc_html_e( 'items', 'hreflang-manager-lite' ); ?></span>
						<?php $pag->show(); ?>
					</div>
				</div>
			<?php endif; ?>

		<?php endif; ?>

		<!-- form -->

		<form method="POST" action="admin.php?page=<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>_connections">

			<input name="form_submitted" type="hidden" value="true">
			<?php wp_nonce_field( 'daexthrmal_create_update_connection', 'daexthrmal_create_update_connection_nonce' ); ?>

			<?php if ( isset( $connection_obj ) ) : ?>

				<!-- Generate the form to edit an existing connection -->

				<div class="daext-form-container">

					<h3 class="daext-form-title"><?php esc_html_e( 'Edit Connection', 'hreflang-manager-lite' ); ?>&nbsp;<?php echo esc_html( $connection_obj->connection_id ); ?></h3>

					<table class="daext-form">

						<input name="update_id" type="hidden"
								value="<?php echo esc_attr( $connection_obj->connection_id ); ?>">

						<!-- URL to connect -->
						<tr valign="top">
							<th scope="row"><label for="url_to_connect"><label
											for="url_to_connect"><?php esc_html_e( 'URL to Connect', 'hreflang-manager-lite' ); ?></label>
							</th>
							<td>
								<input autocomplete="off" type="text" id="url_to_connect" maxlength="2083"
										name="url_to_connect" class="regular-text"
										value="<?php echo esc_attr( stripslashes( $connection_obj->url_to_connect ) ); ?>"/>
								<div class="help-icon"
									title='<?php esc_attr_e( 'The URL where the hreflang tag should be applied.', 'hreflang-manager-lite' ); ?>'></div>
							</td>
						</tr>

						<?php

						for ( $i = 1; $i <= 10; $i++ ) {

							?>

							<tr valign="top">
								<th scope="row"><label
											for="url<?php echo esc_attr( $i ); ?>">URL <?php echo esc_attr( $i ); ?></label>
								</th>
								<td>
									<input autocomplete="off" type="text" id="url<?php echo esc_attr( $i ); ?>"
											maxlength="2083"
											name="url<?php echo esc_attr( $i ); ?>" class="regular-text"
											value="<?php echo esc_attr( stripslashes( $connection_obj->{'url' . $i} ) ); ?>"/>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The URL of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><label
											for="language<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Language', 'hreflang-manager-lite' ); ?>
										&nbsp<?php echo esc_html( $i ); ?></label>
								</th>
								<td>
									<select id="language<?php echo esc_attr( $i ); ?>" class="daexthrmal-language"
											name="language<?php echo esc_attr( $i ); ?>">
										<?php

										$array_language = get_option( 'daexthrmal_language' );
										foreach ( $array_language as $key => $value ) {
											echo '<option value="' . esc_attr( $value ) . '" ' . selected( $connection_obj->{'language' . $i}, $value, false ) . '>' . esc_html( $value ) . ' - ' . esc_html( $key ) . '</option>';
										}

										?>
									</select>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The language of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><label
											for="script<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Script', 'hreflang-manager-lite' ); ?>
										&nbsp<?php echo esc_attr( $i ); ?></label>
								</th>
								<td>
									<select id="script<?php echo esc_attr( $i ); ?>" class="daexthrmal-script"
											name="script<?php echo esc_attr( $i ); ?>">
										<option value=""><?php esc_html_e( 'Not Assigned', 'hreflang-manager-lite' ); ?></option>
										<?php

										$array_script = get_option( 'daexthrmal_script' );
										foreach ( $array_script as $key => $value ) {
											echo '<option value="' . esc_attr( $value ) . '" ' . selected( $connection_obj->{'script' . $i}, $value, false ) . '>' . esc_html( $value ) . ' - ' . esc_html( $key ) . '</option>';
										}

										?>
									</select>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The script of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><label
											for="locale<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Locale', 'hreflang-manager-lite' ); ?>
										&nbsp<?php echo esc_html( $i ); ?></label>
								</th>
								<td>
									<select id="locale<?php echo esc_attr( $i ); ?>" class="daexthrmal-locale"
											name="locale<?php echo esc_attr( $i ); ?>">
										<option value=""><?php esc_html_e( 'Not Assigned', 'hreflang-manager-lite' ); ?></option>
										<?php

										$array_language = get_option( 'daexthrmal_locale' );
										foreach ( $array_language as $key => $value ) {
											echo '<option value="' . esc_attr( $value ) . '" ' . selected( $connection_obj->{'locale' . $i}, $value, false ) . '>' . esc_html( $value ) . ' - ' . esc_html( $key ) . '</option>';
										}

										?>
									</select>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The locale of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<?php

						}

						?>

					</table>

					<!-- submit button -->
					<div class="daext-form-action">
						<input class="button" type="submit"
								value="<?php esc_attr_e( 'Update Connection', 'hreflang-manager-lite' ); ?>">
					</div>

				</div>

			<?php else : ?>

				<!-- Generate the form to add new connection -->

				<div class="daext-form-container">

					<div class="daext-form-title"><?php esc_html_e( 'Create New Connection', 'hreflang-manager-lite' ); ?></div>

					<table class="daext-form">

						<!-- URL to connect -->
						<tr valign="top">
							<th scope="row"><label
										for="url_to_connect"><?php esc_html_e( 'URL to Connect', 'hreflang-manager-lite' ); ?></label>
							</th>
							<td>
								<input autocomplete="off" type="text" id="url_to_connect" maxlength="2083"
										name="url_to_connect" class="regular-text"/>
								<div class="help-icon"
									title='<?php esc_attr_e( 'The URL where the hreflang tag should be applied.', 'hreflang-manager-lite' ); ?>'></div>
							</td>
						</tr>

						<?php

						for ( $i = 1; $i <= 10; $i++ ) {

							?>

							<!-- url -->
							<tr valign="top">
								<th scope="row"><label
											for="url<?php echo esc_attr( $i ); ?>">URL <?php echo esc_html( $i ); ?></label>
								</th>
								<td>
									<input autocomplete="off" type="text" id="url<?php echo esc_attr( $i ); ?>"
											maxlength="2083"
											name="url<?php echo esc_attr( $i ); ?>" class="regular-text"/>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The URL of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<!-- Language -->
							<tr valign="top">
								<th scope="row"><label
											for="language<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Language', 'hreflang-manager-lite' ); ?>
										&nbsp<?php echo esc_html( $i ); ?></label>
								</th>
								<td>
									<select id="language<?php echo esc_attr( $i ); ?>" class="daexthrmal-language"
											name="language<?php echo esc_attr( $i ); ?>">
										<?php

										$array_language = get_option( 'daexthrmal_language' );
										foreach ( $array_language as $key => $value ) {
											echo '<option value="' . esc_attr( $value ) . '" ' . selected( get_option( 'daexthrmal_default_language_' . $i ), $value, false ) . '>' . esc_html( $value ) . ' - ' . esc_html( $key ) . '</option>';
										}

										?>
									</select>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The language of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<!-- Script -->
							<tr valign="top">
								<th scope="row"><label
											for="script<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Script', 'hreflang-manager-lite' ); ?>
										&nbsp<?php echo esc_html( $i ); ?></label>
								</th>
								<td>
									<select id="script<?php echo esc_attr( $i ); ?>" class="daexthrmal-script"
											name="script<?php echo esc_attr( $i ); ?>">
										<option value=""><?php esc_html_e( 'Not Assigned', 'hreflang-manager-lite' ); ?></option>
										<?php

										$array_script = get_option( 'daexthrmal_script' );
										foreach ( $array_script as $key => $value ) {
											echo '<option value="' . esc_attr( $value ) . '" ' . selected( get_option( 'daexthrmal_default_script_' . $i ), $value, false ) . '>' . esc_html( $value ) . ' - ' . esc_html( $key ) . '</option>';
										}

										?>
									</select>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The script of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<!-- Locale -->
							<tr valign="top">
								<th scope="row"><label
											for="locale<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Locale', 'hreflang-manager-lite' ); ?>
										&nbsp<?php echo esc_html( $i ); ?></label>
								</th>
								<td>
									<select id="locale<?php echo esc_attr( $i ); ?>" class="daexthrmal-locale"
											name="locale<?php echo esc_attr( $i ); ?>">
										<option value=""><?php esc_html_e( 'Not Assigned', 'hreflang-manager-lite' ); ?></option>
										<?php

										$array_language = get_option( 'daexthrmal_locale' );
										foreach ( $array_language as $key => $value ) {
											echo '<option value="' . esc_attr( $value ) . '" ' . selected( get_option( 'daexthrmal_default_locale_' . $i ), $value, false ) . '>' . esc_html( $value ) . ' - ' . esc_html( $key ) . '</option>';
										}

										?>
									</select>
									<div class="help-icon"
										title='<?php esc_attr_e( 'The locale of the variant of the page.', 'hreflang-manager-lite' ); ?>'></div>
								</td>
							</tr>

							<?php

						}

						?>

					</table>

					<!-- submit button -->
					<div class="daext-form-action">
						<input class="button" type="submit"
								value="<?php esc_html_e( 'Add Connection', 'hreflang-manager-lite' ); ?>">
					</div>

				</div>

			<?php endif; ?>

		</form>

	</div>

</div>

<!-- Dialog Confirm -->
<div id="dialog-confirm" title="<?php esc_attr_e( 'Delete the connection?', 'hreflang-manager-lite' ); ?>"
	class="daext-display-none">
	<p><?php esc_html_e( 'This connection will be permanently deleted and cannot be recovered. Are you sure?', 'hreflang-manager-lite' ); ?></p>
</div>