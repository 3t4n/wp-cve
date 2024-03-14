<?php

if (!defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

class OSF_Field_Agent_Info {

    public function __construct() {
        add_filter( 'cmb2_render_agent_info', array( $this, 'render' ), 10, 5 );
        add_action( 'wp_ajax_property_change_agent_info', array( $this, 'render_ajax' ) );
        //        add_action( 'wp_ajax_nopriv_get_office_property', 'opalrealestate_load_more_office_property' );
    }

    public function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        $this->render_content( get_post_meta( get_the_ID(), $field->args['sync'], true ) );
    }

    private function render_content($userid) {
        global $post;
        $user_info       = get_userdata( $userid );
        $link_profile    = get_edit_user_link( $userid ) . '#opal-section-estate-settings';
        $link_properties = esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'agent_id' => $userid ), 'edit.php' ) );
        $avatar          = get_user_meta( $userid, OPALREALESTATE_USER_PROFILE_PREFIX . 'avatar', true );
        $phone           = get_user_meta( $userid, 'phone', true );
        if ($phone) {
            $phone = '<strong>' . __( 'Phone', 'ocbee-core' ) . '</strong>: ' . $phone . '<br>';
        }
        if (!$avatar) {
            $avatar = get_avatar( $userid, 120 );
        } else {
            $avatar = '<img src="' . esc_url( $avatar ) . '" width="120">';
        }
        ?>
        <div class="meta-agent-info">
            <div class="agent-avatar">
                <?php echo $avatar; ?>
            </div>
            <div class="info">
                <h3><?php echo esc_html( $user_info->display_name ) ?></h3>
                <address>
                    <strong><?php _e( 'Email', 'ocbee-core' ) ?></strong>: <?php echo $user_info->user_email; ?>
                    <br>
                    <?php echo $phone; ?>
                </address>
                <a href="<?php echo esc_url( $link_profile ) ?>"
                   class="button button-primary button-large"><?php _e( 'View Profile', 'ocbee-core' ) ?></a>
                <a href="<?php echo esc_url( $link_properties ) ?>"
                   class="button button-primary button-large"><?php _e( 'View all Properties', 'ocbee-core' ) ?></a>
            </div>
        </div>
        <?php
    }

    public function render_ajax() {
        ob_start();
        $this->render_content( $_REQUEST['user_id'], true );
        wp_send_json( ob_get_clean() );
    }
}

new OSF_Field_Agent_Info();