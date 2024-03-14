<?php
/**
 * Widget API: ExS_Widget_Theme_Category class
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'ExS_Widget_Theme_Category' ) ) :

	class ExS_Widget_Theme_Category extends WP_Widget {

		/**
		 * Sets up a new Theme Category widget instance.
		 *
		 * @since 0.0.1
		 */
		public function __construct() {
			$exs_widget_ops = array(
				'classname'                   => 'widget_theme_category',
				'description'                 => esc_html__( 'A category with articles counter.', 'exs' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'theme-category', esc_html__( 'ExS Category', 'exs' ), $exs_widget_ops );
			$this->alt_option_name = 'widget_theme_category';
		}

		/**
		 * Outputs the content for the current Categories widget instance.
		 *
		 * @param array $exs_args Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $exs_instance Settings for the current Categories widget instance.
		 *
		 * @since 0.0.1
		 *
		 */
		public function widget( $exs_args, $exs_instance ) {
			if ( ! isset( $exs_args['widget_id'] ) ) {
				$exs_args['widget_id'] = $this->id;
			}

			$exs_title = ! empty( $exs_instance['title'] ) ? $exs_instance['title'] : '';

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$exs_title = apply_filters( 'widget_title', $exs_title, $exs_instance, $this->id_base );

			$exs_cat         = ! empty( $exs_instance['cat'] ) ? absint( $exs_instance['cat'] ) : '0';
			$exs_c           = ! empty( $exs_instance['count'] ) ? '1' : '';
			$exs_bordered    = ! empty( $exs_instance['bordered'] ) ? '1' : '';
			$exs_muted       = ! empty( $exs_instance['muted'] ) ? '1' : '';
			$exs_center      = ! empty( $exs_instance['center'] ) ? '1' : '';
			$exs_count_label = ! empty( $exs_instance['count_label'] ) ? $exs_instance['count_label'] : '';

			echo wp_kses_post( $exs_args['before_widget'] );

			if ( $exs_title ) {
				echo wp_kses_post( $exs_args['before_title'] . $exs_title . $exs_args['after_title'] );
			}

			$exs_category = get_category( $exs_cat );

			if ( $exs_category ) :
				$exs_css_class = '';
				if ( $exs_bordered ) {
					$exs_css_class .= ' bordered';
				}
				if ( $exs_muted ) {
					$exs_css_class .= ' muted';
				}
				if ( $exs_center ) {
					$exs_css_class .= ' text-center';
				}
				?>
				<a href="<?php echo esc_url( get_category_link( $exs_category ) ); ?>">
					<div class="category-block<?php echo esc_attr( $exs_css_class ); ?>">
						<?php if ( empty( $exs_count_label ) ) : ?>
							<h5>
								<?php
								echo esc_html( $exs_category->name );

								if ( ! empty( $exs_c ) ) {
									echo ' <span class="items-count"><span class="items-count-open">(</span>' . esc_html( $exs_category->count ) . '<span class="items-count-close">)</span></span>';
								}
								?>
							</h5>
							<?php
						else :
							?>
							<h5><?php echo esc_html( $exs_category->name ); ?></h5>
							<p>
								<?php
								echo '<span class="items-count-label">' . wp_kses_post( $exs_count_label ) . '</span>';
								if ( ! empty( $exs_c ) ) {
									echo ' <span class="items-count">' . esc_html( $exs_category->count ) . '</span>';
								}
								?>
							</p>
							<?php
						endif; //$exs_count_label
						?>
					</div><!-- .category-block -->
				</a>
				<?php
			endif; //category

			echo wp_kses_post( $exs_args['after_widget'] );
		}

		/**
		 * Handles updating settings for the current Categories widget instance.
		 *
		 * @param array $exs_new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $exs_old_instance Old settings for this instance.
		 *
		 * @return array Updated settings to save.
		 * @since 0.0.1
		 *
		 */
		public function update( $exs_new_instance, $exs_old_instance ) {
			$exs_instance                = $exs_old_instance;
			$exs_instance['title']       = sanitize_text_field( $exs_new_instance['title'] );
			$exs_instance['cat']         = ! empty( $exs_new_instance['cat'] ) ? absint( $exs_new_instance['cat'] ) : 0;
			$exs_instance['count']       = ! empty( $exs_new_instance['count'] ) ? 1 : 0;
			$exs_instance['bordered']    = ! empty( $exs_new_instance['bordered'] ) ? 1 : 0;
			$exs_instance['muted']       = ! empty( $exs_new_instance['muted'] ) ? 1 : 0;
			$exs_instance['center']      = ! empty( $exs_new_instance['center'] ) ? 1 : 0;
			$exs_instance['count_label'] = sanitize_text_field( $exs_new_instance['count_label'] );

			return $exs_instance;
		}

		/**
		 * Outputs the settings form for the Categories widget.
		 *
		 * @param array $exs_instance Current settings.
		 *
		 * @since 0.0.1
		 *
		 */
		public function form( $exs_instance ) {
			//Defaults
			$exs_instance    = wp_parse_args( (array) $exs_instance, array( 'title' => '' ) );
			$exs_cat         = isset( $exs_instance['cat'] ) ? absint( $exs_instance['cat'] ) : false;
			$exs_count       = isset( $exs_instance['count'] ) ? (bool) $exs_instance['count'] : false;
			$exs_bordered    = isset( $exs_instance['bordered'] ) ? (bool) $exs_instance['bordered'] : false;
			$exs_muted       = isset( $exs_instance['muted'] ) ? (bool) $exs_instance['muted'] : false;
			$exs_center      = isset( $exs_instance['center'] ) ? (bool) $exs_instance['center'] : false;
			$exs_count_label = isset( $exs_instance['count_label'] ) ? sanitize_text_field( $exs_instance['count_label'] ) : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<?php esc_html_e( 'Title:', 'exs' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
						value="<?php echo esc_attr( $exs_instance['title'] ); ?>"/>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>">
					<?php esc_html_e( 'Category:', 'exs' ); ?>
				</label>
				<?php
				$exs_dropdown_args = array(
					'taxonomy'          => 'category',
					'selected'          => $exs_cat,
					'orderby'           => 'name',
					'order'             => 'ASC',
					'show_count'        => 1,
					'hide_empty'        => 0,
					'child_of'          => 0,
					'exclude'           => '',
					'hierarchical'      => 1,
					'depth'             => 0,
					'class'             => 'widefat',
					'tab_index'         => 0,
					'hide_if_empty'     => false,
					'option_none_value' => 0,
					'value_field'       => 'term_id',
					'id'                => $this->get_field_id( 'cat' ),
					'name'              => $this->get_field_name( 'cat' ),
				);
				wp_dropdown_categories( $exs_dropdown_args );
				?>
			</p>

			<p>
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>"<?php checked( $exs_count ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
					<?php esc_html_e( 'Show post counts', 'exs' ); ?>
				</label>
			</p>


			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'count_label' ) ); ?>"><?php esc_html_e( 'Count number label:', 'exs' ); ?></label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'count_label' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'count_label' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $exs_count_label ); ?>"/>
			</p>

			<p>
				<input type="checkbox" class="checkbox"
						id="<?php echo esc_attr( $this->get_field_id( 'bordered' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'bordered' ) ); ?>"<?php checked( $exs_bordered ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'bordered' ) ); ?>">
					<?php esc_html_e( 'Bordered', 'exs' ); ?>
				</label>
			</p>

			<p>
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'muted' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'muted' ) ); ?>"<?php checked( $exs_muted ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'muted' ) ); ?>">
					<?php esc_html_e( 'Muted', 'exs' ); ?>
				</label>
			</p>

			<p>
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'center' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'center' ) ); ?>"<?php checked( $exs_center ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'center' ) ); ?>">
					<?php esc_html_e( 'Align center', 'exs' ); ?>
				</label>
			</p>


			<?php
		}

	}

endif;

if ( ! function_exists( 'exs_theme_register_widget_theme_category' ) ) :
	function exs_theme_register_widget_theme_category() {
		register_widget( 'ExS_Widget_Theme_Category' );
	}
endif;
add_action( 'widgets_init', 'exs_theme_register_widget_theme_category' );
