<?php
/**
 * Define the internationalization functionality.
 * Loads and defines the internationalization files for this plugin
 *
 * @since      1.0.0
 * @package    Wpgsi
 * @subpackage Wpgsi/includes
 * @author     javmah <jaedmah@gmail.com>
 */

if(!class_exists('WP_List_Table')) require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

// Plugin class.
class Wpgsi_List_Table extends WP_List_Table {
    /**
	 * Common methods used in the all the classes 
	 * @since    3.5.0
	 * @var      object    $version    The current version of this plugin.
	*/	
    public $eventsAndTitles;

    /**
	 * Common methods used in the all the classes 
	 * @since    3.7.0
	 * @var      object    $version    The current version of this plugin.
	*/	
	public $common;

   /**
   * Construct function
   * Set default settings.
   */
    function __construct($eventsAndTitles, $common){
        global $status, $page;
        # Settings the value
        $this->eventsAndTitles = $eventsAndTitles;
        # Settings the value
        $this->common = $common;
        #Set parent defaults
        parent::__construct(array(
            'ajax'     => FALSE,
            'singular' => 'user',
            'plural'   => 'users',
        ));
    }
    
  /**
   * Renders the columns.
   * @since 1.0.0
   */
    public function column_default($item, $column_name){

        $post_excerpt = unserialize($item->post_excerpt);
        $post_content = '';

        switch ($column_name){
            case 'id':
                $value = $item->ID;
                break;
            case 'IntegrationTitle':
                $value = $item->post_title;
                break;
            case 'DataSource': 
                $value = $post_excerpt->Data_source;
                break;
            case 'worksheetName':
                $value = $post_excerpt->Worksheet;
                break;
            case 'WorksheetID':
                $value = $post_excerpt->Worksheet;
                break;
            case 'spreadsheetName':
                $value = $post_excerpt->Spreadsheet; 
                break;
            case 'SpreadsheetID':
                $value = '';
                break;
            case 'remoteTitles':
                $value = $post_excerpt->Worksheet;
                break;
            case 'relations':
                $value = $post_excerpt->Worksheet;
                break;
            case 'status':
                $value =  $item->post_status;
                break;
            default:
                $value = '--';
        }
    }

    /**
     * Retrieve the table columns.
     * @since 1.0.0
     * @return array $columns Array of all the list table columns.
    */
    public function get_columns(){
        $columns = array(
            'cb'                 => '<input type="checkbox" />',
            'IntegrationTitle'   => esc_html__('Title', 'wpgsi'),
            'DataSource'         => esc_html__('Data Source', 'wpgsi'),
            'Worksheet'          => esc_html__('Worksheet', 'wpgsi'),
            'Spreadsheet'        => esc_html__('Spreadsheet', 'wpgsi'),
            'Relations'          => esc_html__('ID : Column Title ⯈ Relations', 'wpgsi'),
            'status'             => esc_html__('Status', 'wpgsi')
        );
        return $columns;
    }

    # Render the checkbox column.
    public function column_cb($item) {
        return '<input type="checkbox" name="id[]" value="' . absint($item->ID) . '" />';
    }

    public function column_DataSource($item) {
        # Json decoding post excerpt || Excerpt are integration settings 
        $post_excerpt =@ json_decode($item->post_excerpt, true);
        # check and Balance 
        if (is_array($post_excerpt) AND  isset($post_excerpt['DataSource'])){
            # getting the Platform
            $IntegrationPlatform  =  get_post_meta($item->ID, "IntegrationPlatform", TRUE);
            # out putting the data
            if($IntegrationPlatform){
                return  $IntegrationPlatform . "<br><br>" . esc_attr($post_excerpt['DataSource']);
            } else {
                return  esc_attr($post_excerpt['DataSource']);
            }

        } else {
            _e("Not Set !" , "wpgsi");
            # keeping error log 
            $this->common->wpgsi_log(get_class($this), __METHOD__,"100", "ERROR: post_excerpt is not array or  DataSource is empty.");
        }
    }

    public function column_Worksheet($item){
        # json decoding post excerpt 
        $post_excerpt =@ json_decode($item->post_excerpt, true);
        # check and Balance 
        if(is_array($post_excerpt) AND  isset($post_excerpt['Worksheet'], $post_excerpt['WorksheetID'])){
            return  esc_attr($post_excerpt['Worksheet']) . "<br><br><i>" . esc_attr( $post_excerpt['WorksheetID'] ) . "</i>";
        } else {
            _e("Not Set !" , "wpgsi");
            # keeping the log 
            $this->common->wpgsi_log( get_class( $this ), __METHOD__,"101", "ERROR: post_excerpt is not array or  Worksheet or WorksheetID is not set!");
        }
    }

