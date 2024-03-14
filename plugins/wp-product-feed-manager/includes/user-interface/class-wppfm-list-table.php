<?php

/**
 * WPPFM List Table Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_List_Table' ) ) :

	/**
	 * List Table Class
	 */
	class WPPFM_List_Table {

		private $_column_titles = array();
		private $_table_id;
		private $_table_id_string;
		private $_table_list;

		/** @noinspection PhpVoidFunctionResultUsedInspection */
		public function __construct() {
			$queries = new WPPFM_Queries();

			$this->_table_id        = '';
			$this->_table_id_string = '';
			$this->_table_list      = $queries->get_feeds_list();

			add_option( 'wp_enqueue_scripts', WPPFM_i18n_Scripts::wppfm_feed_settings_i18n() );
			add_option( 'wp_enqueue_scripts', WPPFM_i18n_Scripts::wppfm_list_table_i18n() );
		}

		/**
		 * Sets the column titles
		 *
		 * @param array $titles of strings containing the column titles
		 */
		public function set_column_titles( $titles ) {
			if ( ! empty( $titles ) ) {
				$this->_column_titles = $titles;
			}
		}

		public function set_table_id( $id ) {
			if ( $id !== $this->_table_id ) {
				$this->_table_id        = $id;
				$this->_table_id_string = ' id="' . $id . '"';
			}
		}

		public function get_feed_list_table() {
			$html  = '<table class="wppfm-table wppfm-large-table tablepress widefat fixed posts" id="wppfm-feed-list-table">';
			$html .= $this->table_header();
			$html .= $this->table_body();
			$html .= $this->table_footer();
			$html .= '</table>';

			return $html;
		}

		private function table_header() {
			$html             = '<thead><tr>';
			$sortable_columns = $this->get_sortable_columns();

			foreach ( $this->_column_titles as $title ) {
				$on_click_code   = array_key_exists( strtolower( $title ), $sortable_columns ) ? ' onclick="wppfm_sortOnColumn(' . $sortable_columns[ strtolower( $title ) ] . ')"' : '';
				$sortable_header = array_key_exists( strtolower( $title ), $sortable_columns ) ? $this->sortable_title( $title ) : $title;
				$sortable_class  = array_key_exists( strtolower( $title ), $sortable_columns ) ? ' sortable desc' : '';

				$html .= '<th id="wppfm-feed-list-table-header-column-' . strtolower( $title ) . '"' . $on_click_code . ' class="wppfm-manage-column wppfm-column-name' . $sortable_class . '">' . $sortable_header . '</th>';
			}

			$html .= '</tr></thead>';

			return $html;
		}

		private function table_footer() {
			$html = '<tfoot><tr>';

			foreach ( $this->_column_titles as $title ) {
				$html .= '<th>' . $title . '</th>';
			}

			$html .= '</tr></tfoot>';

			return $html;
		}

		private function sortable_title( $title ) {
			$html  = '<a href="#"><span>' . $title . '</span><span class="sorting-indicators">';
			$html .= '<span class="sorting-indicator asc" aria-hidden="true"></span>';
			$html .= '<span class="sorting-indicator desc" aria-hidden="true"></span>';
			$html .= '</span></a>';

			return $html;
		}

		private function table_body() {
			$html        = '<tbody' . $this->_table_id_string . '>';
			$nr_products = '';
			$feed_types  = wppfm_list_feed_type_text();

			$channel_class = new WPPFM_Channel();

			foreach ( $this->_table_list as $list_item ) {
				$feed_ready_status = ( 'on_hold' === $list_item->status || 'ok' === strtolower( $list_item->status ) );
				$feed_type         = '1' === $list_item->feed_type_id ? $channel_class->get_channel_name( $list_item->channel_id ) . ' ' . __( 'Feed', 'wp-product-feed-manager' ) : $feed_types[ $list_item->feed_type_id ];

				if ( $feed_ready_status ) {
					$nr_products = $list_item->products;
				} elseif ( 'processing' === $list_item->status ) {
					$nr_products = __( 'Processing the feed, please wait...', 'wp-product-feed-manager' );
				} elseif ( 'failed_processing' === $list_item->status || 'in_processing_queue' === $list_item->status ) {
					$nr_products = __( 'Unknown', 'wp-product-feed-manager' );
				}

				$html .= '<tr id="wppfm-feed-row" class="wppfm-feed-row">';
				$html .= '<td id="title-' . $list_item->product_feed_id . '">' . $list_item->title . '</td>';
				$html .= '<td id="url">' . $list_item->url . '</td>';
				$html .= '<td id="updated-' . $list_item->product_feed_id . '">' . $list_item->updated . '</td>';
				$html .= '<td id="products-' . $list_item->product_feed_id . '">' . $nr_products . '</td>';
				$html .= '<td id="type-' . $list_item->product_feed_id . '">' . $feed_type . '</td>';
				$html .= '<td id="feed-status-' . $list_item->product_feed_id . '" style="color:' . $list_item->color . '"><strong>';
				$html .= $this->list_status_text( $list_item->status );
				$html .= '</strong></td>';
				$html .= '<td id="wppfm-feed-list-actions-for-feed-' . $list_item->product_feed_id . '">';

				if ( $feed_ready_status ) {
					$html .= $this->feed_ready_action_links( $list_item->product_feed_id, $list_item->url, $list_item->status, $list_item->title, $feed_types[ $list_item->feed_type_id ] );
				} else {
					$html .= $this->feed_not_ready_action_links( $list_item->product_feed_id, $list_item->url, $list_item->title, $feed_types[ $list_item->feed_type_id ] );
				}

				$html .= '</td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';

			return $html;
		}

		private function list_status_text( $status ) {

			switch ( $status ) {
				case 'OK': // sometimes the status is stored in capital letters
				case 'ok':
					return __( 'Ready (auto)', 'wp-product-feed-manager' );

				case 'on_hold':
					return __( 'Ready (manual)', 'wp-product-feed-manager' );

				case 'processing':
					return __( 'Processing', 'wp-product-feed-manager' );

				case 'in_processing_queue':
					return __( 'In processing queue', 'wp-product-feed-manager' );

				case 'has_errors':
					return __( 'Has errors', 'wp-product-feed-manager' );

				case 'failed_processing':
					return __( 'Failed processing', 'wp-product-feed-manager' );

				default:
					return __( 'Unknown', 'wp-product-feed-manager' );
			}
		}

		/**
		 * Defines which columns in the Feed List will be sortable.
		 *
		 * @since 2.38.0
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

		/**
		 * Generates the code for the Action buttons used in the feed list row where the feed is in ready mode.
		 * This function is the PHP equal for the feedReadyActions() function in the wppfm_feed-list.js file.
		 *
		 * @param string $feed_id
		 * @param string $feed_url
		 * @param string $status
		 * @param string $title
		 * @param string $feed_type
		 *
		 * @return string with the html code
		 * @noinspection BadExpressionStatementJS
		 * @noinspection JSVoidFunctionReturnValueUsed
		 * @noinspection CommaExpressionJS
		 */
		private function feed_ready_action_links( $feed_id, $feed_url, $status, $title, $feed_type ) {
			$file_exists    = 'No feed generated' !== $feed_url;
			$url_strings    = explode( '/', $feed_url );
			$file_name      = stripos( $feed_url, '/' ) ? end( $url_strings ) : $title;
			$change_status  = 'ok' === strtolower( $status ) ? __( 'Auto-off', 'wp-product-feed-manager' ) : __( 'Auto-on', 'wp-product-feed-manager' );
			$feed_type_link = wppfm_convert_string_with_spaces_to_lower_case_string_with_dashes( $feed_type );
			$action_id      = wppfm_convert_string_with_spaces_to_lower_case_string_with_dashes( $title );

			$html  = '<strong><a href="javascript:void(0);" id="wppfm-edit-' . $action_id . '-action" onclick="parent.location=\'admin.php?page=wppfm-feed-editor-page&feed-type=' . $feed_type_link . '&id=' . $feed_id . '\'">' . __( 'Edit', 'wp-product-feed-manager' ) . '</a>';
			$html .= $file_exists ? ' | <a href="javascript:void(0);" id="wppfm-view-' . $action_id . '-action" onclick="wppfm_viewFeed(\'' . $feed_url . '\')">' . __( 'View', 'wp-product-feed-manager' ) . '</a>' : '';
			$html .= ' | <a href="javascript:void(0);" id="wppfm-delete-' . $action_id . '-action" onclick="wppfm_deleteSpecificFeed(' . $feed_id . ', \'' . $file_name . '\')">' . __( 'Delete', 'wp-product-feed-manager' ) . '</a>';
			$html .= $file_exists && 'Google Merchant Promotions Feed' !== $feed_type ? '<a href="javascript:void(0);" id="wppfm-deactivate-' . $action_id . '-action" onclick="wppfm_deactivateFeed(' . $feed_id . ')" id="feed-status-switch-' . $feed_id . '"> | ' . $change_status . '</a>' : '';
			$html .= ' | <a href="javascript:void(0);" id="wppfm-duplicate-' . $action_id . '-action" onclick="wppfm_duplicateFeed(' . $feed_id . ', \'' . $title . '\')">' . __( 'Duplicate', 'wp-product-feed-manager' ) . '</a>';
			$html .= 'Product Feed' === $feed_type ? ' | <a href="javascript:void(0);" id="wppfm-regenerate-' . $action_id . '-action" onclick="wppfm_regenerateFeed(' . $feed_id . ')">' . __( 'Regenerate', 'wp-product-feed-manager' ) . '</a></strong>' : '';
			return $html;
		}

		/**
		 * Generates the code for the Action buttons used in the feed list row where the feed is in processing or error mode.
		 * This function is the PHP equal for the feedNotReadyActions() function in the wppfm_feed-list.js file.
		 *
		 * @param string $feed_id
		 * @param string $feed_url
		 * @param string $title
		 * @param string $feed_type
		 *
		 * @return string with the html code
		 * @noinspection BadExpressionStatementJS
		 * @noinspection JSVoidFunctionReturnValueUsed
		 * @noinspection CommaExpressionJS
		 */
		private function feed_not_ready_action_links( $feed_id, $feed_url, $title, $feed_type ) {
			if ( stripos( $feed_url, '/' ) ) {
				$url_array = explode( '/', $feed_url );
				$file_name = end( $url_array );
			} else {
				$file_name = $title;
			}

			$feed_type_link = wppfm_convert_string_with_spaces_to_lower_case_string_with_dashes( $feed_type );
			$action_id      = wppfm_convert_string_with_spaces_to_lower_case_string_with_dashes( $title );

			$html  = '<strong><a href="javascript:void(0);" id="wppfm-edit-' . $action_id . '-action" onclick="parent.location=\'admin.php?page=wppfm-feed-editor-page&feed-type=' . $feed_type_link . '&id=' . $feed_id . '\'">' . __( 'Edit', 'wp-product-feed-manager' ) . '</a>';
			$html .= ' | <a href="javascript:void(0);" id="wppfm-delete-' . $action_id . '-action" onclick="wppfm_deleteSpecificFeed(' . $feed_id . ', \'' . $file_name . '\')">' . __( 'Delete', 'wp-product-feed-manager' ) . '</a>';
			$html .= 'Product Feed' === $feed_type ? ' | <a href="javascript:void(0);" id="wppfm-regenerate-' . $action_id . '-action" onclick="wppfm_regenerateFeed(' . $feed_id . ')">' . __( 'Regenerate', 'wp-product-feed-manager' ) . '</a></strong>' : '';
			$html .= $this->feed_status_checker_script( $feed_id );
			return $html;
		}

		/**
		 * Returns a script that is placed on rows of feeds that are still processing or waiting in the queue. This script then runs every 10 seconds and checks the status
		 * of that specific feed generation processes. It is responsible for showing the correct status of this feed in the feed list.
		 *
		 * @param   string  $feed_id
		 *
		 * @return  string  script to be placed on the feed list page on the row of a running or waiting feed.
		 * @noinspection JSCheckFunctionSignatures
		 */
		private function feed_status_checker_script( $feed_id ) {
			return '<script type="text/javascript">var wppfmStatusCheck_' . $feed_id . ' = null;
				(function(){ wppfmStatusCheck_' . $feed_id . ' = window.setInterval( wppfm_checkAndSetStatus_' . $feed_id . ', 10000, ' . $feed_id . ' ) })();
				function wppfm_checkAndSetStatus_' . $feed_id . '( feedId ) {
				  wppfm_getCurrentFeedStatus( feedId, function( result ) {
				    var data = JSON.parse( result );
				    wppfm_resetFeedStatus( data );
				    if( data["status_id"] !== "3" && data["status_id"] !== "4" ) {
				      window.clearInterval( wppfmStatusCheck_' . $feed_id . ' );
	  				  wppfmRemoveFromQueueString( feedId );
				    }
				  } );
				}
				</script>';
		}
	}


	// end of WPPFM_List_Table class

endif;
