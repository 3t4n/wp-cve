<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class wptsafAbstractAdminPage{

	public function __construct(){
		if ( is_multisite() ) {
			add_action('network_admin_menu', array($this, 'adminMenu'), 10);
		} else {
			add_action('admin_menu', array($this, 'adminMenu'), 10);
		}
	}

	abstract protected function getMenuSlug();

	abstract public function adminMenu();

	abstract public function content();

	protected function beforeContent(){
		?>
			<div class="wptsaf">
				<div class="container-fluid" role="main">
		<?php
		do_action('wptsaf_security_admin_page_before_content');
	}

	protected function afterContent(){
		do_action('wptsaf_admin_page_after_content');
		?>
				</div>
			</div>

			<div id="wptsaf-popup" class="wptsaf popup" style="display: none">
				<a class="close btn-popup-close" href="#">×</a>
				<div class="content container-fluid"></div>
			</div>

			<div id="wptsaf-popup-form" class="wptsaf popup" style="display: none">
				<a class="close btn-popup-close" href="#">×</a>
				<div class="container-fluid">
					<div class="content"></div>
				</div>
			</div>

			<div id="wptsaf-popup-dialog" class="wptsaf popup" style="display: none">
				<a class="close btn-popup-close" href="#">×</a>
				<div class="container-fluid">
					<div class="content"></div>
				</div>
			</div>

			<div id="wptsaf-popup-message" class="wptsaf popup" style="display: none">
				<a class="close btn-popup-close" href="#">×</a>
				<div class="container-fluid">
					<div class="content"></div>
					<div class="ln_solid"></div>
					<div class="buttons">
						<button class="btn btn-default pull-right btn-popup-close">
							<?php _e('Close', 'wptsaf_security'); ?>
						</button>
					</div>
				</div>
			</div>

			<div id="wptsaf-popup-loader" class="wptsaf popup" style="display: none">
				<div class="popup-spinner">
					<img src="<?php echo admin_url('/images/spinner-2x.gif') ?>" />
				</div>
			</div>
		<?php
	}
}
