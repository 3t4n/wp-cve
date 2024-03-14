<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class pisol_disable_order_completion_email{
	protected static $instance = null;

    public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function __construct(){
        $disable_email = get_option('pi_corw_disable_order_completion_email',0);
        if( empty( $disable_email ) ) return;
        
		add_action('edit_post', [$this, 'editPost'], 10, 2);
		add_filter( 'woocommerce_email_enabled_customer_completed_order', [$this, 'is_enabled'], 10, 3);
	}

	function editPost($post_id, $post){
		if('shop_order' == $post->post_type){
			$this->old_status = str_replace('wc-','',$post->post_status);
		}
	}

	function is_enabled($yes_no, $order, $email){
        if(empty($order) || !is_object($order)) return $yes_no;

       	$status = $order->get_status();
		if(!empty($this->old_status) && $this->old_status == 'cancel-request' && $status == 'completed'){
			return false;
		}
        return $yes_no;
    }
}
pisol_disable_order_completion_email::get_instance();