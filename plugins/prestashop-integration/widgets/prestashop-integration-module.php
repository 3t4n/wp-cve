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

class PrestaShopIntegrationModule_Widget extends WP_Widget {
	function __construct() {
		load_plugin_textdomain( 'prestashop_integration', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		parent::__construct( 'prestashop_integration_module_widget', 'PrestaShop Integration Module', array(
			'description' => __( 'Add a PrestaShop module to your sidebar', 'prestashop_integration' )
		) );
	}

	function widget( $args, $instance ) {
		extract( $args );

		global $prestashop_integration;
		if ( $prestashop_integration->psValid() ) {
			if ( !empty( $instance['only_if_products'] ) && $prestashop_integration->disableWidgetDisplayAccordingToOnlyIfProducts( (int)$instance['only_if_products'] ) )
				return;

			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;

			if ( version_compare(_PS_VERSION_, '1.5', '>=') )
				echo Hook::exec( $prestashop_integration->psHooksNames( $instance['hook'] ), array(), $prestashop_integration->psModuleId( $instance['module'] ) );
			else
				echo Module::hookExec( $prestashop_integration->psHooksNames( $instance['hook'] ), array(), $prestashop_integration->psModuleId( $instance['module'] ) );
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		global $prestashop_integration;

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['module'] = strip_tags( $new_instance['module'] );
		$instance['hook'] = preg_replace( '/^('.implode( '|', array_keys( $prestashop_integration->psHooksDescriptions() ) ).')$/', '$1', $new_instance['hook'] );
		$instance['only_if_products'] = (int)$new_instance['only_if_products'];

		return $instance;
	}

	function form( $instance ) {
		global $prestashop_integration;

		if ( $instance ) {
			$title = esc_attr( $instance['title'] );
			$module = esc_attr( $instance['module'] );
			$hook = esc_attr( $instance['hook'] );
			$only_if_products = esc_attr( $instance['only_if_products'] );
		}
		else {
			$title = __( 'New title', 'prestashop_integration' );
			$module = 'blockcart';
			$hook = 'HOOK_RIGHT_COLUMN';
			$only_if_products = 0;
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('module'); ?>"><?php _e('Module:'); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id('module'); ?>" name="<?php echo $this->get_field_name('module'); ?>"><?php
		foreach ( $prestashop_integration->psModules() as $module_name ) { ?>
			<option value="<?php echo $module_name; ?>"<?php if ($module == $module_name ) { echo ' selected="selected"'; } ?>><?php echo $module_name; ?></option>
		<?php } ?>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('hook'); ?>"><?php _e('Hook:'); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id('hook'); ?>" name="<?php echo $this->get_field_name('hook'); ?>"><?php
		foreach ( $prestashop_integration->psHooksDescriptions() as $hook_name => $hook_desc ) { ?>
			<option value="<?php echo $hook_name; ?>"<?php if ($hook == $hook_name ) { echo ' selected="selected"'; } ?>><?php echo $hook_desc; ?></option>
		<?php } ?>
		</select>
		</p>
		<p>
		<?php echo $prestashop_integration->selectOnlyIfProducts( $this, $only_if_products ); ?>
		</p>
		<?php 
	}
}

?>
