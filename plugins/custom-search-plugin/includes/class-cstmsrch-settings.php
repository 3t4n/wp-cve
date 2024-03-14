<?php
/**
 * Displays the content on the plugin settings page
 */


if ( ! class_exists( 'Cstmsrch_Settings_Tabs' ) ) {
	class Cstmsrch_Settings_Tabs extends Bws_Settings_Tabs {
		public $search_objects_custom, $post_types_custom_keys, $post_types_custom, $taxonomies_keys, $taxonomies_global, $cstmsrch_post_types_enabled, $cstmsrch_taxonomies_enabled;
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
			global $cstmsrch_options, $cstmsrch_plugin_info, $cstmsrch_post_types_enabled, $cstmsrch_taxonomies_enabled;

			$tabs = array(
				'settings'		=> array( 'label' => __( 'Settings', 'custom-search-plugin' ) ),
				'display'		=> array( 'label' => __( 'Data', 'custom-search-plugin' ) ),
				'appearance'	=> array( 'label' => __( 'Search Results', 'custom-search-plugin' ) ),
				'misc'			=> array( 'label' => __( 'Misc', 'custom-search-plugin' ) ),
				'custom_code'	=> array( 'label' => __( 'Custom Code', 'custom-search-plugin' ) ),
				'license'		=> array( 'label' => __( 'License Key', 'custom-search-plugin' ) )
			);

			parent::__construct( array(
				'plugin_basename'		=> $plugin_basename,
				'plugins_info'			=> $cstmsrch_plugin_info,
				'prefix'				=> 'cstmsrch',
				'default_options'		=> cstmsrch_default_options(),
				'options'				=> $cstmsrch_options,
				'is_network_options'	=> is_network_admin(),
				'tabs'					=> $tabs,
				'wp_slug'				=> 'custom-search-plugin',
				'link_key'				=> 'f9558d294313c75b964f5f6fa1e5fd3c',
				'link_pn'				=> '81'
			) );

			$this->all_plugins = get_plugins();

			$this->cstmsrch_post_types_enabled = $cstmsrch_post_types_enabled;

			$this->cstmsrch_taxonomies_enabled = $cstmsrch_taxonomies_enabled;

			$this->search_objects_custom['post_type'] = get_post_types( array( 'public' => true ), 'objects' );
			unset( $this->search_objects_custom['post_type']['attachment'] );

			$this->post_types_custom_keys = ( ! empty( $this->search_objects_custom ) ) ? array_combine( array_keys( $this->search_objects_custom['post_type'] ), array_keys( $this->search_objects_custom['post_type'] ) ) : array();
			unset( $this->post_types_custom_keys['post'], $this->post_types_custom_keys['page'] );

			$this->post_types_custom = ( ! empty( $this->post_types_custom_keys ) ) ? array_combine( $this->post_types_custom_keys, $this->post_types_custom_keys ) : array();

			$this->search_objects_custom['taxonomy'] = get_taxonomies( array( 'public' => true ), 'objects' );
			unset( $this->search_objects_custom['taxonomy']['post_format'] );

			$this->taxonomies_keys = array_keys( $this->search_objects_custom['taxonomy'] );

			$this->taxonomies_global = array_combine( $this->taxonomies_keys, $this->taxonomies_keys );

			add_action( get_parent_class( $this ) . '_additional_restore_options', array( $this, 'additional_restore_options' ) );
			add_action( get_parent_class( $this ) . '_display_metabox', array( $this, 'display_metabox' ) );

		}

		/**
		 * Save plugin options to the database
		 * @access public
		 * @param  void
		 * @return array    The action results
		 */
		public function save_options() {
			$message = $notice = $error = '';
			$post_types_global = get_post_types( array( 'public' => true ), 'names' );
			unset( $post_types_global['attachment'] );
			$this->cstmsrch_post_types_enabled = array( 'post', 'page' );
			if ( ! empty( $_REQUEST['cstmsrch_post_types'] ) && is_array( $_REQUEST['cstmsrch_post_types'] ) ) {
				foreach ( $_REQUEST['cstmsrch_post_types'] as $post_type ) {
					if ( in_array( $post_type, $this->post_types_custom ) ) {
						$this->cstmsrch_post_types_enabled[] = $post_type;
					}
				}
			}
			$this->cstmsrch_taxonomies_enabled = array();
			if ( ! empty( $_REQUEST['cstmsrch_taxonomies'] ) && is_array( $_REQUEST['cstmsrch_taxonomies'] ) ) {
				foreach ( $_REQUEST['cstmsrch_taxonomies'] as $taxonomy ) {
					if ( in_array( $taxonomy, $this->taxonomies_global ) ) {
						$this->cstmsrch_taxonomies_enabled[] = $taxonomy;
					}
				}
			}

			$output_order = array();
			foreach ( $post_types_global as $post_type ) {
				$enabled = ( in_array( $post_type, $this->cstmsrch_post_types_enabled ) ) ? 1 : 0;
				$output_order[ 'post_type_' . $post_type ] = array(
					'name'		=> $post_type,
					'type'		=> 'post_type',
					'enabled'	=> $enabled
				);
			}
			foreach ( $this->taxonomies_global as $taxonomy ) {
				$enabled = ( in_array( $taxonomy, $this->cstmsrch_taxonomies_enabled ) ) ? 1 : 0;
				$output_order[ 'taxonomy_' . $taxonomy ] = array(
					'name'		=> $taxonomy,
					'type'		=> 'taxonomy',
					'enabled'	=> $enabled
				);
			}
			$this->options['output_order'] = $output_order;
			$this->options['fields'] = isset( $_REQUEST['cstmsrch_fields_array'] ) ? $_REQUEST['cstmsrch_fields_array'] : array();
			$this->options['fields'] = array_map( 'esc_attr', $this->options['fields'] );
			$this->options['show_hidden_fields']  = isset( $_REQUEST['cstmsrch_show_hidden_fields'] ) ? 1 : 0;
			$this->options['show_tabs_post_type'] = isset( $_REQUEST['cstmsrch_show_tabs_post_type'] ) ? absint( $_REQUEST['cstmsrch_show_tabs_post_type'] ) : 0;

			update_option( 'cstmsrch_options', $this->options );

			$message = __( 'Settings saved' , 'custom-search-plugin' );

			return compact( 'message', 'notice', 'error' );

		}

		public function tab_settings() {
			global $wpdb;
			$install_plugins = get_plugins();
			$post_types_select_all = $taxonomies_select_all = '';
			if ( count( $this->post_types_custom ) == count( $this->cstmsrch_post_types_enabled ) - 2 ) {
				$post_types_select_all = 'checked="checked"';
			}
			if ( count( $this->taxonomies_global ) == count( $this->cstmsrch_taxonomies_enabled ) ) {
				$taxonomies_select_all = 'checked="checked"';
			} ?>
			<h3 class="bws_tab_label"><?php _e( 'Custom Search Settings', 'custom-search-plugin' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<?php if ( empty( $this->options['show_hidden_fields'] ) ) {
				$meta_key_custom_posts	=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta . " JOIN " . $wpdb->posts . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE " . $wpdb->posts . ".post_type NOT IN ('revision', 'page', 'post', 'attachment', 'nav_menu_item') AND meta_key NOT LIKE '\_%'" );
				$meta_key_result		=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta . " WHERE `meta_key` NOT LIKE '\_%'" );
				/* select all user's meta_key from table `wp_postmeta` */
			} else {
				$meta_key_custom_posts	=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta . " JOIN " . $wpdb->posts . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE " . $wpdb->posts . ".post_type NOT IN ('revision', 'page', 'post', 'attachment', 'nav_menu_item')" );
				$meta_key_result		=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta );
				/* select all meta_key from table `wp_postmeta` */
			} ?>
			<table class="form-table cstmsrch-form-table" id="cstmsrch_settings_form">
				<tr valign="top">
					<th scope="row"><?php _e( 'Enable Search by', 'custom-search-plugin' ); ?></th>
					<td class="cstmsrch_names">
						<div id="cstmsrch-post-types-settings" class="cstmsrch-checkbox-section">
							<?php if ( 0 < count( $this->post_types_custom ) ) { ?>
								<fieldset>
									<div class="cstmsrch_select_all_block">
										<label>
											<input type="checkbox" <?php echo $post_types_select_all; ?> style="display:none;" class="cstmsrch_cb_select_all" />
											<span><strong><?php _e( 'Post Types', 'custom-search-plugin' ); ?></strong></span>
										</label>
									</div>
									<?php foreach ( $this->post_types_custom as $post_type ) {
											$current_object = $this->search_objects_custom['post_type'][ $post_type ];
											$label = $current_object->labels->name; ?>
											<label>
												<input type="checkbox" <?php echo ( in_array( $post_type, $this->cstmsrch_post_types_enabled ) ? 'checked="checked"' : "" ); ?> name="cstmsrch_post_types[]" class="cstmsrch_cb_select" value="<?php echo $post_type; ?>"/>
												<span><?php echo $label; ?></span>
											</label><br />
									<?php } ?>
								</fieldset>
							<?php } else { ?>
								<div class="cstmsrch_not_custom_post"><?php _e( 'No custom post type found.', 'custom-search-plugin' ); ?></div>
							<?php } ?>
						</div><!-- #cstmsrch-post-types-settings -->
						<div id="cstmsrch-taxonomies-settings" class="cstmsrch-checkbox-section">
							<?php if ( 0 < count( $this->taxonomies_global ) ) { ?>
								<fieldset>
									<div class="cstmsrch_select_all_block">
										<label>
											<input type="checkbox" <?php echo $taxonomies_select_all; ?> style="display:none;" class="cstmsrch_cb_select_all" />
											<span"><strong><?php _e( 'Taxonomies', 'custom-search-plugin' ); ?></strong></span>
										</label>
									</div>
									<?php foreach ( $this->taxonomies_global as $taxonomy ) {
										$current_object = $this->search_objects_custom['taxonomy'][ $taxonomy ];
											$object_type = $current_object->object_type[0];
											$object_type_name = ( ! empty( $this->search_objects_custom['post_type'][ $object_type ] ) ) ? $this->search_objects_custom['post_type'][ $object_type ]->labels->name : '';
											$label = $current_object->labels->name; ?>
											<label>
												<input type="checkbox" <?php echo ( in_array( $taxonomy, $this->cstmsrch_taxonomies_enabled ) ? 'checked="checked"' : "" ); ?> name="cstmsrch_taxonomies[]" class="cstmsrch_cb_select" value="<?php echo $taxonomy; ?>"/>
												<span><?php echo "$label (" . __( 'for', 'custom-search-plugin' ) . " \"$object_type_name\")"; ?></span>
											</label><br />
									<?php } ?>
								</fieldset>
							<?php } ?>
						</div><!-- #cstmsrch-taxonomies-settings -->
					</td>
				</tr>
            </table>
            <?php if ( ! $this->hide_pro_tabs ) { ?>
							<div class="bws_pro_version_bloc">
									<div class="bws_pro_version_table_bloc">
											<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'custom-search-plugin' ); ?>"></button>
											<div class="bws_table_bg"></div>
											<table class="form-table bws_pro_version">
													<tr valign="top">
															<th scope="row"></th>
															<td>
																	<?php $objects = array(
																			$this->search_objects_custom['post_type']['post'],
																			$this->search_objects_custom['post_type']['page']
																	); ?>
																	<fieldset>
																			<?php foreach ( $objects as $current_object ) { ?>
																					<img title="" src="<?php echo plugins_url( 'custom-search-plugin/images/dragging-arrow.png' ); ?>" alt="" />
																					<label>
																							<input type="checkbox" checked="checked" disabled="disabled" />
																							<span><?php echo $current_object->labels->name; ?></span>
																					</label><br />
																			<?php } ?>
																	</fieldset>
																	<span class="bws_info"><?php _e( 'When you drag post types and taxonomies, you affect the order of their displaying in the frontend on the search page.', 'custom-search-plugin' ); ?></span>
															</td>
													</tr>
													<tr valign="top">
															<th scope="row"><?php _e( 'Current Post Type Search', 'custom-search-plugin' ); ?></th>
															<td>
																	<fieldset>
																			<label><input type="checkbox" disabled="disabled" />
																					<span class="bws_info"><?php _e( 'Enable to search current page post type only.', 'custom-search-plugin' ); ?></span>
																			</label><br />
																	</fieldset>
															</td>
													</tr>
											</table>
									</div>
									<?php $this->bws_pro_block_links(); ?>
							</div>
            <?php } ?>
            <table class="form-table cstmsrch-form-table" id="cstmsrch_settings_form">
				<tr valign="top">
					<th scope="row"><?php _e( 'Hidden Fields', 'custom-search-plugin' ); ?></th>
					<td>
						<input type="checkbox" <?php checked( $this->options['show_hidden_fields'] ); ?> name="cstmsrch_show_hidden_fields" value="1" />
						<span class="bws_info"><?php _e( 'Enable to show hidden fields.', 'custom-search-plugin' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<?php if ( 0 < count( $meta_key_result ) ) { ?>
						<th scope="row"><?php _e( 'Enable Search for the Custom Field', 'custom-search-plugin' ); ?></th>
						<?php if ( is_plugin_active( 'custom-search-pro/custom-search-pro.php' ) || is_plugin_active( 'custom-search-plugin/custom-search-plugin.php' ) ) { ?>
							<td class="cstmsrch_names">
								<fieldset>
									<div id="cstmsrch_div_select_all" style="display:none;"><label ><input id="cstmsrch_select_all" type="checkbox" /><span style="text-transform: capitalize; padding-left: 5px;"><strong><?php _e( 'All', 'custom-search-plugin' ); ?></strong></span></label></div>
									<?php foreach ( $meta_key_result as $value ) { ?>
										<label><input type="checkbox" <?php if ( in_array( $value, $this->options['fields'] ) ) echo 'checked="checked"'; ?> name="cstmsrch_fields_array[]" value="<?php echo $value; ?>" /><span class="cstmsrch_value_of_metakey"><?php echo $value; ?></span></label><br />
									<?php } ?>
								</fieldset>
							</td>
						<?php } else {
							$i = 1; ?>
							<td>
								<fieldset>
									<div id="cstmsrch_div_select_all" style="display:none;"><label ><input id="cstmsrch_select_all" type="checkbox" /><span style="text-transform: capitalize; padding-left: 5px;"><strong><?php _e( 'All', 'custom-search-plugin' ); ?></strong></span></label></div>
									<?php foreach ( $meta_key_result as $value ) {
										if ( false !== in_array( $value, $meta_key_custom_posts ) ) {
											$list_custom_key[ $i ] = $value;
											$i++;
										} else { ?>
											<label><input type="checkbox" <?php if ( in_array( $value, $this->options['fields'] ) ) echo 'checked="checked"'; ?> name="cstmsrch_fields_array[]" value="<?php echo $value; ?>" /><span class="cstmsrch_value_of_metakey"><?php echo $value; ?></span></label><br />
										<?php }
									}
									echo "<br />";
									if ( isset( $list_custom_key ) ) {
										foreach ( $list_custom_key as $value ) {
											$post_type_of_mkey = $wpdb->get_col( "SELECT DISTINCT(post_type) FROM " . $wpdb->posts . " JOIN " . $wpdb->postmeta . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE " . $wpdb->postmeta . ".meta_key LIKE ('" . $value . "')" ); ?>
											<label><input type="checkbox" disabled="disabled" name="cstmsrch_fields_array[]" value="<?php echo $value; ?>" />
											<span class="cstmsrch_disable_key">
												<?php echo $value . " (" . $post_type_of_mkey[0] . " " . __( 'custom post type', 'custom-search-plugin' ); ?>)
											</span></label><br />
										<?php }
									} ?>
								</fieldset>
							</td>
						<?php }
					} else { ?>
						<th scope="row" colspan="2"><?php _e( 'Custom fields not found.', 'custom-search-plugin' ); ?></th><td></td>
					<?php } ?>
				</tr>
			</table>
		<?php }

		public function tab_display() { ?>
			<h3 class="bws_tab_label"><?php _e( 'Data Settings', 'custom-search-plugin' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'custom-search-plugin' ); ?>"></button>
						<div class="bws_table_bg"></div>
						
						<img style="max-width: 100%;" src="<?php echo plugins_url( 'images/pro_screen_1.png', dirname( __FILE__ ) ); ?>" alt="<?php _e( "Example of site pages' tree", 'custom-search-plugin' ); ?>" title="<?php _e( "Example of site pages' tree", 'custom-search-plugin' ); ?>" />
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			<?php } ?>
		<?php }

		public function tab_appearance() { ?>
			<h3 class="bws_tab_label"><?php _e( 'Search Results Settings', 'custom-search-plugin' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table bws_search_result">
				<tr valign="top">
					<th scope="row"><?php _e( 'Search Results Display', 'custom-search-plugin' ); ?></th>
					<td>
						<fieldset>
							<label><input type="radio" name="cstmsrch_show_tabs_post_type" value="0" <?php if ( $this->options['show_tabs_post_type'] == '0' ) echo 'checked="checked"';?>/><?php _e( 'Default', 'custom-search-pro' ); ?></label><br />
							<label><input type="radio" name="cstmsrch_show_tabs_post_type" value="1" <?php if ( $this->options['show_tabs_post_type'] == '1' ) echo 'checked="checked"';?>/><?php _e( 'Tabs', 'custom-search-pro' ); ?></label><br />
						</fieldset>
					</td>
				</tr>
			</table>
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc" style="margin: 10px 0;">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'custom-search-plugin' ); ?>"></button>
						<div class="bws_table_bg"></div>
						<table class="form-table bws_pro_version">
							<tr>
								<th scope="row"><?php _e( 'Featured Image', 'custom-search-plugin' ); ?></th>
								<td><input type="checkbox" checked="checked" disabled="disabled" /><span class="bws_info"><?php _e( 'Enable to display a featured image.', 'custom-search-plugin' ); ?></span></td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Featured Image Size', 'custom-search-plugin' ); ?></th>
								<td><select disabled="disabled"><option>thumbnail (150x150)</option></select></td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Featured Image Align', 'custom-search-plugin' ); ?></th>
								<td>
									<fieldset>
										<label><input type="radio" checked="checked" disabled="disabled" /><?php _e( 'Left', 'custom-search-plugin' ); ?></label><br />
										<label><input type="radio" disabled="disabled" /><?php _e( 'Right', 'custom-search-plugin' ); ?></label><br />
									</fieldset>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Excerpt Length', 'custom-search-plugin' ); ?></th>
								<td>
									<?php _e( 'to', 'custom-search-plugin' ); ?>&nbsp;<input class="small-text" type="number" value="10" disabled="disabled" />&nbsp;<span><?php _e( 'words', 'custom-search-plugin' ); ?></span>
								</td>
							</tr>
						</table>
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			<?php } ?>
			<?php
		}

		/**
		 * Additional actions on 'Restore Settings'.
		 * @access public
		 */
		public function additional_restore_options( $options ) {
			$this->cstmsrch_post_types_enabled = $this->cstmsrch_taxonomies_enabled = array();
			foreach ( $options['output_order'] as $key => $item ) {
				if ( isset( $item['type'] ) && ! empty( $item['enabled'] ) ) {
					if ( 'post_type' == $item['type'] ) {
						$this->cstmsrch_post_types_enabled[] = $item['name'];
					} elseif ( 'taxonomy' == $item['type'] ) {
						$this->cstmsrch_taxonomies_enabled[] = $item['name'];
					}
				}
			}
			return $options;
		}

		public function display_metabox() { ?>
            <div class="postbox">
                <h3 class="hndle">
                    <?php _e( 'Custom Search Shortcode', 'custom-search-plugin' ); ?>
                </h3>
                     <div class="inside">
                        <?php _e( 'Add the "Search" to your pages or posts using the following shortcode:', 'custom-search-plugin' ); ?>
                        <?php bws_shortcode_output( "[cstmsrch_search]" ); ?>
                    </div>
            </div>
        <?php }
	}
}