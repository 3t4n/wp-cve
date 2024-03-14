<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for admin Booking meta boxes
 */
class EventM_Booking_Admin_Meta_Boxes {

	/**
	 * Is meta boxes saved once?
	 *
	 * @var boolean
	 */
	private static $ep_saved_event_meta_boxes = false;

	/**
	 * Constructor
	 */
	public function __construct() {
        add_action( 'init', array($this, 'remove_defult_fields'), 99);
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_bookings_meta_box_scripts' ) );
        add_action( 'add_meta_boxes', array( $this, 'ep_bookings_remove_meta_boxes' ), 10 );
        add_action( 'add_meta_boxes', array( $this, 'ep_bookings_register_meta_boxes' ), 1 );
        add_filter( 'post_row_actions', array( $this, 'ep_remove_actions' ), 10, 2 );
        add_filter( 'manage_em_booking_posts_columns', array( $this, 'ep_filter_booking_columns' ) );
        add_action( 'manage_em_booking_posts_custom_column', array( $this, 'ep_filter_booking_columns_content' ), 10, 2 );
        add_action( 'restrict_manage_posts', array( $this,'ep_booking_filters' ) );
        add_filter( 'parse_query', array( $this, 'ep_booking_filters_argu' ) );
        add_filter( 'months_dropdown_results', array( $this,'ep_booking_filters_remove_date' ) );
        
        add_filter( 'bulk_actions-edit-em_booking', array( $this,'ep_export_booking_bulk_list' ), 10, 1 );
        add_filter( 'handle_bulk_actions-edit-em_booking', array( $this, 'ep_export_booking_bulk_action_handle' ), 10, 3 );
        add_action( 'admin_notices', array( $this, 'ep_admin_notice_export' ) );
        add_action( 'admin_head-edit.php', array( $this, 'ep_add_booking_export_btn' ) );
    }
        
    /**
     * Enqueue meta box scripts
    */

    public function enqueue_admin_bookings_meta_box_scripts() {
        $current_screen = get_current_screen();
        if( $current_screen ->post_type === "em_booking" ) {
            wp_enqueue_style('em-admin-jquery-ui');
            wp_enqueue_style(
                'em-bookings-css',
                EP_BASE_URL . '/includes/bookings/assets/css/ep-abmin-booking-style.css',
                false, EVENTPRIME_VERSION
            );

            // booking js
            wp_enqueue_script(
                'em-booking-js',
                EP_BASE_URL . '/includes/bookings/assets/js/ep-event-booking-admin.js',
                array( 'jquery', 'jquery-ui-datepicker' ), EVENTPRIME_VERSION
            );
        }
    }
       
    /*
     * Remove Editor
     */
    public function remove_defult_fields(){
        $args_completed = array(
            'label'                     =>  _x('Completed', 'Completed', 'z' ),
            'label_count'               =>  _n_noop('Completed (%s)',  'Completed (%s)', 'z'),
            'public'                    =>  true,
            'show_in_admin_all_list'    =>  true,
            'show_in_admin_status_list' =>  true,
            'exclude_from_search'       =>  true,
            'post_type'                 => array( 'em_booking' )
        );
        $args_cancelled = array(
            'label'                     =>  _x('Cancelled', 'Cancelled', 'z' ),
            'label_count'               =>  _n_noop('Cancelled (%s)',  'Cancelled (%s)', 'z'),
            'public'                    =>  true,
            'show_in_admin_all_list'    =>  true,
            'show_in_admin_status_list' =>  true,
            'exclude_from_search'       =>  true,
            'post_type'                 => array( 'em_booking' )
        );
        $args_refunded = array(
            'label'                     =>  _x('Refunded', 'Refunded', 'z' ),
            'label_count'               =>  _n_noop('Refunded (%s)',  'Refunded (%s)', 'z'),
            'public'                    =>  true,
            'show_in_admin_all_list'    =>  true,
            'show_in_admin_status_list' =>  true,
            'exclude_from_search'       =>  true,
            'post_type'                 => array( 'em_booking' )
        );
        $args_pending = array(
            'label'                     =>  _x('Pending', 'Pending', 'z' ),
            'label_count'               =>  _n_noop('Pending (%s)',  'Pending (%s)', 'z'),
            'public'                    =>  true,
            'show_in_admin_all_list'    =>  true,
            'show_in_admin_status_list' =>  true,
            'exclude_from_search'       =>  true,
            'post_type'                 => array( 'em_booking' )
        );
        global $typenow;

        //if ( $typenow == 'em_booking' ) {
            register_post_status('completed', $args_completed);
            register_post_status('cancelled', $args_cancelled);
            register_post_status('refunded', $args_refunded);
            register_post_status('pending', $args_pending);
        //}
        
        remove_post_type_support( 'em_booking', 'editor' );
        remove_post_type_support( 'em_booking', 'title' );
    }

