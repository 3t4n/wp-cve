<?php

namespace Whodunit\MywpCustomPatterns\Admin;


use Whodunit\MywpCustomPatterns\Init\Core;


class Ajax {
	protected $core;

	public function __construct( Core $Core ) {
		$this->core = $Core;


		add_action( 'wp_ajax_save_template_who', array( $this, 'ajax_save' ) );
	}


	public function ajax_save() {

		/**
		 * Nonce non valide
		 */
		if ( ! wp_verify_nonce( $_POST['nonce'], 'create_pattern' ) ) {
			wp_send_json(
				array(
					'response' => 0,
				)
			);
			exit;
		}

		$post_id_ref       = (int) $_POST['post_id'];
		$mywp_template_cat = (int) $_POST['mywp_template_cat'];
		$title             = ( isset( $_POST['title'] ) && '' !== $_POST['title'] ) ? wp_strip_all_tags( sanitize_text_field( $_POST['title'] ) ) : esc_html__( 'Pattern', 'mywp-custom-patterns' );


		$authorized = wp_kses_allowed_html( 'post' );

		$authorized['iframe'] = array(
			'id'          => 1,
			'loading'     => 1,
			'style'       => 1,
			'src'         => 1,
			'height'      => 1,
			'width'       => 1,
			'frameborder' => 1,
		);
		$authorized['script'] = array(
			'src' => 1,
		);


		$template_content = $_POST['template_content'];
		$template_content = wp_kses( stripslashes_deep( $template_content ), $authorized );

		$template_content = str_replace( array(
			'u00',
		), array(
			'\u00',
		), $template_content );


		$template_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_content' => $template_content,
				'post_status'  => 'publish',
				'post_type'    => $this->core->name_cpt,
			)
		);

		$return = array(
			'response' => 0,
		);

		if ( is_int( $template_id ) && $template_id > 0 && $post_id_ref > 0 ) {
			if ( $mywp_template_cat > 0 ) {
				wp_set_object_terms( $template_id, $mywp_template_cat, $this->core->name_cat );
			}

			add_post_meta( $template_id, 'mywp_custom_pattern_post_id_ref', $post_id_ref );
			$return['response'] = 1;
			$return['id']       = $template_id;
			$return['title']    = $title;
			$return['content']  = $template_content;

		}

		wp_send_json( $return );
		exit;
	}
}
