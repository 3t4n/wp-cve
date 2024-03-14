<?php
/**
 * uninstalling simple access control
 * remove all settings and meta data seved by simple access control 
 */
if(! defined('WP_UNINSTALL_PLUGIN'))exit(); // get out if not called by wordpress uninstall

delete_option('sac_locked_text');
delete_option('sac_hide_menus');
delete_post_meta_by_key('sac_locked');

