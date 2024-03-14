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

class wptsafAdminPageExtensions extends wptsafAbstractAdminPage{
	
	const MENU_SLUG = 'wptsaf_security_extensions';
	//. '<span class="update-plugins count-2"><span class="plugin-count">2</span></span>'
	public function adminMenu(){
		add_menu_page(
			__('Security S.A.F.', 'security-antivirus-firewall'),
			__('Security S.A.F.', 'security-antivirus-firewall'),
			WPTSAF_ACCESS_LEVEL,
			self::MENU_SLUG,
			array($this, 'content'),
			'none',
			25
		);
	}

	protected function getMenuSlug(){
		return self::MENU_SLUG;
	}

	public function content(){
		$exceptExtensions = array(
			'file-change',
			'malware-scanner'
		);

		$this->beforeContent();
		?>	
				<div class="row ">
					<div class="wptsaf_panels">
						<div class="col-md-6 col-sm-6 col-xs-12 wptsaf_panel-sizer"></div>
				<?php $count = 0; ?>
				<?php foreach (wptsafSecurity::getInstance()->getExtensions() as $extension) : ?>
					<?php
						if (in_array($extension->getName(), $exceptExtensions)) {
							continue;
						}
					?>
					<?php if ($widget = $extension->createWidget()) : ?>
						<div class="col-md-6 col-sm-6 col-xs-12  wptsaf_panel">
							<div class="x_panel extension-widget <?php echo $extension->getName(); ?>">
								<?php echo $widget->content(); ?>
							</div>
						</div>

						<?php
							$count++;
							if (0 == $count % 2) {}
						?>
					<?php endif; ?>
				<?php endforeach; ?>
					</div>
				</div>
		<?php

		$this->afterContent();
	}
}
