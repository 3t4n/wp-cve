<?php
if ( ! class_exists( 'CF7_DATASOURCE_EDITOR' ) ) {
	class CF7_DATASOURCE_EDITOR {

		private $ds_manager;

		/********************** INSTANCE METHODS AND PROPERTIES **********************/
		public function __construct( $ds_manager ) {
			$this->ds_manager = $ds_manager;

			if ( is_admin() ) {
				load_plugin_textdomain( 'cf7-datasource', false, dirname( __FILE__ ) . '/../languages/' );
			}
			// Edit form
			add_action( 'wpcf7_admin_init', array( $this, 'tags_generator' ), 999 );

			// Informative notice
			add_action( 'admin_notices', array( $this, 'help_notice' ) );
		} // End __construct

		private function admin_resources() {
			if ( function_exists( 'wp_enqueue_code_editor' ) ) {
				wp_enqueue_code_editor( array( 'text/x-sql' ) );
			}
			wp_enqueue_style( 'cf7_datasource_admin_style', plugins_url( '/admin.css', __FILE__ ), array(), CF7_DATASOURCE::$version );
			wp_enqueue_script( 'cf7_datasource_admin_script', plugins_url( '/admin.js', __FILE__ ), array( 'jquery' ), CF7_DATASOURCE::$version );
			wp_localize_script(
				'cf7_datasource_admin_script',
				'cf7_datasource_admin_settings',
				array(
					'mode'         => 'text/x-sql',
					'parserfile'   => plugins_url( '/editor/parsesql.js', __FILE__ ),
					'stylesheet'   => plugins_url( '/editor/sqlcolors.css', __FILE__ ),
					'textWrapping' => false,
				)
			);
		} // End js

		public function help_notice() {
			global $pagenow;
			if( ! function_exists( 'get_current_screen' ) ) { return; }
			$screen = get_current_screen();
			if ( 'admin.php' === $pagenow && $screen && 'wpcf7' === $screen->parent_base ) {
				echo '
					<div class="notice notice-info is-dismissible"><p>' .
						__( 'The <b>recordset</b> and <b>recordset field link</b> controls allow filling the form fields with data store in external data sources (user information, post data, taxonomies, Advanced Custom Fields (ACF), database, CSV file, JSON, Server Side functions). Learn their use with <b><a href="https://cf7-datasource.dwbooster.com/examples" target="_blank">Practical Examples</a></b>.', 'cf7-datasource' ) // phpcs:ignore WordPress.Security.EscapeOutput
					. '</p></div>';
			}
		} // End help_notice

		public function tags_generator() {
			if ( ! class_exists( 'WPCF7_TagGenerator' ) ) {
				return;
			}
			$tag_generator = WPCF7_TagGenerator::get_instance();

			$tag_generator->add(
				'cf7-recordset',
				__( 'recordset', 'cf7-datasource' ),
				array( $this, 'tag_generator_recordset' )
			);

			$tag_generator->add(
				'cf7-link-field',
				__( 'recordset field link', 'cf7-datasource' ),
				array( $this, 'tag_generator_link' )
			);
		} // End tags_generator

		public function tag_generator_recordset( $contact_form, $args = '' ) {
			$this->admin_resources();
			$args = wp_parse_args( $args, array() );
			$type = $args['id'];
			?>
			<div class="control-box cf7-datasource-recordset">
				<fieldset>
					<legend><?php _e( 'Generate a form-tag for a Recordset Field to read data from data-sources, like users data, posts information, taxonomies and databases<b><i>(Advanced Custom Fields (ACF), CSV files, Server Side functions, and JSON files are supported by the Professional version of the plugin)</i></b>', 'cf7-datasource' ); // phpcs:ignore WordPress.Security.EscapeOutput
					?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://wordpress.org/support/plugin/cf7-data-source/" target="_blank" style="font-weight:bold;">
					<?php esc_html_e( 'Do you have a question?', 'cf7-datasource' ); ?>
					</a>
					</legend>
					<?php
					if ( current_user_can( 'manage_options' ) ) :
						?>
					<div style="margin:4px 0;padding:5px 10px; background-color:#fef8ee;border:1px solid #c3c4c7;border-left: 3px solid #f0b849;">
						<?php
						_e( 'Upgrade to the <a href="https://cf7-datasource.dwbooster.com/download" target="_blank"><b>Professional version of the plugin</b></a> to get access to advanced data sources. <a href="https://cf7-datasource.dwbooster.com/download" target="_blank"><b>CLICK HERE</b></a>.', 'cf7-datasource' ); // phpcs:ignore WordPress.Security.EscapeOutput
						?>&nbsp;
						<?php
						_e( 'To try the premium data sources, visit the links: <a href="https://demos.dwbooster.com/cf7-datasource/wp-login.php" target="_blank">WordPress area</a>, and <a href="https://demos.dwbooster.com/cf7-datasource/" target="_blank">public website</a>', 'cf7-datasource' ); // phpcs:ignore WordPress.Security.EscapeOutput
						?>
					</div>
						<?php
					endif;
					?>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><?php echo esc_html( __( 'Id', 'cf7-datasource' ) ); ?></th>
								<td>
									<input aria-label="<?php esc_attr_e( 'Field id', 'cf7-datasource' ); ?>" type="text" name="name" id="<?php print esc_attr( $args['content'] . '-id' ); ?>" />
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo esc_html( __( 'Data source', 'cf7-datasource' ) ); ?></th>
								<td>
									<select name="cf7-datasource" aria-label="<?php esc_attr_e( 'Data source', 'cf7-datasource' ); ?>">
										<?php
											print $this->ds_manager->admin_ds_options(); // phpcs:ignore WordPress.Security.EscapeOutput
										?>
										<optgroup label="<?php esc_attr_e( 'Professional version', 'cf7-datasource' ); ?>">
											<option disabled>* <?php esc_html_e( 'Advanced Custom Fields (ACF)', 'cf7-datasource' ); ?></option>
											<option disabled>* <?php esc_html_e( 'Server Side functions', 'cf7-datasource' ); ?></option>
											<option disabled>* <?php esc_html_e( 'Load CSV file', 'cf7-datasource' ); ?></option>
											<option disabled>* <?php esc_html_e( 'Load JSON file', 'cf7-datasource' ); ?></option>
										</optgroup>
									</select>
								</td>
							</tr>
							<?php
								print $this->ds_manager->admin_ds_settings(); // phpcs:ignore WordPress.Security.EscapeOutput
							?>
							<!-- Common -->
							<tr class="cf7-datasource">
								<th scope="row"><?php esc_html_e( 'Callback', 'cf7-datasource' ); ?></th>
								<td>
									<input aria-label="<?php esc_attr_e( 'Callback', 'cf7-datasource' ); ?>" type="text" name="cf7-callback" /><br />
									<i><?php
										esc_html_e( 'Name of javascript function to call after receiving the data source data. The resulting records are assigned to the Recordset.' );
									?></i>
								</td>
							</tr>
							<tr class="cf7-datasource">
								<th scope="row"><?php esc_html_e( 'Debugging', 'cf7-datasource' ); ?></th>
								<td>
									<label><input aria-label="<?php esc_attr_e( 'Debugging', 'cf7-datasource' ); ?>" type="checkbox" name="cf7-debugging" />
									<i><?php
										esc_html_e( 'If the checkbox is ticked the plugin registers the errors in the error_logs file', 'cf7-datasource' );

										$error_logs_path = ini_get( 'error_log' );
									if (
											! empty( $error_logs_path ) &&
											current_user_can( 'manage_options' )
										) {
										print '<br />' . esc_html( $error_logs_path );
									}
									?></i></label>
								</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>

			<div class="insert-box" style="display:flex;overflow:hidden;">
				<input type="text" name="<?php echo esc_attr( $type ); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

				<div class="submitbox">
					<input type="button" class="button button-primary insert-tag" value="<?php esc_attr_e( 'Insert Tag', 'contact-form-7' ); ?>" />
				</div>

				<br class="clear" />

			</div>
			<script>
				jQuery(function($){
					$('[name="cf7-datasource"]').change();
				});
			</script>
			<?php
		} // End tag_generator_recordset

		public function tag_generator_link( $contact_form, $args = '' ) {
			$this->admin_resources();
			$args = wp_parse_args( $args, array() );
			$type = $args['id'];
			?>
			<div class="control-box cf7-datasource-link">
				<fieldset>
					<legend><?php esc_html_e( 'Generate a form-tag to link a Recordset field with another field in the form to populate it with data read from a data source, like the users data, posts information, taxonomies, databases, CSV files, Advanced Custom Fields (ACF), Server Side functions, and JSON files', 'cf7-datasource' );
					?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://wordpress.org/support/plugin/cf7-data-source/" target="_blank" style="font-weight:bold;">
					<?php esc_html_e( 'Do you have a question?', 'cf7-datasource' ); ?>
					</a>
					</legend>

					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><?php esc_html_e( 'Id of recordset field', 'cf7-datasource' ); ?></th>
								<td>
									<input list="cf7-recordset-id" aria-label="<?php esc_attr_e( 'Id of recordset field', 'cf7-datasource' ); ?>" type="text" name="cf7-recordset-id" placeholder="cf7-recordset-#" />
									<datalist id="cf7-recordset-id">
									</datalist>
									<br><i><?php esc_html_e( 'Enter the id of a recordset field in the form', 'cf7-datasource' ); ?></i>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Field name', 'cf7-datasource' ); ?></th>
								<td>
									<input list="cf7-field-name" aria-label="<?php esc_attr_e( 'Field name', 'cf7-datasource' ); ?>" type="text" name="cf7-field-name" />
									<datalist id="cf7-field-name">
									</datalist>
									<br><i><?php esc_html_e( 'Enter the name of the field to populate', 'cf7-datasource' ); ?></i>
									<br><label><input type="checkbox" aria-label="<?php esc_attr_e( 'For drop-down menus, checkboxes, and radio buttons keep existing options', 'cf7-datasource' ); ?>" name="cf7-field-keep-options"> <?php esc_html_e( 'For drop-down menus, checkboxes, and radio buttons keep existing options', 'cf7-datasource' ); ?></label>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Attribute for value', 'cf7-datasource' ); ?></th>
								<td>
									<input aria-label="<?php esc_attr_e( 'Attribute for value', 'cf7-datasource' ); ?>" type="text" name="cf7-attribute-value" />
									<br><i><?php esc_html_e( 'Ex. post_title for post title, or user_email for the email of the user', 'cf7-datasource' ); ?></i>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Attribute for text', 'cf7-datasource' ); ?></th>
								<td>
									<input aria-label="<?php esc_attr_e( 'Attribute for text', 'cf7-datasource' ); ?>" type="text" name="cf7-attribute-text" />
									<br><i><?php esc_html_e( 'Use it with drop-down menu fields, checkbox and radio buttons. Ex. post_title', 'cf7-datasource' ); ?></i>
								</td>
							</tr>
							<tr>
								<th scope="row">(<?php esc_html_e( 'Other attributes', 'cf7-datasource' ); ?>)</th>
								<td>
									<input aria-label="<?php esc_attr_e( 'Other attributes', 'cf7-datasource' ); ?>" type="text" name="cf7-attribute-extra" />
									<br><i><?php esc_html_e( "attribute_name={record_property}", 'cf7-datasource' ); ?></i>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Condition for filtering', 'cf7-datasource' ); ?></th>
								<td>
									<input aria-label="<?php esc_attr_e( 'Condition for filtering', 'cf7-datasource' ); ?>" type="text" name="cf7-condition" />
									<br><i><?php esc_html_e( 'Condition for filtering the record to use to populate the field from the recordset field. Ex. record[\'user_email\']==\'{field.user-email}\'', 'cf7-datasource' ); ?></i>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Limit', 'cf7-datasource' ); ?></th>
								<td>
									<input aria-label="<?php esc_attr_e( 'Limit', 'cf7-datasource' ); ?>" type="text" name="cf7-limit" />
									<br><i><?php esc_html_e( 'Number of records. According to the linked field, the default value can be one record or any record.', 'cf7-datasource' ); ?></i>
								</td>
							</tr>
							<tr>
								<th scope="row"></th>
								<td>
									<div style="width:50%;float:left;"></div>
									<div style="width:50%;float:right;text-align:right;">
										<a href="https://cf7-datasource.dwbooster.com/documentation#link-control" target="_blank" class="button-secondary"><?php esc_html_e( 'Data surce HELP', 'cf7-datasource' ); ?></a>
									</div>
									<div style="clear:both;"></div>
								</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>

			<div class="insert-box" style="display:flex;overflow:hidden;">
				<input type="text" name="<?php echo esc_attr( $type ); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

				<div class="submitbox">
					<input type="button" class="button button-primary insert-tag" value="<?php esc_attr_e( 'Insert Tag', 'contact-form-7' ); ?>" />
				</div>

				<br class="clear" />

			</div>
			<?php
		} // End tag_generator_link
	} // End class CF7_DATASOURCE_EDITOR
}
