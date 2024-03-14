<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Ajax Search Widget
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Ajax Search Premium
 * @version 1.2
 * @deprecated 2.0.0
 */

if ( ! defined( 'YITH_WCAS' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCAS_Ajax_Search_Widget' ) ) {
	/**
	 * YITH WooCommerce Ajax Navigation Widget
	 *
	 * @since 1.0.0
	 */
	class YITH_WCAS_Ajax_Search_Widget extends WP_Widget {
		/**
		 * Constructor.
		 *
		 * @access public
		 */
		public function __construct() {

			/* Widget variable settings. */
			$this->woo_widget_cssclass    = 'woocommerce widget_product_search yith_woocommerce_ajax_search';
			$this->woo_widget_description = esc_html__( 'An Ajax Search box for products only.', 'yith-woocommerce-ajax-search' );
			$this->woo_widget_idbase      = 'yith_woocommerce_ajax_search';
			$this->woo_widget_name        = esc_html__( 'YITH WooCommerce Ajax Search', 'yith-woocommerce-ajax-search' );

			/* Widget settings. */
			$widget_ops = array(
				'classname'   => $this->woo_widget_cssclass,
				'description' => $this->woo_widget_description,
			);

			/* Create the widget. */
			parent::__construct( 'yith_woocommerce_ajax_search', $this->woo_widget_name, $widget_ops );
		}


		/**
		 * Widget function.
		 *
		 * @param array $args Array of arguments.
		 * @param array $instance Array of instance.
		 *
		 * @return void
		 * @see WP_Widget
		 * @access public
		 */
		public function widget( $args, $instance ) {

			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			if ( ! yith_wcas_is_fresh_block_installation() && ! yith_wcas_user_switch_to_block() ) {
				$template       = ( isset( $instance['template'] ) && $instance['template'] ) ? 'template=wide' : '';
				$filters_above  = ( isset( $instance['filters_above'] ) && $instance['filters_above'] ) ? 'class=filters-above' : '';
				$shortcode_args = $template . ' ' . $filters_above;
			} else {
                wp_enqueue_script('ywcas-search-results-script');
				$preset         = empty( $instance['preset'] ) ? 'default' : $instance['preset'];
				$shortcode_args = 'preset=' . $preset;
			}

			echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			}

			echo do_shortcode( '[yith_woocommerce_ajax_search ' . $shortcode_args . ']' );

			echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Update function.
		 *
		 * @param array $new_instance New instance.
		 * @param array $old_instance Old instance.
		 *
		 * @return array
		 * @see WP_Widget->update
		 * @access public
		 */
		public function update( $new_instance, $old_instance ) {

			$instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( stripslashes( $new_instance['title'] ) ) : '';
			if ( ! yith_wcas_is_fresh_block_installation() && ! yith_wcas_user_switch_to_block() ) {
				$instance['template']      = isset( $new_instance['template'] ) ? 1 : 0;
				$instance['filters_above'] = isset( $new_instance['filters_above'] ) ? 1 : 0;
			} else {
				$instance['preset'] = ! empty( $new_instance['preset'] ) ? $new_instance['preset'] : 'default';
			}

			return $instance;
		}

		/**
		 * Show the legacy form
		 *
		 * @param array $instance The widget instance.
		 *
		 * @return void
		 */
		protected function legacy_form( $instance ) {
			$defaults = array(
				'title'         => '',
				'template'      => 0,
				'filters_above' => 0,
			);

			$instance = wp_parse_args( (array) $instance, $defaults );
			$title    = $instance['title'] ?? '';
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html_x( 'Title:', '[Widget] The widget title', 'yith-woocommerce-ajax-search' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value=" <?php echo esc_attr( $title ); ?>"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"> <?php echo esc_html_x( 'Template wide', '[Widget] The widget arg', 'yith-woocommerce-ajax-search' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>"
                       value="1" <?php checked( $instance['template'], 1 ); ?> />

            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'filters_above' ) ); ?>"> <?php echo esc_html_x( 'Filters above', '[Widget] The widget arg', 'yith-woocommerce-ajax-search' ); ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'filters_above' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'filters_above' ) ); ?>"
                       value="1" <?php checked( $instance['filters_above'], 1 ); ?> />

            </p>
			<?php
		}

		/**
		 * Form function.
		 *
		 * @param array $instance Instance.
		 *
		 * @return void
		 * @see WP_Widget->form
		 * @access public
		 */
		public function form( $instance ) {
			if ( ! yith_wcas_is_fresh_block_installation() && ! yith_wcas_user_switch_to_block() ) {
				$this->legacy_form( $instance );
			} else {
				$defaults = array(
					'title'  => '',
					'preset' => 'default',
				);

				$instance    = wp_parse_args( (array) $instance, $defaults );
				$title       = $instance['title'] ?? '';
				$all_presets = ywcas()->settings->get_shortcodes_list();
				?>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                        <strong><?php echo esc_html_x( 'Title:', '[Widget] The widget title', 'yith-woocommerce-ajax-search' ); ?></strong>
                    </label>
                    <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                           value=" <?php echo esc_attr( $title ); ?>"/>
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'preset' ) ); ?>">
                        <strong><?php echo esc_html_x( 'Search shortcode', '[Widget] The widget arg', 'yith-woocommerce-ajax-search' ); ?></strong>
                        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'preset' ) ); ?>"
                                name="<?php echo esc_attr( $this->get_field_name( 'preset' ) ); ?>">
							<?php foreach ( $all_presets as $shortcode_preset => $shortcode ) : ?>
                                <option value="<?php echo esc_attr( $shortcode_preset ); ?>" <?php selected( $shortcode_preset, $instance['preset'] ); ?>><?php echo esc_html( $shortcode['name'] ); ?></option>
							<?php endforeach; ?>
                        </select>
                    </label>
                </p>
				<?php
			}
		}
	}
}
