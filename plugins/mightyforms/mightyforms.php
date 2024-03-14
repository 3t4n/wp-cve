<?php

/*
    Plugin Name: MightyForms
    Description: Powerful web forms - made easy. Quickly create beautiful forms for any website with this intuitive Drag & Drop online form builder.
    Version: 1.3.8
    Author: Porthas Inc.
    Author URI: https://porthas.com

     Copyright 2019 MightyForm Corp. (email: info@porthas.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('shortcode.php');
require_once('assets.php');
require_once('views/application.php');
require_once('views/forms.php');
require_once('views/how-to.php');

// Set plugin label in main WordPress menu
add_action('admin_menu', 'mightyforms_register_admin_settings');

/**
 * @author DemonIa sanchoclo@gmail.com
 * @function mightyforms_register_admin_settings
 * @description Register menu and submenu items in Wordpress.
 * @param
 * @returns void
 */
function mightyforms_register_admin_settings()
{
    add_menu_page('MightyForms', 'MightyForms', 'manage_options', 'mightyforms', 'mightyforms_run_application', plugins_url('/images/icon.png', __FILE__), 6);

    add_submenu_page('mightyforms', 'Dashboard', 'Dashboard', 'manage_options', 'mightyforms', 'mightyforms_run_application');
    add_submenu_page('mightyforms', 'Embed Forms', 'Embed Forms', 'manage_options', 'mightyforms-forms', 'mightyforms_run_forms');
    add_submenu_page('mightyforms', 'How to', 'How to', 'manage_options', 'mightyforms-how-to', 'mightyforms_run_how_to');
}


/**
 * @author DemonIa sanchoclo@gmail.com
 * @function mightyforms_garbage_collector
 * @description Remove user api key after plugin was uninstalled.
 * @param
 * @returns void
 */
function mightyforms_garbage_collector()
{
    delete_option('mightyforms_api_key');
}

register_uninstall_hook(__FILE__, 'mightyforms_garbage_collector');


add_action('admin_init', 'mf_review_later_handler');

