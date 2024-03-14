<?php
/**
 * Searchanise Admin settings
 *
 * @package Searchanise/AdminSetting
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Admin settings class
 */
class Admin_Setting {

	/**
	 * Initialization
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'woocommerce_admin_field_semultiselect', array( $this, 'get_semultiselect_field' ) );
		add_filter( 'woocommerce_admin_settings_sanitize_option_productcategory', array( $this, 'sanitize_semultiselect_option' ), 50 );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'set_settings_tab' ), 50 );
		add_action( 'woocommerce_update_options_searchanise_settings', array( $this, 'update_settings' ) );
		add_filter( 'woocommerce_sections_searchanise_settings', array( $this, 'set_section_tabs' ) );
		add_action( 'woocommerce_settings_searchanise_settings', array( $this, 'get_section' ) );
	}

	/**
	 * Add setting section tabs
	 *
	 * @param array $settings_tab Settings tab.
	 *
	 * @return void
	 */
	public function set_section_tabs( $settings_tab ) {
		global $current_section;

		$sections = array(
			''     => 'General',
			'info' => 'Info',
		);
		$allowed_tags = array(
			'a' => array(
				'href' => array(),
				'class' => array(),
			),
			'li' => array(),
		);

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			$url       = admin_url( 'admin.php?page=wc-settings&tab=searchanise_settings&section=' . sanitize_title( $id ) );
			$class     = ( $current_section === $id ? 'current' : '' );
			$separator = ( end( $array_keys ) === $id ? '' : '|' );
			$text      = esc_html( $label );
			echo wp_kses( "<li><a href='$url' class='$class'>$text</a> $separator </li>", $allowed_tags );
		}

