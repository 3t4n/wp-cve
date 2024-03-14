<?php
/**
 * Create the date options fields for exporting a given post type.
 *
 * @param string $post_type The post type. Default 'post'.
 */
global $wpdb, $wp_locale;
if ( ! function_exists( 'advanced_export_date_options' ) ) {
	function advanced_export_date_options( $post_type = 'post' ) {
		global $wpdb, $wp_locale;

		$months = $wpdb->get_results(
			$wpdb->prepare(
				"
		SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
		FROM $wpdb->posts
		WHERE post_type = %s AND post_status != 'auto-draft'
		ORDER BY post_date DESC
	",
				$post_type
			)
		);

		$month_count = count( $months );
		if ( ! $month_count || ( 1 == $month_count && 0 == $months[0]->month ) ) {
			return;
		}

		foreach ( $months as $date ) {
			if ( 0 == $date->year ) {
				continue;
			}

			$month = zeroise( $date->month, 2 );
			echo '<option value="' . esc_attr( $date->year ) . '-' . esc_attr( $month ) . '">' . esc_attr( $wp_locale->get_month( $month ) ) . ' ' . esc_attr( $date->year ) . '</option>';
		}
	}
}

function advanced_export_form() {
	global $wpdb, $wp_locale;
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Export Zip', 'advanced-export' ); ?></h1>

		<p><?php esc_html_e( 'When you click the button below Plugin will create a Zip file to save to your computer.', 'advanced-export' ); ?></p>
		<p><?php esc_html_e( 'Once you&#8217;ve saved the download zip file, you can use the Import function in another WordPress installation to import the content from this site.', 'advanced-export' ); ?></p>

		<h2><?php esc_html_e( 'Choose what to export', 'advanced-export' ); ?></h2>
		<form method="post" id="advanced-export-filters" action="">
			<?php
			wp_nonce_field( 'advanced-export' );
			?>
			<legend class="screen-reader-text"><?php esc_html_e( 'Content to export', 'advanced-export' ); ?></legend>
			<fieldset class="single-item">
				<input type="hidden" name="advanced-export-download" value="true" />
				<p><label><input type="radio" name="content" value="all" checked="checked" aria-describedby="all-content-desc" /><?php esc_html_e( 'All content', 'advanced-export' ); ?></label></p>
				<p class="description" id="all-content-desc">
					<?php esc_html_e( 'This will contain all of your posts, pages, comments, custom fields, terms, navigation menus, and custom posts.', 'advanced-export' ); ?>
				</p>

				<p><label><input type="radio" name="content" value="posts" /> <?php esc_html_e( 'Posts', 'advanced-export' ); ?></label></p>
				<ul id="post-filters" class="advanced-export-filters">
					<li>
						<label><span class="label-responsive"><?php _e( 'Categories:' ); ?></span>
							<?php wp_dropdown_categories( array( 'show_option_all' => esc_html__( 'All', 'advanced-export' ) ) ); ?>
						</label>
					</li>
					<li>
						<label><span class="label-responsive"><?php esc_html_e( 'Authors:', 'advanced-export' ); ?></span>
							<?php
							$authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = 'post'" );
							wp_dropdown_users(
								array(
									'include'         => $authors,
									'name'            => 'post_author',
									'multi'           => true,
									'show_option_all' => esc_html__( 'All', 'advanced-export' ),
									'show'            => 'display_name_with_login',
								)
							);
							?>
						</label>
					</li>
					<li>
						<fieldset>
							<legend class="screen-reader-text"><?php esc_html_e( 'Date range:', 'advanced-export' ); ?></legend>
							<label for="post-start-date" class="label-responsive"><?php esc_html_e( 'Start date:', 'advanced-export' ); ?></label>
							<select name="post_start_date" id="post-start-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'advanced-export' ); ?></option>
								<?php advanced_export_date_options(); ?>
							</select>
							<label for="post-end-date" class="label-responsive"><?php esc_html_e( 'End date:', 'advanced-export' ); ?></label>
							<select name="post_end_date" id="post-end-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'advanced-export' ); ?></option>
								<?php advanced_export_date_options(); ?>
							</select>
						</fieldset>
					</li>
					<li>
						<label for="post-status" class="label-responsive"><?php esc_html_e( 'Status:', 'advanced-export' ); ?></label>
						<select name="post_status" id="post-status">
							<option value="0"><?php esc_html_e( 'All', 'advanced-export' ); ?></option>
							<?php
							$post_stati = get_post_stati( array( 'internal' => false ), 'objects' );
							foreach ( $post_stati as $status ) :
								?>
								<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				</ul>

				<p><label><input type="radio" name="content" value="pages" /> <?php esc_html_e( 'Pages', 'advanced-export' ); ?></label></p>
				<ul id="page-filters" class="advanced-export-filters">
					<li>
						<label><span class="label-responsive"><?php esc_html_e( 'Authors:', 'advanced-export' ); ?></span>
							<?php
							$authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = 'page'" );
							wp_dropdown_users(
								array(
									'include'         => $authors,
									'name'            => 'page_author',
									'multi'           => true,
									'show_option_all' => esc_html__( 'All', 'advanced-export' ),
									'show'            => 'display_name_with_login',
								)
							);
							?>
						</label>
					</li>
					<li>
						<fieldset>
							<legend class="screen-reader-text"><?php esc_html_e( 'Date range:', 'advanced-export' ); ?></legend>
							<label for="page-start-date" class="label-responsive"><?php esc_html_e( 'Start date:', 'advanced-export' ); ?></label>
							<select name="page_start_date" id="page-start-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'advanced-export' ); ?></option>
								<?php advanced_export_date_options( 'page' ); ?>
							</select>
							<label for="page-end-date" class="label-responsive"><?php esc_html_e( 'End date:', 'advanced-export' ); ?></label>
							<select name="page_end_date" id="page-end-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'advanced-export' ); ?></option>
								<?php advanced_export_date_options( 'page' ); ?>
							</select>
						</fieldset>
					</li>
					<li>
						<label for="page-status" class="label-responsive"><?php esc_html_e( 'Status:', 'advanced-export' ); ?></label>
						<select name="page_status" id="page-status">
							<option value="0"><?php esc_html_e( 'All', 'advanced-export' ); ?></option>
							<?php foreach ( $post_stati as $status ) : ?>
								<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				</ul>

				<?php
				foreach ( get_post_types(
					array(
						'_builtin'   => false,
						'can_export' => true,
					),
					'objects'
				) as $post_type ) :
					?>
					<p><label><input type="radio" name="content" value="<?php echo esc_attr( $post_type->name ); ?>" /> <?php echo esc_html( $post_type->label ); ?></label></p>
				<?php endforeach; ?>

				<p><label><input type="radio" name="content" value="attachment" /> <?php esc_html_e( 'Media', 'advanced-export' ); ?></label></p>
				<ul id="attachment-filters" class="advanced-export-filters">
					<li>
						<fieldset>
							<legend class="screen-reader-text"><?php esc_html_e( 'Date range:', 'advanced-export' ); ?></legend>
							<label for="attachment-start-date" class="label-responsive"><?php esc_html_e( 'Start date:', 'advanced-export' ); ?></label>
							<select name="attachment_start_date" id="attachment-start-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'advanced-export' ); ?></option>
								<?php advanced_export_date_options( 'attachment' ); ?>
							</select>
							<label for="attachment-end-date" class="label-responsive"><?php esc_html_e( 'End date:', 'advanced-export' ); ?></label>
							<select name="attachment_end_date" id="attachment-end-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'advanced-export' ); ?></option>
								<?php advanced_export_date_options( 'attachment' ); ?>
							</select>
						</fieldset>
					</li>
				</ul>

			</fieldset>
			<fieldset class="single-item">
				<input type="checkbox" name="widgets_data" id="widgets_data" value="1" checked>
				<label for="widgets_data" class="label-responsive"><?php esc_html_e( 'Widget Data', 'advanced-export' ); ?></label>
			</fieldset>
			<fieldset class="single-item">
				<input type="checkbox" id="options_data" name="options_data" value="1" checked>
				<label for="options_data" class="label-responsive"><?php esc_html_e( 'Customizer/Options Data', 'advanced-export' ); ?></label>
			</fieldset>
			<fieldset class="single-item">
				<input type="checkbox" name="include_media" id="include_media" value="1">
				<label for="include_media" class="label-responsive"><?php esc_html_e( 'Include Media', 'advanced-export' ); ?></label>
			</fieldset>
			<?php
			do_action( 'advanced_export_form' );
			submit_button( esc_html__( 'Download Export File', 'advanced-export' ) );
			?>
		</form>
	</div>
	<?php
}
