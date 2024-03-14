<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WooNotify_360Messenger_Contacts_List_Table extends WP_List_Table {

	public static $table = 'woocommerce_360Messenger_contacts';
	private static $users = [];

	public function __construct() {
		if (get_locale() == 'fa_IR'){
			$text1=esc_html('لیست مشترکین اطلاع رسانی محصولات ووکامرس');
		}
		else
		{
			$text1=esc_html('List of WooCommerce product notification subscribers');
		}
		parent::__construct( [
			'singular' => esc_html(sanitize_text_field($text1)),
			'plural'   => esc_html(sanitize_text_field($text1)),
			'ajax'     => false,
		] );
	}

	public function no_items() {
		if (get_locale() == 'fa_IR')
			echo esc_html('موردی یافت نشد.');
		else
			echo esc_html('Item not found.');
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			default:
				return print_r( $item, true );
		}
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="item[]" value="%s" />', esc_html($item['id'])
		);
	}

	public function column_mobile( $item ) {

		if ( empty( $item['mobile'] ) ) {
			return '-';
		}

		$mobile = $this->mobile_with_user( $item['mobile'] );

		$product_ids = self::request_product_id( true );
		if ( count( $product_ids ) == 1 ) {
			$mobile .= $this->column_product_id( $item, false );
		}

		return '<div style="direction:ltr !important; text-align:' . ( is_rtl() ? 'right' : 'left' ) . ';">' . esc_html($mobile) . '</div>';
	}

	private function mobile_with_user( $_mobile ) {

		$mobile = self::prepareMobile( esc_html(sanitize_text_field($_mobile)) );
		$user   = ! empty( esc_html(sanitize_text_field(self::$users))[ esc_html(sanitize_text_field($mobile)) ] ) ? esc_html(sanitize_text_field(self::$users))[ esc_html(sanitize_text_field($mobile)) ] : (object) [];
		$mobile = WooNotify()->modifyMobile( esc_html(sanitize_text_field($mobile)) );

		if ( ! empty( $user->ID ) ) {

			$user_id = $user->ID;

			$full_name = get_user_meta( $user_id, 'billing_first_name', true ) . ' ' . get_user_meta( $user_id, 'billing_last_name', true );
			$full_name = trim( $full_name );
			if ( empty( $full_name ) && ! empty( $user->display_name ) ) {
				$full_name = ucwords( $user->display_name );
			}

			if ( ! empty( $full_name ) ) {
				$mobile = '(' . $full_name . ')&lrm;  ' . $_mobile;
				$mobile = '<a target="_blank" href="' . get_edit_user_link( absint(sanitize_text_field($user_id) )) . '">' . esc_html($mobile) . '</a>';
			}
		}

		return esc_html(sanitize_text_field($mobile));
	}

	public static function prepareMobile( $mobile ) {
		return substr( ltrim( esc_html(sanitize_text_field($mobile)), '0' ), - 10 );
	}

	public static function request_product_id( $array = false ) {

		//old version 
		//$product_ids = ! empty( $_REQUEST['product_id'] ) ? ['product_id'] : array();
		 // $product_ids = ! empty( sanitize_text_field($_REQUEST['product_id'])) ? array_map( 'absint', (array) sanitize_text_field($_REQUEST['product_id'] )) : array();
		 $product_ids = ! empty( absint(sanitize_text_field($_REQUEST['product_id']) )) ? array_map( 'absint', (array) sanitize_text_field($_REQUEST['product_id']) ) : array();
 
	    
		//new version
		//$product_ids = isset( $_REQUEST['product_id'] ) ? array_map( 'absint', (array) $_REQUEST['product_id'] ) : array();
		//$product_ids = ! empty( $_REQUEST['product_id'] ) ? array_map( 'absint', (array) $_REQUEST['product_id'] ) : array();

		//$product_ids = is_array( $product_ids ) ? array_map( 'absint', $product_ids ) : absint( $product_ids );
		//
		
		
		
		if ( ! is_array( $product_ids ) ) {
			$product_ids = explode( ',', (string) $product_ids );
		}
		$product_ids = array_map( 'absint', $product_ids );
		$product_ids = array_unique( array_filter( $product_ids ) );
		if ( $array ) {
			return $product_ids;
		}

		return implode( ',', $product_ids );
	}

	public function column_product_id( $item, $this_column = true ) {

		$product_id = absint( $item['product_id'] );

		$column_value = '';

		if ( $this_column ) {
			$column_value = '-';
			if ( $product_id ) {
				$title        = get_the_title( $product_id );
				$title        = ! empty( $title ) ? $product_id . ' :: ' . $title : $product_id;
				if (get_locale() == 'fa_IR')
					$column_value = '<a title="'.esc_html('مشاهده لیست مشترکین این محصول').'" href="' . add_query_arg( [ 'product_id' => absint(sanitize_text_field($product_id)) ] ) . '">' . esc_html($title) . '</a>';
				else
					$column_value = '<a title="'.esc_html('View the list of subscribers of this product').'" href="' . add_query_arg( [ 'product_id' => absint(sanitize_text_field($product_id)) ] ) . '">' . esc_html($title). '</a>';
			}
		}

		$query_args  = [ 'edit' => absint( $item['id'] ) ];
		$product_ids = self::request_product_id();
		if ( ! empty( $product_ids ) ) {
			$query_args['product_id'] = $product_ids;
		}

		$edit_url = add_query_arg( $query_args,
			admin_url( 'admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=contacts' ) );

		$delete_url = add_query_arg( [
			'action'   => 'delete',
			'item'     => absint( $item['id'] ),
			'_wpnonce' => wp_create_nonce( 'WooNotify_delete_contact' ),
		] );
		if (get_locale() == 'fa_IR'){
				$actions = [
					'edit'   => sprintf( '<a href="%s">%s</a>', $edit_url, esc_html('ویرایش مشترک') ),
					'delete' => sprintf( '<a href="%s">%s</a>', $delete_url, esc_html('حذف مشترک') ),
				];
		}
		else
		{
			$actions = [
				'edit' => sprintf( '<a href="%s">%s</a>', $edit_url, esc_html('Edit shared') ),
				'delete' => sprintf( '<a href="%s">%s</a>', $delete_url, esc_html('delete subscriber' )),
				];
		}
		if ( ! empty( $product_id ) ) {
			$actions['edit_product'] = sprintf( '<a target="_blank" href="%s">%s</a>', get_edit_post_link( absint(sanitize_text_field($product_id)) ), esc_html('مدیریت محصول') );
		}

		return sprintf( '%1$s %2$s', $column_value, $this->row_actions( $actions ) );
	}

	public function column_groups( $item ) {

		if ( empty( $item['groups'] ) || empty( $item['product_id'] ) ) {
			return '-';
		}

		$product_id  = absint( sanitize_text_field($item['product_id'] ));
		$groups      = explode( ',', $item['groups'] );
		$group_names = [];
		foreach ( $groups as $group_id ) {
			$name = WooNotify_360Messenger_Contacts::groupName( $group_id, $product_id, true );
			if ( empty( $name ) ) {
				$name = WooNotify_360Messenger_Contacts::groupName( $group_id, $product_id, false );
				if ( ! empty( $name ) ) {
					if (get_locale() == 'fa_IR')
							$name .= esc_html(' (غیرفعال)');
					else
							$name .= esc_html(' (disabled)');

				} else {
					if (get_locale() == 'fa_IR')
						$name = esc_html('گروه حذف شده');
					else
						$name = esc_html('deleted group');

				}
			}
			$group_names[] = $name;
		}

		return implode( ' | ', array_filter( $group_names ) );
	}

	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$this->process_bulk_action();

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = $this->record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
		] );
		$this->items = $this->get_items( $per_page, $current_page );
	}

	public function get_columns() {
		if (get_locale() == 'fa_IR'){
		$columns = [
			'cb'         => '<input type="checkbox" />',
			'product_id' => esc_html('محصول'),
			'mobile'     => esc_html('شماره واتساپ'),
			'groups'     => esc_html('گروه'),
		];
	}

	else
	{
		$columns = [
			'cb' => '<input type="checkbox" />',
			'product_id' => esc_html('Product'),
			'mobile' => esc_html('WhatsApp number'),
			'groups' => esc_html('group'),
			];
	}

		$product_ids = self::request_product_id( true );

		if ( count( $product_ids ) == 1 ) {
			unset( $columns['product_id'] );
		}

		return $columns;
	}

	public function get_sortable_columns() {
		return [
			'product_id' => [ 'product_id', false ],
			'mobile'     => [ 'mobile', false ],
			'groups'     => [ 'groups', false ],
		];
	}

	public function process_bulk_action() {

		$action = $this->current_action();

		if ( 'delete' === $action ) {

			if ( ! empty( $_REQUEST ) && ! wp_verify_nonce(esc_html(sanitize_text_field( ['_wpnonce'] ), 'WooNotify_delete_contact' ) )) {
				if (get_locale() == 'fa_IR')
					die( esc_html('خطایی رخ داده است. بعدا تلاش کنید.') );
				else
					die( esc_html('An error occurred. Try again later.') );
			}

			$this->delete_item( absint( $_REQUEST['item'] ) );

			echo '<div class="updated notice is-dismissible below-h2"><p>'.esc_html('آیتم حذف شد.').'</p></div>';
		} else if ( $action == 'bulk_delete' ) {

			if ( ! empty( $_REQUEST ) && ! wp_verify_nonce( (sanitize_text_field( $_REQUEST['_wpnonce'] )), 'bulk-' . $this->_args['plural'] ) ) {
				if (get_locale() == 'fa_IR')
					die(esc_html( 'خطایی رخ داده است. بعدا تلاش کنید.' ));
				else
					die( esc_html('An error occurred. Try again later.') );
			}

			$delete_ids = array_map( 'absint', sanitize_text_field($_REQUEST['item']) ?? [] );

			foreach ( (array) $delete_ids as $id ) {
				$this->delete_item( absint( $id ) );
			}

			echo '<div class="updated notice is-dismissible below-h2"><p>'.esc_html('آیتم ها حذف شدند.').'</p></div>';
		}
	}

	public function delete_item( $id ) {
		global $wpdb;

		$wpdb->delete( self::table(), [ 'id' => $id ] );
	}

	public static function table(): string {
		global $wpdb;

		return $wpdb->prefix . self::$table;
	}

	public function record_count() {

		global $wpdb;

		$query  = $this->get_query( true );
		$result = $wpdb->get_var( $query );

		return $result;
	}

	private function get_query( $count = false ) {
		global $wpdb;

		$table = self::table();

		$where = [];
		if ( isset( $_REQUEST['s'] ) ) {
		  $s       =  esc_html(sanitize_text_field( $_REQUEST['s'] ));
		  $s       =  self::prepareMobile( $s );
		  $s       = '%'.$s.'%';
		  $where[] =  $wpdb->prepare('(mobile LIKE %s)', $s);
		}

		if ( ! empty( $_REQUEST['product_id'] ) ) {
			$rpi = self::request_product_id();
			$where[] = $wpdb->prepare('(`product_id` IN %s)',$rpi);
		}

		$where = ! empty( $where ) ? '(' . implode( ' AND ', $where ) . ')' : '';
		$where = $wpdb->prepare("%s", $where);
		$order_by = ! empty(esc_html(sanitize_text_field( $_REQUEST['orderby'])) ) ? esc_html(sanitize_text_field( trim( $_REQUEST['orderby'] )) ) : '';
		$order = ! empty(esc_html(sanitize_text_field( $_REQUEST['order'])) ) ? esc_html(sanitize_text_field( trim( $_REQUEST['order'] )) ) : '';

		$select = $count ? 'count(*)' : '*';
		$select = $wpdb->prepare("%s", $select);

		if ( $order_by == 'groups' ) {

			$sql = $wpdb->prepare( "SELECT %s, SUBSTRING_INDEX(SUBSTRING_INDEX(t.groups, ',', n.n), ',', -1) groups
                    FROM %s t CROSS JOIN (SELECT a.N + b.N * 10 + 1 n FROM
                    (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a,
                    (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
                    ORDER BY n) n WHERE (n.n <= 1 + (LENGTH(t.groups) - LENGTH(REPLACE(t.groups, ',', ''))))", $select, $table );

			if ( ! empty( $where ) ) {
				$sql .= " AND {$where}";
				$sql = $wpdb->prepare("%s", $sql);
			}

		} else {
			$sql = $wpdb->prepare( "SELECT %s FROM %s", $select, $table );
			if ( ! empty( $where ) ) {
				$sql .= " WHERE {$where}";
				$sql = $wpdb->prepare("%s", $sql);
			}
		}

		if ( ! empty( $order_by ) ) {
			$sql .= esc_html(sanitize_text_field($_REQUEST['order'])) == 'DESC' ? ' DESC' : ' ASC';
			$sql = $wpdb->prepare("%s", $sql);
			$sql .= $wpdb->prepare( " ORDER BY %s %s", $order_by, $order );
			if ( $order_by != 'product_id' ) {
				$sql .= $wpdb->prepare( ", product_id %s", $order );
			}
		} else {
			$sql .= ' ORDER BY id DESC';
		}

		return $sql;
	}

	public function get_items( int $per_page = 20, int $page_number = 1 ) {
		global $wpdb;

		$sql = $this->get_query();
		//$sql     .= $wpdb->prepare("LIMIT %d" , $per_page);
		//$sql     .= $wpdb->prepare(" OFFSET ( %d - 1 ) * %d" , $page_number , $per_page);
		$results = $wpdb->get_results( $sql, 'ARRAY_A' );

		$this->set_users_mobile( $results );

		return $results;
	}

	public function set_users_mobile( $result ) {

		$mobiles = array_unique( wp_list_pluck( $result, 'mobile' ) );

		$meta_key  = WooNotify()->buyerMobileMeta();
		$user_meta = [ 'relation' => 'OR' ];
		foreach ( $mobiles as $mobile ) {
			$user_meta[] = [
				'key'     => esc_html(sanitize_text_field($meta_key)),
				'value'   => self::prepareMobile( $mobile ),
				'compare' => 'LIKE',
			];
		}

		$users = ( new WP_User_Query( [ 'meta_query' => $user_meta ] ) )->get_results();

		$_users = [];
		foreach ( $users as $user ) {
			if ( ! empty( $user->ID ) ) {
				$_mobile = get_user_meta( $user->ID, $meta_key, true );
				$_mobile = self::prepareMobile( $_mobile );
				foreach ( $mobiles as $mobile ) {
					$mobile = self::prepareMobile( $mobile );
					if ( stripos( $_mobile, $mobile ) !== false ) {
						$_mobile = $mobile;
						break;
					}
				}
				$_users[ $_mobile ] = $user;
			}
		}

		self::$users = $_users;

		return $_users;
	}

	public function get_bulk_actions() {
		if (get_locale() == 'fa_IR'){
				return [
					'bulk_delete' => esc_html('حذف'),
				];
		}

		else
		{
			return [
				'bulk_delete' => esc_html('delete'),
			];	
		}
	}
}

class WooNotify_360Messenger_Contacts {

	public function __construct() {
		add_action( 'WooNotify_settings_form_bottom_360Messenger_contacts', [ $this, 'contactsTable' ] );
		add_action( 'init', [ $this, 'createTable' ] );
		add_action( 'init', [ $this, 'moveOldContants__3_8' ] );
		add_action( 'wp_ajax_change_360Messenger_text', [ $this, 'change360MessengerTextCallback' ] );

        if(isset($_GET['test'])){

            self::getContactsMobiles(361 , '_in');

        }
	}

	public static function groupName( $group_id, $product_id, $cond = true ) {
		$groups = self::getGroups( absint(sanitize_text_field($product_id)), false, esc_html(sanitize_text_field($cond)) );

		return isset( $groups[ $group_id ] ) ? absint(sanitize_text_field($groups[ $group_id ])) : '';
	}

	public static function getGroups( $product_id, $check = true, $cond = true ) {

		$groups = [];
		if ( ! $check || ! WooNotify()->ProductHasProp( $product_id, 'is_on_sale' ) ) {
			if ( ! $cond || WooNotify()->hasNotifCond( 'enable_onsale', $product_id ) ) {
				$groups['_onsale'] = WooNotify()->getValue( 'notif_onsale_text', $product_id );
			}
		}

		if ( ! $check || ! WooNotify()->ProductHasProp( $product_id, 'is_in_stock' ) ) {
			if ( ! $cond || WooNotify()->hasNotifCond( 'enable_notif_no_stock', $product_id ) ) {
				$groups['_in'] = WooNotify()->getValue( 'notif_no_stock_text', $product_id );
			}
		}

		if ( ! $check || WooNotify()->ProductHasProp( $product_id, 'is_not_low_stock' ) ) {
			if ( ! $cond || WooNotify()->hasNotifCond( 'enable_notif_low_stock', $product_id ) ) {
				$groups['_low'] = WooNotify()->getValue( 'notif_low_stock_text', $product_id );
			}
		}

		foreach ( explode( PHP_EOL, (string) WooNotify()->getValue( 'notif_options', $product_id ) ) as $option ) {
			$options = explode( ":", $option, 2 );
			if ( count( $options ) == 2 ) {
				$groups["$options[0]"] = $options[1];
			}
		}

		return array_filter( $groups );
	}

	/*------------------------------------------------------------------------------*/

	public static function getContactByMobile( $product_id, $mobile ) {

		$table = WooNotify_360Messenger_Contacts_List_Table::table();

		$product_id = absint( $product_id );

		$mobile = WooNotify_360Messenger_Contacts_List_Table::prepareMobile( $mobile );

		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {%s} WHERE product_id=%d AND mobile LIKE '%s'", $table, $product_id, "%$mobile%" ), ARRAY_A );
	}



	public static function getContactsMobiles( int $product_id, string $group ) {

		global $wpdb;

			$table      = WooNotify_360Messenger_Contacts_List_Table::table();
			$query = "SELECT `mobile` FROM {%s} WHERE product_id=%d";
			$query .= ' AND ( `groups` LIKE %s )';

        $group_name = '%' . $wpdb->esc_like( $group ) . '%';
        $query = $wpdb->prepare( $query,  $table, $product_id, $group_name);

         $mobiles = $wpdb->get_col($query);

        $mobiles = array_unique( array_filter( $mobiles ) );


		return $mobiles;
  }

	public function createTable() {

		if ( get_option( 'WooNotify_table_contacts_created' ) ) {
			return;
		}

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		}

		global $wpdb;

		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}

		$table = WooNotify_360Messenger_Contacts_List_Table::table();
		dbDelta( "CREATE TABLE IF NOT EXISTS {$table} (
			`id` mediumint(8) unsigned NOT NULL auto_increment,
			`product_id` mediumint(8) unsigned,
			`mobile` VARCHAR(250),
			`groups` VARCHAR(250),
			PRIMARY KEY  (id)
		) $charset_collate;" );

		update_option( 'WooNotify_table_contacts_created', '1' );
	}

	/*-------------------------------------------------------------------------------*/

	public function moveOldContants__3_8() {


		if ( get_option( 'WooNotify_table_contacts_updated' ) ) {
			return;
		}

		global $wpdb;

		if ( ! $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", WooNotify_360Messenger_Contacts_List_Table::table() ) ) ) {
			return;
		}

		// transfare 50 member
		$sql = $wpdb->prepare("SELECT * FROM %s WHERE `meta_key`='_hannanstd_360Messenger_notification' LIMIT 50" , $wpdb->postmeta);

		$results = $wpdb->get_results( $sql, 'ARRAY_A' );
		if ( empty( $results ) ) {
			update_option( 'WooNotify_table_contacts_updated', '1' );
		}

		foreach ( $results as $result ) {

			$contacts = [];
			foreach ( explode( '***', (string) $result['meta_value'] ) as $contact ) {
				
				[ $contact ] = explode( '_vsh_', $contact, 1 );
				if ( strlen( $contact ) < 2 ) {
					continue;
				}
				[ $mobile, $groups ] = explode( '|', $contact, 2 );
				if ( WooNotify()->validateMobile( $mobile ) ) {
					$groups              = explode( ',', $groups );
					$_groups             = ! empty( $contacts[ $mobile ] ) ? $contacts[ $mobile ] : [];
					$contacts[ $mobile ] = array_unique( array_merge( $groups, $_groups ) );
				}
			}

			$insert     = true;
			$meta_id    = $result['meta_id'];
			$product_id = $result['post_id'];

			foreach ( $contacts as $mobile => $groups ) {
				$insert = self::insertContact( [
						'product_id' => $product_id,
						'mobile'     => $mobile,
						'groups'     => $groups,
					] ) && $insert;
			}

			if ( $insert ) {
				$wpdb->update( $wpdb->postmeta, [
					'meta_key' => '_WooNotify_newsletter_contacts__moved',
				], [
					'meta_id' => absint( $meta_id ),
				] );
			}
		}
	}

	public static function insertContact( $data ) {

		if ( empty( $data['product_id'] ) || empty( $data['mobile'] ) || empty( $data['groups'] ) ) {
			return false;
		}

		global $wpdb;

		return $wpdb->insert( WooNotify_360Messenger_Contacts_List_Table::table(), [
			'product_id' => absint( $data['product_id'] ),
			'mobile'     => WooNotify()->modifyMobile( $data['mobile'] ),
			'groups'     => self::prepareGroups( $data['groups'] ),
		], [ '%d', '%s', '%s' ] );
	}

	private static function prepareGroups( $groups ) {

		if ( ! is_array( $groups ) ) {
			$groups = explode( ',', (string) $groups );
		}

		$groups = array_map( 'sanitize_text_field', $groups );
		$groups = array_map( 'trim', $groups );
		$groups = array_unique( array_filter( $groups ) );
		$groups = implode( ',', $groups );

		return $groups;
	}

	public function contactsTable() {

		$updated = get_option( 'WooNotify_table_contacts_updated' );
		if ( ! $updated ) { ?>
            <div class="notice notice-info below-h2">
                <p>
					<?php
					if (get_locale() == 'fa_IR') {
					echo '
                    <strong>
                        '.esc_html('در حال انتقال دیتابیس مشترکین اطلاه رسانی سایت شما از جدول post_meta به یک جدول مستقل هستیم.
                        این عمل با توجه به حجم مشترکین شما ممکن است کمی زمانبر باشد.
                        لطفا لحظات دیگری پس از انتقال کامل مشترکین مراجعه نمایید.').'
                    </strong>';
					}
					else
					{
						echo '
                     <strong>
                         '.esc_html('We are moving the database of your sites notification subscribers from the post_meta table to an independent table.
                         This process may take some time depending on the volume of your subscribers.
                         Please come back a few moments after the complete transfer of subscribers.').'
                     </strong>';
					}
					?>
                </p>
            </div>
			<?php return;
		} elseif ( $updated == '1' ) { ?>
            <div class="notice notice-success is-dismissible below-h2">
                <p>
                    <?php
					if (get_locale() == 'fa_IR') {
						echo '<strong>
						'.esc_html('انتقال دیتابیس مشترکین اطلاع رسانی سایت شما از جدول post_meta به یک جدول مستقل با موفقیت انجام شد.').'
					</strong>';
					}

					else
					{
						echo '<strong>
						'.esc_html('The transfer of the database of your sites notification subscribers from the post_meta table to an independent table has been done successfully.').'
						</strong>';
					}
					?>
                </p>
            </div>
			<?php update_option( 'WooNotify_table_contacts_updated', '2' );
		}

		/*----------------------------------------------------------------------------*/
		if ( isset( $_GET['edit'] ) ) {
			$this->editContact( absint( sanitize_text_field($_GET['edit'] )) );
		} elseif ( isset( $_GET['add'] ) ) {
			$this->addContact( absint( $_GET['add'] ) );
		} else {

			$list = new WooNotify_360Messenger_Contacts_List_Table();
			$list->prepare_items();

			echo '<style type="text/css">';
			echo '.wp-list-table .column-id { width: 5%; }';
			echo '</style>';


			$product_id = WooNotify_360Messenger_Contacts_List_Table::request_product_id( true );
			$product_id = count( $product_id ) == 1 ? reset( $product_id ) : 0;

			if ( ! empty( $product_id ) && $title = get_the_title( $product_id ) ) {
				if (get_locale() == 'fa_IR')
					echo sprintf( '<h1>'.esc_html('مشترکین محصول').' '.esc_attr("%s").'</h1>', esc_html( $title ) ) . '<br><br>';
				else
				echo sprintf( '<h1>'.esc_html('Subscribers of product').' '.esc_attr("%s").'</h1>', esc_html( $title ) ) . '<br><br>';

			}

			$query_args = [ 'add' => $product_id ];
			if ( ! empty( $product_id ) ) {
				$query_args['product_id'] = $product_id;
			}

			$add_url = add_query_arg( $query_args, esc_url(admin_url( 'admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=contacts' ) )); ?>
            <a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>"><?php 
			if (get_locale() == 'fa_IR')
				echo esc_html('افزودن مشترک جدید');
				else
					echo esc_html('Add new subscriber');
			?></a>

			<?php if ( ! empty( $_GET['product_id'] ) || isset( $_GET['add'] ) || isset( $_GET['edit'] ) ) : ?>
                <a class="page-title-action"
                   href="<?php echo esc_html(remove_query_arg( [ 'product_id', 'add', 'edit' ] )); ?>">
                    back
                </a>
			<?php endif; ?>

            <form method="post">
                <input type="hidden" name="page" value="WooNotify_360Messenger_Contacts_list_table">
				<?php
				if (get_locale() == 'fa_IR')
					$list->search_box( esc_html('جستجوی شماره واتساپ'), 'search_id' );
				else
					$list->search_box( esc_html('search WhatsApp number'), 'search_id' );

				$list->display();
				?>
            </form>
		<?php } ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.delete a, a.delete, .button.action').on('click', function (e) {
                    var action1 = $('select[name="action"]').val();
                    var action2 = $('select[name="action2"]').val();
                    if ($(this).is('a') || action1 === 'bulk_delete' || action2 === 'bulk_delete') {
                        if (!confirm('Are you sure to delete?')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                });
            });
        </script>
		<?php
	}

	private function editContact( $contact_id = 0 ) {

		$operation  = empty( $contact_id ) ? 'add' : 'edit';
		$return_url = remove_query_arg( [ 'add', 'edit', 'added' ] );

		$data = $operation == 'edit' ? self::getContactById( $contact_id ) : [];

		if ( $operation == 'edit' ) {
			$product_id = ! empty( $data['product_id'] ) ? absint( $data['product_id'] ) : 0;
		} else {
			$product_id = absint( sanitize_text_field($_GET['add'] ?? 0 ));
		}

		if ( ! empty( $_POST['_wpnonce'] ) ) {
			$wponce=esc_html(sanitize_text_field($_POST['_wpnonce']));
			if ( ! wp_verify_nonce( $wponce, 'WooNotify_contact_nonce' ) ) {
				if (get_locale() == 'fa_IR')
					wp_die( esc_html('خطایی رخ داده است.') );
				else
					wp_die( esc_html('An error occurred.') );

			}

			$mobile = esc_html(sanitize_text_field( $_POST['mobile'] ?? null ));

			if ( empty( $mobile ) ) {
				if (get_locale() == 'fa_IR')
					$error = esc_html('شماره واتساپ الزامی است.');
				else
					$error = 'WhatsApp number is required.';
			} elseif ( ! WooNotify()->validateMobile( $mobile ) ) {
				if (get_locale() == 'fa_IR')
					$error = esc_html('شماره واتساپ وارد شده معتبر نیست.');
				else
				$error = esc_html('The WhatsApp number entered is not valid.');
			}

			//old code
			//$groups = self::prepareGroups( $_POST['groups'] );
			//new cod
			$groups = isset( $_POST['groups'] ) ? esc_html( sanitize_text_field(wp_unslash( $_POST['groups'] ))) : '';
			$groups = self::prepareGroups( $groups );
			//


			if ( empty( $groups ) ) {
				if (get_locale() == 'fa_IR')
					$error = esc_html('انتخاب حداقل یک گروه الزامی است.');
				else
					$error = esc_html('Selecting at least one group is required.');
			}

			if ( empty( $error ) ) {

				$params = [
					'product_id' => $product_id,
					'mobile'     => $mobile,
					'groups'     => $groups,
				];

				if ( $operation == 'edit' ) {
					$save = self::updateContact( array_merge( [ 'id' => $contact_id ], $params ) );
				} else {
					$save = self::insertContact( $params );
				}

				if ( $save !== false ) {
					if ( $operation == 'edit' ) {
						$saved = true;
					} else {
						global $wpdb;
						$contact_id = $wpdb->insert_id;
						wp_redirect( add_query_arg( [ 'edit' => $contact_id, 'added' => 'true' ], $return_url ) );
						exit();
					}
				} else {
					if (get_locale() == 'fa_IR')
						$error = esc_html('در حین ذخیره خطایی رخ داده است. مجددا تلاش کنید.');
					else
						$error = esc_html('An error occurred while saving. Try again.');
				}
			}

			if ( ! empty( $error ) ) { ?>
                <div class="notice notice-error below-h2">
                    <p><strong>خطا: </strong><?php echo esc_html ( $error ); ?></p>
                </div>
				<?php
			}
		} else {
			$mobile = ! empty( $data['mobile'] ) ? WooNotify()->modifyMobile( $data['mobile'] ) : '';
			$groups = ! empty( $data['groups'] ) ? $data['groups'] : '';
		}

		$contact_groups = array_map( 'strval', explode( ',', $groups ) );
		$contact_groups = array_map( 'trim', $contact_groups );

		if ( ! empty( $saved ) || ! empty( $_GET['added'] ) ) { ?>
            <div class="notice notice-success below-h2">
				<?php
				if (get_locale() == 'fa_IR') {
				 '
				<p><strong>'.esc_html('مشترک ذخیره شد.').'</strong>
                    <a href="<?php echo esc_url( $return_url ); ?>">'.esc_html('بازگشت به لیست مشترکین').'</a>
                </p>
				';
				}
				else
				{
					echo '
			<p><strong>'.esc_html('Subscribe saved.').'</strong>
								<a href="<?php echo esc_url( $return_url ); ?>">'.esc_html('return to subscriber list').'</a>
							</p>
';
					
				}
				?>
                
            </div>
			<?php
		}
		if (get_locale() == 'fa_IR')
			$title = $operation == 'edit' ? 'ویرایش مشترک اطلاع رسانی محصول "%s"' : 'افزودن مشترک جدید برای اطلاع رسانی محصول "%s"'; 
		else
		$title = $operation == 'edit' ? 'Edit product notification subscriber "%s"' : 'Add new subscriber for product notification "%s"';


		
		?>
        <h3><?php printf( esc_html( $title ), esc_html( get_the_title( $product_id ) ) ); ?></h3>


        <form action="<?php echo esc_html( remove_query_arg( [ 'added' ] )); ?>" method="post">
            <table class="form-table">
                <tbody>
                <tr>
					<?php
					if (get_locale() == 'fa_IR'){
					echo '<th><label for="mobile">'.esc_html('شماره واتساپ').'</label></th>';
					}
					else
					{
						echo '<th><label for="mobile">'.esc_html('WhatsApp number').'</label></th>';
					}
					?>
                    
                    <td><input type="text" id="mobile" name="mobile" value="<?php echo esc_html (sanitize_text_field( $mobile )); ?>"
                               style="text-align: left; direction: ltr"></td>
                </tr>
                <tr>

				<?php
					if (get_locale() == 'fa_IR'){
					echo ' <th><label for="mobile">'.esc_html('گروه ها').'</label></th>';
					}
					else
					{
						echo '<th><label for="mobile">'.esc_html('Groups').'</label></th>';
					}
					?>

                   
                    <td>
						<?php
						$all_groups    = (array) WooNotify_360Messenger_Contacts::getGroups( $product_id, false, false );
						$active_groups = (array) WooNotify_360Messenger_Contacts::getGroups( $product_id, false, true );

						foreach ( $all_groups as $group => $label ) {
							$group = strval( $group ); ?>
                            <label for="groups_<?php echo esc_html(sanitize_text_field( $group )); ?>">
                                <input type="checkbox" name="groups[]" id="groups_<?php esc_html(sanitize_text_field( $group )); ?>"
                                       value="<?php echo esc_html(sanitize_text_field( $group )); ?>" <?php checked( in_array( $group, $contact_groups ) ) ?>>
								<?php
								echo esc_html(sanitize_text_field( $label ));
								if ( ! in_array( $group, array_keys( $active_groups ) ) ) {
									if (get_locale() == 'fa_IR')
										echo esc_html(' (غیرفعال)');
									else
									echo esc_html('(disabled)');
								}
								?>
                            </label><br>
							<?php
						}
						?>
                    </td>
                </tr>
                </tbody>
            </table>

			<?php
			wp_nonce_field( 'WooNotify_contact_nonce', '_wpnonce' );
			if (get_locale() == 'fa_IR')
				$title = $operation == 'edit' ? esc_html('بروز رسانی مشترک') : esc_html('افزودن مشترک');
			else
				$title = $operation == 'edit' ? esc_html('update subscriber' ): esc_html('add subscriber');
			?>

            <p class="submit">
                <input name="submit" class="button button-primary" value="<?php echo esc_html(sanitize_text_field( $title )); ?>" type="submit">
                <a href="<?php echo esc_url( $return_url ); ?>" class="button button-secondary">بازگشت</a>

				<?php if ( ! empty( $contact_id ) ) :

					$delete_url = add_query_arg( [
						'action'   => 'delete',
						'item'     => absint( $contact_id ),
						'_wpnonce' => wp_create_nonce( 'WooNotify_delete_contact' ),
					], $return_url ); ?>

                    <a class="delete" href="<?php echo esc_url( $delete_url ); ?>"
                       style="text-decoration: none; color: red">
					<?php
					if (get_locale() == 'fa_IR'){
					echo esc_html('حذف 
					این مشترک');
					}
					else
					{
						echo esc_html('delete
						This joint');
					}
					?>
					
					</a>
				<?php endif; ?>
            </p>

        </form>
		<?php
	}

	/*-----------------------------------------------------------------------------------*/

	public static function getContactById( $contact_id ) {
		global $wpdb;
		$table = WooNotify_360Messenger_Contacts_List_Table::table();

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {%s} WHERE id=%d", $table, absint( $contact_id ) ), ARRAY_A );
	}

	public static function updateContact( array $data ) {
		global $wpdb;

		if ( empty( $data['id'] ) || empty( $data['mobile'] ) || empty( $data['groups'] ) ) {
			return false;
		}

		return $wpdb->update( WooNotify_360Messenger_Contacts_List_Table::table(), [
			'mobile' => WooNotify()->modifyMobile( $data['mobile'] ),
			'groups' => self::prepareGroups( $data['groups'] ),
		], [ 'id' => absint( $data['id'] ) ], [ '%s', '%s' ], [ '%d' ] );
	}

	private function addContact( $product_id = 0 ) {

		if ( ! empty( $product_id ) ) {
			$this->editContact();

			return;
		}
		?>

        <table class="form-table">
            <tbody>
            <tr>
                <th>
					<?php
					if (get_locale() == 'fa_IR'){
					echo '
                    <label for="select_product_id">'.esc_html('یک محصول WooNotify').'</label>';
					}
					else
					{
						echo '
                     <label for="select_product_id">'.esc_html('a WooNotify product').'</label>';
					}
					?>
                </th>
                <td>
                    <select id="select_product_id" class="wc-product-search">
                        <option value=""><?php 
						
						if (get_locale() == 'fa_IR')
							echo esc_html('یک محصول انتخاب کنید');
						else
							echo esc_html('Select a product');
						
							?>
							
						</option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('select#select_product_id').on('change', function () {
                    document.location = '<?php echo esc_html(remove_query_arg( [ 'add' ] ));?>' + "&add=" + esc_js($(this).val());
                });
            });
        </script>
		<?php
	}
}

new WooNotify_360Messenger_Contacts();