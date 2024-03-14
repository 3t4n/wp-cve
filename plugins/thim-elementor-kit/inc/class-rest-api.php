<?php
namespace Thim_EL_Kit;

use Thim_EL_Kit\Utilities\Rest_Response;
use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\Modules\Cache;
use Thim_EL_Kit\Elementor\Library\Import;

class Rest_API {

	use SingletonTrait;

	const NAMESPACE = 'thim-ekit';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	public function register_endpoints() {
		register_rest_route(
			self::NAMESPACE,
			'/create-template',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_template' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/get-posts',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_posts' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);
		register_rest_route(
			self::NAMESPACE,
			'/select-query-conditions',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'select_query_conditions' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/get-conditions',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_conditions' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/save-conditions',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_conditions' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/get-layout-libraries',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_layout_libraries' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		do_action( 'thim_ekit/rest_api/register_endpoints', self::NAMESPACE );
	}

	public function permission_callback() {
		return current_user_can( 'edit_posts' );
	}

	public function select_query_conditions( $request ) {
		$type   = $request->get_param( 'type' );
		$search = $request->get_param( 'search' );

		$output = array();

		switch ( $type ) {
			case 'course_category':
			case 'course_tag':
			case 'course_term':
				$taxonomy = array();

				if ( $type === 'course_category' || 'course_term' === $type ) {
					$taxonomy[] = 'course_category';
				}

				if ( $type === 'course_tag' || 'course_term' === $type ) {
					$taxonomy[] = 'course_tag';
				}

				$terms = get_terms(
					array(
						'hide_empty' => false,
						'fields'     => 'all',
						'taxonomy'   => $taxonomy,
						'search'     => $search,
					)
				);

				if ( count( $terms ) > 0 ) {
					foreach ( $terms as $term ) {
						$output[] = array(
							'id'    => $term->term_id,
							'title' => htmlspecialchars_decode( $term->name ) . ' (ID: ' . $term->term_id . ', Tax: ' . $term->taxonomy . ')',
						);
					}
				}
				break;

			case 'course_id':
				$courses = get_posts(
					array(
						'post_type'      => 'lp_course',
						'posts_per_page' => 100,
						's'              => $search,
					)
				);

				if ( count( $courses ) > 0 ) {
					foreach ( $courses as $course ) {
						$output[] = array(
							'id'    => $course->ID,
							'title' => htmlspecialchars_decode( $course->post_title ) . ' (ID: ' . $course->ID . ')',
						);
					}
				}
				break;

			case 'post_category':
			case 'post_tag':
			case 'post_term':
				$taxonomy = array();

				if ( $type === 'post_category' || 'post_term' === $type ) {
					$taxonomy[] = 'category';
				}

				if ( $type === 'post_tag' || 'post_term' === $type ) {
					$taxonomy[] = 'post_tag';
				}
				$terms = get_terms(
					array(
						'hide_empty' => false,
						'fields'     => 'all',
						'taxonomy'   => $taxonomy,
						'search'     => $search,
					)
				);

				if ( count( $terms ) > 0 ) {
					foreach ( $terms as $term ) {
						$output[] = array(
							'id'    => $term->term_id,
							'title' => htmlspecialchars_decode( $term->name ) . ' (ID: ' . $term->term_id . ', Tax: ' . $term->taxonomy . ')',
						);
					}
				}
				break;

			case 'select_post_author':
				// Get all post authors.
				$authors = get_users(
					array(
						'who' => 'authors',
					)
				);

				if ( count( $authors ) > 0 ) {
					foreach ( $authors as $author ) {
						$output[] = array(
							'id'    => $author->ID,
							'title' => htmlspecialchars_decode( $author->display_name ) . ' (ID: ' . $author->ID . ')',
						);
					}
				}
				break;

			case 'select_post':
				$posts = get_posts(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 100,
						's'              => $search,
					)
				);

				if ( count( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						$output[] = array(
							'id'    => $post->ID,
							'title' => htmlspecialchars_decode( $post->post_title ) . ' (ID: ' . $post->ID . ')',
						);
					}
				}
				break;

			case 'product_category':
			case 'product_tag':
			case 'product_term':
				$taxonomy = array();

				if ( $type === 'product_category' || 'product_term' === $type ) {
					$taxonomy[] = 'product_cat';
				}

				if ( $type === 'product_tag' || 'product_term' === $type ) {
					$taxonomy[] = 'product_tag';
				}
				$terms = get_terms(
					array(
						'hide_empty' => false,
						'fields'     => 'all',
						'taxonomy'   => $taxonomy,
						'search'     => $search,
					)
				);

				if ( count( $terms ) > 0 ) {
					foreach ( $terms as $term ) {
						$output[] = array(
							'id'    => $term->term_id,
							'title' => htmlspecialchars_decode( $term->name ) . ' (ID: ' . $term->term_id . ', Tax: ' . $term->taxonomy . ')',
						);
					}
				}
				break;

			case 'product_id':
				$products = get_posts(
					array(
						'post_type'      => 'product',
						'posts_per_page' => 100,
						's'              => $search,
					)
				);

				if ( count( $products ) > 0 ) {
					foreach ( $products as $product ) {
						$output[] = array(
							'id'    => $product->ID,
							'title' => htmlspecialchars_decode( $product->post_title ) . ' (ID: ' . $product->ID . ')',
						);
					}
				}
				break;

			// Use in header footer builder.
			case 'archive_post_type':
			case 'singular_post_type':
				$post_types = get_post_types(
					array(
						'public'      => true,
						'has_archive' => true,

					),
					'objects'
				);

				if ( count( $post_types ) > 0 ) {
					foreach ( $post_types as $post_type ) {
						$output[] = array(
							'id'    => $post_type->name,
							'title' => htmlspecialchars_decode( $post_type->label ) . ' (' . $post_type->name . ')',
						);
					}
				}

				// Check if has "post" post type, if not, add it.
				if ( ! in_array( 'post', array_column( $output, 'id' ), true ) ) {
					$output[] = array(
						'id'    => 'post',
						'title' => 'Post (post)',
					);
				}
				break;

			case 'select_page':
				$pages = get_posts(
					array(
						'post_type'      => 'page',
						'posts_per_page' => 100,
						's'              => $search,
					)
				);

				if ( count( $pages ) > 0 ) {
					foreach ( $pages as $page ) {
						$output[] = array(
							'id'    => $page->post_name,
							'title' => htmlspecialchars_decode( $page->post_title ) . ' (ID: ' . $page->ID . ')',
						);
					}
				}
				break;
		}

		// filter for custom.
		$output = apply_filters( 'thim_ekit/rest_api/select_query_conditions', $output, $type, $search );

		return rest_ensure_response( $output );
	}

	// When click "Edit conditions" button.
	public function get_conditions( $request ) {
		$post_id = $request->get_param( 'postID' );

		$conditions = get_post_meta( $post_id, 'thim_ekits_conditions', true );

		if ( ! $conditions ) {
			return rest_ensure_response( false );
		}

		return rest_ensure_response( $conditions );
	}

	// When "Save" button.
	public function save_conditions( $request ) {
		$conditions = $request->get_param( 'conditions' );
		$post_id    = $request->get_param( 'postID' );

		try {
			if ( ! $post_id ) {
				throw new \Exception( 'Invalid data' );
			}

			$this->update_conditions( $post_id, $conditions );

			return rest_ensure_response( array( 'success' => true ) );

		} catch ( \Throwable $th ) {
			return rest_ensure_response(
				array(
					'success' => false,
					'message' => $th->getMessage(),
				)
			);
		}
	}

	private function update_conditions( $post_id, $conditions ) {
		if ( ! empty( $conditions ) ) {
			update_post_meta( $post_id, 'thim_ekits_conditions', $conditions );
		} else {
			delete_post_meta( $post_id, 'thim_ekits_conditions' );
		}

		Cache::instance()->regenerate();
	}

	// Use in Select Post Preview in Elementor.
	public function get_posts( \WP_REST_Request $request ) {
		if ( post_type_exists( $request['post_type'] ) ) {
			$query_args = array(
				'post_type'      => $request['post_type'] ?? 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 15,
			);
		} else {
			$query_args = apply_filters( 'thim_ekit/rest_api/get_custom_post', array() );
		}
		if ( isset( $request['ids'] ) ) {
			$ids                    = explode( ',', $request['ids'] );
			$query_args['post__in'] = $ids;
		}

		if ( isset( $request['s'] ) ) {
			$query_args['s'] = $request['s'];
		}

		$query = new \WP_Query( $query_args );

		$options = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$options[] = array(
					'id'   => get_the_ID(),
					'text' => get_the_title(),
				);
			}
		}

