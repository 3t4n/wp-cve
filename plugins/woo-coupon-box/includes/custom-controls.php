<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	require_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
}
if ( class_exists( 'WP_Customize_Control' ) ):
	class WOO_COUPON_BOX_Radio_Icons_Control extends WP_Customize_Control {
		public $type = 'wcb_radio_icons';

		public function render_content() {
			?>
            <div class="customize-control-content">
				<?php
				if ( ! empty( $this->label ) ) {
					?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php
				}
				?>
				<?php
				if ( ! empty( $this->description ) ) {
					?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php
				}
				$class = $this->id;
				$class = str_replace( '[', '-', $class );
				$class = str_replace( ']', '', $class );
				?>
                <div class="wcb-radio-icons-wrap <?php echo esc_attr( $class ); ?>">
					<?php
					foreach ( $this->choices as $key => $value ) {
						?>
                        <label class="wcb-radio-icons-label <?php if ( $key == $this->value() )
							echo esc_attr( 'wcb-radio-icons-active' ) ?>">
                            <input type="radio" style="display: none;" name="<?php echo esc_attr( $this->id ); ?>"
                                   value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
                            <span class="<?php echo esc_attr( $key ); ?>"> </span>
                        </label>
						<?php
					}
					?>
                </div>
            </div>
			<?php
		}

	}

	class WOO_COUPON_BOX_Lists extends WP_Customize_Control {
		public $type = 'wcb_radio_icons';

		public function render_content() {
			?>
            <div class="customize-control-content">
				<?php
				if ( ! empty( $this->label ) ) {
					?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php
				}
				?>
				<?php
				if ( ! empty( $this->description ) ) {
					?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php
				}
				?>
                <ul class="wcb-features-list-wrap">
					<?php
					foreach ( $this->choices as $key => $value ) {
						?>
                        <li>
                            <a class="wcb-features-list-item" target="_blank" href="https://1.envato.market/DzJ12"><?php echo esc_html( $value ); ?></a>
                        </li>
						<?php
					}
					?>
                </ul>
            </div>
			<?php
		}

	}

	/**
	 * Alpha Color Picker Custom Control
	 *
	 * @author Braad Martin <http://braadmartin.com>
	 * @license http://www.gnu.org/licenses/gpl-3.0.html
	 * @link https://github.com/BraadMartin/components/tree/master/customizer/alpha-color-picker
	 */
	class WOO_COUPON_BOX_Alpha_Color_Control extends WP_Customize_Control {
		/**
		 * The type of control being rendered
		 */
		public $type = 'wcb-alpha-color';
		/**
		 * Add support for palettes to be passed in.
		 *
		 * Supported palette values are true, false, or an array of RGBa and Hex colors.
		 */
		public $palette;
		/**
		 * Add support for showing the opacity value on the slider handle.
		 */
		public $show_opacity;

		/**
		 * Enqueue our scripts and styles
		 */
		public function enqueue() {
			wp_enqueue_script( 'woo-coupon-box-custom-controls-alpha-color-picker-js', VI_WOO_COUPON_BOX_JS . 'alpha-color-picker.js', array(
				'jquery',
				'wp-color-picker'
			), '1.0', true );
			wp_enqueue_style( 'woo-coupon-box-custom-controls-alpha-color-picker-css', VI_WOO_COUPON_BOX_CSS . 'alpha-color-picker.css', array( 'wp-color-picker' ), '1.0', 'all' );
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {

			// Process the palette
			if ( is_array( $this->palette ) ) {
				$palette = implode( '|', $this->palette );
			} else {
				// Default to true.
				$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
			}

			// Support passing show_opacity as string or boolean. Default to true.
			$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

			?>
            <label>
				<?php // Output the label and description if they were passed in.
				if ( isset( $this->label ) && '' !== $this->label ) {
					echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
				}
				if ( isset( $this->description ) && '' !== $this->description ) {
					echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
				} ?>
            </label>
            <input class="wcb-alpha-color-control" type="text" data-show-opacity="<?php echo esc_attr($show_opacity); ?>"
                   data-palette="<?php echo esc_attr( $palette ); ?>"
                   data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?> />
			<?php
		}
	}
endif;
