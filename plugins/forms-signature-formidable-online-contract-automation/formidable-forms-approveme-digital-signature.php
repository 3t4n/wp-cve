<?php
/**
 * @package   	      Formidable Forms Signature Online Contract Automation 
 * @contributors      Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me), Arafat Rahman (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       Formidable Forms Signature Online Contract Automation by ApproveMe.com
 * Plugin URI:        http://aprv.me/2llm6iC
 * Description:       This add-on makes it possible to automatically email a WP E-Signature contract (or redirect a user to a contract) after the user has successfully submitted a WPForms. You can also insert data from the submitted WPForms into the WP E-Signature contract.
 * Version:           1.8.3
 * Author:            ApproveMe.com
 * Author URI:        http://aprv.me/2llm6iC
 * Text Domain:       esig-formidableform
 * Domain Path:       /languages
 * License/Terms & Conditions: http://www.approveme.com/terms-conditions/
 * Privacy Policy:    http://www.approveme.com/privacy-policy/
 * License:           GPLv2+
 * Domain Path:       /languages
 */

/**
 * Copyright (c)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Define constants
 */
define( 'FORMIDABLEFORM_WPESIGNATURE_VER', '1.8.3' );
define( 'FORMIDABLEFORM_WPESIGNATURE_URL',     plugin_dir_url( __FILE__ ) );
define( 'FORMIDABLEFORM_WPESIGNATURE_PATH',    dirname( __FILE__ ) . '/' );
define( 'FORMIDABLEFORM_WPESIGNATURE_CORE',    dirname( __FILE__ )  );

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/esig-formidable-functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/esig-formidableform-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/esig-formidableform.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-formidableform-filters.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_FORMIDABLEFORM', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_FORMIDABLEFORM', 'deactivate' ) );

require_once( plugin_dir_path( __FILE__ ) . 'admin/about/autoload.php' );
/**
 * Disable Contact Form 7 JavaScript completely
 */
//add_filter( 'wpFORM_load_js', '__return_false' );

//if (is_admin()) {
         
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-formidable-forms-admin.php' );
        add_action( 'plugins_loaded', array('ESIG_FORMIDABLEFORM_Admin', 'get_instance' ) );
        add_action( 'plugins_loaded', array('esigFormidableFilters', 'instance' ) );

    require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/esig-formidableform-document-view.php' );
    
    require_once( plugin_dir_path( __FILE__ ) . 'admin/rating-widget/esign-rating-widget.php' );
add_action( 'plugins_loaded', array( 'esignRatingWidgetFormidable', 'get_instance' ) );
    
