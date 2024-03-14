<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Snippets List Page
 *
 * The html markup for the snippet list
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class ECSnippets_Snippets_List extends WP_List_Table {

    public $per_page;
	
	public function __construct(){
	            
        //Set parent defaults
        parent::__construct( array(
            'singular'	=> 'form',     //singular name of the listed records
            'plural'	=> 'forms',    //plural name of the listed records
            'ajax'		=> false        //does this table support ajax?
        ) );

        $this->per_page = apply_filters( 'ecsnippets_snippets_list_per_page', 10 );
    }

    /**
	 * Displaying Snippet data
	 *
	 * Does prepare the data for displaying the newsletter in the table.
	 */	
	public function display_ecsnippets_snippets() {

        global $wpdb;
		
		//if search is call then pass searching value to function for displaying searching values
		$search_title = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';
		
        // Table name to get data
        $table_name = $wpdb->prefix . 'ecs_snippets';

        // Create query
        $query = "SELECT * FROM $table_name WHERE 1 = 1";

        // Serch parameter
        if( isset($_GET['s']) ) {
        	$query .= " AND title LIKE '%".sanitize_text_field($_GET['s'])."%' OR position LIKE '%".sanitize_text_field($_GET['s'])."%'";
        }

		// Get total count
		$count = $wpdb->get_results( $query , 'ARRAY_A' );

		// Order parameter
		$limit =  $this->per_page;

		$offset = 0;
		if( isset($_GET['paged']) ){
			$offset = $_GET['paged'] * $limit - $limit;
		}

		$orderby = isset( $_GET['orderby'] ) ? esc_attr( $_GET['orderby'] ) : 'date';
		if( !empty($orderby) ) {
			$query .= " ORDER BY ".$orderby." " ;
		}

		$order   = isset( $_GET['order'] ) ? esc_attr( $_GET['order'] ) : 'desc';
		if( !empty($order) ) {
			$query .=  $order;
		}

		$query .= " LIMIT $limit OFFSET $offset" ;
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
	 */
	public function column_default( $item, $column_name ){
	
		switch( $column_name ){

			case 'ID':
				return '#'.$item[$column_name];
			break;

			case 'title':
			case 'position':
				return !empty( $item[$column_name] ) ? esc_html($item[$column_name]) : __( ' - ', 'ecsnippets' );
			break;

			case 'date':
				// getting date and time format from general settings
				$date_format = get_option( 'date_format' );
				$time_format = get_option( 'time_format' );

				return date_i18n( $date_format. ' '. $time_format, strtotime(esc_html($item[$column_name])) );
			break;

			case 'actions':
				$edit_url = add_query_arg( array(
					'page' => 'ecsnippets-snippets',
					'action' => 'edit-snippet', 
					'form_id' => $item['ID']
				), admin_url('admin.php') );

				$delete_url = add_query_arg( array(
					'page' => 'ecsnippets-snippets',
					'action' => 'delete', 
					'form[]' => $item['ID']
				), admin_url('admin.php') );

				echo '<a href="'.esc_url($edit_url).'" class="button edit"><i class="dashicons dashicons-edit"></i></a> ';

				echo '<a href="'.esc_url($delete_url).'" class="button delete button-danger"><i class="dashicons dashicons-trash"></i></a>';
			break;

			default:
				do_action( 'ecsnippets_smippets_list_column_value', $column_name, $item );
			break;
		}
    }
	
    /**
     * Manage Delete Link
	 */
	public function column_disc_title($item){

	    //Build row actions
		$actions = array(
			'delete'    => sprintf('<a href="?page=%s&action=%s&snippet[]=%s">'.__('Delete', 'ecsnippets').'</a>',$_REQUEST['page'],'delete',$item['ID'])
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
     */
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     *
     * Handles to show the minimum columns into the table 
     */
	public function get_columns(){
	
		$columns = array(
			'cb'			=> '<input type="checkbox" />', //Render a checkbox instead of text
			'ID'			=> esc_html__( 'ID', 'ecsnippets' ),
			'title'			=> esc_html__( 'Title', 'ecsnippets' ),
			'position'		=> esc_html__( 'Position', 'ecsnippets' ),
			'date'			=> esc_html__( 'Created Date', 'ecsnippets' ),
			'actions'		=> esc_html__( 'Actions', 'ecsnippets' )
		);
        return $columns;
    }

	/**
     * Sortable Columns
     *
     * Handles Sortable Columns action
     */
	public function get_sortable_columns() {
		$sortable_columns = array(
		    'ID'        => array('ID',true),
		    'position'  => array('position',true),
		    'date'      => array('date',true)
		);
        return $sortable_columns;
    }

	/**
	 * No items
	 * 
	 * Handles the message when no records available in table
	 */
	public function no_items() {
		_e( 'No snippet found.', 'ecsnippets' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
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
     */
	public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
        	wp_die(__( 'Snippets deleted successfully.', 'ecsnippets' ));
        } 
    }

	
    /**
     * Prepare Items 
     *
     * Does prepare all our data to show into the page
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
		$data = $this->display_ecsnippets_snippets();
                
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
$SnippetsListTable = new ECSnippets_Snippets_List();

//Fetch, prepare, sort, and filter our data...
$SnippetsListTable->prepare_items();  ?>

<div class="wrap">
	<?php 
	$html = '';
	if( !empty($_GET['message']) ) {
		if( $_GET['message'] == '10' ) {
			$html .= '<div class="updated" id="message">
			<p><strong>'.__( 'Snippet deleted successfully.', 'ecsnippets' ).'</strong></p>
			</div>'; 
		}

		if( $_GET['message'] == '11' ) {
			$html .= '<div class="updated" id="message">
			<p><strong>'.__( 'Snippet added successfully!', 'ecsnippets' ).'</strong></p>
			</div>'; 
		}

		if( $_GET['message'] == '12' ) {
			$html .= '<div class="error settings-error" id="setting-error-"><p><strong>'.__( 'You can add maximun five snippets, to add more please get pro version. Please contact at alphabposervice@gmail.com to get pro version.', 'ecsnippets' ).'</strong></p></div>';
		}
	}
	echo $html; ?>
	
	<!-- Snippets are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="snippet-filter" method="get">
		<!-- For plugins, we also need to ensure that the snippet posts back to our current page -->
		<h1 class="wp-heading-inline">
			<?php _e( 'Easy Code Snippets', 'ecsnippets' ); ?>
			<?php
			global $wpdb;
			// Check if more then 5 record exits.
			$count_query = "SELECT count(*) FROM {$wpdb->prefix}ecs_snippets";
			$total_snippet = $wpdb->get_var($count_query);
			if( isset($_GET['page']) && $_GET['page'] == 'ecsnippets-snippets' && $total_snippet >= '5' ) {
				$error_message = '<div class="error settings-error" id="setting-error-"><p><strong>'.__( 'You can add maximun five snippets, to add more please get pro version. Please contact at alphabposervice@gmail.com to get pro version.', 'ecsnippets' ).'</strong></p></div>';
				echo $error_message;
			} else {
				$new_snippet_Url = add_query_arg( array(
					'page' => 'ecsnippets-snippets',
					'action' => 'add-new-snippet'
				) ); ?>
				<a href="<?php echo esc_url($new_snippet_Url); ?>" class="page-title-action">
					<?php _e( 'Add New Snippet', 'ecsnippets' ); ?>
				</a>
				<?php
			} ?>
		</h1>
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<!-- Search Snippets -->
		<?php $SnippetsListTable->search_box( __( 'Search Snippets', 'ecsnippets' ), 'ecsnippets_ltable_search' ); ?>
		<!-- Now we can render the completed list table -->
		<?php $SnippetsListTable->display(); ?>
	</form>
</div>