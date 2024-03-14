<?php

session_start();

class Dxlore_Captcha {
	
	// properties
	private $width = 140;
	private $height = 50;	
	private $codes = '23456789qwertyuipasdfghjkzxcvbnmQWERTYUPASDFGHJKZXCVBNM';
	private $code_num = 4;	
	private $font_path;
	private $font_color;
	private $font_size = 20;
	private $bg_line = 6;
	private $bg_dot = 20;
	private $img;
	private $captcha;
	private $session_name = 'register_captcha';
	private $session_timeout_name = 'register_captcha_timeout';
	private $session_timeout = 120;
	
	// init for datas
	public function __construct( $args = array() ) {
		$this->width = isset( $args['width'] ) ? $args['width'] : $this->width;		// set image width		
		$this->height = isset( $args['height'] ) ? $args['height'] : $this->height;		// set image height
		$this->codes = isset( $args['codes'] ) ? $args['codes'] : $this->codes;		// set codes
		$this->code_num = isset( $args['code_num'] ) ? $args['code_num'] : $this->code_num;		// set coude num
		$this->font_size = isset( $args['font_size'] ) ? $args['font_size'] : $this->font_size;	// set font size
		$this->bg_line = isset( $args['bg_line'] ) ? $args['bg_line'] : $this->bg_line;		// set random line for bg
		$this->bg_dot = isset( $args['bg_dot'] ) ? $args['bg_dot'] : $this->bg_dot;		// set random dot for bg
		$this->font_path = dirname( __FILE__ ) . '/elephant.ttf';
	}
	
	// create background
	private function create_bg() {
		$im = imagecreatetruecolor( $this->width, $this->height );
		$color = imagecolorallocate( $im, mt_rand( 157, 255 ), mt_rand( 157, 255 ), mt_rand( 157, 255 ) );
		imagefill( $im, 0, 0, $color );
		$this->img = $im;
	}
	
	// create line background
	private function create_line() {
		if( 0 == $this->bg_line )
			return;
		for( $i = 0; $i < $this->bg_line; $i++ ) {
			$color = imagecolorallocate( $this->img, mt_rand( 100, 156 ), mt_rand( 100, 156 ), mt_rand( 100, 156 ) );
			imageline( $this->img, mt_rand( 0, $this->width ), mt_rand( 0, $this->width ), mt_rand( 0, $this->height ), mt_rand( 0, $this->height ), $color );
		}
	}
	
	// create dot background
	private function create_dot() {
		if( 0 == $this->bg_dot )
			return;
		for( $i = 0; $i < $this->bg_dot; $i++ ) {
			$color = imagecolorallocate( $this->img, mt_rand( 100, 156 ), mt_rand( 100, 156 ), mt_rand( 100, 156 ) );
			imagesetpixel( $this->img,  mt_rand( 0, $this->width ), mt_rand( 0, $this->height ), $color );
		}
	}
		
	// create code
	private function create_code() {
		$len = strlen( $this->codes ) -1 ;
		for( $i = 0; $i < $this->code_num; $i++ ) {
			$code .= substr( $this->codes, mt_rand( 0, $len ), 1 );
		}
		$this->captcha = $code;
	}
	
	// create font text
	private function create_font() {
		$angle = 0;
		$y = $this->height * 5 / 7;
		$average_width = $this->width / $this->code_num;
		for( $i = 0; $i < $this->code_num; $i++ ) {
			$this->font_color = imagecolorallocate( $this->img, mt_rand( 0,99 ), mt_rand( 0, 99 ), mt_rand( 0, 99 ) );
			$angle = mt_rand( -30, 30 );
			$x = $average_width * $i + mt_rand( 4, 6 );
			$code = substr( $this->captcha, $i, 1 );
			imagettftext( $this->img, $this->font_size, $angle, $x, $y, $this->font_color, $this->font_path, $code );
		}
	}
	
	// output img image
	private function output() {
		header( 'Content-type:image/png' );
		imagepng( $this->img );
		imagedestroy( $this->img );
	}
	
	// do img 
	public function do_img() {
		$this->create_bg();
		$this->create_line();
		$this->create_dot();
		$this->create_code();
		$this->create_font();
		$_SESSION[ $this->session_name ] = strtolower( $this->captcha );
		$_SESSION[ $this->session_timeout_name ] = time() + $this->session_timeout;
		$this->output();
	}	
	
}