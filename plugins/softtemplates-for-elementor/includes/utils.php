<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Utils' ) ) {

	/**
	 * Define Soft_template_Core_Utils class
	 */
	class Soft_template_Core_Utils {
		/**
		 * Elementor Saved page templates list
		 *
		 * @var page_templates
		 */
		private static $page_templates = null;

		/**
		 * Elementor saved section templates list
		 *
		 * @var section_templates
		 */
		private static $section_templates = null;

		/**
		 * Elementor saved widget templates list
		 *
		 * @var widget_templates
		 */
		private static $widget_templates = null;

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public static function get_post_types() {

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$deprecated = apply_filters(
				'soft-template-core/post-types-list/deprecated',
				array(
					'attachment',
					'elementor_library',
					soft_template_core()->templates->post_type,
				)
			);

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $post_type->label;

			}

			return $result;

		}

		/**
		 * Returns all custom taxonomies
		 *
		 * @return [type] [description]
		 */
		public static function get_taxonomies() {

			$taxonomies = get_taxonomies( array(
				'public'   => true,
				'_builtin' => false
			), 'objects' );

			$deprecated = apply_filters(
				'soft-template-core/taxonomies-list/deprecated',
				array()
			);

			$result = array();

			if ( empty( $taxonomies ) ) {
				return $result;
			}

			foreach ( $taxonomies as $slug => $tax ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $tax->label;

			}

			return $result;

		}

		public static function get_taxonomies_content( $args = [], $output = 'names', $operator = 'and' ) {
			global $wp_taxonomies;
	
			$field = ( 'names' === $output ) ? 'name' : false;
	
			// Handle 'object_type' separately.
			if ( isset( $args['object_type'] ) ) {
				$object_type = (array) $args['object_type'];
				unset( $args['object_type'] );
			}
	
			$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );
	
			if ( isset( $object_type ) ) {
				foreach ( $taxonomies as $tax => $tax_data ) {
					if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
						unset( $taxonomies[ $tax ] );
					}
				}
			}
	
			if ( $field ) {
				$taxonomies = wp_list_pluck( $taxonomies, $field );
			}
	
			return $taxonomies;
		}

		public static function search_posts_by_type( $type, $query, $ids = array() ) {

			add_filter( 'posts_where', array( __CLASS__, 'force_search_by_title' ), 10, 2 );

			$posts = get_posts( array(
				'post_type'           => $type,
				'ignore_sticky_posts' => true,
				'posts_per_page'      => -1,
				'suppress_filters'    => false,
				's_title'             => $query,
				'include'             => $ids,
			) );

			remove_filter( 'posts_where', array( __CLASS__, 'force_search_by_title' ), 10 );

			$result = array();

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$result[] = array(
						'id'   => $post->ID,
						'text' => $post->post_title,
					);
				}
			}

			return $result;
		}

		/**
		 * Force query to look in post title while searching
		 * @return [type] [description]
		 */
		public static function force_search_by_title( $where, $query ) {

			$args = $query->query;

			if ( ! isset( $args['s_title'] ) ) {
				return $where;
			} else {
				global $wpdb;

				$searh = esc_sql( $wpdb->esc_like( $args['s_title'] ) );
				$where .= " AND {$wpdb->posts}.post_title LIKE '%$searh%'";

			}

			return $where;
		}

		public static function search_terms_by_tax( $tax, $query, $ids = array() ) {

			$terms = get_terms( array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
				'name__like' => $query,
				'include'    => $ids,
			) );

			$result = array();

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$result[] = array(
						'id'   => $term->term_id,
						'text' => $term->name,
					);
				}
			}

			return $result;

		}

		/**
		 *  Get Saved templates
		 *
		 *  @param string $type Type.
		 *  @since 1.22.0
		 *  @return array of templates
		 */
		public static function get_saved_data( $type = 'page' ) {

			$template_type = $type . '_templates';

			$templates_list = array();

			if ( ( null === self::$page_templates && 'page' === $type ) || ( null === self::$section_templates && 'section' === $type ) || ( null === self::$widget_templates && 'widget' === $type ) ) {

				$posts = get_posts(
					array(
						'post_type'      => 'elementor_library',
						'orderby'        => 'title',
						'order'          => 'ASC',
						'posts_per_page' => '-1',
						'tax_query'      => array(
							array(
								'taxonomy' => 'elementor_library_type',
								'field'    => 'slug',
								'terms'    => $type,
							),
						),
					)
				);

				foreach ( $posts as $post ) {

					$templates_list[] = array(
						'id'   => $post->ID,
						'name' => $post->post_title,
					);
				}

				self::${$template_type}[-1] = __( 'Select', 'stfe' );

				if ( count( $templates_list ) ) {
					foreach ( $templates_list as $saved_row ) {

						$content_id                            = $saved_row['id'];
						$content_id                            = apply_filters( 'wpml_object_id', $content_id );
						self::${$template_type}[ $content_id ] = $saved_row['name'];

					}
				} else {
					self::${$template_type}['no_template'] = __( 'It seems that, you have not saved any template yet.', 'stfe' );
				}
			}

			return self::${$template_type};
		}

		/**
		 * Return the new icon name.
		 *
		 * @since 1.16.1
		 *
		 * @param string $control_name name of the control.
		 * @return string of the updated control name.
		 */
		public static function get_new_icon_name( $control_name ) {
			if ( class_exists( 'Elementor\Icons_Manager' ) ) {
				return 'new_' . $control_name . '[value]';
			} else {
				return $control_name;
			}
		}

		/**
		 * Check if the Elementor is updated.
		 *
		 * @since 1.16.1
		 *
		 * @return boolean if Elementor updated.
		 */
		public static function is_elementor_updated() {
			if ( class_exists( 'Elementor\Icons_Manager' ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Social account share count.
		 *
		 * @since 1.30.0
		 *
		 * @return response.
		 * @param string $response response.
		 * @param string $args arguments.
		 */
		public static function get_social_share_count( $response, $args ) {

			$response = wp_remote_get( $response, $args );

			if ( is_wp_error( $response ) ) {

				return false;
			}

			$response = wp_remote_retrieve_body( $response );

			return $response;

		}
		/**
		 * Provide Integrations settings array().
		 *
		 * @param string $name Module slug.
		 * @return array()
		 * @since 0.0.1
		 */
		public static function get_integrations_options( $name = '' ) {

			$integrations_default = array(
				'google_api'             => '',
				'developer_mode'         => false,
				'language'               => '',
				'google_places_api'      => '',
				'yelp_api'               => '',
				'recaptcha_v3_key'       => '',
				'recaptcha_v3_secretkey' => '',
				'recaptcha_v3_score'     => '0.5',
				'google_client_id'       => '',
				'facebook_app_id'        => '',
				'facebook_app_secret'    => '',
			);

			$avaliable_widgets = soft_template_core()->settings->get( 'softemplate_available_widgets' );

			$integrations = self::get_admin_settings_option( '_uael_integration', array(), true );
			$integrations = wp_parse_args( $integrations, $integrations_default );
			$integrations = apply_filters( 'uael_integration_options', $integrations );

			if ( '' !== $name && isset( $integrations[ $name ] ) && '' !== $integrations[ $name ] ) {
				return $integrations[ $name ];
			} else {
				return $integrations;
			}
		}

		/**
		 * Post Demo data().
		 *
		 * @param string $name Module slug.
		 * @return array()
		 * @since 0.0.1
		 */
		public static function get_demo_post_data() {
			$post_data = [];
	
			if ( ! isset( $GLOBALS['post'] ) ) {
				return $post_data;
			}
	
			$preview_post_ID = '';
	
			if ( $GLOBALS['post']->post_type === 'soft-template-core' ) {
				$ae_post_ID      = $GLOBALS['post']->ID;
	
				$preview_post_ID = get_post_meta( $ae_post_ID, 'preview_post_id', true );
	
				if ( $preview_post_ID !== '' && $preview_post_ID != 0 ) :
					$post_data = get_post( $preview_post_ID );
				else :
					$args      = [
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'posts_per_page' => 1,
					];
					$demo_data = get_posts( $args );
					$post_data = $demo_data[0];
				endif;
			} elseif ( $GLOBALS['post']->post_type === 'elementor_library' && class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
				$document = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_document( $GLOBALS['post']->ID );
				if ( $document ) {
					$preview_id = $document->get_settings( 'preview_id' );
	
					if ( empty( $preview_id ) ) {
						$post_data = get_post( 0 );
						return $post_data;
					}
					$post_data = get_post( $preview_id );
				}
			} else {
				$post_data = $GLOBALS['post'];
			}
	
			if ( empty( $post_data ) ) {
				$post_data = get_post( 0 );
			}
	
			return $post_data;
	
		}

		public static function get_the_archive_title() {
			if ( is_category() ) {
				/* translators: Category archive title. 1: Category name */
				$title = single_cat_title( '', false );
			} elseif ( is_tag() ) {
				/* translators: Tag archive title. 1: Tag name */
				$title = single_tag_title( '', false );
			} elseif ( is_author() ) {
				/* translators: Author archive title. 1: Author name */
				$title = get_the_author();
			} elseif ( is_year() ) {
				/* translators: Yearly archive title. 1: Year */
				$title = get_the_date( _x( 'Y', 'yearly archives date format' ) );
			} elseif ( is_month() ) {
				/* translators: Monthly archive title. 1: Month name and year */
				$title = get_the_date( _x( 'F Y', 'monthly archives date format' ) );
			} elseif ( is_day() ) {
				/* translators: Daily archive title. 1: Date */
				$title = get_the_date( _x( 'F j, Y', 'daily archives date format' ) );
			} elseif ( is_tax( 'post_format' ) ) {
				if ( is_tax( 'post_format', 'post-format-aside' ) ) {
					$title = _x( 'Asides', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
					$title = _x( 'Galleries', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
					$title = _x( 'Images', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
					$title = _x( 'Videos', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
					$title = _x( 'Quotes', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
					$title = _x( 'Links', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
					$title = _x( 'Statuses', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
					$title = _x( 'Audio', 'post format archive title' );
				} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
					$title = _x( 'Chats', 'post format archive title' );
				}
			} elseif ( is_post_type_archive() ) {
				/* translators: Post type archive title. 1: Post type name */
				$title = post_type_archive_title( '', false );
			} elseif ( is_tax() ) {
				$tax = get_taxonomy( get_queried_object()->taxonomy );
				/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
				$title = single_term_title( '', false );
			} else {
				$title = __( 'Archives' );
			}
	
			/**
			 * Filters the archive title.
			 *
			 * @since 4.1.0
			 *
			 * @param string $title Archive title to be displayed.
			 */
			return apply_filters( 'get_the_archive_title', $title );
		}

		public static function get_the_archive_description( $term = 0, $taxonomy = 'post_tag' ) {
			return term_description( $term, $taxonomy );
		}

		public static function trim_letters( $string, $start, $length, $append, $html_entity_decode = true, $more = array() ){

			if ( $html_entity_decode ){
				//Convert HTML entities to their corresponding characters
				$string = html_entity_decode($string);
			}
			//Get truncated string with specified width		
			return mb_strimwidth( $string, $start, $length, $append );
	
		}

		public static function get_rule_post_types( $output = 'object' ) {
			$final_post_types = [];
			$all_post_types   = get_post_types( [ 'public' => true ], $output );
	
			$skip_post_types = [
				'attachment',
				'soft-template-core',
				'elementor_library',
				'e-landing-page',
				'jkit-header',
				'jkit-footer',
			];
	
			if ( $output === 'names' ) {
				return array_diff( $all_post_types, $skip_post_types );
			}
	
			foreach ( $all_post_types as $name => $post_type ) {
				if ( ! in_array( $name, $skip_post_types, true ) ) {
					$final_post_types[ $name ] = $post_type->label;
				}
			}
	
			return $final_post_types;
		}

		public static function get_custom_taxonomies() {
			$args = [
				'public'   => true,
				'_builtin' => false,
	
			];
			$tax_array  = [ '' => 'Select' ];
			$taxonomies = get_taxonomies( $args, 'objects' );
			if ( is_array( $taxonomies ) && count( $taxonomies ) ) {
				foreach ( $taxonomies as $slug => $taxonomy ) {
					$tax_array[ $slug ] = $taxonomy->labels->name;
				}
			}
			return $tax_array;
		}
	
		public static function get_rules_taxonomies() {
			$args = [
				'public' => true,
	
			];
			$tax_array  = [];
			$taxonomies = get_taxonomies( $args, 'objects' );
			if ( is_array( $taxonomies ) && count( $taxonomies ) ) {
				foreach ( $taxonomies as $slug => $taxonomy ) {
					$tax_array[ $slug ] = $taxonomy->labels->name;
				}
			}
			return $tax_array;
		}

		public static function get_authors() {
			$args = array(
				'has_published_posts' => true,
				'fields'              => [
					'ID',
					'display_name',
				],
			);
			 
			// Capability queries were only introduced in WP 5.9.
			if ( version_compare( $GLOBALS['wp_version'], '5.9', '<' ) ) {
				$args['who'] = 'authors';
				unset( $args['capability'] );
			}
			 
			$authors = get_users( $args );
	
			$authors = [];
	
			foreach ( get_users( $args ) as $result ) {
				$authors[ $result->ID ] = $result->display_name;
			}
	
			return $authors;
		}

		public static function get_taxonomy_terms( $taxonomy ) {

			$tax_array = [];
			$terms     = get_terms(
				[
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				]
			);
	
			if ( count( $terms ) ) {
				foreach ( $terms as $term ) {
					$tax_array[ $term->term_id ] = $term->name;
				}
			}
	
			return $tax_array;
		}
		public static function get_all_taxonomies() {
			$ae_taxonomy_filter_args = [
				'show_in_nav_menus' => true,
			];
	
			return get_taxonomies( $ae_taxonomy_filter_args, 'objects' );
		}
	
		public static function get_taxonomies_by_post_type( $post_type ) {
			$tax_array  = [];
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			if ( isset( $taxonomies ) && count( $taxonomies ) ) {
				foreach ( $taxonomies as $tax ) {
					$tax_array[ $tax->name ] = $tax->label;
				}
			}
			return $tax_array;
		}

		public static function get_preview_term_data() {
			$term_data = [
				'prev_term_id' => '',
				'taxonomy'     => '',
			];

			global $posts;

			if( isset($GLOBALS['post']) ) {
				$post_type = $GLOBALS['post']->post_type;
				$ae_template_id   = $GLOBALS['post']->ID;
			} else {
				$post_type = get_post_type( $_GET['post'] );
				$ae_template_id  = $_GET['post'];
			}
			
			if ( $post_type === 'soft-template-core' ) {
						
				//$verbosed = soft_template_core()->conditions->post_conditions_terms( $ae_template_id );

				$template_meta = get_post_meta( $ae_template_id, '_elementor_page_settings', true );
						
				if ( ! empty( $template_meta['conditions_archive-category_cats'] ) && !empty( $template_meta["conditions_sub_archive"]  ) ) {
					$term_data['prev_term_id'] = $template_meta['conditions_archive-category_cats'];
					$texonomy_data = explode("-",$template_meta["conditions_sub_archive"]);
					$term_data['taxonomy']     = $texonomy_data[1];
				} elseif( !empty($template_meta['conditions_sub_archive']) && $template_meta['conditions_sub_archive'] == 'archive-all' ) {
					$term_data['prev_term_id'] = array();
					$term_data['taxonomy'] = array();
				} else {
					$term_data['prev_term_id'] = array();
					$term_data['taxonomy'] = array();
				}
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$queried_object            = get_queried_object();
				$term_data['prev_term_id'] = $queried_object->term_id;
				$term_data['taxonomy']     = $queried_object->taxonomy;
			}
	
			return $term_data;
		}
	
		public static function get_preview_author_data() {
			$author_data = [
				'prev_author_id' => '',
			];
			if ( $GLOBALS['post']->post_type === 'soft-template-core' ) {
				$ae_template_id                = $GLOBALS['post']->ID;
				$author_data['prev_author_id'] = get_post_meta( $ae_template_id, 'ae_preview_author', true );
			} else {
				if ( is_author() ) {
					$author                        = get_queried_object();
					$author_data['prev_author_id'] = $author->ID;
				}
			}
	
			return $author_data;
		}

		public static function postexcerpt( $length = 30 ) {
            global $post;

            // Check for custom excerpt
            if ( has_excerpt( $post->ID ) ) {
                $output = $post->post_excerpt;
            }

            // No custom excerpt
            else {
                // Check for more tag and return content if it exists
                if ( strpos( $post->post_content, '<!--more-->' ) ) {
                    $output = apply_filters( 'the_content', get_the_content() );
                } elseif ( strpos( $post->post_content, '<!--nextpage-->' ) ) {
                    $output = apply_filters( 'the_content', get_the_content() );
                } else {
                    $output = wp_trim_words( strip_shortcodes( $post->post_content ), $length );
                }

            }
            return $output;
        }

		public static function get_featured_image_html( $settings, $image_size_key = 'image', $post_id = null ) {
			if ( ! isset( $settings[ $image_size_key . '_size' ] ) ) {
				$settings[ $image_size_key . '_size' ] = '';
			}
	
			$size = $settings[ $image_size_key . '_size' ];
	
			$image_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';
	
			$html = '';
	
			// If is the new version - with image size.
			$image_sizes = get_intermediate_image_sizes();
	
			$image_sizes[] = 'full';

			$is_static_render_mode = Elementor\Plugin::$instance->frontend->is_static_render_mode();
	
			// On static mode don't use WP responsive images.
			if ( ! empty( $post_id ) && in_array( $size, $image_sizes ) && ! $is_static_render_mode ) {
				$image_class .= " attachment-$size size-$size";
				$image_attr = [
					'class' => trim( $image_class ),
				];
	
				$html .= wp_get_attachment_image( $post_id, $size, false, $image_attr );
			} 
	
			return apply_filters( 'soft-template-core/image_size/get_attachment_image_html', $html, $settings, $image_size_key, $post_id );
		}
	}
}
