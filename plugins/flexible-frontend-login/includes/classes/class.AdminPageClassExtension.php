<?php

class AdminPageClassExtension extends BF_Admin_Page_Class
{
	
	public function test_db_access()
	{
		global $wpdb;
		
		$result = $wpdb->query(
			"
			SELECT FROM $wpdb->options
			WHERE option_name = 'flexible_frontend_login'
			"
		);
		echo $result;
		exit;
	}
}