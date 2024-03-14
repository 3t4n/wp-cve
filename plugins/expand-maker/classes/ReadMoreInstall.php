<?php
class ReadMoreInstall
{
	public static function createTables($blogId = '') {

		global $wpdb;
		$dbKey = $wpdb->prefix.$blogId;
		$createTableStr = "CREATE TABLE IF NOT EXISTS ". sanitize_text_field($dbKey);
		$dbEngineStr = " ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$expanderDataBase = $createTableStr."expm_maker (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`type` varchar(255) NOT NULL,
			`expm-title` varchar(255) NOT NULL,
			`button-width` varchar(255) NOT NULL,
			`button-height` varchar(255) NOT NULL,
			`animation-duration` varchar(255) NOT NULL,
			`options` text NOT NULL,
			PRIMARY KEY (id)
		)"." ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$expmPages = $createTableStr."expm_maker_pages (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`post_id` int(11) NOT NULL,
			`button_id` int(11) NOT NULL,
			`options` text NOT NULL,
			PRIMARY KEY (id)
		)"." ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		$hiddenContent = $createTableStr."expm_hiiden_content (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`rm_id` int(11) NOT NULL,
			`rm_key` varchar(255) NOT NULL,
			`content` LONGTEXT NOT NULL,
			PRIMARY KEY (id),
			FOREIGN KEY (rm_id)
        REFERENCES ".sanitize_text_field($dbKey)."expm_maker (id)
        ON DELETE CASCADE
		)"." ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$searchReplace = $createTableStr.YRM_FIND_TABLE." (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(255) NOT NULL,
			`enable` int(2) NOT NULL,
			`options` LONGTEXT NOT NULL,
			PRIMARY KEY (id)
		)"." ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$wpdb->query($expanderDataBase);
		$wpdb->query($expmPages);
		$wpdb->query($hiddenContent);
		$wpdb->query($searchReplace);
	}

	public static function install() {

		self::createTables();
		YrmShowReviewNotice::setInitialDates();
		update_option('EXPM_VERSION', EXPM_VERSION);
		if(is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;
			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}
			foreach($sites as $site) {
				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}
				if($blogId != 1) {
					self::createTables($blogId);
				}
			}
		}
	}

    public static function deleteInitialDates() {
        delete_option('YrmUsageDays');
        delete_option('YrmInstallDate');
        delete_option('YrmShowNextTime');
    }

	public static function uninstall() {

		if(!get_option('yrm-delete-data')) {
			return false;
		}
		self::deleteInitialDates();
		$obj = new self();
		$obj->uninstallTables();
		if (is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;
			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}
			foreach($sites as $site) {
				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}
				$obj->uninstallTables($blogId);
			}
		}
	}

	public function uninstallTables($blogId = '') {

		if(YRM_PKG == YRM_FREE_PKG) {
			return false;
		}
		global $wpdb;
		$expanderTable = $wpdb->prefix.$blogId."expm_maker";
		$expmSql = "DROP TABLE ". $expanderTable;

		$expanderPagesTable = $wpdb->prefix.$blogId."expm_maker_pages";
		$expmPagesSql = "DROP TABLE ". $expanderPagesTable;
		
		$hiddenContentTable = $wpdb->prefix.$blogId."expm_hiiden_content";
		$hiddenContentTableSql = "DROP TABLE ". $hiddenContentTable;

		$farTable = $wpdb->prefix.$blogId.YRM_FIND_TABLE;
		$farTableSql = "DROP TABLE ". $farTable;
		
		$wpdb->query($expmPagesSql);
		$wpdb->query($expmSql);
		$wpdb->query($hiddenContentTableSql);
		$wpdb->query($farTableSql);
		return true;
	}
	
	public static function udateToNewVersion() {
		
		global $wpdb;

		$results = $wpdb->get_results("SELECT * FROM ".sanitize_text_field($wpdb->prefix)."expander_maker ORDER BY ID DESC", ARRAY_A);
		if(!empty($results[0])) {
			$results = $results[0];
		}
		
		$width = '100px';
		$height = '32px';
		$duration = 1000;
		$options = array();
		
 		if(!empty($results['width'])) {
			$width = $results['width'];
		}
		if(!empty($results['height'])) {
			$height = $results['height'];
		}
		if(!empty($results['duration'])) {
			$duration = $results['duration'];
		}
		$options = $results['options'];
		$title = 'read more';

		$width = sanitize_text_field($width);
		$height = sanitize_text_field($height);
		$duration = sanitize_text_field($duration);
	
		$data = array(
			'type' => 'button',
			'expm-title' => $title,
			'button-width' => $width,
			'button-height' => $height,
			'animation-duration' => $duration,
			'options' => $options
		);

		$format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
		);

	    $wpdb->insert($wpdb->prefix.'expm_maker', $data, $format);
	}
}