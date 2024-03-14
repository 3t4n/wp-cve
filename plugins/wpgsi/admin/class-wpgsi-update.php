<?php

/**
 * This is new remote update class, This call will update wordPress From google Sheet.
 * This call  has Dependence in googleSheet class for Token Generation 
 * @since      3.6.0
 * @package    Wpgsi
 * @subpackage Wpgsi/includes
 * @author     javmah <jaedmah@gmail.com>
 */
class Wpgsi_Update
{
    /**
     * Events Children titles .
     * @since   3.7.0
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    private  $plugin_name ;
    /**
     * Events Children titles .
     * @since    3.7.0
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    private  $version ;
    /**
     * Events Children titles.
     * @since    3.7.0
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     */
    public  $googleSheet ;
    /**
     * Common methods used in the all the classes 
     * @since    3.6.0
     * @var      object    $version    The current version of this plugin.
     */
    public  $common ;
    /**
     * Common methods used in the all the classes 
     * @since    3.7.0
     * @var      object    $version    The current version of this plugin.
     */
    public  $adminClass ;
    /**
     * Class Constrictors. Setting the class Variables
     * @since    3.7.0
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
     * @since    	3.7.0
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_update_notices()
    {
        // echo"<pre>";
        // echo"</pre>";
    }
    
    /**
     * REST API end Point creator, This Function Will create two rest end point one for data acceptance and another for Update data
     * Request will be POST
     * Payload will be <token> and Sheet JSON data 
     * END POINT WILL BE : http://localhost/office/wp-json/wpgsi/update/ 
     * @since    	3.6.0
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_register_rest_route()
    {
        # For receiving data and saving that to option table
        register_rest_route( 'wpgsi', '/accept', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'wpgsi_callBackFuncAccept' ),
            'permission_callback' => '__return_true',
        ) );
        # For updating data site data
        register_rest_route( 'wpgsi', '/update', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'wpgsi_callBackFuncUpdate' ),
            'permission_callback' => '__return_true',
        ) );
    }
    
    /**
     * This is the callback function of register_rest_route() Function
     * This Function get the Request data & handel the Request and return the response 
     * *** IMPORTANT  if you use *** POST MAN *** mast use data as JSON ***
     * @param       $Request data 
     * @since    	3.6.0
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_callBackFuncAccept( $data )
    {
        // error_log(print_r($data['token'], true));
        # Check & Balance; Check to see data <token> is empty or not
        
        if ( !isset( $data['token'] ) or empty($data['token']) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "300",
                "ERROR: update error from Google Sheet. token is empty."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: update error from Google Sheet. token is empty.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # converting data from base64 string
        $jsonString = @base64_decode( $data['token'] );
        # encoding JSON string to PHP array
        $updateInfo = json_decode( $jsonString, TRUE );
        # User information validation;  $updateInfo array and isset( ) check for ID, UID, email
        
        if ( !is_array( $updateInfo ) or !isset( $updateInfo['ID'], $updateInfo['UID'], $updateInfo['email'] ) ) {
            echo  "DANGER: update error from Google Sheet. Not array or ID, UID, email, URL is not set !" ;
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "301",
                "ERROR: update error from Google Sheet. Not array or ID, UID, email, URL is not set !"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: update error from Google Sheet. token is empty.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # integration Id
        
        if ( empty($updateInfo['ID']) or !is_numeric( $updateInfo['ID'] ) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "302",
                "ERROR: update error from Google Sheet. integration ID is empty."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: update error from Google Sheet. integration ID is empty.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Getting the ID
        $integrationID = wp_kses_post( $updateInfo['ID'] );
        # getting user data
        $userData = get_userdata( wp_kses_post( $updateInfo['UID'] ) );
        # User ID check see user
        
        if ( !is_array( $userData ) and $updateInfo['UID'] != $userData->data->ID ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "303",
                "ERROR: update error from Google Sheet. user id is not correct or no user."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: update error from Google Sheet. user id is not correct or no user.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Check User Role If User role is not administrator or editor STOP. send a 400 response
        $user_roles = $userData->roles;
        
        if ( !in_array( 'administrator', $user_roles, true ) and !in_array( 'editor', $user_roles, true ) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "304",
                "ERROR: sorry user didn't have permission to do this task. User role is not administrator or editor"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: sorry user didn't have permission to do this task. User role is not OK",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Email Check
        
        if ( empty($updateInfo['email']) or $updateInfo['email'] != $userData->data->user_email ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "305",
                "ERROR: update error from Google Sheet. user email address is not correct."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: update error from Google Sheet. user email address is not correct.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # getting the remote Update Status.
        $remoteUpdateStatus = get_post_meta( $integrationID, "remoteUpdateStatus", TRUE );
        # remote Update Status check
        
        if ( $remoteUpdateStatus ) {
            # Keeping Log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: remote update from google sheet is initiated. Integration ID : " . $integrationID . " User email : " . $updateInfo['email']
            );
        } else {
            # Keeping Log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "306",
                "ERROR:  Integration ID: " . $integrationID . " remote update status to DISABLED! Request for Update is received."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR:  Integration ID: " . $integrationID . " remote update status to DISABLED! Request for Update is received.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Global database instance
        global  $wpdb ;
        #Product List Empty Array
        $updatePostList = array();
        # Getting the integration
        $Integration = get_post( $integrationID );
        # Post Content
        $post_content = json_decode( $Integration->post_content, TRUE );
        # valid JSON check
        
        if ( !isset( $post_content[0], $post_content[1] ) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "307",
                "ERROR: Saved Relation array is Not Set or JSON parse ERROR or Wrong Post ID of wpgsiintegration !"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: Saved Relation array is Not Set or JSON parse ERROR or Wrong Post ID of wpgsiintegration !",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Empty Check Empty or not
        
        if ( empty($post_content[0]) and empty($post_content[1]) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "308",
                "ERROR: Saved Relation array is EMPTY !"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: Saved Relation array is EMPTY !",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Converting The Content to array
        $post_excerpt = ( !empty($Integration->post_content) ? json_decode( $Integration->post_excerpt, TRUE ) : array() );
        # Empty check, if empty then return the ERROR message
        
        if ( !isset( $post_excerpt['Worksheet'] ) or empty($post_excerpt['Worksheet']) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "309",
                "ERROR: Worksheet Name or Worksheet is empty!"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: Worksheet Name or Worksheet is empty!",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Empty check, if empty then return the ERROR message
        
        if ( !isset( $post_excerpt['SpreadsheetID'] ) or empty($post_excerpt['SpreadsheetID']) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "310",
                "ERROR: Worksheet Name or SpreadsheetID is empty!"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: Worksheet Name or SpreadsheetID is empty!",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Integration Platform check
        
        if ( !isset( $post_excerpt['DataSourceID'] ) or empty($post_excerpt['DataSourceID']) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "311",
                "ERROR: DataSourceID is Empty! its means integration Platform is not present."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: DataSourceID is Empty! its means integration Platform is not present.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # get the integration platform
        $IntegrationPlatform = get_post_meta( $Integration->ID, "IntegrationPlatform", TRUE );
        # check to see is the Platform is empty or not
        
        if ( empty($IntegrationPlatform) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "312",
                "ERROR: There is no Integration Platform saved. Please Open the Integration and re-save it again."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: There is no Integration Platform saved. Please Open the Integration and re-save it again.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # is the Platform is Editable via update
        
        if ( !in_array( $IntegrationPlatform, array(
            'wpPost',
            'wcProduct',
            'wcOrder',
            'wpUser',
            'customPostType',
            'database'
        ) ) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "313",
                "ERROR: SORRY not this time : This integration platform is not supported !"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: SORRY not this time : This integration platform is not supported !",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # BLOCKING Professional Version STARTS
        $lock = TRUE;
        # Check and Balance for Free and professional version
        
        if ( in_array( $post_excerpt['DataSourceID'], array(
            'wordpress_newPost',
            'wordpress_editPost',
            'wordpress_deletePost',
            'wordpress_page'
        ) ) ) {
            #  including the View File;
            $lock = FALSE;
        } else {
        }
        
        # Open for professional version
        
        if ( $lock ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "314",
                " information : We are very sorry for your unexpected experience. All default WordPress Posts and Pages remote updates are FREE.<br> WooCommerce and Custom post types along with user's are in the Professional version. Thank you for using the Plugin."
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: We are very sorry for your unexpected experience. All default WordPress Posts and Pages remote updates are FREE. WooCommerce and Custom post types are in the Professional version. Thank you for using the Plugin.!",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # BLOCKING Professional Version ENDS
        # getting post content
        $post_content = ( !empty($Integration->post_content) ? json_decode( $Integration->post_content, TRUE ) : array() );
        
        if ( !isset( $post_content[1] ) or empty($post_content[1]) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "315",
                "ERROR: Relation is Empty!"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: Relation is Empty!",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Processing the relation
        $relations = array_flip( array_filter( array_values( $post_content[1] ) ) );
        $spreadsheets_id = $post_excerpt['SpreadsheetID'];
        $worksheet_name = $post_excerpt['Worksheet'];
        # Check & balance,
        
        if ( !isset( $data['sheetData'] ) or empty($data['sheetData']) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "316",
                "ERROR: sheetData is not array or sheetData is Empty!"
            );
            echo  "ERROR: sheetData is not array or sheetData is Empty!" ;
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: update error from Google Sheet. token is empty.",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Json encoding the response Data
        $dataArray = ( (isset( $data['sheetData'] ) and !empty($data['sheetData'])) ? $data['sheetData'] : array() );
        # Check and Balance >> is array and not empty
        
        if ( !is_array( $dataArray ) or empty($dataArray) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "317",
                "ERROR: dataArray is not array or dataArray is Empty!"
            );
            # http Response
            $response_data = array(
                'status'  => TRUE,
                'message' => "ERROR: dataArray is not array or dataArray is Empty!",
                'code'    => "400",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 400 );
            return $response;
        }
        
        # Looping the Spreadsheet data that got From Google Sheet
        foreach ( $dataArray as $key => $rowData ) {
            # match the value with the Relation
            $relatedData = $this->common->relationToValue( $rowData, $relations );
            # Now Update the Product to Store The Things
            $mainData = array();
            # Now Update to the Product Meta
            $metaData = array();
            # Select the integration  platform is it - WP post - CPT - WC product - WP user
            
            if ( in_array( $post_excerpt['DataSourceID'], array( 'wordpress_newUser', 'wordpress_UserProfileUpdate', 'wordpress_deleteUser' ) ) ) {
            } elseif ( in_array( $post_excerpt['DataSourceID'], array(
                'wordpress_newPost',
                'wordpress_editPost',
                'wordpress_deletePost',
                'wordpress_page'
            ) ) ) {
                # wp default post and page create and update
                # For Post ID
                if ( isset( $relatedData[1]['postID'] ) ) {
                    $mainData['ID'] = ( !empty($relatedData[1]['postID']) ? wp_kses_post( $relatedData[1]['postID'] ) : "" );
                }
                # Assigning one-to-one relations
                # post_date relation,
                if ( isset( $relatedData[1]['post_date'] ) ) {
                    $mainData['post_date'] = ( !empty($relatedData[1]['post_date']) ? wp_kses_post( $relatedData[1]['post_date'] ) : "" );
                }
                # Modified Date
                # Need to add this
                # Product Description relation || Content
                if ( isset( $relatedData[1]['post_content'] ) ) {
                    $mainData['post_content'] = ( !empty($relatedData[1]['post_content']) ? wp_kses_post( $relatedData[1]['post_content'] ) : "" );
                }
                # Product post_title relation || Title
                if ( isset( $relatedData[1]['post_title'] ) ) {
                    $mainData['post_title'] = ( !empty($relatedData[1]['post_title']) ? wp_kses_post( $relatedData[1]['post_title'] ) : "" );
                }
                # Product post_excerpt relation || Short description
                if ( isset( $relatedData[1]['post_excerpt'] ) ) {
                    $mainData['post_excerpt'] = ( !empty($relatedData[1]['post_excerpt']) ? wp_kses_post( $relatedData[1]['post_excerpt'] ) : "" );
                }
                # Product post_status relation || post status
                if ( isset( $relatedData[1]['post_status'] ) ) {
                    $mainData['post_status'] = ( !empty($relatedData[1]['post_status']) ? wp_kses_post( $relatedData[1]['post_status'] ) : "" );
                }
                # Product post_status relation  || comment status
                if ( isset( $relatedData[1]['comment_status'] ) ) {
                    $mainData['comment_status'] = ( !empty($relatedData[1]['comment_status']) ? wp_kses_post( $relatedData[1]['comment_status'] ) : "" );
                }
                # Product post_type relation || Post type
                if ( isset( $relatedData[1]['post_type'] ) ) {
                    $mainData['post_type'] = ( !empty($relatedData[1]['post_type']) ? wp_kses_post( $relatedData[1]['post_type'] ) : "" );
                }
                # Product menu_order relation || Menu order
                if ( isset( $relatedData[1]['menu_order'] ) ) {
                    $mainData['menu_order'] = ( !empty($relatedData[1]['menu_order']) ? wp_kses_post( $relatedData[1]['menu_order'] ) : "" );
                }
                #--------------------------------------------------------   For meta data for post/page   -----------------------------------------------------------------
                
                if ( $post_excerpt['DataSourceID'] == 'wordpress_page' ) {
                    foreach ( $this->adminClass->wpgsi_pages_metaKeys()[1] as $metaKey ) {
                        if ( isset( $relatedData[1][$metaKey] ) ) {
                            $metaData[$metaKey] = ( !empty($relatedData[1][$metaKey]) ? wp_kses_post( $relatedData[1][$metaKey] ) : "" );
                        }
                    }
                } else {
                    foreach ( $this->adminClass->wpgsi_posts_metaKeys()[1] as $metaKey ) {
                        if ( isset( $relatedData[1][$metaKey] ) ) {
                            $metaData[$metaKey] = ( !empty($relatedData[1][$metaKey]) ? wp_kses_post( $relatedData[1][$metaKey] ) : "" );
                        }
                    }
                }
            
            } elseif ( in_array( $post_excerpt['DataSourceID'], array( 'wc-new_product', 'wc-edit_product', 'wc-delete_product' ) ) ) {
            } elseif ( in_array( $post_excerpt['DataSourceID'], array(
                'wc-new_order',
                'wc-pending',
                'wc-processing',
                'wc-on-hold',
                'wc-completed',
                'wc-cancelled',
                'wc-refunded',
                'wc-failed'
            ) ) ) {
            } elseif ( isset( $this->adminClass->wpgsi_allCptEvents()[2][$post_excerpt['DataSourceID']] ) ) {
            } elseif ( isset( $this->adminClass->database_tables_and_columns()[2][$post_excerpt['DataSourceID']] ) ) {
            } else {
                # ola banana don't do anything,  Although code will not come this Fur,
            }
            
            # Adding Every Row Data to Main POST ARRAY
            $processedData[] = array(
                "mainData" => $mainData,
                "metaData" => $metaData,
            );
        }
        # Separate Post Default Data And Meta Data
        # Setting Update List on the Site Option cache *** important without saving it will n
        update_option( 'wpgsi_remote_data', $processedData );
        update_option( 'wpgsi_integrationID', $Integration->ID );
        # After Update to the Array Unset The Variable For Memory management || clear the Memory
        unset( $data );
        unset( $updateInfo );
        unset( $processedData );
        unset( $dataArray );
        # Keeping data accept log
        $this->common->wpgsi_log(
            get_class( $this ),
            __METHOD__,
            "200",
            "SUCCESS: getting data from remote Google Sheet. Integration ID : " . $Integration->ID
        );
        # setting the WP REST response.
        $response_data = array(
            'status'  => TRUE,
            'message' => 'SUCCESS: getting data from remote Google Sheet. DataSourceID : ' . $post_excerpt['DataSourceID'],
            'code'    => "200",
        );
        $response = new WP_REST_Response( $response_data );
        $response->set_status( 201 );
        return $response;
    }
    
    /**
     * This is the callback function of register_rest_route() Function
     * This Function get the Request data & handel the Request and return the response 
     * This is the updater function it will update Post Types to database.
     * After getting data from the site Option DB Table It Will Update the post and WooCommerce  product .
     * It will Update 13 Product at a time, A frontend Ajax Function will run this Function After every 20 second 
     * @param       $Request data
     * @since    	3.6.0
     * @return 	   	array 	Integrations details.
     */
    public function wpgsi_callBackFuncUpdate()
    {
        # getting integration ID. If integration is Active make it Pending First;
        $integration_id = wp_kses_post( get_option( 'wpgsi_integrationID' ) );
        # if ID Present proceed on.
        
        if ( empty($integration_id) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "318",
                "ERROR: Not Implemented, No integration ID on the SITE Option DB table."
            );
            $response_data = array(
                'status'  => TRUE,
                'message' => 'ERROR: Not Implemented, No integration ID on the SITE Option DB table.',
                'code'    => "501",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 501 );
            return $response;
        } else {
            # getting the Integration.
            $post = get_post( $integration_id );
            # if Post is Publish, Stop the Post by making it Pending.
            
            if ( $post->post_status == 'publish' ) {
                $update_post = array(
                    'ID'          => $integration_id,
                    'post_status' => 'pending',
                );
                wp_update_post( $update_post, true );
            }
        
        }
        
