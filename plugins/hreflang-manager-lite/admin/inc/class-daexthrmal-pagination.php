<?php
/**
 * This file is used to handle the pagination in the back-end menus.
 */

/**
 * Handles the pagination on the back-end menus by returning the HTML content useful to represent the elements of the
 *  pagination.
 */
class Daexthrmal_Pagination {

	/**
	 * Total number of items.
	 *
	 * @var null
	 */
	public $total_items = null;

	/**
	 * Number of records to display per page.
	 *
	 * @var int
	 */
	private $record_per_page = 10;

	/**
	 * Target page url.
	 *
	 * @var string
	 */
	private $target_page = '';

	/**
	 * Store the current page value, this is set through the set_current_page() method.
	 *
	 * @var int
	 */
	private $current_page = 0;

	/**
	 * Store the number of adjacent pages to show on each side of the current page inside the pagination.
	 *
	 * @var int
	 */
	private $adjacents = 2;

	/**
	 * Store the $_GET parameter to use.
	 *
	 * @var string
	 */
	private $parameter_name = 'p';

	/**
	 * Set the total number of items.
	 *
	 * @param $value
	 */
	public function set_total_items( $value ) {
		$this->total_items = intval( $value, 10 );
	}

	/**
	 * Set the number of items to show per page.
	 *
	 * @param $value
	 */
	public function set_record_per_page( $value ) {
		$this->record_per_page = intval( $value, 10 );
	}

	/**
	 * Set the page url
	 *
	 * @param $value
	 */
	public function set_target_page( $value ) {
		$this->target_page = $value;
	}

	/**
	 * Set the current page parameter by getting it from $_GET['p'], if it's not set or it's not > than 0 then set
	 * it to 1.
	 */
	public function set_current_page() {

		if ( isset( $_GET[ $this->parameter_name ] ) ) {

			$page_number = intval( $_GET[ $this->parameter_name ], 10 );

			if ( $page_number > 0 and $page_number <= ceil( $this->total_items / $this->record_per_page ) ) {
				$this->current_page = $page_number;
			} else {
				$this->current_page = 1;
			}
		} else {

			$this->current_page = 1;

		}
	}

	/**
	 * Set the number of adjacent pages to show on each side of the current page inside the pagination.
	 *
	 * @param int $value
	 *
	 * @return void
	 */
	public function set_adjacents( $value ) {
		$this->adjacents = intval( $value, 10 );
	}

	// Assing a different $_GET parameter instead of p
	public function set_parameter_name( $value = '' ) {
		$this->parameter_name = $value;
	}

