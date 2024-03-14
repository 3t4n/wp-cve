<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/book-press
 * @since      1.0.0
 *
 * @package    Book_Press
 * @subpackage Book_Press/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Book_Press
 * @subpackage Book_Press/includes
 * @author     Md Kabir Uddin <bd.kabiruddin@gmail.com>
 */
class Book_Press_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
       require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-book-press-admin.php';
       $Book_Press_Admin = new Book_Press_Admin('','');
       $Book_Press_Admin->book_press_book_init();
       $Book_Press_Admin->book_press_book_taxonomies();
       flush_rewrite_rules();
	}




}