	/**
	 * Remove default meta boxes
	 */
	public function ep_bookings_remove_meta_boxes() {
		remove_meta_box( 'postexcerpt', 'em_booking', 'normal' );
		remove_meta_box( 'commentsdiv', 'em_booking', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'em_booking', 'side' );
		remove_meta_box( 'commentstatusdiv', 'em_booking', 'normal' );
		remove_meta_box( 'postcustom', 'em_booking', 'normal' );
		remove_meta_box( 'pageparentdiv', 'em_booking', 'side' );
        remove_meta_box( 'postimagediv', 'em_booking', 'side' );
        remove_meta_box( 'postdivrich', 'em_booking', 'normal' );
        remove_meta_box( 'submitdiv', 'em_booking', 'side' );
	}

	/**
	 * Register meta box for event
	 */
	public function ep_bookings_register_meta_boxes() {
        add_meta_box(
			'ep_booking_general',
			esc_html__( 'General Details', 'eventprime-event-calendar-management' ),
			array( $this, 'ep_general_booking_box' ),
			'em_booking', 'normal', 'high'
		);

        add_meta_box( 
			'ep_booking_tickets', 
			esc_html__( 'Event Tickets', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_tickets_booking_box' ),
			'em_booking', 'normal', 'low' 
		);
        add_meta_box( 
			'ep_tickets_attendies', 
			esc_html__( 'Tickets Attendees', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_tickets_attendies_box' ),
			'em_booking', 'normal', 'low' 
		);
        add_meta_box( 
			'ep_booking_notes', 
			esc_html__( 'Booking Notes', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_booking_notes_box' ),
			'em_booking', 'side', 'low' 
		);

        add_meta_box( 
			'ep_tickets_booking_fields', 
			esc_html__( 'Booking Fields Data', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_tickets_booking_fields_box' ),
			'em_booking', 'normal', 'low' 
		);

        do_action( 'ep_bookings_register_meta_boxes_addon');

        add_meta_box( 
			'ep_transacton_log', 
			esc_html__( 'Transaction Log', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_transacton_log_box' ),
			'em_booking', 'normal', 'low' 
		);
	}
        
    /*
     * General Booking Section
     */
    public function ep_general_booking_box( $post ): void {
        if( $post->post_type == 'em_booking' ) {
            wp_enqueue_style( 'em-bookings-css' );
            wp_enqueue_script( 'em-bookings-js' );
            
            wp_nonce_field( 'ep_save_booking_data', 'ep_booking_meta_nonce' );
            include_once __DIR__ .'/views/meta-box-booking-general.php';
        }
	}
        
    /*
     * General Booking Section
     */
    public function ep_tickets_booking_box( $post ): void {
		include_once __DIR__ .'/views/meta-box-booking-tickets.php';
	}
        
    /*
     * Attendies Section
     */
    public function ep_tickets_attendies_box( $post ): void {
        wp_nonce_field( 'ep_booking_attendee_data', 'ep_booking_attendee_data_nonce' );
		include_once __DIR__ .'/views/meta-box-booking-attendees.php';
	}
        
    /*
     * General Notes Section
     */
    public function ep_booking_notes_box( $post ): void {
        include_once __DIR__ .'/views/meta-box-booking-notes.php';
	}
        
    /*
     * Action button 
     */
    public function ep_booking_actions_box($post):void {
        include_once __DIR__ .'/views/meta-box-booking-action.php';
    }
    
    /*
     * Transaction log section 
     */
    public function ep_transacton_log_box($post):void {
        include_once __DIR__ .'/views/meta-box-transaction-log.php';
    }

    /**
     * Show booking fields data
     */
    public function ep_tickets_booking_fields_box( $post ): void {
		include_once __DIR__ .'/views/meta-box-booking-fields-data.php';
	}
        
    /*
	 * Adding Sponsor logo in List Column
	 */
	public function ep_filter_booking_columns($defaults){
		$offset = 2;
        unset( $defaults['comments'] );
        unset( $defaults['date'] );
        unset( $defaults['title'] );
        $sponsor_column = array(
            'ep_title'          => esc_html__( 'Event', 'eventprime-event-calendar-management' ),
            'ep_booking_id'     => esc_html__( 'Booking ID', 'eventprime-event-calendar-management' ),
            'ep_user_email'     => esc_html__( 'User Email', 'eventprime-event-calendar-management' ),
            'ep_event_date'     => esc_html__( 'Event Date', 'eventprime-event-calendar-management' ),
            'ep_attendees'      => esc_html__( 'No. Of Attendees', 'eventprime-event-calendar-management' ),
            'ep_status'         => esc_html__( 'Booking Status', 'eventprime-event-calendar-management' ),
            'ep_gateway'        => esc_html__( 'Payment Gateway', 'eventprime-event-calendar-management' ),
            'ep_payment_status' => esc_html__( 'Payment Status', 'eventprime-event-calendar-management' ),
        );
		return array_merge(array_slice($defaults, 0, $offset), $sponsor_column, array_slice($defaults, $offset, null));
	}
	
    /*
     * Adding Booking list content
     */
	public function ep_filter_booking_columns_content( $column_name, $post_id ) {
        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );

        $booking = $this->get_booking_cache( $post_id );
        if( empty( $get_booking_cache_data ) ) {
            $booking = $booking_controller->load_booking_detail( $post_id, false );
            $this->set_booking_cache( $post_id, $booking );
        }
        $booking_event_id = $booking->em_event;
        $payment_method = '';
        if( ! empty( $booking->em_payment_method ) ) {
            $payment_method = ucfirst( $booking->em_payment_method );
        } else{
            if( ! empty( $booking->em_order_info['payment_gateway'] ) ) {
                $payment_method = ucfirst( $booking->em_order_info['payment_gateway'] );
            }
        }
        if( $column_name == 'ep_booking_id' ) {?>
            <strong>
                <?php echo '#'. absint( $post_id );?>
            </strong><?php
        }
        if( $column_name == 'ep_title' ){
            $oldtitle = get_the_title();
            if( ! empty( $oldtitle ) ) {?>
                <strong>
                    <a class="row-title" href="<?php echo esc_url( get_edit_post_link() );?>">
                        <?php echo esc_html( $oldtitle );?>
                        <?php do_action( 'ep_booking_list_content_after_event_title', $booking );?>
                    </a>
                </strong><?php
            } else{
                $booking_em_name = get_post_meta( $booking_event_id, 'em_name', true );
                if( ! empty( $booking_em_name ) ) {?>
                    <strong>
                        <a class="row-title" href="<?php echo esc_url( get_edit_post_link() );?>">
                            <?php echo esc_html( $booking_em_name );?>
                            <?php do_action( 'ep_booking_list_content_after_event_title', $booking );?>
                        </a>
                    </strong><?php
                }
            }
        }
        if( $column_name == 'ep_user_email' ) {?>
            <span>
                <?php $user_id = isset( $booking->em_user ) ? (int)$booking->em_user : 0;
                    if( $user_id ) {
                        $user = get_userdata( $user_id );
                        echo esc_html( $user->user_email );
                    }else{
                        $order_info = $booking->em_order_info;
                        if( ! empty( $order_info ) && ! empty( $order_info['user_email'] ) ) {
                            echo esc_html( $order_info['user_email'] );
                        } else{
                            echo esc_html__( 'Guest', 'eventprime-event-calendar-management' );
                        }
                    }
                ?>
            </span><?php
        }
                
        if( $column_name == 'ep_event_date'){
            $em_start_date = get_post_meta( $booking_event_id, 'em_start_date', true );
            if( ! empty( $em_start_date ) ){?>
                <span>
                    <?php echo esc_html( ep_timestamp_to_date( $em_start_date, 'dS M Y', 1 ) );
                    $em_start_time = get_post_meta( $booking_event_id, 'em_start_time', true );
                        if( ! empty( $em_start_time ) ) {
                        echo ', ' . esc_html( $em_start_time );
                    }?>
                </span><?php
            }else{
                echo '--';
            }
        }
        if( $column_name == 'ep_attendees' ) {
            if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0) {
                $attendee_count = 0;
                if( isset( $booking->em_old_ep_booking ) && $booking->em_old_ep_booking == 1 ) {
                    $attendee_count = $booking->em_order_info['quantity'];
                } else{
                    foreach( $booking->em_attendee_names as $attendee_data ) {
                        if( ! empty( $attendee_data ) ) {
                            $attendee_data = (array)$attendee_data;
                        }
                        $attendee_count += count( $attendee_data );
                    }
                }
                echo absint( $attendee_count );
            } else{
                echo '--';
            }
        }
        if( $column_name === 'ep_status' ) {
            if( ! empty( $booking->em_status ) ) {
                if( $booking->em_status == 'publish' || $booking->em_status == 'completed' ) {?>
                    <span class="ep-booking-status ep-status-confirmed">
                        <?php esc_html_e( 'Completed', 'eventprime-event-calendar-management' );?>
                        <span class="ep-booking-status-icons dashicons dashicons-yes"></span>
                    </span><?php
                }
                if( $booking->em_status == 'pending' ) {?>
                    <span class="ep-booking-status ep-status-pending">
                        <?php esc_html_e( 'Pending', 'eventprime-event-calendar-management' );?>
                    </span> <?php
                }
                if( $booking->em_status == 'cancelled' ) {?>
                    <span class="ep-booking-status ep-status-cancelled">
                        <?php esc_html_e( 'Cancelled', 'eventprime-event-calendar-management' );?>
                    </span><?php
                }
                if( $booking->em_status == 'refunded' ) {?>
                    <span class="ep-booking-status ep-status-refunded">
                        <?php esc_html_e( 'Refunded', 'eventprime-event-calendar-management' );?>
                    </span><?php
                }
                if( $booking->em_status == 'draft' ) {?>
                    <span class="ep-booking-status ep-status-draft">
                        <?php esc_html_e( 'Draft', 'eventprime-event-calendar-management' );?>
                    </span><?php
                }
            } else{
                $booking_status = $booking->post_data->post_status;
                if( ! empty( $booking_status ) ) {?>
                    <span class="ep-booking-status ep-status-<?php echo esc_attr( $booking_status );?>">
                        <?php esc_html_e( EventM_Constants::$status[$booking_status], 'eventprime-event-calendar-management' );?>
                    </span><?php
                } else{
                    echo '--';
                }
            }
		}
        if( $column_name == 'ep_gateway' ) {
            if( ! empty( $payment_method ) ) {
                echo esc_html( $payment_method );
            } else{
                echo '--';
            }
        }
        if( $column_name == 'ep_payment_status' ) {
            $payment_log = isset( $booking->em_payment_log ) ? $booking->em_payment_log : array();
            if( ! empty( $payment_log ) ) {
                if( strtolower( $payment_method ) == 'offline' ) {
                    echo isset( $payment_log['offline_status'] ) ? esc_html( $payment_log['offline_status'] ) : '';
                } else{
                    $payment_status = $payment_log['payment_status'];
                    if( ! empty( $payment_status ) ) {
                        if( $payment_status == 'completed' ) {
                            echo esc_html( EventM_Constants::$offline_status['Received'] );
                        } else{
                            echo esc_html( ucfirst( $payment_status ) );
                        }
                    }
                }
            } else{
                echo '--';
            }
        }
	}
        
    public function ep_remove_actions( $actions, $post ) {
        if ( $post->post_type == 'em_booking' ) {
            unset( $actions['edit'] );
            unset( $actions['trash'] );
            unset( $actions['view'] );
            unset( $actions['inline hide-if-no-js'] );
        }
        return $actions;
    }
        
    /*
     * Adding Filter to booking
     */
    public function ep_booking_filters(){
        global $typenow;
        
        $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
        $events = $event_controller->get_events_field_data(array( 'id', 'name' ) );
        
        if ( $typenow == 'em_booking' ) {
            $selected_event = '';
            if( isset( $_GET['event_id'] ) ) {
                $selected_event = absint(sanitize_text_field($_GET['event_id']));
            }?>
            <select name="event_id" id="ep_event_id">
                <option value="all"><?php esc_html_e( 'All Events', 'eventprime-event-calendar-management' );?></option>
                <?php foreach( $events as $event ) {?>
                    <option value="<?php echo esc_attr( $event['id'] ); ?>" <?php selected( $event['id'], $selected_event ); ?>><?php echo esc_attr( $event['name'] ); ?></option>
                <?php } ?>
            </select>
            <input type="hidden" id="ep_booking_event_id" value="<?php echo !empty($selected_event) ? $selected_event : 'all';?>"><?php
            $payment_method = apply_filters('ep_payments_gateways_list', array());
            $selected_payment_method = $start_date = $end_date = '';
            if (isset($_GET['em_start_date']) && preg_match("/^[0-9-]+$/", $_GET['em_start_date'])){
                $start_date = sanitize_text_field($_GET['em_start_date']);
            }
            if (isset($_GET['em_end_date']) && preg_match("/^[0-9-]+$/", $_GET['em_end_date'])){
                $end_date = sanitize_text_field($_GET['em_end_date']);
            }
            
            if( isset( $_GET['payment_method'] ) ) {
                $selected_payment_method = sanitize_text_field($_GET['payment_method']);
            } ?>
            <select name="payment_method" id="ep_booking_payment">
                <option value="all"><?php esc_html_e( 'All Payment Methods', 'eventprime-event-calendar-management' );?></option>
                <?php foreach( $payment_method as $key => $payment ) {?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $selected_payment_method ); ?>><?php echo esc_attr( $payment['method'] ); ?></option>
                <?php } ?>
            </select>

            <span><?php esc_html_e( 'Start Date', 'eventprime-event-calendar-management' );?></span>
            <input type="date" id="ep_booking_start_date" name="em_start_date" value="<?php echo isset($_GET['em_start_date']) ? $start_date : '';?>" placeholder="<?php esc_html_e( 'Start Date', 'eventprime-event-calendar-management' );?>"/>
            
            <span><?php esc_html_e( 'End Date', 'eventprime-event-calendar-management' );?></span>
            <input type="date" id="ep_booking_end_date" name="em_end_date" value="<?php echo isset($_GET['em_end_date']) ? $end_date : '';?>" placeholder="<?php esc_html_e( 'End Date', 'eventprime-event-calendar-management' );?>"/><?php 
        }
    }
    
    /*
     * Modify Filter Query
     */
    public function ep_booking_filters_argu($query ){
        global $pagenow;
        $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
        if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_booking' && isset( $_GET['event_id'] ) && $_GET['event_id'] !='all' ) {
            $query->query_vars['meta_key'] = 'em_event';
            $query->query_vars['meta_value'] = absint(sanitize_text_field($_GET['event_id']));
            $query->query_vars['meta_compare'] = '=';
        }
        if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_booking' && isset( $_GET['payment_method'] ) && $_GET['payment_method'] !='all' ) {
            $query->query_vars['meta_key'] = 'em_payment_method';
            $query->query_vars['meta_value'] = sanitize_text_field(trim($_GET['payment_method']));
            $query->query_vars['meta_compare'] = '=';
        }
        
        if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_booking' && isset( $_GET['em_start_date'] ) && !empty($_GET['em_start_date']) ) {
            $start_date = $_GET['em_start_date'];
            $query->query_vars['meta_key'] = 'em_date';
            $query->query_vars['meta_value'] = strtotime($start_date);
            $query->query_vars['meta_compare'] = '>=';
            $query->query_vars['meta_type'] = 'NUMERIC';
        }
        
