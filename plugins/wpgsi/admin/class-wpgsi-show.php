<?php

/**
 * This Class is Responsible for Displaying the All the Google Sheet to User selected PAGE or POST 
 * This call  has Dependence in googleSheet class for Token Generation 
 * @since      3.7.3
 * @package    Wpgsi
 * @subpackage Wpgsi/includes
 * @author     javmah <jaedmah@gmail.com>
 */
class Wpgsi_Show
{
    /**
     * Events Children titles .
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    private  $plugin_name ;
    /**
     * Events Children titles .
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    private  $version ;
    /**
     * Events Children titles.
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public  $googleSheet ;
    /**
     * Common methods used in the all the classes 
     * @since    3.7.3
     * @var      object    $version    The current version of this plugin.
     */
    public  $adminClass ;
    /**
     * Common methods used in the all the classes 
     * @since    3.6.0
     * @var      object    $version    The current version of this plugin.
     */
    public  $common ;
    /**
     * Sync Frequency array
     * @since    3.7.3
     * @var      object    $version    The current version of this plugin.
     */
    public  $syncFrequency = array(
        "manually"         => "sync Google sheet Manually",
        "everyTwoHours"    => "Sync Google sheet every 2 hours",
        "everyThreeHours"  => "Sync Google sheet every 3 hours",
        "everyFiveHours"   => "Sync Google sheet every 5 hours",
        "everySevenHours"  => "Sync Google sheet every 7 hours",
        "everyTwelveHours" => "Sync Google sheet every 12 hours",
        "everyDay"         => "Sync Google sheet every day",
        "everyTwoDay"      => "Sync Google sheet every 2 day",
        "everyThreeDay"    => "Sync Google sheet every 3 day",
        "everyFiveDay"     => "Sync Google sheet every 5 day",
        "everyWeek"        => "Sync Google sheet every week",
    ) ;
    /**
     * Class Constrictors. Setting the class Variables
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public function __construct(
        $plugin_name,
        $version,
        $googleSheet,
        $adminClass,
        $common
    )
    {
        # Plugin Name
        $this->plugin_name = $plugin_name;
        # WPGSI version
        $this->version = $version;
        # Events
        $this->googleSheet = $googleSheet;
        # Events
        $this->adminClass = $adminClass;
        # Events
        $this->common = $common;
    }
    
    /**
     * This is a Admin notification function 
     * This Will use for test and Debug 
     * @since    	 3.7.3
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_show_enqueue_scripts()
    {
        
        if ( get_current_screen()->id == 'spreadsheet-integrations_page_wpgsi-show' ) {
            # Adding script
            wp_register_script(
                'vue',
                plugin_dir_url( __FILE__ ) . 'js/vue.js',
                '',
                FALSE,
                FALSE
            );
            # our custom js code
            wp_enqueue_script(
                'wpgsi-show',
                plugin_dir_url( __FILE__ ) . 'js/wpgsi-show.js',
                array( 'vue' ),
                '0.1',
                TRUE
            );
            # check its edit or not
            $selectedSpreadSheetWorkSheet = "";
            # check and Balance
            
            if ( isset( $_GET['action'], $_GET['id'] ) and ($_GET['action'] == 'edit' and is_numeric( $_GET['id'] )) ) {
                # spreadsheetID
                $spreadsheetID = get_post_meta( esc_html( $_GET['id'] ), 'spreadsheetID', true );
                # getting sheet ID
                $worksheetID = get_post_meta( esc_html( $_GET['id'] ), 'worksheetID', true );
                # encoding
                $selectedSpreadSheetWorkSheet = base64_encode( json_encode( [ $spreadsheetID, $worksheetID ] ) );
            }
            
            # getting google worksheetID
            $showNumberOfRows = ( (isset( $_GET['action'], $_GET['id'] ) and get_post_meta( esc_html( $_GET['id'] ), 'worksheetID', true )) ? get_post_meta( esc_html( $_GET['id'] ), 'showNumberOfRows', true ) : 10 );
            # getting google sheet  syncFrequency
            $syncFrequency = ( (isset( $_GET['action'], $_GET['id'] ) and get_post_meta( esc_html( $_GET['id'] ), 'syncFrequency', true )) ? get_post_meta( esc_html( $_GET['id'] ), 'syncFrequency', true ) : 'manually' );
            #
            $disableColumns = ( (isset( $_GET['action'], $_GET['id'] ) and get_post_meta( esc_html( $_GET['id'] ), 'disableColumns', true )) ? get_post_meta( esc_html( $_GET['id'] ), 'disableColumns', true ) : "" );
            # Preparing data to send on the Frontend
            $showData = array(
                "googleSheetsDetails"          => ( $this->googleSheet->wpgsi_spreadsheetsAndWorksheets()[0] ? json_encode( $this->googleSheet->wpgsi_spreadsheetsAndWorksheets()[1] ) : '[0, "ERROR: Hmm error"]' ),
                "selectedSpreadSheetWorkSheet" => $selectedSpreadSheetWorkSheet,
                "showNumberOfRows"             => $showNumberOfRows,
                "syncFrequency"                => $syncFrequency,
                "disableColumns"               => $disableColumns,
                "wpgsiAJAXurl"                 => admin_url( 'admin-ajax.php' ),
                'nonce'                        => wp_create_nonce( 'wpgsi_show_nonce' ),
            );
            # Localizing data
            wp_localize_script( 'wpgsi-show', 'showData', $showData );
        }
    
    }
    
    /**
     * This is a Admin notification function 
     * This Will use for test and Debug 
     * @since    	 3.7.3
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_show_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * This is a Admin notification function 
     * This Will use for test and Debug 
     * @since    	1.0.0
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_show_menu()
    {
        add_submenu_page(
            'wpgsi',
            __( 'Show Google Sheet as a table in Post or Page', 'wpgsi' ),
            __( 'Show Google Sheet as a table in Post or Page', 'wpgsi' ),
            'manage_options',
            'wpgsi-show',
            array( $this, 'wpgsi_show_view' )
        );
    }
    
    /**
     * This is a Admin notification function 
     * This Will use for test and Debug 
     * @since    	 3.7.3
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_show_view()
    {
        # URL param
        $action = ( isset( $_GET['action'] ) && !empty($_GET['action']) ? sanitize_text_field( $_GET['action'] ) : false );
        # ID
        $id = ( isset( $_GET['id'] ) && !empty($_GET['id']) ? sanitize_text_field( $_GET['id'] ) : false );
        # routing
        
        if ( $action == 'new' ) {
            # for add new show or edit show view
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wpgsi-showClass-newAndEditShowView.php';
        } else {
            
            if ( $action == 'edit' and !empty($id) ) {
                # Edit the things
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wpgsi-showClass-newAndEditShowView.php';
            } else {
                
                if ( $action == 'delete' and !empty($id) ) {
                    # Delete the custom show post
                    wp_delete_post( $id );
                    # keep a log
                    $this->common->wpgsi_log(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "SUCCESS: WPGSI a table show is deleted. ID " . $id
                    );
                    # redirecting
                    wp_redirect( 'admin.php?page=wpgsi-show&msg=success' );
                } else {
                    
                    if ( $action == 'status' and !empty($id) ) {
                        # check the Post type status
                        
                        if ( get_post( $id )->post_status == 'publish' ) {
                            $post = array(
                                'ID'          => $id,
                                'post_status' => 'pending',
                            );
                        } else {
                            $post = array(
                                'ID'          => $id,
                                'post_status' => 'publish',
                            );
                        }
                        
                        # Keeping Log
                        $this->common->wpgsi_log(
                            get_class( $this ),
                            __METHOD__,
                            "200",
                            "SUCCESS: ID " . $id . " display status  change to ." . get_post( $id )->post_status
                        );
                        # redirect
                        ( wp_update_post( $post ) ? wp_redirect( admin_url( 'admin.php?page=wpgsi-show&msg=success' ) ) : wp_redirect( admin_url( '/admin.php?page=wpgsi-show&msg=fail' ) ) );
                    } else {
                        
                        if ( $action == 'sync' and !empty($id) ) {
                            # getting spreadsheet ID
                            $spreadsheetID = sanitize_text_field( get_post_meta( $id, 'spreadsheetID', true ) );
                            # getting worksheet Name
                            $worksheetName = strip_tags( get_post_meta( $id, 'worksheetName', true ) );
                            # getting disable Columns
                            $disableColumns = array();
                            # Get the Google  Sheet ID and worksheet name
                            
                            if ( empty($spreadsheetID) or empty($worksheetName) ) {
                                # keeping log
                                $this->common->wpgsi_log(
                                    get_class( $this ),
                                    __METHOD__,
                                    "901",
                                    "ERROR: spreadsheetID or worksheetName is empty."
                                );
                                # redirect
                                wp_redirect( 'admin.php?page=wpgsi-show&msg=spreadsheetIDorWorksheetNameEmpty' );
                            }
                            
                            # Download the information from Google sheet
                            $googleWorksheetData = $this->googleSheet->wpgsi_googleWorksheetData( $worksheetName, $spreadsheetID, $disableColumns );
                            # check and Balance
                            
                            if ( !$googleWorksheetData[0] or !is_array( $googleWorksheetData[1] ) ) {
                                # keeping log
                                $this->common->wpgsi_log(
                                    get_class( $this ),
                                    __METHOD__,
                                    "902",
                                    "ERROR: Google sheet is empty or returning false."
                                );
                                # redirect
                                wp_redirect( 'admin.php?page=wpgsi-show&msg=googleWorksheetDataError' );
                                exit;
                            }
                            
                            # Now update
                            $r = wp_update_post( array(
                                'ID'            => $id,
                                'post_content'  => ( ($googleWorksheetData[0] and !empty($googleWorksheetData[1])) ? addslashes( json_encode( $googleWorksheetData[1] ) ) : "" ),
                                'post_modified' => current_time( 'mysql' ),
                                'meta_input'    => array(
                                'lastSyncTime' => $this->site_date_time(),
                            ),
                            ) );
                            #
                            
                            if ( $r ) {
                                # keeping log
                                $this->common->wpgsi_log(
                                    get_class( $this ),
                                    __METHOD__,
                                    "200",
                                    "SUCCESS: Google sheet is synced of ID {$id}"
                                );
                                # redirect depends on returns . update the last update number
                                wp_redirect( admin_url( 'admin.php?page=wpgsi-show&msg=success' ) );
                            } else {
                                # keeping log
                                $this->common->wpgsi_log(
                                    get_class( $this ),
                                    __METHOD__,
                                    "903",
                                    "ERROR: Google sheet synced Failed of ID {$id}"
                                );
                                # redirect depends on returns . update the last update number
                                wp_redirect( admin_url( 'admin.php?page=wpgsi-show&msg=error' ) );
                            }
                        
                        } else {
                            # notification
                            $credential = get_option( 'wpgsi_google_credential', FALSE );
                            # Creating view Page layout
                            echo  "<div class='wrap'>" ;
                            # if credentials is empty; Show this message to create credential.
                            
                            if ( !$credential ) {
                                echo  "<div class='notice notice-warning inline'>" ;
                                echo  "<p> Please integrate Google APIs & Service Account before creating new connection. Get <code><b><a href=" . admin_url( 'admin.php?page=wpgsi-settings&action=service-account-help' ) . " style='text-decoration: none;'> step-by-step</a></b></code> help. This plugin will not work without Google APIs & Service Account. </p>" ;
                                echo  "</div>" ;
                            }
                            
                            echo  "</div>" ;
                            # for show landing page !
                            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wpgsi-showClass-landingView.php';
                        }
                    
                    }
                
                }
            
            }
        
        }
    
    }
    
    /**
     * This is a Admin notification function 
     * This Will use for test and Debug 
     * @since    	3.7.3
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_save_google_show()
    {
        # check set or not
        $spreadsheetID = ( isset( $_POST['spreadsheetID'] ) && !empty($_POST['spreadsheetID']) ? sanitize_text_field( $_POST['spreadsheetID'] ) : false );
        $spreadsheetName = ( isset( $_POST['spreadsheetName'] ) && !empty($_POST['spreadsheetName']) ? sanitize_text_field( $_POST['spreadsheetName'] ) : false );
        $worksheetID = ( isset( $_POST['worksheetID'] ) && !is_null( $_POST['worksheetID'] ) ? sanitize_text_field( $_POST['worksheetID'] ) : false );
        $worksheetName = ( isset( $_POST['worksheetName'] ) && !empty($_POST['worksheetName']) ? strip_tags( $_POST['worksheetName'] ) : false );
        $showNumberOfRows = ( isset( $_POST['showNumberOfRows'] ) && !empty($_POST['showNumberOfRows']) ? sanitize_text_field( $_POST['showNumberOfRows'] ) : 10 );
        $syncFrequency = ( isset( $_POST['syncFrequency'] ) && !empty($_POST['syncFrequency']) ? sanitize_text_field( $_POST['syncFrequency'] ) : 'manually' );
        $editID = ( isset( $_POST['editID'] ) && !empty($_POST['editID']) ? sanitize_text_field( $_POST['editID'] ) : false );
        # empty Holder
        $disableColumns = array();
        # checking nonce
        
        if ( !wp_verify_nonce( $_POST['nonce'], 'wpgsi-googleShow-nonce' ) ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "904",
                "ERROR: Nonce Verification Failed."
            );
            # redirecting
            wp_redirect( 'admin.php?page=wpgsi-show&msg=nonceVerificationFailed' );
            exit;
        }
        
        # checking spreadsheet or work sheet is empty or not;
        
        if ( !$spreadsheetID ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "905",
                "ERROR: spreadsheetID empty."
            );
            # redirecting
            wp_redirect( 'admin.php?page=wpgsi-show&msg=spreadsheetID' );
            exit;
        }
        
        # checking spreadsheet or work sheet is empty or not;
        
        if ( !$spreadsheetName ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "906",
                "ERROR: spreadsheetName empty."
            );
            # redirecting
            wp_redirect( 'admin.php?page=wpgsi-show&msg=spreadsheetName' );
            exit;
        }
        
        # checking spreadsheet or work sheet is empty or not;
        
        if ( is_null( $worksheetID ) ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "907",
                "ERROR: worksheetID empty."
            );
            # redirecting
            wp_redirect( 'admin.php?page=wpgsi-show&msg=worksheetID' );
            exit;
        }
        
        # checking spreadsheet or work sheet is empty or not;
        
        if ( !$worksheetName ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "908",
                "ERROR: worksheetName empty."
            );
            # redirecting
            wp_redirect( 'admin.php?page=wpgsi-show&msg=worksheetName' );
            exit;
        }
        
        # getting sheet data
        $googleWorksheetData = $this->googleSheet->wpgsi_googleWorksheetData( $worksheetName, $spreadsheetID, $disableColumns );
        # save the settings,  Inserting or update into the DB
        
        if ( $editID ) {
            # this is the edit part
            $r = wp_update_post( array(
                'ID'            => $editID,
                'post_title'    => 'WPGSI SHOW',
                'post_content'  => ( ($googleWorksheetData[0] and !empty($googleWorksheetData[1])) ? addslashes( json_encode( $googleWorksheetData[1] ) ) : "" ),
                'post_excerpt'  => "",
                'post_modified' => current_time( 'mysql' ),
                'post_status'   => 'publish',
                'post_type'     => 'wpgsiShow',
                'meta_input'    => array(
                'spreadsheetID'    => $spreadsheetID,
                'spreadsheetName'  => $spreadsheetName,
                'worksheetID'      => $worksheetID,
                'worksheetName'    => $worksheetName,
                'showNumberOfRows' => $showNumberOfRows,
                'syncFrequency'    => $syncFrequency,
                'disableColumns'   => $disableColumns,
                'lastSyncTime'     => $this->site_date_time(),
            ),
            ) );
        } else {
            # this is the new part
            $r = wp_insert_post( array(
                'post_title'    => 'WPGSI SHOW',
                'post_content'  => ( ($googleWorksheetData[0] and !empty($googleWorksheetData[1])) ? addslashes( json_encode( $googleWorksheetData[1] ) ) : "" ),
                'post_excerpt'  => "",
                'post_modified' => current_time( 'mysql' ),
                'post_status'   => 'publish',
                'post_type'     => 'wpgsiShow',
                'meta_input'    => array(
                'spreadsheetID'    => $spreadsheetID,
                'spreadsheetName'  => $spreadsheetName,
                'worksheetID'      => $worksheetID,
                'worksheetName'    => $worksheetName,
                'showNumberOfRows' => $showNumberOfRows,
                'syncFrequency'    => $syncFrequency,
                'disableColumns'   => $disableColumns,
                'lastSyncTime'     => $this->site_date_time(),
            ),
            ) );
        }
        
        # redirect by response s
        
        if ( $r ) {
            # redirecting
            wp_redirect( 'admin.php?page=wpgsi-show&msg=success' );
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: new sheet show saved."
            );
        } else {
            # redirecting
            wp_redirect( 'admin.php?page=wpgsi-show&msg=error' );
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "909",
                "ERROR: new sheet show saved error."
            );
        }
    
    }
    
    /**
     * Below functor will create shotCode, this shot code will display google sheet table 
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public function wpgsi_wpShortCode()
    {
        add_shortcode( 'wpgsi', array( $this, 'wpgsi_shortCodeCallback' ) );
    }
    
    /**
     * This is the callback function of wpgsi_wpShortCode() this will generate table from JSON data in frontend 
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public function wpgsi_shortCodeCallback( $atts )
    {
        #  adding our parameters to the global attributes
        $a = shortcode_atts( array(
            'id' => '',
        ), $atts );
        #
        if ( empty($a['id']) or !is_numeric( $a['id'] ) ) {
            return "SORRY, you didn't passed any show id. Please set a show number.";
        }
        # getting post data
        $showPost = get_post( $a['id'] );
        # if there is no post just return
        
        if ( !$showPost ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "910",
                "ERROR: No show on this id in the database."
            );
            return;
        }
        
        # if post status is disable aka not publish than return empty
        
        if ( $showPost->post_status != 'publish' ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "911",
                "ERROR: google sheet table is not published."
            );
            # displaying to page or Post content
            return "SORRY, google sheet table is not published.";
        }
        
        # if post is empty
        
        if ( !isset( $showPost->ID ) ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "912",
                "ERROR: no post on this id, Please provide the valid Post ID."
            );
            # displaying to page or Post content
            return "SORRY, no post on this id, Please provide the valid Post ID.";
        }
        
        # getting  showNumberOfRows
        $showNumberOfRows = esc_html( get_post_meta( $showPost->ID, 'showNumberOfRows', true ) );
        # Create table with the data
        $googleSheetData = json_decode( $showPost->post_content );
        # check and Balance
        if ( !is_array( $googleSheetData ) or empty($googleSheetData) ) {
            return "SORRY, google sheet is empty in the site caching or JSON conversion error.";
        }
        # Now Create table
        $table = '<div id=wpgsiFrontendShow>';
        // Adding vue.js Framework
        $table .= '<script type="text/javascript" src="' . plugin_dir_url( __FILE__ ) . 'js/vue.js' . '"></script>';
        // table starts
        $table .= '<div id="wpgsiFrontend' . $a['id'] . '"  style="overflow-x:auto;">';
        $table .= '<table class="widefat wpgsi-table" id="wpgsi-table-' . $a['id'] . '" style="width: 100%;">';
        $table .= '<thead id="wpgsiTableHeaderOne">';
        $table .= '<tr id="wpgsiTableHeaderSearchBar">';
        $table .= '<td v-bind:colspan="googleSheetTitles.length">';
        $table .= '<span>';
        $table .= '<select id="showNumberOfRowsDropdown" v-model="showNumberOfRows"  >';
        $table .= '<option value="5">  	5  	</option>';
        $table .= '<option value="10"> 	10 	</option>';
        $table .= '<option value="25"> 	25 	</option>';
        $table .= '<option value="50"> 	50 	</option>';
        $table .= '<option value="75"> 	75 	</option>';
        $table .= '<option value="100">	100	</option>';
        $table .= '</select>';
        $table .= '</span>';
        $table .= '<span style="float:right;">';
        $table .= 'Search: <input type="text" v-model="searchField" id="searchFieldID">';
        $table .= '</span>';
        $table .= '</td>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<thead id="wpgsiTableHeaderTwo">';
        $table .= '<tr id="wpgsiTableTitleTr">';
        $table .= '<td v-for="(titleItem, columnIndex) in googleSheetTitles"  @click="sortingTableRowsByColumn(columnIndex)" style="cursor:ns-resize;"> <strong>  {{titleItem}}  </strong> </td>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody id="wpgsiTableBody">';
        $table .= '<tr id="wpgsiTableTitleTr"  v-for="(row, index) in googleSheetDataForTableRender" >';
        $table .= '<td v-for="item in row" v-html="item">  {{item}}  </td>';
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '<tfoot id="wpgsiTableFooterTwo">';
        $table .= '<tr id="wpgsiTableFooterNavigationBar">';
        $table .= '<td v-bind:colspan="googleSheetTitles.length">';
        $table .= '<span v-html="navText()">  </span>';
        $table .= '<span style="float:right;"> ';
        $table .= '<span id="movePrevious" @click="movePrevious()" style="cursor:pointer;"> < Previous </span>';
        $table .= '&nbsp;  |  &nbsp;';
        $table .= '<span id="moveNext" @click="moveNext()" style="cursor:pointer;"> Next > </span>';
        $table .= '</span>';
        $table .= '</td>';
        $table .= '</tr>';
        $table .= '</tfoot>';
        $table .= '<tfoot id="wpgsiTableFooterOne">';
        $table .= '<tr id="wpgsiTableFooterTr" >';
        $table .= '<td v-for="(titleItem, columnIndex) in googleSheetTitles"  @click="sortingTableRowsByColumn(columnIndex)" style="cursor:ns-resize;"> <strong>  {{titleItem}}  </strong> </td>';
        $table .= '</tr>';
        $table .= '</tfoot>';
        $table .= '</table>';
        $table .= '</div>';
        // Adding CSS table style
        $table .= '<link rel="stylesheet" type="text/css" rel="noopener" target="_blank" href="' . plugin_dir_url( __FILE__ ) . 'css/wpgsi-show-frontend.css' . '">';
        // Adding custom javascript
        $table .= '<script type="text/javascript" wpgsiDisplayid="' . $a['id'] . '"  wpgsiShownumberofrows = "' . $showNumberOfRows . '" wpgsiTabledata = "' . base64_encode( $showPost->post_content ) . '"  src="' . plugin_dir_url( __FILE__ ) . 'js/wpgsi-show-frontend.js' . '"></script>';
        $table .= '</div>';
        //
        return $table;
    }
    
    /**
     * This Function will fetch data from the certain google sheet 
     * @since    3.7.3
     * @return 	 array 	Integrations details.
     */
    public function wpgsi_ajaxWorksheetData()
    {
        # checking nonce
        
        if ( !wp_verify_nonce( $_POST['nonce'], 'wpgsi_show_nonce' ) ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "913",
                "ERROR: Nonce Verification Failed."
            );
            # printing to js console
            echo  json_encode( [ 0, 'ERROR: Nonce Verification Failed.' ] ) ;
            exit;
        }
        
