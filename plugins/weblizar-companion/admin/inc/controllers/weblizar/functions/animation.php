<?php 

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'enigma_animation' ) ) :
	class enigma_animation extends WP_Customize_Control {

		/**
		 * Render the content on the theme customizer page
		 */
		public function render_content() { ?>
            <span class="customize-control-title"><?php esc_html_e( $this->label ,WL_COMPANION_DOMAIN); ?></span>
			<?php
			$animate_slider   = get_theme_mod( 'animate_type_title', 'fadeIn' );
			$animation        = array(
				'fadeIn',
				'fadeInUp',
				'fadeInDown',
				'fadeInLeft',
				'fadeInRight',
				'bounceIn',
				'bounceInUp',
				'bounceInDown',
				'bounceInLeft',
				'bounceInRight',
				'rotateIn',
				'rotateInUpLeft',
				'rotateInDownLeft',
				'rotateInUpRight',
				'rotateInDownRight',
			); ?>

            <select name="animate_slider" class="webriti_inpute" <?php $this->link(); ?>>
				<?php foreach ( $animation as $animate ) { ?>
                    <option value="<?php  esc_attr_e( $animate,WL_COMPANION_DOMAIN ); ?>" <?php echo selected( $animate_slider, $animate ); ?>><?php esc_attr_e( $animate ,WL_COMPANION_DOMAIN); ?></option>
				<?php } ?>
            </select>
			<?php
		}
	}
endif;