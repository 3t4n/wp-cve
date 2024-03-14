<?php
/*  Copyright 2010-2023  François Pons  (email : fpons@aytechnet.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class PrestaShopIntegrationProducts_Widget extends WP_Widget {
	function __construct() {
		load_plugin_textdomain( 'prestashop_integration', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		parent::__construct( 'prestashop_integration_products_widget', 'PrestaShop Integration Products', array(
			'description' => __( 'Add PrestaShop products to your sidebar', 'prestashop_integration' )
		) );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$tpl = $instance['tpl'] == '' ? 'product-list.tpl' : $instance['tpl'];

		global $post;
		global $prestashop_integration;

		if ( $prestashop_integration->psValid() ) {
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;

			if ( isset($post) && isset($post->ID) ) {
				$id_product = preg_replace( array('/^[\s,]+/', '/[\s,]+/', '/[\s,]+$/'), array('', ',', ''), get_post_meta( $post->ID, '_prestashop_integration_products', true ) );

				if ( !empty( $id_product ) ) {
					$products = $prestashop_integration->getProducts($prestashop_integration->psLang(), $id_product);

					$prestashop_integration->setTemplateVars( array(
						'products' => $products,
						'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
						'categorySize' => Image::getSize('category'),
						'mediumSize' => Image::getSize('medium'),
						'homeSize' => Image::getSize('home') ));
					echo $prestashop_integration->displayTemplate(_PS_THEME_DIR_.$tpl);
				}
			}
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tpl'] = strip_tags( $new_instance['tpl'] );

		return $instance;
	}

	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance['title'] );
			$tpl = esc_attr( $instance['tpl'] );
		}
		else {
			$title = __( 'New title', 'prestashop_integration' );
			$tpl = 'product-list.tpl';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('tpl'); ?>"><?php _e('Smarty template:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('tpl'); ?>" name="<?php echo $this->get_field_name('tpl'); ?>" type="text" value="<?php echo $tpl; ?>" />
		</p>
		<?php 
	}
}

?>
