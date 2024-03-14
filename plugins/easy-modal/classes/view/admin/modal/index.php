<?php class EModal_View_Admin_Modal_Index extends EModal_View {

	public function output() {
		extract( $this->values ); ?>
        <div class="wrap">
        <h2><?php
			esc_html_e( $title );
			if ( ! empty( $modal_new_url ) ) {
				echo ' <a href="' . esc_url( $modal_new_url ) . '" class="add-new-h2">' . __( 'Add New', 'easy-modal' ) . '</a>';
			}
			if ( ! count_all_modals() ) {
				echo ' <div class="add-new-get-started">Add a new modal to get started!</div>';
			}
			?></h2>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
					<?php if ( count_all_modals() || count_deleted_modals() ) {
						?>
                        <ul class="subsubsub">
                            <li class="all">
                                <a <?php echo empty( $_GET['status'] ) || $_GET['status'] == 'all' ? 'class="current" ' : ''; ?>href="<?php echo esc_url( emodal_admin_url() . '&status=all' ); ?>">
                                    All <span class="count">(<?php esc_html_e( count_all_modals() ); ?>)</span>
                                </a>
                            </li>
							<?php if ( $trash = count_deleted_modals() ) { ?>
                                <li class="trash">|
                                    <a <?php echo ! empty( $_GET['status'] ) && $_GET['status'] == 'trash' ? 'class="current" ' : ''; ?>href="<?php echo esc_url( emodal_admin_url() . '&status=trash' ); ?>">
                                        Trash <span class="count">(<?php esc_html_e( $trash ); ?>)</span>
                                    </a>
                                </li>
							<?php } ?>
                        </ul>
                        <form id="modals-filter" method="get" action="">
						<?php wp_nonce_field( EMCORE_NONCE, EMCORE_NONCE, true, true ); ?>
                        <input type="hidden" name="page" value="<?php echo EMCORE_SLUG; ?>" />
						<?php
						$table = new EModal_Modal_List_Table();
						$table->prepare_items();
						$table->display();
						?>
                        </form><?php
					} else {
						$url  = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=popup-maker' ), 'install-plugin_popup-maker' ) );
						?>
                        <div class="get-started-modal-welcome">
                            <h1>Welcome to <span class="easy">Easy</span><span class="modal">Modal</span></h1>
                            <p>Thank you for installing EasyModal! We hope you enjoy our plug-in! We strive for perfection, but we can’t do it without you! Give us your feedback (<a href="mailto:feedback@easymodal.com">feedback@easymodal.com</a>) and leave us a review at:<a target="_blank" href="http://www.wordpress.org/plugins/easymodal">http://www.wordpress.org/plugins/easymodal</a></p>
                            <p>That said, did you know, that Easy Modal has a fancy new replacement called <strong><a href="https://wordpress.org/plugins/popup-maker/" target="_blank">Popup Maker</a></strong>? It is the highest user rated popup & modal plugin available for WordPress.</p>
                            <ul class="ul-square">
                                <li>Unlimited themes</li>
                                <li>Precision Targeting, Triggers & Cookies</li>
                                <li>Customize everything</li>
                                <li>Full line of extensions</li>
                                <li>Extensive Documentation & Developer APIs</li>
                                <li><a href="https://wordpress.org/plugins/popup-maker/" target="_blank">Learn more</a> or <a href="<?php echo esc_url( $url ); ?>">Install it now!</a></li>
                            </ul>
                        </div><?php
					} ?>


                </div>
                <div id="postbox-container-1" class="postbox-container">
					<?php do_action( 'emodal_admin_sidebar' ); ?>
                </div>
            </div>
            <br class="clear" />
        </div>
        </div><?php
	}
}

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class EModal_Modal_List_Table extends WP_List_Table {

	function __construct() {
		parent::__construct( array(
			'singular' => 'modal', //Singular label
			'plural'   => 'modals', //plural label, also this well be one of the table css class
			'ajax'     => false //We won't support Ajax for this table
		) );
	}

	function prepare_items() {
		global $wpdb, $_wp_column_headers;

		$table_name = $wpdb->prefix . 'em_modals';
		$screen     = get_current_screen();

		/* -- Preparing your query -- */
		$status = ! empty( $_GET['status'] ) ? $_GET['status'] : '';
		switch ( $status ) {
			case "trash":
				$where = "WHERE `is_trash` = 1";
				break;
			default:
				$where = "WHERE `is_trash` != 1";
				break;
		}

		$query = "SELECT * FROM $table_name $where";
		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = ! empty( $_GET["orderby"] ) ? mysql_real_escape_string( $_GET["orderby"] ) : 'ASC';
		$order   = ! empty( $_GET["order"] ) ? mysql_real_escape_string( $_GET["order"] ) : '';
		if ( ! empty( $orderby ) & ! empty( $order ) ) {
			$query .= ' ORDER BY ' . $orderby . ' ' . $order;
		}

		/* -- Pagination parameters -- */
		//Number of elements in your table?
		$totalitems = $wpdb->query( $query ); //return the total number of affected rows
		//How many to display per page?
		$perpage = ! empty( $_GET["per_page"] ) && intval( $_GET["per_page"] ) > 0 ? intval( $_GET["per_page"] ) : 10;
		//Which page is this?
		$paged = ! empty( $_GET["paged"] ) && intval( $_GET["paged"] ) > 0 ? intval( $_GET["paged"] ) : 1;
		//How many pages do we have in total?
		$totalpages = ceil( $totalitems / $perpage );
		//adjust the query to take pagination into account
		if ( ! empty( $paged ) && ! empty( $perpage ) ) {
			$offset = ( $paged - 1 ) * $perpage;
			$query  .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage;
		}


		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page"    => $perpage,
		) );

		//The pagination links are automatically built according to those parameters

		/* -- Register the Columns -- */
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		/* -- Fetch the items -- */
		$this->items = $wpdb->get_results( $query );
	}

	function get_columns() {
		return apply_filters( 'emodal_admin_modal_table_columns', array(
			'cb'    => '<input type="checkbox" />',
			'name'  => __( 'Name' ),
			'class' => __( 'Class' ),
			'load'  => __( 'Load Method' ),
		) );
	}

	function get_sortable_columns() {
		return apply_filters( 'emodal_admin_modal_table_sortable_columns', array(
			'name'  => 'name',
			'class' => 'class',
			'load'  => 'load',
		) );
	}

	function display_tablenav( $which ) {
		//if ( 'top' == $which )
		?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">

            <div class="alignleft actions bulkactions">
				<?php $this->bulk_actions(); ?>
            </div>
			<?php
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>

            <br class="clear" />
        </div>
		<?php
	}

	function get_bulk_actions() {
		$actions = array( 'delete' => __( 'Delete', 'easy-modal' ) );
		if ( ! empty( $_GET['status'] ) && $_GET['status'] == 'trash' ) {
			$actions['untrash'] = __( 'Restore' );
		}
		if ( count_deleted_modals() ) {
			$actions['empty_trash'] = __( 'Empty Trash', 'easy-modal' );
		}

		return $actions;
	}

	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->id );
	}

	function column_class( $item ) {
		return '<code>eModal-' . stripslashes( $item->id ) . '</code>';
	}

	function column_name( $item ) {
		$clone_link  = esc_url( wp_nonce_url( emodal_admin_url() . '&action=clone&id=' . $item->id, EMCORE_NONCE, EMCORE_NONCE ) );
		$edit_link   = esc_url( emodal_admin_url() . '&action=edit&id=' . $item->id );
		$delete_link = esc_url( wp_nonce_url( emodal_admin_url() . '&action=delete&id[]=' . $item->id, EMCORE_NONCE, EMCORE_NONCE ) );

		$out = '<strong><a class="row-title" href="' . $edit_link . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', 'easy-modal' ), $item->name ) ) . '">' . $item->name . '</a></strong><br />';

		$actions = array();
		//if ( current_user_can( $tax->cap->edit_terms ) ) {
		$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit', 'easy-modal' ) . '</a>';
		//$actions['inline hide-if-no-js'] = '<a href="#" class="editinline">' . __( 'Quick&nbsp;Edit' ) . '</a>';
		//}
		$actions['clone'] = '<a href="' . $clone_link . '">' . __( 'Clone', 'easy-modal' ) . '</a>';
		//if ( current_user_can( $tax->cap->delete_terms ) && $tag->term_id != $default_term )
		$actions['delete'] = "<a class='delete-tag' href='" . $delete_link . "'>" . __( 'Delete', 'easy-modal' ) . "</a>";
		//if ( $tax->public )

		//$actions = apply_filters( 'tag_row_actions', $actions, $tag );

		//$actions = apply_filters( "{$taxonomy}_row_actions", $actions, $tag );

		$out .= $this->row_actions( $actions );
		//$out .= '<div class="hidden" id="inline_' . $qe_data->term_id . '">';
		//$out .= '<div class="name">' . $qe_data->name . '</div>';

		//$out .= '<div class="slug">' . apply_filters( 'editable_slug', $qe_data->slug ) . '</div>';
		//$out .= '<div class="parent">' . $qe_data->parent . '</div></div>';

		return $out;
	}

	function column_load( $item ) {
		if ( ! $item->is_sitewide ) {
			return __( 'Per Page / Post' );
		} elseif ( $item->is_sitewide ) {
			return __( 'Sitewide' );
		}
	}

	function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

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
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, 'button', false, false, array( 'id' => 'search-submit' ) ); ?>
        </p>
		<?php
	}
}