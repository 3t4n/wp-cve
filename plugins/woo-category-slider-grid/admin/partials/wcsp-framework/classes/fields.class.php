<?php
/**
 * Framework fields.class file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/framework
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Fields' ) ) {
	/**
	 *
	 * Fields Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	abstract class SP_WCS_Fields extends SP_WCS_Abstract {

		/**
		 * Field
		 *
		 * @var array
		 */
		public $field = array();

		/**
		 * Value
		 *
		 * @var string
		 */
		public $value = '';

		/**
		 * Unique
		 *
		 * @var string
		 */
		public $unique = '';

		/**
		 * Where
		 *
		 * @var string
		 */
		public $where = '';

		/**
		 * Parent
		 *
		 * @var string
		 */
		public $parent = '';

		/**
		 * Field class constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {
			$this->field  = $field;
			$this->value  = $value;
			$this->unique = $unique;
			$this->where  = $where;
			$this->parent = $parent;
		}

		/**
		 * Field name method.
		 *
		 * @param string $nested_name Nested field name.
		 * @return string
		 */
		public function field_name( $nested_name = '' ) {

			$field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
			$unique_id  = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $field_id . ']' : $field_id;
			$field_name = ( ! empty( $this->field['name'] ) ) ? $this->field['name'] : $unique_id;
			$tag_prefix = ( ! empty( $this->field['tag_prefix'] ) ) ? $this->field['tag_prefix'] : '';

			if ( ! empty( $tag_prefix ) ) {
				$nested_name = str_replace( '[', '[' . $tag_prefix, $nested_name );
			}

			return $field_name . $nested_name;

		}

		/**
		 * Field attributes.
		 *
		 * @param array $custom_atts Custom attributes.
		 * @return mixed
		 */
		public function field_attributes( $custom_atts = array() ) {

			$field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
			$attributes = ( ! empty( $this->field['attributes'] ) ) ? $this->field['attributes'] : array();

			if ( ! empty( $field_id ) ) {
				$attributes['data-depend-id'] = $field_id;
			}

			if ( ! empty( $this->field['placeholder'] ) ) {
				$attributes['placeholder'] = $this->field['placeholder'];
			}

			$attributes = wp_parse_args( $attributes, $custom_atts );

			$atts = '';

			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $key => $value ) {
					if ( 'only-key' === $value ) {
						$atts .= ' ' . $key;
					} else {
						$atts .= ' ' . $key . '="' . $value . '"';
					}
				}
			}

			return $atts;

		}

		/**
		 * Field before.
		 *
		 * @return mixed
		 */
		public function field_before() {
			return ( ! empty( $this->field['before'] ) ) ? $this->field['before'] : '';
		}

		/**
		 * Field after.
		 *
		 * @return mixed
		 */
		public function field_after() {

			$output  = ( ! empty( $this->field['desc'] ) ) ? '<p class="spf-text-desc">' . $this->field['desc'] . '</p>' : '';
			$output .= ( ! empty( $this->field['after'] ) ) ? $this->field['after'] : '';
			$output .= ( ! empty( $this->field['help'] ) ) ? '<span class="spf-help"><span class="spf-help-text">' . $this->field['help'] . '</span><span class="fa fa-question-circle"></span></span>' : '';
			$output .= ( ! empty( $this->field['_error'] ) ) ? '<p class="spf-text-error">' . $this->field['_error'] . '</p>' : '';

			return $output;

		}

		/**
		 * Field Data.
		 *
		 * @param array $type post types.
		 *
		 * @return statement
		 */
		public function field_data( $type = '' ) {

			$options    = array();
			$query_args = ( ! empty( $this->field['query_args'] ) ) ? $this->field['query_args'] : array();

			switch ( $type ) {

				case 'sp_wcsp_categories':
				case 'sp_wcsp_category':
					$taxonomies = ( isset( $query_args['taxonomies'] ) ) ? $query_args['taxonomies'] : 'product_cat';
					$cat_args   = array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => 0,
						'parent'     => 0,
					);
					$tags       = get_terms( $taxonomies, $cat_args );

					if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
						foreach ( $tags as $tag ) {
							$options[ $tag->term_id ] = $tag->name . ' (' . $tag->count . ')';
						}
					}

					break;
				case 'sp_wcslider':
					$lcp_get_specific = array(
						'post_type' => 'sp_wcslider',
					);
					$query_args       = array_merge( $query_args, $lcp_get_specific );
					$all_posts        = get_posts( $query_args );

					if ( ! is_wp_error( $all_posts ) && ! empty( $all_posts ) ) {
						foreach ( $all_posts as $post_obj ) {
							$options[ $post_obj->ID ] = isset( $post_obj->post_title ) && ! empty( $post_obj->post_title ) ? $post_obj->post_title : 'Untitled';
						}
					}
					break;
				case 'page':
				case 'pages':
					$pages = get_pages( $query_args );

					if ( ! is_wp_error( $pages ) && ! empty( $pages ) ) {
						foreach ( $pages as $page ) {
							$options[ $page->ID ] = $page->post_title;
						}
					}

					break;

				case 'post':
				case 'posts':
					$posts = get_posts( $query_args );

					if ( ! is_wp_error( $posts ) && ! empty( $posts ) ) {
						foreach ( $posts as $post ) {
							$options[ $post->ID ] = $post->post_title;
						}
					}

					break;

				case 'taxonomies':
				case 'taxonomy':
					global $post;
					$saved_meta = get_post_meta( $post->ID, 'sp_wcsp_upload_options', true );
					if ( isset( $saved_meta['wpsp_post_type'] ) && ! empty( $saved_meta['wpsp_post_type'] ) ) {
						$taxonomy_names = get_object_taxonomies( $saved_meta['wpsp_post_type'], 'names' );
						if ( ! is_wp_error( $taxonomy_names ) && ! empty( $taxonomy_names ) ) {
							foreach ( $taxonomy_names as $taxonomy => $label ) {
								$options[ $label ] = $label;
							}
						}
					} else {
						$post_types       = get_post_types( array( 'public' => true ) );
						$post_type_list   = array();
						$post_type_number = 1;
						foreach ( $post_types as $post_type => $label ) {
							$post_type_list[ $post_type_number++ ] = $label;
						}
						$taxonomy_names = get_object_taxonomies( $post_type_list['1'], 'names' );
						foreach ( $taxonomy_names as $taxonomy => $label ) {
							$options[ $label ] = $label;
						}
					}

					break;

				case 'terms':
				case 'term':
					global $post;
					$saved_meta = get_post_meta( $post->ID, 'sp_wcsp_upload_options', true );
					if ( is_object( isset( $saved_meta['wpsp_post_taxonomy'] ) ) && '' !== $saved_meta['wpsp_post_taxonomy'] ) {
						$terms = get_terms( $saved_meta['wpsp_post_taxonomy'] );
						foreach ( $terms as $key => $value ) {
							$options[ $value->term_id ] = $value->name;
						}
					} else {
						$post_types       = get_post_types( array( 'public' => true ) );
						$post_type_list   = array();
						$post_type_number = 1;
						foreach ( $post_types as $post_type => $label ) {
							$post_type_list[ $post_type_number++ ] = $label;
						}
						$taxonomy_names  = get_object_taxonomies( $post_type_list['1'], 'names' );
						$taxonomy_number = 1;
						foreach ( $taxonomy_names as $taxonomy => $label ) {
							$taxonomy_terms[ $taxonomy_number++ ] = $label;
						}
						$terms = get_terms( $taxonomy_terms['1'] );
						foreach ( $terms as $key => $value ) {
							$options[ $value->term_id ] = $value->name;
						}
					}

					break;

				case 'category':
				case 'categories':
					$categories = get_categories( $query_args );

					if ( ! is_wp_error( $categories ) && ! empty( $categories ) && ! isset( $categories['errors'] ) ) {
						foreach ( $categories as $category ) {
							$options[ $category->term_id ] = $category->name;
						}
					}

					break;

				case 'tag':
				case 'tags':
					$taxonomies = ( isset( $query_args['taxonomies'] ) ) ? $query_args['taxonomies'] : 'post_tag';
					$tags       = get_terms( $taxonomies, $query_args );

					if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
						foreach ( $tags as $tag ) {
							$options[ $tag->term_id ] = $tag->name;
						}
					}

					break;

				case 'menu':
				case 'menus':
					$menus = wp_get_nav_menus( $query_args );

					if ( ! is_wp_error( $menus ) && ! empty( $menus ) ) {
						foreach ( $menus as $menu ) {
							$options[ $menu->term_id ] = $menu->name;
						}
					}

					break;

				case 'post_type':
				case 'post_types':
					$post_types = get_post_types(
						array(
							'show_in_nav_menus' => true,
						)
					);

					if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
						foreach ( $post_types as $post_type ) {
							$options[ $post_type ] = ucfirst( $post_type );
						}
					}

					break;

				case 'sidebar':
				case 'sidebars':
					global $wp_registered_sidebars;

					if ( ! empty( $wp_registered_sidebars ) ) {
						foreach ( $wp_registered_sidebars as $sidebar ) {
							$options[ $sidebar['id'] ] = $sidebar['name'];
						}
					}

					break;

				case 'role':
				case 'roles':
					global $wp_roles;

					if ( is_object( $wp_roles ) ) {
						$roles = $wp_roles->get_names();
						if ( ! empty( $wp_roles ) ) {
							foreach ( $roles as $key => $value ) {
								$options[ $key ] = $value;
							}
						}
					}

					break;

				default:
					if ( function_exists( $type ) ) {
						$options = call_user_func( $type, $this->value, $this->field );
					}
					break;

			}

			return $options;

		}

	}
}
