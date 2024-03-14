<?php
use Automattic\WooCommerce\Utilities\OrderUtil;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WooNotify_360Messenger_Archive_List_Table extends WP_List_Table {

	public static $table = 'woocommerce_360Messenger_archive';

	public function __construct() {
		if ( get_locale() == 'fa_IR' ) {
			parent::__construct( [
				'singular' => esc_html( 'آرشیو پیام های واتساپ ووکامرس' ),
				'plural'   => esc_html( 'آرشیو پیام های واتساپ ووکامرس' ),
				'ajax'     => false,
			] );
		} else {
			parent::__construct( [
				'singular' => esc_html( 'WooCommerce WhatsApp Message Archive' ),
				'plural'   => esc_html( 'Archive of WhatsApp WooCommerce messages' ),
				'ajax'     => false,
			] );
		}
	}

	public function no_items() {
		if ( get_locale() == 'fa_IR' ) {
			echo esc_html( 'موردی یافت نشد.' );
		} else {
			echo esc_html( 'Item not found.' );
		}

	}

	public function column_default( $item, $column_name ): string {

		$align = is_rtl() ? esc_html( 'right' ) : esc_html( 'left' );

		switch ( $column_name ) {

			case 'sender':
			case 'reciever':
				return '<div style="direction:ltr !important;text-align:' . esc_html( $align ) . ';">' . esc_html( $item[ $column_name ] ) . '</div>';
			default:

				if ( is_string( $item[ $column_name ] ) ) {
					return nl2br( $item[ $column_name ] );
				}

				return print_r( $item[ $column_name ], true );
		}
	}

	public function column_cb( $item ): string {
		return sprintf(
			'<input type="checkbox" name="item[]" value="%s" />', intval( $item['id'] )
		);
	}

	public function column_post_id( $item ) {

		if ( empty( $item['post_id'] ) ) {
			return '-';
		}
		$post_id = intval( $item['post_id'] );
		$is_order   = OrderUtil::is_order( $post_id, wc_get_order_types() );
 		$is_product = get_post_type( $post_id ) == 'product';

		$value = [];

		switch ( true ) {

			case $is_order:
				if ( get_locale() == 'fa_IR' ) {
					$edit_title   = esc_html( 'مدیریت سفارش' );
					$filter_title = esc_html( 'مشاهده آرشیو پیام های واتساپ این سفارش' );
					$value[]      = esc_html( 'سفارش #'  . $post_id );
				} else {
					$edit_title   = esc_html( 'Order Management' );
					$filter_title = esc_html( 'View the archive of WhatsApp messages of this order' );
					$value[]      = esc_html( 'Order #'  .  $post_id );
				}
				break;

			case $is_product:
				if ( get_locale() == 'fa_IR' ) {
					$edit_title   = esc_html( 'مدیریت محصول' );
					$filter_title = esc_html( 'مشاهده آرشیو پیام های واتساپ این محصول' );
					$value[]      = esc_html( 'محصول' );
				} else {
					$edit_title   = esc_html( 'Product Management' );
					$filter_title = esc_html( 'View the archive of WhatsApp messages of this product' );
					$value[]      = esc_html( 'product' );
				}

				$value[] = get_the_title( esc_html(sanitize_text_field( $post_id )) );
				break;

			default:
				return '-';
		}

		$actions = [
			'delete' => sprintf( '<a target="_blank" href="%s">%s</a>', get_edit_post_link( intval( $post_id ) ), esc_html( $edit_title ) ),
		];

		$post_id = '<a title="' .  esc_html($filter_title)  . '" href="' . add_query_arg( [ 'id' =>  $post_id  ] ) . '">' . implode( ' :: ',  $value  ) . '</a>';

		return sprintf( '%1$s %2$s', esc_html(sanitize_text_field( $post_id )), $this->row_actions( $actions ) );
	}

	public function column_result( $item ) {

		$result = ! empty( $item['result'] ) ? esc_html(sanitize_text_field($item['result'])) : '';

		if ( trim( $result ) == '_ok_' ) {
			if ( get_locale() == 'fa_IR' ) {
				$result = esc_html('پیام با موفقیت ارسال شد.');
			} else {
				$result = esc_html('The message was sent successfully.');
			}
		}

		return $result;
	}

	public function column_type( $item ) {

		if ( empty( $item['type'] ) ) {
			return '-';
		}

		switch ( $item['type'] ) {

			case '1':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('ارسال دسته جمعی');
				} else {
					$value = esc_html('Bulk Send');
				}
				break;

			/*customer*/
			case '2':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('مشتری - خودکار - سفارش');
				} else {
					$value = esc_html('customer - auto - order');
				}
				break;

			case '3':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('مشتری - دستی - متاباکس سفارش');
				} else {
					$value = esc_html('customer - manual - order metabox');
				}
				break;

			/*general manager*/
			case '4':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('مدیر کل - خودکار - سفارش');
				} else {
					$value = esc_html('General Manager - Auto - Order');
				}
				break;

			/* product manager*/
			case '5':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('مدیر محصول - خودکار - سفارش');
				} else {
					$value = esc_html('Product Manager - Auto - Order');
				}

				break;

			case '6':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('مدیر محصول - دستی - متاباکس محصول');
				} else {
					$value = esc_html('Product Manager - Manual - Product Metabox');
				}
				break;

			/*joint general manager and product manager*/
			case '7':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('مدیران - خودکار - ناموجود شدن');
				} else {
					$value = esc_html('administrators - auto - disappear');
				}
				break;

			case '8':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('مدیران - خودکار - کم بودن موجودی');
				} else {
					$value = esc_html('Managers - Auto - Low Stock');
				}
				break;

			/*اطلاع رسانی*/
			case '9':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('اطلاع رسانی - حراج شدن - اتوماتیک');
				} else {
					$value = esc_html('notification - auction - automatic');
				}
				break;

			case '10':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('خبرنامه - حراج شدن - دستی');
				} else {
					$value = esc_html('Newsletter - Auction - Manual');
				}
				break;
			/*--*/
			case '11':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('اطلاع رسانی - موجود شدن - اتوماتیک');
				} else {
					$value = esc_html('notification - availability - automatic');
				}
				break;

			case '12':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('اطلاع رسانی - موجود شدن - دستی');
				} else {
					$value = esc_html('notification - availability - manual');
				}
				break;
			/*--*/
			case '13':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('اطلاع رسانی - کم بودن موجودی - اتوماتیک');
				} else {
					$value = esc_html('Information - low stock - automatic');
				}
				break;

			case '14':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('اطلاع رسانی - کم بودن موجودی - دستی');
				} else {
					$value = esc_html('Information - low stock - manual');
				}
				break;
			/*--*/
			case '15':
				if ( get_locale() == 'fa_IR' ) {
					$value = esc_html('اطلاع رسانی - گزینه های دلخواه - دستی');
				} else {
					$value = esc_html('Notification - Preferences - Manual');
				}
				break;

			default:
				$value = '';
		}

		return $value;
	}

	public function column_date( $item ): string {

		$delete_nonce = wp_create_nonce( 'WooNotify_delete_archive' );

		$url = add_query_arg( [
			'action'   => 'delete',
			'item'     => intval( $item['id']),
			'_wpnonce' => $delete_nonce,
		] );
		
        if ( get_locale() == 'fa_IR' ) {
    		$actions = [
    			'delete' => sprintf( '<a href="%s">'.esc_html('حذف').'</a>', $url ),// @todo escape url
    		];
    				$date = date_i18n( 'Y-m-d H:i:s', strtotime( $item['date'] ) );
			$date = WooNotify()->mayBeJalaliDate( $date );
            
        } else {
						$actions = [
			'delete' => sprintf( '<a href="%s">'.esc_html('Delete').'</a>', $url ),// @todo escape url
		];
				$date = date_i18n( 'Y-m-d H:i:s', strtotime( $item['date'] ) );
			}




		return sprintf( '%1$s %2$s', $date, $this->row_actions( $actions ) );
	}

	public function prepare_items() {

		$this->_column_headers = [ $this->get_columns(), [], $this->get_sortable_columns() ];
		$this->process_bulk_action();

		$per_page = 20;

		$this->set_pagination_args( [
			'total_items' => $this->record_count(),
			'per_page'    => $per_page,
		] );

		$this->items = $this->get_items( $per_page, $this->get_pagenum() );
	}

	public function get_columns(): array {
		if ( get_locale() == 'fa_IR' ) {
			return [
				'cb'       => '<input type="checkbox" />',
				'date'     => esc_html('زمان'),
				'post_id'  => esc_html('سفارش / محصول'),
				'type'     => esc_html('نوع پیام'),
				'message'  => esc_html('متن پیام'),
				'reciever' => esc_html('گیرندگان'),
				'sender'   => esc_html('مسیر'),
				'result'   => esc_html('نتیجه وبسرویس'),
			];
		} else {
			return [
				'cb'       => '<input type="checkbox" />',
				'date'     => esc_html('date'),
				'post_id'  => esc_html('Order / Product'),
				'type'     => esc_html('message type'),
				'message'  => esc_html('message text'),
				'reciever' => esc_html('recipients'),
				'sender'   => esc_html('gateway'),
				'result'   => esc_html('webservice result'),
			];
		}
	}

	public function get_sortable_columns(): array {
		return [
			'post_id'  => [ 'post_id', false ],
			'type'     => [ 'type', false ],
			'sender'   => [ 'sender', false ],
			'reciever' => [ 'reciever', false ],
			'date'     => [ 'date', false ],
		];
	}

	public function process_bulk_action() {

		$action = $this->current_action();

		if ( 'delete' === $action ) {

			//if ( ! wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']) ?? null, 'WooNotify_delete_archive' ) ) {
			//new
			if ( ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce'] ) ?? null, 'WooNotify_delete_archive' ) ) {


				if ( get_locale() == 'fa_IR' ) {
					wp_die( esc_html('خطایی رخ داده است. بعدا تلاش کنید.') );
				} else {
					wp_die( esc_html('An error occurred. Try again later.') );
				}
			}

			$this->delete_item( intval( [ 'item' ] ?? 0 )  );
			if ( get_locale() == 'fa_IR' ) {
				echo '<div class="updated notice is-dismissible below-h2"><p>'.esc_html('آیتم حذف شد.').'</p></div>';
			} else {
				echo '<div class="updated notice is-dismissible below-h2"><p>'.esc_html('The item has been removed.').'</p></div>';
			}


		} else if ( $action == 'bulk_delete' ) {

			if ( ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce'] ) ?? null, 'bulk-' . $this->_args['plural'] ) ) {
				//if ( ! wp_verify_nonce( wp_unslash( $_REQUEST['_wpnonce'] ), 'bulk-' . $this->_args['plural'] ) ) {

				//  if ( ! wp_verify_nonce( wp_unslash( $_REQUEST['_wpnonce'] ), 'bulk-' . $this->_args['plural'] ) ) {
				//	if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {

				if ( get_locale() == 'fa_IR' ) {
					wp_die( esc_html('خطایی رخ داده است. بعدا تلاش کنید.') );
				} else {
					wp_die( esc_html('An error occurred. Try again later.') );
				}
			}

			$delete_ids = array_map( 'intval', $_REQUEST['item'] ?? [] );

			foreach ( (array) $delete_ids as $id ) {
				$this->delete_item( $id );
			}
			if ( get_locale() == 'fa_IR' ) {
				echo '<div class="updated notice is-dismissible below-h2"><p>'.esc_html('آیتم ها حذف شدند.').'</p></div>';
			} else {
				echo '<div class="updated notice is-dismissible below-h2"><p>'.esc_html('Items have been removed.').'</p></div>';
			}
		}
	}

	public function delete_item( int $id ) {
		global $wpdb;

		$wpdb->delete( self::table(), [ 'id' => $id ] );

	}

	/*--------------------------------------------*/

	public static function table(): string {
		global $wpdb;

		return $wpdb->prefix . self::$table;
	}

	public function record_count(): int {
		global $wpdb;

		if ( ! $this->table_exists() ) {
			return 0;
		}

		$query  = $this->get_query( true );
		$result = $wpdb->get_var( $query );

		return $result;
	}

	private function table_exists() {
		global $wpdb;

		$wild   = '%';
		$like   = $wild . $wpdb->esc_like( self::table() ) . $wild;
		$result = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES  LIKE %s", $like ) );

		return ! is_null( $result ) ? 1 : 0;
	}

	private function get_query( $count = false ) {
		global $wpdb;
$table = self::table();
if($count){
		if ( isset( $_REQUEST['s'] ) ) {
			$s   = ltrim( intval( $_REQUEST['s'] ), '0' );
			$sql = $wpdb->prepare( "SELECT count(*) FROM $table WHERE (`message` LIKE %s OR `reciever` LIKE %s  OR `sender` LIKE %s)", '%' . $wpdb->esc_like( $s ) . '%', '%' . $wpdb->esc_like( $s ) . '%', '%' . $wpdb->esc_like( $s ) . '%' );
		}


		if ( ! empty( $_REQUEST['id'] ) ) {
			//old code
			$post_id = array_map( 'absint', is_array( intval( $_REQUEST['id'] ) ) ? intval( $_REQUEST['id'] ) : explode( ',', (string) intval( $_REQUEST['id'] ) ) );
            $npost_id = implode( ',', $post_id );

			if ( isset( $s )){
			    $sql = $wpdb->prepare( "SELECT count(*) FROM $table AND `post_id` IN %s", $npost_id );
			}else{
			    $sql = $wpdb->prepare( "SELECT count(*) FROM $table WHERE `post_id` IN %s", $npost_id );
			}
		}


		if ( ! empty( $_REQUEST['orderby'] ) ) {
		    if($_REQUEST['order'] == 'DESC'){
			    $sql = $wpdb->prepare( "SELECT count(*) FROM $table ORDER BY %s DESC", sanitize_text_field( $_REQUEST['orderby'] ) );
		    }else{
		        $sql = $wpdb->prepare( "SELECT count(*) FROM $table ORDER BY %s ASC", sanitize_text_field( $_REQUEST['orderby'] ) );
		    }
		} else {
			$sql = "SELECT count(*) FROM $table  ORDER BY id DESC";
		}
}else{
   		if ( isset( $_REQUEST['s'] ) ) {
			$s   = ltrim( intval( $_REQUEST['s'] ), '0' );
			$sql = $wpdb->prepare( "SELECT * FROM $table WHERE (`message` LIKE %s OR `reciever` LIKE %s  OR `sender` LIKE %s)", '%' . $wpdb->esc_like( $s ) . '%', '%' . $wpdb->esc_like( $s ) . '%', '%' . $wpdb->esc_like( $s ) . '%' );
		}


		if ( ! empty( $_REQUEST['id'] ) ) {
			//old code
			$post_id = array_map( 'absint', is_array( intval( $_REQUEST['id'] ) ) ? intval( $_REQUEST['id'] ) : explode( ',', (string) intval( $_REQUEST['id'] ) ) );
            $npost_id = implode( ',', $post_id );

			if ( isset( $s )){
			    $sql = $wpdb->prepare( "SELECT * FROM $table AND `post_id` IN %s", $npost_id );
			}else{
			    $sql = $wpdb->prepare( "SELECT * FROM $table WHERE `post_id` IN %s", $npost_id );
			}
		}


		if ( ! empty( $_REQUEST['orderby'] ) ) {
		    if($_REQUEST['order'] == 'DESC'){
			    $sql = $wpdb->prepare( "SELECT * FROM $table ORDER BY %s DESC", sanitize_text_field( $_REQUEST['orderby'] ) );
		    }else{
		        $sql = $wpdb->prepare( "SELECT * FROM $table ORDER BY %s ASC", sanitize_text_field( $_REQUEST['orderby'] ) );
		    }
		} else {
			$sql = "SELECT * FROM $table  ORDER BY id DESC";
		} 
}


		return $sql;
	}

	public function get_items( int $per_page = 20, int $page_number = 1 ) {
		global $wpdb;

		if ( ! $this->table_exists() ) {
			return [];
		}
		
		$query   = $this->get_query();
		$query .= $wpdb->prepare( " LIMIT %d, %d ", ( $page_number - 1 ) * $per_page, $per_page );
		$results = $wpdb->get_results( $query, 'ARRAY_A' );

		return $results;
	}

	public function get_bulk_actions(): array {
		if ( get_locale() == 'fa_IR' ) {
			return [
				'bulk_delete' => esc_html('حذف'),
			];
		} else {
			return [
				'bulk_delete' =>  esc_html('bulk delete'),
			];
		}

	}

}

