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

if ( ! class_exists( 'WPPFM_Feed_List_Page' ) ) :

	/**
	 * Feed List Form Class
	 *
	 * @since 3.2.0
	 */
	class WPPFM_Feed_List_Page {

		private $_list_table;

		function __construct() {

			wppfm_check_db_version();

			$this->_list_table = new WPPFM_List_Table();

			$this->prepare_feed_list();
		}

		/**
		 * Generates the main part of the Feed_List page
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the main part of the Feed_List page
		 */
		public function display() {
			$html  = $this->add_data_storage();
			$html .= $this->feed_list_page();

			return $html;
		}

		/**
		 * Generates the main part of the Feed_List page
		 *
		 * @return string The html code for the main part of the Feed_List page
		 */
		private function feed_list_page() {
			// Feed List Page Header with Add New Feed button
			$html  = '<div class="wppfm-page__title" id="wppfm-product-feed-list-title"><h1>' . esc_html__( 'Product Feed List', 'wp-product-feed-manager' ) . '</h1></div>';
			$html .= '<div class="wppfm-button-wrapper">';
			$html .= '<a href="admin.php?page=wppfm-feed-editor-page" class="wppfm-button wppfm-blue-button" id="wppfm-add-new-feed-button"><i class="wppfm-button-icon wppfm-icon-plus"></i>' . esc_html__( 'Add New Feed', 'wp-product-feed-manager' ) . '</a>';
			$html .= '</div>';

			// Feed List Table
			$html .= '<div class="wppfm-page-layout__main" id="wppfm-product-feed-list-table">';
			$html .= $this->list_content();
			$html .= '</div>';

			return $html;
		}

		/**
		 * Stores data in the DOM for the Feed List Table
		 *
		 * @return string The html code for the data storage
		 */
		private function add_data_storage() {
			$sortable_columns = $this->get_sortable_columns();
			$feeds_in_queue   = get_site_option( 'wppfm_feed_queue', array() );

			return
				'<div id="wppfm-feed-list-page-data-storage" class="wppfm-data-storage-element" 
					data-wppfm-sort-column="none"
					data-wppfm-sort-direction="none" 
					data-wppfm-sortable-columns="' . implode( '-', $sortable_columns ) . '"
					data-wppfm-feeds-in-queue="' . implode( ',', $feeds_in_queue ) . '"
					data-wppfm-plugin-version-id="' . WPPFM_PLUGIN_VERSION_ID . '" 
					data-wppfm-plugin-version-nr="' . WPPFM_VERSION_NUM . '">
				</div>';
		}

		/**
		 * Prepares the list table
		 */
		private function prepare_feed_list() {
			$this->_list_table->set_table_id( 'wppfm-feed-list' );

			$list_columns = array(
				'col_feed_name'        => __( 'Name', 'wp-product-feed-manager' ),
				'col_feed_url'         => __( 'Url', 'wp-product-feed-manager' ),
				'col_feed_last_change' => __( 'Updated', 'wp-product-feed-manager' ),
				'col_feed_items'       => __( 'Items', 'wp-product-feed-manager' ),
			);

			$list_columns['col_feed_type'] = __( 'Type', 'wp-product-feed-manager' );

			$list_columns['col_feed_status']  = __( 'Status', 'wp-product-feed-manager' );
			$list_columns['col_feed_actions'] = __( 'Actions', 'wp-product-feed-manager' );

			// set the column names
			$this->_list_table->set_column_titles( $list_columns );
		}

		/**
		 * Activates the html for the main body top.
		 */
		private function list_content() {
			return $this->_list_table->get_feed_list_table();
		}

		/**
		 * Stores which columns in the Feed List will be sortable.
		 *
		 * @return string[]
		 */
		private function get_sortable_columns() {
			return array(
				'name'    => '1',
				'updated' => '3',
				'items'   => '4',
				'type'    => '5',
			);
		}
	}

	// end of WPPFM_Feed_List_Page class

endif;
