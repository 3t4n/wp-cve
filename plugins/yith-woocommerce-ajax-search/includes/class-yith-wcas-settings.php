<?php
/**
 * Settings class
 *
 * @author  YITH
 * @package YITH/Search/Options
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Settings' ) ) {
	/**
	 * Class definition
	 */
	class YITH_WCAS_Settings {

		use YITH_WCAS_Trait_Singleton;

		/**
		 * Constructor
		 *
		 * @return void
		 */
		protected function __construct() {
			add_filter( 'pre_update_option', array( $this, 'update_options' ), 10, 3 );
		}

		/**
		 * Return the option
		 *
		 * @param string $key Option to retrieve.
		 * @param string $default Default value.
		 *
		 * @return mixed
		 */
		protected function get( $key, $default = null ) {
			$key = 'yith_wcas_' . $key;

			return get_option( $key, $default );
		}

		/**
		 * Update the option
		 *
		 * @param string $key Option to retrieve.
		 * @param string $value Value of the option to update.
		 *
		 * @return bool
		 */
		protected function update( $key, $value ) {
			$key = 'yith_wcas_' . $key;

			return update_option( $key, $value );
		}

		/**
		 * Return if the autocomplete feature is active or not
		 *
		 * @return string
		 */
		public function get_is_autocomplete() {
			return $this->get( 'enable_autocomplete', 'yes' );
		}

		/**
		 * Get the min chars to start the autocomplete search
		 *
		 * @return int
		 */
		public function get_min_chars() {
			return $this->get( 'min_chars', 3 );
		}

		/**
		 * Return synonymous list
		 *
		 * @return array
		 */
		public function get_synonymous() {
			return false;
		}

		/**
		 * Get the fuzzy distance
		 *
		 * @return int
		 */
		public function get_fuzzy_distance() {
			$fuzzy_levels   = array(
				'0'   => 1,
				'25'  => 2,
				'50'  => 3,
				'75'  => 4,
				'100' => 5,
			);
			$fuzzy_distance = $this->get( 'fuzzy_level', '50' );

			return $fuzzy_levels[ $fuzzy_distance ];
		}

		/**
		 * Return list of search fields
		 *
		 * @return int
		 */
		public function get_search_fields() {
			$search_fields = $this->get(
				'search_fields',
				array(
					array(
						'type'     => 'name',
						'priority' => 1,
					),
					array(
						'type'     => 'description',
						'priority' => 2,
					),
				)
			);
			$search_fields = array_filter( $search_fields, array(
				$this,
				'filter_right_search_fields'
			), ARRAY_FILTER_USE_BOTH );

			return apply_filters( 'ywcas_search_fields', $search_fields );
		}

		/**
		 * Remove the unsupported fields
		 *
		 * @param array $field The field.
		 * @param int   $key The key.
		 *
		 * @return bool
		 */
		public function filter_right_search_fields( $field, $key ) {
			$filter = true;

			if ( ! defined( 'YITH_WPV_PREMIUM' ) && 'yith_shop_vendor' === $field['type'] ) {
				$filter = false;
			}

			if ( ! defined( 'YITH_WCBR_PREMIUM_INIT' ) && 'yith_product_brand' === $field['type'] ) {
				$filter = false;
			}

			return $filter;
		}

		/**
		 * Get the search field by type
		 *
		 * @param string $type Type of search field to retrieve.
		 *
		 * @return array|bool
		 */
		public function get_search_field_by_type( $type ) {
			$search_fields = $this->get_search_fields();

			$search_field = false;

			$key = array_search( $type, array_column( $search_fields, 'type' ), true );
			if ( false !== $key ) {
				$search_field = $search_fields[ $key ];
			}

			return $search_field;
		}

		/**
		 * Get if the fuzzy search is enabled
		 *
		 * @return string
		 */
		public function get_enable_search_fuzzy() {
			return $this->get( 'enable_search_fuzzy', 'no' );
		}

		/**
		 * Get if the indexing is scheduled
		 *
		 * @return string
		 */
		public function get_schedule_indexing() {
			return $this->get( 'schedule_indexing', 'no' );
		}

		/**
		 * Get the interval schedule the indexing process
		 *
		 * @return string
		 */
		public function get_schedule_indexing_interval() {
			return $this->get( 'schedule_indexing_interval', 'weekly' );
		}

		/**
		 * Get the time to schedule the indexing process
		 *
		 * @return string
		 */
		public function get_schedule_indexing_time() {
			return $this->get( 'schedule_indexing_time', 1 );
		}

		/**
		 * Check if it is necessary do something when an option is updated.
		 *
		 * @param mixed  $value The new, unserialized option value.
		 * @param string $option Name of the option.
		 * @param mixed  $old_value The old option value.
		 */
		public function update_options( $value, $option, $old_value ) {
			if ( $value !== $old_value ) {
				switch ( $option ) {
					case 'yith_wcas_schedule_indexing':
					case 'yith_wcas_schedule_indexing_interval':
					case 'yith_wcas_schedule_indexing_time':
						YITH_WCAS_Data_Index_Scheduler::get_instance()->unschedule( 'yith_wcas_index_schedule' );
						break;
				}
			}

			return $value;
		}

		/**
		 * Get if the variations must be showed on search results.
		 *
		 * @return string
		 */
		public function get_include_variations() {
			return 'no';
		}

		/**
		 * Retrieve the default settings for the classic search form widget
		 *
		 * @return array
		 */
		public function get_classic_default_settings() {
			$default_args    = $this->get_default_shortcode_args();
			$args            = $default_args['default']['options'];
			$details_to_show = $args['search-results']['info-to-show'];
			$related_color   = $this->get( 'related_bg_color', array( 'bgcolor' => '#f1f1f1' ) );
			$settings        = array(
				'placeholder'            => $args['search-input']['placeholder'],
				'submitLabel'            => $args['submit-button']['button-label'],
				'showName'               => in_array( 'name', $details_to_show, true ),
				'showThumb'              => in_array( 'image', $details_to_show, true ),
				'showPrice'              => in_array( 'price', $details_to_show, true ),
				'showStock'              => in_array( 'stock', $details_to_show, true ),
				'showSku'                => in_array( 'sku', $details_to_show, true ),
				'showExcerpt'            => in_array( 'excerpt', $details_to_show, true ),
				'showAddToCart'          => in_array( 'add-to-cart', $details_to_show, true ),
				'showCategory'           => in_array( 'categories', $details_to_show, true ),
				'nameColor'              => $args['search-results']['name-color'],
				'thumbPosition'          => $args['search-results']['image-position'],
				'thumbSize'              => $args['search-results']['image-size'],
				'priceLabel'             => $args['search-results']['price-label'],
				'limitSummary'           => 'yes' === $args['search-results']['set-summary-limit'],
				'excerptNumWords'        => $args['search-results']['max-summary'],
				'layout'                 => $args['search-results']['results-layout'],
				'maxResults'             => $args['search-results']['max-results'],
				'showViewAll'            => 'yes' === $args['search-results']['show-view-all'],
				'viewAllLabel'           => $args['search-results']['view-all-label'],
				'saleBadgeLabel'         => __( 'On Sale', 'yith-woocommerce-ajax-search' ),
				'showSaleBadge'          => in_array( 'sale', $args['search-results']['badges-to-show'], true ),
				'saleBadgeColors'        => $this->get(
					'sale_badge',
					array(
						'bgcolor' => '#7eb742',
						'color'   => '#ffffff',
					)
				),
				'saleOutOfStockLabel'    => __( 'Out of stock', 'yith-woocommerce-ajax-search' ),
				'showOutOfStockBadge'    => in_array( 'out-of-stock', $args['search-results']['badges-to-show'], true ),
				'outOfStockBadgeColors'  => $this->get(
					'outofstock',
					array(
						'bgcolor' => '#7a7a7a',
						'color'   => '#ffffff',
					)
				),
				'saleFeaturedLabel'      => __( 'Featured', 'yith-woocommerce-ajax-search' ),
				'showFeaturedBadge'      => in_array( 'featured', $args['search-results']['badges-to-show'], true ),
				'featuredBadgeColors'    => $this->get(
					'featured_badge',
					array(
						'bgcolor' => '#c0392b',
						'color'   => '#ffffff',
					)
				),
				'hideFeaturedIfOnSale'   => 'yes' === $args['search-results']['show-hide-featured-if-on-sale'],
				'searchFormColors'       => array(
					'placeholder'     => $args['search-input']['colors']['placeholder'],
					'text'            => $args['search-input']['colors']['textcolor'],
					'background'      => $args['search-input']['colors']['background'],
					'backgroundFocus' => $args['search-input']['colors']['background-focus'],
					'border'          => $args['search-input']['colors']['border'],
					'borderFocus'     => $args['search-input']['colors']['border-focus'],
				),
				'searchFormBorderRadius' => $args['search-input']['border_radius'],
				'searchSubmitStyle'      => $args['submit-button']['search-style'],
				'searchSubmitColors'     => $args['submit-button']['icon-colors'],
				'showHistory'            => 'yes' === $args['extra-options']['show-history'],
				'maxHistoryResults'      => $args['extra-options']['max-history-results'],
				'historyLabel'           => $args['extra-options']['history-label'],
				'showPopular'            => 'yes' === $args['extra-options']['show-popular'],
				'maxPopularResults'      => $args['extra-options']['max-popular-results'],
				'popularLabel'           => $args['extra-options']['popular-label'],
				'noResultsLabel'         => $args['search-results']['no-results-label'],
				'relatedLabel'           => $args['search-results']['related-label'],
				'relatedPostType'        => $args['search-results']['related-to-show'],
				'maxRelatedResults'      => $args['search-results']['related-limit'],
				'relateBgColor'          => ! empty( $related_color['bgcolor'] ) ? $related_color['bgcolor']  : 'f1f1f1',
			);

			return $settings;
		}

		/**
		 * Add the old setting name to the list of checked options
		 *
		 * @param string $key Option name.
		 *
		 * @return void
		 */
		public function add_check_on_old_settings( $key ) {
			$old_setting_checked   = (array) get_option( 'ywcas_old_setting_checked', array() );
			$old_setting_checked[] = $key;
			update_option( 'ywcas_old_setting_checked', $old_setting_checked );
		}

		/**
		 * Check if the old option should be checked.
		 *
		 * @param string $key Option name.
		 *
		 * @return bool
		 */
		public function need_to_be_checked( $key ) {
			$old_setting_checked = (array) get_option( 'ywcas_old_setting_checked', array() );

			return empty( $old_setting_checked ) || ! in_array( $key, $old_setting_checked, true );
		}

		/**
		 * Return the default shortcode options
		 *
		 * @return array
		 */
		public function get_default_shortcode_options() {
			return array(
				'general'        => array(
					'name'         => __( 'Default', 'yith-woocommerce-ajax-search' ),
					'style'        => 'sm',
					'type'         => 'classic',
					'custom_class' => '',
				),
				'search-input'   => array(
					'placeholder'   => __( 'Search for products...', 'yith-woocommerce-ajax-search' ),
					'border_size'   => 1,
					'border_radius' => 20,
					'colors'        => array(
						'textcolor'        => 'rgb(136, 136, 136)',
						'placeholder'      => 'rgb(87, 87, 87)',
						'background'       => '#fff',
						'background-focus' => '#fff',
						'border'           => 'rgb(216, 216, 216)',
						'border-focus'     => 'rgb(124, 124, 124)',
					),
				),
				'submit-button'  => array(
					'search-style'  => 'icon',
					'icon-position' => 'right',
					'button-label'  => __( 'Search', 'yith-woocommerce-ajax-search-premium' ),
					'icon-colors'   => array(
						'icon'             => '#DCDCDC',
						'icon-hover'       => 'rgb(136, 136, 136)',
						'background'       => '#fff',
						'background-hover' => '#fff',
						'border'           => '#fff',
						'border-hover'     => '#fff',
					),
					'border-radius' => 20,
				),
				'search-results' => array(
					'max-results'                   => 5,
					'info-to-show'                  => array(
						'name',
						'image',
						'price',
					),
					'info-to-show-overlay'          => array(
						'name',
						'image',
						'price',
						'add-to-cart',
					),
					'columns'                       => 4,
					'rows'                          => 3,
					'name-color'                    => 'rgb(29, 29, 29)',
					'results-layout'                => 'list',
					'image-size'                    => 170,
					'image-position'                => 'left',
					'price-label'                   => __( 'Price:', 'yith-woocommerce-ajax-search' ),
					'set-summary-limit'             => 'no',
					'max-summary'                   => 10,
					'no-results-label'              => __( 'No results. Try with a different keyword!', 'yith-woocommerce-ajax-search' ),
					'show-view-all'                 => 'no',
					'view-all-label'                => __( 'See all products ({total})', 'yith-woocommerce-ajax-search' ),
					'badges-to-show'                => array( 'sale', 'featured', 'out-of-stock' ),
					'show-hide-featured-if-on-sale' => 'yes',
					'related-to-show'               => array(),
					'related-limit'                 => 3,
					'related-label'                 => __( 'Related content', 'yith-woocommerce-ajax-search' ),
				),
				'extra-options'  => array(
					'icon-colors' => array(
						'color'       => '#000',
						'color-hover' => '#000',
					),
					'show-related-categories'        => 'yes',
					'max-related-categories-results' => 3,
					'related-categories-label'       => '',
					'show-history'                   => 'yes',
					'max-history-results'            => 3,
					'history-label'                  => __( 'Latest searches', 'yith-woocommerce-ajax-search' ),
					'show-popular'                   => 'yes',
					'max-popular-results'            => 3,
					'popular-label'                  => __( 'Trending', 'yith-woocommerce-ajax-search' ),
				),
			);
		}

		/**
		 * Return the default value of shortcode arguments
		 *
		 * @return array
		 */
		public function get_default_shortcode_args() {
			return array(
				'default' => array(
					'name'    => 'Default',
					'code'    => "[yith_woocommerce_ajax_search preset='default']",
					'options' => $this->get_default_shortcode_options(),
				),
			);
		}

		/**
		 * Return the fields for the shortcode by the tab
		 *
		 * @param string $key The tab key.
		 * @param string $slug The shortcode slug.
		 *
		 * @return array
		 */
		public function get_shortcode_fields( $key, $slug ) {

			$fields = array(
				'general'        => array(
					'name'         => array(
						'id'                => 'ywcas-name-' . $key . '_' . $slug,
						'type'              => 'text',
						'label'             => __( 'Name', 'yith-woocommerce-ajax-search' ),
						'class'             => 'ywcas-shortcode-field',
						'custom_attributes' => array(
							'placeholder' => __( 'Enter a name...', 'yith-woocommerce-ajax-search' ),
						),
						'desc'              => __( 'Set a name for this shortcode.', 'yith-woocommerce-ajax-search' ),
					),
					'style'        => array(
						'id'      => 'ywcas-style-' . $key . '_' . $slug,
						'type'    => 'select',
						'label'   => __( 'Size', 'yith-woocommerce-ajax-search' ),
						'options' => array(
							'sm' => __( 'Small', 'yith-woocommerce-ajax-search' ),
							'lg' => __( 'Large', 'yith-woocommerce-ajax-search' ),
						),
						'class'   => 'ywcas-shortcode-field wc-enhanced-select',
						'desc'    => __( 'Choose the search size.', 'yith-woocommerce-ajax-search' ),
					),
					'custom_class' => array(
						'id'    => 'ywcas-custom-class-' . $key . '_' . $slug,
						'type'  => 'text',
						'label' => __( 'CSS class', 'yith-woocommerce-ajax-search' ),
						'class' => 'ywcas-shortcode-field',
						'desc'  => __(
							'Enter additional CSS classes to customize this search. 
Separate multiple classes with spaces.',
							'yith-woocommerce-ajax-search'
						),
					),
				),
				'search-input'   => $this->get_shortcode_search_input_field( $key, $slug ),
				'submit-button'  => $this->get_shortcode_submit_button_field( $key, $slug ),
				'search-results' => $this->get_shortcode_search_results_field( $key, $slug ),
			);

			return $fields[ $key ] ?? array();
		}

		/**
		 * Get all shortcode tabs
		 *
		 * @return array
		 */
		public function get_shortcode_tabs() {
			return array(
				'general'        => esc_html_x( 'General', 'Settings tab header', 'yith-woocommerce-ajax-search' ),
				'search-input'   => esc_html_x( 'Search input', 'Settings tab header', 'yith-woocommerce-ajax-search' ),
				'submit-button'  => esc_html_x( 'Submit button', 'Settings tab header', 'yith-woocommerce-ajax-search' ),
				'search-results' => esc_html_x( 'Search results', 'Settings tab header', 'yith-woocommerce-ajax-search' ),
			);

		}

		/**
		 * Get the options for search input tab
		 *
		 * @param string $key The key.
		 * @param string $slug The slug.
		 *
		 * @return array[]
		 */
		public function get_shortcode_search_input_field( $key, $slug ) {
			return array(
				'placeholder' => array(
					'id'                => 'ywcas-placeholder-' . $key . '_' . $slug,
					'label'             => __( 'Placeholder', 'yith-woocommerce-ajax-search' ),
					'type'              => 'text',
					'custom_attributes' => array(
						'placeholder' => __( 'Search for products...', 'yith-woocommerce-ajax-search' ),
					),
					'desc'              => __( 'Enter the placeholder text to show inside the search form.', 'yith-woocommerce-ajax-search' ),
					'class'             => 'ywcas-shortcode-field',
				),
				'colors'      => array(
					'id'           => 'ywcas-input-colors-' . $key . '_' . $slug,
					'label'        => __( 'Colors', 'yith-woocommerce-ajax-search' ),
					'type'         => 'multi-colorpicker',
					'colorpickers' => array(
						array(
							'id'      => 'textcolor',
							'name'    => __( 'Text', 'yith-woocommerce-ajax-search' ),
							'default' => 'rgb(136, 136, 136)',
						),
						array(
							'id'      => 'placeholder',
							'name'    => __( 'Placeholder', 'yith-woocommerce-ajax-search' ),
							'default' => 'rgb(87, 87, 87)',
						),
						array(
							'id'      => 'background',
							'name'    => __( 'Background', 'yith-woocommerce-ajax-search' ),
							'default' => '#fff',
						),
						array(
							'id'      => 'background-focus',
							'name'    => __( 'Background focus', 'yith-woocommerce-ajax-search' ),
							'default' => '#fff',
						),
						array(
							'id'      => 'border',
							'name'    => __( 'Border', 'yith-woocommerce-ajax-search' ),
							'default' => 'rgb(216, 216, 216)',
						),
						array(
							'id'      => 'border-focus',
							'name'    => __( 'Border focus', 'yith-woocommerce-ajax-search' ),
							'default' => 'rgb(124, 124, 124)',
						),
					),
				),
			);
		}

		/**
		 * Get the options for the submit button field
		 *
		 * @param string $key The key.
		 * @param string $slug The slug.
		 *
		 * @return array
		 */
		public function get_shortcode_submit_button_field( $key, $slug ) {
			return array(
				'search-style'  => array(
					'id'      => 'ywcas-search-style-' . $key . '_' . $slug,
					'type'    => 'hidden',
					'default' => 'icon',
				),
				'icon-position' => array(
					'id'      => 'ywcas-icon-position-' . $key . '_' . $slug,
					'type'    => 'radio',
					'label'   => __( 'Icon position', 'yith-woocommerce-ajax-search' ),
					'class'   => 'ywcas-toggle-button',
					'options' => array(
						'left'  => __( 'Left', 'yith-woocommerce-ajax-search' ),
						'right' => __( 'Right', 'yith-woocommerce-ajax-search' ),
					),

					'default' => 'right',
					'desc'    => __( 'Choose the icon alignment inside the search field.', 'yith-woocommerce-ajax-search' ),
					'deps'    => array(
						'id'    => 'ywcas-search-style-' . $key . '_' . $slug,
						'value' => 'icon',
						'type'  => 'show',
					),

				),
				'icon-colors'   => array(
					'id'           => 'ywcas-submit-colors-' . $key . '_' . $slug,
					'label'        => __( 'Button colors', 'yith-woocommerce-ajax-search' ),
					'type'         => 'multi-colorpicker',
					'colorpickers' => array(
						array(
							'id'      => 'icon',
							'name'    => __( 'Label', 'yith-woocommerce-ajax-search' ),
							'default' => '#DCDCDC',
						),
						array(
							'id'      => 'icon-hover',
							'name'    => __( 'Label hover', 'yith-woocommerce-ajax-search' ),
							'default' => 'rgb(136, 136, 136)',
						),
						array(
							'id'      => 'background',
							'name'    => __( 'Background', 'yith-woocommerce-ajax-search' ),
							'default' => '#fff',
						),
						array(
							'id'      => 'background-hover',
							'name'    => __( 'Background hover', 'yith-woocommerce-ajax-search' ),
							'default' => '#fff',
						),
						array(
							'id'      => 'border',
							'name'    => __( 'Border', 'yith-woocommerce-ajax-search' ),
							'default' => '#fff',
						),
						array(
							'id'      => 'border-hover',
							'name'    => __( 'Border hover', 'yith-woocommerce-ajax-search' ),
							'default' => '#fff',
						),
					),
					'desc'         => __( 'Customize the submit button colors.', 'yith-woocommerce-ajax-search' ),
				),

			);
		}

		/**
		 * Get the options for the search results tab
		 *
		 * @param string $key The key.
		 * @param string $slug The slug.
		 *
		 * @return array[]
		 */
		public function get_shortcode_search_results_field( $key, $slug ) {
			return array(
				'max-results'      => array(
					'id'      => 'ywcas-max-results-' . $key . '_' . $slug,
					'type'    => 'number',
					'label'   => __( 'Max results to show', 'yith-woocommerce-ajax-search' ),
					'min'     => 1,
					'step'    => 1,
					'default' => 5,
					'desc'    => __( 'Set how many results to show.', 'yith-woocommerce-ajax-search' ),
				),
				'info-to-show'     => array(
					'id'      => 'ywcas-info-to-show-' . $key . '_' . $slug,
					'type'    => 'checkbox-array',
					'label'   => __( 'Product info to show', 'yith-woocommerce-ajax-search' ),
					'options' => array(
						'name'  => __( 'Title', 'yith-woocommerce-ajax-search' ),
						'image' => __( 'Image', 'yith-woocommerce-ajax-search' ),
					),

				),
				'name-color'       => array(
					'id'    => 'ywcas-name-color-' . $key . '_' . $slug,
					'label' => __( 'Title color', 'yith-woocommerce-ajax-search' ),
					'type'  => 'colorpicker',
					'data'  => array(
						'ywcas-deps' => wp_json_encode(
							array(
								array(
									'id'    => 'ywcas-info-to-show-' . $key . '_' . $slug,
									'value' => 'name',
								),
							)
						),
					),
				),
				'results-layout'   => array(
					'id'    => 'ywcas-results-layout-' . $key . '_' . $slug,
					'label' => __( 'Results layout', 'yith-woocommerce-ajax-search' ),
					'type'  => 'hidden',
				),
				'image-size'       => array(
					'id'    => 'ywcas-image-size-' . $key . '_' . $slug,
					'type'  => 'number',
					'label' => __( 'Image size (px)', 'yith-woocommerce-ajax-search' ),
					'min'   => 1,
					'step'  => 1,
					'data'  => array(
						'ywcas-deps' => wp_json_encode(
							array(
								array(
									'id'    => 'ywcas-info-to-show-' . $key . '_' . $slug,
									'value' => 'image',
								),
							)
						),
					),
				),
				'image-position'   => array(
					'id'      => 'ywcas-image-position-' . $key . '_' . $slug,
					'type'    => 'radio',
					'label'   => __( 'Image position', 'yith-woocommerce-ajax-search' ),
					'options' => array(
						'left'  => __( 'Show left', 'yith-woocommerce-ajax-search' ),
						'right' => __( 'Show right', 'yith-woocommerce-ajax-search' ),
					),
					'data'    => array(
						'ywcas-deps' => wp_json_encode(
							array(
								array(
									'id'    => 'ywcas-results-layout-' . $key . '_' . $slug,
									'value' => 'list',
								),
								array(
									'id'    => 'ywcas-info-to-show-' . $key . '_' . $slug,
									'value' => 'image',
								),
							)
						),
					),
				),
				'no-results-label' => array(
					'id'    => 'ywcas-no-results-label-' . $key . '_' . $slug,
					'label' => __( 'No results notice', 'yith-woocommerce-ajax-search' ),
					'type'  => 'text',
					'class' => 'ywcas-shortcode-field',
				),
				'show-view-all'    => array(
					'id'    => 'ywcas-show-view-all-' . $key . '_' . $slug,
					'label' => __( 'Show “View all results" link', 'yith-woocommerce-ajax-search' ),
					'type'  => 'onoff',
					'desc'  => __( 'Show “View all results" link', 'yith-woocommerce-ajax-search' ),
				),
				'view-all-label'   => array(
					'id'    => 'ywcas-view-all-label-' . $key . '_' . $slug,
					'label' => __( '“View all results" text', 'yith-woocommerce-ajax-search' ),
					'type'  => 'text',
					'class' => 'ywcas-shortcode-field',
					'desc'  => __( 'Use {total} to show the total number of results.', 'yith-woocommerce-ajax-search' ),
					'deps'  => array(
						'id'    => 'ywcas-show-view-all-' . $key . '_' . $slug,
						'value' => 'yes',
						'type'  => 'hide',
					),
				),
			);
		}

		/**
		 * Return the shortcode list
		 *
		 * @return array
		 */
		public function get_shortcodes_list() {
			return apply_filters( 'ywcas_get_shortcode_list', $this->get( 'shortcodes_list', $this->get_default_shortcode_args() ) );
		}

		/**
		 * Return the shortcode list
		 *
		 * @param array $shortcode_list Shortcode list to update.
		 *
		 * @return bool
		 */
		public function update_shortcodes_list( $shortcode_list ) {
			return $this->update( 'shortcodes_list', $shortcode_list );
		}

		/**
		 * Get the source of popular searches
		 *
		 * @return string
		 */
		public function get_trending_searches_source() {
			return '';
		}

		/**
		 * Get if out of stock
		 *
		 * @return string
		 */
		public function get_hide_out_of_stock() {
			return false;
		}

		/**
		 * Return the field that should be checked before save the shortcode
		 *
		 * @return array[]
		 */
		public function get_shortcode_fields_to_check() {
			return array(
				'search-results' => array(
					'info-to-show' => array(),
				)
			);
		}
	}
}