class WooNotify_360Messenger_Archive {

	public function __construct() {
		add_action( 'WooNotify_settings_form_bottom_360Messenger_archive', [ $this, 'archiveTable' ] );
		add_action( 'init', [ $this, 'createTable' ] );
	}

	public static function insertRecord( $data ) {
		global $wpdb;

		$time = time();

		if ( function_exists( 'wc_timezone_offset' ) ) {
			$time += wc_timezone_offset();
		}

		$wpdb->insert( WooNotify_360Messenger_Archive_List_Table::table(), [
			'post_id'  => ! empty( $data['post_id'] ) ? intval($data['post_id']) : 0,
			'type'     => ! empty( $data['type'] ) ? sanitize_text_field($data['type']) : 0,
			'reciever' => ! empty( $data['reciever'] ) ? sanitize_text_field($data['reciever'] ): '',
			'message'  => ! empty( $data['message'] ) ? sanitize_text_field($data['message']) : '',
			'sender'   => ! empty( $data['sender'] ) ? sanitize_text_field($data['sender'] ): '',
			'result'   => ! empty( $data['result'] ) ? sanitize_text_field($data['result']) : '',
			'date'     => gmdate( 'Y-m-d H:i:s', sanitize_text_field($time) ),
		], [ '%d', '%d', '%s', '%s', '%s', '%s', '%s' ] );
	}

