<?php 

//Reject if accessed directly
defined( 'BOP_PLUGIN_DEACTIVATING' ) || die( 'Our survey says: ... X.' );

//Deactivation script - turn off events that might persist despite deactivation; typically caches.

delete_option( 'bop_nav_search_box_item_recd_vers_warn' );
