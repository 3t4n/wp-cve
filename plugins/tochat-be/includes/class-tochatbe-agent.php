<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Agent {

    private $agent_id;

    public function __construct( $agent_id ) {
        $this->agent_id = $agent_id;        
    }

    public function get_name() {
        return get_post_meta( $this->agent_id, 'agent_name', true );
    }

    public function get_title() {
        return get_post_meta( $this->agent_id, 'agent_title', true );
    }

    public function get_number() {
        return get_post_meta( $this->agent_id, 'agent_number', true );
    }

    public function get_group_id() {
        return get_post_meta( $this->agent_id, 'agent_group_id', true );
    }

    public function get_pre_defined_message() {
        return get_post_meta( $this->agent_id, 'pre_defined_message', true );
    }

    public function get_image() {
        if ( has_post_thumbnail( $this->agent_id ) ) {
            return get_the_post_thumbnail_url( $this->agent_id );
        } else {
            return TOCHATBE_PLUGIN_URL . 'assets/images/ToChatBe.png';
        }
    }

    public function get_type() {
        $type = get_post_meta( $this->agent_id, 'agent_type', true );

        if ( ! $type ) {
            return 'number';
        }

        return $type;
    }

}