		echo '</ul><br class="clear" />';
	}

	/**
	 * Output current section
	 *
	 * @return void
	 */
	public function get_section() {
		global $current_section, $hide_save_button, $se_need_reindexation;

		// TODO: Fix this.
		if ( $se_need_reindexation ) {
			if ( Api::get_instance()->queue_import( null, false ) ) {
				$se_need_reindexation = false;
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( __( 'The product catalog is queued for syncing with Searchanise', 'woocommerce-searchanise' ) ) . '</strong></p></div>';
			}
		}

		if ( 'info' == $current_section ) {
			$hide_save_button = true;
			require_once SE_TEMPLATES_PATH . 'searchanise_settings_info.php';
		} else {
			$this->settings_tab();
		}
	}

	/**
	 * Custom type field multiselect
	 *
	 * @param array $value Values.
	 *
	 * @return void
	 */
	public function get_semultiselect_field( $value ) {
		$option_value = $value['value'];
		$description = $value['desc'] ? '<p class="description">' . wp_kses_post( $value['desc'] ) . '</p>' : false;
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
			</th>
			<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
				<select name="<?php echo esc_attr( $value['field_name'] ); ?><?php echo '[]'; ?>" id="<?php echo esc_attr( $value['id'] ); ?>" style="<?php echo esc_attr( $value['css'] ); ?>" class="<?php echo esc_attr( $value['class'] ); ?>"
					?> <?php echo 'multiple="multiple"'; ?>>
					<?php
					foreach ( $value['options'] as $key => $val ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>"
						<?php
						if ( is_array( $option_value ) ) {
							selected( in_array( (string) $key, $option_value, true ), true );
						} else {
							$option_value = explode( ',', $option_value );
							selected( in_array( (string) $key, $option_value, true ), true );
						}
						?>
							><?php echo esc_html( $val ); ?></option>
						<?php
					}
					?>
				</select>
				<?php
				echo wp_kses(
					$description,
					array(
						'p' => array(),
						'br' => array(),
					)
				);
				?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Sanitize data for new settings type
	 *
	 * @param array $value     Value.
	 * @param array $option    Option name.
	 * @param array $raw_value Raw data.
	 *
	 * @return array $value
	 */
	public function sanitize_semultiselect_option( $value, $option, $raw_value ) {
		$value = array_filter( array_map( 'wc_clean', (array) $raw_value ) );

		return $value;
	}

	/**
	 * Add_settings_tab to Woocommerce options
	 *
	 * @param  array $settings_tabs Settings tab.
	 *
	 * @return array $settings_tabs
	 */
	public function set_settings_tab( $settings_tabs ) {
		$settings_tabs['searchanise_settings'] = Api::get_instance()->get_woocommerce_plugin_version() ? SE_PRODUCT_NAME : __( 'Searchanise', 'woocommerce-searchanise' );

		return $settings_tabs;
	}

	/**
	 * Update_settings hook save settings
	 *
	 * @return void
	 */
	public function update_settings() {
		woocommerce_update_options( $this->get_settings() );
	}

	/**
	 * Default struct setting_tab
	 *
	 * @return void
	 */
	public function settings_tab() {
		woocommerce_admin_fields( $this->get_settings() );
	}

	/**
	 * Get all Searchanise options
	 *
	 * @return array $settings
	 */
	public function get_settings() {
		// General settings.
		$settings = array(
			'se_page_option_general_start' => array(
				'name'     => __( 'General', 'woocommerce-searchanise' ),
				'type'     => 'title',
				'desc'     => '',
				'id'       => 'se_page_option_general',
			),
			'se_search_input_selector' => array(
				'name'       => __( 'Search input jQuery selector', 'woocommerce-searchanise' ),
				'type'       => 'text',
				'field_name' => 'se_search_input_selector',
				'desc'       => __( 'Important: Edit only if your custom theme changes the default search input ID!', 'woocommerce-searchanise' ),
				'id'         => 'se_search_input_selector',
				'value'      => Api::get_instance()->get_search_input_selector(),
			),
			'se_search_result_page' => array(
				'name'       => __( 'Search results page', 'woocommerce-searchanise' ),
				'type'       => 'text',
				'field_name' => 'se_search_result_page',
				'id'         => 'se_search_result_page',
				'value'      => Api::get_instance()->get_search_results_page(),
			),
			'se_enabled_searchanise_search' => array(
				'name' => __( 'Use Searchanise for Full-text search', 'woocommerce-searchanise' ),
				'type' => 'select',
				'desc' => __( 'Disable in case of invalid search operation. The instant search widget will <b>remain active</b>.', 'woocommerce-searchanise' ),
				'id'   => 'se_enabled_searchanise_search',
				'options' => array(
					'Y' => 'Yes',
					'N' => 'No',
				),
			),
			'se_use_wp_jquery' => array(
				'name'    => __( 'Use WordPress integrated jQuery version', 'woocommerce-searchanise' ),
				'type'    => 'select',
				'desc'    => __( 'Select "Yes" to use WordPress integrated jQuery version instead of Searchanise CDN version on the frontend of your website. It reduces the traffic and makes the website a little faster.', 'woocommerce-searchanise' ),
				'id'      => 'se_use_wp_jquery',
				'options' => array(
					'Y' => 'Yes',
					'N' => 'No',
				),
			),
			'se_page_option_general_end' => array(
				'type' => 'sectionend',
				'id'   => 'se_page_option_general',
			),
		);

		// Sync settings.
		$settings = array_merge(
			$settings,
			array(
				'se_sync_settings_start' => array(
					'name'     => __( 'Synchronisation settings', 'woocommerce-searchanise' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'se_sync_settings_start',
				),
				'se_sync_mode' => array(
					'name' => __( 'Sync catalog', 'woocommerce-searchanise' ),
					'type' => 'select',
					'desc' => __( 'Select <strong>When catalog updates</strong> to keep track of catalog changes and index them automatically.<br>Select <strong>Periodically via cron</strong> to index catalog changes according to "Cron resync interval" setting.<br>Select <strong>Manually</strong> to index catalog changes manually by clicking <i>FORCE RE-INDEXATION</i> button in the Searchanise control panel(<i>Products → Searchanise</i>).', 'woocommerce-searchanise' ),
					'id'   => 'se_sync_mode',
					'options' => array(
						Api::SYNC_MODE_REALTIME => __( 'When catalog updates', 'woocommerce-searchanise' ),
						Api::SYNC_MODE_PERIODIC => __( 'Periodically via cron', 'woocommerce-searchanise' ),
						Api::SYNC_MODE_MANUAL   => __( 'Manually', 'woocommerce-searchanise' ),
					),
				),
			)
		);

		if ( Api::get_instance()->is_periodic_sync_mode() ) {
			$settings = array_merge(
				$settings,
				array(
					'se_resync_interval' => array(
						'name' => __( 'Cron resync interval', 'woocommerce-searchanise' ),
						'type' => 'select',
						'desc' => __( 'Valid only if "Sync catalog" is set to "Periodically via cron"!', 'woocommerce-searchanise' ),
						'id'   => 'se_resync_interval',
						'options' => array(
							'hourly'     => __( 'Hourly', 'woocommerce-searchanise' ),
							'twicedaily' => __( 'Twice in day', 'woocommerce-searchanise' ),
							'daily'      => __( 'Daily', 'woocommerce-searchanise' ),
						),
					),
				)
			);
		}

		$settings = array_merge(
			$settings,
			array(
				'se_use_direct_image_links' => array(
					'name'    => __( 'Use direct images links', 'woocommerce-searchanise' ),
					'type'    => 'select',
					'desc'    => __( 'Note: Catalog reindexation will start automatically when value changed.', 'woocommerce-searchanise' ),
					'id'      => 'se_use_direct_image_links',
					'options' => array(
						'Y' => 'Yes',
						'N' => 'No',
					),
				),
				'se_import_block_posts' => array(
					'name'    => __( 'Import blog posts', 'woocommerce-searchanise' ),
					'type'    => 'select',
					'desc'    => __( 'Select "Yes" if you want Searchanise search by block posts as pages.</br>Note: Catalog reindexation will start automatically when value changed..', 'woocommerce-searchanise' ),
					'id'      => 'se_import_block_posts',
					'options' => array(
						'Y' => 'Yes',
						'N' => 'No',
					),
				),
				'se_color_attribute' => array(
					'name'    => __( 'Color attribute', 'woocommerce-searchanise' ),
					'type'    => 'multiselect',
					'class'   => 'multiselect wc-enhanced-select',
					'id'      => 'se_color_attribute',
					'options' => $this->get_option_values( 'product_filters' ),
				),
				'se_size_attribute' => array(
					'name'    => __( 'Size attribute', 'woocommerce-searchanise' ),
					'type'    => 'multiselect',
					'class'   => 'multiselect wc-enhanced-select',
					'id'      => 'se_size_attribute',
					'options' => $this->get_option_values( 'product_filters' ),
				),
				'se_sync_settings_end' => array(
					'type' => 'sectionend',
					'id'   => 'se_sync_settings_end',
				),
				'se_advance_sync_settings_start' => array(
					'name'     => __( 'Advanced synchronisation settings', 'woocommerce-searchanise' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'se_advance_sync_settings_start',
				),
			)
		);

		$custom_attributes = $this->get_option_values( 'custom_attributes' );
		$custom_product_fields = $this->get_option_values( 'custom_product_fields' );

		if ( isset( $_GET['insert_taxonomies'] ) && 'true' === $_GET['insert_taxonomies'] ) {
			$settings = array_merge(
				$settings,
				array(
					'se_custom_taxonomies' => array(
						'name'       => __( 'Custom taxonomies for Indexation', 'woocommerce-searchanise' ),
						'type'       => 'text',
						'field_name' => 'se_custom_taxonomies',
						'desc'       => __( 'Insert the slug name of the custom product taxonomies separated by commas.</br>New filters will be created for them.', 'woocommerce-searchanise' ),
						'id'         => 'se_custom_taxonomies',
						'value'      => Async::get_instance()->get_custom_taxonomies(),
					),
				)
			);
		}

		if ( ! empty( $custom_attributes ) ) {
			$settings = array_merge(
				$settings,
				array(
					'se_custom_attribute' => array(
						'name'    => __( 'Custom taxonomies', 'woocommerce-searchanise' ),
						'type'    => 'multiselect',
						'class'   => 'multiselect wc-enhanced-select',
						'desc'    => __( 'Select custom product taxonomies to import. Selected taxonomies data will be imported and new filters will be created for them.</br>Note: Catalog reindexation will start automatically when value changed.', 'woocommerce-searchanise' ),
						'id'      => 'se_custom_attribute',
						'options' => $custom_attributes,
					),
				)
			);
		}

		if ( ! empty( $custom_product_fields ) ) {
			$settings = array_merge(
				$settings,
				array(
					'se_custom_product_fields' => array(
						'name'    => __( 'Custom product meta fields', 'woocommerce-searchanise' ),
						'type'    => 'multiselect',
						'class'   => 'multiselect wc-enhanced-select',
						'desc'    => 'Select custom product fields to import. Selected fields data will be imported and it will be possible to search for products by them.</br>Note: Catalog reindexation will start automatically when value changed.',
						'id'      => 'se_custom_product_fields',
						'options' => $custom_product_fields,
					),
				)
			);
		}

		$settings = array_merge(
			$settings,
			array(
				'se_excluded_tags' => array(
					'name'    => __( 'Exclude products with these tags', 'woocommerce-searchanise' ),
					'type'    => 'multiselect',
					'class'   => 'multiselect wc-enhanced-select',
					'desc'    => 'Product with these tags will be excluded from indexation.<br />Note: Catalog reindexation will start automatically when value changed.',
					'id'      => 'se_excluded_tags',
					'options' => $this->get_option_values( 'excluded_tags' ),
				),
				'se_excluded_pages' => array(
					'name'    => __( 'Exclude these pages', 'woocommerce-searchanise' ),
					'type'    => 'multiselect',
					'class'   => 'multiselect wc-enhanced-select',
					'desc'    => __( 'These pages will be excluded from indexation and will not be displayed in search results.<br />Note: Catalog reindexation will start automatically when value changed.', 'woocommerce-searchanise' ),
					'id'      => 'se_excluded_pages',
					'options' => $this->get_option_values( 'excluded_pages' ),
				),
				'se_excluded_categories' => array(
					'name'    => __( 'Exclude these categories', 'woocommerce-searchanise' ),
					'type'    => 'multiselect',
					'class'   => 'multiselect wc-enhanced-select',
					'desc'    => __( 'These categories will be excluded from indexation and will not be displayed in search results.<br />Note: Catalog reindexation will start automatically when value changed.', 'woocommerce-searchanise' ),
					'id'      => 'se_excluded_categories',
					'options' => $this->get_option_values( 'excluded_categories' ),
				),

				'se_advance_sync_settings_end' => array(
					'type' => 'sectionend',
					'id'   => 'se_advance_sync_settings_end',
				),
				'se_admin_settings_start' => array(
					'name'     => __( 'Admin settings', 'woocommerce-searchanise' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'se_admin_settings_start',
				),
				'se_show_analytics_on_dashboard' => array(
					'name'    => __( 'Show Smart Search dashboard widget', 'woocommerce-searchanise' ),
					'type'    => 'select',
					'desc'    => __( 'Select "Yes" to display "Smart Search Analytics" widget on dashboard page.', 'woocommerce-searchanise' ),
					'id'      => 'se_show_analytics_on_dashboard',
					'options' => array(
						'Y' => 'Yes',
						'N' => 'No',
					),
				),
				'se_admin_settings_end' => array(
					'type' => 'sectionend',
					'id'   => 'se_admin_settings_end',
				),
			)
		);

		/**
		 * Get all Searchanise options
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings Settings.
		 */
		return apply_filters( 'wc_settings_tab_searchanise_settings', $settings );
	}

	/**
	 * Get Option values for option_name
	 *
	 * @param  string $option_name Option name.
	 *
	 * @return array $results
	 */
	public function get_option_values( $option_name ) {
		$results = array();

		switch ( $option_name ) {
			case 'product_filters':
				$options = Async::get_instance()->get_attribute_filters( Api::get_instance()->get_locale() );
				break;
			case 'custom_attributes':
				$options = Async::get_instance()->generate_custom_product_attribute();
				break;
			case 'excluded_tags':
				$options = Async::get_instance()->get_product_tags( Api::get_instance()->get_locale() );
				break;
			case 'excluded_pages':
				$options = $this->get_all_pages( Api::get_instance()->get_locale() );
				break;
			case 'excluded_categories':
				$options = $this->get_all_categories( Api::get_instance()->get_locale() );
				break;
			case 'custom_product_fields':
				$options = Async::get_instance()->get_meta_product_types();
				break;
			default:
				$options = array();
		}

		if ( in_array( $option_name, array( 'excluded_pages', 'excluded_categories' ) ) ) {
			foreach ( $options as $slug => $title ) {
				$results = array_merge( $results, array( $slug => $title ) );
			}
		} elseif ( 'custom_product_fields' == $option_name ) {
			foreach ( $options as $option ) {
				$results = array_merge( $results, array( $option->name => $option->label ) );
			}
		} else {
			foreach ( $options as $option ) {
				$results = array_merge( $results, array( $option['name'] => $option['label'] ) );
			}
		}

		return $results;
	}

	/**
	 * Returns all pages for system settings
	 *
	 * @param string $lang_code Language code.
	 *
	 * @return array
	 */
	public function get_all_pages( $lang_code ) {
		$pages = array();

		$posts = get_posts(
			array(
				'post_type'   => Async::get_post_types(),
				'numberposts' => -1,
			)
		);

		foreach ( $posts as $post ) {
			$pages[ $post->post_name ] = $post->post_title;
		}

		/**
		 * Returns all pages for system settings
		 *
		 * @since 1.0.0
		 *
		 * @param array $pages
		 * @param string $lang_code
		 */
		return (array) apply_filters( 'se_get_all_pages', $pages, $lang_code );
	}

	/**
	 * Returns all categories for system settings
	 *
	 * @param string $lang_code Language code.
	 *
	 * @return array
	 */
	public function get_all_categories( $lang_code ) {
		$categories = array();

		$terms = get_terms( 'product_cat' );

		foreach ( $terms as $term ) {
			$categories[ $term->slug ] = $term->name;
		}

		/**
		 * Returns all categories for system settings
		 *
		 * @since 1.0.0
		 *
		 * @param array $categories
		 * @param string $lang_code
		 */
		return (array) apply_filters( 'se_get_all_categories', $categories, $lang_code );
	}
}