    public function column_Spreadsheet($item){
        # JSON decoding Post excerpt
        $post_excerpt =@ json_decode($item->post_excerpt, true);
        # check and Balance 
        if(is_array($post_excerpt) AND isset($post_excerpt['Spreadsheet'], $post_excerpt['SpreadsheetID'])){
            return esc_attr($post_excerpt['Spreadsheet']) . "<br><br><i>" . esc_attr($post_excerpt['SpreadsheetID']) . "</i>";
        } else {
            _e("Not Set !" , "wpgsi");
            # keeping the log 
            $this->common->wpgsi_log(get_class($this), __METHOD__,"102", "ERROR: post_excerpt is not array or Spreadsheet or SpreadsheetID is not set!");
        }
    }

    # .........................................................................
    # Working Here || Need To Change || Remove Empty Value array_filter()
    # Relations Output from DB
    # .........................................................................
    public function column_Relations($item){
        $string         = "";
        $DataSource     = json_decode($item->post_excerpt, true)['DataSourceID'];
        $ColumnTitles   = json_decode($item->post_content, true)[0];
        # ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        # Keep the Error in the Log 
        # Checking is Custom post_content data is Valid JSON AND didn't edited if edited and not valid return an empty array;
        $postContent  =@  json_decode($item->post_content, true);
        # empty check 
        if(is_array($postContent) AND $postContent){
            $Relations  = array_filter($postContent[1]);
        } else {
            # Creating error log string 
            $string     = "<b>ERROR: invalid JSON string. Please delete this integration & create new one.</b>";
            # keeping error log 
            $this->common->wpgsi_log(get_class( $this ), __METHOD__,"103", "ERROR: invalid JSON string. Please delete this integration & create new one.");
            # set relation array empty 
            $Relations  = array();
        }

        $data           = array();
        $eventsAndTitlesBracket = array();

        # Change The key to Bracketed 
        if(isset($this->eventsAndTitles[$DataSource])){
            foreach($this->eventsAndTitles[$DataSource] as $key => $value){
                $eventsAndTitlesBracket[ "{{". $key ."}}" ] = "<code><b>" . esc_attr($value) ."</b></code>";
            }
        }

        # replace the placeholder ;++
        $countRelations = count($Relations); 
        $i = 0 ;
        foreach($Relations as $key => $value){
            $i++ ;
            if($i == $countRelations){
                $string .= $key . " : " . esc_attr($ColumnTitles[ $key]) . " ⯈ " . strtr($value, $eventsAndTitlesBracket);
            } else {  
                $string .= $key . " : " . esc_attr($ColumnTitles[ $key]) . " ⯈ " . strtr($value, $eventsAndTitlesBracket) . "<br>";
            }
        }
        # return relation strings to the relation column
        return  $string;
    }

    # .........................................................................
    # Need some Update to this Place 
    # .........................................................................
    public function column_status($item){
        # if id and post_status is not set 
        if(!isset($item->ID, $item->post_status)){
            $this->common->wpgsi_log(get_class($this), __METHOD__,"104", "ERROR: ID  or Post status is not Set.");
        }
        # getting data source id 
        $DataSourceID     = json_decode($item->post_excerpt, true)['DataSourceID'];
        # if is set get the eventsAndTitles or set it as false
        if(isset($this->eventsAndTitles[$DataSourceID]) AND is_array($this->eventsAndTitles[$DataSourceID])){
            $eventsAndTitles = base64_encode(json_encode($this->eventsAndTitles[$DataSourceID]));
        } else {
            $eventsAndTitles = 'false';
        }
        # Integration status 
        if(isset($item->post_status) AND $item->post_status == 'publish'){
            $actions = "<br><span onclick='wpgsiChangeIntegrationStatus(" . esc_html($item->ID) . ")' title='enable or disable the integrations'> <input type='checkbox' name='status' checked=checked > </span>";
        } else {
            $actions = "<br><span onclick='wpgsiChangeIntegrationStatus(" . esc_html($item->ID) . ")' title='enable or disable the integrations'> <input type='checkbox' name='status' > </span>";
        }
        # Creating Sheet Column Title        
        $actions .= "<br><br><span onclick='wpgsiCreateSheetColumnTitles(" . esc_html($item->ID) . ",\"". $eventsAndTitles ."\")' title='Test Fire ! Please check your Google Spreadsheet for effects'><span class='dashicons dashicons-controls-repeat'></span></span>";
        # getting Data source ID 
        $DataSourceID  =@ json_decode($item->post_excerpt, true)['DataSourceID'];
        # check and Balance 
        if(! $DataSourceID){
            $this->common->wpgsi_log(get_class($this), __METHOD__,"105", "ERROR: DataSourceID is empty.");
        }
        # Integration Platform check 
        $IntegrationPlatform  =  get_post_meta($item->ID, "IntegrationPlatform", TRUE);
        # if empty show message 
        if(empty($IntegrationPlatform)){
            echo"<br> <span style='color: red;'>ERROR: Integration platform is not set, Open this on Edit and save it again.</span> <br>";
            $this->common->wpgsi_log(get_class($this), __METHOD__,"105", "ERROR: There is no Integration Platform saved. Please Open the Integration and re-save it again.");
        }
        # display update code if Platform is supported.
        if($IntegrationPlatform AND in_array($IntegrationPlatform, array('wpPost','wcProduct','wcOrder','wpUser','customPostType','database'))){
            # Enable and Disable Remote Sheet  remoteUpdate
            if(get_post_meta($item->ID, "remoteUpdateStatus", TRUE)){
                $actions .= "<br><br><span onclick='wpgsiChangeRemoteUpdateStatus(" . esc_html($item->ID) . ")' title='Enable or Disable Update from the Google Sheet' > <input type='checkbox' name='remoteUpdate' checked=checked > </span>";
            } else {
                $actions .= "<br><br><span onclick='wpgsiChangeRemoteUpdateStatus(" . esc_html($item->ID) . ")' title='Enable or Disable Update from the Google Sheet' > <input type='checkbox' name='remoteUpdate' > </span>";
            }
            # Remote Update help;
            $actions .= "<br><br><span title='Update from remote Google Sheet Help & code for this Integration.' onclick='window.location=\"admin.php?page=wpgsi&action=remoteUpdate&id=" . esc_html($item->ID) . " \"'  class='a_remoteUpdate_checkbox'> <span class='dashicons dashicons-database-import'></span> </span>";
        }
        # return the icons and text
        return $actions;
    }

