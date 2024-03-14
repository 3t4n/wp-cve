<?php

/**
 * Tranzly Posts Translator
 * @link       https://tranzly.io
 * @since      1.0.0
 * @package    Tranzly
 * @subpackage Tranzly/includes
 */

/**
 * Class Tranzly_Posts_Translator
 */
class Tranzly_Posts_Translator {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_tranzly_load_taxonomy', array( $this, 'load_taxonomy' ) );
		add_action('wp_ajax_tranzly_validate_before_translate_posts',array( $this, 'validate_before_translate_posts' ));
		add_action( 'wp_ajax_tranzly_translate_posts', array( $this, 'translate_posts' ) );
		add_action( 'wp_ajax_tranzly_generate_posts', array( $this, 'generate_posts' ) );
	}

	/**
	 * Loads the taxonomy via ajax.	
	 */
	public function load_taxonomy() {
		$wptranzlynonce = isset( $_REQUEST['tranzly_nonce'] ) ? wp_verify_nonce( sanitize_key( $_REQUEST['_tranzly_nonce'] ), '_tranzly_nonce' ) : false;
		//check_ajax_referer( 'tranzly_process_translation', '_tranzly_nonce' );

		$tranzly_options = isset( $_POST['tranzly_options'] ) ?
			array_map( 'sanitize_text_field', wp_unslash( $_POST['tranzly_options'] ) )
			: array();

		$post_type = isset( $tranzly_options['post_type'] ) ? $tranzly_options['post_type'] : '';

		if ( $post_type ) {
			tranzly_get_post_type_taxonomy_filter_markup( $post_type );
		}

		exit;
	}

	/**
	 * Validates the user input before translating posts.
	 */
	public function validate_before_translate_posts() {
		$messages = array();
		$valid    = 'false';

		$wptranzlynonce = isset( $_REQUEST['_tranzly_nonce'] ) ? wp_verify_nonce( sanitize_key( $_REQUEST['_tranzly_nonce'] ), '_tranzly_nonce' ) : false;
		//check_ajax_referer( 'tranzly_process_translation', '_tranzly_nonce', false );
		//if ( check_ajax_referer( 'tranzly_process_translation', '_tranzly_nonce', false ) ) {
		$tranzly_options = isset( $_POST['tranzly_options'] ) ?
			array_map( 'sanitize_text_field', wp_unslash( $_POST['tranzly_options'] ) )
			: array();

		$post_type       = isset( $tranzly_options['post_type'] ) ? $tranzly_options['post_type'] : '';
		$source_language = isset( $tranzly_options['source_language'] ) ? $tranzly_options['source_language'] : '';
		$target_language = isset( $tranzly_options['target_language'] ) ? $tranzly_options['target_language'] : '';

		if ( ! $post_type ) {
			$messages[] = esc_html__( 'Post type is required', 'tranzly' );
		} elseif ( ! array_key_exists( $post_type, tranzly_get_translatable_post_types() ) ) {
			$messages[] = esc_html__( 'Invalid post type', 'tranzly' );
		}

		if (
			'auto' !== $source_language
			&& ! array_key_exists( $source_language, tranzly_supported_languages() )
		) {
			$messages[] = esc_html__( 'Invalid source language', 'tranzly' );
		}

		if ( ! array_key_exists( $target_language, tranzly_supported_languages() ) ) {
			$messages[] = esc_html__( 'Invalid target language', 'tranzly' );
		}
		

		if ( ! $messages ) {
			$valid = 'true';
		}

		$response = array(
			'valid'    => $valid,
			'messages' => $messages,
		);

		wp_send_json( $response );
	}

	/**
	 * Translate the posts via ajax.
	 */
	public function translate_posts() {
		$success = 'false';
		$status  = 'incomplete';
		$data    = array();
		$errors  = array();

		$wptranzlynonce = isset( $_REQUEST['_tranzly_nonce'] ) ? wp_verify_nonce( sanitize_key( $_REQUEST['_tranzly_nonce'] ), '_tranzly_nonce' ) : false;

		 /**
         * Note to WordPress.org Reviewers:
         *  This is a helper method to fetch POST Options user input with an optional default value when the input is not set. The actual sanitization is done in the scope of the function's usage.
         */
		 
		$tranzly_options = isset( $_POST['tranzly_options'] ) ?
			array_map( 'sanitize_text_field', wp_unslash( $_POST['tranzly_options'] ) )
			: array();

		$page  = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 0;

		$post_type            = isset( $tranzly_options['post_type'] ) ? $tranzly_options['post_type'] : '';
		$translate_atts       = isset( $tranzly_options['translate_atts'] ) ? $tranzly_options['translate_atts'] : '';
		$translate_slug       = isset( $tranzly_options['translate_slug'] ) ? $tranzly_options['translate_slug'] : '';
		$translate_seo        = isset( $tranzly_options['translate_seo'] ) ? $tranzly_options['translate_seo'] : '';
		$override_translation = true; //isset( $tranzly_options['override_translation'] ) ? $tranzly_options['override_translation'] : '';
		$source_language      = isset( $tranzly_options['source_language'] ) ? $tranzly_options['source_language'] : '';
		$target_language      = isset( $tranzly_options['target_language'] ) ? $tranzly_options['target_language'] : '';

		$tax_query = array();

	
		$taxonomies = isset( $_POST['tranzly_options']['taxonomy'] ) ?
		sanitize_text_field(wp_unslash( $_POST['tranzly_options']['taxonomy']) ) : array();
		

		foreach ( $taxonomies as $taxonony => $terms ) {
			$tax_query[] = array(
				'taxonomy' => $taxonony,
				'field'    => 'term_id',
				'terms'    => array_map( 'absint', $terms ),
			);
		}

		$translator = new Tranzly_Translator();
		$translator->set_source_lang( $source_language );
		$translator->set_target_lang( $target_language );
		$translator->set_translate_attributes( $translate_atts );
		$translator->set_translate_slug( $translate_slug );
		$translator->set_translate_seo( $translate_seo );

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query' => array(
								array(
									'key'     => 'deepl_translated',
									'value'   => 1,
									'compare' => '='
									),
							),
			'posts_per_page' => apply_filters( 'tranzly_translate_posts_per_batch', 1 ),
			'paged'          => $page,
			'tax_query'      => $tax_query, 
		);

		$args = apply_filters( 'tranzly_translate_posts_args', $args, $tranzly_options );

		$results     = new WP_Query( $args );
		$total_posts = $results->found_posts;
		$max_pages   = $results->max_num_pages;
		$translated = array();
		if ( $page <= $max_pages ) {
			$should_translate = false;
			foreach ( $results->posts as $post_id ) {
				// Translate the post.
				$key           = '_tranzly_post_translated_to_' . $target_language;
				$is_translated = get_post_meta( $post_id, $key, true );
				$tranzly_target_language = get_post_meta( $post_id, 'tranzly_post_translated_to', true );

				$deepl_translated = get_post_meta($post_id, 'deepl_translated',true);
				if ( ! $is_translated ) {
					$should_translate = true;
				}
				if ( $is_translated && $override_translation ) {
					$should_translate = true;
				}
				
				if ($should_translate) {
					try {

							// $tranzly_target_language = get_post_meta( $post_id, 'tranzly_post_translated_to', true );
							// if ($tranzly_target_language) {
							// 	$newArr[]=array('translated_to'=>$target_language,'translated_from'=>$source_language);
							// 	$translated_to=array_merge($tranzly_target_language,$newArr);
							// }else{
							// 	$translated_to[]= array('translated_to'=>$target_language,'translated_from'=>$source_language,'tranzly_child_post_id'=>$generate_post_id);
							// }
							$translated[] = $post_id;
							$translator->translate_post( $post_id );
							update_post_meta($post_id, 'tranzly_mylang', $target_language);
							//update_post_meta($post_id, 'tranzly_post_translated_to', $translated_to);
							update_post_meta($post_id, $key, true);

					} catch ( Exception $e ) {
						$errors[] = sprintf(
							/* translators: post_id, error */
							esc_html__( 'Error translating post#%1$d. Error: %2$s', 'tranzly' ),
							$post_id,
							$e->getMessage()
						);
					}
				} else {
					$errors[] = sprintf(
						/*translators: post_id, target language*/
						esc_html__( 'A translation already exists for post#%1$d lang#%2$s', 'tranzly' ),
						$post_id,
						$target_language
					);
				}

				$count++;
			}

			// Calculate percentage.
			$percentage = floor( ( $count / $total_posts ) * 100 );

			$page++;

			$data = array(
				'page'        => $page,
				'count'       => $count,
				'total_posts' => $total_posts,
				'status'      => $status,
				'percentage'  => $percentage,
				'errors'      => $errors,
				'translated'  => $translated,
			);
		} else {
			$data = array(
				'page'        => $page,
				'count'       => $count,
				'total_posts' => $total_posts,
				'status'      => 'complete',
				'percentage'  => 100,
				'errors'      => $errors,
			);
		}

		$success = 'true';

		$response = array(
			'success' => $success,
			'data'    => $data,
			'deepl_translated'=>$deepl_translated
		);

		wp_send_json( $response );
	}
	public function generate_posts() {
		$success = 'false';
		$status  = 'incomplete';
		$data    = array();
		$errors  = array();

		$wptranzlynonce = isset( $_REQUEST['_tranzly_nonce'] ) ? wp_verify_nonce( sanitize_key( $_REQUEST['_tranzly_nonce'] ), '_tranzly_nonce' ) : false;
		
		$tranzly_options = isset( $_POST['tranzly_options'] ) ?
			array_map( 'sanitize_text_field', wp_unslash( $_POST['tranzly_options'] ) )
			: array();
		/**
         * Note to WordPress.org Reviewers:
         *  This is a helper method to fetch POST Options user input with an optional default value when the input is not set. The actual sanitization is done in the scope of the function's usage.
         */
		$page  = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 0;

		$post_type            = isset( $tranzly_options['post_type'] ) ? $tranzly_options['post_type'] : '';
		$tranzly_post_status            = isset( $tranzly_options['tranzly_post_status'] ) ? $tranzly_options['tranzly_post_status'] : '';
		$translate_atts       = isset( $tranzly_options['translate_atts'] ) ? $tranzly_options['translate_atts'] : '';
		$translate_slug       = isset( $tranzly_options['translate_slug'] ) ? $tranzly_options['translate_slug'] : '';
		$translate_seo        = isset( $tranzly_options['translate_seo'] ) ? $tranzly_options['translate_seo'] : '';
		$override_translation = true; //isset( $tranzly_options['override_translation'] ) ? $tranzly_options['override_translation'] : '';
		$source_language      = isset( $tranzly_options['source_language'] ) ? $tranzly_options['source_language'] : '';
		$target_language      = isset( $tranzly_options['target_language'] ) ? $tranzly_options['target_language'] : '';

		$tax_query = array();

		if ($tranzly_post_status) {
			$tranzly_status='draft';
		}else{
			$tranzly_status='publish';
		}


		
		$taxonomies = isset( $_POST['tranzly_options']['taxonomy'] ) ?
		sanitize_text_field(wp_unslash( $_POST['tranzly_options']['taxonomy'] )) : array();
	

		foreach ( $taxonomies as $taxonony => $terms ) {
			$tax_query[] = array(
				'taxonomy' => $taxonony,
				'field'    => 'term_id',
				'terms'    => array_map( 'absint', $terms ),
			);
		}

		$translator = new Tranzly_Translator();
		$translator->set_source_lang( $source_language );
		$translator->set_target_lang( $target_language );
		$translator->set_translate_attributes( $translate_atts );
		$translator->set_translate_slug( $translate_slug );
		$translator->set_translate_seo( $translate_seo );

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query' => array(
								array(
									'key'     => 'deepl_translated',
									'value'   => 1,
									'compare' => '='
									),
							),
			'meta_key'=>'deepl_translated',
			'posts_per_page' => apply_filters( 'tranzly_translate_posts_per_batch', 1 ),
			'paged'          => $page,
			'tax_query'      => $tax_query, 
		);

		$args = apply_filters( 'tranzly_translate_posts_args', $args, $tranzly_options );

		$results     = new WP_Query( $args );
		$total_posts = $results->found_posts;
		$max_pages   = $results->max_num_pages;
		$translated = array();
		if ( $page <= $max_pages ) {
			$should_translate = false;
			foreach ( $results->posts as $post_id ) {
				// Translate the post.
				$key           = '_tranzly_post_translated_to_' . $target_language;
				$is_translated = get_post_meta( $post_id, $key, true );
				
				$deepl_translated = get_post_meta($post_id, 'deepl_translated',true);
				if ( ! $is_translated ) {
					$should_translate = true;
				}

				if ( $is_translated && $override_translation ) {
					$should_translate = true;
				}
				
				if ($should_translate) {
					try {
							$translated[] = $post_id;
							$generate_post_id=$translator->generate_post($post_id,$tranzly_status);
							$tranzly_target_language = get_post_meta( $post_id, 'tranzly_post_translated_to', true );
							if ($tranzly_target_language) {
								$newArr[]=array('translated_to'=>$target_language,'translated_from'=>$source_language,'tranzly_child_post_id'=>$generate_post_id);
								$translated_to=array_merge($tranzly_target_language,$newArr);
								foreach ($translated_to as $tranzly_translated) {$alltranslated_to[]=$tranzly_translated['translated_to'];}
								$Match=0;
								foreach (tranzly_supported_languages() as $code => $name ) {
								if (in_array($code, $alltranslated_to)){$Match++;}}
								if ($Match>=8) {update_post_meta($post_id, 'deepl_translated', 2 );}
							}else{
								$translated_to[]= array('translated_to'=>$target_language,'translated_from'=>$source_language,'tranzly_child_post_id'=>$generate_post_id);
							}
							$tranzly_post_translated[]=array('translated_from'=>$source_language,'translated_to'=>$target_language,'tranzly_parent_post_id'=>$post_id);
							update_post_meta($generate_post_id, 'tranzly_post_translated_to_from', $tranzly_post_translated);
							update_post_meta($post_id, 'tranzly_post_translated_to', $translated_to);

							update_post_meta($generate_post_id, 'translated_from', $target_language);
							update_post_meta($post_id, 'translated_to', $source_language);

							update_post_meta($generate_post_id, 'tranzly_mylang', $target_language);
							update_post_meta($post_id, 'tranzly_mylang', $source_language);


							update_post_meta($generate_post_id, 'deepl_translated', 2 );

							update_post_meta($post_id, $key, true);
						
					} catch ( Exception $e ) {
						$errors[] = sprintf(
							/* translators: post_id, error */
							esc_html__( 'Error translating post#%1$d. Error: %2$s', 'tranzly' ),
							$post_id,
							$e->getMessage()
						);
					}
				} else {
					$errors[] = sprintf(
						/*translators: post_id, target language*/
						esc_html__( 'A translation already exists for post#%1$d lang#%2$s', 'tranzly' ),
						$post_id,
						$target_language
					);
				}

				$count++;
			}

			// Calculate percentage.
			$percentage = floor( ( $count / $total_posts ) * 100 );

			$page++;

			$data = array(
				'page'        => $page,
				'count'       => $count,
				'total_posts' => $total_posts,
				'status'      => $status,
				'percentage'  => $percentage,
				'errors'      => $errors,
				'translated'  => $translated,
			);
		} else {
			$data = array(
				'page'        => $page,
				'count'       => $count,
				'total_posts' => $total_posts,
				'status'      => 'complete',
				'percentage'  => 100,
				'errors'      => $errors,
			);
		}

		$success = 'true';

		$response = array(
			'success' => $success,
			'data'    => $data,
		);

		wp_send_json( $response );
	}

}

if ( is_admin() ) {
	new Tranzly_Posts_Translator();
}