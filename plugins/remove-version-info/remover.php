<?php
/*
Plugin Name: Remove Version Info
Plugin URI: http://techmagics.com/
Description: Remove the version from your WordPress website completely and Increase security and thwart potential hacks.
Author: Ashkar
Author URI: http://techmagics.com/
Version: 1.1
*/

function remove_version_info() {
     return '';
}
add_filter('the_generator', 'remove_version_info');
?>
