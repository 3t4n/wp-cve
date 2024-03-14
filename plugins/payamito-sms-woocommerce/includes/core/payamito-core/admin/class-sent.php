<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if ( ! class_exists( "Payamito_Sent_List_Table" ) ) {
	class Payamito_Sent_List_Table extends WP_List_Table
	{

		/**
		 * Number of results to show per page
		 *
		 * @var string
		 * @since 1.4
		 */
		public $per_page = 20;

		public $export_download = false;

		public static $file_name;

		function __construct()
		{
			parent::__construct( [

				'singular' => __( 'SMS', 'payamito' ),
				'plural'   => __( 'SMS', 'payamito' ),
				'ajax'     => false,
			] );
		}

		function no_items()
		{
			_e( 'There is no SMS', 'payamito' );
		}

		function column_default( $item, $column_name )
		{
			switch ( $column_name ) {
				case 'id':
					$delete_nonce = wp_create_nonce( 'payamito_delete_sms' );

					$actions = [
						'delete' => sprintf( '<a href="?page=%s&view=sent&action=%s&item=%s&_wpnonce=%s">%s</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce, __( 'Delete', 'payamito' ) ),
					];

					return sprintf( '%1$s %2$s', ! empty( $item[ $column_name ] ) && is_numeric( str_replace( ',', '', $item[ $column_name ] ) ) ? $item[ $column_name ] : '-', $this->row_actions( $actions ) );

				case 'method':

					return self::prepare_method( $item[ $column_name ] );

				case 'reciever':

					return self::remove_0_from_mobile( $item[ $column_name ] );

				case 'date':
					return $this->column_date( $column_name );

				case 'message':

					return self::prepare_message( $item[ $column_name ] );
				case 'slug':

					return self::prepare_slug( $item[ $column_name ] );
				case 'status':

					return self::prepare_status( $item[ $column_name ] );

				default:
					return print_r( $item, true );
			}
		}

		public function advanced_export()
		{
			?>

            <div class="payamito" id="payamito-log">

                <div class="container">

                    <div class="p-2">


                        <div class="row">

                            <div class="card">
                                <h5 class="card-header"><?php
									_e( 'Log Export', 'payamito' ); ?></h5>
                                <div class="card-body">
                                    <p class="card-text"><?php
										_e( 'You can output the desired reports using the following options', 'payamito' ) ?></p>


                                    <div class="row py-3 g-3">


                                        <div class="col-12">
                                            <label class="form-label" for="payamito-status"><?php
												_e( 'columns', 'payamito' ); ?></label>
                                            <select style="with:100%" multiple class="paymito-chosen-select"
                                                    id="payamito-columns" name="payamito-columns" placeholder="<?php
											_e( 'Columns', 'payamito' ); ?>">
                                                <option value="id"><?php
													_e( 'ID', 'payamito' ); ?></option>
                                                <option value="status"><?php
													_e( 'Status', 'payamito' ); ?></option>
                                                <option value="reciever"><?php
													_e( 'Reciever', 'payamito' ); ?></option>
                                                <option value="message"><?php
													_e( 'Message', 'payamito' ); ?></option>
                                                <option value="method"><?php
													_e( 'Method', 'payamito' ); ?></option>
                                                <option value="slug"><?php
													_e( 'Operative', 'payamito' ); ?></option>
                                                <option value="date"><?php
													_e( 'Date', 'payamito' ); ?></option>
                                            </select>
                                        </div>


                                        <div class="col-sm-6">

                                            <label class="form-label" for="payamito-status"><?php
												_e( 'Status', 'payamito' ); ?></label>
                                            <select id="payamito-status" class="paymito-chosen-select"
                                                    name="payamito-status" placeholder="<?php
											_e( 'Status', 'payamito' ); ?>">
                                                <option value="all"><?php
													_e( 'All', 'payamito' ); ?></option>
                                                <option value="1"><?php
													_e( 'Success', 'payamito' ); ?></option>
                                                <option value="0"><?php
													_e( 'Filed', 'payamito' ); ?></option>
                                            </select>

                                        </div>


                                        <div class="col-sm-6"><label class="form-label" for="payamito-method"><?php
												_e( 'Method', 'payamito' ); ?></label>
                                            <select id="payamito-method" class="paymito-chosen-select"
                                                    name="payamito-method" placeholder="<?php
											_e( 'Method', 'payamito' ); ?>">
                                                <option value="all"><?php
													_e( 'All', 'payamito' ); ?></option>
                                                <option value="1"><?php
													_e( 'Pattern', 'payamito' ); ?></option>
                                                <option value="2"><?php
													_e( 'Message', 'payamito' ); ?></option>
                                            </select>
                                        </div>


                                        <div class="col-sm-6"><label class="form-label" for="payamito-operative"><?php
												_e( 'Operative', 'payamito' ); ?></label>

                                            <select id="payamito-operative" class="paymito-chosen-select"
                                                    name="payamito-operative" placeholder="<?php
											_e( 'Operative:', 'payamito' ); ?>">
                                                <option value="all"><?php
													_e( 'All', 'payamito' ); ?></option>
                                                <option value="payamito_edd"><?php
													_e( 'Easy digital downloads', 'payamito' ); ?></option>
                                                <option value="payamito_um"><?php
													_e( 'Ultimate member', 'payamito' ); ?></option>
                                                <option value="payamito_wc"><?php
													_e( 'Woocommerce', 'payamito' ); ?></option>
                                                <option value="payamito_vv"><?php
													_e( 'Vendor verification', 'payamito' ); ?></option>
                                                <option value="payamito_gf"><?php
													_e( 'Gravity form', 'payamito' ); ?></option>
                                                <option value="payamito_as"><?php
													_e( 'Awesome Support', 'payamito' ); ?></option>
                                                <option value="direct_send"><?php
													_e( 'Direct send', 'payamito' ); ?></option>
                                            </select>
                                        </div>


                                        <div class="col-sm-6">
                                            <label class="form-label" for="payamito-reciever"><?php
												_e( 'Reciever', 'payamito' ); ?></label>
                                            <input type="number" class="form-control" id="payamito-reciever"
                                                   name="payamito-reciever"/>

                                        </div>


                                        <div class="col-sm-6">

                                            <label class="form-label" for="payamito-limit"><?php
												_e( ' Limit', 'payamito' ); ?></label>
                                            <input maxlength="9" type="number" class="form-control" id="payamito-limit"
                                                   name="payamito-limit"/>
                                        </div>


                                        <div class="col-12">
                                            <input type="button" name="payamito-btn-extend" id="payamito_btn_extend"
                                                   class="btn btn-primary form-label" value="<?php
											_e( 'Export', 'payamito' ); ?>"/>


                                        </div>


                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!---->


			<?php
		}

		protected function get_views()
		{
			$status_links = [
				"all"       => __( "<a href='#'>All</a>", 'payamito' ),
				"published" => __( "<a href='#'>Published</a>", 'payamito' ),
				"success"   => __( "<a href='#'>Success</a>", 'payamito' ),
				"failed"    => __( "<a href='#'>Failed</a>", 'payamito' ),
			];

			return $status_links;
		}

		public static function remove_0_from_mobile( $mobile )
		{
			if ( trim( empty( $mobile ) ) ) {
				return "";
			}

			$is_0 = $mobile[0];
			if ( $is_0 == '0' ) {
				$mobile = substr_replace( $mobile, "", 0, 1 );
			}

			return $mobile;
		}

		public static function prepare_date( $date )
		{
			return sprintf( '%1$s', date_i18n( 'Y-m-d H:i:s', strtotime( $date ) ) );
		}

		public static function prepare_method( $metohd )
		{
			$metohd = (int) $metohd;

			switch ( $metohd ) {
				case 1:
					return __( "Pattern", "payamito" );
				case 2:
					return __( "Message", "payamito" );
			}
		}

		public static function prepare_message( $message )
		{
			$message = maybe_unserialize( $message );
			if(empty($message)){
				return "";
			}

			if ( $message != false ) {
				$text = "";
				if ( is_array( $message ) ) {
					foreach ( $message as $index => $me ) {
						$text .= $index . " --> " . $me;
						if ( count( $message ) > 1 ) {
							$text . "  | ";
						}
					}
				} else {
					return $message;
				}

				if ( ! is_string( $text ) ) {
					$message = strval( $text );
				}

				return $message = $text;
			}

			return $message;
		}

		public function get_sortable_columns()
		{
			$sortable_columns = [
				'date'     => [ 'date', false ],
				'id'       => [ 'id', false ],
				'slug'     => [ 'slug', false ],
				'reciever' => [ 'reciever', false ],
			];

			return $sortable_columns;
		}

		public static function prepare_status( $status )
		{
			if ( $status === true ) {
				return __( "Success", "payamito" );
			}
			$status = payamito_code_to_message( $status );

			return $status;
		}

		public static function prepare_slug( $slug )
		{
			switch ( $slug ) {
				case "payamito_edd":
					return __( "Easy digital downloads", "payamito" );
				case "payamito_um":
					return __( "Ultimate member", "payamito" );
				case "payamito_wc":
					return __( "Woocommerce", "payamito" );
				case "payamito_vv":
					return __( "Vendor verification", "payamito" );
				case "payamito_gf":
					return __( "Gravity form", "payamito" );
				case "payamito_as":
					return __( "Awesome Support", "payamito" );
				case "direct_send":
					return __( "Direct send", "payamito" );
				default:
					return __( "Undefind", "payamito" );
			}
		}

		function get_columns()
		{
			$columns = [
				'cb'       => '<input type="checkbox" />',
				'id'       => __( 'ID', 'payamito' ),
				'status'   => __( 'Status', 'payamito' ),
				'reciever' => __( 'Reciever', 'payamito' ),
				'message'  => __( 'Message', 'payamito' ),
				'method'   => __( 'Method', 'payamito' ),
				'slug'     => __( 'Operative', 'payamito' ),
				'date'     => __( 'Date', 'payamito' ),
			];

			return $columns;
		}

		function column_date( $item )
		{
			return sprintf( '%1$s', date_i18n( 'Y-m-d H:i:s', strtotime( $item['date'] ) ) );
		}

		function get_bulk_actions()
		{
			$actions = [
				'bulk_delete' => __( 'Delete', 'payamito' ),
				'export'      => __( 'Export(Excel)', 'payamito' ),
			];

			return $actions;
		}

		function column_cb( $item )
		{
			return sprintf( '<input type="checkbox" name="item[]" value="%s" />', $item['id'] );
		}

		function prepare_items()
		{
			$columns               = $this->get_columns();
			$hidden                = [];
			$sortable              = $this->get_sortable_columns();
			$this->_column_headers = [ $columns, $hidden, $sortable ];

			/** Process bulk action */
			$this->process_bulk_action();

			$per_page     = 100;
			$current_page = $this->get_pagenum();
			$total_items  = $this->record_count();

			$this->set_pagination_args( [
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			] );
			$this->items = $this->get_items( $per_page, $current_page );
		}

		/**
		 * Returns the count of records in the database.
		 *
		 * @return null|string
		 */
		public function record_count()
		{
			global $wpdb;
			$sent_table_name = Payamito_DB::table_name();

			$sql = "SELECT COUNT(*) FROM `{$sent_table_name}`";
			$id  = isset( $_REQUEST['id'] ) ? sanitize_text_field( $_REQUEST['id'] ) : "";

			if ( ! empty( $id ) ) {
				$var = $wpdb->get_var( $sql .= $wpdb->prepare( "WHERE `id` LIKE %% %s %%", $id ) );

				return $var;
			}

			return 0;
		}

		public function process_bulk_action()
		{
			$action = $this->current_action();

			if ( 'delete' === $action ) {
				$this->delete_item( absint( sanitize_text_field( $_REQUEST['item'] ) ) );

				echo '<div class="updated notice is-dismissible below-h2"><p>' . __( 'Item removed.', 'payamito' ) . '</p></div>';
			} else {
				if ( $action == 'bulk_delete' ) {
					$delete_ids = $this->validate_ids( $_REQUEST['item'] );
					foreach ( (array) $delete_ids as $id ) {
						$this->delete_item( absint( $id ) );
					}

					echo '<div class="updated notice is-dismissible below-h2"><p>' . __( 'Items removed.', 'payamito' ) . '</p></div>';
				}
			}

			if ( $action == 'export' ) {
				$export_ids = $this->validate_ids( $_REQUEST['item'] );
				$this->export_item( $export_ids );
			}
		}

		public function validate_ids( $ids )
		{
			if ( ! is_array( $ids ) ) {
				return [];
			}
			$validate_ids = [];
			foreach ( (array) $ids as $id ) {
				$id = sanitize_key( trim( preg_replace( '/[^0-9]/', '', $id ) ) );
				if ( is_numeric( $id ) ) {
					array_push( $validate_ids, $id );
				}
			}

			return $validate_ids;
		}

		/**
		 * Delete a item record.
		 *
		 * @param int $id item ID
		 */
		public function delete_item( $id )
		{
			global $wpdb;
			$id              = $this->sanitize( $id );
			$sent_table_name = Payamito_DB::table_name();

			$wpdb->delete( $sent_table_name, [ 'id' => $id ] );
		}

		/**
		 * Sanitize a potential Session key so we aren't fetching broken data
		 * from the options table.
		 *
		 * @param string $key Session key to sanitize.
		 *
		 * @return string
		 */
		protected function sanitize( $key )
		{
			return preg_replace( '/[^A-Za-z0-9_]/', '', $key );
		}

		/**
		 * export  items record.
		 *
		 * @param array $ids item ID
		 */
		public function export_item( $ids )
		{
			if ( count( $ids ) == 0 ) {
				echo '<div class="warning notice is-dismissible below-h2"><p>' . __( 'Please select at least one item', 'payamito' ) . '</p></div>';
			} else {
				global $wpdb;

				$sent_table_name = Payamito_DB::table_name();

				$sql = "SELECT * FROM `{$sent_table_name}`  WHERE `id` = ";

				$count = count( $ids );

				foreach ( $ids as $index => $id ) {
					$index += 1;
					if ( $index == 1 ) {
						$sql .= $wpdb->esc_like( $id );
					}

					if ( $index != 1 && $count > 1 ) {
						$sql .= " OR `id`=" . $wpdb->esc_like( $id );
					}
				}
				$sql     = $wpdb->prepare( $sql );
				$records = $wpdb->get_results( $sql, 'ARRAY_A' );
				if ( ! is_array( $records ) || count( $records ) == 0 ) {
					echo '<div class="warning notice is-dismissible below-h2"><p>' . __( 'There is no records', 'payamito' ) . '</p></div>';
				} else {
					$this->export_download = true;
					$header                = self::XLSX_set_header( 'payamito_sms', [ "*" ] );
					self::XLSXWriter( $records, $header );
				}
			}
		}

		public static function XLSXWriter( $records, $header = [] )
		{
			$SheetHeader = [];
			$row         = [];
			foreach ( $header as $item ) {
				$SheetHeader[ $item['text'] ] = $item['type'];

				foreach ( $records as $index => $record ) {
					if ( isset( $record[ $item['header'] ] ) ) {
						$row[ $index ][ $item['header'] ] = $record[ $item['header'] ];
					}
				}
			}

			foreach ( $row as $index => $record ) {
				if ( isset( $row[ $index ]['status'] ) ) {
					$row[ $index ]['status'] = self::prepare_status( $record['status'] );
				}
				if ( isset( $row[ $index ]['reciever'] ) ) {
					$row[ $index ]['reciever'] = $record['reciever'];
				}
				if ( isset( $row[ $index ]['message'] ) ) {
					$row[ $index ]['message'] = self::prepare_message( $record['message'] );
				}
				if ( isset( $row[ $index ]['method'] ) ) {
					$row[ $index ]['method'] = self::prepare_method( $record['method'] );
				}
				if ( isset( $row[ $index ]['slug'] ) ) {
					$row[ $index ]['slug'] = self::prepare_slug( $record['slug'] );
				}
				if ( isset( $row[ $index ]['date'] ) ) {
					$row[ $index ]['date'] = self::prepare_date( $record['date'] );
				}
			}
			require_once PAYAMITO_DIR . '/lib/mk-j/php_xlsxwriter/xlsxwriter.class.php';

			$writer = new XLSXWriter();

			$writer->writeSheetHeader( 'Sheet1', $SheetHeader );

			foreach ( $row as $r ) {
				$writer->writeSheetRow( 'Sheet1', $r );
			}
			$date = date( 'H:i:s', current_time( 'timestamp', 1 ) );

			self::$file_name = 'payamito_logs(' . $date . ').xlsx';

			$writer->writeToFile( self::$file_name );
		}

		public static function XLSX_set_header( $name, $header )
		{
			$full_header   = self::XLSX_get_header( $name );
			$return_header = [];
			if ( in_array( "*", $header ) ) {
				return $full_header;
			}

			foreach ( $full_header as $item ) {
				if ( in_array( $item['header'], $header ) ) {
					array_push( $return_header, $item );
				}
			}

			return $return_header;
		}

		public static function XLSX_get_header( $name )
		{
			switch ( $name ) {
				case "payamito_sms":
					$header = [
						[ 'header' => 'id', 'type' => 'integer', "text" => __( "id", "payamito" ) ],
						[ 'header' => "reciever", "type" => 'string', "text" => __( "reciever", "payamito" ) ],
						[ 'header' => "method", "type" => 'string', "text" => __( "method", "payamito" ) ],
						[ 'header' => "slug", "type" => 'string', "text" => __( "slug", "payamito" ) ],
						[ 'header' => "status", "type" => 'string', "text" => __( "status", "payamito" ) ],
						[ 'header' => "message", "type" => 'string', "text" => __( "message", "payamito" ) ],
						[ 'header' => "date", "type" => 'string', "text" => __( "date", "payamito" ) ],
					];
					$header = apply_filters( "payamito_sms_header", $header );

					return $header;
					break;
				default:
					return [];
			}
		}

		/**
		 * Retrieve items data from the database
		 *
		 * @param int $per_page
		 * @param int $page_number
		 *
		 * @return mixed
		 */
		public function get_items( $per_page = 20, $page_number = 1 )
		{
			global $wpdb;

			$sent_table_name = Payamito_DB::table_name();

			$sql = "SELECT * FROM `{$sent_table_name}`";
			$s   = isset( $_REQUEST['s'] ) ? $this->sanitize( $_REQUEST['s'] ) : "";
			$id  = isset( $_REQUEST['id'] ) ? $this->sanitize( $_REQUEST['id'] ) : "";

			$orderby = isset( $_REQUEST['orderby'] ) ? $this->sanitize( $_REQUEST['orderby'] ) : "";

			if ( ! empty( $s ) ) {
				$sql .= ' WHERE `id` LIKE "%% ' . $wpdb->esc_like( $s ) . '%%" OR  `reciever` LIKE "%%' . $wpdb->esc_like( $s ) . '%%" OR `message` LIKE "%%' . $wpdb->esc_like( $s ) . '%%' . '"  OR `method` LIKE "%%' . $wpdb->esc_like( $s ) . '%%"  OR `date` LIKE "%%' . $wpdb->esc_like( $s ) . '%%"  OR `slug` LIKE "%%' . $wpdb->esc_like( $s ) . '%%"  OR `status` LIKE "%%"';
			} else {
				if ( empty( $id ) ) {
					$sql .= ' WHERE `id` LIKE "%%' . $wpdb->esc_like( $id ) . '%%"';
				}
			}

			if ( ! empty( $orderby ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $orderby );
				$sql .= ! empty( $orderby ) ? ' ' . esc_sql( $orderby ) : ' ASC';
			} else {
				$sql .= ' ORDER BY id DESC';
			}

			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

			$sql = $wpdb->prepare( $sql );

			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			return $result;
		}
	}
}

if ( ! class_exists( "Payamito_Sent" ) ) {
	class Payamito_Sent
	{

		public function __construct() {}

		public static function enqueue_scripts()
		{
			$rtl = is_rtl();
			wp_enqueue_script( "payamito-notification-js", PAYAMITO_URL . "/assets/js/notification.js", [ 'jquery' ], false, true );
			wp_enqueue_style( "payamito-notification-css", PAYAMITO_URL . "/assets/css/notification.css", [], false );

			wp_enqueue_style( "payamito-chosen-bootstrap", PAYAMITO_URL . "/assets/css/chosen-bootstrap.css", [], false );

			wp_enqueue_script( "payamito-export", PAYAMITO_URL . "/admin/js/payamito-export.js", [ 'jquery' ], false, true );
			wp_localize_script( 'payamito-export', 'payamito_export', [
				'ajxurl' => admin_url( 'admin-ajax.php' ),
				'url'    => get_home_url() . '/wp-admin',
				'rtl'    => $rtl,
			] );
		}

		public function table()
		{
			self::enqueue_scripts();
			$payamito_sms = new Payamito_Sent_List_Table();

			echo '<div class="wrap"><h2>' . __( 'Payamito SMS', 'payamito' );
			echo '</h2>';
			$payamito_sms->prepare_items();

			echo '<style type="text/css">';
			echo '.wp-list-table .column-id { width: 5%; }';
			echo '.wp-list-table .column-method { width: 5%; }';
			echo '.wp-list-table .column-slug { width: 15%; }';
			echo '.wp-list-table .column-message { width: 30%; }';
			echo '</style>';
			$payamito_sms->advanced_export();
			?>

            <form method="post">
                <input type="hidden" name="page" value="payamito_list_table">
				<?php
				$payamito_sms->search_box( __( 'Search', 'payamito' ), 'search_id' );
				$payamito_sms->display();
				?>
            </form>
            </div>
			<?php
			if ( $payamito_sms->export_download == true ) {
				wp_enqueue_script( "payamito-downlod-export", PAYAMITO_URL . "/admin/js/downlod-export.js", [ 'jquery' ], false, true );
				wp_localize_script( 'payamito-downlod-export', 'payamito_export', [
					'download'  => true,
					'url'       => get_home_url() . '/wp-admin',
					'file_name' => $payamito_sms::$file_name,
				] );
			}
		}
	}
}
