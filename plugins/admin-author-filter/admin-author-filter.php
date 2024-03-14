<?php
/*
Plugin Name: Admin Author Filter
Plugin URI: http://yourdomain.com/
Description: Filters on Author in Admin Post Page
Version: 1.2
Author: Don Kukral
Author URI: http://yourdomain.com
License: GPL
*/

add_action('restrict_manage_posts', 'author_filter');

function author_filter() {
    $args = array('name' => 'author', 'show_option_all' => 'View all authors');
    if (isset($_GET['user'])) {
        $args['selected'] = $_GET['user'];
    }
    wp_dropdown_users($args);
}
?>
