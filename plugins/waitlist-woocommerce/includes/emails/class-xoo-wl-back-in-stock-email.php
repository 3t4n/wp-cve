<?php

class Xoo_Wl_Back_In_Stock_Email extends Xoo_Wl_Email{

	public $body_text;
	public $heading;

	public function __construct(){

		parent::__construct();

		$this->id 			= 'back_in_stock';
		$this->template 	= 'emails/xoo-wl-back-in-stock-email.php';
		$this->subject 		= xoo_wl_helper()->get_email_option( 'bis-subject' );
		$this->body_text 	= xoo_wl_helper()->get_email_option( 'bis-content' );
		$this->heading 		= xoo_wl_helper()->get_email_option( 'bis-heading' );
		$this->hooks();

	}


	public function hooks(){
		add_action( 'xoo_wl_email_back_in_stock_sent', array( $this, 'update_email_count' ), 10, 2 );
		add_action( 'xoo_wl_email_back_in_stock_sent', array( $this, 'delete_waitlist_row' ), PHP_INT_MAX, 2 );
		add_action( 'xoo_wl_email_head', array( $this, 'inline_style' ) );
	}

	public function update_email_count( $sent, $obj ){

		if( !$sent ) return;

		//Update sent count
		$sent_count = (int) xoo_wl_db()->get_waitlist_meta( $this->row_id, '_sent_count' );
		$sent_count++;
		xoo_wl_db()->update_waitlist_meta( $this->row_id, '_sent_count', $sent_count );
	}

	public function delete_waitlist_row( $sent, $obj ){
		if( !$sent || xoo_wl_helper()->get_email_option( 'bis-keep-wl' ) === "yes" ) return;
		xoo_wl_db()->delete_waitlist_row_by_id( $this->row_id );
	}


	public function validation(){

		//Check if only one email per user is enabled & one email has already been sent
		if( xoo_wl_helper()->get_email_option( 'bis-send-once' ) === "yes" ){
			$sent_count = (int) xoo_wl_db()->get_waitlist_meta( $this->row_id, '_sent_count' );
			if( $sent_count >= 1 ){
				return new WP_Error( 'email-already-sent', __( 'Email has been already sent to this user. For multiple emails, please enable it from your settings', 'waitlist-woocommerce' ) );
			}
		}

		return $this->product_validation();
	}


	public function product_validation( $product_id = '' ){

		$product = $product_id ? wc_get_product( $product_id ) : $this->product; 

		if( !$product ){
			return new WP_Error( 'no-product', __( 'No product found', 'waitlist-woocommerce' ) );
		}

		if( xoo_wl_helper()->get_email_option( 'bis-check-stock' ) === "yes" && xoo_wl_is_product_out_of_stock( $product->get_id() ) ){
			return new WP_Error( 'in-stock', __( 'Product is out of stock, cannot send email.', 'waitlist-woocommerce' ) );
		}

		return true;
	}


	public function get_template(){

		$show_pimage = xoo_wl_helper()->get_email_option( 'bis-show-pimg' ) === "yes" && $this->row->get_product_image_src();


		$args = array(
			'show_pimage' 	=> $show_pimage,
			'product_image'	=> $this->row->get_product_image_src(),
			'product_name' 	=> $this->product->get_name(),
			'product_link'	=> $this->product->get_permalink(),
			'heading' 		=> $this->heading,
			'body_text' 	=> $this->body_text,
			'buy_now_text' 	=> xoo_wl_helper()->get_email_option( 'bis-buy-btn-txt' ),
			'emailObj' 		=> $this
		);

		return xoo_wl_helper()->get_template( $this->template, $args, '', true );
	}

	public function get_recipient_emails(){
		$this->recipient_emails[] = $this->row->get_email();
		return $this->recipient_emails;
	}

	public function inline_style(){
		?>
		<style type="text/css">
			img.xoo-wl-em-pimg{
		      width: 100%!important;
		      max-width: 200px!important;
		    }
		</style>
		<?php
	}


}

return new Xoo_Wl_Back_In_Stock_Email();