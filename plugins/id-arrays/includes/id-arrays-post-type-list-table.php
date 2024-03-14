<?php

class ida79_Post_Type_List_Table extends WP_List_Table
{

    // Constructor
    public function __construct()
    {
        global $status, $page;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'ida79_item',     //singular name of the listed records
            'plural' => 'ida79_items',    //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));

    }

    // Prepare items for table to process
    public function prepare_items()
    {
        // Setup the screen options vairables and get the option chosen by the user
        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option('per_page', 'option');
        $perPage = get_user_meta($user, $option, true);
        if (empty ($perPage) || $perPage < 1) {
            $perPage = $screen->get_option('per_page', 'default');
        }
        // Setup the columns and data to be displayed
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage
        ));
        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    // Define the custom columns of the table
    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => 'Name'
        );
        return $columns;
    }

    // Define hidden columns as set in screen options
    public function get_hidden_columns()
    {
        $screen = get_current_screen();
        if (is_string($screen))
            $user = get_current_user_id();
        $screen = get_current_screen();
        return (array)get_user_option('manage' . $screen->id . 'columnshidden');

    }

    // Define sortable columns
    public function get_sortable_columns()
    {
        return array('name' => array('name', false));
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input id = "cb-select-%s" type="checkbox" name="%s[]" value="%s" />', esc_attr($item['name']), $this->_args['singular'], esc_attr($item['name'])
        );
    }

    // Data to be displayed in the table
    private function table_data()
    {
        // Get all post types
		$args = array(
			'public' => true,
			'publicly_queriable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'exclude_from_search' => false
		 );
		 
        $post_types = get_post_types($args, 'names', 'or');
		
        $data = array();
        if (!empty($post_types) && !is_wp_error($post_types)) {
            foreach ($post_types as $post_type) {
                $data[] = array(
                    'name' => esc_attr($post_type)
                );
            }
        }
        return $data;
    }

    // Define which column shows what 
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'name':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    // Defines sort actions 
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'name';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }

        $result = strnatcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }

    // Return the checked checkboxes
    function process_post_type_list_data()
    {
        if (!empty($_POST['ida79_item'])) {
            // Get the values of all checked checkboxes
            $checked = $_POST['ida79_item'];
            foreach ($checked as $check) {
                $selected_post_types[] = $check;
            }

            // Set the arguments of the query
            $args = array(
                'post_type' => $selected_post_types,
                'fields' => 'ids',
				'post_status' => 'any',
                'posts_per_page' => -1
            );

            // Query for all ids requested
            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) {
                foreach ($the_query->posts as $id) {
                    $ids_array[] = $id;
                }
            }
            wp_reset_postdata();

            // Create a delimited string from the array and output result
            $ida79_options = get_option('ida79_options_val');
            $ida79_delimiter = isset($ida79_options['ida79_delimiter']) ? $ida79_options['ida79_delimiter'] : ',&nbsp;';
            if (!empty($ids_array)) {
                sort($selected_post_types);
                sort($ids_array);
                $the_post_types = implode(', ', $selected_post_types);
                $the_ids = implode($ida79_delimiter, $ids_array);
				$the_count = count($ids_array);
                return array($the_post_types, $the_ids, $the_count);
            } elseif (sizeof($selected_post_types) == 1 && empty($ids_array)) {
                $the_post_types = implode(', ', $selected_post_types);
                $the_ids = 'Nothing Found!';
                return array($the_post_types, $the_ids);
            } elseif (sizeof($selected_post_types) > 1 && empty($ids_array)) {
                sort($selected_post_types);
                $the_post_types = implode(', ', $selected_post_types);
                $the_ids = 'Nothing Found!';
                return array($the_post_types, $the_ids);
            }
        }
    }

} // Class ends
?>