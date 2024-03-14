<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Newsletter List Page
 *
 * The html markup for the newsletter list
 * 
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class Eclg_Subscribers_List extends WP_List_Table {

    public $per_page;
	
	public function __construct(){
	            
        //Set parent defaults
        parent::__construct( array(
		            'singular'  => 'eclg_id',     //singular name of the listed records
		            'plural'    => 'eclg_ids',    //plural name of the listed records
		            'ajax'      => false        //does this table support ajax?
		        ) );

        $this->per_page = apply_filters( 'eclg_subscribers_list_per_page', 10 );
    }
   


    /**
	 * Displaying eclg data
	 *
	 * Does prepare the data for displaying the eclg in the table.
	 *
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
	 */	
	public function display_newsletter_users() {

        global $wpdb;
		
		//if search is call then pass searching value to function for displaying searching values
		$search_title = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
		
        // Table name to get data
        $table_name = $wpdb->prefix . 'eclg_subscribers';

        // Create query
        $query = "SELECT * FROM $table_name WHERE 1 = 1";

        // Serch parameter
        if( isset($_GET['s']) ) {
        	$query .= " AND first_name LIKE '%".sanitize_text_field($_GET['s'])."%' OR last_name LIKE '%".sanitize_text_field($_GET['s'])."%'";
        }

		// Get total count
		$count = $wpdb->get_results( $query , 'ARRAY_A' );

		// Order parameter
		$limit =  $this->per_page;

		$offset = 0;
		if( isset($_GET['paged']) ){
			$offset = absint($_GET['paged']) * $limit - $limit;
		}

		$query .= " ORDER BY date desc LIMIT $limit OFFSET $offset" ;

		$result = $wpdb->get_results( $query , 'ARRAY_A' );

		$data['data'] = $result;
		$data['total'] = count($count);

		return $data;
	}
	
	/**
	 * Mange column data
	 *
	 * Default Column for listing table
	 * Does to add the column to the listing page
	 * column name must be same as in function {get_columns} 
	 * 
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
	 */
	public function column_default( $item, $column_name ){
	
        switch( $column_name ){
            case 'first_name':
            case 'last_name':
                return !empty( $item[$column_name] ) ? esc_html($item[$column_name]) : __( ' - ', 'email-capture-lead-generation' );
                break;
            case 'email':
                return esc_html($item[$column_name]);
                break;
            case 'date':

                // getting date and time format from general settings
                $date_format = get_option( 'date_format' );
                $time_format = get_option( 'time_format' );

				return date_i18n( $date_format. ' '. $time_format, strtotime(esc_html($item[$column_name])) );
                break;
			default:
                do_action( 'eclg_subscribers_list_column_value', $column_name, $item );
				break;
        }
    }
	
    
    /**
     * Manage Delete Link
     * 
     * Action url for delete subscribers
     * 
     * @package Email Capture & Lead Generation
 	 * @since 1.0.0
	 */
	public function column_disc_title($item){

	    //Build row actions
		$actions = array(
			'delete'    => sprintf('<a href="?page=%s&action=%s&eclg_ids[]=%s">'.__('Delete', 'email-capture-lead-generation').'</a>',esc_attr($_REQUEST['page']),'delete',esc_attr($item['id']))
			);

	    //Return the title contents	        
		return sprintf('%1$s %2$s',
			/*$1%s*/ $item['first_name'],
			/*$2%s*/ $this->row_actions($actions)
			);
	}
    
    /**
     * Add Check boxes in Listing Table
     * 
     * Does to adding checkboxes for bulk action into the listing page table
     * 
     * Note: Dont change name cb, else checkall functionality wont work and design get distrubed.
     * 
     * @package Email Capture & Lead Generation
     * @since 1.0.0
     */
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     *
     * Handles to show the minimum columns into the table 
     * 
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
     */
	public function get_columns(){
	
		$columns = array(
						'cb'			=> '<input type="checkbox" />', //Render a checkbox instead of text
						'email'			=> __( 'Email', 'email-capture-lead-generation' ),
                        'first_name'    => __( 'First Name', 'email-capture-lead-generation' ),
                        'last_name'     => __( 'Last Name', 'email-capture-lead-generation' ),
						'date'			=> __( 'Created Date', 'email-capture-lead-generation' )
					);
        return $columns;
    }
	
    /**
     * Sortable Columns
     *
     * Handles sortable column in list table 
     * it will automatically manage ascending and descending functionality of table
     * 
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
     */
	public function get_sortable_columns() {
	
        /*$sortable_columns = array(
								'first_name'	=> array( 'first_name', true ),    
								'last_name'		=> array( 'last_name', true ),
								'email'			=> array( 'email', true ),
								'date'			=> array( 'date', true )
						);*/
        return array();
    }

	/**
	 * No items
	 * 
	 * Handles the message when no records available in table
	 * 
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
	 */
	public function no_items() {
		_e( 'No newsletter found.', 'email-capture-lead-generation' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
     * 
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
     */
	public function get_bulk_actions() {
        $actions = array(
		            'delete'    => 'Delete'
		        );
        return $actions;
    }
    
    /**
     * Process Bulk actions
     *
     * Handles Process of bulk action which is call on bulk action
     * 
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
     */
	public function process_bulk_action() {
    
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
        	wp_die(__( 'Subscriber deleted successfully.', 'email-capture-lead-generation' ));
        } 
        
    }
	
    /**
     * Prepare Items 
     *
     * Does prepare all our data to show into the page
     * 
	 * @package Email Capture & Lead Generation
	 * @since 1.0.0
     */
	public function prepare_items() {
        
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = $this->per_page;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
         /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        //$this->process_bulk_action();
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
		$data = $this->display_newsletter_users();
                
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
		$total_items = isset( $data['total'] ) ? esc_html($data['total']) : '';

		/**
		 * The WP_List_Table class does not handle pagination for us, so we need
		 * to ensure that the data is trimmed to only the current page. We can use
		 * array_slice() to 
		 */
		// $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
		$data = $data;

		/**
		 * REQUIRED. Now we can add our *sorted* data to the items property, where 
		 * it can be used by the rest of the class.
		 */
		$this->items = $data['data'];

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
			'total_items'	=> $total_items,					//WE have to calculate the total number of items
			'per_page'		=> $per_page,						//WE have to determine how many items to show on a page
			'total_pages'	=> ceil( $total_items / $per_page )	//WE have to calculate the total number of pages
		) );
	}
}

//Create an instance of our package class...
$NewsletterListTable = new Eclg_Subscribers_List();

//Fetch, prepare, sort, and filter our data...
$NewsletterListTable->prepare_items(); 

?>


<div class="wrap">	
	
	<?php 
	$html = '';
	if( !empty($_GET['message']) ) {
		if( $_GET['message'] == '3' ) {
			$html .= '<div class="updated" id="message">
			<p><strong>'.__( 'Subscribers deleted successfully.', 'email-capture-lead-generation' ).'</strong></p>
			</div>'; 
		}
	}
	echo $html; ?>
	
	<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="newsletter-filter" method="get">
		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
		<h1 class="wp-heading-inline"><?php _e( 'Signup Users', 'email-capture-lead-generation' ); ?></h1>
		<input type="submit" name="export" class="page-title-action" value="Export CSV" />
		
		<input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
		<!-- Search User -->
		<?php $NewsletterListTable->search_box( __( 'Search User', 'email-capture-lead-generation' ), 'eclg_ltable_search' ); ?>
		<!-- Now we can render the completed list table -->
		<?php $NewsletterListTable->display(); ?>

	</form>
</div>