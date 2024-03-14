<?php
/**
 * Widget API: ExS_Widget_Theme_Posts class
 *
 * based on WordPress recent posts
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'ExS_Widget_Theme_Posts' ) ) :

	class ExS_Widget_Theme_Posts extends WP_Widget {

		/**
		 * Sets up a new Posts widget instance.
		 *
		 * @since 0.0.1
		 */
		public function __construct() {
			$exs_widget_ops = array(
				'classname'                   => 'widget_custom_posts',
				'description'                 => esc_html__( 'Posts in various layouts.', 'exs' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'custom-posts', esc_html__( 'ExS Blog Posts', 'exs' ), $exs_widget_ops );
			$this->alt_option_name = 'widget_custom_posts';
		}

		/**
		 * Outputs the content for the current Custom Posts widget instance.
		 *
		 * @param array $exs_args Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $exs_instance Settings for the current Custom Posts widget instance.
		 */
		public function widget( $exs_args, $exs_instance ) {
			if ( ! isset( $exs_args['widget_id'] ) ) {
				$exs_args['widget_id'] = $this->id;
			}

			$exs_title = ( ! empty( $exs_instance['title'] ) ) ? $exs_instance['title'] : '';

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$exs_title = apply_filters( 'widget_title', $exs_title, $exs_instance, $this->id_base );

			$exs_sub_title = ( ! empty( $exs_instance['sub_title'] ) ) ? $exs_instance['sub_title'] : '';

			$exs_number = ( ! empty( $exs_instance['number'] ) ) ? absint( $exs_instance['number'] ) : 5;
			// May be not integer
			if ( empty( $exs_number ) ) {
				$exs_number = 5;
			}

			$exs_category     = ( ! empty( $exs_instance['category'] ) ) ? absint( $exs_instance['category'] ) : '';
			$exs_show_cat     = isset( $exs_instance['show_cat'] ) ? $exs_instance['show_cat'] : false;
			$exs_layout       = ( ! empty( $exs_instance['layout'] ) ) ? esc_attr( $exs_instance['layout'] ) : 'default';
			$exs_gap          = ( ! empty( $exs_instance['gap'] ) ) ? esc_attr( $exs_instance['gap'] ) : '';
			$exs_show_date    = isset( $exs_instance['show_date'] ) ? $exs_instance['show_date'] : false;
			$exs_text_center  = isset( $exs_instance['text_center'] ) ? $exs_instance['text_center'] : false;
			$exs_css_class    = ( ! empty( $exs_instance['css_class'] ) ) ? sanitize_text_field( $exs_instance['css_class'] ) : '';
			$exs_cats         = ( ! empty( $exs_instance['cats'] ) ) ? sanitize_text_field( $exs_instance['cats'] ) : '';
			$exs_read_all     = ( ! empty( $exs_instance['read_all'] ) ) ? sanitize_text_field( $exs_instance['read_all'] ) : '';
			$exs_post__not_in = ( ! empty( $exs_instance['post__not_in'] ) ) ? (array) ( $exs_instance['post__not_in'] ) : array();

			//layout may contain columns count separated by space
			$exs_layout  = explode( ' ', $exs_layout );
			$exs_columns = ( ! empty( $exs_layout[1] ) ) ? absint( $exs_layout[1] ) : 2;
			$exs_layout  = $exs_layout[0];

			/**
			 * Filters the arguments for the Recent Posts widget.
			 *
			 * @param array $exs_args An array of arguments used to retrieve the recent posts.
			 * @param array $exs_instance Array of settings for the current widget.
			 *
			 * @see WP_Query::get_posts()
			 *
			 */
			// for sticky posts
			// $exs_sticky_posts = get_option('sticky_posts');
			$exs_r = new WP_Query(
				array(
					'posts_per_page'      => $exs_number,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'cat'                 => $exs_category,
					'post__not_in'        => $exs_post__not_in,
				)
			);

			if ( ! $exs_r->have_posts() ) {
				return;
			}

			$exs_cat_name = '';
			if ( ! empty( $exs_category ) ) {
				$exs_category = get_category( $exs_category );
				$exs_cat_name = $exs_category->cat_name;
			}

			$exs_read_all_url = '';
			if ( ! empty( $exs_read_all ) ) {
				if ( ! empty( $exs_category ) ) {
					$exs_read_all_url = get_category_link( $exs_category );
				} else {
					$exs_read_all_url = get_post_type_archive_link( 'post' );
				}
			}

			$exs_filepath = EXS_WIDGETS_PLUGIN_PATH . 'widgets/posts/views/' . $exs_layout . '.php';

			if ( file_exists( $exs_filepath ) ) {
				include $exs_filepath;
			} else {
				esc_html_e( 'View not found: ', 'exs' );
				echo '<strong>' . esc_html( $exs_filepath ) . '</strong>';
			}

		}

		/**
		 * Handles updating the settings for the current Recent Posts widget instance.
		 *
		 * @param array $exs_new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $exs_old_instance Old settings for this instance.
		 *
		 * @return array Updated settings to save.
		 */
		public function update( $exs_new_instance, $exs_old_instance ) {
			$exs_instance                = $exs_old_instance;
			$exs_instance['title']       = sanitize_text_field( $exs_new_instance['title'] );
			$exs_instance['sub_title']   = sanitize_text_field( $exs_new_instance['sub_title'] );
			$exs_instance['number']      = (int) $exs_new_instance['number'];
			$exs_instance['category']    = (int) $exs_new_instance['category'];
			$exs_instance['show_cat']    = isset( $exs_new_instance['show_cat'] ) ? (bool) $exs_new_instance['show_cat'] : false;
			$exs_instance['layout']      = esc_attr( $exs_new_instance['layout'] );
			$exs_instance['gap']         = esc_attr( $exs_new_instance['gap'] );
			$exs_instance['show_date']   = isset( $exs_new_instance['show_date'] ) ? (bool) $exs_new_instance['show_date'] : false;
			$exs_instance['text_center'] = isset( $exs_new_instance['text_center'] ) ? (bool) $exs_new_instance['text_center'] : false;
			$exs_instance['read_all']    = sanitize_text_field( $exs_new_instance['read_all'] );
			$exs_instance['css_class']   = sanitize_text_field( $exs_new_instance['css_class'] );
			$exs_instance['cats']        = sanitize_text_field( $exs_new_instance['cats'] );

			return $exs_instance;
		}

		/**
		 * Outputs the settings form for the Recent Posts widget.
		 *
		 * @param array $exs_instance Current settings.
		 */
		public function form( $exs_instance ) {
			$exs_title       = isset( $exs_instance['title'] ) ? esc_attr( $exs_instance['title'] ) : '';
			$exs_sub_title   = isset( $exs_instance['sub_title'] ) ? esc_attr( $exs_instance['sub_title'] ) : '';
			$exs_number      = isset( $exs_instance['number'] ) ? absint( $exs_instance['number'] ) : 5;
			$exs_category    = isset( $exs_instance['category'] ) ? absint( $exs_instance['category'] ) : '';
			$exs_show_cat    = isset( $exs_instance['show_cat'] ) ? (bool) $exs_instance['show_cat'] : false;
			$exs_layout      = isset( $exs_instance['layout'] ) ? esc_attr( $exs_instance['layout'] ) : 'default';
			$exs_gap         = isset( $exs_instance['gap'] ) ? esc_attr( $exs_instance['gap'] ) : '';
			$exs_show_date   = isset( $exs_instance['show_date'] ) ? (bool) $exs_instance['show_date'] : false;
			$exs_text_center = isset( $exs_instance['text_center'] ) ? (bool) $exs_instance['text_center'] : false;
			$exs_css_class   = isset( $exs_instance['css_class'] ) ? sanitize_text_field( $exs_instance['css_class'] ) : '';
			$exs_read_all    = isset( $exs_instance['read_all'] ) ? sanitize_text_field( $exs_instance['read_all'] ) : '';
			$exs_cats        = isset( $exs_instance['cats'] ) ? sanitize_text_field( $exs_instance['cats'] ) : '';
			?>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'exs' ); ?></label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $exs_title ); ?>"/>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'sub_title' ) ); ?>"><?php esc_html_e( 'Sub Title:', 'exs' ); ?></label>
				<textarea
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'sub_title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'sub_title' ) ); ?>"
				><?php echo esc_html( $exs_sub_title ); ?></textarea>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Select category to show:', 'exs' ); ?></label>
				<?php
					wp_dropdown_categories(
						array(
							'id'              => $this->get_field_id( 'category' ),
							'name'            => $this->get_field_name( 'category' ),
							'selected'        => $exs_category,
							'show_option_all' => esc_html__( 'All', 'exs' ),
							'hierarchical'    => 1,
							'show_count'      => 1,
							'class'           => 'widefat',
						)
					);
				?>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_show_cat ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_cat' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_cat' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_cat' ) ); ?>"><?php esc_html_e( 'Show category name if selected', 'exs' ); ?></label>
			</p>

			<p>
				<?php esc_html_e( 'Any layout in main sidebar will be displayed in one column:', 'exs' ); ?>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Widgets layout:', 'exs' ); ?></label>
				<select
					name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"
					class="widefat"
				>
					<option value="default"<?php selected( $exs_layout, 'default' ); ?>><?php esc_html_e( 'Default list', 'exs' ); ?></option>
					<option value="title-only"<?php selected( $exs_layout, 'title-only' ); ?>><?php esc_html_e( 'Only titles', 'exs' ); ?></option>
					<option value="featured-columns"<?php selected( $exs_layout, 'featured-columns' ); ?>><?php esc_html_e( 'Large first post - layout 1', 'exs' ); ?></option>
					<option value="featured"<?php selected( $exs_layout, 'featured' ); ?>><?php esc_html_e( 'Large first post - layout 2', 'exs' ); ?></option>
					<option value="featured-3"<?php selected( $exs_layout, 'featured-3' ); ?>><?php esc_html_e( 'Large two first posts', 'exs' ); ?></option>
					<option value="cols"<?php selected( $exs_layout, 'cols' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'exs' ); ?></option>
					<option value="cols 3"<?php selected( $exs_layout, 'cols 3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'exs' ); ?></option>
					<option value="cols 4"<?php selected( $exs_layout, 'cols 4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'exs' ); ?></option>
					<option value="cols-absolute-single"<?php selected( $exs_layout, 'cols-absolute-single' ); ?>><?php esc_html_e( '1 column - title overlap', 'exs' ); ?></option>
					<option value="cols-absolute"<?php selected( $exs_layout, 'cols-absolute' ); ?>><?php esc_html_e( 'Grid - 2 cols - title overlap', 'exs' ); ?></option>
					<option value="cols-absolute 3"<?php selected( $exs_layout, 'cols-absolute 3' ); ?>><?php esc_html_e( 'Grid - 3 cols - title overlap', 'exs' ); ?></option>
					<option value="cols-absolute 4"<?php selected( $exs_layout, 'cols-absolute 4' ); ?>><?php esc_html_e( 'Grid - 4 cols - title overlap', 'exs' ); ?></option>
					<option value="side"<?php selected( $exs_layout, 'side' ); ?>><?php esc_html_e( 'Side featured image', 'exs' ); ?></option>
				</select>
			</p>


			<p>
				<?php esc_html_e( 'Gap size for columns layout:', 'exs' ); ?>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'gap' ) ); ?>"><?php esc_html_e( 'Columns gap:', 'exs' ); ?></label>
				<select
					name="<?php echo esc_attr( $this->get_field_name( 'gap' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'gap' ) ); ?>"
					class="widefat"
				>
					<option value=""<?php selected( $exs_gap, '' ); ?>><?php esc_html_e( 'Default', 'exs' ); ?></option>
					<option value="1"<?php selected( $exs_gap, '1' ); ?>><?php esc_html_e( '1px', 'exs' ); ?></option>
					<option value="2"<?php selected( $exs_gap, '2' ); ?>><?php esc_html_e( '2px', 'exs' ); ?></option>
					<option value="3"<?php selected( $exs_gap, '3' ); ?>><?php esc_html_e( '3px', 'exs' ); ?></option>
					<option value="4"<?php selected( $exs_gap, '4' ); ?>><?php esc_html_e( '4px', 'exs' ); ?></option>
					<option value="5"<?php selected( $exs_gap, '5' ); ?>><?php esc_html_e( '5px', 'exs' ); ?></option>
					<option value="10"<?php selected( $exs_gap, '10' ); ?>><?php esc_html_e( '10px', 'exs' ); ?></option>
					<option value="15"<?php selected( $exs_gap, '15' ); ?>><?php esc_html_e( '15px', 'exs' ); ?></option>
					<option value="20"<?php selected( $exs_gap, '20' ); ?>><?php esc_html_e( '20px', 'exs' ); ?></option>
					<option value="30"<?php selected( $exs_gap, '30' ); ?>><?php esc_html_e( '30px', 'exs' ); ?></option>
					<option value="40"<?php selected( $exs_gap, '40' ); ?>><?php esc_html_e( '40px', 'exs' ); ?></option>
					<option value="50"<?php selected( $exs_gap, '50' ); ?>><?php esc_html_e( '50px', 'exs' ); ?></option>
					<option value="60"<?php selected( $exs_gap, '60' ); ?>><?php esc_html_e( '60px', 'exs' ); ?></option>
				</select>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts (5 by default):', 'exs' ); ?></label>
				<input
					class="tiny-text"
					id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
					type="number"
					step="1"
					min="1"
					value="<?php echo esc_attr( $exs_number ); ?>"
					size="3"/>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_show_date ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'exs' ); ?></label>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_text_center ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'text_center' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'text_center' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'text_center' ) ); ?>"><?php esc_html_e( 'Center alignment', 'exs' ); ?></label>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'read_all' ) ); ?>"><?php esc_html_e( '\'Read All\' link text', 'exs' ); ?></label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'read_all' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'read_all' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $exs_read_all ); ?>"/>
			</p>


			<p>
				<label
						for="<?php echo esc_attr( $this->get_field_id( 'cats' ) ); ?>"><?php esc_html_e( 'Show categories', 'exs' ); ?></label>
				<select
						name="<?php echo esc_attr( $this->get_field_name( 'cats' ) ); ?>"
						id="<?php echo esc_attr( $this->get_field_id( 'cats' ) ); ?>"
						class="widefat"
				>
					<option value=""<?php selected( $exs_cats, '' ); ?>><?php esc_html_e( 'No', 'exs' ); ?></option>
					<option value="links-all"<?php selected( $exs_cats, 'links-all' ); ?>><?php esc_html_e( 'All (simple links)', 'exs' ); ?></option>
					<option value="links-first"<?php selected( $exs_cats, 'links-first' ); ?>><?php esc_html_e( 'Only first (simple link)', 'exs' ); ?></option>
					<option value="pills-all"<?php selected( $exs_cats, 'pills-all' ); ?>><?php esc_html_e( 'All (buttons)', 'exs' ); ?></option>
					<option value="pills-first"<?php selected( $exs_cats, 'pills-first' ); ?>><?php esc_html_e( 'Only first (button)', 'exs' ); ?></option>

				</select>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'css_class' ) ); ?>"><?php esc_html_e( 'Custom CSS class:', 'exs' ); ?></label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'css_class' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'css_class' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $exs_css_class ); ?>"/>
			</p>
			<?php
		}
	}

