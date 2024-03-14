<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection HtmlUnknownTarget */

class Football_Pool_Pagination {
	public bool $show_total = true;
	public int $current_page = 1;
	public bool $wrap = false;
	
	private string $page_param = 'paged';
	private string $url = '';
	private array $query_arg = [];
	private int $total_pages = 0;
	private int $total_items = 0;
	private int $page_size = FOOTBALLPOOL_DEFAULT_PAGINATION_PAGE_SIZE;

	/**
	 * @param int $num_items
	 * @param bool|null $wrap
	 */
	public function __construct( int $num_items, ?bool $wrap = false ) {
		$this->total_items = $num_items;
		$this->total_pages = $this->calc_total_pages( $num_items, $this->page_size );
		$this->current_page = $this->get_page_num();
		$this->wrap = $wrap;
	}

	/**
	 * @param string $page_param
	 * @return void
	 */
	public function set_page_param( string $page_param ) {
		$this->page_param = $page_param;
		$this->current_page = $this->get_page_num();
	}

	/**
	 * @return string
	 */
	public function get_page_param(): string
	{
		return $this->page_param;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function add_query_arg( string $key, $value ) {
		$this->query_arg[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public function remove_query_arg( string $key ) {
		unset( $this->query_arg[$key] );
	}

	/**
	 * @param string $url
	 * @return void
	 */
	public function set_url( string $url ) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function get_url(): string
	{
		return $this->url;
	}

	/**
	 * @return int
	 */
	public function get_page_size(): int
	{
		return $this->page_size;
	}

	/**
	 * @param int $size
	 * @return void
	 */
	public function set_page_size( int $size ) {
		$this->page_size = $size;
		$this->total_pages = $this->calc_total_pages( $this->total_items, $this->page_size );
		$this->current_page = $this->get_page_num();
	}

	/** @noinspection PhpInconsistentReturnPointsInspection */
	public function show( $return = 'echo' ) {
		$current_url = $this->url;
		if ( $current_url === '' ) $current_url = set_url_scheme( '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		if ( count( $this->query_arg ) > 0 ) $current_url = add_query_arg( $this->query_arg, $current_url );
			
		if ( $this->total_pages ) {
			$page_class = $this->total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		
		$output = '';
		if ( $this->wrap ) $output .= sprintf( '<div class="tablenav top%s">', $page_class );
		
		$output .= sprintf( '<div class="tablenav-pages%s">', $page_class );
		if ( $this->show_total ) {
			$output .= sprintf( '<span class="displaying-num">%s</span>'
				, sprintf( _n( '1 item', '%s items', $this->total_items, 'football-pool' ), $this->total_items )
			);
		}
		
		$disable_first = $disable_last = $disable_next = $disable_prev = '';
		$disabled_css = ' tablenav-pages-navspan disabled';
		if ( $this->current_page === 1 ) {
			$disable_first = $disabled_css;
			$disable_prev = $disabled_css;
		} elseif ( $this->current_page === 2 ){
			$disable_first = $disabled_css;
		} elseif ( $this->current_page === ( $this->total_pages - 1 ) ) {
			$disable_last = $disabled_css;
		} elseif ( $this->current_page === $this->total_pages ) {
			$disable_last = $disabled_css;
			$disable_next = $disabled_css;
		}
		
		$output .= '<span class="pagination-links">';
		$output .= sprintf( '<a class="first-page button%s" title="%s" href="%s">&laquo;</a>'
							, $disable_first
							, esc_attr__( 'Go to the first page', 'football-pool' )
							, esc_url( remove_query_arg( $this->page_param, $current_url ) )
					);
		$output .= sprintf( '<a class="prev-page button%s" title="%s" href="%s">&lsaquo;</a>'
							, $disable_prev
							, esc_attr__( 'Go to the previous page', 'football-pool' )
							, esc_url( add_query_arg( 
											$this->page_param, max( 1, $this->current_page - 1 ), 
											$current_url ) )
					);

		$output .= sprintf(
			'<span class="paging-input">
				<input class="current-page" title="%s" type="text" name="%s" value="%d" size="%d"> %s 
				<span class="total-pages">%d</span></span>'
			, esc_attr__( 'Current page', 'football-pool' )
			, $this->page_param
			, $this->current_page
			, strlen( $this->total_pages )
			, _x( 'of', 'used in pagination, e.g. 1 of 5', 'football-pool' )
			, $this->total_pages
		);

		$output .= sprintf( '<a class="next-page button%s" title="%s" href="%s">&rsaquo;</a>'
			, $disable_next
			, esc_attr__( 'Go to the next page', 'football-pool' )
			, esc_url( add_query_arg(
							$this->page_param, min( $this->total_pages, $this->current_page + 1 ),
							$current_url ) )
		);
		$output .= sprintf( '<a class="last-page button%s" title="%s" href="%s">&raquo;</a>'
			, $disable_last
			, esc_attr__( 'Go to the last page', 'football-pool' )
			, esc_url( add_query_arg( $this->page_param, $this->total_pages, $current_url ) )
		);
		$output .= '</span></div>';
		
		if ( $this->wrap ) $output .= '</div>';
		
		if ( $return === 'echo' ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * @param int $num_items
	 * @param int $page_size
	 * @return int
	 */
	private function calc_total_pages( int $num_items, int $page_size ): int
	{
		if ( $page_size <= 0 ) $page_size = FOOTBALLPOOL_DEFAULT_PAGINATION_PAGE_SIZE;
		return (int) ceil( $num_items / $page_size );
	}

	/**
	 * @return int
	 */
	private function get_page_num(): int
	{
		$page_num = Football_Pool_Utils::request_int( $this->page_param, 0 );

		if( $page_num > $this->total_pages ) {
			$page_num = $this->total_pages;
		}
		
		return max( 1, $page_num );
	}

}
