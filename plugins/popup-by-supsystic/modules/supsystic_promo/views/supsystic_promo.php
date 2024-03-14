<?php
class supsystic_promoViewPps extends viewPps {
    public function displayAdminFooter() {
        parent::display('adminFooter');
    }
	public function showAdditionalmainAdminShowOnOptions($popup) {
		$this->assign('promoLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=onexit&utm_campaign=popup'));
		parent::display('additionalmainAdminShowOnOptions');
	}
	public function getOverviewTabContent() {
		framePps::_()->getModule('templates')->loadJqueryUi();

		framePps::_()->getModule('templates')->loadSlimscroll();
		framePps::_()->addScript('admin.overview', $this->getModule()->getModPath(). 'js/admin.overview.js');
		framePps::_()->addStyle('admin.overview', $this->getModule()->getModPath(). 'css/admin.overview.css');
		$this->assign('mainLink', $this->getModule()->getMainLink());
		$this->assign('faqList', $this->getFaqList());
		$this->assign('serverSettings', $this->getServerSettings());
		$this->assign('contactFields', $this->getModule()->getContactFormFields());
		return parent::getContent('overviewTabContent');
	}
	public function getFaqList() {
		return array(
			__('How to open popup on click?', PPS_LANG_CODE)
				=> sprintf(__('With Popup by Supsystic you can show popup in different ways – when page loads, after user scroll page, on exit from site, after user comment. Besides you can show popup by clicking on certain link, button, image or even show it by clicking the Menu item. Just add required code and everything is done!<br />More info you can find here <a target="_blank" href="%s">here</a>', PPS_LANG_CODE), '//supsystic.com/open-popup-on-click/'),
			__('What is A/B testing?', PPS_LANG_CODE)
				=> sprintf(__('A/B testing is one of the easiest ways to increase conversion rates and learn more about your audience!<br />A/B test in Popup plugin involves testing two or more versions of a popup window - an A version (original) and a B versions (the variation) - with live traffic and measuring the effect each version has on your conversion rate.<br />To know more detail – click <a target="_blank" href="%s">here</a>', PPS_LANG_CODE), 'http://supsystic.com/what-is-ab-testing/'),
			__('How to create Subscribe Custom Fields?', PPS_LANG_CODE)
				=> sprintf(__('With PRO version of Popup plugin you can easily customize Subscribe Fields!
Go to Design tab -> Subscribe section -> Subscription Fields block. Here you can add any new fields which you want. Read more <a target="_blank" href="%s">here.</a>', PPS_LANG_CODE), '//supsystic.com/subscribe-custom-fields-builder/'),
			__('How to subscribe to MailChimp?', PPS_LANG_CODE)
				=> __('To subscribe to MailChimp you need enter your MailChimp API key and name of list for subscription. To find your MailChimp API key - follow the instructions below:<br />
				1. Login to your mailchimp account at http://mailchimp.com<br />
				2. From the left main menu, click on your Username, then select "Account" in the flyout menu.<br />
				3. From the account page select "Extras" -> "API Keys".<br />
				4. Your API Key will be listed in the table labeled "Your API Keys".<br />
				5. Copy / Paste your API key into "MailChimp API key" field in PopUp edit screen -> Subscribe section.', PPS_LANG_CODE),
			__('Where to find css code for the pop-up window?', PPS_LANG_CODE)
				=> __('With Popup by Supsystic you can edit CSS style directly from the plugin. <br />
				In WordPress admin area -
go to Popup by Supsystic -> choose a popup, what you need -> click Code tab. <br />
Here you can edit css style of the pop-up window.', PPS_LANG_CODE),
			__('How to embed popup into the page on your site?', PPS_LANG_CODE)
				=> sprintf(__('We have a great solution for you – Build-In Page Popup (PRO feature). You can simply embed popup into the page of your site. Such popup will not disturb or annoy anyone. It will be quiet and peacefully carry out its function on your site.
					Check the example of <a target="_blank" href="%s">Build-In Page Popup.</a>', PPS_LANG_CODE), 'http://supsystic.com/build-page-popup/'),
		);
	}
	public function getMostFaqList() {
		return array(
			__("Where's my subscribers?", PPS_LANG_CODE)
				=> sprintf(__("By default all subscribers add to the WordPress.
					To find your subscribers go to Users tab on the left navigation menu of WordPress admin area.
					Also available subscription to the Aweber, MailChimp, MailPoet <a href='%s' target='_blank'>and other</a>.
					If you want to add another subscription service - just <a href='%s' target='_blank'>contact us</a> and provide URL of the subscription service.", PPS_LANG_CODE), $this->getModule()->getMainLink(). '#subscribe-to-email-popup-settings', $this->getModule()->getContactLink()),
			__("PopUp doesn't appear on the website", PPS_LANG_CODE)
				=> sprintf(__("If you setup you're PopUp properly, and it still doesn't show on the page - there are can be conflict with your WordPress theme or other plugins. <a href='%s' target='_blank'>Contact us</a> with the URL of the webpage you add popup and screenshots / text of the error messages, if you have one - and we will help you resolve your issue.", PPS_LANG_CODE), $this->getModule()->getContactLink()),
		);
	}
	public function getServerSettings() {
		global $wpdb;
		return array(
			      'Operating System' => array('value' => PHP_OS),
            'PHP Version' => array('value' => PHP_VERSION),
            'Server Software' => array('value' => $_SERVER['SERVER_SOFTWARE']),
			      'MySQL' => array('value' =>  $wpdb->db_version()),
            'PHP Allow URL Fopen' => array('value' => ini_get('allow_url_fopen') ? __('Yes', PPS_LANG_CODE) : __('No', PPS_LANG_CODE)),
            'PHP Memory Limit' => array('value' => ini_get('memory_limit')),
            'PHP Max Post Size' => array('value' => ini_get('post_max_size')),
            'PHP Max Upload Filesize' => array('value' => ini_get('upload_max_filesize')),
            'PHP Max Script Execute Time' => array('value' => ini_get('max_execution_time')),
            'PHP EXIF Support' => array('value' => extension_loaded('exif') ? __('Yes', PPS_LANG_CODE) : __('No', PPS_LANG_CODE)),
            'PHP EXIF Version' => array('value' => phpversion('exif')),
            'PHP XML Support' => array('value' => extension_loaded('libxml') ? __('Yes', PPS_LANG_CODE) : __('No', PPS_LANG_CODE), 'error' => !extension_loaded('libxml')),
            'PHP CURL Support' => array('value' => extension_loaded('curl') ? __('Yes', PPS_LANG_CODE) : __('No', PPS_LANG_CODE), 'error' => !extension_loaded('curl')),
		);
	}
	public function getLayeredStylePromo() {
		$this->assign('promoLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=layered&utm_campaign=popup'));
		return parent::getContent('layeredStylePromo');
	}
	public function showWelcomePage() {
		framePps::_()->getModule('templates')->loadJqueryUi();
		framePps::_()->addStyle('admin.welcome', $this->getModule()->getModPath(). 'css/admin.welcome.css');
		$createNewLink = framePps::_()->getModule('options')->getTabUrl('popup_add_new');
		$goToAdminLink = framePps::_()->getModule('options')->getTabUrl('popup');
		$skipTutorLink = uriPps::_(array('baseUrl' => $goToAdminLink, 'skip_tutorial' => 1));
		$this->assign('createNewLink', $this->_makeWelcomeLink( $createNewLink ));
		$this->assign('skipTutorLink', $this->_makeWelcomeLink( $skipTutorLink ));
		$this->assign('faqList', $this->getMostFaqList());
		$this->assign('mainLink', $this->getModule()->getMainLink());
		parent::display('welcomePage');
	}
	private function _makeWelcomeLink($link) {
		return uriPps::_(array('baseUrl' => $link, 'from' => 'welcome-page', 'pl' => PPS_CODE));
	}
	public function getTourHtml() {
		$this->assign('contactFormLink', $this->getModule()->getContactLink());
		$this->assign('finishSiteLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=final_step_b_step&utm_campaign=popup'));
		return parent::getContent('adminTour');
	}
	public function getPluginDeactivation() {
		return parent::getContent('pluginDeactivation');
	}
}
