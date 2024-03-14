<?php
/**
 * helper class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'LaStudio_Kit_Helper' ) ) {

	/**
	 * Define LaStudio_Kit_Helper class
	 */
	class LaStudio_Kit_Helper {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   LaStudio_Kit_Helper
		 */
		private static $instance = null;

		public static $data_caches = [];

        /**
         * Returns columns classes string
         *
         * @param  array $columns Columns classes array
         * @return string
         */
		public function render_grid_classes( $columns = [] ){

            $columns = wp_parse_args( $columns, array(
                'desktop'  => '1',
                'laptop'   => '',
                'tablet'   => '',
                'mobile'  => '',
                'xmobile'   => ''
            ) );

            $replaces = array(
                'xmobile' => 'xmobile-block-grid',
                'mobile' => 'mobile-block-grid',
                'tablet' => 'tablet-block-grid',
                'laptop' => 'laptop-block-grid',
                'desktop' => 'block-grid'
            );

            $classes = array();

            foreach ( $columns as $device => $cols ) {
                if ( ! empty( $cols ) ) {
                    $classes[] = sprintf( '%1$s-%2$s', $replaces[$device], $cols );
                }
            }

            return implode( ' ' , $classes );

        }

		/**
		 * Returns columns classes string
		 *
		 * @param  array $columns Columns classes array
		 * @return string
		 */
		public function col_classes( $columns = array() ) {

		    $bk_columns = $columns;

			$columns = wp_parse_args( $columns, array(
				'desk' => 1,
				'tab'  => 1,
				'mob'  => 1,
			));

			$classes = array();

			foreach ( $columns as $device => $cols ) {
				if ( ! empty( $cols ) ) {
					$classes[] = sprintf( 'col-%1$s-%2$s', $device, $cols );
				}
			}

			return implode( ' ' , $classes );
		}

		/**
		 * Returns disable columns gap nad rows gap classes string
		 *
		 * @param  string $use_cols_gap [description]
		 * @param  string $use_rows_gap [description]
		 * @return string
		 */
		public function gap_classes( $use_cols_gap = 'yes', $use_rows_gap = 'yes' ) {

			$result = array();

			foreach ( array( 'cols' => $use_cols_gap, 'rows' => $use_rows_gap ) as $element => $value ) {
				if ( 'yes' !== $value ) {
					$result[] = sprintf( 'disable-%s-gap', $element );
				}
			}

			return implode( ' ', $result );

		}

		/**
		 * Returns image size array in slug => name format
		 *
		 * @return  array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			return $test = array_merge( array( 'full' => esc_html__( 'Full', 'lastudio-kit' ), ), $result );
		}

		/**
		 * Get categories list.
		 *
		 * @return array
		 */
		public function get_categories() {

			$categories = get_categories();

			if ( empty( $categories ) || ! is_array( $categories ) ) {
				return array();
			}

			return wp_list_pluck( $categories, 'name', 'term_id' );

		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function orderby_arr() {
			return array(
				'none'          => esc_html__( 'None', 'lastudio-kit' ),
				'ID'            => esc_html__( 'ID', 'lastudio-kit' ),
				'author'        => esc_html__( 'Author', 'lastudio-kit' ),
				'title'         => esc_html__( 'Title', 'lastudio-kit' ),
				'name'          => esc_html__( 'Name (slug)', 'lastudio-kit' ),
				'date'          => esc_html__( 'Date', 'lastudio-kit' ),
				'modified'      => esc_html__( 'Modified', 'lastudio-kit' ),
				'rand'          => esc_html__( 'Rand', 'lastudio-kit' ),
				'comment_count' => esc_html__( 'Comment Count', 'lastudio-kit' ),
				'menu_order'    => esc_html__( 'Menu Order', 'lastudio-kit' ),
			);
		}

		/**
		 * Returns allowed order fields for options
		 *
		 * @return array
		 */
		public function order_arr() {

			return array(
				'desc' => esc_html__( 'Descending', 'lastudio-kit' ),
				'asc'  => esc_html__( 'Ascending', 'lastudio-kit' ),
			);

		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function verrtical_align_attr() {
			return array(
				'baseline'    => esc_html__( 'Baseline', 'lastudio-kit' ),
				'top'         => esc_html__( 'Top', 'lastudio-kit' ),
				'middle'      => esc_html__( 'Middle', 'lastudio-kit' ),
				'bottom'      => esc_html__( 'Bottom', 'lastudio-kit' ),
				'sub'         => esc_html__( 'Sub', 'lastudio-kit' ),
				'super'       => esc_html__( 'Super', 'lastudio-kit' ),
				'text-top'    => esc_html__( 'Text Top', 'lastudio-kit' ),
				'text-bottom' => esc_html__( 'Text Bottom', 'lastudio-kit' ),
			);
		}

		/**
		 * Returns array with numbers in $index => $name format for numeric selects
		 *
		 * @param  integer $to Max numbers
		 * @return array
		 */
		public function get_select_range( $to = 10 ) {
			$range = range( 1, $to );
			return array_combine( $range, $range );
		}

		/**
		 * Returns badge placeholder URL
		 *
		 * @return void
		 */
		public function get_badge_placeholder() {
			return lastudio_kit()->plugin_url( 'assets/images/placeholder-badge.svg' );
		}

		/**
		 * Rturns image tag or raw SVG
		 *
		 * @param  string $url  image URL.
		 * @param  array  $attr [description]
		 * @return string
		 */
		public function get_image_by_url( $url = null, $attr = array(), $wrapper = true ) {

			$url = esc_url( $url );

			if ( empty( $url ) ) {
				return;
			}

			$ext  = pathinfo( $url, PATHINFO_EXTENSION );
			$attr = array_merge( array( 'alt' => '' ), $attr );

			if ( 'svg' !== $ext ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			$base_url = site_url( '/' );
			$svg_path = str_replace( $base_url, ABSPATH, $url );
			$key      = md5( $svg_path );
			$svg      = get_transient( $key );

			if ( ! $svg ) {
				$svg = file_get_contents( $svg_path );
			}

			if ( ! $svg ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			set_transient( $key, $svg, DAY_IN_SECONDS );

			if ( ! $wrapper ) {
				return $svg;
			}

			unset( $attr['alt'] );

			return sprintf( '<div%2$s>%1$s</div>', $svg, $this->get_attr_string( $attr ) );
		}

		/**
		 * Return attributes string from attributes array.
		 *
		 * @param  array  $attr Attributes string.
		 * @return string
		 */
		public function get_attr_string( $attr = array() ) {

			if ( empty( $attr ) || ! is_array( $attr ) ) {
				return;
			}

			$result = '';

			foreach ( $attr as $key => $value ) {
				$result .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}

			return $result;
		}

		/**
		 * Returns carousel arrow
		 *
		 * @param  array $classes Arrow additional classes list.
		 * @return string
		 */
		public function get_carousel_arrow( $classes ) {

			$format = apply_filters( 'lastudio_kit/carousel/arrows_format', '<i class="%s lakit-arrow"></i>', $classes );

			return sprintf( $format, implode( ' ', $classes ) );
		}

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public static function get_post_types( $args = [] ) {

			if( empty($args) && !empty( self::$data_caches['post_type'] ) ){
				return self::$data_caches['post_type'];
			}

            $post_type_args = [
                'show_in_nav_menus' => true,
                'public' => true,
            ];

            if ( ! empty( $args['post_type'] ) ) {
                $post_type_args['name'] = $args['post_type'];
            }

            $post_type_args = apply_filters('lastudio-kit/post-types-list/args', $post_type_args, $args);

			$post_types = get_post_types( $post_type_args, 'objects' );

			$deprecated = apply_filters(
				'lastudio-kit/post-types-list/deprecated',
				array( 'attachment', 'elementor_library' )
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
			if(empty($args)){
				self::$data_caches['post_type'] = $result;
			}
			return $result;

		}

        /**
         * Returns all custom taxonomies
         *
         * @return [type] [description]
         */
        public static function get_taxonomies( $args = [], $output = 'names', $operator = 'and' ) {

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

        /**
         * [search_posts_by_type description]
         * @param  [type] $type  [description]
         * @param  [type] $query [description]
         * @param  array  $ids   [description]
         * @return [type]        [description]
         */
        public static function search_posts_by_type( $type, $query, $ids = array(), $excludes = array() ) {

            add_filter( 'posts_where', array( __CLASS__, 'force_search_by_title' ), 10, 2 );

            $posts = get_posts( array(
                'post_type'           => $type,
                'ignore_sticky_posts' => true,
                'posts_per_page'      => -1,
                'suppress_filters'    => false,
                's_title'             => $query,
                'include'             => $ids,
                'exclude'             => $excludes,
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

        /**
         * [search_terms_by_tax description]
         * @param  [type] $tax   [description]
         * @param  [type] $query [description]
         * @param  array  $ids   [description]
         * @return [type]        [description]
         */
        public static function search_terms_by_tax( $tax, $query, $ids = array() ) {

            $terms = get_terms( array(
                'taxonomy'   => $tax,
                'hide_empty' => false,
                'name__like' => $query,
                'include'    => $ids,
            ) );

            $result = array();


            if ( ! empty( $terms ) && !is_wp_error($terms) ) {
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
		 * Return available arrows list
		 * @return array
		 */
		public function get_available_title_html_tags() {

			return array(
				'h1'   => esc_html__( 'H1', 'lastudio-kit' ),
				'h2'   => esc_html__( 'H2', 'lastudio-kit' ),
				'h3'   => esc_html__( 'H3', 'lastudio-kit' ),
				'h4'   => esc_html__( 'H4', 'lastudio-kit' ),
				'h5'   => esc_html__( 'H5', 'lastudio-kit' ),
				'h6'   => esc_html__( 'H6', 'lastudio-kit' ),
				'div'  => esc_html__( 'div', 'lastudio-kit' ),
				'span' => esc_html__( 'span', 'lastudio-kit' ),
				'p'    => esc_html__( 'p', 'lastudio-kit' ),
			);

		}

		/**
		 * Get post taxonomies for options.
		 *
		 * @return array
		 */
		public function get_taxonomies_for_options( $exclude = [] ) {
			$args = array(
				'public' => true,
			);
			$taxonomies = get_taxonomies( $args, 'objects', 'and' );
			$return = wp_list_pluck( $taxonomies, 'label', 'name' );
            if( is_array($exclude) && count($exclude) > 0){
                $return = array_filter($return, function ( $key ) use ($exclude){
                    return !in_array( $key, $exclude );
                }, ARRAY_FILTER_USE_KEY);
            }
            return $return;
		}

		/**
		 * Get elementor templates list for options.
		 *
		 * @return array
		 */
		public function get_elementor_templates_options() {
			$templates = lastudio_kit()->elementor()->templates_manager->get_source( 'local' )->get_items();

			$options = array(
				'0' => '— ' . esc_html__( 'Select', 'lastudio-kit' ) . ' —',
			);

			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}

			return $options;
		}

		/**
		 * Is script debug.
		 *
		 * @return bool
		 */
		public function is_script_debug() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		}

		/**
		 * Is FA5 migration.
		 *
		 * @return bool
		 */
		public function is_fa5_migration() {

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) && Elementor\Icons_Manager::is_migration_allowed() ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if is valid timestamp
		 *
		 * @param  int|string $timestamp
		 * @return boolean
		 */
		public function is_valid_timestamp( $timestamp ) {
			return ( ( string ) ( int ) $timestamp === $timestamp ) && ( $timestamp <= PHP_INT_MAX ) && ( $timestamp >= ~PHP_INT_MAX );
		}

		public function validate_html_tag( $tag ) {
			$allowed_tags = array(
				'article',
				'aside',
				'div',
				'footer',
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'header',
				'main',
				'nav',
				'p',
				'section',
				'span',
			);
			return in_array( strtolower( $tag ), $allowed_tags ) ? $tag : 'div';
		}

		public function get_active_breakpoints( $reverse_key = false, $label_with_breakpoint = false ){
            $breakpoints = [];
			$active_breakpoints = lastudio_kit()->elementor()->breakpoints->get_active_breakpoints();
			foreach ($active_breakpoints as $k => $v){
				if($reverse_key){
					$breakpoints[$v->get_value()] = $label_with_breakpoint ? sprintf('%1$s(< %2$spx)', $v->get_label(), ($v->get_value() + 1)) : $k;
				}
				else{
					$breakpoints[$k] = $label_with_breakpoint ? sprintf('%1$s(< %2$spx)', $v->get_label(), ($v->get_value() + 1)) : $v->get_value();
				}
			}
            return $breakpoints;
        }

		public function get_attribute_with_all_breakpoints( $atts = '', $settings = [], $inherit = true, $only_device = '' ) {

		    $data = [];

            $config = $this->get_active_breakpoints();

		    if(!empty($atts) && !empty($settings)){
		        if(isset($settings[$atts])){
                    $data['desktop'] = $settings[$atts];
                }
		        if(!empty($config)){
		            foreach ($config as $k => $v){
		                if(isset($settings[$atts.'_' . $k])){
                            $data[$k] = $settings[$atts.'_' . $k];
                        }
                    }
                }
            }

		    if( $inherit && isset($config['laptop']) && empty($data['laptop']) && !empty($data['desktop'])){
                $data['laptop'] = $data['desktop'];
            }
            if( $inherit && empty($data['tablet']) && !empty($data['laptop'])){
                $data['tablet'] = $data['laptop'];
            }
		    if( $inherit && isset($config['tabletportrait']) && empty($data['tabletportrait']) && !empty($data['tablet'])){
                $data['tabletportrait'] = $data['tablet'];
            }
		    if( $inherit && isset($config['mobile_extra']) && empty($data['mobile_extra']) && !empty($data['tablet'])){
                $data['mobile_extra'] = $data['tablet'];
            }

		    if(!empty($only_device)){
		        if(isset($data[$only_device])){
		            return $data[$only_device];
                }
		        else{
		            return '';
                }
            }

            return $data;
        }

        public function col_new_classes( $atts = '', $settings = [] ){
            $layouts = $this->get_attribute_with_all_breakpoints($atts, $settings, true);
            $classes = [];
            $grid_mapping = [
                'desktop'       => 'desk',
                'laptop'        => 'lap',
                'tablet'        => 'tab',
                'mobile_extra'  => 'tabp',
                'tabletportrait'=> 'tabp',
                'mobile'        => 'mob',
            ];

            if(empty($layouts['mobile']) && empty($layouts['tabletportrait']) && empty($layouts['mobile_extra']) && empty($layouts['tablet']) && empty($layouts['laptop'])){
                $layouts['mobile'] = $layouts['desktop'];
                $layouts['desktop'] = '';
            }

            foreach ($layouts as $device => $value){
                if(empty($value)){
                    continue;
                }
                if(isset($grid_mapping[$device])){
                    if($device == 'mobile' && $value == 1){
                        continue;
                    }
                    $classes[] = 'col-' . $grid_mapping[$device] . '-' . $value;
                }
            }
            return join(' ', $classes);
        }

        public function get_blockgrid_cssclass( $atts = '', $settings = [] ){
		    $layouts = $this->get_attribute_with_all_breakpoints($atts, $settings);
		    $classes = [];
		    foreach ($layouts as $device => $value){
		        if(empty($value)){
		            continue;
                }
		        $tmp = 'lakit-blockgrid-' . $value;
		        if($device != 'desktop'){
                    $tmp = $device . '-' . $tmp;
                }
		        $classes[] = $tmp;
            }
		    return join(' ', $classes);
        }

        public static function get_css_by_responsive_columns( $columns, $css_selector ){
            if(empty($columns['desktop'])){
                return '';
            }
            $breakpoints = lastudio_kit_helper()->get_active_breakpoints();
            arsort($breakpoints);

            $css = [];
            $css[] = sprintf('%1$s{--e-c-col:%2$s}', $css_selector, $columns['desktop'] ?? 1);

            foreach ($breakpoints as $k => $vl){
                if( isset($columns[$k]) ){
                    $css[] = sprintf('@media(max-width: %3$spx){%1$s{--e-c-col:%2$s}}', $css_selector, $columns[$k], $vl);
                }
            }
            return join('', $css);
        }

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return LaStudio_Kit_Helper
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

        /**
         * Get breadcrumbs post taxonomy settings.
         *
         * @return array
         */
        public function get_breadcrumbs_post_taxonomy_settings() {

            static $results = array();

            if ( empty( $results ) ) {
                $post_types = get_post_types( array( 'public' => true ), 'objects' );

                if ( is_array( $post_types ) && ! empty( $post_types ) ) {

                    foreach ( $post_types as $post_type ) {
                        $value = lastudio_kit_settings()->get( 'breadcrumbs_taxonomy_' . $post_type->name, ( 'post' === $post_type->name ) ? 'category' : '' );

                        if ( ! empty( $value ) ) {
                            $results[ $post_type->name ] = $value;
                        }
                    }
                }
            }

            return $results;
        }

        public static function set_global_authordata() {
            global $authordata;
            if ( ! isset( $authordata->ID ) ) {
                $post = get_post();
                $authordata = get_userdata( $post->post_author ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            }
        }

        public function get_elementor_icon_from_widget_setting( $setting = null, $format = '%s', $icon_class = '', $echo = false ){
            $icon_html = '';

            $attr = array( 'aria-hidden' => 'true' );

            if ( ! empty( $icon_class ) ) {
                $attr['class'] = $icon_class;
            }

            if(!empty($setting)){
                ob_start();
                \Elementor\Icons_Manager::render_icon( $setting, $attr );
                $icon_html = ob_get_clean();
            }

            if ( empty( $icon_html ) ) {
                return '';
            }

            if ( ! $echo ) {
                return sprintf( $format, $icon_html );
            }

            printf( $format, $icon_html );

        }

        public static function get_polyfill_inline( $data = [] ) {
            $response_data = '';
            if(!empty($data)){
                foreach ($data as $handle => $polyfill){
                    if(!empty($polyfill['condition']) && !empty($polyfill['src'])){
                        $src = $polyfill['src'];
                        if ( ! empty( $polyfill['version'] ) ) {
                            $src = add_query_arg( 'ver', $polyfill['version'], $src );
                        }
                        $src = esc_url( apply_filters( 'script_loader_src', $src, $handle ) );
                        if ( ! $src ) {
                            continue;
                        }
                        $response_data .= (
                            // Test presence of feature...
                            '( ' . $polyfill['condition'] . ' ) || ' .
                            /*
                             * ...appending polyfill on any failures. Cautious viewers may balk
                             * at the `document.write`. Its caveat of synchronous mid-stream
                             * blocking write is exactly the behavior we need though.
                             */
                            'document.write( \'<script src="' . $src . '"></scr\' + \'ipt>\' );'
                        );
                    }
                }
            }
            return $response_data;
        }

        public function get_post_terms($post_id = null, $type = 'slug'){
            $post = get_post( $post_id );
            $classes = [];
            // All public taxonomies.
            $taxonomies = get_taxonomies( array( 'public' => true ) );
            foreach ( (array) $taxonomies as $taxonomy ) {
                if ( is_object_in_taxonomy( $post->post_type, $taxonomy ) ) {
                    foreach ( (array) get_the_terms( $post->ID, $taxonomy ) as $term ) {
                        if ( empty( $term->slug ) ) {
                            continue;
                        }
                        if($type == 'id'){
                            $classes[] = 'term-' . $term->term_id;
                        }
                        else{
                            $term_class = sanitize_html_class( $term->slug, $term->term_id );
                            if ( is_numeric( $term_class ) || ! trim( $term_class, '-' ) ) {
                                $term_class = $term->term_id;
                            }

                            // 'post_tag' uses the 'tag' prefix for backward compatibility.
                            if ( 'post_tag' === $taxonomy ) {
                                $classes[] = 'tag-' . $term_class;
                            } else {
                                $classes[] = sanitize_html_class( $taxonomy . '-' . $term_class, $taxonomy . '-' . $term->term_id );
                            }
                        }
                    }
                }
            }
            return $classes;
        }

        public static function get_excerpt( $length = 30 ){
	        global $post;

	        // Check for custom excerpt
	        if ( has_excerpt( $post->ID ) ) {
		        $output = wp_trim_words( strip_shortcodes( $post->post_excerpt ), $length );
	        }

	        // No custom excerpt
	        else {

		        // Check for more tag and return content if it exists
		        if ( strpos( $post->post_content, '<!--more-->' ) || strpos( $post->post_content, '<!--nextpage-->' ) ) {
			        $output = apply_filters( 'the_content', get_the_content() );
		        }

		        // No more tag defined
		        else {
			        $output = wp_trim_words( strip_shortcodes( $post->post_content ), $length );
		        }

	        }

	        return $output;
        }

        public static function get_the_archive_url() {
            $url = '';
            if ( is_category() || is_tag() || is_tax() ) {
                $url = get_term_link( get_queried_object() );
            } elseif ( is_author() ) {
                $url = get_author_posts_url( get_queried_object_id() );
            } elseif ( is_year() ) {
                $url = get_year_link( get_query_var( 'year' ) );
            } elseif ( is_month() ) {
                $url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
            } elseif ( is_day() ) {
                $url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
            } elseif ( is_post_type_archive() ) {
                $url = get_post_type_archive_link( get_post_type() );
            }

            return $url;
        }

        public static function get_page_title( $include_context = true ) {
            $title = '';

            if ( is_singular() ) {
                /* translators: %s: Search term. */
                $title = get_the_title();

                if ( $include_context ) {
                    $post_type_obj = get_post_type_object( get_post_type() );
                    $title = sprintf( '%s: %s', $post_type_obj->labels->singular_name, $title );
                }
            } elseif ( is_search() ) {
                /* translators: %s: Search term. */
                $title = sprintf( esc_html__( 'Search Results for: %s', 'lastudio-kit' ), get_search_query() );

                if ( get_query_var( 'paged' ) ) {
                    /* translators: %s is the page number. */
                    $title .= sprintf( esc_html__( '&nbsp;&ndash; Page %s', 'lastudio-kit' ), get_query_var( 'paged' ) );
                }
            } elseif ( is_category() ) {
                $title = single_cat_title( '', false );

                if ( $include_context ) {
                    /* translators: Category archive title. 1: Category name */
                    $title = sprintf( esc_html__( 'Category: %s', 'lastudio-kit' ), $title );
                }
            } elseif ( is_tag() ) {
                $title = single_tag_title( '', false );
                if ( $include_context ) {
                    /* translators: Tag archive title. 1: Tag name */
                    $title = sprintf( esc_html__( 'Tag: %s', 'lastudio-kit' ), $title );
                }
            } elseif ( is_author() ) {
                $title = '<span class="vcard">' . get_the_author() . '</span>';

                if ( $include_context ) {
                    /* translators: Author archive title. 1: Author name */
                    $title = sprintf( esc_html__( 'Author: %s', 'lastudio-kit' ), $title );
                }
            } elseif ( is_year() ) {
                $title = get_the_date( _x( 'Y', 'yearly archives date format', 'lastudio-kit' ) );

                if ( $include_context ) {
                    /* translators: Yearly archive title. 1: Year */
                    $title = sprintf( esc_html__( 'Year: %s', 'lastudio-kit' ), $title );
                }
            } elseif ( is_month() ) {
                $title = get_the_date( _x( 'F Y', 'monthly archives date format', 'lastudio-kit' ) );

                if ( $include_context ) {
                    /* translators: Monthly archive title. 1: Month name and year */
                    $title = sprintf( esc_html__( 'Month: %s', 'lastudio-kit' ), $title );
                }
            } elseif ( is_day() ) {
                $title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'lastudio-kit' ) );

                if ( $include_context ) {
                    /* translators: Daily archive title. 1: Date */
                    $title = sprintf( esc_html__( 'Day: %s', 'lastudio-kit' ), $title );
                }
            } elseif ( is_tax( 'post_format' ) ) {
                if ( is_tax( 'post_format', 'post-format-aside' ) ) {
                    $title = _x( 'Asides', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
                    $title = _x( 'Galleries', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
                    $title = _x( 'Images', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
                    $title = _x( 'Videos', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
                    $title = _x( 'Quotes', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
                    $title = _x( 'Links', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
                    $title = _x( 'Statuses', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
                    $title = _x( 'Audio', 'post format archive title', 'lastudio-kit' );
                } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
                    $title = _x( 'Chats', 'post format archive title', 'lastudio-kit' );
                }
            } elseif ( is_post_type_archive() ) {
                $title = post_type_archive_title( '', false );

                if ( $include_context ) {
                    /* translators: Post type archive title. 1: Post type name */
                    $title = sprintf( esc_html__( 'Archives: %s', 'lastudio-kit' ), $title );
                }
            } elseif ( is_tax() ) {
                $title = single_term_title( '', false );

                if ( $include_context ) {
                    $tax = get_taxonomy( get_queried_object()->taxonomy );
                    /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
                    $title = sprintf( esc_html__( '%1$s: %2$s', 'lastudio-kit' ), $tax->labels->singular_name, $title );
                }
            } elseif ( is_archive() ) {
                $title = esc_html__( 'Archives', 'lastudio-kit' );
            } elseif ( is_404() ) {
                $title = esc_html__( 'Page Not Found', 'lastudio-kit' );
            } // End if().

            /**
             * The archive title.
             *
             * Filters the archive title.
             *
             * @since 1.0.0
             *
             * @param string $title Archive title to be displayed.
             */
            $title = apply_filters( 'elementor/utils/get_the_archive_title', $title );

            return $title;
        }

        /**
         * Remove words from a sentence.
         *
         * @param string  $text
         * @param integer $length
         *
         * @return string
         */
        public static function trim_words( $text, $length ) {
            if ( $length && str_word_count( $text ) > $length ) {
                $text = explode( ' ', $text, $length + 1 );
                unset( $text[ $length ] );
                $text = implode( ' ', $text );
            }

            return $text;
        }

		public static function number_format_short( $n, $precision = 1 ){
			if ($n < 900) {
				// 0 - 900
				$n_format = number_format($n, $precision);
				$suffix = '';
			} elseif ($n < 900000) {
				// 0.9k-850k
				$n_format = number_format($n * 0.001, $precision);
				$suffix = 'K';
			} elseif ($n < 900000000) {
				// 0.9m-850m
				$n_format = number_format($n * 0.000001, $precision);
				$suffix = 'M';
			} elseif ($n < 900000000000) {
				// 0.9b-850b
				$n_format = number_format($n * 0.000000001, $precision);
				$suffix = 'B';
			} else {
				// 0.9t+
				$n_format = number_format($n * 0.000000000001, $precision);
				$suffix = 'T';
			}
			// Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
			// Intentionally does not affect partials, eg "1.50" -> "1.50"
			if ($precision > 0) {
				$dotzero = '.' . str_repeat('0', $precision);
				$n_format = str_replace($dotzero, '', $n_format);
			}
			return $n_format . $suffix;
		}

		public static function validate_date( $date = '', $format = 'Y-m-d'){
			if(empty($date)){
				return false;
			}
			$d = DateTime::createFromFormat($format, $date);
			return $d && $d->format($format) === $date;
		}

		public static function transfer_text( $content, $autop = false ){
			if ( $autop ) {
				$content = preg_replace( '/<\/?p\>/', "\n", $content );
				$content = preg_replace( '/<p[^>]*><\\/p[^>]*>/', "", $content );
				$content = wpautop( $content . "\n" );
			}
			return do_shortcode( shortcode_unautop( $content ) );
		}

		public function print_elementor_template( $template_id ){

		}

		public static function get_blend_mode_options() {
			return [
				'' => esc_html__( 'Normal', 'elementor' ),
				'multiply' => esc_html__( 'Multiply', 'elementor' ),
				'screen' => esc_html__( 'Screen', 'elementor' ),
				'overlay' => esc_html__( 'Overlay', 'elementor' ),
				'soft-light' => esc_html__( 'Soft Light', 'elementor' ),
				'darken' => esc_html__( 'Darken', 'elementor' ),
				'lighten' => esc_html__( 'Lighten', 'elementor' ),
				'color-dodge' => esc_html__( 'Color Dodge', 'elementor' ),
				'color-burn' => esc_html__( 'Color Burn', 'elementor' ),
				'saturation' => esc_html__( 'Saturation', 'elementor' ),
				'color' => esc_html__( 'Color', 'elementor' ),
				'difference' => esc_html__( 'Difference', 'elementor' ),
				'exclusion' => esc_html__( 'Exclusion', 'elementor' ),
				'hue' => esc_html__( 'Hue', 'elementor' ),
				'luminosity' => esc_html__( 'Luminosity', 'elementor' ),
			];
		}

        public static function minify_css( $css ){
            if(empty($css)){
                return $css;
            }
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            $css = preg_replace('/\s*([{}|:;,+~*>!])\s+/', '$1', $css);
            $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '',$css);
            return $css;
        }

	}

}

/**
 * Returns instance of LaStudio_Kit_Helper
 *
 * @return LaStudio_Kit_Helper
 */
function lastudio_kit_helper() {
	return LaStudio_Kit_Helper::get_instance();
}
