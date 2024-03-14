<?php
/**
 * Administration API: WP_List_Table class
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 */

/**
 * Base class for displaying a list of items in an ajaxified HTML table.
 *
 * @since 3.1.0
 */
class SEO_Backlink_Monitor_Parent_WP_List_Table extends WP_List_Table {

	/**
	 * @see WP_List_Table::ajax_user_can()
	 */
	public function ajax_user_can()
	{
		return current_user_can( 'manage_options' );
	}

	/**
	 * @see WP_List_Table::no_items()
	 *
	 * mod: set custom no_itmes text
	 */
	public function no_items() {
		_e( 'No links found.', 'seo-backlink-monitor' );
	}

	/**
	 * @see WP_List_Table::search_box()
	 *
	 * mod: add search_column filter select
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = 'seo-blm-' . $input_id . '-search-input';
		$search_column_val = '';

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['order'] ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['detached'] ) ) {
			echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
		}
		?>
<p class="search-box">
	<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
	<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" class="regular-text" placeholder="<?php echo __( 'Search Links....', 'seo-backlink-monitor' ); ?>" />
	<select name="search_column" id="seo-blm-search-column" >
		<!-- <option value="" selected> <?php echo __( '-- Select Column --', 'seo-backlink-monitor' ); ?></option> -->
		<option value="linkTo" <?php selected( $search_column_val ); ?>><?php echo __( 'Link To', 'seo-backlink-monitor' ); ?></option>
		<option value="linkFrom" <?php selected( $search_column_val ); ?>><?php echo __( 'Link From', 'seo-backlink-monitor' ); ?></option>
		<option value="anchorText" <?php selected( $search_column_val ); ?>><?php echo __( 'Anchor Text', 'seo-backlink-monitor' ); ?></option>
		<option value="notes" <?php selected( $search_column_val ); ?>><?php echo __( 'Notes', 'seo-backlink-monitor' ); ?></option>
		<option value="follow" <?php selected( $search_column_val ); ?>><?php echo __( 'Follow', 'seo-backlink-monitor' ); ?></option>
		<option value="status" <?php selected( $search_column_val ); ?>><?php echo __( 'Status', 'seo-backlink-monitor' ); ?></option>
	</select>
	<?php
		submit_button( __( 'Search Links', 'seo-backlink-monitor' ), 'primary', 'seo-blm-links-search-btn', false );
		submit_button( __( 'Reset Search', 'seo-backlink-monitor' ), 'secondary', 'seo-blm-links-reset-btn', false );
	?>
</p>
		<?php
	}

	/**
	 * @see WP_List_Table::current_action()
	 *
	 * mod: add action2
	 */
	public function current_action() {
		if ( isset( $_REQUEST['filter_action'] ) && ! empty( $_REQUEST['filter_action'] ) ) {
			return false;
		}

		if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
			return $_REQUEST['action'];
		}

		// ADD action2
		if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
			return $_REQUEST['action2'];
		}

		return false;
	}

	/**
	 * @see WP_List_Table::pagination()
	 *
	 * mod: add seo-blm-tbl-nav-pages class
	 */
	protected function pagination( $which ) {
		if ( empty( $this->_pagination_args ) ) {
			return;
		}

		ob_start();
		parent::pagination( $which );
		$pagination = ob_get_contents();
		ob_end_clean();

		// SET custom class
		$this->_pagination = str_replace("tablenav-pages ", "tablenav-pages seo-blm-tbl-nav-pages ", $pagination);

		echo $this->_pagination;
	}

	/**
	 * @see WP_List_Table::print_column_headers()
	 *
	 * mod: add class seo-blm-sort-column, remove manage-column
	 */
	public function print_column_headers( $with_id = true ) {
		ob_start();
		// WP_List_Table::print_column_headers( $with_id );
		parent::print_column_headers( $with_id );
		$column_headers = ob_get_contents();
		ob_end_clean();

		echo str_replace('manage-column ', 'seo-blm-sort-column ', $column_headers);
	}

	/**
	 * @see WP_List_Table::display_tablenav()
	 *
	 * mod: don't output 'bottom' tablenav
	 * mod: output search_box within bulkactions
	 */
	protected function display_tablenav( $which ) {
		if ( 'bottom' === $which ) {
			// Don't output bottom tablenav
			return;
		}
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		?>
	<div class="tablenav <?php echo esc_attr( $which ); ?>">

		<?php if ( $this->has_items() ) : ?>
		<div class="alignleft actions bulkactions">
			<?php
				// Add searchbox
				$this->search_box( __('Search'), 'links-list');
				$s_term = isset( $_GET ) ? (array) $_GET : array();
				$s_term = array_map( 'esc_attr', $s_term );
				foreach ($s_term as $key => $value) {
					if ('s' === $key) {
						echo "<input type='hidden' name='search_field' value='$value' />";
					}
					if ('search_column' === $key) {
						echo "<input type='hidden' name='$key' value='$value' />";
					}
				}
			?>
			<?php $this->bulk_actions( $which ); ?>
		</div>
			<?php
		endif;
		$this->extra_tablenav( $which );
		$this->pagination( $which );
		?>

		<br class="clear" />
	</div>
		<?php
	}

	/**
	 * @see WP_List_Table::single_row()
	 *
	 * mod: ADD id attribute
	 */
	public function single_row( $item ) {
		// ADD [id]
		echo '<tr id="tr-'.$item['id'].'">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}
}
