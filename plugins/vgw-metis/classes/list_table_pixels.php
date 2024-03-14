<?php

namespace WP_VGWORT;

/**
 * Manages the WP Table to build the pixel list screen
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class List_Table_Pixels extends \WP_List_Table {
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
			'cb'                        => '<input type="checkbox" />',
			'public_identification_id'  => esc_html__( 'Öffentlicher Identifikationscode', 'vgw-metis' ),
			'private_identification_id' => esc_html__( 'Privater Identifikationscode', 'vgw-metis' ),
			'ordered_at'                => esc_html__( 'Bestellt am', 'vgw-metis' ),
			'count_started'             => esc_html__( 'Zählung gestartet', 'vgw-metis' ),
			'min_hits'                  => esc_html__( 'Mindestzugriff', 'vgw-metis' ),
			'post_title'                => esc_html__( 'Titel', 'vgw-metis' ),
			'message_date'              => esc_html__( 'Meldungsdatum', 'vgw-metis' ),
			'state'                     => esc_html__( 'Status', 'vgw-metis' )
		);
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
				'managevg-wort-metis_page_metis-pixelcolumnshidden',
				true ) ) ) ?
			get_user_meta( get_current_user_id(), 'managevg-wort-metis_page_metis-pixelcolumnshidden', true ) : array();
		$columns  = $this->get_columns();
		$primary  = 'public_identification_id';

		$this->_column_headers = array( $columns, $hidden, $sortable, $primary );

		$items_per_page = $this->get_items_per_page( 'metis_pixels_per_page' );
		$current_page   = (int) $this->get_pagenum();
		$total_items    = count( $items );
		$total_pages    = ceil( $total_items / $items_per_page ); // use ceil to round up

		$items = array_slice( $items, ( ( $current_page - 1 ) * $items_per_page ), $items_per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items, // total number of items
			'per_page'    => $items_per_page, // items to show on a page
			'total_pages' => $total_pages
		) );

		$this->items = $items;
	}

	/**
     * redirect to the same page at page 1 if the state dropdown has been used
     *
	 * @return void
	 */
	public function redirect_if_state_changed() {
		$statefilter   = ! empty( $_GET['state'] ) ? sanitize_key( $_GET['state'] ) : '';
		$state_changed = ! empty( $_GET['state_changed'] );
        $page = ! empty($_GET['page']) ? sanitize_key($_GET['page']) : '';


		// check if filter status has changed and reset current page to 1
		if ( $page === 'metis-pixel' && $state_changed ) {
			wp_redirect( 'admin.php?page=metis-pixel&paged=1&state=' . $statefilter );
			exit;
		}
	}


	/**
	 * get all pixels to display in the wp table filtered by state
	 *
	 * @return array
	 */
	private function get_table_data(): array {
		$statefilter = ! empty( $_GET['state'] ) ? sanitize_key( $_GET['state'] ) : '';

		$order = ! empty( $_GET['order'] ) ? sanitize_key( $_GET['order'] ) : 'asc';

		$assigned = null;
		$active   = null;
		$disabled = null;
		$orderby  = array();

		switch ( $statefilter ) {
			case '':
				$orderby += array( 'assigned' => $order );
				$orderby += array( 'active' => 'asc' );
				$orderby += array( 'disabled' => 'asc' );
				break;
			case Common::STATE_PIXEL_ASSIGNED:
				$assigned = true;
				$active   = true;
				$orderby  += array( 'disabled' => 'asc' );
				break;
			case Common::STATE_PIXEL_AVAILABLE:
				$assigned = false;
				$disabled = false;
				break;
			case Common::STATE_PIXEL_RESERVED:
				$assigned = true;
				$disabled = false;
				$active   = false;
				break;
			case Common::STATE_PIXEL_DISABLED:
				$disabled = true;
				break;
		}

		return DB_Pixels::get_all_pixels( $assigned, $active, $disabled, null, null, $orderby );
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
	 * wp table helper function for ordered at column data display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_ordered_at( $item ): string {
		$date = date_create( $item['ordered_at'] );

		return date_format( $date, 'd.m.Y' );
	}

	/**
	 * wp table helper function for post title data display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_post_title( $item ): string {
		if ( isset( $item['post_name'] ) ) {
			$status = get_post_status( $item['post_id'] );
			if ( $status == "trash" ) {
				return "<a href = '" . esc_url( "/wp-admin/edit.php?post_status=trash&post_type=" . get_post_type( $item['post_id'] ) ) . "'>" . $item['post_title'] . esc_html__( " (im Papierkorb)", 'vgw-metis' ) . '</a>';
			} else {
				return "<a href = '" . esc_url( get_edit_post_link( $item['post_id'] ) ) . "'>" . $item['post_title'] . '</a>';
			}
		} else {
			return "";
		}
	}

	/**
	 * wp table helper function for state column data display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_state( $item ): string {
		return self::get_state_label( $item['assigned'], $item['active'], $item['disabled'] );
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
	 * wp table helper function for message date column display
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_message_date( $item ): string {
		if ( ! empty( $item['message_created_at'] ) && $item['message_created_at'] !== '0000-00-00 00:00:00' ) {
			$date_obj = new \DateTime( $item['message_created_at'] );

			return $date_obj->format( 'Y-m-d' );

		} else {
			return '-';
		}
	}

	/**
	 * wp table helper function to get all sortable columns
	 *
	 * @return array[]
	 */
	protected function get_sortable_columns(): array {
		return array(
			'state' => array( 'state', true ),
		);
	}

	/**
	 * returns the name of the given state
	 *
	 * @param bool | null $assigned given State or ''
	 * @param bool | null $active   active status of pixel post relation or null
	 * @param bool | null $disabled disabled status of pixel
	 *
	 * @return string               returns the name for displaying
	 */
	public static function get_state_label( bool|null $assigned, bool|null $active, bool|null $disabled ): string {
		$message = '';

		if ( $disabled ) {
			$message = esc_html__( 'Ungültig', 'vgw-metis' );
		} else if ( $assigned && $active ) {
			$message = esc_html__( 'Zugewiesen', 'vgw-metis' );
		} else if ( ! $assigned && ! $active ) {
			$message = esc_html__( 'Nicht zugewiesen', 'vgw-metis' );
		} else if ( $assigned === null && $active === null ) {
			$message = esc_html__( 'Nicht zugewiesen', 'vgw-metis' );
		} else if ( $assigned && ! $active ) {
			$message = esc_html__( 'Nicht zugewiesen (reserviert)', 'vgw-metis' );
		}

		return $message;
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
			$state = ! empty( $_GET['state'] ) ? sanitize_key( $_GET["state"] ) : '';
			?>
            <label for="state"><?php _e( 'Status', 'vgw-metis' ); ?></label>
            <select name="state" id="state">
                <option value="" <?php selected( $state, '' ); ?>><?php _e( 'Alle', 'vgw-metis' ); ?></option>
                <option value="<?php esc_attr_e( Common::STATE_PIXEL_ASSIGNED ); ?>" <?php selected( $state, Common::STATE_PIXEL_ASSIGNED ); ?>>
					<?php esc_html_e( List_Table_Pixels::get_state_label( true, true, false ) ) ?>
                </option>
                <option value="<?php esc_attr_e( Common::STATE_PIXEL_AVAILABLE ); ?>" <?php selected( $state, Common::STATE_PIXEL_AVAILABLE ); ?>>
					<?php esc_html_e( List_Table_Pixels::get_state_label( false, false, false ) ) ?>
                </option>
                <option value="<?php esc_attr_e( Common::STATE_PIXEL_RESERVED ); ?>" <?php selected( $state, Common::STATE_PIXEL_RESERVED ); ?>>
					<?php esc_html_e( List_Table_Pixels::get_state_label( true, false, false ) ) ?>
                </option>
                <option value="<?php esc_attr_e( Common::STATE_PIXEL_DISABLED ); ?>" <?php selected( $state, Common::STATE_PIXEL_DISABLED ); ?>>
					<?php esc_html_e( List_Table_Pixels::get_state_label( null, null, true ) ) ?>
                </option>
            </select>
            <script>
                document.getElementById('state').addEventListener('change', () => {
                    document.getElementById('state_changed').disabled = false;
                    document.getElementById("pixels-form").submit()
                });
            </script>
            <input type="hidden" name="state_changed" id="state_changed" value="1" disabled />
            <input type="hidden" name="page" value="metis-pixel"/>
			<?php
		}
	}

}
