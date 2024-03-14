<?php
/**
 * Displays the content on the plugin settings page
 */

if ( ! class_exists( 'Psttcsv_Settings_Tabs' ) ) {
	class Psttcsv_Settings_Tabs extends Bws_Settings_Tabs {
		public $all_post_types, $all_fields, $all_status, $post_types;

		/**
		 * Constructor.
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
		 *
		 * @param string $plugin_basename
		 */
		public function __construct( $plugin_basename ) {
			global $psttcsv_options, $psttcsv_plugin_info;

			$tabs = array(
				'settings'    => array( 'label' => __( 'Settings', 'post-to-csv' ) ),
				'comments'    => array( 'label' => __( 'Comments', 'post-to-csv' ) ),
				'woocommerce' => array(
					'label'  => __( 'WooCommerce', 'post-to-csv' ),
					'is_pro' => 1,
				),
				'misc'        => array( 'label' => __( 'Misc', 'post-to-csv' ) ),
				'custom_code' => array( 'label' => __( 'Custom Code', 'post-to-csv' ) ),
				'license'     => array( 'label' => __( 'License Key', 'post-to-csv' ) ),
			);

			parent::__construct(
				array(
					'plugin_basename' => $plugin_basename,
					'plugins_info'    => $psttcsv_plugin_info,
					'prefix'          => 'psttcsv',
					'default_options' => psttcsv_get_options_default(),
					'options'         => $psttcsv_options,
					'tabs'            => $tabs,
					'wp_slug'         => 'post-to-csv',
					'link_key'        => '8f09a91fce52ce4cf41fa8aec0f434ea',
					'link_pn'         => '113',
					'doc_link'        => 'https://bestwebsoft.com/documentation/post-to-csv/post-to-csv-user-guide/',
				)
			);

			$args = array(
				'public'   => true,
				'_builtin' => false,
			);

			$this->post_types = get_post_types( $args, 'names', 'and' );

			$taxonomy_args         = array();
			$this->post_taxonomies = get_taxonomies( $taxonomy_args, 'objects' );

			$this->post_types = array_merge(
				array(
					'post'       => __( 'Post', 'post-to-csv' ),
					'page'       => __( 'Page', 'post-to-csv' ),
					'attachment' => __( 'Attachment', 'post-to-csv' ),
				),
				$this->post_types
			);

			$this->all_fields = array(
				'post_title'   => __( 'Title', 'post-to-csv' ),
				'guid'         => __( 'Guid', 'post-to-csv' ),
				'post_date'    => __( 'Post date', 'post-to-csv' ),
				'post_author'  => __( 'Author', 'post-to-csv' ),
				'post_content' => __( 'Content', 'post-to-csv' ),
				'taxonomy'     => __( 'Taxonomy', 'post-to-csv' ),
				'term'         => __( 'Term', 'post-to-csv' ),

			);

			$this->all_comment_fields = array(
				'comment_post_ID'      => __( 'Post ID', 'post-to-csv' ),
				'permalink'            => __( 'Post Permalink', 'post-to-csv' ),
				'comment_author'       => __( 'Author', 'post-to-csv' ),
				'comment_author_email' => __( 'Author\'s Email', 'post-to-csv' ),
				'comment_content'      => __( 'Comment Content', 'post-to-csv' ),
				'comment_date'         => __( 'Comment Date', 'post-to-csv' ),
			);

			$this->all_status                     = array(
				'publish' => __( 'Publish', 'post-to-csv' ),
				'draft'   => __( 'Draft', 'post-to-csv' ),
				'inherit' => __( 'Inherit', 'post-to-csv' ),
				'private' => __( 'Private', 'post-to-csv' ),
			);
			$this->options['psttcsv_order']       = $psttcsv_options['psttcsv_order'];
			$this->options['psttcsv_direction']   = $psttcsv_options['psttcsv_direction'];
			$this->options['psttcsv_delete_html'] = $psttcsv_options['psttcsv_delete_html'];

			add_action( get_parent_class( $this ) . '_display_custom_messages', array( $this, 'display_custom_messages' ) );
		}

		public function save_options() {
			$message = $notice = $error = '';

			if ( ! isset( $_POST['psttcsv_field'] )
					|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['psttcsv_field'] ) ), 'psttcsv_action' )
			) {
				print __( 'Sorry, your nonce did not verify.', 'post-to-csv' );
				exit;
			} else {
				$this->options['psttcsv_export_type'] = isset( $_POST['psttcsv_export_type'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['psttcsv_export_type'] ) ), array( 'post_type', 'taxonomy' ), true ) ? sanitize_text_field( wp_unslash( $_POST['psttcsv_export_type'] ) ) : 'post_type';

				if ( ! isset( $_POST['psttcsv_post_type'] ) ) {
					$error = __( 'Please choose at least one Post Type.', 'post-to-csv' ) . '<br />';
				} else {
					$this->options['psttcsv_post_type'] = array_map( 'sanitize_title', array_map( 'wp_unslash', (array) $_POST['psttcsv_post_type'] ) );
				}

				if ( ! isset( $_POST['psttcsv_fields'] ) ) {
					$error .= __( 'Please choose at least one Field.', 'post-to-csv' ) . '<br />';
				} else {
					$this->options['psttcsv_fields'] = array_map( 'sanitize_title', array_map( 'wp_unslash', (array) $_POST['psttcsv_fields'] ) );
					foreach ( $this->post_types as $post_type => $post_value ) {
						$this->options[ 'psttcsv_meta_key_' . $post_type ] = isset( $_POST[ 'psttcsv_meta_key_' . $post_type ] ) ? array_map( 'sanitize_title', array_map( 'wp_unslash', (array) $_POST[ 'psttcsv_meta_key_' . $post_type ] ) ) : array();
					}
				}
				foreach ( $this->post_taxonomies as $taxonomy ) {
					$this->options['psttcsv_taxonomy'][ $taxonomy->name ] = isset( $_POST[ 'psttcsv_term_taxonomy_' . $taxonomy->name ] ) ? array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) $_POST[ 'psttcsv_term_taxonomy_' . $taxonomy->name ] ) ) : array();
				}

				if ( ! isset( $_POST['psttcsv_status'] ) ) {
					$error .= __( 'Please choose at least one Post status.', 'post-to-csv' ) . '<br />';
				} else {
					$this->options['psttcsv_status'] = array_map( 'sanitize_title', array_map( 'wp_unslash', (array) $_POST['psttcsv_status'] ) );
				}

				$this->options['psttcsv_order']              = isset( $_POST['psttcsv_order'] ) && in_array( $_POST['psttcsv_order'], array( 'post_title', 'post_date', 'post_author' ), true ) ? sanitize_text_field( wp_unslash( $_POST['psttcsv_order'] ) ) : 'post_date';
				$this->options['psttcsv_direction']          = isset( $_POST['psttcsv_direction'] ) && in_array( $_POST['psttcsv_direction'], array( 'asc', 'desc' ), true ) ? sanitize_text_field( wp_unslash( $_POST['psttcsv_direction'] ) ) : 'desc';
				$this->options['psttcsv_show_hidden_fields'] = isset( $_POST['psttcsv_show_hidden_fields'] ) ? 1 : 0;
				$this->options['psttcsv_delete_html']        = isset( $_POST['psttcsv_delete_html'] ) ? 1 : 0;

				/*Comments Tab*/

				if ( ! isset( $_POST['psttcsv_comment_fields'] ) ) {
					$error .= __( 'Please choose at least one Comment field.', 'post-to-csv' ) . '<br />';
				} else {
					$this->options['psttcsv_comment_fields'] = array();
					$keys                                    = array( 'comment_post_ID', 'permalink', 'comment_author', 'comment_author_email', 'comment_content', 'comment_date' );
					foreach ( $_POST['psttcsv_comment_fields'] as $value ) {
						$value = sanitize_text_field( wp_unslash( $value ) );
						if ( in_array( $value, $keys, true ) ) {
							$this->options['psttcsv_comment_fields'][] = $value;
						}
					}
				}

				$this->options['psttcsv_order_comment']     = isset( $_POST['psttcsv_order_comment'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['psttcsv_order_comment'] ) ), array( 'comment_ID', 'comment_date', 'comment_author' ), true ) ? sanitize_text_field( wp_unslash( $_POST['psttcsv_order_comment'] ) ) : 'comment_ID';
				$this->options['psttcsv_direction_comment'] = isset( $_POST['psttcsv_direction_comment'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['psttcsv_direction_comment'] ) ), array( 'asc', 'desc' ), true ) ? sanitize_text_field( wp_unslash( $_POST['psttcsv_direction_comment'] ) ) : 'desc';
				if ( empty( $error ) ) {
					update_option( 'psttcsv_options', $this->options );
					$message = __( 'Settings saved.', 'post-to-csv' );
				}
			}
			return compact( 'message', 'notice', 'error' );
		}

		public function tab_settings() { ?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Post to CSV Settings', 'post-to-csv' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table psttcsv-table-settings" id="psttcsv_settings_form">
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Export', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<label><input type="radio" name="psttcsv_export_type" value="post_type" <?php checked( 'post_type' === $this->options['psttcsv_export_type'], true ); ?> /> <?php esc_html_e( 'Post Types', 'post-to-csv' ); ?></label><br />
							<label><input type="radio" name="psttcsv_export_type" value="taxonomy" <?php checked( 'taxonomy' === $this->options['psttcsv_export_type'], true ); ?> /> <?php esc_html_e( 'Taxonomies', 'post-to-csv' ); ?></label><br />
						</fieldset>
					</td>
				</tr>
				<tr valign="top" id="psttcsv-posttype-block">
					<th scope="row"><?php esc_html_e( 'Post Types', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<div class="psttcsv_div_select_all hide-if-no-js"><label><input id="psttcsv_select_all_post_types" class="psttcsv_select_all" type="checkbox" /> <strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong></label></div>
							<?php foreach ( $this->post_types as $post_type => $post_type_name ) { ?>
								<label>
									<input type="checkbox" name="psttcsv_post_type[]" value="<?php echo esc_attr( $post_type ); ?>" class="bws_option_affect" data-affect-show="[data-post-type=<?php echo esc_attr( $post_type ); ?>]" <?php checked( in_array( $post_type, $this->options['psttcsv_post_type'], true ), true ); ?> /> 
									<?php
									$post_obj = get_post_type_object( $post_type_name );
									if ( ! isset( $post_obj ) ) {
										echo esc_html( ucfirst( $post_type_name ) );
									} else {
										echo esc_html( ucfirst( $post_obj->labels->singular_name ) );
									}
									?>
								</label><br />
							<?php } ?>
						</fieldset>
					</td>
				</tr>
				<tr valign="top" id="psttcsv-taxonomies-block">
					<th scope="row"><?php esc_html_e( 'Taxonomies', 'post-to-csv' ); ?></th>
					<td>
						<div id="psttcsv-accordion-taxonomies">
							<?php
							foreach ( $this->post_taxonomies as $taxonomy ) {
								$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
								if ( ! empty( $terms ) ) {
									?>
									<h3 data-post-type="<?php echo esc_attr( $taxonomy->name ); ?>"><?php echo esc_attr( $taxonomy->labels->name ) . ' (' . esc_attr( $taxonomy->name ) . ')'; ?></h3>
									<div class="psttcsv_taxonomies_settings_accordion_item" data-post-type="<?php echo esc_html( $taxonomy->name ); ?>">
										<fieldset>
											<div class="hide-if-no-js" >
												<label>
													<input id="psttcsv_select_all_<?php echo esc_attr( $taxonomy->name ); ?>" class="psttcsv_select_all" type="checkbox" /><strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong>
												</label>
											</div>
											<?php foreach ( $terms as $item ) { ?>
												<label><input type="checkbox" class="psttcsv_checkbox_select" name="psttcsv_term_taxonomy_<?php echo esc_attr( $taxonomy->name ); ?>[]" value="<?php echo esc_attr( $item->name ); ?>" 
												<?php
												if ( isset( $this->options['psttcsv_taxonomy'][ $taxonomy->name ] ) ) {
													checked( in_array( $item->name, $this->options['psttcsv_taxonomy'][ $taxonomy->name ], true ), true );
												}
												?>
												/> <?php echo esc_html( $item->name ); ?></label><br />
											<?php } ?>
										</fieldset>
									</div>
									<?php
								}
							}
							?>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Fields', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<div class="psttcsv_div_select_all hide-if-no-js"><label><input id="psttcsv_select_all_fields" class="psttcsv_select_all" type="checkbox" /> <strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong></label></div>
							<?php foreach ( $this->all_fields as $field_key => $field_name ) { ?>
								<label><input type="checkbox" name="psttcsv_fields[]" value="<?php echo esc_attr( $field_key ); ?>" class="bws_option_affect" <?php checked( in_array( $field_key, $this->options['psttcsv_fields'], true ), true ); ?> /> <?php echo esc_html( $field_name ); ?></label><br />
							<?php } ?>
						</fieldset>
					</td>
				</tr>
				<?php if ( ! empty( $this->options['psttcsv_post_type'] ) ) { ?>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Custom Fields', 'post-to-csv' ); ?></th>
					<td>
						<div class="psttcsv-show-meta-key hide-if-no-js">
							<label><input name="psttcsv_show_hidden_fields" type="checkbox" id="psttcsv-show-hidden-meta" 
							<?php
							if ( isset( $this->options['psttcsv_show_hidden_fields'] ) ) {
								checked( $this->options['psttcsv_show_hidden_fields'] );
							}
							?>
							data-affect-show=".psttcsv-hidden-option"><?php esc_html_e( 'Show hidden fields', 'post-to-csv' ); ?></label>
						</div>
						<div id="psttcsv-accordion">
							<?php
							foreach ( $this->post_types as $post_type => $post_type_name ) {
								$post_type_meta = psttcsv_get_all_meta( $post_type );
								?>
								<h3 data-post-type="<?php echo esc_attr( $post_type ); ?>"><?php echo esc_html( ucfirst( $post_type_name ) ); ?></h3>
								<div class="psttcsv_custom_fields_settings_accordion_item" data-post-type="<?php echo esc_attr( $post_type ); ?>">
									<?php
									if ( empty( $post_type_meta ) ) {
										esc_html_e( 'No service meta keys', 'post-to-csv' ); }
									?>
									<fieldset>
										<div class="hide-if-no-js" >
											<label>
												<input id="psttcsv_select_all_<?php echo esc_attr( $post_type ); ?>" class="psttcsv_select_all" type="checkbox" /><strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong>
											</label>
										</div>
										<?php
										foreach ( $post_type_meta as $item ) {
											$post_meta_field_name = esc_attr( $item['meta_key'] );
											if ( '_' === substr( $post_meta_field_name, 0, 1 ) ) {
												?>
												<div class="psttcsv-hidden-option">
													<label><input type="checkbox" class="psttcsv_checkbox_select" name="psttcsv_meta_key_<?php echo esc_attr( $post_type ); ?>[]" value="<?php echo esc_attr( $post_meta_field_name ); ?>" 
													<?php
													if ( isset( $this->options[ 'psttcsv_meta_key_' . $post_type ] ) ) {
														checked( in_array( $post_meta_field_name, $this->options[ 'psttcsv_meta_key_' . $post_type ], true ), true );
													}
													?>
													/> <?php echo esc_html( $post_meta_field_name ); ?></label><br />
												</div>
											<?php } else { ?>
												<label><input type="checkbox" class="psttcsv_checkbox_select" name="psttcsv_meta_key_<?php echo esc_attr( $post_type ); ?>[]" value="<?php echo esc_attr( $post_meta_field_name ); ?>" 
												<?php
												if ( isset( $this->options[ 'psttcsv_meta_key_' . $post_type ] ) ) {
													checked( in_array( $post_meta_field_name, $this->options[ 'psttcsv_meta_key_' . $post_type ], true ), true );
												}
												?>
												/> <?php echo esc_html( $post_meta_field_name ); ?></label><br />
												<?php
											}
										}
										?>
									</fieldset>
								</div>
							<?php } ?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Post Status', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<div class="psttcsv_div_select_all hide-if-no-js"><label><input id="psttcsv_select_all_status" class="psttcsv_select_all" type="checkbox" /> <strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong></label></div>
							<?php foreach ( $this->all_status as $status_value => $status_name ) { ?>
								<label><input type="checkbox" name="psttcsv_status[]" value="<?php echo esc_attr( $status_value ); ?>" <?php checked( in_array( $status_value, $this->options['psttcsv_status'], true ), true ); ?> /> <?php echo esc_html( $status_name ); ?></label><br />
							<?php } ?>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Sort by', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<label><input type="radio" name="psttcsv_order" value="post_title" <?php checked( 'post_title' === $this->options['psttcsv_order'], true ); ?> /> <?php esc_html_e( 'Title', 'post-to-csv' ); ?></label><br />
							<label><input type="radio" name="psttcsv_order" value="post_date"  <?php checked( 'post_date' === $this->options['psttcsv_order'], true ); ?> /> <?php esc_html_e( 'Date', 'post-to-csv' ); ?></label><br />
							<label><input type="radio" name="psttcsv_order" value="post_author"  <?php checked( 'post_author' === $this->options['psttcsv_order'], true ); ?> /> <?php esc_html_e( 'Author', 'post-to-csv' ); ?></label><br />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Arrange by', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<label><input type="radio" name="psttcsv_direction" value="asc" <?php checked( 'asc' === $this->options['psttcsv_direction'], true ); ?> /> <?php esc_html_e( 'ASC', 'post-to-csv' ); ?></label><br />
							<label><input type="radio" name="psttcsv_direction" value="desc" <?php checked( 'desc' === $this->options['psttcsv_direction'], true ); ?> /> <?php esc_html_e( 'DESC', 'post-to-csv' ); ?></label><br />
						</fieldset>
					</td>
				</tr>
				<tr valgin="top">
					<th scope="row"><?php esc_html_e( 'Remove HTML Tags', 'post-to-csv' ); ?></th>
					<td>
						<input type="checkbox" name="psttcsv_delete_html" value="1" <?php checked( 1 === $this->options['psttcsv_delete_html'], true ); ?> />
						<span class="bws_info">
							<?php esc_html_e( 'Enable to remove HTML tags from the post content.', 'post-to-csv' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Export to CSV', 'post-to-csv' ); ?></th>
					<td>
						<div class="bws_help_warning_wrapper">
							<h4><?php _e( 'Warning. Read before export', 'post-to-csv' ); ?></h4>
							<div class="bws_help_box dashicons dashicons-editor-help">
								<div class="bws_hidden_help_text">
									<p><?php _e( 'This CSV file may contain content that could auto-execute formulas in certain spreadsheet applications. This can be problematic as auto-executing formulas can potentially be used for malicious activity, including malware and other security threats.', 'post-to-csv' ); ?></p>
									<p><?php _e( 'Here are some elements that could be causing these issues:', 'post-to-csv' ); ?></p>
									<ul>
										<li><b><?php _e( 'Formulas', 'post-to-csv' ); ?></b>: <?php _e( 'The most direct issue. CSV files can include formulas that will automatically execute when the file is opened in a spreadsheet application. This can be particularly hazardous if the formula includes functions that can interact with the user\'s system, such as creating new files, executing commands on the user\'s computer, or even sending data to a remote server.', 'post-to-csv' ); ?></li>
										<li><b><?php _e( 'Hyperlinks', 'post-to-csv' ); ?></b>: <?php _e( 'Hyperlinks may also automatically open upon CSV file launch. If a URL leads to a malicious site, this can pose problems.', 'post-to-csv' ); ?></li>
										<li><b><?php _e( 'Embedded or OLE Objects', 'post-to-csv' ); ?></b>: <?php _e( 'Some programs allow the embedding of objects (such as images or other files) within a spreadsheet. If these objects contain malware, they can be activated upon opening the file.', 'post-to-csv' ); ?></li>
										<li><b><?php _e( 'Macros', 'post-to-csv' ); ?></b>: <?php _e( 'While CSV files typically do not support macros, some spreadsheet applications may automatically convert them into a format that supports macros, which can also pose a security risk.', 'post-to-csv' ); ?></li>
									</ul>
									<p><?php _e( 'Always exercise caution when opening files from untrusted sources, especially those that can contain auto-executing scripts or other potentially dangerous content. If possible, disable automatic execution of formulas and other functions in your spreadsheet applications.'); ?></p>
								</div>
							</div>
						</div>
						<input type="submit" name="psttcsv_export_submit" class="button-secondary" value="<?php esc_html_e( 'Export Now', 'post-to-csv' ); ?>" />
						<div class="bws_info psttcsv_export_notice" style="display: none">
							<strong><?php esc_html_e( 'Notice', 'post-to-csv' ); ?></strong>: <?php esc_html_e( "The plugin's settings have been changed.", 'post-to-csv' ); ?>
							<a class="bws_save_anchor" href="#bws-submit-button"><?php esc_html_e( 'Save Changes', 'post-to-csv' ); ?></a>&nbsp;<?php esc_html_e( 'before export', 'post-to-csv' ); ?>.
						</div>
					</td>
				</tr>
			</table>
			<?php
			wp_nonce_field( 'psttcsv_action', 'psttcsv_field' );
		}

		public function tab_comments() {
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Comments Export Settings', 'post-to-csv' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table psttcsv-table-settings" id="psttcsv_comments_form">
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Comment Fields', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<div class="psttcsv_div_select_all hide-if-no-js"><label><input class="psttcsv_select_all" type="checkbox" /> <strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong></label></div>
							<?php foreach ( $this->all_comment_fields as $comment_field_key => $comment_field_name ) { ?>
								<label><input type="checkbox" name="psttcsv_comment_fields[]" value="<?php echo esc_attr( $comment_field_key ); ?>" class="bws_option_affect" <?php checked( in_array( $comment_field_key, $this->options['psttcsv_comment_fields'], true ), true ); ?> /> <?php echo esc_html( $comment_field_name ); ?></label><br />
							<?php } ?>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Sort by', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<label><input type="radio" name="psttcsv_order_comment" value="comment_ID" <?php checked( 'comment_ID' === $this->options['psttcsv_order_comment'], true ); ?> /> <?php esc_html_e( 'ID', 'post-to-csv' ); ?></label><br />
							<label><input type="radio" name="psttcsv_order_comment" value="comment_date" <?php checked( 'comment_date' === $this->options['psttcsv_order_comment'], true ); ?> /> <?php esc_html_e( 'Date', 'post-to-csv' ); ?></label><br />
							<label><input type="radio" name="psttcsv_order_comment" value="comment_author" <?php checked( 'comment_author' === $this->options['psttcsv_order_comment'], true ); ?> /> <?php esc_html_e( 'Author', 'post-to-csv' ); ?></label><br />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Arrange by', 'post-to-csv' ); ?></th>
					<td><fieldset>
							<label><input type="radio" name="psttcsv_direction_comment" value="asc" <?php checked( 'asc' === $this->options['psttcsv_direction_comment'], true ); ?> /> <?php esc_html_e( 'ASC', 'post-to-csv' ); ?></label><br />
							<label><input type="radio" name="psttcsv_direction_comment" value="desc" <?php checked( 'desc' === $this->options['psttcsv_direction_comment'], true ); ?> /> <?php esc_html_e( 'DESC', 'post-to-csv' ); ?></label><br />
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Export to CSV', 'post-to-csv' ); ?></th>
					<td>
						<div class="bws_help_warning_wrapper">
								<h4><?php _e( 'Warning. Read before export', 'post-to-csv' ); ?></h4>
								<div class="bws_help_box dashicons dashicons-editor-help">
									<div class="bws_hidden_help_text">
										<p><?php _e( 'This CSV file may contain content that could auto-execute formulas in certain spreadsheet applications. This can be problematic as auto-executing formulas can potentially be used for malicious activity.', 'post-to-csv' ); ?></p>
										<p><?php _e( 'Here are some elements that could be causing these issues:', 'post-to-csv' ); ?></p>
										<ul>
											<li><b><?php _e( 'Formulas', 'post-to-csv' ); ?></b>: <?php _e( 'The most direct issue. CSV files can include formulas that will automatically execute when the file is opened in a spreadsheet application.', 'post-to-csv' ); ?></li>
											<li><b><?php _e( 'Hyperlinks', 'post-to-csv' ); ?></b>: <?php _e( 'Hyperlinks may also automatically open upon CSV file launch.', 'post-to-csv' ); ?></li>
											<li><b><?php _e( 'Embedded or OLE Objects', 'post-to-csv' ); ?></b>: <?php _e( 'Some programs allow the embedding of objects (such as images or other files) within a spreadsheet. If these objects contain malware, they can be activated upon opening the file.', 'post-to-csv' ); ?></li>
											<li><b><?php _e( 'Macros', 'post-to-csv' ); ?></b>: <?php _e( 'While CSV files typically do not support macros, some spreadsheet applications may automatically convert them into a format that supports macros.', 'post-to-csv' ); ?></li>
										</ul>
										<p><?php _e( 'Always exercise caution when opening files from untrusted sources, especially those that can contain auto-executing scripts or other potentially dangerous content. If possible, disable automatic execution of formulas and other functions in your spreadsheet applications.'); ?></p>
									</div>
								</div>
							</div>
						<input type="submit" name="psttcsv_export_submit_comments" class="button-secondary" value="<?php esc_html_e( 'Export Now', 'post-to-csv' ); ?>" />
						<div class="bws_info psttcsv_export_notice" style="display: none">
							<strong><?php esc_html_e( 'Notice', 'post-to-csv' ); ?></strong>: <?php esc_html_e( "The plugin's settings have been changed.", 'post-to-csv' ); ?>
							<a class="bws_save_anchor" href="#bws-submit-button"><?php esc_html_e( 'Save Changes', 'post-to-csv' ); ?></a>&nbsp;<?php esc_html_e( 'before export', 'post-to-csv' ); ?>.
						</div>
					</td>
				</tr>
			</table>
			<?php
		}

		public function tab_woocommerce() {
			$status = array(
				'private' => __( 'Private', 'post-to-csv' ),
				'publish' => __( 'Publish', 'post-to-csv' ),
				'draft'   => __( 'Draft', 'post-to-csv' ),
				'future'  => __( 'Future', 'post-to-csv' ),
				'pending' => __( 'Pending', 'post-to-csv' ),
			);

			$woo_product_types = array(
				'simple'    => __( 'Simple product', 'post-to-csv' ),
				'grouped'   => __( 'Grouped product', 'post-to-csv' ),
				'external'  => __( 'External/Affiliate product', 'post-to-csv' ),
				'variable'  => __( 'Variable product', 'post-to-csv' ),
				'variation' => __( 'Product variations', 'post-to-csv' ),
			);
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'WooCommerce Export Product Settings', 'post-to-csv' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<div class="bws_pro_version_bloc">
				<div class="bws_pro_version_table_bloc">
					<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php esc_html_e( 'Close', 'subscriber' ); ?>"></button>
					<div class="bws_table_bg"></div>
					<table class="form-table bws_pro_version">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'WooCommerce Product Fields', 'post-to-csv' ); ?></th>
						<td>
							<div id="psttcsv-accordion-woocommerce">
								<h3 data-post-type="woocommerce_product"><?php esc_html_e( 'Product Fields', 'post-to-csv' ); ?></h3>
								<div class="psttcsv_custom_fields_settings_accordion_item" data-post-type="woocommerce_product">
								</div>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Product Status', 'post-to-csv' ); ?></th>
						<td><fieldset>
								<div class="psttcsv_div_select_all hide-if-no-js"><label><input disabled="disabled" id="psttcsv_select_all_status" class="psttcsv_select_all" type="checkbox" /> <strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong></label></div>
								<?php foreach ( $status as $value => $key ) { ?>
									<label>
										<input disabled="disabled" type="checkbox" name="psttcsv_status_woocommerce[]" value="<?php echo esc_attr( $value ); ?>"  /> <?php echo esc_html( $key ); ?>
									</label>
									<br />
								<?php } ?>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Product Types', 'post-to-csv' ); ?></th>
						<td><fieldset>
								<div class="psttcsv_div_select_all hide-if-no-js"><label><input disabled="disabled"  id="psttcsv_select_all_types" class="psttcsv_select_all" type="checkbox" /> <strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong></label></div>
								<?php foreach ( $woo_product_types as $types_id => $types_name ) { ?>
									<label>
										<input disabled="disabled"  type="checkbox" name="psttcsv_product_type_woocommerce[]" value="<?php echo esc_attr( $types_id ); ?>" /> <?php echo esc_html( $types_name ); ?>
									</label><br />
								<?php } ?>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Product Category', 'post-to-csv' ); ?></th>
						<td><fieldset>
								<div class="psttcsv_div_select_all hide-if-no-js"><label><input disabled="disabled"  id="psttcsv_select_all_category" class="psttcsv_select_all" type="checkbox" /> <strong><?php esc_html_e( 'All', 'post-to-csv' ); ?></strong></label></div>
									<label>
										<input disabled="disabled" type="checkbox" name="psttcsv_product_category_woocommerce[]" value="uncategorized" /> <?php echo esc_html_e( 'Uncategorized', 'post-to-csv' ); ?>
									</label><br />
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Sort by', 'post-to-csv' ); ?></th>
						<td><fieldset>
								<label><input disabled="disabled" type="radio" name="psttcsv_order_woocommerce" value="ID"  /> <?php esc_html_e( 'ID', 'post-to-csv' ); ?></label><br />
								<label><input disabled="disabled" type="radio" name="psttcsv_order_woocommerce" value="name" /> <?php esc_html_e( 'Name', 'post-to-csv' ); ?></label><br />
								<label><input disabled="disabled" type="radio" name="psttcsv_order_woocommerce" value="type" /> <?php esc_html_e( 'Type', 'post-to-csv' ); ?></label><br />
								<label><input disabled="disabled" type="radio" name="psttcsv_order_woocommerce" value="date" /> <?php esc_html_e( 'Date', 'post-to-csv' ); ?></label><br />
								<label><input disabled="disabled" type="radio" name="psttcsv_order_woocommerce" value="modified" /> <?php esc_html_e( 'Modified', 'post-to-csv' ); ?></label><br />
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Arrange by', 'post-to-csv' ); ?></th>
						<td><fieldset>
								<label><input disabled="disabled" type="radio" name="psttcsv_direction_woocommerce" value="ASC" /> <?php esc_html_e( 'ASC', 'post-to-csv' ); ?></label><br />
								<label><input disabled="disabled" type="radio" name="psttcsv_direction_woocommerce" value="DESC" /> <?php esc_html_e( 'DESC', 'post-to-csv' ); ?></label><br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Export to CSV', 'post-to-csv' ); ?></th>
						<td>
							<input disabled="disabled" name="bws_restore_default" type="submit" class="button" value="<?php esc_html_e( 'Export Now', 'post-to-csv' ); ?>" />
						</td>
					</tr>
					</table>
				</div>
				<?php $this->bws_pro_block_links(); ?>
			</div>
			<?php
		}

		/**
		 * Displays custom error message on export error
		 *
		 * @access public
		 */
		public function display_custom_messages( $save_results ) {
			if ( ! empty( $_SESSION['psttcsv_error_message'] ) ) {
				if ( 'no_data' === $_SESSION['psttcsv_error_message'] ) {
					?>
					<div class="error inline psttcsv_error"><p><strong><?php esc_html_e( 'No records meet the specified criteria.', 'post-to-csv' ); ?></strong></p></div>
					<?php
				}
				unset( $_SESSION['psttcsv_error_message'] );
			}
		}

	}
}
