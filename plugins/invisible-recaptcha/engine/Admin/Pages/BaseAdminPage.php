<?php
namespace InvisibleReCaptcha\Admin\Pages;

use InvisibleReCaptcha\MchLib\Plugin\MchBaseAdminPage;

abstract class BaseAdminPage extends MchBaseAdminPage
{

	public function __construct($pageMenuTitle, $renderModulesInSubTabs = true)
	{
		parent::__construct($pageMenuTitle, \InvisibleReCaptcha::PLUGIN_SLUG, $renderModulesInSubTabs);
		$this->setPageLayoutColumns(2);
	}

	public function renderPageContent()
	{
		parent::renderPageContent();
	}


	public function registerPageMetaBoxes()
	{

		parent::registerPageMetaBoxes();

		if ( $this->getPageLayoutColumns() <= 1 ) {
			return;
		}

		add_meta_box(
				"invre-help-metabox",
				__( 'Need help? Have questions...?', 'invisible-recaptcha' ),
				array( $this, 'renderNeedHelpMetaBox' ),
				$this->getAdminScreenId(),
				'side',
				'core',
				null
		);

	}

	public function renderNeedHelpMetaBox()
	{
		echo '<div><img class="logo-help" src="https://ps.w.org/invisible-recaptcha/assets/icon-128x128.png" /></div>';
		echo '<p class="contact-help"> <a class = "button-primary" href="https://ultracommunity.com/forums/forum/invisible-recaptcha/" target="_blank">Get In Touch With Us</a></p>';
	}


	public function getAdminUrl($appendAddNewQueryString = false)
	{
		if(!$appendAddNewQueryString) {
			return parent::getAdminUrl();
		}

		return esc_url(add_query_arg(array('add-new' => 1), parent::getAdminUrl()));
	}

}