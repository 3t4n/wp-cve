<?php
/*
Plugin Name: WP Excerpt Generator
Plugin URI: http://blog.internet-formation.fr/2013/10/wp-excerpt-generator/
Description: générateur d'extraits pour WordPress avec plusieurs options... (<em>excerpts generator for WordPress with several options...</em>).
Author: Mathieu Chartier
Version: 2.6.1
Author URI: http://blog.internet-formation.fr
*/

// Instanciation des variables globales
global $wpdb, $table_WP_Excerpt_Generator, $WP_Excerpt_Generator_Version;
$table_WP_Excerpt_Generator = $wpdb->posts;

// Version du plugin
$WP_Excerpt_Generator_Version = "2.6.1";

// Gestion des langues
function WP_Excerpt_Generator_Lang() {
   $path = dirname(plugin_basename(__FILE__)).'/lang/';
   load_plugin_textdomain('wp-excerpt-generator', NULL, $path);
}
add_action('plugins_loaded', 'WP_Excerpt_Generator_Lang');

// Fonction lancée lors de l'activation ou de la desactivation de l'extension
register_activation_hook( __FILE__, 'WP_Excerpt_Generator_install');
register_deactivation_hook( __FILE__, 'WP_Excerpt_Generator_desinstall');

function WP_Excerpt_Generator_install() {	
	global $wpdb, $table_WP_Excerpt_Generator, $WP_Excerpt_Generator_Version;
	
	// Valeurs par défaut
	add_option("wp_excerpt_generator_save", true);
	add_option("wp_excerpt_generator_maj", true);
	add_option("wp_excerpt_generator_type", 'pagepost');
	add_option("wp_excerpt_generator_status", 'publish');
	add_option("wp_excerpt_generator_method", 'paragraph');
	add_option("wp_excerpt_generator_nbletters", 600);
	add_option("wp_excerpt_generator_nbwords", 100);
	add_option("wp_excerpt_generator_nbparagraphs", 1);
	add_option("wp_excerpt_generator_cleaner", true);
	add_option("wp_excerpt_generator_breakOK", false);
	add_option("wp_excerpt_generator_break", ' [...]');
	add_option("wp_excerpt_generator_htmlOK", 'none');
	add_option("wp_excerpt_generator_htmlBR", true);
	add_option("wp_excerpt_generator_deleteExcerpt", false);
	add_option("wp_excerpt_generator_delete_shortcode", false);

	// Prise en compte de la version en cours
	add_option("wp_excerpt_generator_version", $WP_Excerpt_Generator_Version);
}

// Quand ça désactive l'extension, la table est supprimée...
function WP_Excerpt_Generator_desinstall() {
	global $wpdb, $table_WP_Excerpt_Generator, $WP_Excerpt_Generator_Version;

	delete_option("wp_excerpt_generator_save");
	delete_option("wp_excerpt_generator_maj");
	delete_option("wp_excerpt_generator_type");
	delete_option("wp_excerpt_generator_status");
	delete_option("wp_excerpt_generator_method");
	delete_option("wp_excerpt_generator_nbletters");
	delete_option("wp_excerpt_generator_nbwords");
	delete_option("wp_excerpt_generator_nbparagraphs");
	delete_option("wp_excerpt_generator_cleaner");
	delete_option("wp_excerpt_generator_breakOK");
	delete_option("wp_excerpt_generator_break");
	delete_option("wp_excerpt_generator_htmlOK");
	delete_option("wp_excerpt_generator_htmlBR");
	delete_option("wp_excerpt_generator_deleteExcerpt");
	delete_option("wp_excerpt_generator_delete_shortcode");
	
	delete_option("wp_excerpt_generator_version", $WP_Excerpt_Generator_Version);
}

// Quand le plugin est mise à jour, on relance la fonction
function WP_Excerpt_Generator_Upgrade() {
    global $WP_Excerpt_Generator_Version;
    if(get_site_option('wp_excerpt_generator_version') != $WP_Excerpt_Generator_Version) {
		if(get_site_option('wp_excerpt_generator_maj') == false) {
			add_option("wp_excerpt_generator_maj", true);
		}
		if(get_site_option('wp_excerpt_generator_nbparagraphs') == false) {
			add_option("wp_excerpt_generator_nbparagraphs", 1);
		}
		if(!get_site_option('wp_excerpt_generator_delete_shortcode')) {
			add_option("wp_excerpt_generator_delete_shortcode", false);
		}
        update_option("wp_excerpt_generator_version", $WP_Excerpt_Generator_Version);
    }
}
add_action('plugins_loaded', 'WP_Excerpt_Generator_Upgrade');

// Ajout d'une page de sous-menu
function WP_Excerpt_Generator_admin() {
	$parent_slug	= 'options-general.php';					// Page dans laquelle est ajoutée le sous-menu
	$page_title		= 'Réglages de WP Excerpt Generator';		// Titre interne à la page de réglages
	$menu_title		= 'Excerpt Generator';						// Titre du sous-menu
	$capability		= 'manage_options';							// Rôle d'administration qui a accès au sous-menu
	$menu_slug		= 'wp-excerpt-generator';					// Alias (slug) de la page
	$function		= 'WP_Excerpt_Generator_Callback';			// Fonction appelé pour afficher la page de réglages
	add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
}
add_action('admin_menu', 'WP_Excerpt_Generator_admin');

// Ajout d'une feuille de style pour l'admin
function WP_Excerpt_Generator_Admin_CSS() {
	$handle = 'excerpt-generator-admin';
	$style	= plugins_url('excerpt-generator-admin.css', __FILE__);
	wp_enqueue_style($handle, $style, 15);
}
add_action('admin_print_styles', 'WP_Excerpt_Generator_Admin_CSS');

// Ajout de scripts pour l'admin
function WP_Excerpt_Generator_Admin_Scripts() {
	// Ajout des scripts utiles pour le plugin
	wp_enqueue_script('wp-excerpt-generator-scripts', plugins_url('/js/scripts.min.js', __FILE__), array('jquery'), false, true);

	// Appel Ajax pour WordPress (renvoyé au script)
	wp_localize_script('wp-excerpt-generator-scripts', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('admin_enqueue_scripts', 'WP_Excerpt_Generator_Admin_Scripts');

// Inclusion des fonctions et options de réglages
include_once('wp-excerpt-generator-functions.php');
include_once('wp-excerpt-generator-options.php');
include_once('wp-excerpt-generator-maj-auto.php');
?>