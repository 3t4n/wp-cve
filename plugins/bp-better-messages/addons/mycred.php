<?php

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_MyCred' ) ) {
    class Better_Messages_MyCred
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_MyCred();
            }

            return $instance;
        }

        public function __construct(){
            add_filter( 'better_messages_can_send_message',           array( $this, 'mycred_core_charge_message'), 10, 3);
            add_action( 'better_messages_message_sent',               array( $this, 'mycred_core_charge_for_message' ) );
            add_action( 'bp_better_messages_new_thread_created',      array( $this, 'mycred_core_charge_new_thread_created'), 10, 2 );
            add_action( 'better_messages_before_new_thread',          array( $this, 'mycred_core_charge_for_new_thread' ), 10, 2 );

            add_filter( 'better_messages_private_call_allowed_error', array( $this, 'is_call_allowed' ), 10, 4 );
            add_action( 'better_messages_register_call_usage',        array( $this, 'call_usage_charge' ), 10, 3 );
        }

        public function call_usage_charge( $message_id, $thread_id, $caller_user_id ){
            if( $caller_user_id <= 0 || ! Better_Messages()->calls ) return;

            // User role not charged for video call
            $user_charge_rate = $this->get_user_mycred_call_charge_rate( $caller_user_id );
            if( $user_charge_rate === 0 ) return;

            // Charge only when at least 20KB of traffic is transferred
            if( ! Better_Messages()->calls->call_has_confirmed_traffic( $message_id ) ) return;

            $current_minute = Better_Messages()->functions->get_message_meta($message_id, 'mins');
            if( $current_minute === '' ) return;

            $current_minute = intval( $current_minute ) + 1;
            $charged_minutes = (int) Better_Messages()->functions->get_message_meta($message_id, 'mycred_charged_mins');

            if( $charged_minutes >= $current_minute ) return;

            $uncharged_minutes = $current_minute - $charged_minutes;

            $to_charge = $user_charge_rate * $uncharged_minutes;
            $balance = mycred()->get_users_balance( $caller_user_id );

            if( $balance >= $to_charge ){
                $log_entry = sprintf( _x('Better Messages charge for call usage #%d', 'MyCred Log Entry', 'bp-better-messages'), $message_id );
                mycred()->add_creds( 'better_messages_call_charge_' . $message_id, $caller_user_id, 0 - ($to_charge), $log_entry );

                Better_Messages()->functions->update_message_meta( $message_id, 'mycred_charged_mins', $current_minute );
            } else {
                wp_send_json([ 'action' => 'end_call', 'reason' => Better_Messages()->settings['myCredCallPricingEndMessage'] ]);
            }
        }

        public function is_call_allowed( $error, $thread_id, $caller_user_id, $target_user_id ){
            if( ! empty( $error ) ) return $error;

            $user_charge_rate = $this->get_user_mycred_call_charge_rate( $caller_user_id );

            if( $user_charge_rate === 0 ){
                return $error;
            }

            $balance = mycred()->get_users_balance( $caller_user_id );

            if( $balance >= $user_charge_rate ){
                $can_afford = true;
            } else {
                $can_afford = false;
            }

            if( ! $can_afford ){
                return Better_Messages()->settings['myCredCallPricingStartMessage'];
            }

            return $error;
        }


        public function get_user_mycred_call_charge_rate( $user_id ){
            if( $user_id < 0 ) return 0;

            $charge_values = Better_Messages()->settings['myCredCallPricing'];

            $enabled_roles = [];

            foreach ( $charge_values as $role => $value ){
                if( $value['value'] > 0 ){
                    $enabled_roles[$role] = (int) $value['value'];
                }
            }

            if( count( $enabled_roles ) === 0 ) {
                return 0;
            }

            $user_roles = (array) Better_Messages()->functions->get_user_roles( $user_id );

            $user_charge_rate = 0;

            foreach( $user_roles as $user_role ){
                if( isset( $enabled_roles[ $user_role ] ) ) {
                    $role_charge = (int) $enabled_roles[ $user_role ];

                    if( $role_charge > $user_charge_rate ){
                        $user_charge_rate = $role_charge;
                    }
                }
            }

            return $user_charge_rate;
        }

        public function get_user_mycred_charge_rate( $user_id ){
            if( $user_id < 0 ) return 0;

            $charge_values = Better_Messages()->settings['myCredNewMessageCharge'];

            $enabled_roles = [];

            foreach ( $charge_values as $role => $value ){
                if( $value['value'] > 0 ){
                    $enabled_roles[$role] = (int) $value['value'];
                }
            }

            if( count( $enabled_roles ) === 0 ) {
                return 0;
            }

            $user_roles = (array) Better_Messages()->functions->get_user_roles( $user_id );

            $user_charge_rate = 0;

            foreach( $user_roles as $user_role ){
                if( isset( $enabled_roles[ $user_role ] ) ) {
                    $role_charge = (int) $enabled_roles[ $user_role ];

                    if( $role_charge > $user_charge_rate ){
                        $user_charge_rate = $role_charge;
                    }
                }
            }

            return $user_charge_rate;
        }

        public function get_user_mycred_charge_new_thread_rate( $user_id ){
            if( $user_id < 0 ) return 0;

            $charge_values = Better_Messages()->settings['myCredNewThreadCharge'];

            $enabled_roles = [];

            foreach ( $charge_values as $role => $value ){
                if( $value['value'] > 0 ){
                    $enabled_roles[$role] = (int) $value['value'];
                }
            }

            if( count( $enabled_roles ) === 0 ) {
                return 0;
            }

            $user_roles = Better_Messages()->functions->get_user_roles( $user_id );

            $user_charge_rate = 0;

            foreach( $user_roles as $user_role ){
                if( isset( $enabled_roles[ $user_role ] ) ) {
                    $role_charge = (int) $enabled_roles[ $user_role ];

                    if( $role_charge > $user_charge_rate ){
                        $user_charge_rate = $role_charge;
                    }
                }
            }

            return $user_charge_rate;
        }

        public function mycred_core_charge_message( $allowed, $user_id, $thread_id ){
            if( ! function_exists('mycred') ) {
                return $allowed;
            }

            $user_charge_rate = $this->get_user_mycred_charge_rate( $user_id );

            if( $user_charge_rate === 0 ) return $allowed;

            $balance = mycred()->get_users_balance( $user_id );

            if( $balance >= $user_charge_rate ){
                $can_afford = true;
            } else {
                $can_afford = false;
            }

            if( ! $can_afford ){
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['mycred_core_restricted'] = Better_Messages()->settings['myCredNewMessageChargeMessage'];
            }

            return $allowed;
        }

        public function mycred_core_charge_new_thread_created( $thread_id, $bpbm_last_message_id ){
            if( ! function_exists('mycred') ) {
                return false;
            }

            $user_id = Better_Messages()->functions->get_current_user_id();

            $user_charge_rate = $this->get_user_mycred_charge_new_thread_rate( $user_id );
            if( $user_charge_rate === 0 ) return false;

            $amount_to_deduct = 0 - $user_charge_rate;
            $log_entry = sprintf( _x('Better Messages charge for new thread #%d', 'MyCred Log Entry', 'bp-better-messages'), $thread_id );
            mycred()->add_creds( 'better_messages_new_thread_' . $thread_id, $user_id, $amount_to_deduct, $log_entry );
        }

        public function mycred_core_charge_for_message( $message ){
            if( ! function_exists('mycred') ) {
                return false;
            }

            if( trim($message->message) === '<!-- BBPM START THREAD -->' ) return false;

            $user_id = (int) $message->sender_id;

            $user_charge_rate = $this->get_user_mycred_charge_rate( $user_id );
            if( $user_charge_rate === 0 ) return false;

            $amount_to_deduct = 0 - $user_charge_rate;
            $log_entry = sprintf(_x('Better Messages charge for message #%d', 'MyCred Log Entry', 'bp-better-messages'), $message->id);
            mycred()->add_creds( 'better_messages_new_message_' . $message->id, $user_id, $amount_to_deduct, $log_entry );
        }

        public function mycred_core_charge_for_new_thread( &$args, &$errors ){
            if( ! function_exists('mycred') ) {
                return false;
            }

            $user_id = Better_Messages()->functions->get_current_user_id();

            if( ! is_array( $args['recipients'] ) ){
                $args['recipients'] = [$args['recipients']];
            }

            $user_charge_rate = $this->get_user_mycred_charge_new_thread_rate( $user_id );
            if( $user_charge_rate === 0 ) return false;

            $balance = mycred()->get_users_balance( $user_id );

            if( $balance >= $user_charge_rate ){
                $can_afford = true;
            } else {
                $can_afford = false;
            }

            if( ! $can_afford ){
                $errors['mycred_core_restricted'] = Better_Messages()->settings['myCredNewThreadChargeMessage'];
            }
        }

    }
}
