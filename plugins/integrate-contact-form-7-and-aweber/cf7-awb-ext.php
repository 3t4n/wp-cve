<?php
/*
Plugin Name: Contact Form 7 AWeber Extension
Plugin URI: http://renzojohnson.com/contributions/contact-form-7-Aweber-extension
Description: Integrate Contact Form 7 with AWeber. Automatically add form submissions to predetermined lists in Aweber, using its latest API.
Author: Renzo Johnson
Author URI: http://renzojohnson.com
Text Domain: contact-form-7
Domain Path: /languages/
Version: 0.1.38
*/

/*  Copyright 2013-2022 Renzo Johnson (email: renzojohnson at gmail.com)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'SPARTAN_AWB_VERSION', '0.1.38' );
define( 'SPARTAN_AWB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPARTAN_AWB_PLUGIN_NAME', trim( dirname( SPARTAN_AWB_PLUGIN_BASENAME ), '/' ) );
define( 'SPARTAN_AWB_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'SPARTAN_AWB_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'SPARTAN_AWB_PLUGIN_MODULES_DIR', SPARTAN_AWB_PLUGIN_DIR . '/modules' );

require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/aweber.php' );

