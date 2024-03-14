<?php

/**
 * Class that handle all admin notices
 *
 * @since      1.1
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class GeoTarget_Notices {


	/**
	 * The version of this plugin.
	 *
	 * @since    1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $version ) {

		$this->version = $version;
		if( isset( $_GET['geot_notice'])){
			update_option('geot_'.esc_attr($_GET['geot_notice']), true);
		}
	}


	public function rate_plugin(){
		?><div class="updated notice">
		<h3>GeoTargeting Plugin!</h3>
			<p><?php echo sprintf(__( 'We noticed that you have been using our plugin for a while and we would like to ask you a little favour. If you are happy with it and can take a minute please <a href="%s" target="_blank">leave a nice review</a> on WordPress', 'wsi' ), 'https://wordpress.org/support/view/plugin-reviews/wp-social-invitations?filter=5' ); ?></p>
		<ul>
			<li><?php echo sprintf(__('<a href="%s" target="_blank">Leave a nice review</a>'),'https://wordpress.org/support/view/plugin-reviews/geotargeting?filter=5');?></li>
			<li><?php echo sprintf(__('<a href="%s">No, thanks</a>'), '?geot_notice=rate_plugin');?></li>
		</ul>
		</div><?php
	}

	public function install_geot_maxmind(){
		?><div class="updated notice">
		<h3>GeoTargeting Plugin!</h3>
			<p><?php echo sprintf(__( 'To keep your Maxmind Geoip database up to date you need to install <a href="%s" target="_blank">Geot Maxmind</a> plugin. This will automatically update the database every month.', 'geot' ), 'https://github.com/timersys/geot-maxmind/archive/master.zip' ); ?></p>
			<p><?php  _e( 'It requires PHP 5.6', 'geot');?></p>
		<ul>
			<li><?php echo sprintf(__('<a href="%s">Dismiss notice</a>'), '?geot_notice=install_geot_maxmind');?></li>
		</ul>
		</div><?php
	}
}