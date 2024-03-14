<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class XLWCTY_Post_Table extends WP_List_Table {

	public $per_page = 20;
	public $data;
	public $meta_data;

	/**
	 * Constructor.
	 * @since  1.0.0
	 */
	public function __construct( $args = array() ) {
		global $status, $page;
		parent::__construct( array(
			'singular' => 'Thank You Page', //singular name of the listed records
			'plural'   => 'Thank You Pages', //plural name of the listed records
			'ajax'     => false,        //does this table support ajax?
		) );
		$status     = 'all';
		$page       = $this->get_pagenum();
		$this->data = array();
		// Make sure this file is loaded, so we have access to plugins_api(), etc.
		require_once( ABSPATH . '/wp-admin/includes/plugin-install.php' );
		parent::__construct( $args );
	}

	/**
	 * Text to display if no items are present.
	 * @return  void
	 * @since  1.0.0
	 */
	public function no_items() {
		echo wpautop( __( 'No Page Available', 'woo-thank-you-page-nextmove-lite' ) );
	}

	/**
	 * The content of each column.
	 *
	 * @param array $item The current item in the list.
	 * @param string $column_name The key of the current column.
	 *
	 * @return string              Output for the current column.
	 * @since  1.0.0
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'check-column':
				return '&nbsp;';
			case 'status':
				return $item[ $column_name ];
				break;
		}

		return '';
	}

	public function get_item_data( $item_id ) {

		if ( isset( $this->meta_data[ $item_id ] ) ) {
			$data = $this->meta_data[ $item_id ];
		} else {
			$this->meta_data[ $item_id ] = XLWCTY_Common::get_item_data( $item_id );
			$data                        = $this->meta_data[ $item_id ];
		}

		return $data;
	}

	/**
	 * Content for the "product_name" column.
	 *
	 * @param array $item The current item.
	 *
	 * @return string       The content of this column.
	 * @since  1.0.0
	 */
	public function column_status( $item ) {
		if ( $item['trigger_status'] == XLWCTY_SHORT_SLUG . 'disabled' ) {
			$text = __( 'Deactivated', 'woo-thank-you-page-nextmove-lite' );
			$link = get_post_permalink( $item['id'] );
		} else {
			$text = __( 'Activated', 'woo-thank-you-page-nextmove-lite' );
			$link = get_post_permalink( $item['id'] );
		}

		return wpautop( $text );
	}

	public function column_priority( $item ) {
		$data = $this->get_item_data( (int) $item['id'] );

		if ( isset( $data['menu_order'] ) ) {
			return $data['menu_order'];
		} else {
			update_post_meta( (int) $item['id'], '_xlwcty_menu_order', 0 );

			return 0;
		}
	}

	public function column_layout( $item ) {
		$data         = $this->get_item_data( (int) $item['id'] );
		$layouts      = XLWCTY_Common::get_builder_layouts();
		$layout_slugs = wp_list_pluck( $layouts, 'slug' );

		if ( isset( $data['builder_template'] ) && in_array( $data['builder_template'], $layout_slugs ) ) {

			$key = array_search( $data['builder_template'], $layout_slugs );

			return $layouts[ $key ]['name'];
		}

		return '';
	}

	public function column_template( $item ) {
		$template = get_post_meta( (int) $item['id'], '_wp_page_template', true );
		if ( empty( $template ) ) {
			update_post_meta( (int) $item['id'], '_wp_page_template', 'default' );

			return 'Default';
		} elseif ( 'default' === $template ) {
			return 'Default';
		} else {
			return $template;
		}
	}

	public function column_component( $item ) {
		$data = $this->get_item_data( (int) $item['id'] );

		if ( isset( $data['builder_template'] ) && $data['builder_template'] !== '' ) {
			$builder_layout = ( isset( $data['builder_layout'] ) ) ? $data['builder_layout'] : false;
			if ( $builder_layout !== false ) {
				$builder_layout     = json_decode( $builder_layout, true );
				$premium_components = XLWCTY_Common::get_premium_components();
				ob_start();
				if ( isset( $builder_layout[ $data['builder_template'] ] ) ) {
					foreach ( $builder_layout[ $data['builder_template'] ] as $section ) {
						if ( is_array( $section ) && count( $section ) > 0 ) {
							foreach ( $section as $component ) {
								if ( in_array( $component['slug'], $premium_components, true ) ) {
									continue;
								}
								echo '<p>' . $component['name'] . '</p>';
							}
						}
					}
				}
				$component_output = ob_get_clean();

				return $component_output;
			}
		}

		return '';
	}

	public function column_name( $item ) {
		$edit_link     = XLWCTY_Common::get_builder_link( $item['id'] );
		$column_string = '<strong>';
		if ( $item['trigger_status'] === 'trash' ) {
			$column_string .= '' . _draft_or_post_title( $item['id'] ) . '' . _post_states( get_post( $item['id'] ) ) . '</strong>';
		} else {
			$column_string .= '<a href="' . $edit_link . '" class="row-title">' . _draft_or_post_title( $item['id'] ) . '</a>' . _post_states( get_post( $item['id'] ) ) . '</strong>';
		}
		$column_string .= '<div class=\'row-actions\'>';

		$column_string .= '<span class="">ID: ' . $item['id'] . '';
		$column_string .= '</span> | ';
		$count         = count( $item['row_actions'] );
		foreach ( $item['row_actions'] as $k => $action ) {
			$column_string .= '<span class="' . $action['action'] . '"><a href="' . $action['link'] . '" ' . $action['attrs'] . '>' . $action['text'] . '</a>';
			if ( $k < $count - 1 ) {
				$column_string .= ' | ';
			}
			$column_string .= '</span>';
		}

		return wpautop( $column_string );
	}

	/**
	 * Retrieve an array of possible bulk actions.
	 * @return array
	 * @since  1.0.0
	 */
	public function get_bulk_actions() {
		$actions = array();

		return $actions;
	}

	/**
	 * Prepare an array of items to be listed.
	 * @since  1.0.0
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$total_items = $this->data['found_posts'];

		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $this->per_page, //WE have to determine how many items to show on a page
		) );

		unset( $this->data['found_posts'] );

		$this->items = $this->data;
	}

	/**
	 * Retrieve an array of columns for the list table.
	 * @return array Key => Value pairs.
	 * @since  1.0.0
	 */
	public function get_columns() {
		$columns = array(
			'check-column' => __( '&nbsp;', 'woo-thank-you-page-nextmove-lite' ),
			'name'         => __( 'Title', 'woo-thank-you-page-nextmove-lite' ),
			'component'    => __( 'Components', 'woo-thank-you-page-nextmove-lite' ),
			'layout'       => __( 'Layout', 'woo-thank-you-page-nextmove-lite' ),
			'template'     => __( 'Template', 'woo-thank-you-page-nextmove-lite' ),
			'status'       => __( 'Status', 'woo-thank-you-page-nextmove-lite' ),
			'priority'     => __( 'Priority', 'woo-thank-you-page-nextmove-lite' ),
		);

		return $columns;
	}

	public function get_table_classes() {
		$get_default_classes = parent::get_table_classes();
		array_push( $get_default_classes, 'xlwcty-instance-table' );

		return $get_default_classes;
	}

	public function single_row( $item ) {
		$tr_class = 'xlwcty_trigger_active';
		if ( $item['trigger_status'] === XLWCTY_SHORT_SLUG . 'disabled' ) {
			$tr_class = 'xlwcty_trigger_deactive';
		}
		echo '<tr class="' . $tr_class . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

}
