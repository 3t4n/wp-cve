<?php
/**
 * Plugin Name: GR Order Category Post
 * Plugin URI: https://wordpress.org/plugins/gr-order-category-post/
 * Description: This plugin let you change the order from a category to an alphabetical order (A-Z).
 * Version: 1.0.8
 * Author: Achim T.
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gr-order-category-post 
 */
 
 
/* Datenbankeintrag hinzufügen bei Pluginaktivierung */
register_activation_hook(__FILE__,'gr_order_category_post_activate'); 
function gr_order_category_post_activate() {
add_option('GROrderkey', array());
}
/* Datenbankeintrag löschen bei Löschen des Plugins */
register_uninstall_hook( __FILE__, 'gr_order_category_post_remove' );
function gr_order_category_post_remove() {
delete_option('GROrderkey');
}
 
/* Prüfen ob Kategorien gesetzt sind, ansonsten wird die Sortierung nicht genutzt, da ansonsten alle Kategorien geändert werden */
	$gr_order_category_post_site_categories = get_option( 'GROrderkey' );
		if (empty($gr_order_category_post_site_categories)) {   	
		}
		else { 	 
		/* Start der Sortierung */
		add_action( 'pre_get_posts', 'gr_order_category_post'); 
		function gr_order_category_post($query){
		/* Sortierung wenn Kategorie zutreffend */
		$gr_order_category_post_site_categories = get_option( 'GROrderkey' );
		if (is_category($gr_order_category_post_site_categories))  
		{
           /* A-Z Sortierung */
           $query->set( 'order', 'ASC' );
           /* Setzen des orderby */
           $query->set( 'orderby', 'title' );
		}
		};
	}

/* Admin-Menü Anzeige */
include (plugin_dir_path(__FILE__) . 'inc/gr-admin-menu.php');	

?>