        if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_booking' && isset( $_GET['em_end_date'] ) && !empty($_GET['em_end_date']) ) {
            $end_date = $_GET['em_end_date'];
            $query->query_vars['meta_key'] = 'em_date';
            $query->query_vars['meta_value'] = strtotime($end_date);
            $query->query_vars['meta_compare'] = '<=';
            $query->query_vars['meta_type'] = 'NUMERIC';
        }

        $query = apply_filters( 'ep_add_booking_filter_query', $query );
        return $query;
    }
    
    /*
     * Remove Date Filter
     */
    public function ep_booking_filters_remove_date($months){
        global $typenow;
        if ( $typenow == 'em_booking' ) {
            return array();
        }
        return $months;
    }
    
    /*
     * Add Export Option in Bulk Actions
     */
    public function ep_export_booking_bulk_list($bulk_actions) {
        $bulk_actions['ep_export_booking'] = esc_html__( 'Export', 'eventprime-event-calendar-management' );
        return $bulk_actions;
    }
    
    /*
     * Handle Exports
     */
    public function ep_export_booking_bulk_action_handle($redirect_url, $action, $post_ids){
        $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        if ( $action == 'ep_export_booking' ) {
            if( ! empty( $post_ids ) && count( $post_ids ) > 0 ) {
               $booking_controller->export_bookings_bulk_action( 'selected_export', $post_ids );
            }
	    }
	    return $redirect_url;
    }
    
    /*
     * Bulk Action export notice
     */
    public function ep_admin_notice_export(){
        if ( ! empty( $_REQUEST['ep_export_booking'] ) ) {
            $num_changed = (int) $_REQUEST['ep_export_booking'];
            printf( '<div id="message" class="updated notice is-dismissable"><p>' . esc_html__( 'Exported %d bookings.', 'eventprime-event-calendar-management' ) . '</p></div>', $num_changed );
	    }
    }

    /*
     * Add Export all Button
     */
    public function ep_add_booking_export_btn(){
        global $current_screen;

        // Not our post type, exit earlier
        // You can remove this if condition if you don't have any specific post type to restrict to. 
        if ('em_booking' != $current_screen->post_type) {
            return;
        }
        $export_all_btn = __( 'Export All', 'eventprime-event-calendar-management' );?>
        <script type="text/javascript">
            jQuery( document ).ready( function($) {
                let exp_btn_label = '<?php echo esc_html( $export_all_btn ); ?>';
                let exp_btn = '<a id="ep_export_booking_all_btn" class="ep_export_booking_all_btn add-new-h2">'+ exp_btn_label +'</a>';
                $( $( ".wrap h1.wp-heading-inline" )[0] ).append( exp_btn );
            });
        </script><?php
    }


    public function get_booking_cache( $booking_id ) {
        $key = 'ep_admin_all_bookings_data';
        $all_booking_data = get_transient( $key );
        if( ! empty( $all_booking_data[$booking_id] ) ) {
            return $all_booking_data[$booking_id];
        } else{
            $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
            $booking = $booking_controller->load_booking_detail( $booking_id );
            $this->set_booking_cache( $booking_id, $booking );
            $all_booking_data = get_transient( $key );
            if( ! empty( $all_booking_data[$booking_id] ) ) {
                return $all_booking_data[$booking_id];
            }
        }
    }

    public function set_booking_cache( $booking_id, $booking_data ) {
        $key = 'ep_admin_all_bookings_data';
        $all_booking_data = ( ! empty( get_transient( $key ) ) ? get_transient( $key ) : array() );
        $all_booking_data[$booking_id] = $booking_data;
        set_transient( $key, $all_booking_data, 3600 );
    }
}

new EventM_Booking_Admin_Meta_Boxes();