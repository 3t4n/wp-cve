<?php
/**
 * The log viewer functionality of the plugin.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Backend;

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary. In this tutorial, we are
 * going to use the WP_List_Table class directly from WordPress core.
 *
 * IMPORTANT:
 * Please note that the WP_List_Table class technically isn't an official API,
 * and it could change at some point in the distant future. Should that happen,
 * I will update this plugin with the most current techniques for your reference
 * immediately.
 *
 * If you are really worried about future compatibility, you can make a copy of
 * the WP_List_Table class (file path is shown just below) to use and distribute
 * with your plugins. If you do that, just remember to change the name of the
 * class to avoid conflicts with core.
 *
 * Since I will be keeping this tutorial up-to-date for the foreseeable future,
 * I am going to work with the copy of the class provided in WordPress core.
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * The LogViewer class renders logging data.
 *
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 */
class LogViewer extends \WP_List_Table {
	/**
	 * Constructor.
	 */
	public function __construct() {
		global $status, $page;

		// Set parent defaults.
		parent::__construct(
			array(
				'singular' => 'message',     // singular name of the listed records.
				'plural'   => 'messages',    // plural name of the listed records.
				'ajax'     => false,        // does this table support ajax?
			)
		);
	}

	/** ************************************************************************
	 * Recommended. This method is called when the parent class can't find a method
	 * specifically build for a given column. Generally, it's recommended to include
	 * one method for each column you want to render, keeping your package class
	 * neat and organized. For example, if the class needs to process a column
	 * named 'title', it would first see if a method named $this->column_title()
	 * exists - if it does, that method will be used. If it doesn't, this one will
	 * be used. Generally, you should try to use custom column methods as much as
	 * possible.
	 *
	 * Since we have defined a column_title() method later on, this method doesn't
	 * need to concern itself with any column with a name of 'title'. Instead, it
	 * needs to handle everything else.
	 *
	 * For more detailed insight into how columns are handled, take a look at
	 * WP_List_Table::single_row_columns()
	 *
	 * @param array $item        A singular item (one full row's worth of data).
	 * @param array $column_name The name/slug of the column to be processed.
	 *
	 * @return string Text or HTML to be placed inside the column <td>.
	 **************************************************************************/
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'priority':
			case 'message':
			case 'ID':
			case 'time':
				return $item[ $column_name ];
			default:
				return 'unknown column';
		}
	}

	/** ************************************************************************
	 * Format priority column.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 *
	 * @param array $item A singular item (one full row's worth of data).
	 *
	 * @return string Text to be placed inside the column <td>.
	 **************************************************************************/
	public function column_priority( $item ) {
		$prio_levels = array(
			'Emergency',
			'Alert',
			'Critical',
			'Error',
			'Warning',
			'Notice',
			'Info',
			'Debug',
		);

		$prio2style = array(
			'color: red;padding: 0px 2px 0px 2px;font-weight: bold;',
			'color: red;padding: 0px 2px 0px 2px;font-weight: bold;',
			'color: red;padding: 0px 2px 0px 2px;font-weight: bold;',
			'color: orange;padding: 0px 2px 0px 2px;font-weight: bold;',
			'background: yellow;padding: 0px 2px 0px 2px;font-weight: bold;',
			'color: green;padding: 0px 2px 0px 2px;font-weight: bold;',
			'color: darkgreen;padding: 0px 2px 0px 2px;font-weight: bold;',
			'padding: 0px 2px 0px 2px;font-weight: bold;',
		);

		$label = '';

		switch ( $item['priority'] ) {
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
				$label = $prio_levels[ $item['priority'] ];
				break;
			default:
				$label = 'unknown';
				break;
		}

		// Return the priority contents.
		return sprintf(
			'<span style="%2$s">%1$s</span>',
			/*$1%s*/ 
			$label,
			$prio2style[ $item['priority'] ]
		);
	}

	/** ************************************************************************
	 * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
	 * is given special treatment when columns are processed. It ALWAYS needs to
	 * have it's own method.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 *
	 * @param array $item A singular item (one full row's worth of data).
	 *
	 * @return string Text to be placed inside the column <td> (movie title only).
	 **************************************************************************/
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/ 
			$this->_args['singular'],  // Let's simply repurpose the table's singular label ("movie").
			/*$2%s*/ 
			$item['ID']                // The value of the checkbox should be the record's id.
		);
	}

	/** ************************************************************************
	 * REQUIRED! This method dictates the table's columns and titles. This should
	 * return an array where the key is the column slug (and class) and the value
	 * is the column's title text. If you need a checkbox for bulk actions, refer
	 * to the $columns array below.
	 *
	 * The 'cb' column is treated differently than the rest. If including a checkbox
	 * column in your table you must create a column_cb() method. If you don't need
	 * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 *
	 * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
	 **************************************************************************/
	public function get_columns() {
		$columns = array(
			'cb'       => '<input type="checkbox" />', // Render a checkbox instead of text.
			'time'     => 'Time',
			'priority' => 'Priority',
			'message'  => 'Message',
		);
		return $columns;
	}

	/** ************************************************************************
	 * Optional. If you want one or more columns to be sortable (ASC/DESC toggle),
	 * you will need to register it here. This should return an array where the
	 * key is the column that needs to be sortable, and the value is db column to
	 * sort by. Often, the key and value will be the same, but this is not always
	 * the case (as the value is a column name from the database, not the list table).
	 *
	 * This method merely defines which columns should be sortable and makes them
	 * clickable - it does not handle the actual sorting. You still need to detect
	 * the ORDERBY and ORDER querystring variables within prepare_items() and sort
	 * your data accordingly (usually by modifying your query).
	 *
	 * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
	 **************************************************************************/
	public function get_sortable_columns() {
		$sortable_columns = array();
		return $sortable_columns;
	}

	/** ************************************************************************
	 * Optional. If you need to include bulk actions in your list table, this is
	 * the place to define them. Bulk actions are an associative array in the format
	 * 'slug'=>'Visible Title'
	 *
	 * If this method returns an empty value, no bulk action will be rendered. If
	 * you specify any bulk actions, the bulk actions box will be rendered with
	 * the table automatically on display().
	 *
	 * Also note that list tables are not automatically wrapped in <form> elements,
	 * so you will need to create those manually in order for bulk actions to function.
	 *
	 * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
	 **************************************************************************/
	public function get_bulk_actions() {
		$actions = array();
		return $actions;
	}

	/** ************************************************************************
	 * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
	 * For this example package, we will handle it in the class to keep things
	 * clean and organized.
	 *
	 * @see $this->prepare_items()
	 **************************************************************************/
	public function process_bulk_action() {
		// Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			wp_die( 'Items deleted (or they would be if we had items to delete)!' );
		}
	}

	/**
	 * This checks for sorting input and sorts the data in our array accordingly.
	 *
	 * In a real-world situation involving a database, you would probably want
	 * to handle sorting by passing the 'orderby' and 'order' values directly
	 * to a custom query. The returned data will be pre-sorted, and this array
	 * sorting technique would be unnecessary.
	 *
	 * @param mixed $a Part a.
	 * @param mixed $b Part b.
	 */
	public function usort_reorder( $a, $b ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'time'; // If no sort, default to title.
		$order   = ( ! empty( $_REQUEST['order'] ) ) ? sanitize_text_field( $_REQUEST['order'] ) : 'desc'; // If no order, default to desc.
		// phpcs:enable
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] ); // Determine sort order.
		return ( 'asc' === $order ) ? $result : -$result; // Send final sort direction to usort.
	}

	/** ************************************************************************
	 * REQUIRED! This is where you prepare your data for display. This method will
	 * usually be used to query the database, sort and filter the data, and generally
	 * get it ready to be displayed. At a minimum, we should set $this->items and
	 * $this->set_pagination_args(), although the following properties and methods
	 * are frequently interacted with here...
	 *
	 * @global WPDB $wpdb
	 *
	 * @uses $this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_sortable_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 **************************************************************************/
	public function prepare_items() {
		global $wpdb; // This is used only if making any database queries.

		/**
		 * First, lets decide how many records per page to show
		 */
		$per_page = 25;

		/**
		 * REQUIRED. Now we need to define our column headers. This includes a complete
		 * array of columns to be displayed (slugs & titles), a list of columns
		 * to keep hidden, and a list of columns that are sortable. Each of these
		 * can be defined in another method (as we've done here) before being
		 * used to build the value for our _column_headers property.
		 */
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		/**
		 * REQUIRED. Finally, we build an array to be used by the class for column
		 * headers. The $this->_column_headers property takes an array which contains
		 * 3 other arrays. One for all columns, one for hidden columns, and one
		 * for sortable columns.
		 */
		$this->_column_headers = array( $columns, $hidden, $sortable );

		/**
		 * Optional. You can handle your bulk actions however you see fit. In this
		 * case, we'll handle them within our package just to keep things clean.
		 */
		$this->process_bulk_action();

		/**
		 * Instead of querying a database, we're going to fetch the example data
		 * property we created for use in this plugin. This makes this example
		 * package slightly different than one you might build on your own. In
		 * this example, we'll be using array manipulation to sort and paginate
		 * our data. In a real-world implementation, you will probably want to
		 * use sort and pagination data to build a custom query instead, as you'll
		 * be able to use your precisely-queried data immediately.
		 */
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		$data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}etracker_logging ORDER BY time ASC", ARRAY_A );
		// phpcs:enable

		usort( $data, array( $this, 'usort_reorder' ) );

		/**
		 * REQUIRED for pagination. Let's figure out what page the user is currently
		 * looking at. We'll need this later, so you should always include it in
		 * your own package classes.
		 */
		$current_page = $this->get_pagenum();

		/**
		 * REQUIRED for pagination. Let's check how many items are in our data array.
		 * In real-world use, this would be the total number of items in your database,
		 * without filtering. We'll need this later, so you should always include it
		 * in your own package classes.
		 */
		$total_items = count( $data );

		/**
		 * The WP_List_Table class does not handle pagination for us, so we need
		 * to ensure that the data is trimmed to only the current page. We can use
		 * array_slice() to
		 */
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		/**
		 * REQUIRED. Now we can add our *sorted* data to the items property, where
		 * it can be used by the rest of the class.
		 */
		$this->items = $data;

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,                  // WE have to calculate the total number of items.
				'per_page'    => $per_page,                     // WE have to determine how many items to show on a page.
				'total_pages' => ceil( $total_items / $per_page ),   // WE have to calculate the total number of pages.
			)
		);
	}
}
