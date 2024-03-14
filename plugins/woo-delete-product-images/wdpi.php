<?php 
    /*
    Plugin Name: Woocommerce delete product images
    Plugin URI: 
    Description: Plugin for delete product attached image when delete product.
    Author: Husain Ahmed
    Version: 1.0.2
	WC requires at least: 3.0.0
    WC tested up to: 4.0.1
    Author URI: https://husain25.wordpress.com/
    */
	
	
	/**
	* Check if WooCommerce is active
	**/
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
	{

		add_action("before_delete_post","woocommerce_delete_product_attached_images",10,1);

			function woocommerce_delete_product_attached_images($post_id)
			{
			 global $wpdb;
					  $arg = array(
							'post_parent' => $post_id,
							'post_type'   => 'attachment', 
							'numberposts' => -1,
							'post_status' => 'any' 
					); 
					$childrens = get_children( $arg);
					if($childrens):
						foreach($childrens as $attachment):   
						 wp_delete_attachment( $attachment->ID, true ); 
						 $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id = ".$attachment->ID);
						 wp_delete_post($attachment->ID,true ); 
						endforeach; 
					endif; 
			}
	} else {
       
		echo "<div class='error'><p>WooCommerce plugin is not activated. Please install and activate it to use Woocommerce Delete Product Images Plugin</p> </div>";

}