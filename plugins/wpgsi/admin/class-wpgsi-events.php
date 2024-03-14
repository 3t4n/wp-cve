<?php

/**
 * Define the internationalization functionality.
 * Loads and defines the internationalization files for this plugin
 * @since      1.0.0
 * @package    Wpgsi
 * @subpackage Wpgsi/includes
 * @author     javmah <jaedmah@gmail.com>
 */
class Wpgsi_Events
{
    /**
     * The current Date.
     *
     * @since    1.0.0
     * @access   Public
     * @var      string    $Date    The current version of the plugin.
     */
    public  $Date = "" ;
    /**
     * The current Time.
     * @since    1.0.0
     * @access   Public
     * @var      string    $Time   The current Time.
     */
    public  $Time = "" ;
    /**
     * List of active plugins.
     * @since    1.0.0
     * @access   Public
     * @var      array    $active_plugins     List of active plugins .
     */
    public  $active_plugins = array() ;
    /**
     * Common methods used in the all the classes 
     * @since    3.6.0
     * @var      object    $version    The current version of this plugin.
     */
    public  $common ;
    /**
     * Define the class variables, arrays for Events to use;
     * @since    1.0.0s
     */
    public function __construct( $plugin_name, $version, $common )
    {
        # Set date
        $date_format = get_option( 'date_format' );
        $this->Date = ( $date_format ? current_time( $date_format ) : current_time( 'd/m/Y' ) );
        # set time
        $time_format = get_option( 'time_format' );
        $this->Time = ( $date_format ? current_time( $time_format ) : current_time( 'g:i a' ) );
        # Active Plugins
        $this->active_plugins = get_option( 'active_plugins' );
        # Checking Active And Inactive Plugin
        # Common Methods
        $this->common = $common;
    }
    
    # construct Ends Here
    /**
     * For Testing purpose 
     */
    public function wpgsi_event_notices()
    {
        // echo "<pre>";
        // echo "</pre>";
    }
    