        # spreadsheetID is set and empty check
        
        if ( !isset( $_POST['spreadsheetID'] ) or empty($_POST['spreadsheetID']) ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "914",
                "ERROR: spreadsheetID is not set or empty."
            );
            # printing to js console
            echo  json_encode( [ 0, 'ERROR: spreadsheetID is not set or empty.' ] ) ;
            exit;
        }
        
        # worksheetID is set and empty check
        
        if ( !isset( $_POST['worksheetName'] ) or empty($_POST['worksheetName']) ) {
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "915",
                "ERROR: worksheetName is not set or empty."
            );
            # printing to js console
            echo  json_encode( [ 0, 'ERROR: worksheetName is not set or empty.' ] ) ;
            exit;
        }
        
        # worksheetName || used strip_tags so that whitespace remain not removed
        $worksheetName = strip_tags( $_POST['worksheetName'] );
        # spreadsheetID
        $spreadsheetID = sanitize_text_field( $_POST['spreadsheetID'] );
        # getting data
        $googleWorksheetData = $this->googleSheet->wpgsi_googleWorksheetData( $worksheetName, $spreadsheetID );
        # check balance  is not true or is empty
        
        if ( !$googleWorksheetData[0] or empty($worksheetName[1]) ) {
            # printing to js console
            echo  json_encode( [ 0, 'ERROR: wpgsi_googleWorksheetData is returning false.' ] ) ;
            exit;
        }
        
        # sending data to the frontend
        print_r( json_encode( $googleWorksheetData, TRUE ) );
        exit;
    }
    
    /**
     * Adding our crone's to the site cron lists 
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public function wpgsi_add_cron_schedule( $schedules )
    {
        $schedules['wpgsi_every_two_hours'] = array(
            'interval' => 7200,
            'display'  => __( 'wpgsi_every_two_hours' ),
        );
        $schedules['wpgsi_every_three_hours'] = array(
            'interval' => 10800,
            'display'  => __( 'wpgsi_every_three_hours' ),
        );
        $schedules['wpgsi_every_five_hours'] = array(
            'interval' => 18000,
            'display'  => __( 'wpgsi_every_five_hours' ),
        );
        $schedules['wpgsi_every_seven_hours'] = array(
            'interval' => 25200,
            'display'  => __( 'wpgsi_every_seven_hours' ),
        );
        $schedules['wpgsi_every_twelve_hours'] = array(
            'interval' => 43200,
            'display'  => __( 'wpgsi_every_twelve_hours' ),
        );
        $schedules['wpgsi_every_day'] = array(
            'interval' => 86400,
            'display'  => __( 'wpgsi_every_day' ),
        );
        $schedules['wpgsi_every_two_day'] = array(
            'interval' => 172800,
            'display'  => __( 'wpgsi_every_two_day' ),
        );
        $schedules['wpgsi_every_three_day'] = array(
            'interval' => 259200,
            'display'  => __( 'wpgsi_every_three_day' ),
        );
        $schedules['wpgsi_every_five_day'] = array(
            'interval' => 259200,
            'display'  => __( 'wpgsi_every_five_day' ),
        );
        $schedules['wpgsi_every_week'] = array(
            'interval' => 604800,
            'display'  => __( 'wpgsi_every_week' ),
        );
        return $schedules;
    }
    
    /**
     * this function will schedule and register Crone, it  will create crone Hook 
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public function wpgsi_wp_next_scheduled()
    {
        if ( !wp_next_scheduled( 'wpgsi_every_two_hours' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_two_hours', 'wpgsi_every_two_hours' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_three_hours' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_three_hours', 'wpgsi_every_three_hours' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_five_hours' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_five_hours', 'wpgsi_every_five_hours' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_seven_hours' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_seven_hours', 'wpgsi_every_seven_hours' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_twelve_hours' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_twelve_hours', 'wpgsi_every_twelve_hours' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_day' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_day', 'wpgsi_every_day' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_two_day' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_two_day', 'wpgsi_every_two_day' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_three_day' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_three_day', 'wpgsi_every_three_day' );
        }
        if ( !wp_next_scheduled( 'wpgsi_every_week' ) ) {
            wp_schedule_event( time(), 'wpgsi_every_week', 'wpgsi_every_week' );
        }
    }
    
    public function wpgsi_every_5_minutes_cron()
    {
    }
    
    public function wpgsi_every_10_minutes_cron()
    {
    }
    
    public function wpgsi_every_15_minutes_cron()
    {
    }
    
    public function wpgsi_every_30_minutes_cron()
    {
    }
    
    public function wpgsi_every_hour_cron()
    {
    }
    
    public function wpgsi_every_two_hours_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyTwoHours' );
    }
    
    public function wpgsi_every_three_hours_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyThreeHours' );
    }
    
    public function wpgsi_every_five_hours_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyFiveHours' );
    }
    
    public function wpgsi_every_seven_hours_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everySevenHours' );
    }
    
    public function wpgsi_every_twelve_hours_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyTwelveHours' );
    }
    
    public function wpgsi_every_day_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyDay' );
    }
    
    public function wpgsi_every_two_day_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyTwoDay' );
    }
    
    public function wpgsi_every_three_day_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyThreeDay' );
    }
    
    public function wpgsi_every_five_day_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyFiveDay' );
    }
    
    public function wpgsi_every_week_cron()
    {
        # sync data from google sheet
        $this->wpgsi_sync_show( 'everyWeek' );
    }
    
    /**
     * this function sync the Google sheet and Update the custom post type content field, aka show data 
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public function wpgsi_sync_show( $syncFrequency = "" )
    {
        # if empty
        if ( empty($syncFrequency) ) {
            return array( false, "ERROR: syncFrequency is empty." );
        }
        # database object
        global  $wpdb ;
        # getting data
        $showPosts = $wpdb->get_results( "SELECT \r\n\t\t\t\t\t\t\t\t\t\t\t{$wpdb->posts}.ID, {$wpdb->posts}.post_type, {$wpdb->posts}.post_status, {$wpdb->postmeta}.meta_id, {$wpdb->postmeta}.meta_value\r\n\t\t\t\t\t\t\t\t\t\tFROM \r\n\t\t\t\t\t\t\t\t\t\t\t{$wpdb->posts}, {$wpdb->postmeta} \r\n\t\t\t\t\t\t\t\t\t\tWHERE \r\n\t\t\t\t\t\t\t\t\t\t\t{$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\t\t\t\t\t\t\t\tAND \r\n\t\t\t\t\t\t\t\t\t\t\t{$wpdb->posts}.post_type = 'wpgsiShow' \r\n\t\t\t\t\t\t\t\t\t\tAND \r\n\t\t\t\t\t\t\t\t\t\t\t{$wpdb->posts}.post_status = 'publish' \r\n\t\t\t\t\t\t\t\t\t\tAND \r\n\t\t\t\t\t\t\t\t\t\t\t{$wpdb->postmeta}.meta_value = '" . esc_sql( sanitize_text_field( $syncFrequency ) ) . "' AND {$wpdb->postmeta}.meta_key = 'syncFrequency' ", ARRAY_A );
        # check to see the length of database return
        if ( count( $showPosts ) == 0 ) {
            return array( false, "INFO: No show in the database." );
        }
        # loop the shows
        foreach ( $showPosts as $key => $show ) {
            # post id
            $postID = sanitize_text_field( $show['ID'] );
            # check there is a Post on this id
            
            if ( get_post_status( $postID ) === FALSE ) {
                # keeping log
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "916",
                    "ERROR: No post on database with this id {$postID}."
                );
                # hmm
                continue;
            }
            
            # spreadsheet id
            $spreadsheetID = sanitize_text_field( get_post_meta( $postID, 'spreadsheetID', true ) );
            # worksheet name
            $worksheetName = strip_tags( get_post_meta( $postID, 'worksheetName', true ) );
            # getting disable Columns
            $disableColumns = array();
            # now check and see noting is Empty
            
            if ( empty($spreadsheetID) or empty($worksheetName) ) {
                # keeping log
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "917",
                    "ERROR: spreadsheetID or worksheetName is empty."
                );
                # hmm
                continue;
            }
            
            # Download the information from Google sheet
            $googleWorksheetData = $this->googleSheet->wpgsi_googleWorksheetData( $worksheetName, $spreadsheetID, $disableColumns );
            # check and Balance
            
            if ( !$googleWorksheetData[0] or !is_array( $googleWorksheetData[1] ) ) {
                # keeping log
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "918",
                    "ERROR: googleWorksheetData[0] is false or googleWorksheetData[1] is empty."
                );
                # hmm
                continue;
            }
            
            # Now update
            $r = wp_update_post( array(
                'ID'            => $postID,
                'post_content'  => ( ($googleWorksheetData[0] and !empty($googleWorksheetData[1])) ? addslashes( json_encode( $googleWorksheetData[1] ) ) : "" ),
                'post_modified' => current_time( 'mysql' ),
                'meta_input'    => array(
                'lastSyncTime' => $this->site_date_time(),
            ),
            ) );
            # keeping log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: Google sheet is synced of ID {$postID} in crone " . $syncFrequency
            );
        }
        #
        return;
    }
    
    #
    # Place below site_date_time() to Common class and share this to all other timing functions
    #
    /**
     *This will generate time and date according to site date & time formate 
     * @since    3.7.3
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public function site_date_time()
    {
        $date_format = get_option( 'date_format' );
        $time_format = get_option( 'time_format' );
        // set default
        $date_format = ( $date_format ? $date_format : 'F j, Y' );
        $time_format = ( $time_format ? $time_format : 'g:i a' );
        // Creating date time string
        $date = date( "{$date_format} {$time_format}", current_time( 'timestamp' ) );
        return $date;
    }

}