	/**
	 * Calculate and echo the pagination.
	 */
	public function show() {

		// Setup page vars for display.
		$prev      = $this->current_page - 1;// previous page
		$next      = $this->current_page + 1;// next page
		$last_page = intval( ceil( $this->total_items / $this->record_per_page ), 10 );// last page
		$lpm1      = $last_page - 1;// last page minus 1

		// Generate the pagination if there is more than one page.
		if ( $last_page > 1 ) {

			// Generate the "Previous" button.
			if ( $this->current_page ) {

				if ( $this->current_page > 1 ) {

					// If the current page is > 1 the "Previous" button is clickable.
					$this->display_link( '&#171', $this->get_pagenum_link( $prev ) );

				} else {

					// If the current page is not > 1 the previous button is not clickable.
					$this->display_link( '&#171' );

				}
			}

			// Generate the buttons of all the pages.
			if ( $last_page < 7 + ( $this->adjacents * 2 ) ) {

				// Not enough pages to bother breaking it up

				for ( $counter = 1; $counter <= $last_page; $counter++ ) {
					if ( $counter === $this->current_page ) {
						$this->display_link( $counter );
					} else {
						$this->display_link( $counter, $this->get_pagenum_link( $counter ) );
					}
				}
			} else {

				// Enough pages to hide some.

				if ( $this->current_page < 1 + ( $this->adjacents * 2 ) ) {

					// When the selected page is near the beginning hide pages at the end.

					for ( $counter = 1; $counter < 4 + ( $this->adjacents * 2 ); $counter++ ) {

						if ( $counter === $this->current_page ) {
							$this->display_link( $counter );
						} else {
							$this->display_link( $counter, $this->get_pagenum_link( $counter ) );
						}
					}

					echo '<span>...</span>';
					$this->display_link( $lpm1, $this->get_pagenum_link( $lpm1 ) );
					$this->display_link( $last_page, $this->get_pagenum_link( $last_page ) );

				} elseif ( $last_page - ( $this->adjacents * 2 ) > $this->current_page && $this->current_page > ( $this->adjacents * 2 ) ) {

					// When the selected page is in the middle hide some pages form the front and some page from the back.

					$this->display_link( '1', $this->get_pagenum_link( 1 ) );
					$this->display_link( '2', $this->get_pagenum_link( 2 ) );
					echo '<span>...</span>';

					for ( $counter = $this->current_page - $this->adjacents; $counter <= $this->current_page + $this->adjacents; $counter++ ) {

						if ( $counter === $this->current_page ) {
							$this->display_link( $counter );
						} else {
							$this->display_link( $counter, $this->get_pagenum_link( $counter ) );
						}
					}

					echo '<span>...</span>';
					$this->display_link( $lpm1, $this->get_pagenum_link( $lpm1 ) );
					$this->display_link( $last_page, $this->get_pagenum_link( $last_page ) );

				} else {

					// When the selected page is near the end hide pages at the beginning.

					$this->display_link( '1', $this->get_pagenum_link( 1 ) );
					$this->display_link( '2', $this->get_pagenum_link( 2 ) );
					echo '<span>...</span>';
					for ( $counter = $last_page - ( 2 + ( $this->adjacents * 2 ) ); $counter <= $last_page; $counter++ ) {

						if ( $counter === $this->current_page ) {
							$this->display_link( $counter );
						} else {
							$this->display_link( $counter, $this->get_pagenum_link( $counter ) );
						}
					}
				}
			}

			// Generate the "Next" button.
			if ( $this->current_page ) {

				if ( $this->current_page < $counter - 1 ) {

					// If the current page is not the last page the "Next" button is clickable.
					$this->display_link( '&#187', $this->get_pagenum_link( $next ) );

				} else {

					// If the current page is the last page the "Next" button is not clickable.
					$this->display_link( '&#187' );

				}
			}
		}
	}

	/**
	 * Return the complete url associated with this page id.
	 *
	 * @param $id The page id.
	 *
	 * @return string The URL associated with the id.
	 */
	private function get_pagenum_link( $id ) {

		//search: s ----------------------------------------------------------------------------------------------------
		if(isset($_GET['s'])){
			$s = sanitize_text_field( $_GET['s']);
			$filter = '&s=' . $s;
		}else{
			$filter = '';
		}

		if ( false === strpos( $this->target_page, '?' ) ) {
			return $this->target_page . '?' . $this->parameter_name . '=' . $id . $filter;
		} else {
			return $this->target_page . '&' . $this->parameter_name . '=' . $id . $filter;
		}
	}

	/**
	 * Generate the query string to use inside the SQL query.
	 *
	 * @return string
	 */
	public function query_limit() {

		// Calculate the $list_start position.
		$list_start = ( $this->current_page - 1 ) * $this->record_per_page;

		// Start of the list should be less than pagination count.
		if ( $list_start >= $this->total_items ) {
			$list_start = ( $this->total_items - $this->record_per_page );
		}

		// List start can't be negative.
		if ( $list_start < 0 ) {
			$list_start = 0;
		}

		return 'LIMIT ' . intval( $list_start, 10 ) . ', ' . intval( $this->record_per_page, 10 );
	}

	/**
	 * Display the pagination link based on the provided link text and url.
	 *
	 * @param string $text The text of the link.
	 * @param null $url The url of the link.
	 */
	private function display_link( $text, $url = null ) {

		if ( null === $url ) {

			// Non-clickable and disabled links.

			if ( '&#171' === $text ) {
				echo '<a href="javascript: void(0)" class="disabled">&#171</a>';
			} elseif ( '&#187' === $text ) {
				echo '<a href="javascript: void(0)" class="disabled">&#187</a>';
			} else {
				echo '<a href="javascript: void(0)" class="disabled">' . esc_html( $text ) . '</a>';
			}
		} else {

			// Clickable and active links.

			if ( '&#171' === $text ) {
				echo '<a href="' . esc_url( $url ) . '">&#171</a>';
			} elseif ( '&#187' === $text ) {
				echo '<a href="' . esc_url( $url ) . '">&#187</a>';
			} else {
				echo '<a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a>';
			}
		}
	}
}
