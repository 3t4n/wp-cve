<?php
/**
 * Copyright 2013-2015 Renzo Johnson (email: renzojohnson at gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package aweber
 */

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/activate.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/enqueue.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/tools.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/helperaw.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/awb_db_log.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/functions.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/findaw.php' );
require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/viewaw.php' );



require_once( SPARTAN_AWB_PLUGIN_DIR . '/lib/wp.php' );
if ( ! class_exists( 'AWeberAPI' ) ) {
			require_once( SPARTAN_AWB_PLUGIN_DIR .'/api/aweber_api/aweber_api.php' );
}
