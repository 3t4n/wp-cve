<?php
defined( 'ABSPATH' ) || exit;
/**
 * this class is responsible for all tickera operations
 */

/**
 *
 */
class Pos_Bridge_Tickera {
    public function __construct() {
        # code...
    }

    public function oliver_pos_is_tickera_active() {
        $v = false;
        if ( is_plugin_active( 'tickera/tickera.php' ) ) {
            $v = true;
        }
        return $v;
    }

    public function oliver_pos_tickera_custom_forms() {
        $forms = array();
        $args = array(
            'post_type' => 'tc_forms',
            'post_status' => array('publish'),
            'posts_per_page' => -1,
        );

        $loop = new WP_Query( $args );
        while ($loop->have_posts()):
            $loop->the_post();
            $post_id = (int) $loop->post->ID;
            $forms[] = $this->oliver_pos_get_tickera_post( $post_id );
        endwhile;

        return empty($forms) ? array() : $forms;
    }

    public function oliver_pos_tickera_custom_form( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['form_id']) && !empty($parameters['form_id'])) {
            return $this->oliver_pos_get_tickera_post( sanitize_text_field($parameters['form_id']));
        }
        return array();
    }

    public function oliver_pos_tickera_custom_form_fields( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['form_id']) && !empty($parameters['form_id'])) {
            return $this->oliver_pos_get_tickera_custom_form_fields( sanitize_text_field($parameters['form_id']));
        }
        return array();
    }

    private function oliver_pos_get_tickera_custom_form_fields( $form_id = 0 ) {
        $data = array();
        $args = array(
            'post_type' => 'tc_form_fields',
            'post_status' => array('publish'),
            'post_parent' => $form_id,
            'posts_per_page' => -1,
        );

        $loop = new WP_Query( $args );
        while ($loop->have_posts()):
            $loop->the_post();
            $post_id = (int) $loop->post->ID;
            $data[] = $this->oliver_pos_get_tickera_post( $post_id );
        endwhile;

        return empty($data) ? array() : $data;
    }

    public function oliver_pos_tickera_custom_form_field( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['field_id']) && !empty($parameters['field_id'])) {
            return $this->oliver_pos_get_tickera_post( sanitize_text_field($parameters['field_id']));
        }
        return array();
    }

    public function oliver_pos_get_tickera_post( $id ) {
        if ( wp_get_post_parent_id( $id ) > 0 ) {
            return array(
                "id" 		 => (int) $id,
                "form_id" 	 => wp_get_post_parent_id( $id ),
                "title" 	 => get_the_title( $id ),
                "name" 	  	 => esc_attr( get_post_meta( $id, 'name', true )),
                "content" 	 => esc_attr( get_post_field( 'post_content', $id )),
                "field_type" => $this->oliver_pos_get_field_type( esc_attr( get_post_meta( $id, 'field_type', true ))),
                "placeholder"=> esc_attr( get_post_meta( $id, 'placeholder', true )),
                "is_required"=> ( (string) esc_attr( get_post_meta( $id, 'required', true )) == "1" ) ? true : false,
            );
        } else {
            return array(
                "id" 	=> (int) $id,
                "title" => get_the_title( $id ),
                "content" => esc_attr( get_post_field( 'post_content', $id )),
                "type" 	=> esc_attr( get_post_meta( $id, 'form_type', true )),
                "fields" => $this->oliver_pos_get_tickera_custom_form_fields( $id ),
            );
        }
    }

    public function oliver_pos_get_field_type( $type ) {
        if (! empty($type)) {
            switch ($type) {
                case 'tc_radio_field_form_element':
                    return 'radio';
                    break;

                case 'tc_select_field_form_element':
                    return 'select';
                    break;

                case 'tc_checkbox_field_form_element':
                    return 'checkbox';
                    break;

                case 'tc_input_field_form_element':
                    return 'input';
                    break;

                case 'tc_textarea_field_form_element':
                    return 'textarea';
                    break;

                default:
                    return '';
                    break;
            }
        }
    }

    /* TICKETS */
    public function oliver_pos_get_tickets( $request_data ) {
        $parameters = $request_data->get_params();
        // init tickets array
        $tickets = array();

        $posts_per_pag = -1;

        $args = array(
            'post_type' => 'tc_tickets_instances',
            'post_status' => array('publish'),
            'order' => 'ASC'
        );

        if (isset($parameters['page']) && isset($parameters['per_page'])) {
            $posts_per_pag = (integer) $parameters['per_page'];
            $args['paged'] = (integer) $parameters['page'];
        }

        $args['posts_per_page'] = $posts_per_pag;

        $loop = new WP_Query( $args );
        while ($loop->have_posts()):
            $loop->the_post();
            $post_id = (int) $loop->post->ID;
            $tickets[] = $this->oliver_pos_get_ticket_data( $post_id );
        endwhile;

        return empty($tickets) ? array() : $tickets;
    }

    public function oliver_pos_get_ticket( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['ticket_id']) && !empty($parameters['ticket_id'])) {
            return $this->oliver_pos_get_ticket_data( sanitize_text_field($parameters['ticket_id']));
        }
        return array();
    }

    public function oliver_pos_get_ticket_data($id) {
        return array(
            'id' 		=> $id,
            'order_id' 	=> wp_get_post_parent_id( $id ),
            'name' => esc_attr( get_post_field( 'post_name', $id )),
            'first_name' => esc_attr( get_post_meta( $id, 'first_name', true ) ),
            'last_name' => esc_attr( get_post_meta( $id, 'last_name', true ) ),
            'ticket_type_id' => esc_attr( get_post_meta( $id, 'ticket_type_id', true ) ),
            'ticket_code' => esc_attr( get_post_meta( $id, 'ticket_code', true ) ),
            // 'event_id' => esc_attr( get_post_meta( $id, 'event_id', true ) ),
            'event_detail' => $this->oliver_pos_get_event_data( esc_attr( get_post_meta( $id, 'event_id', true ) ) ),
            'check_ins' => empty( get_post_meta( $id, 'tc_checkins', true ) ) ? array() : get_post_meta( $id, 'tc_checkins', true ),
        );
    }

    public function oliver_pos_tickera_get_ticket_by_order_id( $request_data ) {
        $data = array();
        $parameters = $request_data->get_params();
        if (isset($parameters['order_id']) && !empty($parameters['order_id'])) {
            $order_id = is_integer($parameters['order_id']) ? $parameters['order_id'] : (int) $parameters['order_id'];

            $args = array(
                'post_type' => 'tc_tickets_instances',
                'post_parent' => $order_id,
                'post_status' => array('publish'),
                'posts_per_page' => -1,
            );

            $loop = new WP_Query( $args );
            while ($loop->have_posts()):
                $loop->the_post();
                $post_id = (int) $loop->post->ID;
                $data[] = $this->oliver_pos_get_ticket_data( $post_id );
            endwhile;
        }
        return $data;
    }

    /* TICKETS */

    /* Events */
    public function oliver_pos_get_events() {
        $events = array();
        $args = array(
            'post_type' => 'tc_events',
            'post_status' => array('publish'),
            'posts_per_page' => -1,
        );

        $loop = new WP_Query( $args );
        while ($loop->have_posts()):
            $loop->the_post();
            $post_id = (int) $loop->post->ID;
            $events[] = $this->oliver_pos_get_event_data( $post_id );
        endwhile;

        return empty($events) ? array() : $events;
    }

    public function oliver_pos_get_event( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['event_id']) && !empty($parameters['event_id'])) {
            return $this->oliver_pos_get_event_data( sanitize_text_field($parameters['event_id']));
        }
        return array();
    }

    public function oliver_pos_get_event_data($id) {
        return array(
            'id' 	=> $id,
            'title' => get_the_title( $id ),
            'content' => esc_attr( get_post_field( 'post_content', $id)),
            'event_start_date_time' => esc_attr( get_post_meta( $id, 'event_date_time', true ) ),
            'event_end_date_time' => esc_attr( get_post_meta( $id, 'event_end_date_time', true ) ),
            'event_location' => esc_attr( get_post_meta( $id, 'event_location', true ) ),
            'event_logo' => esc_attr( get_post_meta( $id, 'event_logo_file_url', true ) ),
        );
    }
    /* Events */

    /* general settings */

    /**
     * Get tickera general settings.
     * @return array Returns array of tickera general settings.
     */
    public function oliver_pos_get_settings() {
        $tc_general_setting = get_option( 'tc_general_setting', false ) ? get_option( 'tc_general_setting', false ) : null;
        $tc_seat_charts_settings = get_option( 'tc_seat_charts_settings', false ) ? get_option( 'tc_seat_charts_settings', false ) : null;

        if (!empty($tc_seat_charts_settings) && !is_null($tc_seat_charts_settings)) {
            return array_merge($tc_general_setting, $tc_seat_charts_settings);
        }

        return $tc_general_setting;
    }

    /**
     * Trigger when tickera setting update.
     * @return void  call the .net API.
     */
    public function oliver_pos_save_tickera_general_setting() {
        oliver_log("=== === ===");
        oliver_log("Start tickera setting trigger");
        $udid = ASP_DOT_NET_UDID;
        $method = ASP_TRIGGER_TICKERA_SETTING;
        wp_remote_get( esc_url_raw("{$method}?udid={$udid}"), array(
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));
        oliver_log("End tickera setting trigger");
    }

    /* general settings */

    /* Tickera seating charts */

    /**
     * Get all seating charts.
     * @since 2.1.2.1
     * @return array Returns array of all seat charts.
     */
    public function oliver_pos_get_charts() {
        $tickets = array();
        $args = array(
            'post_type' => 'tc_seat_charts',
            'post_status' => array('publish'),
            'posts_per_page' => -1,
        );

        $loop = new WP_Query( $args );
        while ($loop->have_posts()):
            $loop->the_post();
            $post_id = (int) $loop->post->ID;
            $tickets[] = $this->oliver_pos_get_chart_data( $post_id );
        endwhile;

        return empty($tickets) ? array() : $tickets;
    }

    /**
     * Get seating chart.
     * @since 2.1.2.1
     * @param int chart_id
     * @return object Returns a single seat chart details.
     */
    public function oliver_pos_get_chart( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['chart_id']) && !empty($parameters['chart_id'])) {
            return $this->oliver_pos_get_chart_data( sanitize_text_field($parameters['chart_id']));
        }
        return array();
    }

    /**
     * Get seating chart.
     *
     * @since 2.1.2.1
     * @param int chart_id
     * @return object Returns a single seat chart details (only for internally usage).
     */
    private function oliver_pos_get_chart_data( $id ) {
        return array(
            'id' 		=> $id,
            'event_id' 	=> esc_attr( get_post_meta($id, 'event_name', true)),
            'product_id'=> esc_attr( get_post_meta($id, 'tc_ticket_types', true)),
            'chart_data'=> $this->oliver_pos_get_seating_chart_html($id, 'post_name', true),
            'reserved_seats'=> $this->oliver_pos_get_reserved_seats( $id ),
        );
    }

    /**
     * Get seating chart htnl data.
     *
     * @since 2.1.2.1
     * @param int chart_id
     * @param bool true for frontend|false for backend
     * @return text|html Returns a single seat chart htnl data (only for internally usage).
     */
    private function oliver_pos_get_seating_chart_html( $post_id, $front = true ) {
        try {
            $content = '';
            $upload = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $upload_dir = $upload_dir . '/tc-seating-charts';

            if ( $front ) {
                $filename = $post_id . '-front.tcsm';
            } else {
                $filename = $post_id . '.tcsm';
            }

            $path = $upload_dir . '/' . $filename;
            if ( file_exists( $path ) ) {
                try {
                    $content = file_get_contents( $path );
                } catch ( Exception $e ) {
                    $myfile = fopen( $path, "r" );
                    $content = fread( $myfile, filesize( $path ) );
                    fclose( $myfile );
                }
            }
            return $content;
        } catch ( Exception $e ) {
            return '';
        }
    }

    /**
     * Get seating chart reserved seat id's.
     *
     * @since 2.1.2.1
     * @param int chart_id
     * @return array Returns array of reserved seats of a chart (only for internally usage).
     */
    private function oliver_pos_get_reserved_seats( $chart_id ) {
        $seats = array();

        // query to get seats behalf of tickets
        $args = array(
            'post_type'      => 'tc_tickets_instances',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_key'       => 'chart_id',
            'meta_value'     => (string) $chart_id,
            'no_found_rows'  => true,
        );

        // fire query
        $tickets_instances = get_posts( $args );

        if ( !empty($tickets_instances) ) {
            foreach ( $tickets_instances as $key => $ticket_instance ) {

                if ( in_array( get_post_status( $ticket_instance->post_parent ), $this->oliver_pos_get_reserved_order_statuses() ) ) {
                    $seat_id = esc_attr(get_post_meta( $ticket_instance->ID, 'seat_id', true ));
                    array_push($seats, $seat_id);
                }

            }
        }

        return $seats;
    }

    /**
     * Get tickera seats reserved order statuses.
     *
     * @since 2.1.2.1
     * @return array Returns array of reserved seats order status.
     */
    private function oliver_pos_get_reserved_order_statuses() {
        return apply_filters( 'tc_seat_charts_get_reserved_seats_order_statuses', array( 'order_received', 'order_paid' ) );
    }

    /**
     * Get chart reserved seat.
     * @since 2.1.2.3
     * @param int chart_id
     * @return array Returns reserved seat of chart.
     */
    public function oliver_pos_get_chart_reserved_seats( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['chart_id']) && !empty($parameters['chart_id'])) {
            return $this->oliver_pos_get_reserved_seats( sanitize_text_field($parameters['chart_id']));
        }
        return array();
    }

    /* Tickera seating charts */

    /* counts */

    /**
     * Get form count.
     *
     * @return int Returns Int form count on success.
     */
    public static function oliver_pos_get_form_count() {
        return ( (new self)->oliver_pos_is_tickera_active() ) ? (int) wp_count_posts("tc_forms")->publish : 0;
    }

    /**
     * Get event count.
     *
     * @return int Returns Int event count on success.
     */
    public static function oliver_pos_get_event_count() {
        return ( (new self)->oliver_pos_is_tickera_active() ) ? (int) wp_count_posts("tc_events")->publish : 0;
    }

    /**
     * Get event ticket.
     *
     * @return int Returns Int event ticket on success.
     */
    public static function oliver_pos_get_ticket_count() {
        return ( (new self)->oliver_pos_is_tickera_active() ) ? (int) wp_count_posts("tc_tickets_instances")->publish : 0;
    }

    /**
     * Get tickera seating chart.
     * @since 2.1.2.1
     * @return int Returns Int tickera seating chart count on success.
     */
    public static function oliver_pos_get_seating_chart_count() {
        return ( (new self)->oliver_pos_is_tickera_active() ) ? (int) wp_count_posts("tc_seat_charts")->publish : 0;
    }

    /* counts */

    /* triggers */

    public function oliver_pos_save_tickera_form_post( $id, $post, $update ) {
        if (get_post_type( $id ) == "tc_forms") {
            oliver_log("=== === ===");
            $post_status = get_post_status($id);
            if ($post_status == "publish") {
                oliver_log("Start tickera create/update form trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_CREATE_UPDATE_TICKERA_FORM);
                oliver_log("End tickera create/update form trigger");
            } else if ($post_status == "trash") {
                oliver_log("Start tickera delete form trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_REMOVE_TICKERA_FORM);
                oliver_log("End tickera delete form trigger");
            }
        }
    }

    public function oliver_pos_save_tickera_event_post( $id, $post, $update ) {
        if (get_post_type( $id ) == "tc_events") {
            oliver_log("=== === ===");
            $post_status = get_post_status($id);
            if ($post_status == "publish") {
                oliver_log("Start tickera create/update events trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_CREATE_UPDATE_TICKERA_EVENT);
                oliver_log("End tickera create/update events trigger");
            } else if ($post_status == "trash") {
                oliver_log("Start tickera delete events trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_REMOVE_TICKERA_EVENT);
                oliver_log("End tickera delete events trigger");
            }
        }
    }

    public function oliver_pos_save_tickera_ticket_post( $id, $post, $update ) {
        if (get_post_type( $id ) == "tc_tickets_instances") {
            oliver_log("=== === ===");
            $post_status = get_post_status($id);
            if ($post_status == "publish") {
                oliver_log("Start tickera create/update ticket trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_CREATE_UPDATE_TICKERA_TICKET);
                oliver_log("Start tickera create/update ticket trigger");
            } else if ($post_status == "trash") {
                oliver_log("Start tickera delete ticket trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_REMOVE_TICKERA_TICKET);
                oliver_log("Start tickera delete ticket trigger");
            }
        }
    }

    /**
     * Execute the function while seating chart add update and delete
     * @since 2.1.2.1
     * @param int $id post id
     * @param instance $post instance
     * @param bool $update return true if post update false while post created
     * @return void call ASP.Net API's
     */
    public function oliver_pos_save_tickera_seating_chart( $id, $post, $update ) {
        if (get_post_type( $id ) == "tc_tickets_instances") {
            oliver_log("=== === ===");
            $post_status = get_post_status($id);
            if ($post_status == "publish") {
                oliver_log("Start tickera create/update seating chart trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_CREATE_UPDATE_TICKERA_SEATING_CHART);
                oliver_log("Start tickera create/update seating chart trigger");
            } else if ($post_status == "trash") {
                oliver_log("Start tickera delete seating chart trigger");
                $this->oliver_pos_sync_to_asp_dot_net( $id, ASP_TRIGGER_REMOVE_TICKERA_SEATING_CHART);
                oliver_log("Start tickera delete seating chart trigger");
            }
        }
    }

    /* triggers */

    private function oliver_pos_sync_to_asp_dot_net( $post_id, $method ) {
        $udid = ASP_DOT_NET_UDID;
        $url = esc_url_raw("{$method}?udid={$udid}&wpid={$post_id}");

        wp_remote_get($url, array(
            'timeout'   => 0.01,
            'blocking'  => false,
            'sslverify' => false,
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));
    }
}