<?php
/*
Plugin Name: Quick META Keywords
Author: Quick Online Tips
Author URI: https://www.quickonlinetips.com
Version: 1.1
Description: Automatically adds META keywords tags within html HEAD tags and uses categories as keywords.
Plugin URI: https://www.quickonlinetips.com/archives/quick-meta-keywords-wordpress-plugin/
*/
function quickkeywords()
{
    if (is_single())
    {
        echo '<meta name="keywords" content="';
    }
    foreach ((get_the_category()) as $cat) if (is_single())
    {
        echo $cat->cat_name . ', ';
    }
    if (is_single())
    {
        echo '" />';
    }
}
add_action('wp_head', 'quickkeywords');
?>
