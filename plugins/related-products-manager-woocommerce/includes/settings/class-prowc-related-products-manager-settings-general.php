<?php
/**
 * Related Products Manager for WooCommerce - General Section Settings
 *
 * @version 1.4.4
 * @since   1.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_Related_Products_Manager_Settings_General' ) ) :

class ProWC_Related_Products_Manager_Settings_General extends ProWC_Related_Products_Manager_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public $id   = '';
	public $desc;
	public function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', RPMW_TEXTDOMAIN );
		parent::__construct();
		add_action( 'admin_init', array( $this, 'maybe_delete_product_transients' ), PHP_INT_MAX, 2 );
	}

	/**
	 * maybe_delete_product_transients.
	 *
	 * @since   1.4.3
	 * @version 1.0.0
	 */
	function maybe_delete_product_transients() {
		if ( isset( $_GET['prowc_clear_all_products_transients'] ) ) {
			if ( -1 != ( $time_limit = get_option( 'prowc_related_products_manager_clear_transients_time_limit', -1 ) ) ) {
				set_time_limit( $time_limit );
			}
			$offset     = 0;
			$block_size = get_option( 'prowc_related_products_manager_clear_transients_block', 1024 );
			while( true ) {
				$args = array(
					'post_type'      => 'product',
					'post_status'    => $post_status,
					'posts_per_page' => $block_size,
					'offset'         => $offset,
					'orderby'        => 'ID',
					'order'          => 'DESC',
					'fields'         => 'ids',
				);
				$loop = new WP_Query( $args );
				if ( ! $loop->have_posts() ) {
					break;
				}
				foreach ( $loop->posts as $post_id ) {
					wc_delete_product_transients( $post_id );
				}
				$offset += $block_size;
			}
			wp_safe_redirect( remove_query_arg( 'prowc_clear_all_products_transients' ) );
			exit;
		}
	}

	/**
	 * get_terms.
	 *
	 * @version 1.4.3
	 * @since   1.4.2
	 */
	function get_terms( $args ) {
		$_taxonomy = $args['taxonomy'];
		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$_terms = get_terms( $args );
		} else {
			unset( $args['taxonomy'] );
			$_terms = get_terms( $_taxonomy, $args );
		}
		$is_wpml_active = ( function_exists( 'icl_get_languages' ) && function_exists( 'icl_object_id' ) );
		if ( $is_wpml_active ) {
			if ( ! ( $languages = icl_get_languages() ) || ! is_array( $languages ) ) {
				$is_wpml_active = false;
			}
		}
		$_terms_options = array();
		if ( ! empty( $_terms ) && ! is_wp_error( $_terms ) ) {
			foreach ( $_terms as $_term ) {
				$_terms_options[ $_term->term_id ] = $_term->name;
				if ( $is_wpml_active ) {
					foreach ( $languages as $language_code => $language_data ) {
						if ( $translated_term_id = icl_object_id( $_term->term_id, $_taxonomy, false, $language_code ) ) {
							$_terms_options[ $translated_term_id ] = $_term->name . ' (' . $language_code . ')';
						}
					}
				}
			}
		}
		return $_terms_options;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.4.4
	 * @since   1.0.0
	 * @todo    [dev] (maybe) Position priority: better descriptions
	 */
	function get_settings() {
		$orderby_options = array(
			'rand'  => __( 'Random', RPMW_TEXTDOMAIN ),
			'date'  => __( 'Date', RPMW_TEXTDOMAIN ),
			'title' => __( 'Title', RPMW_TEXTDOMAIN ),
		);
		if ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ) {
			$orderby_options['meta_value']     = __( 'Meta value', RPMW_TEXTDOMAIN );
			$orderby_options['meta_value_num'] = __( 'Meta value (numeric)', RPMW_TEXTDOMAIN );
		} else {
			$orderby_options['id']             = __( 'ID', RPMW_TEXTDOMAIN );
			$orderby_options['modified']       = __( 'Modified', RPMW_TEXTDOMAIN );
			$orderby_options['menu_order']     = __( 'Menu order', RPMW_TEXTDOMAIN );
			$orderby_options['price']          = __( 'Price', RPMW_TEXTDOMAIN );
		}
		$settings = array(
			array(
				'title'    => __( 'Related Products Manager Options', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'desc'     => sprintf(
					__( 'You may need to <a href="%s" style="font-weight:bold;">clear all products transients</a> to immediately see results on frontend after changing plugin\'s settings. Alternatively you can just update each product individually to clear its transients.', RPMW_TEXTDOMAIN ),
					add_query_arg( 'prowc_clear_all_products_transients', 'yes' )
				),
				'id'       => 'prowc_related_products_manager_options',
			),
			array(
				'title'    => __( 'Related Products Manager', RPMW_TEXTDOMAIN ),
				'desc'     => '<strong>' . __( 'Enable plugin', RPMW_TEXTDOMAIN ) . '</strong>',
				'desc_tip' => '<a class="button" target="_blank" href="https://prowcplugins.com/downloads/related-products-manager-for-woocommerce/?utm_source=related-products-manager-for-woocommerce&utm_medium=referral&utm_campaign=settings">' .
					__( 'Documentation', RPMW_TEXTDOMAIN ) . '</a>',
				'id'       => 'prowc_related_products_manager_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_options',
			),
			array(
				'title'    => __( 'General', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_general_options',
			),
			array(
				'title'    => __( 'Related products number', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_num',
				'default'  => 3,
				'type'     => 'number',
			),
			array(
				'title'    => __( 'Related products columns', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_columns',
				'default'  => 3,
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_general_options',
			),
			array(
				'title'    => __( 'Order', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_order_options',
			),
			array(
				'title'    => __( 'Order by', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_orderby',
				'default'  => 'rand',
				'type'     => 'select',
				'options'  => $orderby_options,
				'class'    => 'chosen_select',
			),
		);
		if ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'Meta Key', RPMW_TEXTDOMAIN ),
					'desc_tip' => __( 'Used only if order by "Meta Value" or "Meta Value (Numeric)" is selected in "Order by".', RPMW_TEXTDOMAIN ),
					'id'       => 'prowc_related_products_manager_orderby_meta_value_meta_key',
					'default'  => '',
					'type'     => 'text',
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'title'    => __( 'Order', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'Ignored if order by "Random" is selected in "Order by".', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_order',
				'default'  => 'desc',
				'type'     => 'select',
				'options'  => array(
					'asc'  => __( 'Ascending', RPMW_TEXTDOMAIN ),
					'desc' => __( 'Descending', RPMW_TEXTDOMAIN ),
				),
				'class'    => 'chosen_select',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_order_options',
			),
			array(
				'title'    => __( 'Relate', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_relate_options',
			),
			array(
				'title'    => __( 'Relate by category', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_relate_by_category',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Relate by tag', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_relate_by_tag',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Relate by product attribute', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_relate_by_attribute_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => __( 'Attribute type', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'If using "Global attribute" enter attribute\'s <em>slug</em> in "Attribute name"', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_relate_by_attribute_type',
				'default'  => 'global',
				'type'     => 'select',
				'options'  => array(
					'global' => __( 'Global attribute', RPMW_TEXTDOMAIN ),
					'local'  => __( 'Local attribute', RPMW_TEXTDOMAIN ),
				),
				'class'    => 'chosen_select',
			),
			array(
				'desc'     => sprintf( __( 'Attribute name, e.g.: %s', RPMW_TEXTDOMAIN ), '<code>color</code>' ),
				'desc_tip' => __( 'Required.', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_relate_by_attribute_name',
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'desc'     => sprintf( __( 'Attribute value, e.g.: %s', RPMW_TEXTDOMAIN ), '<code>Red</code>' ),
				'desc_tip' => apply_filters( 'prowc_related_products_manager', __( 'Required.', RPMW_TEXTDOMAIN ) . ' ' .
					__( 'Attribute value is required in free version of the plugin. If you want to omit setting the attribute value and instead use automatically extracted attribute value of the current product - you will need Related Products Manager for WooCommerce Pro plugin.', RPMW_TEXTDOMAIN ),
					'settings_attribute_value' ),
				'id'       => 'prowc_related_products_manager_relate_by_attribute_value',
				'default'  => '',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Relate manually', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'This will add metabox to each product\'s edit page.', RPMW_TEXTDOMAIN ) .
					' ' . __( 'You will be able to select related products, product categories and/or tags manually for each product individually. There is also an option to remove related products on per product basis.', RPMW_TEXTDOMAIN ) .
					' ' . apply_filters( 'prowc_related_products_manager', sprintf( __('<br> You will need <a target="_blank" href="%s">Related Products Manager for WooCommerce Pro</a> plugin to relate on per product basis.', RPMW_TEXTDOMAIN),
						'https://prowcplugins.com/downloads/related-products-manager-for-woocommerce/?utm_source=related-products-manager-for-woocommerce&utm_medium=referral&utm_campaign=settings' ), 'settings' ),
				'id'       => 'prowc_related_products_manager_relate_per_product',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'prowc_related_products_manager', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'desc'     => __( 'Select box type', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'Sets type of select box when relating products manually on product\'s admin edit page.', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_relate_per_product_select_type',
				'default'  => 'chosen_select',
				'type'     => 'select',
				'options'  => array(
					'chosen_select' => __( 'Chosen select', RPMW_TEXTDOMAIN ),
					'standard'      => __( 'Standard', RPMW_TEXTDOMAIN ),
				),
				'class'    => 'chosen_select',
			),
			array(
				'title'    => __( 'Extra products limit', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'To display different related products on each page reload, WooCommerce takes extra products before shuffling the results. You can set this number for extra products here.', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_limit',
				'default'  => 20,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 20 ),
			),
			array(
				'title'    => __( 'Categories and tags', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'If enabled will override relation by categories and tags when relating manually or by product attribute.', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_override_cats_and_tags',
				'default'  => 'yes',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'yes'   => __( 'Override', RPMW_TEXTDOMAIN ),
					'no'    => __( 'Do not override (AND)', RPMW_TEXTDOMAIN ),
					'no_or' => __( 'Do not override (OR)', RPMW_TEXTDOMAIN ),
				),
			),
			array(
				'title'    => __( 'WPML: Use default product ID', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'If enabled will use default WPML product ID when relating manually.', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_wpml_use_default',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_relate_options',
			),
			array(
				'title'    => __( 'Exclude', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_exclude_options',
			),
			array(
				'title'    => __( 'Exclude from related', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable section', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_exclude_section_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Categories', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_exclude_taxonomy[product_cat]',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $this->get_terms( array( 'taxonomy' => 'product_cat', 'orderby' => 'name', 'hide_empty' => false ) ),
			),
			array(
				'title'    => __( 'Tags', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_exclude_taxonomy[product_tag]',
				'default'  => array(),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $this->get_terms( array( 'taxonomy' => 'product_tag', 'orderby' => 'name', 'hide_empty' => false ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_exclude_options',
			),
			array(
				'title'    => __( 'Hide', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_hide_options',
			),
			array(
				'title'    => __( 'Hide related products', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Hide', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_hide',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_hide_options',
			),
			array(
				'title'    => __( 'Position', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_position_options',
			),
			array(
				'title'    => __( 'Change related products position', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable section', RPMW_TEXTDOMAIN ),
				'desc_tip' => apply_filters( 'prowc_related_products_manager', sprintf( __( 'You will need <a target="_blank" href="%s">Related Products Manager for WooCommerce Pro</a> plugin to enable this section.', RPMW_TEXTDOMAIN ),
					'https://prowcplugins.com/downloads/related-products-manager-for-woocommerce/?utm_source=related-products-manager-for-woocommerce&utm_medium=referral&utm_campaign=settings' ), 'settings' ),
				'id'       => 'prowc_related_products_manager_position_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'prowc_related_products_manager', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'    => __( 'Position', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_position_hook',
				'default'  => 'woocommerce_after_single_product_summary',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'woocommerce_before_single_product'         => __( 'Before single product', RPMW_TEXTDOMAIN ),
					'woocommerce_before_single_product_summary' => __( 'Before single product summary', RPMW_TEXTDOMAIN ),
					'woocommerce_single_product_summary'        => __( 'Inside single product summary', RPMW_TEXTDOMAIN ),
					'woocommerce_after_single_product_summary'  => __( 'After single product summary', RPMW_TEXTDOMAIN ),
					'woocommerce_after_single_product'          => __( 'After single product', RPMW_TEXTDOMAIN ),
				),
			),
			array(
				'title'    => __( 'Position priority', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_position_priority',
				'default'  => 20,
				'type'     => 'number',
				'desc'     => '<details><summary>' . __( 'Default priorities', RPMW_TEXTDOMAIN ) . '</summary>' .
						'<p>' . sprintf( __( 'Before single product: %s', RPMW_TEXTDOMAIN ),
							'<code>' . implode( '</code>, <code>', array( 'wc_print_notices - 10' ) ) . '</code>' ) . '</p>' .
						'<p>' . sprintf( __( 'Before single product summary: %s', RPMW_TEXTDOMAIN ),
							'<code>' . implode( '</code>, <code>', array( 'woocommerce_show_product_sale_flash - 10', 'woocommerce_show_product_images - 20' ) ) . '</code>' ) . '</p>' .
						'<p>' . sprintf( __( 'Inside single product summary: %s', RPMW_TEXTDOMAIN ),
							'<code>' . implode( '</code>, <code>', array( 'woocommerce_template_single_title - 5', 'woocommerce_template_single_rating - 10', 'woocommerce_template_single_price - 10', 'woocommerce_template_single_excerpt - 20', 'woocommerce_template_single_add_to_cart - 30', 'woocommerce_template_single_meta - 40', 'woocommerce_template_single_sharing - 50' ) ) . '</code>' ) . '</p>' .
						'<p>' . sprintf( __( 'After single product summary: %s', RPMW_TEXTDOMAIN ),
							'<code>' . implode( '</code>, <code>', array( 'woocommerce_output_product_data_tabs - 10', 'woocommerce_upsell_display - 15', 'woocommerce_output_related_products - 20' ) ) . '</code>' ) . '</p>' .
					'</details>',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_position_options',
			),
			array(
				'title'    => __( 'Title', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_title_options',
			),
			array(
				'title'    => __( 'Change related products title', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Enable section', RPMW_TEXTDOMAIN ),
				'desc_tip' => apply_filters( 'prowc_related_products_manager', sprintf( __( 'You will need <a target="_blank" href="%s">Related Products Manager for WooCommerce Pro</a> plugin to enable this section.', RPMW_TEXTDOMAIN ),
					'https://prowcplugins.com/downloads/related-products-manager-for-woocommerce/?utm_source=related-products-manager-for-woocommerce&utm_medium=referral&utm_campaign=settings' ), 'settings' ),
				'id'       => 'prowc_related_products_manager_title_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'prowc_related_products_manager', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'    => __( 'Title', RPMW_TEXTDOMAIN ),
				'desc'     => sprintf( __( 'You can use shortcodes here, for example: %s', RPMW_TEXTDOMAIN ),
					'<br>' . sprintf( __( 'For category / tag / taxonomy titles, e.g.: %s.', RPMW_TEXTDOMAIN ),
						'<code>More from [prowc_rpm_product_category]</code>' ) .
					'<br>' . sprintf( __( 'For translations, e.g.: %s.', RPMW_TEXTDOMAIN ),
						'<code>[prowc_rpm_translate lang="DE"]Ähnliche Artikel[/prowc_rpm_translate][prowc_rpm_translate not_lang="DE"]Related items[/prowc_rpm_translate]</code>' )
					),
				'id'       => 'prowc_related_products_manager_title',
				'default'  => 'Related products',
				'type'     => 'text',
				'css'      => 'width:100%',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_title_options',
			),
			array(
				'title'    => __( 'Advanced', RPMW_TEXTDOMAIN ),
				'type'     => 'title',
				'id'       => 'prowc_related_products_manager_advanced_options',
			),
			array(
				'title'    => __( 'Clear all products transients', RPMW_TEXTDOMAIN ),
				'desc'     => __( 'Block size', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_clear_transients_block',
				'default'  => 1024,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 1 ),
			),
			array(
				'desc'     => __( 'Time limit (seconds)', RPMW_TEXTDOMAIN ),
				'desc_tip' => __( 'Set to -1 for the default server time limit.', RPMW_TEXTDOMAIN ),
				'id'       => 'prowc_related_products_manager_clear_transients_time_limit',
				'default'  => -1,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => -1 ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_related_products_manager_advanced_options',
			),
		) );
		return $settings;
	}

}

endif;

return new ProWC_Related_Products_Manager_Settings_General();