		wp_reset_postdata();

		return array( 'results' => $options );
	}

	public function create_template( \WP_REST_Request $request ) {
		$response = new Rest_Response();

		$type       = ! empty( $request['type'] ) ? sanitize_text_field( $request['type'] ) : '';
		$name       = ! empty( $request['name'] ) ? sanitize_text_field( $request['name'] ) : '';
		$conditions = ! empty( $request['conditions'] ) ? $request['conditions'] : '';
		$sticky     = ! empty( $request['sticky'] ) ? $request['sticky'] : false;
		$layout     = ! empty( $request['layout'] ) ? $request['layout'] : false;

		try {
			if ( empty( $name ) ) {
				throw new \Exception( esc_html__( 'Please enter template name', 'thim-elementor-kit' ) );
			}

			do_action( 'thim_ekit/rest_api/create_template/before', $request );

			$args = apply_filters(
				'thim_ekit/rest_api/create_template/args',
				array(
					'post_title'  => $name,
					'post_type'   => Custom_Post_Type::CPT,
					'post_status' => 'publish',
					'meta_input'  => array(
						'_elementor_edit_mode' => 'builder',
					),
				)
			);

			if ( ! empty( $type ) ) {
				$args['meta_input']['thim_elementor_type'] = $type;
			}

			$id = wp_insert_post( $args );

			if ( is_wp_error( $id ) ) {
				throw new \Exception( esc_html__( 'Cannot insert template', 'thim-elementor-kit' ) );
			}

			if ( $type === 'header' ) {
				update_post_meta( $id, 'thim_elementor_sticky', $sticky );
			}

			// Check if WPML is active.
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				global $sitepress;

				// Get the post type
				$type = get_post_type( $id );

				// Get the translation id (trid)
				$get_language_args           = array(
					'element_id'   => $id,
					'element_type' => 'post_' . $type,
				);
				$original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
				$trid                        = $original_post_language_info->trid;

				// Set the desired language
				$language_code = $sitepress->get_default_language();

				// Update the post language info
				$language_args = array(
					'element_id'           => $id,
					'element_type'         => 'post_' . $type,
					'trid'                 => $trid,
					'language_code'        => $language_code,
					'source_language_code' => null,
				);

				do_action( 'wpml_set_element_language_details', $language_args );
			}

			$this->update_conditions( $id, $conditions );

			if ( ! empty( $layout['id'] ) ) {
				$theme = wp_get_theme();

				if ( $theme->parent() ) {
					$theme = $theme->parent();
				}

				$body = array(
					'id'    => absint( $layout['id'] ),
					'type'  => 'layouts',
					'theme' => $layout['isPro'] ? $theme->get( 'TextDomain' ) : '',
				);

				if ( class_exists( '\Thim_Product_Registration' ) ) {
					$site_key = \Thim_Product_Registration::get_site_key();
					$code     = thim_core_generate_code_by_site_key( $site_key );

					if ( ! empty( $site_key ) ) {
						$body['code'] = $code ? $code : '';
					} else {
						$purchase_token = '';

						if ( method_exists( '\Thim_Product_Registration', 'get_data_theme_register' ) ) {
							$purchase_token = \Thim_Product_Registration::get_data_theme_register( 'purchase_token' );
						}

						$body['purchase_token'] = $purchase_token;
					}
				}

				$fetch = wp_remote_post(
					'https://updates.thimpress.com/wp-json/thim_em/v1/thim-kit/import-library',
					array(
						'timeout' => 60,
						'body'    => $body,
					)
				);

				if ( ! is_wp_error( $fetch ) ) {
					$api_body = json_decode( wp_remote_retrieve_body( $fetch ), true );

					if ( isset( $api_body['code'] ) && $api_body['code'] === 'no_code' ) {
						throw new \Exception( class_exists( '\Thim_Product_Registration' ) ? 'Please <a href="' . admin_url( '/admin.php?page=thim-dashboard' ) . '" target="_blank" rel="noopener">Active theme</a> to continue' : 'Please register your site to use this feature.' );
					}

					// go to thim license page.
					if ( $api_body['code'] === 'no_purchase_code' ) {
						throw new \Exception( class_exists( '\Thim_Product_Registration' ) ? 'Please <a href="' . admin_url( '/admin.php?page=thim-license' ) . '" target="_blank" rel="noopener">add purchase code</a> to continue.' : 'Please register your site to use this feature.' );
					}

					if ( $api_body['code'] === 'invalid_purchase_code' ) {
						throw new \Exception( class_exists( '\Thim_Product_Registration' ) ? 'Please <a href="' . admin_url( '/admin.php?page=thim-license' ) . '" target="_blank" rel="noopener">add valid purchase code</a> to continue.' : 'Please register your site to use this feature.' );
					}

					// if is error.
					if ( ! empty( $api_body['code'] ) ) {
						throw new \Exception( $api_body['message'] ?? 'Something went wrong.' );
					}

					if ( ! empty( $api_body['content'] ) ) {
						$import = new Import();
						$import->import( $id, $api_body['content'] );
					}
				}
			}

			do_action( 'thim_ekit/rest_api/create_template/after', $id, $request );

			$url = add_query_arg(
				array(
					'post'   => $id,
					'action' => 'elementor',
				),
				admin_url( 'post.php' )
			);

			$response->status         = 'success';
			$response->data->id       = $id;
			$response->data->redirect = $url;
			$response->message        = esc_html__( 'Create template successfully, Redirecting...', 'thim-elementor-kit' );
		} catch ( \Throwable $th ) {
			$response->message = $th->getMessage();
		}

		return rest_ensure_response( $response );
	}

	// Use for chooses layout for create template from Thim Elementor Kit.
	public function get_layout_libraries( \WP_REST_Request $request ) {
		$theme = wp_get_theme();

		if ( $theme->parent() ) {
			$theme = $theme->parent();
		}

		$response = wp_remote_get( 'https://updates.thimpress.com/wp-json/thim_em/v1/thim-kit/get-layout-libraries?theme=' . $theme->get( 'TextDomain' ) );

		$raw = ! is_wp_error( $response ) ? json_decode( wp_remote_retrieve_body( $response ), true ) : array();

		return rest_ensure_response( $raw );
	}
}

Rest_API::instance();
