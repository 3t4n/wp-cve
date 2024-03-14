<?php
/**
 * Created by PhpStorm.
 * User: Sanchoss
 * Date: 3/13/19
 * Time: 16:33
 */

include ('gutenberg_block/init.php');

/**
 * @author DemonIa sanchoclo@gmail.com
 * @function mightyforms_admin_load_assets
 * @description Needed for include plugin .js and .css files.
 * @param
 * @returns void
 */
function mightyforms_admin_load_assets()
{
    wp_enqueue_script('datatables_js', plugins_url('/js/jquery.dataTables.min.js', __FILE__));
    wp_enqueue_script('mightyforms_js', plugins_url('/js/script.js', __FILE__), array('jquery'));


    wp_enqueue_style('datatables_css', plugins_url('/css/jquery.dataTables.min.css', __FILE__));
    wp_enqueue_style('mightyforms_css', plugins_url('/css/style.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'mightyforms_admin_load_assets');