<?php
class YCFContactFormInstaller
{
	public static function createTables($blogsId)
	{
		global $wpdb;
		$ycfContactFormBase = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."ycf_form (
			`form_id` int(11) NOT NULL AUTO_INCREMENT,
			`type` varchar(255) NOT NULL,
			`title` varchar(255) NOT NULL,
			`options` text NOT NULL,
			PRIMARY KEY (form_id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
		$ycfContactFormFields = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."ycf_fields (
			`field_id` int(11) NOT NULL AUTO_INCREMENT,
			`form_id` int(11) NOT NULL,
			`fields_data` TEXT NOT NULL,
			PRIMARY KEY (field_id),
			CONSTRAINT fk_". $wpdb->prefix.$blogsId."ycf_fields FOREIGN KEY (form_id)
			REFERENCES ". $wpdb->prefix.$blogsId."ycf_form(form_id)
			ON DELETE CASCADE ON UPDATE CASCADE
			)  ENGINE=InnoDB DEFAULT CHARSET=utf8; ";

		$wpdb->query($ycfContactFormBase);
		$wpdb->query($ycfContactFormFields);
	}

	public static function install()
	{
		$obj = new self();
		$obj->createTables("");
		if(is_multisite()) {
			$sites = wp_get_sites();
			foreach($sites as $site) {
				$blogsId = $site['blog_id']."_";
				global $wpdb;
				$obj->createTables($blogsId);
			}
		}
	}

	public static function uninstall()
	{
		$obj = new self();
		$obj->uninstallTables("");
		if (is_multisite()) {
			$sites = wp_get_sites();
			foreach($sites as $site) {
				$blogsId = $site['blog_id']."_";
				$obj->uninstallTables($blogsId);
			}
		}
	}

	public function uninstallTables($blogsId)
	{
		global $wpdb;
		$ycfContactFormTable = $wpdb->prefix."ycf_form";
		$ycfContactFormSql = "DROP TABLE ". $ycfContactFormTable;
		$wpdb->query($ycfContactFormSql);	
	}
}