<?php 

function pmlc_admin_notices() {
	// notify user if GeoIPCountry database is empty
	$geoip = new PMLC_GeoIPCountry_List();
	if (0 == $geoip->countBy()) {
		?>
		<div class="error"><p>
			<?php printf(
					__('<b>%s Plugin</b>: GeoIPCountry database is empty. Please reactivate the plugin and if the error still appears, you should manually import it from %s into `%s` database table.', 'pmlc_plugin'),
					PMLC_Plugin::getInstance()->getName(),
					'<a href="http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip">GeoIPCountryCSV.zip</a>',
					PMLC_Plugin::getInstance()->getTablePrefix() . 'geoipcountry'
			) ?>
		</p></div>
		<?php
	}
	
	$plugin_screen = PMLC_Plugin::getInstance()->getAdminCurrentScreen();
	if ( ! PMLC_Plugin::getInstance()->isLicensed() and ! ($plugin_screen and 'PMLC_Admin_Home' == $plugin_screen->id)) { // show license related warning if necessary
		?>
		<div class="error"><p>
			<?php if (PMLC_Plugin::getInstance()->getLicense()): ?>
				<?php printf(__('<b>%s</b> admin panel is not functional since recurrent license check operation has failed. Please make sure <a href="%s">entered</a> license key is valid and if it is, contact <a href="%s">support</a> for assistance.', 'pmlc_plugin'), PMLC_Plugin::getInstance()->getName(), add_query_arg(array('page' => 'pmlc-admin-settings'), admin_url('admin.php')), PMLC_Plugin::getInstance()->getPluginURI()) ?>
			<?php else: ?>
				<?php printf(__('<b>%s</b> is not yet functional. Please <a href="%s">enter</a> proper license key to activate the plugin.', 'pmlc_plugin'), PMLC_Plugin::getInstance()->getName(), add_query_arg(array('page' => 'pmlc-admin-settings'), admin_url('admin.php'))) ?>
			<?php endif ?>
		</p></div>
		<?php
	}
	
	$input = new PMLC_Input();
	$messages = $input->get('pmlc_nt', array());
	if ($messages) {
		is_array($messages) or $messages = array($messages);
		foreach ($messages as $type => $m) {
			in_array((string)$type, array('updated', 'error')) or $type = 'updated';
			?>
			<div class="<?php echo $type ?>"><p><?php echo $m ?></p></div>
			<?php 
		}
	}
	
}