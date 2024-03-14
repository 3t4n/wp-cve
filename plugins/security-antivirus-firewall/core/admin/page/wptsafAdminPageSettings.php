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

class wptsafAdminPageSettings extends wptsafAbstractAdminPage{
	const MENU_SLUG = 'wptsaf_security_settings';

	protected function getMenuSlug(){
		return self::MENU_SLUG;
	}

	public function adminMenu(){
		add_submenu_page(
			wptsafAdminPageExtensions::MENU_SLUG,
			__('Settings', 'security-antivirus-firewall'),
			__('Settings', 'security-antivirus-firewall'),
			WPTSAF_ACCESS_LEVEL,
			self::MENU_SLUG,
			array($this, 'content')
		);
	}

	public function content(){
		$this->beforeContent();
		?>
			<div class="row">
				<div class="col-md-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2><?php echo __('SAF Security: Settings', 'security-antivirus-firewall'); ?></h2>
							<div class="clearfix"></div>
						</div>
						<div id="wptsaf_security_settings" class="x_content">
							<?php
								$extension = wptsafSecurity::getInstance();
								$view = new wptsafView();
								$view->render(
									$extension->getExtensionDir() . 'template/settings.php',
									array(
										'extensionName' => $extension->getName(),
										'extensionTitle' => $extension->getTitle(),
										'errors' => array(),
										'settings' => $extension->getSettings()->get()
									)
								);

							?>
						</div>
					</div>
				</div>
			</div>
			<script>
				(function ($) {
					$(document).ready(function () {
						$('#wptsaf_security_settings').on('submit', 'form', function () {
							var $form = $(this),
								data = {};

							$.each($form.serializeArray(), function (i, field) {
								data[field.name] = field.value;
							});

							wptsafDataAction.processAction(
								$form.closest('div'),
								'action=wptsaf_security&extension=wptsaf-security&method=settingsSave',
								data,
								true,
								function ($target, response) {
									$target.html(response);
								}
							);

							return false;
						});
					});
				})(jQuery);
			</script>
		<?php
		$this->afterContent();
	}
}