    /**
     *  WordPress new User Registered  HOOK's callback function
     *  @param     int     $user_id     	  username
     *  @param     int     $old_user_data     username
     *  @since     1.0.0
     */
    public function wpgsi_wordpress_newUser( $user_id )
    {
        # if There is a integration on  new user
        if ( $this->wpgsi_integrations( 'wordpress_newUser' )[0] ) {
            # if get_userdata() and get_user_meta() Functions are exist;
            
            if ( function_exists( 'get_userdata' ) and function_exists( 'get_user_meta' ) ) {
                $user_data = array();
                $user = get_userdata( $user_id );
                $userMeta = get_user_meta( $user_id );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "New User";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                $user_data['userPassword'] = ( isset( $user->user_pass ) && !empty($user->user_pass) ? $user->user_pass : "" );
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                #
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user_id ) {
                    $r = $this->wpgsi_eventBoss(
                        'wp',
                        'wordpress_newUser',
                        $user_data,
                        $user_id
                    );
                } else {
                    $this->common->wpgsi_log(
                        get_class( $this ),
                        __METHOD__,
                        "700",
                        "ERROR: wordpress_newUser fired but no User ID . " . json_encode( array( $user_id, $user_data ) )
                    );
                }
            
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "701",
                    "wpgsi_log: get_userdata or get_user_meta is not Exist"
                );
            }
        
        }
    }
    
    /**
     *  WordPress new User Profile Update HOOK's callback function
     *  @param     int     $user_id     		user ID
     *  @param     int     $old_user_data     	user Data
     *  @since     1.0.0
     */
    public function wpgsi_wordpress_profileUpdate( $user_id, $old_user_data )
    {
        # if There is a integration on User profile update
        if ( $this->wpgsi_integrations( 'wordpress_UserProfileUpdate' )[0] ) {
            # if get_userdata() and get_user_meta() Functions are exist
            
            if ( function_exists( 'get_userdata' ) && function_exists( 'get_user_meta' ) && !empty($user_id) ) {
                $user_data = array();
                $user = get_userdata( $user_id );
                $userMeta = get_user_meta( $user_id );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "Profile Update";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                $user_data['userPassword'] = ( isset( $user->user_pass ) && !empty($user->user_pass) ? $user->user_pass : "" );
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                # New Code Starts From Here
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user_id && $user->ID ) {
                    $r = $this->wpgsi_eventBoss(
                        'wp',
                        'wordpress_UserProfileUpdate',
                        $user_data,
                        $user_id
                    );
                } else {
                    $this->common->wpgsi_log(
                        get_class( $this ),
                        __METHOD__,
                        "702",
                        "ERROR: wordpress_UserProfileUpdate fired but no User ID. " . json_encode( array( $user_id, $user->ID, $user_data ) )
                    );
                }
            
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "703",
                    "ERROR:  get_userdata or get_user_meta or User id is not Exist"
                );
            }
        
        }
    }
    
    /**
     *  WordPress Delete User HOOK's callback function
     *  @param    int     $user_id     user ID
     *  @since    1.0.0
     */
    public function wpgsi_wordpress_deleteUser( $user_id )
    {
        # if There is a integration on Delete user
        if ( $this->wpgsi_integrations( 'wordpress_deleteUser' )[0] ) {
            # if get_userdata() and get_user_meta() Functions are exist
            
            if ( function_exists( 'get_userdata' ) && function_exists( 'get_user_meta' ) && !empty($user_id) ) {
                # Empty Holder
                $user_data = array();
                $user = get_userdata( $user_id );
                $userMeta = get_user_meta( $user_id );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "Delete User";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                $user_data['userPassword'] = ( isset( $user->user_pass ) && !empty($user->user_pass) ? $user->user_pass : "" );
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                #
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user_id && $user->ID ) {
                    $r = $this->wpgsi_eventBoss(
                        'wp',
                        'wordpress_deleteUser',
                        $user_data,
                        $user_id
                    );
                } else {
                    $this->common->wpgsi_log(
                        get_class( $this ),
                        __METHOD__,
                        "704",
                        "ERROR: wordpress_deleteUser fired but no User ID . " . json_encode( array( $user_id, $user->ID, $user_data ) )
                    );
                }
            
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "705",
                    "ERROR: get_userdata or get_user_meta or user_id is not Exist"
                );
            }
        
        }
    }
    
    /**
     * User Logged in  HOOK's callback function
     * @param     int     $username     username
     * @param     int     $user     	user
     * @since     1.0.0
     */
    public function wpgsi_wordpress_userLogin( $username, $user )
    {
        # if There is a integration on user login
        if ( $this->wpgsi_integrations( 'wordpress_userLogin' )[0] ) {
            # if get_user_meta() function and $user->ID exist
            
            if ( function_exists( 'get_user_meta' ) and !empty($user->ID) ) {
                # Pre-populating User Data
                $user_data = array();
                $userMeta = get_user_meta( $user->ID );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "User Login";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                #
                $user_data['userLoginTime'] = $this->Time;
                $user_data['userLoginDate'] = $this->Date;
                #
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
                # New Code Starts From Here
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action,  Sending Data to Event Boss
                $r = $this->wpgsi_eventBoss(
                    'wp',
                    'wordpress_userLogin',
                    $user_data,
                    $user->ID
                );
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "706",
                    "ERROR: user->ID Not Exist OR get_user_meta is not Exist"
                );
            }
        
        }
    }
    
    /**
     * User wp_logout  HOOK's callback function
     * @since   1.0.0
     */
    public function wpgsi_wordpress_userLogout( $userInfo )
    {
        # if There is a integration on user logout
        if ( $this->wpgsi_integrations( 'wordpress_userLogout' )[0] ) {
            # if wp_get_current_user() function and wp_get_current_user()->ID exist
            
            if ( function_exists( 'wp_get_current_user' ) && !empty(wp_get_current_user()->ID) ) {
                # Pre-populating User Data
                $user = wp_get_current_user();
                $user_data = array();
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "User Logout";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                #
                $user_data['userLogoutTime'] = $this->Time;
                $user_data['userLogoutDate'] = $this->Date;
                #
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                # New Code Starts From Here
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user->ID ) {
                    $r = $this->wpgsi_eventBoss(
                        'wp',
                        'wordpress_userLogout',
                        $user_data,
                        $user->ID
                    );
                } else {
                    $this->common->wpgsi_log(
                        get_class( $this ),
                        __METHOD__,
                        "707",
                        "ERROR: wordpress_userLogout fired but no User ID . " . json_encode( array( $user->ID, $user_data ) )
                    );
                }
            
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "708",
                    "ERROR: User ID OR  Function  wp_get_current_user() is not exists."
                );
            }
        
        }
    }
    
    /**
     * WordPress Post HOOK's callback function [NEW ONE]
     * @since     1.0.0   
     * @param     int     $post_id      Order ID
     * @param     int     $post    		Order ID
     * @param     int     $update     	Product Post 
     */
    public function wpgsi_wordpress_post( $post_id, $post, $update )
    {
        # Check Empty Post Id or Post
        if ( empty($post_id) or empty($post) ) {
            return;
        }
        # Default Post type array
        $postType = array(
            'post' => 'Post',
            'page' => "Page",
        );
        # If Free and Post type is Not Post or Page return
        if ( !isset( $postType[$post->post_type] ) ) {
            return;
        }
        # Setting the Values
        $post_data = array();
        $userData = get_userdata( $post->post_author );
        $post_data['postID'] = $post->ID;
        #
        $post_data['post_authorID'] = ( isset( $post->post_author ) ? $post->post_author : '' );
        // property_exists // isset
        $post_data['authorUserName'] = ( isset( $userData->user_login ) ? $userData->user_login : '' );
        //
        $post_data['authorDisplayName'] = ( isset( $userData->display_name ) ? $userData->display_name : '' );
        $post_data['authorEmail'] = ( isset( $userData->user_email ) ? $userData->user_email : '' );
        $post_data['authorRole'] = ( isset( $userData->roles ) && is_array( $userData->roles ) ? implode( ", ", $userData->roles ) : "" );
        #
        $post_data['post_title'] = ( isset( $post->post_title ) ? $post->post_title : '' );
        $post_data['post_date'] = ( isset( $post->post_date ) ? $post->post_date : '' );
        $post_data['post_date_gmt'] = ( isset( $post->post_date_gmt ) ? $post->post_date_gmt : '' );
        # site Current Time
        $post_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $post_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # New Code Starts From Here
        # date of the Post Creation
        $post_data["post_date_year"] = ( (isset( $post->ID ) and !empty(get_the_date( 'Y', $post->ID ))) ? date( 'Y', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_date_month"] = ( (isset( $post->ID ) and !empty(get_the_date( 'm', $post->ID ))) ? date( 'm', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_date_date"] = ( (isset( $post->ID ) and !empty(get_the_date( 'd', $post->ID ))) ? date( 'd', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_date_time"] = ( (isset( $post->ID ) and !empty(get_the_date( 'H:i', $post->ID ))) ? date( 'H:i', strtotime( "{$post->post_modified}" ) ) : '' );
        # date of Post Modification
        $post_data["post_modified_year"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'Y', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_modified_month"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'm', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_modified_date"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'd', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_modified_time"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'H:i', strtotime( "{$post->post_modified}" ) ) : '' );
        # New Code Ends Here
        $post_data['post_content'] = ( isset( $post->post_content ) ? $post->post_content : '' );
        $post_data['post_excerpt'] = ( isset( $post->post_excerpt ) ? $post->post_excerpt : '' );
        $post_data['post_status'] = ( isset( $post->post_status ) ? $post->post_status : '' );
        $post_data['comment_status'] = ( isset( $post->comment_status ) ? $post->comment_status : '' );
        $post_data['ping_status'] = ( isset( $post->ping_status ) ? $post->ping_status : '' );
        $post_data['post_password'] = ( isset( $post->post_password ) ? $post->post_password : '' );
        $post_data['post_name'] = ( isset( $post->post_name ) ? $post->post_name : '' );
        $post_data['to_ping'] = ( isset( $post->to_ping ) ? $post->to_ping : '' );
        $post_data['pinged'] = ( isset( $post->pinged ) ? $post->pinged : '' );
        $post_data['post_modified'] = ( isset( $post->post_modified ) ? $post->post_modified : '' );
        $post_data['post_modified_gmt'] = ( isset( $post->post_modified_gmt ) ? $post->post_modified_gmt : '' );
        $post_data['post_parent'] = ( isset( $post->post_parent ) ? $post->post_parent : '' );
        $post_data['guid'] = ( isset( $post->guid ) ? $post->guid : '' );
        $post_data['menu_order'] = ( isset( $post->menu_order ) ? $post->menu_order : '' );
        $post_data['post_type'] = ( isset( $post->post_type ) ? $post->post_type : '' );
        $post_data['post_mime_type'] = ( isset( $post->post_mime_type ) ? $post->post_mime_type : '' );
        $post_data['comment_count'] = ( isset( $post->comment_count ) ? $post->comment_count : '' );
        $post_data['filter'] = ( isset( $post->filter ) ? $post->filter : '' );
        # if Post type is Post
        
        if ( $post->post_type == 'post' ) {
            # getting Time Difference
            if ( !empty($post->post_date) and !empty($post->post_modified) ) {
                $post_time_diff = strtotime( $post->post_modified ) - strtotime( $post->post_date );
            }
            # New Post,
            
            if ( $post->post_status == 'publish' and $post_time_diff <= 1 ) {
                $post_data['eventName'] = "New Post";
                # Action
                $r = $this->wpgsi_eventBoss(
                    'wp',
                    'wordpress_newPost',
                    $post_data,
                    $post->ID
                );
                # event Log for Trash
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: testing the post from new post. " . json_encode( array(
                    $post_id,
                    $post,
                    $update,
                    $post_data
                ) )
                );
            }
            
            # Updated post
            
            if ( $post->post_status == 'publish' and $post_time_diff > 1 ) {
                $post_data['eventName'] = "Posts Edited";
                # Action
                $r = $this->wpgsi_eventBoss(
                    'wp',
                    'wordpress_editPost',
                    $post_data,
                    $post->ID
                );
                # event Log for Trash
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: testing the post edited publish. " . json_encode( array(
                    $post_id,
                    $post,
                    $update,
                    $post_data
                ) )
                );
            }
            
            # Post Is trash  || If Post is Trashed This Will fired
            
            if ( $post->post_status == 'trash' ) {
                $post_data['eventName'] = "Trash";
                $r = $this->wpgsi_eventBoss(
                    'wp',
                    'wordpress_deletePost',
                    $post_data,
                    $post->ID
                );
                # event Log for Trash
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: testing the post from trash. " . json_encode( array(
                    $post_id,
                    $post,
                    $update,
                    $post_data
                ) )
                );
            }
        
        }
        
        # if Post type is Page
        
        if ( $post->post_type == 'page' ) {
            $post_data['eventName'] = "New Page";
            # Action
            $r = $this->wpgsi_eventBoss(
                'wp',
                'wordpress_page',
                $post_data,
                $post->ID
            );
            # event Log for Trash
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: testing page. " . json_encode( array(
                $post_id,
                $post,
                $update,
                $post_data
            ) )
            );
        }
    
    }
    
    /**
     * WordPress New Comment   HOOK's callback function
     * @since     1.0.0
     * @param     int     $commentID     			Order ID
     * @param     int     $commentApprovedStatus    Order ID
     * @param     int     $commentData     	  		Product Post 
     */
    public function wpgsi_wordpress_comment( $commentID, $commentApprovedStatus, $commentData )
    {
        # if There is a integration on  Comment
        
        if ( $this->wpgsi_integrations( 'wordpress_comment' )[0] ) {
            # Check Comment ID is exist
            if ( empty($commentID) ) {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "709",
                    "ERROR:  Comment ID is Empty! "
                );
            }
            # Setting Data
            $Data = array();
            $Data["comment_ID"] = $commentID;
            $Data["comment_post_ID"] = ( isset( $commentData["comment_post_ID"] ) ? $commentData["comment_post_ID"] : '' );
            $Data["comment_author"] = ( isset( $commentData["comment_author"] ) ? $commentData["comment_author"] : '' );
            $Data["comment_author_email"] = ( isset( $commentData["comment_author_email"] ) ? $commentData["comment_author_email"] : '' );
            $Data["comment_author_url"] = ( isset( $commentData["comment_author_url"] ) ? $commentData["comment_author_url"] : '' );
            $Data["comment_content"] = ( isset( $commentData["comment_content"] ) ? $commentData["comment_content"] : '' );
            $Data["comment_type"] = ( isset( $commentData["comment_type"] ) ? $commentData["comment_type"] : '' );
            $Data["user_ID"] = ( isset( $commentData["user_ID"] ) ? $commentData["user_ID"] : '' );
            $Data["comment_author_IP"] = ( isset( $commentData["comment_author_IP"] ) ? $commentData["comment_author_IP"] : '' );
            $Data["comment_agent"] = ( isset( $commentData["comment_agent"] ) ? $commentData["comment_agent"] : '' );
            $Data["comment_date"] = ( isset( $commentData["comment_date"] ) ? $commentData["comment_date"] : '' );
            $Data["comment_date_gmt"] = ( isset( $commentData["comment_date_gmt"] ) ? $commentData["comment_date_gmt"] : '' );
            #
            $Data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
            $Data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
            # New Code Starts From Here
            $Data["year_of_comment"] = get_comment_date( "Y", $commentID );
            $Data["month_of_comment"] = get_comment_date( "m", $commentID );
            $Data["date_of_comment"] = get_comment_date( "d", $commentID );
            $Data["time_of_comment"] = get_comment_date( "H:t", $commentID );
            # New Code Ends Here
            $Data["filtered"] = ( isset( $commentData["filtered"] ) ? $commentData["filtered"] : '' );
            $Data["comment_approved"] = ( isset( $commentData["comment_approved"] ) && $commentData["comment_approved"] ? "True" : "False" );
            # Action
            
            if ( empty($commentID) or empty($commentData) or empty($Data) ) {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "710",
                    "ERROR:  commentID or commentData is empty !"
                );
            } else {
                $r = $this->wpgsi_eventBoss(
                    'wp',
                    'wordpress_comment',
                    $Data,
                    $commentID
                );
            }
        
        }
    
    }
    
    # There should be an Edit Comment Hook Function in Here !
    # Create the Function and The Code for Edit product
    /**
     * WordPress Edit Comment   HOOK's callback function
     * @since     1.0.0
     * @param     int     $commentID     			Order ID
     * @param     int     $commentData     	  		Product Post 
     */
    public function wpgsi_wordpress_edit_comment( $commentID, $commentData )
    {
        # if There is a integration on edit Comment
        
        if ( $this->wpgsi_integrations( 'wordpress_edit_comment' )[0] ) {
            # Check Comment ID is exist
            if ( empty($commentID) ) {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "711",
                    " Comment ID is Empty!"
                );
            }
            $Data = array();
            $Data["comment_ID"] = $commentID;
            $Data["comment_post_ID"] = ( isset( $commentData["comment_post_ID"] ) ? $commentData["comment_post_ID"] : '' );
            $Data["comment_author"] = ( isset( $commentData["comment_author"] ) ? $commentData["comment_author"] : '' );
            $Data["comment_author_email"] = ( isset( $commentData["comment_author_email"] ) ? $commentData["comment_author_email"] : '' );
            $Data["comment_author_url"] = ( isset( $commentData["comment_author_url"] ) ? $commentData["comment_author_url"] : '' );
            $Data["comment_content"] = ( isset( $commentData["comment_content"] ) ? $commentData["comment_content"] : '' );
            $Data["comment_type"] = ( isset( $commentData["comment_type"] ) ? $commentData["comment_type"] : '' );
            $Data["user_ID"] = ( isset( $commentData["user_ID"] ) ? $commentData["user_ID"] : '' );
            $Data["comment_author_IP"] = ( isset( $commentData["comment_author_IP"] ) ? $commentData["comment_author_IP"] : '' );
            $Data["comment_agent"] = ( isset( $commentData["comment_agent"] ) ? $commentData["comment_agent"] : '' );
            $Data["comment_date"] = ( isset( $commentData["comment_date"] ) ? $commentData["comment_date"] : '' );
            $Data["comment_date_gmt"] = ( isset( $commentData["comment_date_gmt"] ) ? $commentData["comment_date_gmt"] : '' );
            #
            $Data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
            $Data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
            # New Code Starts From Here
            $Data["year_of_comment"] = get_comment_date( "Y", $commentID );
            $Data["month_of_comment"] = get_comment_date( "m", $commentID );
            $Data["date_of_comment"] = get_comment_date( "d", $commentID );
            $Data["time_of_comment"] = get_comment_date( "H:t", $commentID );
            # New Code Ends Here
            $Data["filtered"] = ( isset( $commentData["filtered"] ) ? $commentData["filtered"] : '' );
            $Data["comment_approved"] = ( isset( $commentData["comment_approved"] ) && $commentData["comment_approved"] ? "True" : "False" );
            # Action
            
            if ( empty($commentID) or empty($commentData) or empty($Data) ) {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "712",
                    "ERROR: commentID or commentData is empty !"
                );
            } else {
                $r = $this->wpgsi_eventBoss(
                    'wp',
                    'wordpress_edit_comment',
                    $Data,
                    $commentID
                );
            }
        
        }
    
    }
    
    /**
     * Woocommerce  Products  HOOK's callback function
     * @since     1.0.0
     * @param     int     $new_status     Order ID
     * @param     int     $old_status     Order ID
     * @param     int     $post     	  Product Post 
     */
    public function wpgsi_woocommerce_product( $new_status, $old_status, $post )
    {
        # If Post type is Not product
        if ( $post->post_type !== 'product' ) {
            return;
        }
        # getting Product information
        $product = wc_get_product( $post->ID );
        $product_data = array();
        # Get Product General Info
        $product_data['productID'] = $post->ID;
        $product_data['type'] = ( method_exists( $product, 'get_type' ) && is_string( $product->get_type() ) ? $product->get_type() : "--" );
        $product_data['post_type'] = ( isset( $post->post_type ) ? $post->post_type : '' );
        $product_data['name'] = ( method_exists( $product, 'get_name' ) && is_string( $product->get_name() ) ? $product->get_name() : "--" );
        $product_data['slug'] = ( method_exists( $product, 'get_slug' ) && is_string( $product->get_slug() ) ? $product->get_slug() : "--" );
        $product_data['date_created'] = ( method_exists( $product, 'get_date_created' ) && is_object( $product->get_date_created() ) ? $product->get_date_created()->date( "F j, Y, g:i:s A T" ) : "--" );
        $product_data['date_modified'] = ( method_exists( $product, 'get_date_modified' ) && is_object( $product->get_date_modified() ) ? $product->get_date_modified()->date( "F j, Y, g:i:s A T" ) : "--" );
        # site Current Time
        $product_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $product_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Get Product Dimensions
        $product_data['weight'] = ( method_exists( $product, 'get_weight' ) && is_string( $product->get_weight() ) ? $product->get_weight() : "--" );
        $product_data['length'] = ( method_exists( $product, 'get_length' ) && is_string( $product->get_length() ) ? $product->get_length() : "--" );
        $product_data['width'] = ( method_exists( $product, 'get_width' ) && is_string( $product->get_width() ) ? $product->get_width() : "--" );
        $product_data['height'] = ( method_exists( $product, 'get_height' ) && is_string( $product->get_height() ) ? $product->get_height() : "--" );
        # Get Product Variations
        $product_data['attributes'] = ( method_exists( $product, 'get_variation_attributes' ) && is_array( $product->get_variation_attributes() ) ? json_encode( $product->get_variation_attributes() ) : "--" );
        $product_data['default_attributes'] = ( method_exists( $product, 'get_default_attributes' ) && is_array( $product->get_default_attributes() ) ? json_encode( $product->get_default_attributes() ) : "--" );
        # Get Product Taxonomies
        $product_data['category_ids'] = ( method_exists( $product, 'get_category_ids' ) && is_array( $product->get_category_ids() ) ? implode( ", ", $product->get_category_ids() ) : "--" );
        $product_data['tag_ids'] = ( method_exists( $product, 'get_tag_ids' ) && is_array( $product->get_tag_ids() ) ? implode( ", ", $product->get_gallery_image_ids() ) : "--" );
        # Get Product Images
        $product_data['image_id'] = ( method_exists( $product, 'get_image_id' ) && is_string( $product->get_image_id() ) ? $product->get_image_id() : "--" );
        $product_data['gallery_image_ids'] = ( method_exists( $product, 'get_gallery_image_ids' ) && is_array( $product->get_gallery_image_ids() ) ? implode( ", ", $product->get_gallery_image_ids() ) : "--" );
        $product_data['get_attachment_image_url'] = ( (method_exists( $product, 'get_image_id' ) and function_exists( 'wp_get_attachment_image_url' ) and !empty($product->get_image_id())) ? wp_get_attachment_image_url( $product->get_image_id() ) : "--" );
        
        if ( $new_status == 'publish' && $old_status !== 'publish' ) {
            # New Product Insert
            $product_data['price'] = wp_strip_all_tags( $_POST['_sale_price'] );
            $product_data['regular_price'] = wp_strip_all_tags( $_POST['_regular_price'] );
            $product_data['sale_price'] = wp_strip_all_tags( $_POST['_sale_price'] );
            $product_data['eventName'] = "New Product";
            # Action
            $r = $this->wpgsi_eventBoss(
                'Woocommerce',
                'wc-new_product',
                $product_data,
                $post->ID
            );
        } elseif ( $new_status == 'trash' ) {
            # Delete  Product;
            $product_data['eventName'] = "Trash";
            # Action
            $r = $this->wpgsi_eventBoss(
                'Woocommerce',
                'wc-delete_product',
                $product_data,
                $product->ID
            );
        } else {
            # Update
            $product_data['eventName'] = "Update Product";
            # Action
            $r = $this->wpgsi_eventBoss(
                'Woocommerce',
                'wc-edit_product',
                $product_data,
                $post->ID
            );
        }
    
    }
    
    /**
     * WooCommerce Order  HOOK's callback function
     * @since    1.0.0
     * @param    int     $order_id     Order ID
     */
    public function wpgsi_woocommerce_order_status_changed( $order_id, $this_status_transition_from, $this_status_transition_to )
    {
        # check to see is there any integration on this order change Status.
        if ( !$this->wpgsi_integrations( 'wc-' . $this_status_transition_to )[0] ) {
            return;
        }
        # getting order data
        $order = wc_get_order( $order_id );
        $order_data = array();
        #  ++++++++++++ This below of Code Is Not Working | change the Code ++++++++++++
        # New system For Stopping Dabble Submission # If Order Created Date Is Less than 3 mints and Order is from checkout
        $orderDateTimeStamp = strtotime( $order->get_date_created() );
        $currentDateTimeStamp = strtotime( current_time( "Y-m-d H:i:s" ) );
        $timDiffMin = round( ($currentDateTimeStamp - $orderDateTimeStamp) / 60 );
        # check the arguments || if time difference is less than 5 mints stop
        
        if ( $order->get_created_via() == 'checkout' and $timDiffMin < 5 ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "400",
                "ERROR: Dabble Submission Stopped!"
            );
            return;
        }
        
        # ++++++++++++ This above of Code Is Not Working | change the Code  ++++++++++++
        #
        $order_data['orderID'] = ( method_exists( $order, 'get_id' ) && is_int( $order->get_id() ) ? $order->get_id() : "" );
        $order_data['billing_first_name'] = ( method_exists( $order, 'get_billing_first_name' ) && is_string( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : "" );
        $order_data['billing_last_name'] = ( method_exists( $order, 'get_billing_last_name' ) && is_string( $order->get_billing_last_name() ) ? $order->get_billing_last_name() : "" );
        $order_data['billing_company'] = ( method_exists( $order, 'get_billing_company' ) && is_string( $order->get_billing_company() ) ? $order->get_billing_company() : "" );
        $order_data['billing_address_1'] = ( method_exists( $order, 'get_billing_address_1' ) && is_string( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : "" );
        $order_data['billing_address_2'] = ( method_exists( $order, 'get_billing_address_2' ) && is_string( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : "" );
        $order_data['billing_city'] = ( method_exists( $order, 'get_billing_city' ) && is_string( $order->get_billing_city() ) ? $order->get_billing_city() : "" );
        $order_data['billing_state'] = ( method_exists( $order, 'get_billing_state' ) && is_string( $order->get_billing_state() ) ? $order->get_billing_state() : "" );
        $order_data['billing_postcode'] = ( method_exists( $order, 'get_billing_postcode' ) && is_string( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : "" );
        # site Current Time
        $order_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $order_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Start
        $order_data['shipping_first_name'] = ( method_exists( $order, 'get_shipping_first_name' ) && is_string( $order->get_shipping_first_name() ) ? $order->get_shipping_first_name() : "" );
        $order_data['shipping_last_name'] = ( method_exists( $order, 'get_shipping_last_name' ) && is_string( $order->get_shipping_last_name() ) ? $order->get_shipping_last_name() : "" );
        $order_data['shipping_company'] = ( method_exists( $order, 'get_shipping_company' ) && is_string( $order->get_shipping_company() ) ? $order->get_shipping_company() : "" );
        $order_data['shipping_address_1'] = ( method_exists( $order, 'get_shipping_address_1' ) && is_string( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() : "" );
        $order_data['shipping_address_2'] = ( method_exists( $order, 'get_shipping_address_2' ) && is_string( $order->get_shipping_address_2() ) ? $order->get_shipping_address_2() : "" );
        $order_data['shipping_city'] = ( method_exists( $order, 'get_shipping_city' ) && is_string( $order->get_shipping_city() ) ? $order->get_shipping_city() : "" );
        $order_data['shipping_state'] = ( method_exists( $order, 'get_shipping_state' ) && is_string( $order->get_shipping_state() ) ? $order->get_shipping_state() : "" );
        $order_data['shipping_postcode'] = ( method_exists( $order, 'get_shipping_postcode' ) && is_string( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : "" );
        #
        $order_data['eventName'] = $order->get_status();
        $order_data['status'] = "wc-" . $order->get_status();
        # Action
        
        if ( empty($order_id) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "713",
                "ERROR: Order is empty !"
            );
        } else {
            $r = $this->wpgsi_eventBoss(
                'Woocommerce',
                'wc-' . $this_status_transition_to,
                $order_data,
                $order_id
            );
        }
    
    }
    
    /**
     * woocommerce_new_orders New Order  HOOK's callback function
     * @since     1.0.0
     * @param     int     $order_id     Order ID
     */
    public function wpgsi_woocommerce_new_order_admin( $order_id )
    {
        $order_data = array();
        # getting order information
        $order = wc_get_order( $order_id );
        # if not admin returns
        if ( empty($order_id) && $order->get_created_via() != 'admin' ) {
            return;
        }
        # check to see is there any integration on this order change Status.
        if ( !$this->wpgsi_integrations( "wc-" . $order->get_status() )[0] ) {
            return;
        }
        #
        $order_data['orderID'] = ( method_exists( $order, 'get_id' ) && is_int( $order->get_id() ) ? $order->get_id() : "" );
        $order_data['billing_first_name'] = ( method_exists( $order, 'get_billing_first_name' ) && is_string( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : "" );
        $order_data['billing_last_name'] = ( method_exists( $order, 'get_billing_last_name' ) && is_string( $order->get_billing_last_name() ) ? $order->get_billing_last_name() : "" );
        $order_data['billing_company'] = ( method_exists( $order, 'get_billing_company' ) && is_string( $order->get_billing_company() ) ? $order->get_billing_company() : "" );
        $order_data['billing_address_1'] = ( method_exists( $order, 'get_billing_address_1' ) && is_string( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : "" );
        $order_data['billing_address_2'] = ( method_exists( $order, 'get_billing_address_2' ) && is_string( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : "" );
        $order_data['billing_city'] = ( method_exists( $order, 'get_billing_city' ) && is_string( $order->get_billing_city() ) ? $order->get_billing_city() : "" );
        $order_data['billing_state'] = ( method_exists( $order, 'get_billing_state' ) && is_string( $order->get_billing_state() ) ? $order->get_billing_state() : "" );
        $order_data['billing_postcode'] = ( method_exists( $order, 'get_billing_postcode' ) && is_string( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : "" );
        # site Current Time
        $order_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $order_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Start
        $order_data['shipping_first_name'] = ( method_exists( $order, 'get_shipping_first_name' ) && is_string( $order->get_shipping_first_name() ) ? $order->get_shipping_first_name() : "" );
        $order_data['shipping_last_name'] = ( method_exists( $order, 'get_shipping_last_name' ) && is_string( $order->get_shipping_last_name() ) ? $order->get_shipping_last_name() : "" );
        $order_data['shipping_company'] = ( method_exists( $order, 'get_shipping_company' ) && is_string( $order->get_shipping_company() ) ? $order->get_shipping_company() : "" );
        $order_data['shipping_address_1'] = ( method_exists( $order, 'get_shipping_address_1' ) && is_string( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() : "" );
        $order_data['shipping_address_2'] = ( method_exists( $order, 'get_shipping_address_2' ) && is_string( $order->get_shipping_address_2() ) ? $order->get_shipping_address_2() : "" );
        $order_data['shipping_city'] = ( method_exists( $order, 'get_shipping_city' ) && is_string( $order->get_shipping_city() ) ? $order->get_shipping_city() : "" );
        $order_data['shipping_state'] = ( method_exists( $order, 'get_shipping_state' ) && is_string( $order->get_shipping_state() ) ? $order->get_shipping_state() : "" );
        $order_data['shipping_postcode'] = ( method_exists( $order, 'get_shipping_postcode' ) && is_string( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : "" );
        #
        $order_data['eventName'] = $order->get_status();
        $order_data['status'] = "wc-" . $order->get_status();
        # Action
        
        if ( empty($order_id) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "714",
                "ERROR: Order is empty !"
            );
        } else {
            $r = $this->wpgsi_eventBoss(
                'Woocommerce',
                $order_data['status'],
                $order_data,
                $order_id
            );
        }
    
    }
    
    /**
     * WooCommerce Checkout PAge Order CallBack Function 
     * @since     1.0.0
     * @param     int     $order_id     Order ID
     */
    public function wpgsi_woocommerce_new_order_checkout( $order_id )
    {
        $order_data = array();
        $order = wc_get_order( $order_id );
        # if not checkout returns
        if ( empty($order_id) && $order->get_created_via() != 'checkout' ) {
            return;
        }
        # check to see is there any integration on this order change Status.
        if ( !$this->wpgsi_integrations( 'wc-new_order' )[0] ) {
            return;
        }
        #
        $order_data['orderID'] = ( method_exists( $order, 'get_id' ) && is_int( $order->get_id() ) ? $order->get_id() : "" );
        $order_data['billing_first_name'] = ( method_exists( $order, 'get_billing_first_name' ) && is_string( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : "" );
        $order_data['billing_last_name'] = ( method_exists( $order, 'get_billing_last_name' ) && is_string( $order->get_billing_last_name() ) ? $order->get_billing_last_name() : "" );
        $order_data['billing_company'] = ( method_exists( $order, 'get_billing_company' ) && is_string( $order->get_billing_company() ) ? $order->get_billing_company() : "" );
        $order_data['billing_address_1'] = ( method_exists( $order, 'get_billing_address_1' ) && is_string( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : "" );
        $order_data['billing_address_2'] = ( method_exists( $order, 'get_billing_address_2' ) && is_string( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : "" );
        $order_data['billing_city'] = ( method_exists( $order, 'get_billing_city' ) && is_string( $order->get_billing_city() ) ? $order->get_billing_city() : "" );
        $order_data['billing_state'] = ( method_exists( $order, 'get_billing_state' ) && is_string( $order->get_billing_state() ) ? $order->get_billing_state() : "" );
        $order_data['billing_postcode'] = ( method_exists( $order, 'get_billing_postcode' ) && is_string( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : "" );
        # site Current Time
        $order_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $order_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Start
        $order_data['shipping_first_name'] = ( method_exists( $order, 'get_shipping_first_name' ) && is_string( $order->get_shipping_first_name() ) ? $order->get_shipping_first_name() : "" );
        $order_data['shipping_last_name'] = ( method_exists( $order, 'get_shipping_last_name' ) && is_string( $order->get_shipping_last_name() ) ? $order->get_shipping_last_name() : "" );
        $order_data['shipping_company'] = ( method_exists( $order, 'get_shipping_company' ) && is_string( $order->get_shipping_company() ) ? $order->get_shipping_company() : "" );
        $order_data['shipping_address_1'] = ( method_exists( $order, 'get_shipping_address_1' ) && is_string( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() : "" );
        $order_data['shipping_address_2'] = ( method_exists( $order, 'get_shipping_address_2' ) && is_string( $order->get_shipping_address_2() ) ? $order->get_shipping_address_2() : "" );
        $order_data['shipping_city'] = ( method_exists( $order, 'get_shipping_city' ) && is_string( $order->get_shipping_city() ) ? $order->get_shipping_city() : "" );
        $order_data['shipping_state'] = ( method_exists( $order, 'get_shipping_state' ) && is_string( $order->get_shipping_state() ) ? $order->get_shipping_state() : "" );
        $order_data['shipping_postcode'] = ( method_exists( $order, 'get_shipping_postcode' ) && is_string( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : "" );
        #
        $order_data['eventName'] = "New order";
        $order_data['status'] = "wc-" . $order->get_status();
        # Action
        
        if ( empty($order_id) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "715",
                "ERROR: Order is empty !"
            );
        } else {
            $r = $this->wpgsi_eventBoss(
                'Woocommerce',
                'wc-new_order',
                $order_data,
                $order_id
            );
        }
    
    }
    
    /**
     * CF7 Form Submission Event || its a HOOK  callback function of Contact form 7 form
     * Contact form 7 is a Disgusting Code || Noting is good of this Plugin || 
     * @since    3.1.0
     * @param    array     $form_data     data_array
     */
    public function wpgsi_cf7_submission( $contact_form )
    {
        $id = $contact_form->id();
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        # if There is a integration on this Form Submission
        if ( !empty($id) and $this->wpgsi_integrations( 'cf7_' . $id )[0] ) {
            
            if ( isset( $id ) && !empty($id) ) {
                # Calling Event Boss
                $r = $this->wpgsi_eventBoss(
                    'cf7',
                    'cf7_' . $id,
                    $posted_data,
                    $id
                );
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "716",
                    "ERROR: Contact form 7 Form Submitted But No Form ID !"
                );
            }
        
        }
    }
    
    /**
     * ninja after saved entry to DB || its a HOOK  callback function of ninja form
     * @since    1.0.0
     * @param    array     $form_data     data_array
     */
    public function wpgsi_ninja_forms_after_submission( $form_data )
    {
        # if There is a integration on this Form Submission
        
        if ( isset( $form_data["form_id"] ) and $this->wpgsi_integrations( 'ninja_' . $form_data["form_id"] ) ) {
            # Empty array holder
            $data = array();
            # Looping the Fields
            foreach ( $form_data["fields"] as $field ) {
                $data[$field["key"]] = $field["value"];
            }
        }
    
    }
    
    /**
     * formidable after saved entry to DB || its a HOOK  callback function of formidable form
     * @since    1.0.0
     * @param    array    $entry_id    Which platform call this function 
     * @param    array    $form_id     event_name 
     */
    public function wpgsi_formidable_after_save( $entry_id, $form_id )
    {
        # if There is a integration on this Form Submission
        if ( !empty($form_id) and $this->wpgsi_integrations( 'frm_' . $form_id )[0] ) {
            # Check to see database table exist or not
            
            if ( $this->wpgsi_dbTableExists( "frm_item_metas" ) ) {
                # Code
                $dataArray = array();
                global  $wpdb ;
                $entrees = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}frm_item_metas WHERE item_id = " . $entry_id . " ORDER BY field_id" );
                foreach ( $entrees as $entre ) {
                    # Convert serialize string to array; So that options are comma separated;
                    $rt = @unserialize( $entre->meta_value );
                    
                    if ( $rt ) {
                        $dataArray[$entre->field_id] = $rt;
                    } else {
                        $dataArray[$entre->field_id] = $entre->meta_value;
                    }
                
                }
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "719",
                    "ERROR: formidable frm_item_metas table is Not Exist!"
                );
            }
        
        }
    }
    
    /**
     * wpforms Submit Action Handler, its a HOOK  callback function of WP form
     * @since      1.0.0
     * @param      array    $fields    		Which platform call this function 
     * @param      array    $entry     		event_name 
     * @param      array    $form_data     	data_array
     */
    public function wpgsi_wpforms_process( $fields, $entry, $form_data )
    {
        # if There is a integration on this Form Submission
        if ( isset( $form_data["id"] ) and $this->wpgsi_integrations( 'wpforms_' . $form_data["id"] )[0] ) {
        }
    }
    
    /**
     * weforms forms_after_submission 
     * @param    string   $entry_id   		entry_id;
     * @param    string   $form_id   		form_id;
     * @param    string   $page_id     		page_id;
     * @param    array    $form_settings    form_data;
     * @since    2.0.0
     */
    public function wpgsi_weforms_entry_submission(
        $entry_id,
        $form_id,
        $page_id,
        $form_settings
    )
    {
        # if There is a integration on this Form Submission
        if ( !empty($form_id) and $this->wpgsi_integrations( 'we_' . $form_id ) ) {
            # Check if frm_item_metas table exists or not
            
            if ( $this->wpgsi_dbTableExists( "frm_item_metas" ) ) {
                # code
                $dataArray = array();
                global  $wpdb ;
                $entrees = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}weforms_entrymeta WHERE weforms_entry_id = " . $entry_id . " ORDER BY meta_id DESC" );
                foreach ( $entrees as $entre ) {
                    $dataArray[$entre->meta_key] = $entre->meta_value;
                }
            } else {
                $this->common->wpgsi_log(
                    get_class( $this ),
                    __METHOD__,
                    "722",
                    "ERROR: weform frm_item_metas table is Not Exist!"
                );
            }
        
        }
    }
    
    /**
     * gravityForms gform_after_submission 
     * @param    array   $entry     All the Entries with Some Extra;
     * @param    array   $formObj   Submitted form Object;
     * @since    3.3.0
     */
    public function wpgsi_gravityForms_after_submission( $entry, $formObj )
    {
        # if There is a integration on this Form Submission
        if ( isset( $entry['form_id'] ) and $this->wpgsi_integrations( 'gravity_' . $entry['form_id'] )[0] ) {
        }
    }
    
    /**
     * forminator forminator_custom_form_submit_field_data 
     * @param    array   $form_data   Data array;
     * @param    array   $form_id    form ID;
     * @since    3.6.0
     */
    public function wpgsi_forminator_custom_form_submit_field_data( $form_data, $form_id )
    {
        if ( isset( $form_id ) and $this->wpgsi_integrations( 'forminator_' . $form_id )[0] ) {
        }
        return $form_data;
    }
    
    /**
     * fluentform forminator_custom_form_submit_field_data 
     * @param    int      $form_id    form    ID;
     * @param    object   $field_data_array   Data array;
     * @param    object   $field_data_array   Data array;
     * @since    3.6.0
     */
    public function wpgsi_fluentform_before_submission_confirmation( $entryId, $formData, $form )
    {
        # Testing Starts
        # if There is a integration on this Form Submission
        if ( isset( $form->id ) and $this->wpgsi_integrations( 'fluent_' . $form->id )[0] ) {
        }
    }
    
    /**
     * Database new row integration, When New row append it will send that to Google Sheet 
     * @since    3.7.1
     */
    public function wpgsi_database_data_update()
    {
    }
    
    /**
     * Third party plugin :
     * Checkout Field Editor ( Checkout Manager) for WooCommerce
     * BETA testing;
     * @since    2.0.0
     */
    public function wpgsi_woo_checkout_field_editor_pro_fields()
    {
        # getting The Active Plugin list
        $active_plugins = get_option( 'active_plugins' );
        # Empty Holder
        $woo_checkout_field_editor_pro = array();
        
        if ( in_array( 'woo-checkout-field-editor-pro/checkout-form-designer.php', $active_plugins ) ) {
            # Getting data from wp options
            $a = get_option( "wc_fields_billing" );
            $b = get_option( "wc_fields_shipping" );
            $c = get_option( "wc_fields_additional" );
            if ( $a ) {
                foreach ( $a as $key => $field ) {
                    
                    if ( isset( $field['custom'] ) && $field['custom'] == 1 ) {
                        $woo_checkout_field_editor_pro[$key]['type'] = $field['type'];
                        $woo_checkout_field_editor_pro[$key]['name'] = $field['name'];
                        $woo_checkout_field_editor_pro[$key]['label'] = $field['label'];
                    }
                
                }
            }
            if ( $b ) {
                foreach ( $b as $key => $field ) {
                    
                    if ( isset( $field['custom'] ) && $field['custom'] == 1 ) {
                        $woo_checkout_field_editor_pro[$key]['type'] = $field['type'];
                        $woo_checkout_field_editor_pro[$key]['name'] = $field['name'];
                        $woo_checkout_field_editor_pro[$key]['label'] = $field['label'];
                    }
                
                }
            }
            if ( $c ) {
                foreach ( $c as $key => $field ) {
                    
                    if ( isset( $field['custom'] ) && $field['custom'] == 1 ) {
                        $woo_checkout_field_editor_pro[$key]['type'] = $field['type'];
                        $woo_checkout_field_editor_pro[$key]['name'] = $field['name'];
                        $woo_checkout_field_editor_pro[$key]['label'] = $field['label'];
                    }
                
                }
            }
            
            if ( empty($woo_checkout_field_editor_pro) ) {
                return array( FALSE, "ERROR: Checkout Field Editor aka Checkout Manager for WooCommerce is EMPTY no Custom Field." );
            } else {
                return array( TRUE, $woo_checkout_field_editor_pro );
            }
        
        } elseif ( in_array( 'woocommerce-checkout-field-editor-pro/woocommerce-checkout-field-editor-pro.php', $active_plugins ) ) {
            # this part is for professional Version of that Plugin;
            # if Check to see class is exists or not
            if ( class_exists( 'For_WCFE_Checkout_Fields_Utils' ) and class_exists( 'WCFE_Checkout_Fields_Utils' ) ) {
                # it declared in the Below of this Class
                For_WCFE_Checkout_Fields_Utils::fields();
            }
        } else {
            return array( FALSE, "ERROR: Checkout Field Editor aka Checkout Manager for WooCommerce is not installed" );
        }
    
    }
    
    /**
     * Centralized Events , All events Will call this Function & feed Data to this Func || It Will Do All event Job 
     * Creating new token if token is not valid . as you know frontend user comes and goes without Notice !
     * @since      1.0.0
     * @param      string    $data_source    Which platform call this function 
     * @param      string    $event_name     event_name 
     * @param      array     $data_array     data_array
     * @param      int    	 $id    		 ID is optional so that , 
     */
    public function wpgsi_eventBoss(
        $data_source = '',
        $event_name = '',
        $data_array = '',
        $id = ''
    )
    {
        # Got the Event Data [Custom Action Hook]	||  raw data hook
        do_action(
            'wpgsi_event_raw',
            $data_source,
            $event_name,
            $data_array,
            $id
        );
        # data_source Empty test;
        
        if ( empty($data_source) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "727",
                "ERROR: data_source  is EMPTY. " . json_encode( array(
                "data_source" => $data_source,
                "event_name"  => $event_name,
                "data_array"  => $data_array,
                "id"          => $id,
            ) )
            );
            return FALSE;
        }
        
        # event_name Empty test;
        
        if ( empty($event_name) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "728",
                "ERROR: event_name  is EMPTY. " . json_encode( array(
                "data_source" => $data_source,
                "event_name"  => $event_name,
                "data_array"  => $data_array,
                "id"          => $id,
            ) )
            );
            return FALSE;
        }
        
        #  data_array Empty test;
        
        if ( empty($data_array) ) {
            $this->common->wpgsi_log(
                get_class( $this ),
                __METHOD__,
                "729",
                "ERROR: data_array  is EMPTY!. " . json_encode( array(
                "data_source" => $data_source,
                "event_name"  => $event_name,
                "data_array"  => $data_array,
                "id"          => $id,
            ) )
            );
            return FALSE;
        }
        
        # below code is changed in v3.7.0 , Good solution for the Problem
        foreach ( $data_array as $firstKey => $firstValue ) {
            # empty holder
            $data = "";
            # if value is string
            if ( is_string( $firstValue ) or is_numeric( $firstValue ) ) {
                $data .= strip_tags( $firstValue );
            }
            # if value is array or object
            if ( is_array( $firstValue ) or is_object( $firstValue ) ) {
                # loop the first stage value
                foreach ( $firstValue as $secondKey => $secondValue ) {
                    # if  first stage value is string
                    if ( is_string( $secondValue ) ) {
                        $data .= $secondValue . ", ";
                    }
                    # if second stage value is array or object
                    if ( is_array( $secondValue ) or is_object( $secondValue ) ) {
                        # loop the second stage value
                        foreach ( $secondValue as $thirdKey => $thirdValue ) {
                            
                            if ( is_string( $thirdValue ) ) {
                                $data .= $thirdValue . ", ";
                            } else {
                                $data .= ( empty($thirdValue) ? "  " : json_encode( $thirdValue ) );
                            }
                        
                        }
                    }
                }
            }
            # inserting value
            $data_array[$firstKey] = $data;
        }
        # If everything okay than Proceed on
        $this->common->wpgsi_log(
            get_class( $this ),
            __METHOD__,
            "200",
            "SUCCESS: okay, on the event A1 . " . json_encode( array(
            "data_source" => $data_array,
            "event_name"  => $event_name,
            "data_array"  => $data_array,
            "id"          => $id,
        ) )
        );
        # Event checked AND before  Passed [Custom Action Hook]  || If you Need Modify Data DO it here;
        do_action(
            'wpgsi_event_before',
            $data_source,
            $event_name,
            $data_array,
            $id
        );
        # Event Passed  [Custom Action Hook]  || Only for GOOGLE || Don't do Anything here - lat it go;
        do_action(
            'wpgsi_khatas',
            $data_source,
            $event_name,
            $data_array,
            $id
        );
        # Sending a True
        return TRUE;
    }
    
    /**
     * This Function will return [wordPress Users] Meta keys.
     * @since      3.2.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wpgsi_users_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT( {$wpdb->usermeta}.meta_key ) FROM {$wpdb->usermeta} ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of users.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Posts] Meta keys.
     * @since      3.2.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wpgsi_posts_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \r\n\t\t\t\t  \tFROM {$wpdb->posts} \r\n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \r\n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'post' \r\n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Pages] Meta keys.
     * @since      3.2.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wpgsi_pages_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \r\n\t\t\t\t\tFROM {$wpdb->posts}\r\n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \r\n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'page' \r\n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post type page.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    # Getting Meta Key of WooCommerce Order, Product, Post, Page, User, Comment Meta Keys
    /**
     * This Function will return [WooCommerce Order] Meta keys.
     * @since      3.2.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wpgsi_wooCommerce_order_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \r\n\t\t\t\t\tFROM {$wpdb->posts} \r\n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \r\n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'shop_order' \r\n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the post type WooCommerce Order.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [WooCommerce product] Meta keys.
     * @since      3.2.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wpgsi_wooCommerce_product_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \r\n\t\t\t\t\tFROM {$wpdb->posts} \r\n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \r\n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'product' \r\n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post type WooCommerce Product.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Users] Meta keys.
     * @since      3.2.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function wpgsi_comments_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT( {$wpdb->commentmeta}.meta_key ) FROM {$wpdb->commentmeta} ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist on comment meta' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will All Custom Post types 
     * @since      3.3.0
     * @return     array   First one is CPS and Second one is CPT's Field source.
     */
    public function wpgsi_allCptEvents()
    {
        # Getting The Global wp_post_types array
        global  $wp_post_types ;
        # Check And Balance
        
        if ( isset( $wp_post_types ) && !empty($wp_post_types) ) {
            # CPT holder empty array declared
            $cpts = array();
            # List of items for removing
            $removeArray = array(
                "wpforms",
                "acf-field-group",
                "acf-field",
                "product",
                "product_variation",
                "shop_order",
                "shop_order_refund"
            );
            # Looping the Post types
            foreach ( $wp_post_types as $postKey => $PostValue ) {
                # if Post type is Not Default
                if ( isset( $PostValue->_builtin ) and !$PostValue->_builtin ) {
                    # Look is it on remove list, if not insert
                    if ( !in_array( $postKey, $removeArray ) ) {
                        # Pre populate $cpts array
                        
                        if ( isset( $PostValue->label ) and !empty($PostValue->label) ) {
                            $cpts[$postKey] = $PostValue->label . " (" . $postKey . ")";
                        } else {
                            $cpts[$postKey] = $postKey;
                        }
                    
                    }
                }
            }
            # Empty Holder Array for CPT events
            $cptEvents = array();
            # Creating events
            
            if ( !empty($cpts) ) {
                # Looping for Creating Extra Events Like Update and Delete
                foreach ( $cpts as $key => $value ) {
                    $cptEvents['cpt_new_' . $key] = 'CPT New ' . $value;
                    $cptEvents['cpt_update_' . $key] = 'CPT Update ' . $value;
                    $cptEvents['cpt_delete_' . $key] = 'CPT Delete ' . $value;
                }
                # Now setting default Event data Source Fields; Those events data source  are common in all WordPress Post type
                $eventDataFields = array(
                    "postID"            => "ID",
                    "post_authorID"     => "post author_ID",
                    "authorUserName"    => "author User Name",
                    "authorDisplayName" => "author Display Name",
                    "authorEmail"       => "author Email",
                    "authorRole"        => "author Role",
                    "post_title"        => "post title",
                    "post_date"         => "post date",
                    "post_date_gmt"     => "post date gmt",
                    "site_time"         => "Site Time",
                    "site_date"         => "Site Date",
                    "post_content"      => "post content",
                    "post_excerpt"      => "post excerpt",
                    "post_status"       => "post status",
                    "comment_status"    => "comment status",
                    "ping_status"       => "ping status",
                    "post_password"     => "post password",
                    "post_name"         => "post name",
                    "to_ping"           => "to ping",
                    "pinged"            => "pinged",
                    "post_modified"     => "post modified date",
                    "post_modified_gmt" => "post modified date GMT",
                    "post_parent"       => "post parent",
                    "guid"              => "guid",
                    "menu_order"        => "menu order",
                    "post_type"         => "post type",
                    "post_mime_type"    => "post mime type",
                    "comment_count"     => "comment count",
                    "filter"            => "filter",
                );
                # Global Db object
                global  $wpdb ;
                # Query for getting Meta keys
                $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \r\n\t\t\t\t\t\t\tFROM {$wpdb->posts} \r\n\t\t\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \r\n\t\t\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\t\t\t\t\tWHERE {$wpdb->posts}.post_type != 'post' \r\n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'page' \r\n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'product' \r\n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'shop_order' \r\n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'shop_order_refund' \r\n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'product_variation' \r\n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type \t != 'wpforms' \r\n\t\t\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
                # execute Query for getting the Post meta key it will use for event data source
                $meta_keys = $wpdb->get_col( $query );
                # Inserting Meta keys to Main $eventDataFields Array;
                
                if ( !empty($meta_keys) and is_array( $meta_keys ) ) {
                    foreach ( $meta_keys as $value ) {
                        if ( !isset( $eventDataFields[$value] ) ) {
                            $eventDataFields[$value] = "CPT Meta " . $value;
                        }
                    }
                } else {
                    # insert to the log but don't return
                    # ERROR:  Meta keys are empty;
                }
                
                # Everything seems ok, Now send the CPT events and Related Data source;
                return array(
                    TRUE,
                    $cpts,
                    $cptEvents,
                    $eventDataFields,
                    $meta_keys
                );
            } else {
                return array( FALSE, "ERROR: cpts Array is Empty." );
            }
        
        } else {
            return array( FALSE, "ERROR: wp_post_types global array is not exists or Empty." );
        }
    
    }
    
    /**
     * This is a Helper function to check is There Any integration saved. Also set the transient cache
     * @since      3.4.0
     * @param      string    $data_source    Which platform call this function s
     */
    public function wpgsi_integrations( $DataSourceID = '' )
    {
        # getting the Options
        $integrations = get_transient( "wpgsi_integrations" );
        # Number of published Integration
        $publish = 0;
        # Number of Pending Integration
        $pending = 0;
        # Setting Default Value
        $integrationForDataSource = FALSE;
        # Setting Empty Array
        $integrationsArray = array();
        #  from Cache or From DB
        
        if ( $integrations and is_array( $integrations ) ) {
            # integration loop starts for Counting the publish and pending Statuses
            foreach ( $integrations as $value ) {
                # Testing if DataSource is Exist or Not
                if ( $value["DataSourceID"] == $DataSourceID and $value["Status"] == "publish" ) {
                    $integrationForDataSource = TRUE;
                }
                # Counting Publish
                if ( $value["Status"] == 'publish' ) {
                    $publish++;
                }
                # Counting pending
                if ( $value["Status"] == 'pending' ) {
                    $pending++;
                }
            }
            # return  array with First Value as Bool and second one is integrationsArray array
            return array(
                $integrationForDataSource,
                $integrations,
                $publish,
                $pending,
                "From transient"
            );
        } else {
            # Getting All Posts
            $listOfConnections = get_posts( array(
                'post_type'      => 'wpgsiIntegration',
                'post_status'    => array( 'publish', 'pending' ),
                'posts_per_page' => -1,
            ) );
            # integration loop starts
            foreach ( $listOfConnections as $key => $value ) {
                # Compiled to JSON String
                $post_excerpt = json_decode( $value->post_excerpt, TRUE );
                # if JSON Compiled SUCCESSfully
                
                if ( is_array( $post_excerpt ) and !empty($post_excerpt) ) {
                    $integrationsArray[$key]["IntegrationID"] = $value->ID;
                    $integrationsArray[$key]["DataSource"] = $post_excerpt["DataSource"];
                    $integrationsArray[$key]["DataSourceID"] = $post_excerpt["DataSourceID"];
                    $integrationsArray[$key]["Worksheet"] = $post_excerpt["Worksheet"];
                    $integrationsArray[$key]["WorksheetID"] = $post_excerpt["WorksheetID"];
                    $integrationsArray[$key]["Spreadsheet"] = $post_excerpt["Spreadsheet"];
                    $integrationsArray[$key]["SpreadsheetID"] = $post_excerpt["SpreadsheetID"];
                    $integrationsArray[$key]["Status"] = $value->post_status;
                    # Testing if DataSource is Exist or Not
                    if ( $post_excerpt["DataSourceID"] == $DataSourceID and $value->post_status == "publish" ) {
                        $integrationForDataSource = TRUE;
                    }
                    # Counting Publish
                    if ( $integrationsArray[$key]["Status"] == 'publish' ) {
                        $publish++;
                    }
                    # Counting pending
                    if ( $integrationsArray[$key]["Status"] == 'pending' ) {
                        $pending++;
                    }
                }
            
            }
            # updating the options cache
            set_transient( 'wpgsi_integrations', $integrationsArray );
            # return  array with First Value as Bool and second one is integrationsArray array
            return array(
                $integrationForDataSource,
                $integrationsArray,
                $publish,
                $pending,
                "From DB"
            );
        }
    
    }
    
    /**
     * This is a Helper function to check Table is Exist or Not 
     * If DB table Exist it will return True if Not it will return False
     * @since      3.2.0
     * @param      string    $data_source    Which platform call this function s
     */
    public function wpgsi_dbTableExists( $tableName = null )
    {
        # Check and Balance
        if ( empty($tableName) ) {
            return FALSE;
        }
        #
        global  $wpdb ;
        $r = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . $tableName . "'" );
        
        if ( $r ) {
            return TRUE;
        } else {
            return FALSE;
        }
    
    }

}
# Below code is Out side of the Parent Class s
# Spacial class for getting Data from " Checkout Field Editor aka Checkout Manager for WooCommerce" professional version;

if ( class_exists( 'WCFE_Checkout_Fields_Utils' ) ) {
    # Class starts
    class For_WCFE_Checkout_Fields_Utils extends WCFE_Checkout_Fields_Utils
    {
        # static method
        # This Static Method will return a nested array;
        public static function fields()
        {
            # Check to see is method exist on the Parent class
            
            if ( method_exists( 'WCFE_Checkout_Fields_Utils', 'get_all_custom_checkout_fields' ) ) {
                # Creating Empty Array
                $woo_checkout_field_editor_pro = array();
                # Calling Parent method
                $custom_field_list = parent::get_all_custom_checkout_fields();
                # Populating The array
                foreach ( $custom_field_list as $key => $val ) {
                    $woo_checkout_field_editor_pro[$key]['type'] = $val->type;
                    $woo_checkout_field_editor_pro[$key]['name'] = $val->name;
                    $woo_checkout_field_editor_pro[$key]['label'] = "CFE - " . $val->title;
                }
                # return Value
                
                if ( empty($woo_checkout_field_editor_pro) ) {
                    return array( FALSE, "ERROR: Checkout Field Editor aka Checkout Manager for WooCommerce is EMPTY no Custom Field." );
                } else {
                    return array( TRUE, $woo_checkout_field_editor_pro );
                }
            
            } else {
                # if method is not exist;
                return array( FALSE, "ERROR: This get_all_custom_checkout_fields() method is not exists in the For_WCFE_Checkout_Fields_Utils class;" );
            }
        
        }
    
    }
    # Class is ends
}

#------------------------------- TODO: -------------------------------
#----------------------------- 14/jul/2021 ---------------------------
# 1. Add Hook For Custom Platform						[x] Fix it Toady
# 2. 													[x] Fix it Toady
# 3.
#-------------------------------- FIXME: -----------------------------