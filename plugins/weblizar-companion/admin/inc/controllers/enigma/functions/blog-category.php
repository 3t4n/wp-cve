<?php

defined( 'ABSPATH' ) or die();

/* class for categories */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'enigma_category_Control' ) ) :
	class enigma_category_Control extends WP_Customize_Control {
		public function render_content() { ?>
            <span class="customize-control-title"><?php  esc_attr_e( $this->label ,WL_COMPANION_DOMAIN); ?></span>
			<?php $enigma_category = get_categories(); ?>
            <select <?php $this->link(); ?> >
				<?php foreach ( $enigma_category as $category ) { ?>
                    <option value="<?php esc_attr_e( $category->term_id,WL_COMPANION_DOMAIN ); ?>" <?php if ( $this->value() == '' ) {
						echo esc_attr('selected="selected"');
					} ?> ><?php esc_attr_e( $category->cat_name,WL_COMPANION_DOMAIN ); ?></option>
				<?php } ?>
            </select> <?php
		}  /* public function ends */
	}/*   class ends */
endif;