<?php
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {exit();}
delete_option('shtb_adv_setting_opt');
delete_option('shtb_adv_languages');
delete_option('shtb_adv_checkver_stamp');
delete_option('shtb_adv_updated');
?>
