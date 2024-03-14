<?php

namespace WP_VGWORT;

/**
 * Manages the WP Table to build the posts with messages / pixels list screen
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class List_Table_Messages extends \WP_List_Table {
	/**
	 * constructor
	 */
	public function __construct() {
		parent::__construct( array( 'ajax' => false ) );

		$this->redirect_if_state_changed();
	}

	/**
	 * get all columns to display
	 *
	 * @return array
	 */
	public function get_columns(): array {
		return array(
			'cb'            => '<input type="checkbox" />',
			'post_title'    => esc_html__( 'Titel', 'vgw-metis' ),
			'post_type'     => esc_html__( 'Typ', 'vgw-metis' ),
			'perma_link'    => esc_html__( 'Permalink', 'vgw-metis' ),
			'text_length'   => esc_html__( 'Textlänge', 'vgw-metis' ),
			'count_started' => esc_html__( 'Zählung gestartet', 'vgw-metis' ),
			'min_hits'      => esc_html__( 'Mindestzugriff', 'vgw-metis' ),
			'state'         => esc_html__( 'Status', 'vgw-metis' )
		);
	}

	/**
	 * redirect to the same page at page 1 if the state dropdown has been used
	 *
	 * @return void
	 */
	public function redirect_if_state_changed(): void {
		$statefilter   = ! empty( $_GET['state'] ) ? sanitize_key( $_GET['state'] ) : '';
		$state_changed = ! empty( $_GET['state_changed'] );
		$page = ! empty($_GET['page']) ? sanitize_key($_GET['page']) : '';


		// check if filter status has changed and reset current page to 1
		if ( $page === 'metis-messages' && $state_changed ) {
			wp_redirect( 'admin.php?page=metis-messages&paged=1&state=' . $statefilter );
			exit;
		}
	}

	/**
	 * prepare data before display
	 *
	 * @return void
	 */
	public function prepare_items(): void {

		$items = $this->get_table_data();

		$sortable = $this->get_sortable_columns();
		$hidden   = (
		is_array(
			get_user_meta(
				get_current_user_id(),
				'managevg-wort-metis_page_metis-messagescolumnshidden',
				true ) ) ) ?
			get_user_meta( get_current_user_id(), 'managevg-wort-metis_page_metis-messagescolumnshidden', true ) : array();
		$columns  = $this->get_columns();
		$primary  = 'public_identification_id';

		$this->_column_headers = array( $columns, $hidden, $sortable, $primary );


		$items_per_page = $this->get_items_per_page( 'metis_messages_per_page' );
		$current_page   = $this->get_pagenum();
		$total_items    = count( $items );
        $total_pages    = ceil( $total_items / $items_per_page );

		$items = array_slice( $items, ( ( $current_page - 1 ) * $items_per_page ), $items_per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $items_per_page,
			'total_pages' => $total_pages
		) );

		$this->items = $items;
	}

	/**
	 * get all posts to display in the wp table filtered by state
	 *
	 * @return array
	 */
	private function get_table_data(): array {
		$result = Db_Messages::get_all_posts_with_pixel();

		return $this->set_status_filter_and_sort_items( $result );
	}

	/**
	 * @param array | null $items array of posts with pixels
	 *
	 * @return array sorted and filtered items
	 */
	private function set_status_filter_and_sort_items( array|null $items ): array {
		if ( ! is_array( $items ) ) {
			return [];
		}

		$state_filter = ! empty( $_GET['state'] ) ? sanitize_key( $_GET['state'] ) : '';

		$filtered_items = [];

		// find status for each item
		foreach ( $items as $post ) {
            $post['state'] = Common::get_text_message_state( new Pixel($post));

			switch ( $state_filter ) {
				case Common::STATE_MESSAGE_REPORTED:
					if ( $post['state'] === Common::STATE_MESSAGE_REPORTED ) {
						$filtered_items[] = $post;
					}
					break;
				case Common::STATE_MESSAGE_NOT_REPORTED:
					if ( $post['state'] === Common::STATE_MESSAGE_NOT_REPORTED ) {
						$filtered_items[] = $post;
					}

					break;
				case Common::STATE_MESSAGE_NOT_REPORTABLE:
					if ( $post['state'] === Common::STATE_MESSAGE_NOT_REPORTABLE ) {
						$filtered_items[] = $post;
					}

					break;
				default:
					$filtered_items[] = $post;
					break;
			}


		}


		return $filtered_items;
	}

	/**
	 * wp table helper function for default columns data display
	 *
	 * @param $item
	 * @param $column_name
	 *
	 * @return mixed|void
	 */
	public function column_default( $item, $column_name ) {
		return $item[ $column_name ];
	}


	/**
	 * wp table helper function for post title data display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_post_title( $item ): string {
		if ( isset( $item['post_title'] ) ) {
			return "<a href = '" . esc_url( get_edit_post_link( $item['post_id'] ) ) . "'>" . $item['post_title'] . '</a>';
		} else {
			return '';
		}
	}

	/**
	 * wp table helper function for post type display
	 *
	 * @param $item
	 *
	 * @return string
	 */

	public function column_post_type( $item ): string {
		if ( ! empty( $item['post_id'] ) ) {
			global $wp_post_types;
			$obj = $wp_post_types[get_post_type( $item['post_id'] )];
			return $obj->labels->singular_name;
		}

		return '';
	}

	/**
	 * wp table helper function for post type display
	 *
	 * @param $item
	 *
	 * @return string
	 */

	public function column_state( $item ): string {
		if ( ! empty( $item['state'] ) ) {
			switch ( $item['state'] ) {
				case Common::STATE_MESSAGE_REPORTED:
					return esc_html__( 'gemeldet', 'vgw-metis' );
				case Common::STATE_MESSAGE_NOT_REPORTED:
					return '<a href="' . admin_url( 'admin.php?page=metis-message&post_id=' . $item['post_id'] ) . '" class="button button-primary">' . esc_html__( 'Meldung erstellen', 'vgw-metis' ) . '</a>';
				case Common::STATE_MESSAGE_NOT_REPORTABLE:
					return esc_html__( 'nicht meldefähig', 'vgw-metis' );
			}
		}

		return '-';
	}


	/**
	 * wp table helper function for post type display
	 *
	 * @param $item
	 *
	 * @return string
	 */

	public function column_perma_link( $item ): string {
		if ( ! empty( $item['post_id'] ) ) {
			$status = get_post_status( $item['post_id'] );

			if ( $status === 'trash' ) {
				return esc_html__( 'im Papierkorb', 'vgw-metis' );
			}

			return '<a href="' . get_permalink( $item['post_id'] ) . '" target=""_blank>' . get_permalink( $item['post_id'] ) . '</a>';
		}

		return '';
	}

	/**
	 * wp table helper function for text length display
	 *
	 * @param $item
	 *
	 * @return string
	 */

	public function column_text_length( $item ): string {
		if ( ! empty( $item['text_length'] ) ) {
			return $item['text_length'];
		}

		return '';
	}


	/**
	 * wp table helper function for min hits column data display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_min_hits( $item ): string {
		$column_html_value = "";
		if ( isset( $item["min_hits"] ) ) {
			$min_hits = json_decode( $item["min_hits"] );
			if ( $min_hits != null ) {
				foreach ( $min_hits as $min_hit ) {
					switch ( $min_hit->type ) {
						// Mindestzugriff erreicht grün FULL_LIMIT
						case "FULL_LIMIT":
							$column_html_value = $column_html_value . '<div class="tooltip chip limit-reached">'
							                     . $min_hit->year . '<span class="tooltiptext">' . $min_hit->year . ' ' . esc_html__( 'Mindestzugriff erreicht', 'vgw-metis' ) . '</span></div> ';
							break;
						// Mindestzugriff anteilig blau REDUCED_LIMIT
						case "REDUCED_LIMIT":
							$column_html_value = $column_html_value . '<div class="tooltip chip limit-proportionally-reached">'
							                     . $min_hit->year . '<span class="tooltiptext">' . $min_hit->year . ' ' . esc_html__( 'Mindestzugriff anteilig', 'vgw-metis' ) . '</span></div> ';
							break;
						// Mindestzugriff nicht erreicht rot WITHOUT_LIMIT
						case "WITHOUT_LIMIT":
							$column_html_value = $column_html_value . '<div class="tooltip chip limit-not-reached">'
							                     . $min_hit->year . '<span class="tooltiptext">' . $min_hit->year . ' ' . esc_html__( 'Mindestzugriff nicht erreicht', 'vgw-metis' ) . '</span></div> ';
							break;
						// Mindestzugriff nicht festgelegt grau NOT_SET
						case "NOT_SET":
							$column_html_value = $column_html_value . '<div class="tooltip chip limit-not-set">'
							                     . $min_hit->year . '<span class="tooltiptext">' . $min_hit->year . ' ' . esc_html__( 'Mindestzugriff nicht festgelegt', 'vgw-metis' ) . '</span></div> ';
							break;
					}
				}
			} else {
				return '';
			}

		} else {
			return '';
		}

		return $column_html_value;
	}

	/**
	 * wp table helper function for count started column data display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_count_started( $item ): string {
		if ( isset( $item['count_started'] ) && $item['count_started'] ) {
			return "<span class='dashicons dashicons-yes'></span>";
		} else {
			return "";
		}
	}

	/**
	 * wp table helper function for checkbox column display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_cb( $item ): string {
		return sprintf(
			'<input type="checkbox" name="element[]" value="%s" />',
			$item['public_identification_id']
		);
	}

	/**
	 * wp table helper function to get all sortable columns
	 *
	 * @return array[]
	 */
	protected function get_sortable_columns(): array {
		return array();
	}

	/**
	 * add status filter dropdown and per page dropdown left from pagination
	 *
	 * @param string $which top or bottom?
	 *
	 * @return void
	 */
	protected function extra_tablenav( $which ): void {
		if ( $which === 'top' ) {
			$state    = ! empty( $_GET['state'] ) ? sanitize_key( $_GET["state"] ) : '';
			?>
            <label for="state"><?php _e( 'Status', 'vgw-metis' ); ?></label>
            <select name="state" id="state">
                <option value="" <?php selected( $state, '' ); ?>><?php _e( 'Alle', 'vgw-metis' ); ?></option>
                <option value="<?php esc_attr_e( Common::STATE_MESSAGE_REPORTED ); ?>" <?php selected( $state, Common::STATE_MESSAGE_REPORTED ); ?>>
					<?php esc_html_e( 'Gemeldet' ) ?>
                </option>
                <option value="<?php esc_attr_e( Common::STATE_MESSAGE_NOT_REPORTED ); ?>" <?php selected( $state, Common::STATE_MESSAGE_NOT_REPORTED ); ?>>
					<?php esc_html_e( 'Meldefähig' ) ?>
                </option>
                <option value="<?php esc_attr_e( Common::STATE_MESSAGE_NOT_REPORTABLE ); ?>" <?php selected( $state, Common::STATE_MESSAGE_NOT_REPORTABLE ); ?>>
					<?php esc_html_e( 'Nicht meldefähig' ) ?>
                </option>
            </select>
            <script>
                document.getElementById('state').addEventListener('change', () => {
                    document.getElementById('state_changed').disabled = false;
                    document.getElementById("messages-form").submit()
                });
            </script>
            <input type="hidden" name="state_changed" id="state_changed" value="1" disabled />
            <input type="hidden" name="page" value="metis-messages"/>
			<?php
		}
	}
}
