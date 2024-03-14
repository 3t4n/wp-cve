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

class wptsafExtensionGoogleCaptchaAjaxHandle extends wptsafAbstractExtensionAjaxHandle{
	public function settingsSave(){}

	public function blogSettingsRowEdit($id){
		$row = $this->extension->getBlogSettings()->getRow($id);
		$view = new wptsafView();

		if (is_wp_error($row)) {
			$this->response->addError($row);
			$this->response->addJsCallback('wptsafCallback.formHide');
			return $this->response;
		}

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/blog-settigs-row-edit.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'row' => $row
			)
		);
		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.formShowContent');

		return $this->response;
	}

	public function blogSettingsRowSave(){
		$view = new wptsafView();
		$validator = wptsafValidator::getInstance();
		$request = array();
		$errors = array();

		$request['id'] = isset($_POST['id']) ? absint($_POST['id']) : null;

		$request['is_active'] = isset($_POST['is_active'])
			? ((bool)$_POST['is_active'])
			: false;

		$request['key'] = isset($_POST['key']) ? strip_tags(trim($_POST['key'])) : '';
		if ($request['is_active'] && empty($request['key'])) {
			$errors['key'] = __('The field is required for active state', 'wptsaf_security');
		}

		$request['secret_key'] = isset($_POST['secret_key']) ? strip_tags(trim($_POST['secret_key'])) : '';
		if ($request['is_active'] && empty($request['secret_key'])) {
			$errors['secret_key'] = __('The field is required for active state', 'wptsaf_security');
		}

		$errors = array_filter($errors);
		if (!empty($errors)) {
			$response = $view->content(
				$this->extension->getExtensionDir() . 'template/blog-settigs-row-edit.php',
				array(
					'extensionTitle' => $this->extension->getTitle(),
					'errors' => $errors,
					'row' => $request
				)
			);
			$this->response->setResponse($response);
			$this->response->addJsCallback('wptsafCallback.formShowContent');
			return $this->response;
		}

		$result = $this->extension->getBlogSettings()->updateRow($request);
		if (is_wp_error($result)) {
			$this->response->addError($result);
			return $this->response;
		}

		$this->response->addMessage(
			__('Blog Settings is updated', 'wptsaf_security'),
			wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS
		);
		$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));
		$this->response->addJsCallback('wptsafCallback.formHide');
		return $this->response;
	}

	public function blogSettings(){
		$view = new wptsafView();
		$rows = $this->extension->getBlogSettings()->getRows(WPTSAF_LOG_LIMIT);
		$limitMessage = null;

		if (
			WPTSAF_LOG_LIMIT == count($rows)
			&& $this->extension->getBlogSettings()->getRows(1, WPTSAF_LOG_LIMIT)
		) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addWarningMessage(
				$this->extension,
				__('The number of entries in the blog settings log exceeds the display limit', 'wptsaf_security')
			);
			$limitMessage = __('The number of entries in the blog settings log exceeds the display limit', 'wptsaf_security');
		}

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/blog-settings.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'limitMessage' => $limitMessage,
				'rows' => $rows
			)
		);

		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.popupShowContent');

		return $this->response;
	}

	public function testCaptcha($domain, $key){
		$view = new wptsafView();

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/test-captcha.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'domain' => $domain,
				'key' => $key
			)
		);

		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.dialogShowContent');

		return $this->response;
	}

	public function captcha($key){
		?>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<form action="?" method="POST">
			<div class="g-recaptcha" data-sitekey="<?php echo $key; ?>"></div>
		</form>
		<?php
	}
}
