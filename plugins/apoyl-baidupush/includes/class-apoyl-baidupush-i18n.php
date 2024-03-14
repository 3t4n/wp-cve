<?php
/*
 * @link       http://www.apoyl.com/
 * @since      1.0.0
 * @package    Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/includes
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Apoyl_Baidupush_i18n {


	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'apoyl-baidupush',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
