<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Xoo_Wl_Email{

	public $id;

	public $subject = '';

	public $placeholders = array();

	public $row_id;

	public $row;

	public $template;

	public $product;

	public $recipient_emails = array();

	public function __construct() {

		$this->placeholders = array_merge(
			array(
				'[b]' 			=> '<b>',
				'[/b]' 			=> '</b>',
				'[new_line]' 	=> '<br>',
				'[i]' 			=> '<i>',
				'[/i]' 			=> '</i>'
			),
			$this->placeholders
		);

	}


	public function set_product( $product_id ){

		if( $product = wc_get_product( $product_id ) ){

			$this->product = $product;

			$this->placeholders = array_merge(
				array(
					'[product_id]' 		=> $product->get_id(),
					'[product_name]' 	=> $product->get_name(),
					'[product_link]' 	=> '<a href="'.$product->get_permalink().'">'.$product->get_name().'</a>',
					'[product_price]' 	=> $product->get_price()
				),
				$this->placeholders
			);
		}
	}

	public function set_row_data( $row_id ){

		if( is_object( $row_id ) ){
			$this->row 	= $row_id;
		}
		else{
			$this->row 	= xoo_wl_get_row( $row_id ); 
		}


		if( $this->row->get_row_id() ){

			$this->row_id = $this->row->get_row_id();

			$this->placeholders = array_merge(
				array(
					'[user_email]' 	=> $this->row->get_email(),
					'[quantity]' 	=> $this->row->get_quantity(),
					'[join_date]' 	=> $this->row->get_joining_date()
				),
				$this->placeholders
			);


			$this->set_product( $this->row->get_product_id() );

			$meta_data 		= get_metadata( 'xoo_wl', $row_id  );
			$form_fields 	= (array) xoo_wl()->aff->fields->get_fields_data();


			foreach ( $meta_data as $field_key => $field_value ) {
				if( !isset( $form_fields[ $field_key ] ) ) continue;
				$this->placeholders[ '['.$field_key.']' ] = esc_attr( xoo_wl()->aff->fields->get_field_value_label( $field_key, $field_value ) );
			}
		}
		else{
			return new WP_Error( 'row-not-found', __( 'No row found', 'waitlist-woocommerce' ) );
		}

	}

	public function get_recipient_emails(){
		return $this->recipient_emails;
	}


	public function trigger( $row_id, $recipient_emails = array() ){

		$this->recipient_emails = array_merge(
			$this->recipient_emails,
			$recipient_emails 
		);

		$set_row = $this->set_row_data( $row_id );

		if( is_wp_error( $set_row ) ){
			return $set_row;
		}

		return $this->send();
	}


	/**
	 * Send an email.
	 *
	 * @param string $to Email to.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $headers Email headers.
	 * @param array  $attachments Email attachments.
	 * @return bool success
	 */
	public function send() {
		add_filter( 'wp_mail_from', array( $this, 'get_sender_email' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_sender_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		$message 				= $this->parse_placeholders( $this->get_template() );
		$message              	= apply_filters( 'xoo_wl_mail_content', wp_kses( $message, array_merge( wp_kses_allowed_html('post'), array( 'style' => array() ) ) ), $this );
		$mail_callback        	= apply_filters( 'xoo_wl_mail_callback', 'wp_mail', $this );
		$mail_callback_params 	= apply_filters( 'xoo_wl_mail_callback_params', array( $this->get_recipient_emails(), $this->parse_placeholders( $this->subject ), $message, $this->get_headers(), $this->get_attachments() ), $this );

		$validation = apply_filters( 'xoo_wl_before_sending_email', $this->validation(), $this, $mail_callback_params );

		if( is_wp_error( $validation ) ){
			return $validation;
		}

		$return = $mail_callback( ...$mail_callback_params );
		//$return = true;

		$this->recipient_emails = array(); // empty list

		do_action( 'xoo_wl_email_'.$this->id.'_sent', $return, $this );

		remove_filter( 'wp_mail_from', array( $this, 'get_sender_email' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_sender_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		return $return;
	}


	public function validation(){
		return true;
	}



	/**
	 * Get email content type.
	 *
	 * @param string $default_content_type Default wp_mail() content type.
	 * @return string
	 */
	public function get_content_type() {
		return apply_filters( 'xoo_wl_email_content_type', 'text/html', $this );
	}

	public function get_attachments(){
		return apply_filters( 'xoo_wl_email_attachment', array(), $this );
	}

	public function get_headers(){
		return apply_filters( 'xoo_wl_email_headers', array(), $this );
	}


	abstract public function get_template();


	public function parse_placeholders( $text ){

		foreach ( $this->placeholders as $key => $value ) {
			$text = str_replace( $key, $value, $text );
		}

		return $text;

	}

	public function get_sender_name(){
		return apply_filters( 'xoo_wl_sender_name_'.$this->id, xoo_wl_helper()->get_email_option( 's-name' ) );
	}


	public function get_sender_email(){
		return apply_filters( 'xoo_wl_sender_email_'.$this->id, xoo_wl_helper()->get_email_option( 's-email' ) );
	}

	public function get_subject(){
		return apply_filters( 'xoo_wl_email_subject_'.$this->id, $this->subject );
	}

	public function get_site_logo(){
		return xoo_wl_helper()->get_email_option( 'gl-logo' );
	}

	public function preview_email_template( $row_id ){
		$set_row = $this->set_row_data( $row_id );
		if( is_wp_error( $set_row ) ){
			return $set_row;
		}
		return $this->parse_placeholders( $this->get_template() );
	}


	public function button_markup( $text, $url, $args = array() ){

		$defaults = array(
			'text' 			=>  $text,
			'url' 			=> $url,
			'txtColor' 		=> '#ffffff',
			'bgColor' 		=> '#00a63f',
			'vpadding' 		=> '10px',
			'hpadding' 		=> '40px',
			'fontWeight' 	=> 'bold',
			'fontFamily' 	=> 'sans-serif, Tahoma',
			'borderRadius' 	=> '3px',
			'fontSize' 		=> '16px',
			'border'		=> '1px solid #ffffff'
		);

		$args = apply_filters( 'xoo_wl_'.$this->id.'_button_args', wp_parse_args( $args, $defaults ), $this );

		extract($args);

		$borderV 		= $vpadding.' solid '.$bgColor;
		$borderH 		= $hpadding.' solid '.$bgColor;
		ob_start();
		?>

		<a href="<?php echo esc_url( $url ); ?>" style="
		border-radius: <?php echo esc_attr( $borderRadius ) ?>;
		color: <?php echo esc_attr( $txtColor ) ?>;
		text-decoration:none;
		background-color: <?php echo esc_attr( $bgColor ); ?>;
		border-top: <?php echo esc_attr( $borderV ) ?>;
		border-bottom: <?php echo esc_attr( $borderV ) ?>;
		border-left: <?php echo esc_attr( $borderH ) ?>;
		border-right: <?php echo esc_attr( $borderH ) ?>;
		display:inline-block;
		font-size: <?php echo esc_attr( $fontSize ) ?>;
		font-family: <?php echo esc_attr( $fontFamily ) ?>;
		font-weight: <?php echo esc_attr( $fontWeight ) ?>;"><?php echo esc_html( $text ); ?></a>
		<?php
		echo ob_get_clean();
	}

}


?>