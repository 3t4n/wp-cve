<?php
defined('ABSPATH') || exit;

if(!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ImageLinks_List_Table_Items extends WP_List_Table {
	function __construct() {
		parent::__construct(array(
			'singular'=> 'imagelinks_item',
			'plural' => 'imagelinks_items',
			'ajax' => false
		));
	}
	
	function handle_row_actions( $post, $column_name, $primary ) {
		return '';
	}
	
	function column_default($item, $column_name){
		switch($column_name){
			case 'title':
			case 'active':
			case 'shortcode':
			case 'author':
			case 'date':
			case 'modified':
			case 'id':
				return $item[$column_name];
			default:
				return print_r($item, true);
		}
	}
	
	function column_cb($item) {
		return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s">',
            esc_attr($this->_args['singular']),
            esc_attr($item['id'])
		);
	}
	
	function column_title($item) {
		$page = sanitize_key(filter_input(INPUT_GET, 'page', FILTER_DEFAULT));
		
		if(current_user_can('administrator') || get_current_user_id()==$item['author']) {
			$actions = [
				'edit' => sprintf(
                    '<a href="%s">%s</a>',
                    esc_url(sprintf('?page=%s&id=%s', 'imagelinks_item', $item['id'])),
                    esc_html__('Edit', 'imagelinks')
                ),
				'copy' => sprintf(
                    '<a href="%s">%s</a>',
                    esc_url(sprintf('?page=%s&action=%s&id=%s&_wpnonce=%s', $page, 'duplicate', $item['id'], wp_create_nonce('imagelinks'))),
                    esc_html__('Duplicate', 'imagelinks')
                ),
				'delete' => sprintf(
                    '<a href="%s">%s</a>',
                    esc_url(sprintf('?page=%s&action=%s&id=%s&_wpnonce=%s', $page, 'delete', $item['id'], wp_create_nonce('imagelinks'))),
                    esc_html__('Delete', 'imagelinks')
                )
			];
			
			return sprintf(
                '<a href="%s" class="row-title">%s</a> %s',
                esc_url(sprintf('?page=%s&id=%s', 'imagelinks_item', $item['id'])),
                esc_attr($item['title']),
                $this->row_actions($actions)
			);
		} else {
			return sprintf('<strong>%1$s</strong>', esc_html($item['title']));
		}
	}
	
	function column_active($item) {
		if(current_user_can('administrator') || get_current_user_id()==$item['author']) {
			return sprintf(
				'<div class="imagelinks-toggle imagelinks-%s" data-id="%s">&nbsp;</div>',
                esc_attr($item['active'] ? 'checked' : 'unchecked'),
                esc_attr($item['id'])
			);
		} else {
			return sprintf(
				'<div class="imagelinks-toggle imagelinks-readonly imagelinks-%s" data-id="%s">&nbsp;</div>',
                esc_attr($item['active'] ? 'checked' : 'unchecked'),
                esc_attr($item['id'])
			);
		}
	}
	
	function column_shortcode($item) {
		return sprintf('<code>[imagelinks id="%s"]</code>', esc_html($item['id']));
	}
	
	function column_author($item) {
		$page = sanitize_key(filter_input(INPUT_GET, 'page', FILTER_DEFAULT));

		$args = [
			'page'   => $page,
			'author' => $item['author']
		];
		$url = add_query_arg($args, 'admin.php');
		
		return sprintf(
			'<a href="%s">%s</a>',
			esc_url($url),
			get_the_author_meta('display_name', $item['author'])
		);
	}
	
	function column_date($item) {
		$m_time = mysql2date('Y/m/d g:i:s a', $item['date']);
		$h_time = mysql2date('Y/m/d', $item['date']);
		
		return sprintf(
			'<abbr title="%1$s">%2$s</abbr>',
            esc_attr($m_time),
			esc_html($h_time)
		);
	}
	
	function column_modified( $item ) {
		$m_time = mysql2date('Y/m/d g:i:s a', $item['modified']);
		$h_time = mysql2date('Y/m/d', $item['modified']);
		
		return sprintf(
			'<abbr title="%s">%s</abbr>',
            esc_attr($m_time),
            esc_html($h_time)
		);
	}
	
	function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox">',
			'title'     => esc_html('Title', 'imagelinks'),
			'active'    => esc_html('Active', 'imagelinks'),
			'shortcode' => esc_html('Shortcode', 'imagelinks'),
			'author'    => esc_html('Author', 'imagelinks'),
			'date'      => esc_html('Date', 'imagelinks'),
			'modified'  => esc_html('Modified', 'imagelinks')
		);
		return $columns;
	}
	
	function get_sortable_columns() {
		$columns = array(
			'title'     => array('title',false),
			'active'    => array('active',false),
			'author'    => array('author',false),
			'date'      => array('date',false),
			'modified'  => array('modified',false)
		);
		return $columns;
	}
	
	function get_bulk_actions() {
		$actions = ['delete' => 'Delete'];
		return $actions;
	}
	
	function process_bulk_action() {
		$access = false;
		if(isset($_GET['_wpnonce']) && !empty($_GET['_wpnonce'])) {
            $nonce = sanitize_key(filter_input(INPUT_GET, '_wpnonce', FILTER_DEFAULT));
			$action = 'bulk-' . $this->_args['plural'];
			
			if(wp_verify_nonce($nonce, $action)) {
				$access = true;
			}
		}
		
		if(!$access) {
			return;
		}
		
		if('delete' === $this->current_action() ) {
			global $wpdb;
			$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;
			
			$items = filter_input(INPUT_GET, $this->_args['singular'], FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
			foreach($items as $id) {
				$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id=%s", $id);
				$item = $wpdb->get_row($query, OBJECT);
				if($item && (current_user_can('administrator') || get_current_user_id()==$item->author) ) {
					$result = $wpdb->delete($table, ['id'=>$id], ['%d']);

					// [filemanager] delete file
					if($result && wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
						$file_json = 'config.json';
						$file_css = 'custom.css';
						$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/' . $item->id . '/';
						
						wp_delete_file($file_root_path . $file_json);
						wp_delete_file($file_root_path . $file_css);
						
						if(is_dir($file_root_path)) {
							rmdir($file_root_path);
						}
					}
				}
			}
		}
	}
	
	function prepare_items() {
		$this->process_bulk_action();
		
		$columns = $this->get_columns();
		$sortable = $this->get_sortable_columns();
		$hidden = array();

        $itemsPerPage = 25;
        $currentPage = ($this->get_pagenum()-1) * $itemsPerPage;

        $this->_column_headers = [$columns, $hidden, $sortable];
		
		// make sql query
		global $wpdb;
		$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;

		$orderby = (isset($_GET['orderby']) ? strtolower(sanitize_key(filter_input( INPUT_GET, 'orderby', FILTER_DEFAULT ))) : 'id');
		$order = (isset($_GET['order']) ? strtolower(sanitize_key(filter_input( INPUT_GET, 'order', FILTER_DEFAULT ))) : 'desc');
		$author = (isset($_GET['author']) ? filter_input( INPUT_GET, 'author', FILTER_SANITIZE_NUMBER_INT ) : NULL);

		if($author) {
            $sql = $wpdb->prepare("SELECT * FROM {$table} WHERE author=%s ORDER BY %s %s LIMIT %d, %d", $author, $orderby, $order, $currentPage, $itemsPerPage);
            $sql_total_items = $wpdb->prepare("SELECT id FROM {$table} WHERE author=%s", $author);
		} else {
            $sql = $wpdb->prepare("SELECT * FROM {$table} ORDER BY %s %s LIMIT %d, %d", $orderby, $order, $currentPage, $itemsPerPage);
            $sql_total_items = "SELECT id FROM {$table}";
		}

        $this->items = $wpdb->get_results($sql, 'ARRAY_A');
        $total_items = $wpdb->query($sql_total_items);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'total_pages' => ceil($total_items / $itemsPerPage)
        ]);
	}
}
?>