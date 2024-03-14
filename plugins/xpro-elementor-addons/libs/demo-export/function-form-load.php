<?php
/**
 * Create the date options fields for exporting a given post type.
 *
 * @param string $post_type The post type. Default 'post'.
 */

global $wpdb, $wp_locale;

if ( ! function_exists( 'xpro_elementor_demo_export_data_options' ) ) {
	function xpro_elementor_demo_export_data_options( $post_type = 'post' ) {
		global $wpdb, $wp_locale;

		$months = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
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

function xpro_elementor_demo_export_form() {
	global $wpdb, $wp_locale;
	?>
	<div class="xpro-elementor-sites-wrapper">

		<div class="xe-header">
			<h2 class="xe-header-title"><?php esc_html_e( 'Export Zip', 'xpro-elementor-addons' ); ?></h2>
			<p class="xe-header-description"><?php esc_html_e( 'When you click the button below Plugin will create a Zip file and save it to your computer.', 'xpro-elementor-addons' ); ?></p>
		</div>

		<form method="post" id="xpro-elementor-demo-export-filters" action="">
			<h2><?php esc_html_e( 'Choose what to export', 'xpro-elementor-addons' ); ?></h2>
			<?php
			wp_nonce_field( 'xpro-elementor-demo-export' );
			?>
			<legend class="screen-reader-text"><?php esc_html_e( 'Content to export', 'xpro-elementor-addons' ); ?></legend>
			<fieldset class="single-item">
				<input type="hidden" name="xpro-elementor-demo-export-download" value="true"/>
				<p><label><input type="radio" name="content" value="all" checked="checked" aria-describedby="all-content-desc"/><?php esc_html_e( 'All content', 'xpro-elementor-addons' ); ?>
					</label></p>
				<p class="description" id="all-content-desc">
					<?php esc_html_e( 'This will contain all of your posts, pages, comments, custom fields, terms, navigation menus, and custom posts.', 'xpro-elementor-addons' ); ?>
				</p>

				<p><label><input type="radio" name="content" value="posts"/> <?php esc_html_e( 'Posts', 'xpro-elementor-addons' ); ?></label></p>
				<ul id="post-filters" class="xpro-elementor-demo-export-filters">
					<li>
						<label><span class="label-responsive"><?php _e( 'Categories:' ); ?></span>
							<?php wp_dropdown_categories( array( 'show_option_all' => esc_html__( 'All', 'xpro-elementor-addons' ) ) ); ?>
						</label>
					</li>
					<li>
						<label><span class="label-responsive"><?php esc_html_e( 'Authors:', 'xpro-elementor-addons' ); ?></span>
							<?php
							$authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = 'post'" );
							wp_dropdown_users(
								array(
									'include'         => $authors,
									'name'            => 'post_author',
									'multi'           => true,
									'show_option_all' => esc_html__( 'All', 'xpro-elementor-addons' ),
									'show'            => 'display_name_with_login',
								)
							);
							?>
						</label>
					</li>
					<li>
						<fieldset>
							<legend class="screen-reader-text"><?php esc_html_e( 'Date range:', 'xpro-elementor-addons' ); ?></legend>
							<label for="post-start-date" class="label-responsive"><?php esc_html_e( 'Start date:', 'xpro-elementor-addons' ); ?></label>
							<select name="post_start_date" id="post-start-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'xpro-elementor-addons' ); ?></option>
								<?php xpro_elementor_demo_export_data_options(); ?>
							</select>
							<label for="post-end-date" class="label-responsive"><?php esc_html_e( 'End date:', 'xpro-elementor-addons' ); ?></label>
							<select name="post_end_date" id="post-end-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'xpro-elementor-addons' ); ?></option>
								<?php xpro_elementor_demo_export_data_options(); ?>
							</select>
						</fieldset>
					</li>
					<li>
						<label for="post-status" class="label-responsive"><?php esc_html_e( 'Status:', 'xpro-elementor-addons' ); ?></label>
						<select name="post_status" id="post-status">
							<option value="0"><?php esc_html_e( 'All', 'xpro-elementor-addons' ); ?></option>
							<?php
							$post_stati = get_post_stati( array( 'internal' => false ), 'objects' );
							foreach ( $post_stati as $status ) :
								?>
								<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				</ul>

				<p><label><input type="radio" name="content" value="pages"/> <?php esc_html_e( 'Pages', 'xpro-elementor-addons' ); ?></label></p>
				<ul id="page-filters" class="xpro-elementor-demo-export-filters">
					<li>
						<label><span
									class="label-responsive"><?php esc_html_e( 'Authors:', 'xpro-elementor-addons' ); ?></span>
							<?php
							$authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type = 'page'" );
							wp_dropdown_users(
								array(
									'include'         => $authors,
									'name'            => 'page_author',
									'multi'           => true,
									'show_option_all' => esc_html__( 'All', 'xpro-elementor-addons' ),
									'show'            => 'display_name_with_login',
								)
							);
							?>
						</label>
					</li>
					<li>
						<fieldset>
							<legend class="screen-reader-text"><?php esc_html_e( 'Date range:', 'xpro-elementor-addons' ); ?></legend>
							<label for="page-start-date" class="label-responsive"><?php esc_html_e( 'Start date:', 'xpro-elementor-addons' ); ?></label>
							<select name="page_start_date" id="page-start-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'xpro-elementor-addons' ); ?></option>
								<?php xpro_elementor_demo_export_data_options( 'page' ); ?>
							</select>
							<label for="page-end-date" class="label-responsive"><?php esc_html_e( 'End date:', 'xpro-elementor-addons' ); ?></label>
							<select name="page_end_date" id="page-end-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'xpro-elementor-addons' ); ?></option>
								<?php xpro_elementor_demo_export_data_options( 'page' ); ?>
							</select>
						</fieldset>
					</li>
					<li>
						<label for="page-status" class="label-responsive"><?php esc_html_e( 'Status:', 'xpro-elementor-addons' ); ?></label>
						<select name="page_status" id="page-status">
							<option value="0"><?php esc_html_e( 'All', 'xpro-elementor-addons' ); ?></option>
							<?php foreach ( $post_stati as $status ) : ?>
								<option value="<?php echo esc_attr( $status->name ); ?>"><?php echo esc_html( $status->label ); ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				</ul>

				<?php
				foreach (
					get_post_types(
						array(
							'_builtin'   => false,
							'can_export' => true,
						),
						'objects'
					) as $post_type
				) :
					?>
					<p><label><input type="radio" name="content" value="<?php echo esc_attr( $post_type->name ); ?>"/> <?php echo esc_html( $post_type->label ); ?>
						</label></p>
				<?php endforeach; ?>

				<p><label><input type="radio" name="content" value="attachment"/> <?php esc_html_e( 'Media', 'xpro-elementor-addons' ); ?></label>
				</p>
				<ul id="attachment-filters" class="xpro-elementor-demo-export-filters">
					<li>
						<fieldset>
							<legend class="screen-reader-text"><?php esc_html_e( 'Date range:', 'xpro-elementor-addons' ); ?></legend>
							<label for="attachment-start-date" class="label-responsive"><?php esc_html_e( 'Start date:', 'xpro-elementor-addons' ); ?></label>
							<select name="attachment_start_date" id="attachment-start-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'xpro-elementor-addons' ); ?></option>
								<?php xpro_elementor_demo_export_data_options( 'attachment' ); ?>
							</select>
							<label for="attachment-end-date" class="label-responsive"><?php esc_html_e( 'End date:', 'xpro-elementor-addons' ); ?></label>
							<select name="attachment_end_date" id="attachment-end-date">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'xpro-elementor-addons' ); ?></option>
								<?php xpro_elementor_demo_export_data_options( 'attachment' ); ?>
							</select>
						</fieldset>
					</li>
				</ul>

			</fieldset>
			<fieldset class="single-item">
				<input type="checkbox" name="widgets_data" id="widgets_data" value="1" checked>
				<label for="widgets_data" class="label-responsive"><?php esc_html_e( 'Widget Data', 'xpro-elementor-addons' ); ?></label>
			</fieldset>
			<fieldset class="single-item">
				<input type="checkbox" id="options_data" name="options_data" value="1" checked>
				<label for="options_data" class="label-responsive"><?php esc_html_e( 'Customizer Data', 'xpro-elementor-addons' ); ?></label>
			</fieldset>
			<fieldset class="single-item">
				<input type="checkbox" id="settings_data" name="settings_data" value="1" checked>
				<label for="settings_data" class="label-responsive"><?php esc_html_e( 'Site Settings', 'xpro-elementor-addons' ); ?></label>
			</fieldset>
			<fieldset class="single-item">
				<input type="checkbox" name="include_media" id="include_media" value="1">
				<label for="include_media" class="label-responsive"><?php esc_html_e( 'Include Media', 'xpro-elementor-addons' ); ?></label>
			</fieldset>
			<?php
			do_action( 'xpro_elementor_demo_export_form' );
			submit_button( esc_html__( 'Download Export File', 'xpro-elementor-addons' ) );
			?>
		</form>
	</div>
	<?php
}
