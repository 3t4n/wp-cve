<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Block_Users' ) ){

    class Better_Messages_Block_Users
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Block_Users();
            }

            return $instance;
        }

        public function __construct(){
            add_filter( 'better_messages_can_send_message', array( $this, 'disable_blocked_replies' ), 20, 3);
            add_action( 'better_messages_before_new_thread', array( $this, 'disable_start_thread_for_blocked_users' ), 20, 2 );

            add_action( 'bp_better_messages_user_options_bottom', array( $this, 'block_users_settings' ) );

            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
            add_filter( 'better_messages_rest_thread_item', array( $this, 'rest_thread_item'), 10, 4 );
            add_filter( 'better_messages_rest_user_item', array( $this, 'rest_user_item'), 10, 4 );

            add_filter( 'better_messages_user_config', array( $this, 'user_config_filter'), 11, 1 );
        }

        public function user_config_filter( $settings ){
            $user_id  = Better_Messages()->functions->get_current_user_id();

            if( $user_id > 0 ) {
                $settings[] = [
                    'id' => 'bm_blocked_users',
                    'title' => _x('Blocked users', 'User settings screen', 'bp-better-messages'),
                    'type' => 'blocked_users',
                    'users' => $this->get_blocked_users(Better_Messages()->functions->get_current_user_id())
                ];
            }

            return $settings;
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/blockUser', array(
                'methods' => 'POST',
                'callback' => array( $this, 'block_user_ajax' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/unblockUser', array(
                'methods' => 'POST',
                'callback' => array( $this, 'unblock_user_ajax' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ) );
        }

        public function rest_user_item( $item, $user_id, $include_personal ){
            if( $include_personal && Better_Messages()->functions->get_current_user_id() !== $user_id ){
                $item['blocked'] = (int) $this->is_user_blocked( Better_Messages()->functions->get_current_user_id(), $user_id );
                $item['canBlock'] = (int) $this->can_block_user( Better_Messages()->functions->get_current_user_id(), $user_id );
            }

            return $item;
        }

        public function rest_thread_item( $thread_item, $thread_id, $thread_type, $include_personal ){
            if( $thread_type !== 'thread'){
                return $thread_item;
            }

            if( $include_personal ){

                if( $thread_item['participantsCount'] === 2 ) {
                    $user_id = $thread_item['participants'][0];
                    $thread_item['permissions']['canBlockUser'] = $this->can_block_user( Better_Messages()->functions->get_current_user_id(), $user_id );
                }
            }

            return $thread_item;
        }

        public function disable_start_thread_for_blocked_users(&$args, &$errors){
            if( current_user_can('manage_options' ) ) {
                return null;
            }

            $recipients = $args['recipients'];
            if( ! is_array( $recipients ) ) $recipients = [ $recipients ];

            foreach($recipients as $user_id) {
                if(  Better_Messages()->functions->is_valid_user_id( $user_id ) ) {
                    $is_blocked_1 = $this->is_user_blocked(Better_Messages()->functions->get_current_user_id(), $user_id );
                    if ($is_blocked_1) {
                        $errors[] = sprintf(_x('%s blocked by you', 'Error when starting new thread but user blocked', 'bp-better-messages'), Better_Messages()->functions->get_name($user_id));
                        continue;
                    }

                    $is_blocked_2 = $this->is_user_blocked($user_id, Better_Messages()->functions->get_current_user_id());
                    if ($is_blocked_2) {
                        $errors[] = sprintf(_x('%s blocked you', 'Error when starting new thread but user blocked', 'bp-better-messages'), Better_Messages()->functions->get_name($user_id));
                        continue;
                    }
                }
            }
        }

        public function disable_blocked_replies( $allowed, $user_id, $thread_id ){
            $current_user_id = Better_Messages()->functions->get_current_user_id();

            if( ! Better_Messages()->functions->is_valid_user_id( $user_id ) ) {
                return $allowed;
            }

            $roles = Better_Messages()->functions->get_user_roles( $current_user_id );

            if( in_array('administrator', $roles)){
                return $allowed;
            }

            $participants = Better_Messages()->functions->get_participants($thread_id);

            if( count($participants['recipients']) !== 1) return $allowed;

            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
            if( $thread_type !== 'thread' ) return $allowed;

            $user_id_2 = array_pop($participants['recipients']);

            //var_dump( 'test' );
            /**
             *  Current user blocked other
             */
            $is_blocked_1 = $this->is_user_blocked( Better_Messages()->functions->get_current_user_id(), $user_id_2 );
            if( $is_blocked_1 ) {
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['user_blocked_messages'] = _x("You can't send message to user who was blocked by you", 'Message when user cant send message to user blocked by him' ,'bp-better-messages');
                return false;
            }

            /**
             *  Other user blocked current user
             */
            $is_blocked_2 = $this->is_user_blocked( $user_id_2, Better_Messages()->functions->get_current_user_id() );

            if( $is_blocked_2 ) {
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['user_blocked_messages'] = _x("You can't send message to user who blocked you", 'Message when user cant send message to user who blocked him' ,'bp-better-messages');
                return false;
            }

            return $allowed;
        }

        public function block_user_ajax( WP_REST_Request $request ){
            $blocked_user_id = intval( $request->get_param('user_id') );
            wp_send_json($this->block_user( Better_Messages()->functions->get_current_user_id(), $blocked_user_id ));
        }

        public function unblock_user_ajax( WP_REST_Request $request ){
            $blocked_user_id = intval( $request->get_param('user_id') );
            wp_send_json($this->unblock_user( Better_Messages()->functions->get_current_user_id(), $blocked_user_id ));
        }

        public function get_blocked_users( $user_id ){
            $blocked_users = Better_Messages()->functions->get_user_meta($user_id, 'bm_blocked_users', true);

            if( ! is_array( $blocked_users ) || empty( $blocked_users ) ) {
                $blocked_users = [];
            }

            return $blocked_users;
        }

        public function is_user_blocked( $user_id, $blocked_id ){
            $blocked_users = $this->get_blocked_users( $user_id );

            if( isset( $blocked_users[$blocked_id] ) ){
                return true;
            } else {
                return false;
            }
        }

        public function block_user( $user_id, $blocked_id ){
            $blocked_users = $this->get_blocked_users( $user_id );

            $can_block = $this->can_block_user( $user_id, $blocked_id );

            if( $can_block ) {
                $blocked_users[ $blocked_id ] = time();
                Better_Messages()->functions->update_user_meta( $user_id, 'bm_blocked_users', $blocked_users );
                return true;
            } else {
                return false;
            }
        }

        public function unblock_user( $user_id, $blocked_id ){
            $blocked_users = $this->get_blocked_users( $user_id );

            $can_unblock = $this->can_unblock_user( $user_id, $blocked_id );

            if( $can_unblock ) {
                if( isset( $blocked_users[ $blocked_id ] ) ) {
                    unset( $blocked_users[ $blocked_id ] );
                }

                Better_Messages()->functions->update_user_meta( $user_id, 'bm_blocked_users', $blocked_users );

                return true;
            } else {
                return false;
            }
        }

        public function can_block_user( $user_id, $blocked_id ){
            $blocker_user_roles = Better_Messages()->functions->get_user_roles( $user_id );
            $blocked_user_roles = Better_Messages()->functions->get_user_roles( $blocked_id );

            $can_block = true;

            if( (int) $user_id === (int) $blocked_id ){
                $can_block = false;
            } else if( count($blocked_user_roles) === 0 || (count($blocker_user_roles)) === 0 ) {
                $can_block = false;
            } else {
                /**
                 * Administrator can't be blocked
                 */
                if (in_array('administrator', $blocked_user_roles)) {
                    $can_block = false;
                }

                if( count(Better_Messages()->settings['restrictBlockUsers']) > 0 ){
                    foreach( Better_Messages()->settings['restrictBlockUsers'] as $blockedRole ){
                        if( in_array( $blockedRole, $blocker_user_roles ) ){
                            $can_block = false;
                        }
                    }
                }

                if( count(Better_Messages()->settings['restrictBlockUsersImmun']) > 0 ){
                    foreach( Better_Messages()->settings['restrictBlockUsersImmun'] as $blockedRole ){
                        if( in_array( $blockedRole, $blocked_user_roles ) ){
                            $can_block = false;
                        }
                    }
                }

                /**
                 * Administrator always can block
                 */
                if (in_array('administrator', $blocker_user_roles)) {
                    $can_block = true;
                }
            }

            return apply_filters( 'bp_better_messages_can_block_user', $can_block, $user_id, $blocked_id );
        }

        public function can_unblock_user( $user_id, $blocked_id ){
            return apply_filters( 'bp_better_messages_can_unblock_user', true, $user_id, $blocked_id );
        }

    }
}

