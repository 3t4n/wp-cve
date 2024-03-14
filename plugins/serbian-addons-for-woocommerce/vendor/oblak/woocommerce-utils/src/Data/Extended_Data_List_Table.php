<?php //phpcs:disable WordPress.Security.NonceVerification.Recommended, PHPCompatibility.Variables.ForbiddenThisUseContexts.Global, PHPCompatibility.Variables.ForbiddenGlobalVariableVariable.NonBareVariableFound
/**
 * Extended_Data_List_Table class file.
 *
 * @package WooCommerce Utils
 * @subpackage Data
 */

namespace Oblak\WooCommerce\Data;

use WC_Data_Store;
use WP_List_Table;

/**
 * Standardized list table for extended data stores
 */
abstract class Extended_Data_List_Table extends WP_List_Table {
    /**
     * Datastore holding the data
     *
     * @var Extended_Data_Store $data_store
     */
    protected $data_store = null;

    /**
     * WHERE clauses for use in Data Store
     *
     * @var array
     */
    protected $where_clauses;

    /**
     * Columns we can use for sorting and search.
     * Column name should correspond to a `_GET` parameter.
     *
     * @var array
     */
    protected $searchable_columns = array();

    /**
     * Column we use to filter the views
     *
     * @var string
     */
    protected $views_column = '';

    /**
     * Row variable name
     *
     * @var string
     */
    protected $row_variable = '';

    /**
     * Entity classname for the global item object
     *
     * @var string
     */
    protected $entity_classname = '';

    /**
     * Class constructor
     *
     * @param string $entity Data store entity.
     * @param array  $args   Arguments for the list table.
     */
    public function __construct( $entity, $args ) {
        $this->data_store    = WC_Data_Store::load( $entity );
        $this->where_clauses = $this->parse_where_clauses();

        $this->maybe_clear_referer();

        parent::__construct( $args );
    }

    /**
     * Clears the referer and nonce from the URL
     */
    private function maybe_clear_referer() {
        if ( ! $this->current_action() && ! empty( $_REQUEST['_wp_http_referer'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) ) ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            exit;
        }
    }

    /**
     * Parses the WHERE clauses from request array
     *
     * @return array WHERE clauses
     */
    final public function parse_where_clauses() {
        $request = wc_clean( wp_unslash( $_GET ) );
        $clauses = array();

        if ( $this->current_action() ) {
            return $clauses;
        }

        foreach ( $this->searchable_columns as $get_param ) {

            $param_value = $request[ $get_param ] ?? null;

            if ( ! is_null( $param_value ) && ! in_array( $param_value, array( 'all', '' ), true ) ) {
                $clauses[ $get_param ] = $param_value;
            }
        }

        return $clauses;
    }

    /**
     * Record count for pagination
     */
    final protected function record_count() {
        return $this->data_store->get_entity_count( $this->where_clauses );
    }

    /**
     * Get the base URL for the list table
     *
     * @return string Base URL
     */
    abstract public function get_base_url();

    /**
     * Get the list of views available on this table.
     *
     * @return string[]
     */
    abstract protected function get_view_types();

    /**
     * Get current row actions
     *
     * @return array
     */
    abstract protected function get_row_actions();

    /**
     * Extra inputs used when displaying the table on subpage of another page
     */
    public function extra_inputs() {
        $get          = wc_clean( wp_unslash( $_GET ) );
        $input_string = '<input type="hidden" name="%s" value="%s">';

        //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        if ( str_contains( $this->get_base_url(), 'post_type' ) && ! empty( $get['post_type'] ?? '' ) ) {
            printf(
                $input_string,
                'post_type',
                esc_attr( $get['post_type'] )
            );
        }

        if ( str_contains( $this->get_base_url(), 'page' ) && ! empty( $get['page'] ?? '' ) ) {
            printf(
                $input_string,
                'page',
                esc_attr( $get['page'] )
            );
        }

