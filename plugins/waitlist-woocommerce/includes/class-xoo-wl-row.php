<?php

class Xoo_Wl_Row{

	public $row_id = 0;

	public $quantity = 0;

	public $join_date = '';

	public $user_id = 0;

	public $product_id = 0;

	public function __construct( $row_id ){

		if( is_a( $row_id, 'Xoo_Wl_Row' ) ){
			$row_data = $row_id;
		}
		else{
			$row_data = xoo_wl_db()->get_waitlist_row( $row_id );
		}

		if( !$row_data || empty( $row_data ) ) return;

		$this->row_id 		= $row_id;
		$this->email 		= esc_attr( $row_data->email );
		$this->quantity 	= esc_attr( $row_data->quantity );
		$this->join_date 	= esc_attr( $row_data->join_date );
		$this->user_id 		= (int) $row_data->user_id;
		$this->product_id 	= (int) $row_data->product_id;
		$this->product 		= wc_get_product( $this->product_id );
	}

	public function get_email(){
		return $this->email;
	}


	public function get_joining_date( $format = "d M y" ){
		return date( $format, strtotime( $this->join_date ) );
	}

	public function get_quantity(){
		return $this->quantity;
	}

	public function get_row_id(){
		return $this->row_id;
	}

	public function get_user_id(){
		return $this->user_id;
	}

	public function get_product_id(){
		return $this->product_id;
	}

	public function get_product(){
		return $this->product;
	}

	public function get_product_image_src(){
		if ( $this->product->get_image_id() ) {
			return wp_get_attachment_url( $this->product->get_image_id() );
		}

		if (  $this->product->get_parent_id() ) {
			$parent_product = wc_get_product( $this->product->get_parent_id() );
            if ( $parent_product && $parent_product->get_image_id()) {
                return wp_get_attachment_url( $parent_product->get_image_id() );
            }
		}

		return wc_placeholder_img_src();
	}

}

function xoo_wl_get_row( $row_id ){
	return new Xoo_Wl_Row( $row_id );
}

?>