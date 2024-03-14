<?php
defined('ABSPATH') or die('No script kiddies please!');
require_once plugin_dir_path( __FILE__ ) . 'testimonials-plugin.class.php';
$trustindex_pm_google = new TrustindexTestimonialsPlugin("Testimonial Widgets", __FILE__, "1.4.4", "Testimonial Widgets");
$trustindex_pm_google->plugin_uninstall();
?>