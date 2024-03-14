<?php

class MantisAdOptions
{
	public function __construct()
	{
		add_action('admin_menu', array($this, 'menu'));
		add_action('admin_notices', array($this, 'setup'));
		add_action('admin_init', array($this, 'settings'));
	}

	public function setup()
	{
		$isoptions = !isset($_GET['page']) || $_GET == 'mantis_ad_options';
		$isset = get_option('mantis_site_id') || get_option('mantis_advertiser_id');

		if ($isoptions && !$isset):
			?>
			<div class="error">
				<p>
					You have not configured your MANTIS settings yet.
					<a href="options-general.php?page=mantis_ad_options">Click here</a> to update your information
				</p>
			</div>
		<?php
		endif;
	}

	public function settings()
	{
		register_setting('mantis-settings', 'mantis_site_id');
        register_setting('mantis-settings', 'mantis_advertiser_id');
		register_setting('mantis-settings', 'mantis_async');
		register_setting('mantis-settings', 'mantis_always');
        register_setting('mantis-settings', 'mantis_recommend');
        register_setting('mantis-settings', 'mantis_after');
	}

	public function menu()
	{
		add_options_page('MANTIS Ad Network', 'MANTIS', 'manage_options', 'mantis_ad_options', array(
			$this,
			'page'
		));
	}

	public function page()
	{
		require_once(dirname(__FILE__) . '/html/settings.php');
	}
}

new MantisAdOptions();

?>
