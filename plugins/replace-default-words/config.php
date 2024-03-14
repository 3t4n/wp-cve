<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
$table=$wpdb->prefix."replace_mandegarweb";
if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
$databaser=$wpdb->query("CREATE TABLE IF NOT EXISTS `$table` (
  `new` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `befor` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `user` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `id` int(255) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;");
}
?>