endif; //class_exists

if ( ! function_exists( 'exs_theme_register_widget_theme_posts' ) ) :
	function exs_theme_register_widget_theme_posts() {
		register_widget( 'ExS_Widget_Theme_Posts' );
	}
endif;
add_action( 'widgets_init', 'exs_theme_register_widget_theme_posts' );

if ( ! function_exists( 'exs_widgets_categories_list' ) ) :
	function exs_widgets_categories_list( $layout, $post_id ) {

			if ( empty( $layout ) ) {
				return;
			}
			$exs_c = wp_get_post_categories( $post_id );

			//only if categories exists
			if ( empty( $exs_c ) ) {
				return;
			}
			//'links-all'
			//'links-first'
			//'pills-all'
			//'pills-first'
			switch ( $layout ):

				case 'links-all':
				case 'links-first':
			?>
				<div class="cats-links cats-<?php echo esc_attr( $layout ); ?>">
					<span class="icon-inline post-date">
						<?php function_exists( 'exs_icon' ) ? exs_icon( 'folder' ) : ''; ?>
						<?php echo get_the_category_list( '<span class="cats-separator">,&nbsp;</span>', '', $post_id ); ?>
					</span>
				</div>
			<?php
				break;
				case 'pills-all':
				case 'pills-first':
				?>
				<div class="cats-pills cats-<?php echo esc_attr( $layout ); ?>">
					<?php  echo get_the_category_list( ' ', '', $post_id ); ?>
				</div>
				<?php
				break;
			endswitch;
	}
