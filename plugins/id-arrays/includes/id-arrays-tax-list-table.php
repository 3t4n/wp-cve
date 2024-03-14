<?php

class ida79_Tax_List_Table extends WP_List_Table
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
            'slug' => 'Slug',
            'taxonomy' => 'Taxonomy Type'
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
        return array('name' => array('name', false), 'slug' => array('slug', false), 'taxonomy' => array('taxonomy', false));
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input id = "cb-select-%s" type="checkbox" name="%s[]" value="%s*xida79x*%s" />', esc_attr($item['id']), $this->_args['singular'], esc_attr($item['id']), esc_attr($item['slug']), esc_attr($item['name']), esc_attr($item['slug']), esc_attr($item['taxonomy'])
        );
    }

    // Data to be displayed in the table
    private function table_data()
    {
        // Get the taxoxomies
        $taxonomies = get_taxonomies(array('public' => true), 'names', 'and');
        // Remove the post_format and product_shipping_class (if exists) taxonomies
        if (isset($taxonomies['post_format'])) {
            unset($taxonomies['post_format']);
        }
        if (isset($taxonomies['product_shipping_class'])) {
            unset($taxonomies['product_shipping_class']);
        }
        // Run the loop to get taxonomy terms to populate the table
        $terms = get_terms($taxonomies, array('hide_empty' => false));

        $data = array();
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $data[] = array(
                    'id' => esc_attr($term->term_id),
                    'name' => esc_attr($term->name),
                    'slug' => esc_attr($term->slug),
                    'taxonomy' => esc_attr($term->taxonomy)
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
            case 'slug':
            case 'taxonomy':
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
    function process_tax_list_data()
    {
        if (!empty($_POST['ida79_item'])) {
            // Get the values of all checked checkboxes
            $checked = $_POST['ida79_item'];
            foreach ($checked as $check) {
                // Split value of checkbox via the unique identifier
                list($checked_tax[], $checked_slugs[]) = explode("*xida79x*", $check);
            }
            // Get all post types in the database
            $post_types = get_post_types(array('public' => true), 'names', 'and');
            // Remove the attachment post type. We don't want this in the query
            if (isset($post_types['attachment'])) {
                unset($post_types['attachment']);
            }
            // Get the taxoxomies
            $taxes = get_taxonomies(array('public' => true), 'names', 'and');
            // Remove the post_format and product_shipping_class (if exists) taxonomies
            if (isset($taxes['post_format'])) {
                unset($taxes['post_format']);
            }
            if (isset($taxes['product_shipping_class'])) {
                unset($taxes['product_shipping_class']);
            }

            // Get all taxonomies selected by the user
            foreach ($taxes as $tax) {
                $the_taxes[] = array(
                    'taxonomy' => $tax,
                    'field' => 'term_id',
                    'terms' => $checked_tax,
                );
            }

            // Set the arguments of the query
            $the_taxes['relation'] = 'OR';
            $args = array(
                'post_type' => $post_types,
                'tax_query' => $the_taxes,
                'fields' => 'ids',
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
                sort($checked_slugs);
                sort($ids_array);
                $the_slugs = implode(', ', $checked_slugs);
                $the_ids = implode($ida79_delimiter, $ids_array);
				$the_count = count($ids_array);
                return array($the_slugs, $the_ids, $the_count);
            } elseif (sizeof($checked_slugs) == 1 && empty($ids_array)) {
                $the_slugs = implode(', ', $checked_slugs);
                $the_ids = 'Nothing Found!';
                return array($the_slugs, $the_ids);
            } elseif (sizeof($checked_slugs) > 1 && empty($ids_array)) {
                sort($checked_slugs);
                $the_slugs = implode(', ', $checked_slugs);
                $the_ids = 'Nothing Found!';
                return array($the_slugs, $the_ids);
            }
        }
    }

} // Class ends
?>