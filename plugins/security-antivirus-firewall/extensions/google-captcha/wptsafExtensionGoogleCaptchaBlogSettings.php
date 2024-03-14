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

class wptsafExtensionGoogleCaptchaBlogSettings extends wptsafAbstractLog{

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		global $wpdb;
		$this->table = WPTSAF_DB_PREFIX . 'google_captcha_blog_settings';
		$this->fieldList = array(
			'blog_id',
			'is_active',
			'key',
			'secret_key',
		);
	}

	public function init(){
		$blogs = array();

		if (is_multisite()) {
			foreach (wp_get_sites() as $blog) {
				$blogs[$blog['blog_id']] = $blog;
			}
		} else {
			$blogs[1] = array('blog_id' => 1);
		}

		foreach ($this->getRows(WPTSAF_LOG_LIMIT) as $blogSetting) {
			if (isset($blogs[$blogSetting['blog_id']])) {
				unset($blogs[$blogSetting['blog_id']]);
			}
		}
		
		foreach ($blogs as $blog) {
			$this->insertRow(array(
				'blog_id' => $blog['blog_id'],
				'is_active' => 0,
				'key' => '',
				'secret_key' => ''
			));
		}
	}

	public function getRowByBlogId($blogId){
		global $wpdb;
		$row = $wpdb->get_row($wpdb->prepare(
			"SELECT * FROM {$this->table} where blog_id = '%d'",
			absint($blogId)
		), ARRAY_A);

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'wptsaf_security')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
		}

		if (!$row) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				sprintf(__('Could not find row by id #%d', 'wptsaf_security'), absint($blogId))
			);
			return new WP_Error(
				'wptsaf_security',
				sprintf(__('Could not find blog by id #%d', 'wptsaf_security'), absint($blogId))
			);
		}

		return $row;
	}

	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `blog_id` int(11) NOT NULL,
				 `is_active` tinyint(1) NOT NULL,
				 `key` varchar(50) NOT NULL,
				 `secret_key` varchar(50) NOT NULL,
				 PRIMARY KEY (`id`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}

	public function rotate(){}
}
