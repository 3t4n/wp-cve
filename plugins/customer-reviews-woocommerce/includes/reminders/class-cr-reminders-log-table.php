<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'CR_Reminders_Log_Table' ) ) :

/**
 * Reminders List Table
 *
 * @since 3.5
 */
class CR_Reminders_Log_Table extends WP_List_Table {

	/**
	 * Constructor.
	 *
	 * @since 3.5
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( array(
			'plural'   => 'reminders',
			'singular' => 'reminder',
			'ajax'     => false,
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		) );
	}

	/**
	 * Fetch a list of reminders
	 *
	 * @since 3.5
	 */
	public function prepare_items() {
		global $search;

		$search = ( isset( $_REQUEST['s'] ) ) ? trim( $_REQUEST['s'] ) : '';

		$registered_customers = false;
		if ( 'yes' === get_option( 'ivole_registered_customers', 'no' ) ) {
			$registered_customers = true;
		}

		$orderby = ( isset( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'sent';
		$order = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'desc';

		$per_page = $this->get_per_page();
		$page = $this->get_pagenum();
		$start = ( $page - 1 ) * $per_page;

		$log = new CR_Reminders_Log();
		$reminders = $log->get( $start, $per_page, $orderby, $order, $search );

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable, 'order' );
		$this->items = $reminders['records'];

		$this->set_pagination_args( array(
			'total_items' => $reminders['total'],
			'per_page' => $per_page,
		) );
	}

	public function get_per_page() {
		return $this->get_items_per_page( 'reminders_per_page', 20 );
	}

	/**
	 * Prints the content displayed if there are no reminders.
	 *
	 * @since 3.5
	 */
	public function no_items() {
		if( 'cr' === get_option( 'ivole_scheduler_type' ) ) {
			/* translators: please keep '%1$s' and '%2$s' as is   */
			echo sprintf( __( 'The plugin is configured to use CR Cron for sending review reminders (%1$s\'Reminders Scheduler\' setting%2$s).', 'customer-reviews-woocommerce' ), '<a href="' . admin_url( 'admin.php?page=cr-reviews-settings' ) . '" title="Plugin Settings">', '</a>' );
			echo ' ';
			/* translators: please keep '%1$s' and '%2$s' as is   */
			echo sprintf( __( 'Please log in to your account on %1$sCusRev website%2$s to view and manage the reminders.', 'customer-reviews-woocommerce' ), '<a href="https://www.cusrev.com/login.html" target="_blank" rel="noopener noreferrer">', '</a>' );
		} else {
			_e( 'There are currently no sent review reminders', 'customer-reviews-woocommerce' );
		}
	}

	protected function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'customer-reviews-woocommerce' )
		);

		return $actions;
	}

	public function get_columns() {
		return array(
			'cb'		=> '<input type="checkbox" />',
			'order'		=> __( 'Order', 'customer-reviews-woocommerce' ),
			'customer'	=> __( 'Customer', 'customer-reviews-woocommerce' ),
			'type'	=> __( 'Type', 'customer-reviews-woocommerce' ),
			'verification' => __( 'Verification', 'customer-reviews-woocommerce' ),
			'sent'	=> __( 'Sent', 'customer-reviews-woocommerce' ),
			'status'	=> __( 'Status', 'customer-reviews-woocommerce' ),
			'language'	=> __( 'Language', 'customer-reviews-woocommerce' )
		);
	}

	/**
	 * Returns the columns which are sortable
	 *
	 * @since 3.5
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'order'		=> array( 'order', false ),
			'customer'	=> array( 'customer', false ),
			'sent'	=> array( 'sent', false )
		);
	}

	protected function get_default_primary_column_name() {
		return 'order';
	}

	public function display() {
		wp_nonce_field( "fetch-list-" . get_class( $this ), '_ajax_fetch_list_nonce' );

		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );

		?>
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<thead>
				<tr>
					<?php $this->print_column_headers(); ?>
				</tr>
			</thead>

			<tbody id="the-reminder-list" data-wp-lists="list:reminder">
				<?php $this->display_rows_or_placeholder(); ?>
			</tbody>

			<tfoot>
				<tr>
					<?php $this->print_column_headers( false ); ?>
				</tr>
			</tfoot>

		</table>
		<?php
	}

	public function single_row( $reminder ) {
		echo '<tr id="reminder-' . $reminder['id'] . '" class="reminder-row">';
		$this->single_row_columns( $reminder );
		echo "</tr>\n";
	}

	public function column_cb( $reminder ) {
		?>
			<label class="screen-reader-text" for="cb-select-<?php echo $reminder['id']; ?>"><?php _e( 'Select reminder', 'customer-reviews-woocommerce' ); ?></label>
			<input class="reminder-checkbox" id="cb-select-<?php echo $reminder['id']; ?>" type="checkbox" name="reminders[]" value="<?php echo $reminder['id']; ?>" />
		<?php
	}

	public function column_order( $reminder ) {
		?>
		<a href="<?php echo esc_url( get_edit_post_link( $reminder['orderId'] ) ); ?>"><?php echo $reminder['orderId']; ?></a>
		<?php
	}

	public function column_customer( $reminder ) {
		?>
		<strong><?php echo $reminder['customerName']; ?></strong>
		<br>
		<a href="<?php echo 'mailto:' . $reminder['customerEmail']; ?>"><?php echo $reminder['customerEmail']; ?></a>
		<?php
	}

	public function column_type( $reminder ) {
		$type = '';
		switch ($reminder['type']) {
			case 'm':
				$type = __( 'Manual', 'customer-reviews-woocommerce' );
				break;
			case 'a':
				$type = __( 'Automatic', 'customer-reviews-woocommerce' );
				break;
			default:
				break;
		}
		echo esc_html( $type );
	}

	public function column_sent( $reminder ) {
		$local_timestamp = get_date_from_gmt( $reminder['dateSent'], 'Y-M-d H:i:s (T)' );
		echo esc_html( $local_timestamp );
	}

	public function column_status( $reminder ) {
		$status = '';
		switch ($reminder['status']) {
			case 'sent':
				$status = __( 'Sent', 'customer-reviews-woocommerce' );
				break;
			case 'error':
				$status = __( 'Error', 'customer-reviews-woocommerce' );
				break;
			default:
				break;
		}
		echo esc_html( $status );
	}

	public function column_verification( $reminder ) {
		$verification = 'No';
		switch ($reminder['verification']) {
			case 'verified':
				$verification = __( 'Yes', 'customer-reviews-woocommerce' );
				break;
			default:
				break;
		}
		echo esc_html( $verification );
	}

	public function column_language( $reminder ) {
		echo esc_html( $reminder['language'] );
	}
}


endif;