	public function createTable() {
		global $wpdb;

		if ( get_option( 'WooNotify_table_archive' ) ) {
			return;
		}

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		}

		$charset_collate = '';

		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}

		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}

		$table = WooNotify_360Messenger_Archive_List_Table::table();

		dbDelta( "CREATE TABLE IF NOT EXISTS $table (
			id mediumint(8) unsigned NOT NULL auto_increment,
			post_id mediumint(8) unsigned,
            type tinyint(2),
			reciever TEXT NOT NULL,
			message TEXT NOT NULL,
			sender VARCHAR(100),
			result TEXT,
			date DATETIME,
			PRIMARY KEY  (id)
		) $charset_collate;" );

		update_option( 'WooNotify_table_archive', '1' );
	}

	public function archiveTable() {
		$list = new WooNotify_360Messenger_Archive_List_Table();
		$list->prepare_items();
		?>


        <style type="text/css">
            .wp-list-table .column-id {
                max-width: 5%;
            }
        </style>

		<?php if ( ! empty( $_GET['id'] ) ) : ?>
            <a class="page-title-action" href="<?php echo esc_attr( remove_query_arg( absint( [ 'id' ] ) ) ); ?>">

				<?php
				if ( get_locale() == 'fa_IR' ) {
					echo esc_html('بازگشت به لیست آرشیوهمه پیام های واتساپ');
				} else {
					echo esc_html('Return to the archive list of all WhatsApp messages');
				}

				?>


            </a>
		<?php endif; ?>

        <form method="post">
            <input type="hidden" name="page" value="WooNotify_360Messenger_Archive_list_table">
			<?php
			if ( get_locale() == 'fa_IR' ) {
				$list->search_box( esc_html('جستجوی گیرنده'), esc_html('search_id') );
			} else {
				$list->search_box( esc_html('search recipient'), esc_html('search id') );
			}

			$list->display();
			?>
        </form>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.delete a, a.delete, .button.action').on('click', function (e) {
                    var action1 = $('select[name="action"]').val();
                    var action2 = $('select[name="action2"]').val();
                    if ($(this).is('a') || action1 === 'bulk_delete' || action2 === 'bulk_delete') {
                        if (get_locale() == 'fa_IR') {
                            if (!confirm('<?php  esc_html('آیا از انجام عملیات حذف مطمئن هستید؟ این عمل غیرقابل برگشت است.') ?>')) {
                                e.preventDefault();
                                return false;
                            }
                        } else {
                            if (!confirm('<?php  esc_html('Are you sure about the delete operation? This operation is irreversible.') ?>')) {
                                e.preventDefault();
                                return false;
                            }
                        }
                    }
                });
            });
        </script>
		<?php
	}

}

new WooNotify_360Messenger_Archive();