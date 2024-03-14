<?php

/**
 * WPPFM Product Feed Manager Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Feed_Editor_Page' ) ) :

	/**
	 * Feed Editor Form Class
	 *
	 * @since 3.2.0
	 */
	class WPPFM_Feed_Editor_Page {

		/**
		 * @var string|null contains the feed id, null for a new feed.
		 */
		private $_feed_id;

		/**
		 * @var array|null  contains the feed data.
		 */
		private $_feed_data;

		/** @noinspection PhpVoidFunctionResultUsedInspection */
		public function __construct() {

			wppfm_check_db_version();

			$this->_feed_id = array_key_exists( 'id', $_GET ) && $_GET['id'] ? $_GET['id'] : null;

			$this->set_feed_data();

			add_option( 'wp_enqueue_scripts', WPPFM_i18n_Scripts::wppfm_feed_settings_i18n() );
			add_option( 'wp_enqueue_scripts', WPPFM_i18n_Scripts::wppfm_list_table_i18n() );
		}

		/**
		 * Generates the main part of the Feed Editor page
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the main part of the Feed Editor page
		 */
		public function display() {
			$html  = $this->add_data_storage();
			$html .= $this->feed_editor_page();

			return $html;
		}

		private function feed_editor_page() {
			$html  = '<div class="wppfm-page__title" id="wppfm-edit-feed-title"><h1>' . esc_html__( 'Product Feed Editor', 'wp-product-feed-manager' ) . '</h1></div>';
			$html .= $this->sub_title();

			$html .= '</div>';

			$html .= '<div class="wppfm-feed-editor-wrapper">';

			$html .= $this->main_input_table_wrapper();

			$html .= $this->category_selector_table_wrapper();

			$html .= $this->feed_top_buttons();

			$html .= $this->attribute_mapping_table_wrapper();

			$html .= $this->feed_bottom_buttons();

			$html .= '</div>';

			return $html;
		}

		/**
		 * Fetches feed data from the database and stores it in the _feed_data variable. This data is required to build the edit feed page. Stores empty
		 * data when the page is opened for a new feed.
		 */
		private function set_feed_data() {

			if ( $this->_feed_id ) {
				$queries_class = new WPPFM_Queries();
				$data_class    = new WPPFM_Data();

				$feed_data      = $queries_class->read_feed( $this->_feed_id )[0];
				$feed_filter    = $queries_class->get_product_filter_query( $this->_feed_id );
				$source_fields  = $data_class->get_source_fields();
				$attribute_data = $data_class->get_attribute_data( $this->_feed_id, $feed_data['channel'], $feed_data['feed_type_id'] );

				// Verify the categories in the stored category mapping are still active.
				$feed_data['category_mapping'] = $data_class->verify_categories_in_mapping( $feed_data['category_mapping'] );
			} else { // no feed id = a new feed
				$source_fields  = array();
				$attribute_data = array();
				$feed_filter    = '';
				$feed_data      = null;
			}

			$this->_feed_data = array(
				'feed_id'            => $this->_feed_id ?: false,
				'feed_file_name'     => $feed_data ? $feed_data['title'] : '',
				'channel_id'         => $feed_data ? $feed_data['channel'] : '',
				'feed_type_id'       => $feed_data ? $feed_data['feed_type_id'] : '',
				'language'           => $feed_data ? $feed_data['language'] : '',
				'currency'           => $feed_data ? $feed_data['currency'] : '',
				'target_country'     => $feed_data ? $feed_data['country'] : '',
				'category_mapping'   => $feed_data ? $feed_data['category_mapping'] : '',
				'main_category'      => $feed_data ? $feed_data['main_category'] : '',
				'include_variations' => $feed_data ? $feed_data['include_variations'] : '',
				'is_aggregator'      => $feed_data ? $feed_data['is_aggregator'] : '',
				'aggregator_name'    => $feed_data ? $feed_data['aggregator_name'] : '', // specifically for a Google Dynamic Remarketing Support feed
				'url'                => $feed_data ? $feed_data['url'] : '',
				'source'             => $feed_data ? $feed_data['source'] : '',
				'feed_title'         => $feed_data ? $feed_data['feed_title'] : '',
				'feed_description'   => $feed_data ? $feed_data['feed_description'] : '',
				'schedule'           => $feed_data ? $feed_data['schedule'] : '',
				'status_id'          => $feed_data ? $feed_data['status_id'] : '',
				'feed_filter'        => $feed_filter ?: null,
				'attribute_data'     => $attribute_data,
				'source_fields'      => $source_fields,
			);
		}

		/**
		 * Stores data in the DOM for the Feed Manager Feed Editor page
		 *
		 * @return string The html code for the data storage
		 */
		private function add_data_storage() {
			return
			'<div id="wppfm-feed-editor-page-data-storage" class="wppfm-data-storage-element"
				data-wppfm-feed-data="' . htmlentities( wp_json_encode( $this->_feed_data ), ENT_QUOTES ) . '"
				data-wppfm-ajax-feed-data-to-database-conversion-array=' . wp_json_encode( wppfm_ajax_feed_data_to_database_array( 'product-feed' ) ) . '
				data-wppfm-feed-url="' . $this->_feed_data['url'] . '"
				data-wppfm-all-feed-names="' . implode( ';;',  wppfm_get_all_feed_names() ) . '"
				data-wppfm-plugin-version-id="' . WPPFM_PLUGIN_VERSION_ID . '" 
				data-wppfm-plugin-version-nr="' . WPPFM_VERSION_NUM . '">
			</div>';
		}

		private function sub_title() {
			return WPPFM_Form_Element::feed_editor_sub_title( wppfm_feed_form_sub_header_text() );
		}

		/**
		 * Returns the html code for the main input table.
		 */
		private function main_input_table_wrapper() {
			$main_input_wrapper = new WPPFM_Product_Feed_Main_Input_Wrapper();
			return $main_input_wrapper->display();
		}

		/**
		 * Returns the html code for the category mapping table.
		 */
		private function category_selector_table_wrapper() {
			$category_table_wrapper = new WPPFM_Product_Feed_Category_Wrapper();
			return $category_table_wrapper->display();
		}

		/**
		 * Returns the html code for the Save & Generate Feed and Save Feed buttons at the top of the attributes list.
		 *
		 * @return string
		 */
		private function feed_top_buttons() {
			return WPPFM_Form_Element::feed_generation_buttons( 'wppfm-top-buttons-wrapper', 'page-top-buttons', 'wppfm-generate-feed-button-top', 'wppfm-save-feed-button-top', 'wppfm-view-feed-button-top', 'block' );
		}

		/**
		 * Returns the html code for the Save & Generate Feed and Save Feed buttons at the bottom of the attributes list.
		 *
		 * @return string
		 */
		private function feed_bottom_buttons() {
			return WPPFM_Form_Element::feed_generation_buttons( 'wppfm-center-buttons-wrapper', 'page-center-buttons', 'wppfm-generate-feed-button-bottom', 'wppfm-save-feed-button-bottom', 'wppfm-view-feed-button-bottom' );
		}

		/**
		 * Return the html code for the attribute mapping table.
		 */
		private function attribute_mapping_table_wrapper() {
			$attribute_mapping_wrapper = new WPPFM_Product_Feed_Attribute_Mapping_Wrapper();
			return $attribute_mapping_wrapper->display();
		}
	}

	// end of WPPFM_Feed_Editor_Page class

endif;
