<?php
class PromoWtbp extends ModuleWtbp {
	private $_mainLink = '';
	private $_minDataInStatToSend = 20;	// At least 20 points in table shuld be present before send stats
	private $_assetsUrl = '';
	public function __construct( $d ) {
		parent::__construct($d);
		$this->getMainLink();
		DispatcherWtbp::addFilter('jsInitVariables', array($this, 'addMainOpts'));
	}
	public function init() {
		parent::init();
		add_action('admin_footer', array($this, 'displayAdminFooter'), 9);
		if (is_admin()) {
			add_action('init', array($this, 'checkWelcome'));
			add_action('init', array($this, 'checkStatisticStatus'));
			add_action('admin_footer', array($this, 'checkPluginDeactivation'));
		}
		$this->weLoveYou();
		DispatcherWtbp::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		DispatcherWtbp::addFilter('subDestList', array($this, 'addSubDestList'));
		DispatcherWtbp::addAction('beforeSaveOpts', array($this, 'checkSaveOpts'));
		DispatcherWtbp::addFilter('showTplsList', array($this, 'checkProTpls'));
		add_action('admin_notices', array($this, 'checkAdminPromoNotices'));
		// Admin tutorial
		add_action('admin_enqueue_scripts', array( $this, 'loadTutorial'));
	}
	public function checkAdminPromoNotices() {
		// It's not normal setup for now - back here and make it correct when plugin will be on WP
		return;
		if (!FrameWtbp::_()->isAdminPlugOptsPage()) {
			// Our notices - only for our plugin pages for now
			return;
		}
		$notices = array();
		// Start usage
		$startUsage = (int) FrameWtbp::_()->getModule('options')->get('start_usage');
		$currTime = time();
		$day = 24 * 3600;
		if ($startUsage) {	// Already saved
			/* translators: %s: plugin name */
			$rateMsg = '<h3>' . sprintf(esc_html__('Hey, I noticed you just use %s over a week – that’s awesome!', 'woo-product-tables'), WTBP_WP_PLUGIN_NAME) . '</h3><p>' . esc_html__('Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', 'woo-product-tables') . '</p>';
			$rateMsg .= '<p><a href="https://wordpress.org/support/view/plugin-reviews/popup-by-supsystic?rate=5#postform" target="_blank" class="button button-primary" data-statistic-code="done">' . esc_html__('Ok, you deserve it', 'woo-product-tables') . '</a>
			<a href="#" class="button" data-statistic-code="later">' . esc_html__('Nope, maybe later', 'woo-product-tables') . '</a>
			<a href="#" class="button" data-statistic-code="hide">' . esc_html__('I already did', 'woo-product-tables') . '</a></p>';
			/* translators: %s: plugin name */
			$enbPromoLinkMsg = '<h3>' . sprintf(esc_html__('More then eleven days with our %s plugin - Congratulations!', 'woo-product-tables'), WTBP_WP_PLUGIN_NAME) . '</h3>';
			/* translators: %s: plugin url */
			$enbPromoLinkMsg .= '<p>' . sprintf(esc_html__('On behalf of the entire %s company I would like to thank you for been with us, and I really hope that our software helped you.', 'woo-product-tables'), '<a href="https://woobewoo.com" target="_blank">woobewoo.com</a>') . '</p>';
			$enbPromoLinkMsg .= '<p>' . esc_html__('And today, if you want, - you can help us. This is really simple - you can just add small promo link to our site under your PopUps. This is small step for you, but a big help for us! Sure, if you don\'t want - just skip this and continue enjoy our software!', 'woo-product-tables') . '</p>';
			$enbPromoLinkMsg .= '<p><a href="#" class="button button-primary" data-statistic-code="done">' . esc_html__('Ok, you deserve it', 'woo-product-tables') . '</a>
				<a href="#" class="button" data-statistic-code="later">' . esc_html__('Nope, maybe later', 'woo-product-tables') . '</a>
				<a href="#" class="button" data-statistic-code="hide">' . esc_html__('Skip', 'woo-product-tables') . '</a></p>';
			/* translators: 1: url, 2: end url */
			$enbStatsMsg = '<p>' . sprintf(esc_html__('You can help us improve our plugin - by %1$s enabling Usage Statistics %2$s. We will collect only our plugin usage statistic data - to understand Your needs and make our solution better for You.', 'woo-product-tables'), '<a href="' . esc_url(FrameWtbp::_()->getModule('options')->getTabUrl('settings')) . '" data-statistic-code="hide" class="button button-primary wtbpEnbStatsAdBtn">', '</a>') . '</p>';
			/* translators: 1: url, 2: end url */
			$checkOtherPlugins = '<p>' . sprintf(esc_html__('Check out %1$s our other Plugins%2$s! Years of experience in WordPress plugins developers made those list unbreakable!', 'woo-product-tables'), '<a href="' . esc_url(FrameWtbp::_()->getModule('options')->getTabUrl('featured-plugins')) . '" target="_blank" class="button button-primary" data-statistic-code="hide">', '</a>') . '</p>';
			$notices = array(
				'rate_msg' => array('html' => $rateMsg, 'show_after' => 7 * $day),
				'enb_promo_link_msg' => array('html' => $enbPromoLinkMsg, 'show_after' => 11 * $day),
				'enb_stats_msg' => array('html' => $enbStatsMsg, 'show_after' => 5 * $day),
			);
			foreach ($notices as $nKey => $n) {
				if ($currTime - $startUsage <= $n['show_after']) {
					unset($notices[ $nKey ]);
					continue;
				}
				$done = (int) FrameWtbp::_()->getModule('options')->get('done_' . $nKey);
				if ($done) {
					unset($notices[ $nKey ]);
					continue;
				}
				$hide = (int) FrameWtbp::_()->getModule('options')->get('hide_' . $nKey);
				if ($hide) {
					unset($notices[ $nKey ]);
					continue;
				}
				$later = (int) FrameWtbp::_()->getModule('options')->get('later_' . $nKey);
				if ($later && ( $currTime - $later ) <= 2 * $day) {	// remember each 2 days
					unset($notices[ $nKey ]);
					continue;
				}
				if ('enb_promo_link_msg' == $nKey && (int) FrameWtbp::_()->getModule('options')->get('add_love_link')) {
					unset($notices[ $nKey ]);
					continue;
				}
			}
		} else {
			FrameWtbp::_()->getModule('options')->getModel()->save('start_usage', $currTime);
		}
		if (!empty($notices)) {
			if (isset($notices['rate_msg']) && isset($notices['enb_promo_link_msg']) && !empty($notices['enb_promo_link_msg'])) {
				unset($notices['rate_msg']);	// Show only one from those messages
			}
			$html = '';
			foreach ($notices as $nKey => $n) {
				$this->getModel()->saveUsageStat($nKey . '.show', true);
				$html .= '<div class="updated notice is-dismissible supsystic-admin-notice" data-code="' . esc_attr($nKey) . '">' . $n['html'] . '</div>';
			}
			HtmlWtbp::echoEscapedHtml($html);
		}
	}
	public function addAdminTab( $tabs ) {
		return $tabs;
	}
	public function addSubDestList( $subDestList ) {
		if (!$this->isPro()) {
			$subDestList = array_merge($subDestList, array(
				'constantcontact' => array('label' => esc_html__('Constant Contact - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'campaignmonitor' => array('label' => esc_html__('Campaign Monitor - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'verticalresponse' => array('label' => esc_html__('Vertical Response - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'sendgrid' => array('label' => esc_html__('SendGrid - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'get_response' => array('label' => esc_html__('GetResponse - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'icontact' => array('label' => esc_html__('iContact - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'activecampaign' => array('label' => esc_html__('Active Campaign - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'mailrelay' => array('label' => esc_html__('Mailrelay - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'arpreach' => array('label' => esc_html__('arpReach - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'sgautorepondeur' => array('label' => esc_html__('SG Autorepondeur - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'benchmarkemail' => array('label' => esc_html__('Benchmark - PRO', 'woo-product-tables'), 'require_confirm' => true),
				'infusionsoft' => array('label' => esc_html__('InfusionSoft - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'salesforce' => array('label' => esc_html__('SalesForce - Web-to-Lead - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'convertkit' => array('label' => esc_html__('ConvertKit - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'myemma' => array('label' => esc_html__('Emma - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'sendinblue' => array('label' => esc_html__('SendinBlue - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'vision6' => array('label' => esc_html__('Vision6 - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'vtiger' => array('label' => esc_html__('Vtiger - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'ymlp' => array('label' => esc_html__('Your Mailing List Provider (Ymlp) - PRO', 'woo-product-tables'), 'require_confirm' => false),
				'fourdem' => array('label' => esc_html__('4Dem.it - PRO', 'woo-product-tables'), 'require_confirm' => false),
			));
		}
		return $subDestList;
	}
	public function getOverviewTabContent() {
		return $this->getView()->getOverviewTabContent();
	}
	public function showWelcomePage() {
		$this->getView()->showWelcomePage();
	}
	public function displayAdminFooter() {
		if (FrameWtbp::_()->isAdminPlugPage()) {
			$this->getView()->displayAdminFooter();
		}
	}
	private function _preparePromoLink( $link, $ref = '' ) {
		if (empty($ref)) {
			$ref = 'user';
		}
		return $link;
	}
	public function weLoveYou() {
		if (!$this->isPro()) {
			DispatcherWtbp::addFilter('popupEditTabs', array($this, 'addUserExp'), 10, 2);
			DispatcherWtbp::addFilter('popupEditDesignTabs', array($this, 'addUserExpDesign'));
			DispatcherWtbp::addFilter('editPopupMainOptsShowOn', array($this, 'showAdditionalmainAdminShowOnOptions'));
		}
	}
	public function showAdditionalmainAdminShowOnOptions( $popup ) {
		$this->getView()->showAdditionalmainAdminShowOnOptions($popup);
	}
	public function addUserExp( $tabs, $popup ) {
		$modPath = '';
		$tabs['wtbpPopupAbTesting'] = array(
			
		);
		if (!in_array($popup['type'], array(WTBP_FB_LIKE, WTBP_IFRAME, WTBP_SIMPLE_HTML, WTBP_PDF, WTBP_AGE_VERIFY, WTBP_FULL_SCREEN))) {
			$tabs['wtbpLoginRegister'] = array(
				
			);
		}
		return $tabs;
	}
	public function addUserExpDesign( $tabs ) {
		$tabs['wtbpPopupLayeredPopup'] = array(
			'title' => esc_html__('Popup Location', 'woo-product-tables'),
			'content' => $this->getView()->getLayeredStylePromo(),
			'fa_icon' => 'fa-arrows',
			'sort_order' => 15,
		);
		return $tabs;
	}
	/**
	 * Public shell for private method
	 */
	public function preparePromoLink( $link, $ref = '' ) {
		return $this->_preparePromoLink($link, $ref);
	}
	public function checkStatisticStatus() {
		// Not used for now - using big data methods
	}
	public function getMinStatSend() {
		return $this->_minDataInStatToSend;
	}
	public function getMainLink() {
		if (empty($this->_mainLink)) {
			$affiliateQueryString = '';
			$this->_mainLink = 'https://woobewoo.com/plugins/table-woocommerce-plugin/' . $affiliateQueryString;
		}
		return $this->_mainLink ;
	}
	public function generateMainLink( $params = '' ) {
		$mainLink = $this->getMainLink();
		if (!empty($params)) {
			return $mainLink . ( strpos($mainLink , '?') ? '&' : '?' ) . $params;
		}
		return $mainLink;
	}
	public function getContactFormFields() {
		$fields = array(
			'name' => array('label' => esc_html__('Name', 'woo-product-tables'), 'valid' => 'notEmpty', 'html' => 'text'),
			'email' => array('label' => esc_html__('Email', 'woo-product-tables'), 'html' => 'email', 'valid' => array('notEmpty', 'email'), 'placeholder' => 'example@mail.com', 'def' => get_bloginfo('admin_email')),
			'website' => array('label' => esc_html__('Website', 'woo-product-tables'), 'html' => 'text', 'placeholder' => 'http://example.com', 'def' => get_bloginfo('url')),
			'subject' => array('label' => esc_html__('Subject', 'woo-product-tables'), 'valid' => 'notEmpty', 'html' => 'text'),
			'category' => array('label' => esc_html__('Topic', 'woo-product-tables'), 'valid' => 'notEmpty', 'html' => 'selectbox', 'options' => array(
				'plugins_options' => esc_html__('Plugin options', 'woo-product-tables'),
				'bug' => esc_html__('Report a bug', 'woo-product-tables'),
				'functionality_request' => esc_html__('Require a new functionality', 'woo-product-tables'),
				'other' => esc_html__('Other', 'woo-product-tables'),
			)),
			'message' => array('label' => esc_html__('Message', 'woo-product-tables'), 'valid' => 'notEmpty', 'html' => 'textarea', 'placeholder' => esc_html__('Hello WBW Team!', 'woo-product-tables')),
		);
		foreach ($fields as $k => $v) {
			if (isset($fields[ $k ]['valid']) && !is_array($fields[ $k ]['valid'])) {
				$fields[ $k ]['valid'] = array( $fields[ $k ]['valid'] );
			}
		}
		return $fields;
	}
	public function isPro() {
		static $isPro;
		if (is_null($isPro)) {
			// license is always active with PRO - even if license key was not entered,
			// add_options module was from the begining of the times in PRO, and will be active only once user will activate license on site
			$isPro = FrameWtbp::_()->getModule('license') && FrameWtbp::_()->getModule('on_exit');
		}
		return $isPro;
	}
	public function checkWelcome() {
		$from = ReqWtbp::getVar('from', 'get');
		$pl = ReqWtbp::getVar('pl', 'get');
		if ('welcome-page' == $from && WTBP_CODE == $pl && FrameWtbp::_()->getModule('user')->isAdmin()) {
			$welcomeSent = (int) get_option(WTBP_DB_PREF . 'welcome_sent');
			if (!$welcomeSent) {
				$this->getModel()->welcomePageSaveInfo();
				update_option(WTBP_DB_PREF . 'welcome_sent', 1);
			}
			$skipTutorial = (int) ReqWtbp::getVar('skip_tutorial', 'get');
			if ($skipTutorial) {
				$tourHst = $this->getModel()->getTourHst();
				$tourHst['closed'] = 1;
				$this->getModel()->setTourHst( $tourHst );
			}
		}
	}
	public function getContactLink() {
		return $this->getMainLink() . '#contact';
	}
	public function addMainOpts( $opts ) {
		$opts['options']['love_link_html'] = '';
		return $opts;
	}
	public function checkSaveOpts( $newValues ) {
		$loveLinkEnb = (int) FrameWtbp::_()->getModule('options')->get('add_love_link');
		$loveLinkEnbNew = isset($newValues['opt_values']['add_love_link']) ? (int) $newValues['opt_values']['add_love_link'] : 0;
		if ($loveLinkEnb != $loveLinkEnbNew) {
			$this->getModel()->saveUsageStat('love_link.' . ( $loveLinkEnbNew ? 'enb' : 'dslb' ));
		}
	}
	public function checkProTpls( $list ) {
		if (!$this->isPro()) {
			$imgsPath = '';
			$promoList = array(
				array('label' => 'List Building Layered', 'img_preview' => 'list-building-layered.jpg', 'sort_order' => 18, 'type_id' => 10),
				array('label' => 'Full Screen Transparent', 'img_preview' => 'full-screen-transparent.jpg', 'sort_order' => 20, 'type_id' => 8),
				array('label' => 'Age Verification', 'img_preview' => 'age-verification.jpg', 'sort_order' => 10, 'type_id' => 7),
				array('label' => 'WordPress Login', 'img_preview' => 'wordpress-login.jpg', 'sort_order' => 15, 'type_id' => 9),
				array('label' => 'Bump!', 'img_preview' => 'bump.jpg', 'sort_order' => 16, 'type_id' => 10),
				array('label' => 'Subscribe Me Bar', 'img_preview' => 'subscribe-me-bar.jpg', 'sort_order' => 17, 'type_id' => 10),
				array('label' => 'Black Friday', 'img_preview' => 'black-friday.jpg', 'sort_order' => 16, 'type_id' => 10),
				array('label' => 'Pyramid', 'img_preview' => 'pyramid.jpg', 'sort_order' => 19, 'type_id' => 10),
				array('label' => 'Catch Eye', 'img_preview' => 'catch-eye.jpg', 'sort_order' => 17, 'type_id' => 10),
				array('label' => 'Logout Reminder', 'img_preview' => 'wordpress-logout.jpg', 'sort_order' => 16, 'type_id' => 9),
				array('label' => 'Ho Ho Holiday Sale', 'img_preview' => 'HoHoHolidaySale.png', 'sort_order' => 0, 'type_id' => 11),
				array('label' => 'Exclusive Christmas', 'img_preview' => 'ExclusiveChristmasBg2.png', 'sort_order' => 0, 'type_id' => 11),
				array('label' => 'Christmas-4', 'img_preview' => 'christmas-4-prev.png', 'sort_order' => 0, 'type_id' => 11),
				array('label' => 'Holiday Discount', 'img_preview' => '358-prev-holiday-discount.png', 'sort_order' => 0, 'type_id' => 11),
				array('label' => 'Winter Sale', 'img_preview' => '365-5-winter-sale-prev.png', 'sort_order' => 0, 'type_id' => 7),
				array('label' => 'Christmas Tree', 'img_preview' => '365-6-img-prev.png', 'sort_order' => 0, 'type_id' => 11),
				array('label' => 'Christmas Candies', 'img_preview' => '361-christmas-candies-prev.png', 'sort_order' => 0, 'type_id' => 11),
				array('label' => 'Xmas Discount', 'img_preview' => '373-xmas-discount-prev.png', 'sort_order' => 0, 'type_id' => 11),
				array('label' => 'Exclusive Subscription', 'img_preview' => '230-7-exclusive-subscr-preview.png', 'sort_order' => 1, 'type_id' => 1),
				array('label' => 'Pretty', 'img_preview' => '2016-8-Pretty-prev.png', 'sort_order' => 1, 'type_id' => 1),
				array('label' => 'Get Discount', 'img_preview' => '2016-9-get-discount-prev.png', 'sort_order' => 1, 'type_id' => 1),
				array('label' => 'Winter Subscribe', 'img_preview' => '2016-10-winter-subscr-prev.png', 'sort_order' => 1, 'type_id' => 1),
				array('label' => 'Lavender Mood', 'img_preview' => '2016-11-lavender-mood-prev.png', 'sort_order' => 1, 'type_id' => 1),
			);
			foreach ($promoList as $i => $t) {
				$promoList[ $i ]['img_preview_url'] = $imgsPath . $promoList[ $i ]['img_preview'];
				$promoList[ $i ]['promo'] = strtolower(str_replace(array(' ', '!'), '', $t['label']));
				$promoList[ $i ]['promo_link'] = $this->generateMainLink('utm_source=plugin&utm_medium=' . $promoList[ $i ]['promo'] . '&utm_campaign=popup');
			}
			foreach ($list as $i => $t) {
				if (isset($t['id']) && $t['id'] >= 50) {
					unset($list[ $i ]);
				}
			}
			$list = array_merge($list, $promoList);
		}
		return $list;
	}
	public function loadTutorial() {
		// Don't run on WP < 3.3
		if ( get_bloginfo( 'version' ) < '3.3' ) {
			return;
		}
	}
	public function checkToShowTutorial() {
		if (ReqWtbp::getVar('tour', 'get') == 'clear-hst') {
			$this->getModel()->clearTourHst();
		}
		$hst = $this->getModel()->getTourHst();
		if (( isset($hst['closed']) && $hst['closed'] )
		   || ( isset($hst['finished']) && $hst['finished'] )
		) {
			return;
		}
		$tourData = array();
		$tourData['tour'] = array(
			'welcome' => array(
				'points' => array(
					'first_welcome' => array(
						'target' => '#toplevel_page_popup-wp-supsystic',
						'options' => array(
							'position' => array(
								'edge' => 'bottom',
								'align' => 'top',
							),
						),
						'show' => 'not_plugin',
					),
				),
			),
			'create_first' => array(
				'points' => array(
					'create_bar_btn' => array(
						'target' => '.supsystic-content .supsystic-navigation .supsystic-tab-popup_add_new',
						'options' => array(
							'position' => array(
								'edge' => 'left',
								'align' => 'right',
							),
						),
						'show' => array('tab_popup', 'tab_settings', 'tab_overview'),
					),
					'enter_title' => array(
						'target' => '#wtbpCreatePopupForm input[type=text]',
						'options' => array(
							'position' => array(
								'edge' => 'top',
								'align' => 'bottom',
							),
						),
						'show' => 'tab_popup_add_new',
					),
					'select_tpl' => array(
						'target' => '.popup-list',
						'options' => array(
							'position' => array(
								'edge' => 'bottom',
								'align' => 'top',
							),
						),
						'show' => 'tab_popup_add_new',
					),
					'save_first_popup' => array(
						'target' => '#wtbpCreatePopupForm .button-primary',
						'options' => array(
							'position' => array(
								'edge' => 'left',
								'align' => 'right',
							),
						),
						'show' => 'tab_popup_add_new',
					),
				),
			),
			'first_edit' => array(
				'points' => array(
					'popup_main_opts' => array(
						'target' => '#wtbpPopupEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'left',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_popup_edit',
					),
					'popup_design_opts' => array(
						'target' => '#wtbpPopupEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'top',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_popup_edit',
						'sub_tab' => '#wtbpPopupTpl',
					),
					'popup_subscribe_opts' => array(
						'target' => '#wtbpPopupEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'top',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_popup_edit',
						'sub_tab' => '#wtbpPopupSubscribe',
					),
					'popup_statistic_opts' => array(
						'target' => '#wtbpPopupEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'left',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_popup_edit',
						'sub_tab' => '#wtbpPopupStatistic',
					),
					'popup_code_opts' => array(
						'target' => '#wtbpPopupEditForm',
						'options' => array(
							'position' => array(
								'edge' => 'right',
								'align' => 'left',
							),
							'pointerWidth' => 200,
						),
						'show' => 'tab_popup_edit',
						'sub_tab' => '#wtbpPopupEditors',
					),
					'final' => array(
						'target' => '#wtbpPopupMainControllsShell .wtbpPopupSaveBtn',
						'options' => array(
							'position' => array(
								'edge' => 'top',
								'align' => 'bottom',
							),
							'pointerWidth' => 500,
						),
						'show' => 'tab_popup_edit',
					),
				),
			),
		);
		$isAdminPage = FrameWtbp::_()->isAdminPlugOptsPage();
		$activeTab = FrameWtbp::_()->getModule('options')->getActiveTab();
		foreach ($tourData['tour'] as $stepId => $step) {
			foreach ($step['points'] as $pointId => $point) {
				$pointKey = $stepId . '-' . $pointId;
				if (isset($hst['passed'][ $pointKey ]) && $hst['passed'][ $pointKey ]) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				$show = isset($point['show']) ? $point['show'] : 'plugin';
				if (!is_array($show)) {
					$show = array( $show );
				}
				if (( in_array('plugin', $show) && !$isAdminPage ) || ( in_array('not_plugin', $show) && $isAdminPage )) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				$showForTabs = false;
				$hideForTabs = false;
				foreach ($show as $s) {
					if (strpos($s, 'tab_') === 0) {
						$showForTabs = true;
					}
					if (strpos($s, 'tab_not_') === 0) {
						$showForTabs = true;
					}
				}
				if ($showForTabs && ( !in_array('tab_' . $activeTab, $show) || !$isAdminPage )) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				if ($hideForTabs && ( in_array('tab_not_' . $activeTab, $show) || !$isAdminPage )) {
					unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
					continue;
				}
				switch ($pointKey) {
					case 'create_first-create_bar_btn':
						// Pointer for Create new POpUp we can show only if there are no created PopUps
						$createdPopupsNum = FrameWtbp::_()->getModule('wootablepress')->getModel()->getCount();
						if (!empty($createdPopupsNum)) {
							unset($tourData['tour'][ $stepId ]['points'][ $pointId ]);
							continue 2;
						}
				}
			}
		}
		foreach ($tourData['tour'] as $stepId => $step) {
			if (!isset($step['points']) || empty($step['points'])) {
				unset($tourData['tour'][ $stepId ]);
			}
		}
		if (empty($tourData['tour'])) {
			return;
		}
		$tourData['html'] = $this->getView()->getTourHtml();
		FrameWtbp::_()->getModule('templates')->loadCoreJs();
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'wp-pointer' );
		FrameWtbp::_()->addScript(WTBP_CODE . 'admin.tour', $this->getModPath() . 'js/admin.tour.js');
		FrameWtbp::_()->addJSVar(WTBP_CODE . 'admin.tour', 'wtbpAdminTourData', $tourData);
	}
	public function showFeaturedPluginsPage() {
		return $this->getView()->showFeaturedPluginsPage();
	}
	public function checkPluginDeactivation() {
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			if ($screen && isset($screen->base) && 'plugins' == $screen->base) {
				FrameWtbp::_()->getModule('templates')->loadCoreJs();
				FrameWtbp::_()->getModule('templates')->loadCoreCss();
				wp_enqueue_style('jquery-ui-wtpb', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css', array(), '1.0');
				FrameWtbp::_()->addScript('jquery-ui-dialog');
				FrameWtbp::_()->addScript(WTBP_CODE . '.admin.plugins', $this->getModPath() . 'js/admin.plugins.js');
				FrameWtbp::_()->addJSVar(WTBP_CODE . '.admin.plugins', 'wtbpPluginsData', array(
					'plugName' => WTBP_PLUG_NAME . '/' . WTBP_MAIN_FILE,
				));
				HtmlWtbp::echoEscapedHtml($this->getView()->getPluginDeactivation());
			}
		}
	}
	public function connectItemEditStats() {
		FrameWtbp::_()->addScript(WTBP_CODE . '.admin.item.edit.stats', $this->getModPath() . 'js/admin.item.edit.stats.js');
	}
}
