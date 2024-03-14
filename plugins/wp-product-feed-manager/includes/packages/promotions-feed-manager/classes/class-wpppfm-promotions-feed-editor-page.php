<?php

/**
 * WPPRFM Google Product Promotions Feed Page Class.
 *
 * @package WP Product Promotions Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Promotions_Feed_Editor_Page' ) ) :

	/**
	 * WPPPFM Feed Form Class
	 */
	class WPPPFM_Promotions_Feed_Editor_Page extends WPPFM_Admin_Page {

		/**
		 * @var string|null contains the feed id, null for a new feed.
		 */
		private $_feed_id;

		/**
		 * @var array|null  container for the feed data.
		 */
		private $_feed_data;

		public function __construct() {

			parent::__construct();

			wppfm_check_db_version();

			$this->_feed_id = array_key_exists( 'id', $_GET ) && $_GET['id'] ? $_GET['id'] : null;

			// fill the _feed_data container.
			$this->set_feed_data();

			// load the language scripts.
			/** @noinspection PhpVoidFunctionResultUsedInspection */
			add_option( 'wp_enqueue_scripts', WPPFM_i18n_Scripts::wppfm_feed_settings_i18n() );
		}

		public function display() {
			$html  = $this->add_data_storage();
			$html .= $this->promotions_feed_editor_page();

			return $html;
		}

		/**
		 * Collects the html code for the Google merchant promotions feed form page and displays it on the screen.
		 */
		private function promotions_feed_editor_page() {
			$html  = '<div class="wppfm-page__title" id="wppfm-edit-feed-title"><h1>' . esc_html__( 'Product Feed Editor', 'wp-product-feed-manager' ) . '</h1></div>';
			$html .= $this->sub_title();

			$html .= '</div>';

			$html .= '<div class="wpppfm-promotions-feed-editor-wrapper">';

			$html .= $this->main_input_table();

			$html .= $this->promotions_feed_top_buttons();

			$html .= $this->start_promotions_area();
			$html .= $this->promotion_template();
			$html .= $this->end_promotions_area();

			$html .= $this->promotions_feed_bottom_buttons();

			$html .= $this->end_tab_grid_container();

			return $html;
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
				data-wppfm-ajax-feed-data-to-database-conversion-array=' . wp_json_encode( wppfm_ajax_feed_data_to_database_array( 'google-merchant-promotions-feed' ) ) . '
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
		 * Fetches feed data from the database and stores it in the _feed_data variable. This data is required to build the edit feed page.
		 */
		private function set_feed_data() {
			$promotions_data_class    = new WPPPFM_Data();
			$promotions_queries_class = new WPPPFM_Queries();

			$feed_data                     = $promotions_queries_class->read_feed( $this->_feed_id )[0];
			$promotion_destination_options = $promotions_data_class->get_promotion_destination_options();
			$promotion_filter_options      = $promotions_data_class->get_merchant_promotion_filter_selector_options();
			$attribute_data                = $promotions_queries_class->get_meta_data( $this->_feed_id );

			$this->_feed_data = array(
				'feed_id'                       => $this->_feed_id ?: false,
				'feed_file_name'                => $feed_data ? $feed_data['title'] : '',
				'url'                           => $feed_data ? $feed_data['url'] : '',
				'status_id'                     => $feed_data ? $feed_data['status_id'] : '',
				'feed_type_id'                  => $feed_data ? $feed_data['feed_type_id'] : '',
				'promotion_destination_options' => $promotion_destination_options,
				'promotion_filter_options'      => $promotion_filter_options,
				'attribute_data'                => $attribute_data,
			);
		}

		/**
		 * Returns the html code for the main input table.
		 */
		private function main_input_table() {
			$main_input_wrapper = new WPPPFM_Google_Merchant_Promotions_Feed_Main_Input_Wrapper();
			return $main_input_wrapper->display();
		}

		/**
		 * Returns the html code for the promotions buttons.
		 */
		private function promotions_feed_top_buttons() {
			return WPPFM_Form_Element::feed_generation_buttons( 'wppfm-top-buttons-wrapper', 'wpppfm-promotions-feed-buttons-section', 'wpppfm-generate-merchant-promotions-feed-button-bottom', 'wpppfm-save-merchant-promotions-feed-button-bottom', 'wppfm-view-feed-button-bottom' );
		}

		private function promotions_feed_bottom_buttons() {
			return WPPFM_Form_Element::feed_generation_buttons( 'wppfm-center-buttons-wrapper', 'wpppfm-promotions-feed-buttons-section', 'wpprfm-generate-merchant-promotions-feed-button-bottom', 'wpppfm-save-merchant-promotions-feed-button-bottom', 'wppfm-view-feed-button-bottom' );
		}

		private function start_promotions_area() {
			return '<section class="wpppfm-promotions-group-area">';
		}

		private function end_promotions_area() {
			return '</section>';
		}

		private function promotion_template() {
			$promotion_template = new WPPPFM_Google_Merchant_Promotion_Wrapper();
			return $promotion_template->display();
		}

	}

	// end of WPPPFM_Promotions_Feed_Editor_Page class

endif;
