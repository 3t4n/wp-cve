<?php
/**
 * @author  FlyoutApps
 * @since   1.0
 * @version 1.0
 */

namespace flyoutapps\wfobpp;

abstract class Filter_By {

	public $id;

	public function __construct() {
		if ( Helper::is_HPOS_active()) {
			add_action( 'woocommerce_order_list_table_restrict_manage_orders', array( $this, 'dropdown' ), 50 );
			// add_filter( 'woocommerce_orders_table_query_sql', array( $this, 'debug_query' ) );
		} else {
			add_action( 'restrict_manage_posts', array( $this, 'dropdown' ), 50 );
			// add_filter( 'posts_request', array( $this, 'debug_query' ) );
		}
	}

	abstract public function dropdown_fields();

	public function debug_query( $query ){
		error_log( $query );
		return $query;
	}

	public function dropdown() {
		$screen = get_current_screen();
		if( !in_array( $screen->id, array( 'edit-shop_order', 'woocommerce_page_wc-orders' ) ) ) return;

		$fields = $this->dropdown_fields();
		?>
		<select class="wfobpp-select2" name="<?php echo esc_attr( $this->id );?>" id="<?php echo esc_attr( $this->id );?>">
			<?php
			$current_v = isset( $_GET[$this->id] ) ? $_GET[$this->id]: '';
			foreach ( $fields as $key => $title ) {
				printf
				(
					'<option value="%s"%s>%s</option>',
					$key,
					$key == $current_v? ' selected="selected"':'',
					$title
				);
			}
			?>
		</select>
		<?php
	}

	// Returns list of product id
	protected function query_by_product(){
		global $wpdb;
		$t_posts = $wpdb->posts;
		$t_order_items = $wpdb->prefix . "woocommerce_order_items";
		$t_order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";

		// Build join query, select meta_value
		$query  = "SELECT $t_order_itemmeta.meta_value FROM";
		$query .= " $t_order_items LEFT JOIN $t_order_itemmeta";
		$query .= " on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id";

		// Resultant table after join query
		/*------------------------------------------------------------------
		order_id | order_item_id* | order_item_type | meta_key | meta_value
		-------------------------------------------------------------------*/

		// Build where clause, where order_id = $t_posts.ID
		$query .= " WHERE $t_order_items.order_item_type='line_item'";
		$query .= " AND $t_order_itemmeta.meta_key='_product_id'";
		$query .= " AND $t_posts.ID=$t_order_items.order_id";

		// Visulize result
		/*-------------------------------------------------------------------
		order_id    | order_item_type | meta_key    | meta_value
		$t_posts.ID | line_item       | _product_id | <result>
		---------------------------------------------------------------------*/

		return $query;
	}
}