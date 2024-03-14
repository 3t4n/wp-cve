<?php

class ida79_Template_List_Table extends WP_List_Table
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
            'name' => 'Name',
            'filename' => 'Filename'
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
        return array('name' => array('name', false), 'filename' => array('filename', false));
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input id = "cb-select-%s" type="checkbox" name="%s[]" value="%s*xida79x*%s" />', esc_attr($item['name']), $this->_args['singular'], esc_attr($item['name']), esc_attr($item['filename'])
        );
    }

    // Data to be displayed in the table
    private function table_data()
    {
        $templates = get_page_templates();
        $data = array();
        if (!empty($templates) && !is_wp_error($templates)) {
            foreach ($templates as $template_name => $template_filename) {
                $data[] = array(
                    'name' => esc_attr($template_name),
                    'filename' => esc_attr($template_filename)
                );
            }
        }
        // Include the default template
        $default_template = get_page_template();
        if (!empty($default_template)) {
            $default_template_array = explode("/", $default_template);
            $default_template = end($default_template_array);
            $data[] = array(
                'name' => 'Default Template',
                'filename' => $default_template
            );
        }

        return $data;
    }

    // Define which column shows what 
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'name':
            case 'filename':
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
    function process_template_list_data()
    {
        if (!empty($_POST['ida79_item'])) {
            // Get the values of all checked checkboxes
            $checked = $_POST['ida79_item'];
            foreach ($checked as $check) {
                // Split value of checkbox via the unique (hopefully) identifier
                list($checked_names[], $checked_filenames[]) = explode("*xida79x*", $check);
            }
            // If it exists, replace the 'page.php' value in the checked_filenames array with 'default' value
            if (in_array('page.php', $checked_filenames)) {
                $checked_filenames = array_replace($checked_filenames, array_fill_keys(array_keys($checked_filenames, 'page.php'), 'default'));
            }
            // Get all post types in the database
            $post_types = get_post_types(array('public' => true), 'names', 'and');
            // Remove the attachment post type. We don't want this in the query
            if (isset($post_types['attachment'])) {
                unset($post_types['attachment']);
            }

            // Set the arguments of the query
            $args = array(
                'post_type' => $post_types,
                'meta_key' => '_wp_page_template',
                'meta_value' => $checked_filenames,
                'fields' => 'ids',
                'posts_per_page' => -1
            );

            // Query for all requested ids
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
                sort($checked_names);
                sort($ids_array);
                $the_templates = implode(', ', $checked_names);
                $the_ids = implode($ida79_delimiter, $ids_array);
				$the_count = count($ids_array);
                return array($the_templates, $the_ids, $the_count);
            } elseif (sizeof($checked_names) == 1 && empty($ids_array)) {
                $the_templates = implode(', ', $checked_names);
                $the_ids = 'Nothing Found!';
                return array($the_templates, $the_ids);
            } elseif (sizeof($checked_names) > 1 && empty($ids_array)) {
                sort($checked_names);
                $the_templates = implode(', ', $checked_names);
                $the_ids = 'Nothing Found!';
                return array($the_templates, $the_ids);
            }
        }
    }


} // Class ends
?>