        printf(
            $input_string,
            'active',
            esc_attr( $get['active'] ),
        );
        //phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
    }


    /**
     * Undocumented function
     */
    public function get_views() {
        $statuses = $this->get_view_types();
        $base_url = $this->get_base_url();
        $selected = sanitize_text_field( wp_unslash( $_GET[ $this->views_column ] ?? 'all' ) );
        $views    = array();

        foreach ( $statuses as $status => $title ) {
            $views[ $status ] = sprintf(
                '<a href="%s" class="%s">
                    %s
                    <span class="count">(%s)</span>
                </a>',
                'all' !== $status ? add_query_arg( $this->views_column, $status, $base_url ) : $base_url,
                $status === $selected ? 'current' : '',
                $title,
                $this->data_store->get_entity_count( array( $this->views_column => $status ) )
            );
        }

        return $views;
    }

    /**
     * Get the extra table navigation filters
     *
     * @return array
     */
    abstract protected function get_extra_tablenav_filters();

    /**
     * Extra tablenav display
     *
     * @param  string $which Which tablenav.
     */
    final public function extra_tablenav( $which ) {
        $tablenav_filters = $this->get_extra_tablenav_filters();

        if ( 'top' !== $which || empty( $tablenav_filters ) ) {
            return;
        }

        echo '<input type="hidden" name="s" value="">';
        echo '<div class="alignleft actions">';

        foreach ( $tablenav_filters as $type => $filter_data ) {
            $this->display_tablenav_filter( $type, $filter_data );
        }

        echo '<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter">';
        echo '</div>';
    }

    /**
     * Displays individual tablenav filter
     *
     * @param string $type Filter type.
     * @param array  $data Filter data.
     */
    final protected function display_tablenav_filter( $type, $data ) {
        $selected = sanitize_text_field( wp_unslash( $_REQUEST[ $type ] ?? '0' ) );

        printf(
            '<select class="postform %s" name="%s">',
            esc_attr( implode( ' ', $data['class'] ?? array() ) ),
            esc_attr( $type )
        );

        printf(
            '<option value="">%s</option>',
            esc_html( $data['all'] )
        );

        foreach ( $data['options'] as $value => $label ) {
            $value = is_numeric( $value ) ? $label : $value;
            $label = array_key_exists( 'callback', $data ) && is_callable( $data['callback'] ) ? call_user_func( $data['callback'], $label ) : $label;

            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $value ),
                selected( $selected, $value, false ),
                esc_html( $label )
            );
        }

        echo '</select>';
    }

    /**
     * Prepare items to display
     *
     * @param  int $per_page    Number of synchronizations to retrieve.
     * @param  int $page_number Page number.
     */
    final public function prepare_items( $per_page = 20, $page_number = 1 ) {
        $this->_column_headers = $this->get_column_info();

        $per_page     = $this->get_items_per_page( "edit_{$this->_args['plural']}_per_page", 20 );
        $current_page = $this->get_pagenum();
        $total_items  = $this->record_count();

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page'    => $per_page,
            )
        );

        $defaults = array(
            'per_page' => $per_page,
            'page'     => $current_page,
            'orderby'  => sanitize_text_field( wp_unslash( $_GET['orderby'] ?? 'ID' ) ),
            'order'    => sanitize_text_field( wp_unslash( $_GET['order'] ?? 'DESC' ) ),
        );

        $args = array_merge( $defaults, $this->where_clauses );

        $this->items = $this->data_store->get_entities( $args );
    }

    /**
     * Display the table rows
     *
     * @param array $items List of items.
     */
    public function display_rows( $items = array() ) {
        if ( empty( $items ) ) {
            $items = $this->items;
        }

        if ( ! is_array( $items ) ) {
            $items = array( $items );
        }

        foreach ( $items as $item ) {
            global ${$this->row_variable};

            ${$this->row_variable} = new $this->entity_classname( $item );

            $this->single_row( $item );
        }
    }

    /**
     * Handles the row actions
     *
     * @param  object $item        Item being acted upon.
     * @param  string $column_name Current column name.
     * @param  string $primary     Primary column name.
     * @return string              Row actions HTML, if the column is the primary column.
     */
    public function handle_row_actions( $item, $column_name, $primary ) {
        if ( $primary !== $column_name ) {
            return '';
        }

        $actions = $this->get_row_actions();

        $actions = array_filter(
            $actions,
            function ( $action ) {
                return $action['when'] ?? true;
            }
        );
        $actions = array_map(
            function ( $action ) {
                return ! is_string( $action )
                    ? sprintf(
                        '<a href="%s">%s</a>',
                        $action['url'],
                        $action['title'],
                    )
                    : $action;
            },
            $actions
        );

        return $this->row_actions( $actions );
    }

    /**
     * Default column callback
     *
     * @param  object $item        Item to display.
     * @param  string $column_name Column name.
     * @return string              Column HTML.
     */
    public function column_default( $item, $column_name ) {
        global ${$this->row_variable};

        if ( method_exists( ${$this->row_variable}, "get_localized_$column_name" ) ) {
            return ${$this->row_variable}->{"get_localized_$column_name"}();
        }

        return ${$this->row_variable}->{"get_$column_name"}();
    }

    /**
     * Checkbox column
     *
     * @param  object $item Item to display.
     * @return string       Checkbox HTML
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="%s_id[]" value="%s" />',
            $this->_args['singular'],
            $item->ID
        );
    }

    /**
     * Displays the content for boolean output
     *
     * @param  string|bool $value Value of the prop.
     * @param  string      $text  Text to display.
     * @return string             HTML
     */
    protected function boolean_column( $value, $text = '' ) {
        $value = wc_string_to_bool( $value );

        $class = 'no';
        $icon  = '<span class="dashicons dashicons-dismiss"></span>';
        $color = '#d00';

        if ( $value ) {
            $class = 'yes';
            $icon  = '<span class="dashicons dashicons-yes-alt"></span>';
            $color = '#039403';
        }

        return ! empty( $text )
            ? sprintf(
                '<span class="table-icon icon-%s tips" data-tip="%s" style="color: %s;">
                    %s
                </span>',
                esc_attr( $class ),
                $text,
                $color,
                wp_kses_post( $icon )
            )
            : sprintf(
                '<span class="table-icon icon-%s" style="color: %s;">
                    %s
                </span>',
                esc_attr( $class ),
                $color,
                wp_kses_post( $icon )
            );
    }
}
