<?php
/*
Plugin Name: Contact Details
Plugin URI: http://www.birdbrain.com.au/plugin/contact-details
Description: Adds the simple ability for your clients to easily enter their contact details and output them wherever they like with a single shortcode
Author: BirdBrain Logic
Version: 1.3
Author URI: http://www.birdbrain.com.au
*/

// Ensure WordPress has been bootstrapped
if( !defined( 'ABSPATH' ) )
	die( 'No you don\'t Mr Sneaky! ;)' );

// Ensure the Contact Details class has been defined
if( !class_exists( 'BirdBrain_Contact_Details' ) )
require_once( trailingslashit( dirname( __FILE__ ) ) . 'lib/class.contact-details.php' );

new BirdBrain_Contact_Details();

?>
