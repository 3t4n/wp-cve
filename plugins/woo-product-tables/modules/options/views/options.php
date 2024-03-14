<?php
class OptionsViewWtbp extends ViewWtbp {
	private $_news = array();
	public function getNewFeatures() {
		$res = array();
		$readmePath = WTBP_DIR . 'readme.txt';
		if (file_exists($readmePath)) {
			$readmeContent = @file_get_contents($readmePath);
			if (!empty($readmeContent)) {
				$matchedData = '';
				if (preg_match('/= ' . WTBP_VERSION . ' =(.+)=.+=/isU', $readmeContent, $matches)) {
					$matchedData = $matches[1];
				} elseif (preg_match('/= ' . WTBP_VERSION . ' =(.+)/is', $readmeContent, $matches)) {
					$matchedData = $matches[1];
				}
				$matchedData = trim($matchedData);
				if (!empty($matchedData)) {
					$res = array_map('trim', explode("\n", $matchedData));
				}
			}
		}
		return $res;
	}
	public function getAdminPage() {
		if (!FrameWtbp::_()->getModule('wootablepress')->isWooCommercePluginActivated()) {
			return;
		}
		$tabs = $this->getModule()->getTabs();
		$activeTab = $this->getModule()->getActiveTab();
		$content = 'No tab content found - ERROR';
		if (isset($tabs[ $activeTab ]) && isset($tabs[ $activeTab ]['callback'])) {
			$content = call_user_func($tabs[ $activeTab ]['callback']);
		}
		$activeParentTabs = array();
		foreach ($tabs as $tabKey => $tab) {
			if ($tabKey == $activeTab && isset($tab['child_of'])) {
				$activeTab = $tab['child_of'];
			}
		}
		FrameWtbp::_()->addJSVar('adminOptionsWtbp', 'wtbpActiveTab', $activeTab);
		FrameWtbp::_()->addJSVar('adminOptionsWtbp', 'wtbpNonce', wp_create_nonce('wtbp-save-nonce'));
		$wootableView = FrameWtbp::_()->getModule('wootablepress')->getView();
		$this->assign('tabs', $tabs);
		$this->assign('activeTab', $activeTab);
		$this->assign('content', $content);
		$this->assign('mainUrl', $this->getModule()->getTabUrl());
		$this->assign('activeParentTabs', $activeParentTabs);
		$this->assign('breadcrumbs', FrameWtbp::_()->getModule('admin_nav')->getView()->getBreadcrumbs());
		$this->assign('mainLink', FrameWtbp::_()->getModule('promo')->getMainLink());
		$this->assign('search_table', $wootableView->getLeerSearchTable());
		$this->assign('authors_html', $wootableView->getAuthorsHtml());
		$this->assign('categories_html', $wootableView->getTaxonomyHierarchyHtml());
		$this->assign('tags_html', $wootableView->getTaxonomyHierarchyHtml(0, '', 'product_tag'));
		$this->assign('attributes_html', $wootableView->getAttributesHierarchy());

		parent::display('optionsAdminPage');
	}
	public function sortOptsSet( $a, $b ) {
		if ($a['weight'] > $b['weight']) {
			return -1;
		}
		if ($a['weight'] < $b['weight']) {
			return 1;
		}
		return 0;
	}
	public function getTabContent() {
		FrameWtbp::_()->addScript('wtbp.admin.mainoptions', $this->getModule()->getModPath() . 'js/admin.mainoptions.js');
		return parent::getContent('optionsAdminMain');
	}
	public function serverSettings() {
		global $wpdb;
		$this->assign('systemInfo', array(
			'Operating System' => array('value' => PHP_OS),
			'PHP Version' => array('value' => PHP_VERSION),
			'Server Software' => array('value' => ( empty($_SERVER['SERVER_SOFTWARE']) ? '' : sanitize_text_field($_SERVER['SERVER_SOFTWARE']) )),
			'MySQL' => array('value' =>  $wpdb->db_version()),
			'PHP Allow URL Fopen' => array('value' => ini_get('allow_url_fopen') ? 'Yes' : 'No'),
			'PHP Memory Limit' => array('value' => ini_get('memory_limit')),
			'PHP Max Post Size' => array('value' => ini_get('post_max_size')),
			'PHP Max Upload Filesize' => array('value' => ini_get('upload_max_filesize')),
			'PHP Max Script Execute Time' => array('value' => ini_get('max_execution_time')),
			'PHP EXIF Support' => array('value' => extension_loaded('exif') ? 'Yes' : 'No'),
			'PHP EXIF Version' => array('value' => phpversion('exif')),
			'PHP XML Support' => array('value' => extension_loaded('libxml') ? 'Yes' : 'No', 'error' => !extension_loaded('libxml')),
			'PHP CURL Support' => array('value' => extension_loaded('curl') ? 'Yes' : 'No', 'error' => !extension_loaded('curl')),
		));
		return parent::display('_serverSettings');
	}
	public function getSettingsTabContent() {
		FrameWtbp::_()->addScript('wtbp.admin.settings', $this->getModule()->getModPath() . 'js/admin.settings.js');
		FrameWtbp::_()->getModule('templates')->loadJqueryUi();

		$options = FrameWtbp::_()->getModule('options')->getAll();
		$this->assign('options', $options);
		$this->assign('exportAllSubscribersUrl', UriWtbp::mod('subscribe', 'getWpCsvList'));
		return parent::getContent('optionsSettingsTabContent');
	}
}
