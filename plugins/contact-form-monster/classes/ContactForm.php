<?php
class ContactForm {

	private $pagesObj;

	function __construct() {

		require_once(YCF_FILES."YcfDefaultValues.php");
		require_once(YCF_CLASSES."YcfFunctions.php");
		require_once(YCF_FILES."YCFContactFormAdminPost.php");
		require_once(YCF_CLASSES_FORM."YcfBuilder.php");
		require_once(YCF_CLASSES."YCFContactFormInstaller.php");
		require_once(YCF_CLASSES."YcfContactFormData.php");
		require_once(YCF_CLASSES."YcfFunctions.php");
		require_once(YCF_CSS_PATH."ycfStyles.php");
		require_once(YCF_JAVASCRIPT_PATH."ycfJavascript.php");
		require_once(YCF_FILES."YcfActions.php");
		require_once(YCF_FILES."YcfAjax.php");
		require_once(YCF_FILES."YcfSendEmail.php");
		require_once(YCF_CLASSES."YcfPages.php");

		$pagesObj =  new YcfPages();
		$ycfFormData = new YcfContactFormData();
		$functionsObj = new YcfFunctions();
		$formBuilderObj = new YcfBuilder();

		$this->pagesObj = $pagesObj;
		$pagesObj->ycfFormData = $ycfFormData;
		$pagesObj->functionsObj = $functionsObj;
		$pagesObj->formBuilderObj = $formBuilderObj;
		
		$this->actions();
	}

	public function activate() {

		YCFContactFormInstaller::install();
	}

	public function uninstall() {

		YCFContactFormInstaller::uninstall();
	}

	public function shortCode() {

		require_once(YCF_CLASSES."YcfShortCode.php");
		$shortCodeObj = new YcfShortCode();
	}

	public function enqueueScripts() {
		
	}

	public function ycfMainMenu() {
		
		$this->pagesObj->mainPage();
	}

	public function addNewButton() {

		$this->pagesObj->addNewButtons();
	}

	public function addNewPage() {
		
		$this->pagesObj->addNewPage();
	}

	public function ycfAdminMenu() {

		add_menu_page("Contact Form", "Contact Form", "manage_options","YcfMenu",array($this, 'ycfMainMenu'));
		add_submenu_page("YcfMenu","Add New","Add New","manage_options",'addNewForm', array($this,'addNewPage'));
	}

	public function ycfHead() {

	}

	public function actions() {
		$actions = new YcfActions();
		add_action("admin_menu", array($this, 'ycfAdminMenu'));
		add_action('wp_head',  array($this, 'shortCode'));
		add_action('wp_head',  array($this, 'ycfHead'));
		register_activation_hook(YCF_PATH.YCF_MAIN_FILE,  array($this, 'activate'));
		register_uninstall_hook(YCF_PATH.YCF_MAIN_FILE,  array('YcfMenu', 'uninstall'));
		add_action('admin_post_update_data', array('DataProcessing', 'expanderUpdateData'));
	}
}