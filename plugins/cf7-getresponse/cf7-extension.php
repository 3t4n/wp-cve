<?php
/*
Plugin Name: Contact Form 7 GetResponse Extension
Plugin URI: http://renzojohnson.com/contributions/contact-form-7-getresponse-extension
Description: Integrate Contact Form 7 with GetResponse. Automatically add form submissions to predetermined lists in GetResponse, using its latest API.
Author: Renzo Johnson
Author URI: http://renzojohnson.com
Text Domain: contact-form-7
Domain Path: /languages/
Version: 0.5.26
*/

/*  Copyright 2013-2022 Renzo Johnson (email: renzojohnson at gmail.com)
c
    This prograe is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'SPARTAN_VCGR_VERSION', '0.5.26' );
define( 'SPARTAN_VCGR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPARTAN_VCGR_PLUGIN_NAME', trim( dirname( SPARTAN_VCGR_PLUGIN_BASENAME ), '/' ) );
define( 'SPARTAN_VCGR_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'SPARTAN_VCGR_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
register_activation_hook(__FILE__,'vcgr_help');

require_once( SPARTAN_VCGR_PLUGIN_DIR . '/lib/getresponse.php' );


function vcgr_meta_links( $links, $file ) {
    if ( $file === 'integrate-contact-form-7-and-getresponse/cf7-extension.php' ) {
        // $links[] = '<a href="'.VCGR_URL.'" target="_blank" title="Documentation">Documentation</a>';
        $links[] = '<a href="'.VCGR_URL.'" target="_blank" title="Starter Guide">Starter Guide</a>';
        $links[] = '<a href="//www.paypal.me/renzojohnson" target="_blank" title="Donate"><strong>Donate</strong></a>';
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'vcgr_meta_links', 10, 2 );


function vcgr_settings_link( $links ) {
    $url = get_admin_url() . 'admin.php?page=wpcf7&post='.vcgr_get_latest_item().'&active-tab=4' ;
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'textdomain') . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}


function vcgr_after_setup_theme() {
     add_filter('plugin_action_links_' . SPARTAN_VCGR_PLUGIN_BASENAME, 'vcgr_settings_link');
}
add_action ('after_setup_theme', 'vcgr_after_setup_theme');



  delete_option('vcgr_show_notice', 0);
  update_site_option('vcgr_show_notice', 1);

