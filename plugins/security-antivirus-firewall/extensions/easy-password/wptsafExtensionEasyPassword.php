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

class wptsafExtensionEasyPassword extends wptsafAbstractExtension{

	protected static $instance;

	public function __construct(){
		$this->name = 'easy-password';
		$this->title = __('Easy Password', 'wptsaf_security');
		$this->description = __("Simple password it's one of the most common security problems. Do not use simple passwords and protect your website from simple passwords of another users. Using strong passwords lowers overall risk of a security breach. The rate at which an attacker can submit guessed passwords to the website is a key factor in determining security. We have also brute force monitor to protect website  from such guessing", 'wptsaf_security');

		parent::__construct();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init(){
		parent::init();

		if (is_admin()) {
			add_action('admin_head', array($this, 'adminHead'));
			add_action('wp_loaded', array($this, 'actionCreateUser'));
			add_action('personal_options_update', array($this, 'checkPasswordStrength'));
			add_action('edit_user_profile_update', array($this, 'checkPasswordStrength'));
		}
	}

	public function adminHead(){
		?>
		<style>
			body.user-edit-php .pw-weak,
			body.profile-php .pw-weak,
			body.user-new-php .pw-weak {
				display: none !important;
			}
		</style>
		<?php
	}

	public function actionCreateUser(){
		if (!isset($_REQUEST['action']) || 'createuser' != $_REQUEST['action']) {
			return;
		}

		$this->checkPasswordStrength();
	}

	public function checkPasswordStrength(){
		$isPwWeak = isset($_POST['pw_weak']) && 'on' == $_POST['pw_weak'];

		if ($isPwWeak) {
			$this->getLog()->insertRow(
				array(
					'user_id' => isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0
				)
			);
			wp_die('You password is too easy. Please, change it.');
		}
	}
}
