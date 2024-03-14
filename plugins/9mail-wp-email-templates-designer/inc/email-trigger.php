<?php

namespace EmTmplF\Inc;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Email_Trigger {

	protected static $instance = null;
	protected $template_id;
	protected $object;
	protected $use_default_temp = false;
	protected $heading;
	protected $unique = [];

	private function __construct() {
		add_filter( 'wp_mail', [ $this, 'replace_email_content' ], 100 );
		add_action( 'woocommerce_email_footer', array( $this, 'ignore_9mail_for_woocommerce' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function replace_email_content( $args ) {
		$message = $args['message'];
		$key     = '{{ignore_9mail}}';

		if ( strpos( $message, $key ) !== false ) {
			$message         = str_replace( $key, '', $message );
			$args['message'] = $message;

			return $args;
		}

		$posts = get_posts( [
			'posts_per_page' => 1,
			'post_type'      => 'wp_email_tmpl',
			'post_status'    => 'publish',
			'meta_key'       => 'emtmpl_settings_type',
			'meta_value'     => 'default',
		] );

		if ( empty( $posts ) ) {
			return $args;
		}

		$post        = current( $posts );
		$template_id = $post->ID;

		$email_render = Email_Render::instance();
		$email_render->set_data( [ 'template_id' => $template_id, 'content' => $args['message'] ] );

		ob_start();
		$email_render->render();
		$message = ob_get_clean();

		$custom_style = $email_render->custom_style();
		$message      = str_replace( '[custom_style]', $custom_style, $message );

		if ( $message ) {
			$args['message'] = Utils::minify_html( $message );
			add_filter( 'wp_mail_content_type', [ $this, 'replace_content_type' ] );
		}

		return $args;
	}

	public function replace_content_type() {
		return 'text/html';
	}

	public function show_image( $args ) {
		if ( $this->use_default_temp ) {
			$show_image         = get_post_meta( $this->use_default_temp, 'emtmpl_enable_img_for_default_template', true );
			$args['show_image'] = $show_image ? true : false;

			$size               = get_post_meta( $this->use_default_temp, 'emtmpl_img_size_for_default_template', true );
			$args['image_size'] = $size ? [ (int) $size, 300 ] : [ 80, 80 ];
		}

		return $args;
	}

	public function custom_css( $style ) {
		if ( $this->use_default_temp || $this->template_id ) {
			$id    = $this->template_id ? $this->template_id : $this->use_default_temp;
			$style .= get_post_meta( $id, 'emtmpl_custom_css', true );
		}

		return $style;
	}

	public function ignore_9mail_for_woocommerce() {
		echo '{{ignore_9mail}}';
	}
}

