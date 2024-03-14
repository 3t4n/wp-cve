<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class badr_syndication_table extends WP_List_Table {
	
	function get_columns(){
		$columns = array(
				'post_title'    => '제목',
				'guid' => '색인확인'
		);
		return $columns;
	}
	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'guid':
				//preg_match ( '/([p||l][aegiost]{3}-[0-9]+\.xml)/i', $item[$column_name], $matches );
				//return $matches[1];
				$query = $item->{$column_name}.'&s='.abs(get_post_meta ( $item->ID, '_syndication', true ));
				$link = '<a title="네이버색인확인" href="http://web.search.naver.com/search.naver?sm=tab_hty.top&where=webkr&ie=utf8&query='.urlencode('site:'.$query).'" target="blank">'.$query.'</a>';
				return $link;
			case 'post_title':
				$link = '<a title="네이버검색" href="http://search.naver.com/search.naver?query='.urlencode($item->{$column_name}).'" target="blank">'.$item->{$column_name}.'</a>';
				return $link;
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	function prepare_items(){
		$max_entry = 10;
		$paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
		$orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'ID';
		$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
		$arg = array (
				'post_type' => 'post',
				'meta_key' => '_syndication',
				'meta_value' => '0',
				'meta_compare' => '>',
				'posts_per_page' => $max_entry,
				'paged' => $paged,
				'orderby' => $orderby,
				'order' => $order
		);

		
		$query = new WP_Query ( $arg );
		if ($query->have_posts ()) $this->items = $query->posts;
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
	
		// here we configure table headers, defined in our methods
		$this->_column_headers = array($columns, $hidden, $sortable);
	
		//$this->process_bulk_action();
	

		$this->set_pagination_args(array(
				'total_items' => $query->found_posts, // total items defined above
				'per_page' => $max_entry,
				'total_pages' => $query->max_num_pages
		));
	}
}