endif;

//ExS posts widget block
if ( ! function_exists( 'exs_widgets_posts_block_callback' ) ) :
	function exs_widgets_posts_block_callback( $block_attributes, $content ) {

		require_once EXS_WIDGETS_PLUGIN_PATH . '/functions.php';

		ob_start();

		the_widget(
			'ExS_Widget_Theme_Posts',
			$block_attributes,
			array()
		);

		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'exs_widgets_posts_block' ) ) :
	function exs_widgets_posts_block() {

		$deps = array(
			'lodash',
			'wp-i18n',
			'wp-element',
			'wp-components',
			'wp-compose',
			'wp-data',
			'wp-plugins',
			'wp-blocks',
			'wp-dom-ready',
			'wp-server-side-render',
		);

		$screen = false;
		//$screen = get_current_screen();
		if ( $screen ) {
			if ( 'widgets' !== $screen->id ) {
				$deps[] = 'wp-edit-post';
				$deps[] = 'wp-editor';
			}
		}

		wp_register_script(
			'exs-widgets-posts-dynamic',
			EXS_WIDGETS_PLUGIN_ASSETS_URL . 'js/blocks.js',
			$deps,
			EXS_WIDGETS_PLUGIN_VERSION,
			true
		);

		register_block_type(
			'exs-blocks/exs-widget-posts',
			array(
				'editor_script' => 'exs-widgets-posts-dynamic',
				'render_callback' => 'exs_widgets_posts_block_callback',
				'attributes' => array(
					'number' => array(
						'type' => 'number',
						'default' => 5,
					),
					'category' => array(
						'type' => 'string',
						'default' => '',
					),
					'layout' => array(
						'type' => 'string',
						'default' => 'default',
					),
					'gap' => array(
						'type' => 'string',
						'default' => '',
					),
					'show_date' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'text_center' => array(
						'type' => 'boolean',
						'default' => false,
					),
					'read_all' => array(
						'type' => 'string',
						'default' => '',
					),
					'cats' => array(
						'type' => 'string',
						'default' => '',
					),
					'animation' => array(
						'type' => 'string',
						'default' => '',
					),
				)
			)
		);

	}
endif;
add_action( 'init', 'exs_widgets_posts_block' );

if ( ! function_exists( 'exs_widgets_posts_block_editor_assets' ) ) :
	function exs_widgets_posts_block_editor_assets() {
		wp_enqueue_style(
			'exs-widget-posts-editor-css',
			EXS_WIDGETS_PLUGIN_ASSETS_URL . 'css/exs-widgets.css',
			array(),
			EXS_WIDGETS_PLUGIN_VERSION
		);
	}
endif;
add_action( 'enqueue_block_editor_assets', 'exs_widgets_posts_block_editor_assets' );