        # Getting Integration platform
        $IntegrationPlatform = get_post_meta( $integration_id, "IntegrationPlatform", TRUE );
        
        if ( empty($IntegrationPlatform) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "319",
                "ERROR: Integration platform is empty. Open edit the Integration and re-save it."
            );
            $response_data = array(
                'status'  => TRUE,
                'message' => 'ERROR: Integration platform is empty. Open edit the Integration and re-save it.',
                'code'    => "502",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 501 );
            return $response;
        }
        
        # Getting Integration platform
        $DataSourceID = get_post_meta( $integration_id, "DataSourceID", TRUE );
        
        if ( empty($DataSourceID) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "320",
                "ERROR: DataSourceID is empty. Open edit the Integration and re-save it."
            );
            $response_data = array(
                'status'  => TRUE,
                'message' => 'ERROR: DataSourceID is empty. Open edit the Integration and re-save it.',
                'code'    => "502",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 501 );
            return $response;
        }
        
        # let's see saved data is full or empty;
        $savedData = get_option( 'wpgsi_remote_data' );
        # check save data is empty or not if empty send error message
        
        if ( empty($savedData) ) {
            # getting  integration ID
            $post = get_post( $integration_id );
            # if integration is  Pending Publish the integration
            
            if ( $post->post_status == 'pending' ) {
                $update_post = array(
                    'ID'          => $integration_id,
                    'post_status' => 'publish',
                );
                wp_update_post( $update_post, true );
            }
            
            # Delete Product cache Too || no garbage
            delete_option( 'wpgsi_remote_data' );
            # Delete wpgsi_integrationID
            delete_option( 'wpgsi_integrationID' );
            # Keeping update log
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: Post update successfully. Integration ID : " . $integration_id
            );
            # response
            $response_data = array(
                'status'  => TRUE,
                'message' => "SUCCESS: done ...!",
                'code'    => "202",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 202 );
            return $response;
        }
        
        # Updating / Creating user information
        
        if ( $IntegrationPlatform == 'wpUser' ) {
            # user list loop counter
            $u = 0;
            # Looping the list
            foreach ( $savedData as $key => $dataArray ) {
                # increment the Counter;
                $u++;
                # Checking is there any USER on the Given ID
                
                if ( isset( $dataArray['mainData']['ID'] ) and !empty($dataArray['mainData']['ID']) ) {
                    # get user by user id
                    $user = get_user_by( 'id', $dataArray['mainData']['ID'] );
                    # if ID is not set or empty then DELETE existing ID ***
                    
                    if ( !isset( $user->ID ) or empty($user->ID) ) {
                        // $dataArray['mainData']['ID'] = ""
                        # Keeping the Log
                        $this->common->wpgsi_log(
                            get_class( $this ),
                            __METHOD__,
                            "321",
                            "ERROR: there is no  user on the site  with this id: " . $dataArray['mainData']['ID']
                        );
                        # skip  this entry
                        continue;
                    }
                
                }
                
                # unset the inserted array item.
                unset( $savedData[$key] );
                # Break the Loop after 13
                if ( $u == 13 ) {
                    break;
                }
            }
            # Update Product list cache with remaining list item
            update_option( 'wpgsi_remote_data', $savedData );
            # Preparing response
            $response_data = array(
                'status'  => TRUE,
                'message' => "SUCCESS: user is created / updated ...! remaining " . count( $savedData ),
                'code'    => "201",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 201 );
            return $response;
        }
        
        # Updating / Creating WordPress Post or WooCommerce product / Order
        
        if ( in_array( $IntegrationPlatform, array( 'wpPost', 'wcProduct', 'wcOrder' ) ) ) {
            # Loop the Option Saved Product list.
            $p = 0;
            # Looping the lists
            foreach ( $savedData as $key => $dataArray ) {
                # increment the Counter;
                $p++;
                # Defining  post type
                
                if ( !isset( $dataArray['mainData']['post_type'] ) or !empty($dataArray['mainData']['post_type']) ) {
                    # matching for new post event
                    if ( in_array( $DataSourceID, array( 'wc-new_product', 'wc-edit_product', 'wc-delete_product' ) ) ) {
                        $dataArray['mainData']['post_type'] = 'product';
                    }
                    # matching for update post
                    if ( in_array( $DataSourceID, array(
                        "wc-new_order",
                        "wc-pending",
                        "wc-processing",
                        "wc-on-hold",
                        "wc-completed",
                        "wc-cancelled",
                        "wc-refunded",
                        "wc-failed"
                    ) ) ) {
                        $dataArray['mainData']['post_type'] = 'shop_order';
                    }
                }
                
                # Checking is there any post on the Given ID
                
                if ( isset( $dataArray['mainData']['ID'] ) and !empty($dataArray['mainData']['ID']) and is_int( $dataArray['mainData']['ID'] ) ) {
                    # getting post by post ID
                    $post = get_post( $dataArray['mainData']['ID'], ARRAY_A );
                    # if ID is not set or empty then DELETE existing ID ***
                    
                    if ( !isset( $post['ID'] ) or empty($post['ID']) ) {
                        // $dataArray['mainData']['ID'] = "";
                        # Keeping the Log
                        $this->common->wpgsi_log(
                            get_class( $this ),
                            __METHOD__,
                            "325",
                            "ERROR: there is no  Post on the site with this id: " . $dataArray['mainData']['ID']
                        );
                        # skip  this entry
                        continue;
                    }
                
                }
                
                # if ID set and not empty than update the user # if ID is not set or empty.
                
                if ( isset( $dataArray['mainData']['ID'] ) and !empty($dataArray['mainData']['ID']) ) {
                    # updating existing post
                    $r = wp_update_post( $dataArray['mainData'] );
                    
                    if ( is_wp_error( $r ) ) {
                        # Keeping the Log
                        $this->common->wpgsi_log(
                            get_class( $this ),
                            __METHOD__,
                            "326",
                            "ERROR: post is not updated. " . $r->get_error_message()
                        );
                    } else {
                        # user profile is updated now update the meta data
                        foreach ( $dataArray['metaData'] as $meta_key => $meta_value ) {
                            update_post_meta( $dataArray['mainData']['ID'], $meta_key, $meta_value );
                        }
                    }
                
                } else {
                }
                
                # unset the inserted array item.
                unset( $savedData[$key] );
                # Break the Loop after 13
                if ( $p == 13 ) {
                    break;
                }
            }
            # Update Product list cache with remaining list item
            update_option( 'wpgsi_remote_data', $savedData );
            # Preparing response
            $response_data = array(
                'status'  => TRUE,
                'message' => "SUCCESS: Post is created / updated ...! remaining " . count( $savedData ),
                'code'    => "201",
            );
            $response = new WP_REST_Response( $response_data );
            $response->set_status( 201 );
            return $response;
        }
        
        # Updating / Creating WordPress [Custom Post Type]
        if ( $IntegrationPlatform == 'customPostType' ) {
        }
        # Updating or Creating database row
        if ( $IntegrationPlatform == 'database' ) {
        }
    }

}