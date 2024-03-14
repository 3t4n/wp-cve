<?php
defined( 'ABSPATH' ) or die();
/* Admin-Menü */
add_action( 'admin_menu', 'gr_order_category_post_admin_ansicht_einstellungen');
function gr_order_category_post_admin_ansicht_einstellungen() {
	/* Check ob Nutzer Rechte hat Optionen einzustellen */
	if ( current_user_can( 'manage_options' ))
	{
		/* Erstellen des Admin-Menüs */
		add_options_page('GR Order Category Post', 'GR Order Category Post', 'administrator',
		'GR Order Category Post', 'gr_order_category_post_site');		
			/* Wenn Änderungen gepostet, werden diese übernommen */
			/* Überprüfen ob Formular abgeschickt wurde und Nonce-Check */
			if(isset($_POST['submit']) && wp_verify_nonce( $_POST['gr_order_category_post_nonce_field'], 'gr_order_category_post_nonce' )){
				/* Wenn Kategorien ausgewählt wurden */
				if(isset($_POST['post_category'])){
				/* Post sichern und Slashes entfernen */
				$postcategoryarray = array_map( 'sanitize_text_field', wp_unslash( $_POST['post_category'] ) );					
				update_option( 'GROrderkey', $postcategoryarray);
				}
				/* Wenn nichts ausgewählt wurde */
				else{
				update_option( 'GROrderkey', array());	
				}
			}


		/* Seite im Admin-Menü */
		function gr_order_category_post_site() {
		/* Text der Setup-Seite */
			echo '<h1>GR Order Category Post</h1><h2><br>';
			esc_html_e( 'Manual:', 'gr-order-category-post' );
			echo '</h2>';
			esc_html_e( 'Select the categories who should get an alphabetical order and press the Save-button.', 'gr-order-category-post' );
			echo '<br>';
			esc_html_e( 'Categories that are not selected, get the standard sort methode.', 'gr-order-category-post' );
			echo '<br><br><br><h2>';
			esc_html_e( 'Available categories:', 'gr-order-category-post' );
			echo '</h2>';
	
				/* Kategorien in der Setup-Seite */
				/* Abruf der gespeicherten Kategorien die umsortiert werden und Markierung */
				$gr_order_category_post_site_categories = get_option( 'GROrderkey' );
				echo '<form action="" method="post" id="gr-order-category-post">';
				wp_category_checklist( 0, 0, $gr_order_category_post_site_categories, false, null, false );
				/* Nonce-Erstellung */
				wp_nonce_field( 'gr_order_category_post_nonce', 'gr_order_category_post_nonce_field' );
				/* Absendebutton für Änderungen */    
				echo '<span class="submit" style="border: 0;"><input type="submit" name="submit" value="';
				esc_html_e( 'Save', 'gr-order-category-post' );
				echo '" /></span></form>';				
		}

	} 
}
 


















?>