    # .........................................................................
    # Render the form name column with action links.
    # .........................................................................
    public function column_IntegrationTitle($item){
        $name = ! empty($item->post_title) ? $item->post_title  : '--';
        $name = sprintf('<span><strong>%s</strong></span>', esc_html__($name));
        # Build all of the row action links.
        $row_actions = array();
        # Edit.
        $row_actions['edit'] = sprintf(
            '<a href="%s" title="%s">%s</a>',
            add_query_arg(
                array(
                    'action' => 'edit',
                    'id'     => $item->ID,
                ),
                admin_url('admin.php?page=wpgsi')
            ),
            esc_html__('Edit This Relation', 'wpgsi'),
            esc_html__('Edit', 'wpgsi')
        );

        # Delete.
        $row_actions['delete'] = sprintf(
            '<a href="%s" class="relation-delete" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(
                    array(
                        'action' => 'delete',
                        'id'     => $item->ID,
                    ),
                    admin_url('admin.php?page=wpgsi')
                ),
                'wpgsi_delete_relation_nonce'
            ),
            esc_html__('Delete this relation', 'wpgsi'),
            esc_html__('Delete', 'wpgsi')
        );

        # Build the row action links and return the value.
        return $name . $this->row_actions(apply_filters('fts_relation_row_actions', $row_actions, $item));
    }

    # .........................................................................
    # Define bulk actions available for our table listing.
    # .........................................................................
    public function get_bulk_actions(){
        $actions = array(
            'delete' => esc_html__('Delete', 'wpgsi'),
        );
        return $actions;
    }

    # .........................................................................
    # Process the bulk actions. # This Function Should be Remove || Use wpgsi_delete_connection function in wpgsi admin class
    # .........................................................................
    public function process_bulk_actions(){
        # getting the ids
        $ids = isset($_GET['id']) ? $_GET['id'] : array();
        # security and ID Check 
        if($this->current_action() == 'delete' && wp_verify_nonce($_GET['wpgsi_nonce'], 'wpgsi_nonce_bulk_action') && !empty($ids)){
            # Loop the Ids
            foreach($ids as $id){
                wp_delete_post($id);
            }
            # Caching the integrations 
            $integrations = $this->common->wpgsi_getIntegrations();
            if($integrations[0]){
                # setting or updating the transient;
                set_transient('wpgsi_integrations', $integrations[1]);
            }
        }
    }

    # .........................................................................
    # Message to be displayed when there are no relations.
    # .........................................................................
    public function no_items(){
        printf(
            wp_kses(
                __('Whoops, you haven\'t created a relation yet. Want to <a href="%s">give it a go</a>?', 'wpgsi'),
                array(
                    'a' => array(
                        'href' => array(),
                    ),
                )
            ),
            admin_url('admin.php?page=wpgsi&action=new')
        );
    }

    # .........................................................................
    # Sortable settings.
    # .........................................................................
    public function get_sortable_columns(){
        return array(
            'IntegrationTitle'       => array('IntegrationTitle', TRUE),
            'data_source'            => array('data_source', TRUE),
            'spreadsheetsAndProvider'=> array('spreadsheetsAndProvider', TRUE),
        );
    }

    # .........................................................................
    # Fetching Data from Database 
    # .........................................................................
    public function fetch_table_data(){
        return get_posts(array( 
            'post_type'     => 'wpgsiIntegration',
            'post_status'   => 'any', 
            'posts_per_page'=> -1 ,
        )); 
    }

    # Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
    public function prepare_items(){
        # Process bulk actions if found.
        $this->process_bulk_actions();
        # Defining Values
        $per_page              = 20;
        $count                 = $this->count();
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $table_data            = $this->fetch_table_data();
        $this->items           = $table_data;
        $this->admin_header();

        $this->set_pagination_args(
            array(
                'total_items' => $count,
                'per_page'    => $per_page,
                'total_pages' => ceil($count / $per_page),
            )
        );
    }

    # .........................................................................
    # Count Items for Pagination 
    # .........................................................................
    public function count(){
        $wpgsi_posts = get_posts(array( 
            'post_type'     => 'wpgsiIntegration',
            'post_status'   => 'any',
            'posts_per_page'=> -1,
        )); 
        return count($wpgsi_posts);
    }

    /**
	 * Check this Function! may be useless 
	 * @since      3.4.0
	 * @return     array   	 This Function Will return an array 
	*/
    public function admin_header(){
        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        # if another page redirect user;
        if('wpgsi' != $page){
            return;
        }
        # Table style setting 
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 10%; }';
        echo '.wp-list-table .column-IntegrationTitle { width: 10%; }';
        echo '.wp-list-table .column-DataSource { width: 15%; }';
        echo '.wp-list-table .column-Worksheet { width: 15%; }';
        echo '.wp-list-table .column-Spreadsheet { width: 20%; }';
        echo '.wp-list-table .column-Relations { width: 25%; }';
        echo '.wp-list-table .column-status { width: 5%; }';
        echo '</style>';
        ?>
        <script>
            //  For enable or disable
            function wpgsiChangeIntegrationStatus(integrationID){
                if( typeof integrationID === 'number' ){
                    console.log("AJAX request init for integration status change.");
                    // AJAX Data 
                    var data = {
                        "action" 	    : 'wpgsi_changeIntegrationStatus',
                        "integrationID" : integrationID
                    };
                    // Request Object 
                    let request = new XMLHttpRequest();
                    request.onreadystatechange = function(){
                        if(request.readyState == XMLHttpRequest.DONE){
                            // Response data
                            console.log(request.responseText);
                        }
                    }
                    var ajaxURL = "<?php echo admin_url('admin-ajax.php') ?>";
                    // Opening request and Initiating the request 
                    request.open('POST', ajaxURL, true);
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    request.send(new URLSearchParams(data).toString());
                } else {
                    alert("ERROR : integrationID is not a number. " + integrationID );
                }
            }
            //  For creating google sheet column title 
            function wpgsiCreateSheetColumnTitles(integrationID, eventsAndTitles){
                if( typeof integrationID === 'number' ){
                    console.log("AJAX request init for for create sheet ColumnTitles");
                    // AJAX Data 
                    var data = {
                        "action" 	      : 'wpgsi_createSheetColumnTitles',
                        "integrationID"   : integrationID,
                        "eventsAndTitles" : eventsAndTitles,
                    };
                    // Request Object 
                    let request = new XMLHttpRequest();
                    request.onreadystatechange = function(){
                        if(request.readyState == XMLHttpRequest.DONE){
                            // Response data
                            console.log(request.responseText);
                        }
                    }
                    var ajaxURL = "<?php echo admin_url('admin-ajax.php') ?>";
                    // Opening request and Initiating the request 
                    request.open('POST', ajaxURL, true);
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    request.send(new URLSearchParams(data).toString());
                } else {
                    alert("ERROR : integrationID is not a number. " + integrationID );
                }
            }
            //  For remote update enable or disable 
            function wpgsiChangeRemoteUpdateStatus(integrationID){
                if( typeof integrationID === 'number' ){
                    console.log("AJAX request init for remote update status change.");
                    // AJAX Data 
                    var data = {
                        "action" 	    : 'wpgsi_changeRemoteUpdateStatus',
                        "integrationID" : integrationID
                    };
                    // Request Object 
                    let request = new XMLHttpRequest();
                    request.onreadystatechange = function(){
                        if(request.readyState == XMLHttpRequest.DONE){
                            // Response data
                            console.log(request.responseText);
                        }
                    }
                    var ajaxURL = "<?php echo admin_url('admin-ajax.php') ?>";
                    // Opening request and Initiating the request 
                    request.open('POST', ajaxURL, true);
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    request.send(new URLSearchParams(data).toString());
                } else {
                    alert("ERROR : integrationID is not a number. " + integrationID );
                }
            }
        </script>
        <?php
    }
}
