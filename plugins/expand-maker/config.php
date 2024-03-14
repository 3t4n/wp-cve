<?php
if(!class_exists('YrmConfig')) {

	class YrmConfig {

		public function __construct() {

			$this->init();
		}

		public function addDefine($name, $value) {
			if(!defined($name)) {
				define($name, $value);
			}
		}

		public static function typePaths() {
			global $YRM_TYPES;
			global $YRM_EXTENSIONS;
			$paths = array(
				'button' => YRM_CLASSES,
				'inline' => YRM_CLASSES,
				'popup' => YRM_CLASSES,
				'inlinePopup' => YRM_CLASSES,
				'accordion' => YRM_CLASSES,
				'accordionPopup' => YRM_CLASSES
			);
			$paths = apply_filters('yrmClassesPaths', $paths);
			$YRM_EXTENSIONS = apply_filters('yrmExtensions', array());
		    $groupList = array('all' => 'All', 'button' => 'Button','inline' => 'Inline', 'popup' => 'Popups', 'accordion' => 'Accordion');
		    $typesGroupList = array(
                'button' => 'button',
                'inline' => 'inline',
                'link' => 'button',
                'alink' => 'inline',
                'accordion' => 'accordion',
                'accordionPopup' => 'accordion',
                'popup' => 'popup',
                'inlinePopup' => 'popup'
            );

			$YRM_TYPES = $paths;
            $YRM_TYPES['groupList'] = $groupList;
            $YRM_TYPES['typesGroupList'] = $typesGroupList;
            $YRM_TYPES['customTypes'] = array('accordion', 'accordionPopup');
		}

		public static function extensions() {
		    $extensions = array();
			$extensions['subscription'] = array(
				'pluginKey' => YRM_SUBS_KEY,
				'isType' => true,
				'shortKey' => 'subscription',
				'videoURL' => 'https://www.youtube.com/watch?v=GvX2f8chA-8',
				'boxTitle' => __('Subscription')
			);
			$extensions['findAndReplace'] = array(
				'pluginKey' => YRM_FAR_PLUGIN_KEY,
				'isType' => false,
				'shortKey' => 'far',
				'videoURL' => '',
				'boxTitle' => __('Find And Replace')
			);
            $extensions['forms'] = array(
                'pluginKey' => 'read-more-login-form/ReadMoreLoginForm.php',
                'isType' => true,
                'shortKey' => 'forms',
                'videoURL' => YRM_FORMS_VIDEO,
                'boxTitle' => __('Read More Login & Registration forms')
            );
			$extensions['analytics'] = array(
				'pluginKey' => 'read-more-analytics/read-more-analytics.php',
				'isType' => false,
				'shortKey' => 'analytics',
				'videoURL' => YRM_ANALYTICS_VIDEO,
				'boxTitle' => __('Analytics')
			);
            $extensions['scroll'] = array(
                'pluginKey' => 'read-more-scroll/read-more-scroll.php',
                'isType' => true,
                'useMainOptions' => true,
                'shortKey' => 'scroll',
                'videoURL' => YRM_SCROLL_VIDEO,
                'boxTitle' => __('Scroll to Top')
            );

		    return apply_filters('yrmExtensionsInfo', $extensions);
        }

		public function getDirectoryName() {

			$baseName = plugin_basename(__FILE__);
			$readMoreDirectoryName = explode('/', $baseName);

			if(isset($readMoreDirectoryName[0])) {
				return $readMoreDirectoryName[0];
			}
			else {
				return '';
			}
		}

		private function init() {

			if(!defined('ABSPATH')) {
				exit();
			}
			
			$readMoreDirectoryName = $this->getDirectoryName();

			if(!defined('YRM_PLUGIN_PREFIX')) {
				define("YRM_PLUGIN_PREFIX", $readMoreDirectoryName);
			}

			if(!defined('YRM_MAIN_FILE')) {
				define("YRM_MAIN_FILE", $readMoreDirectoryName . '.php');
			}

			$this->addDefine('YRM_PATH', dirname(__FILE__).'/');
			
			if(!defined('YRM_CLASSES')) {
				define("YRM_CLASSES", YRM_PATH . 'classes/');
			}
			
			$this->addDefine('YRM_ADMIN_CLASSES', YRM_CLASSES.'admin/');
			$this->addDefine('YRM_ADMIN_BUILDER_CLASSES', YRM_ADMIN_CLASSES.'builder/');
			$this->addDefine('YRM_ADMIN_TYPE_CLASSES', YRM_CLASSES.'types/');

			if(!defined('YRM_FILES')) {
				define("YRM_FILES", YRM_PATH . 'files/');
			}
			$this->addDefine('YRM_LIB_FILES', YRM_FILES.'lib/');

			if(!defined('YRM_CSS')) {
				define("YRM_CSS", YRM_PATH . 'css/');
			}

			$this->addDefine('YRM_VIEWS', YRM_PATH . 'views/');
			$this->addDefine('YRM_VIEWS_GENERAL', YRM_VIEWS.'general/');
			$this->addDefine('YRM_VIEWS_SECTIONS', YRM_VIEWS.'sections/');
			$this->addDefine('YRM_VIEWS_FIND', YRM_VIEWS.'findAndReplace/');
			$this->addDefine('YRM_VIEWS_ACCORDION_OPTIONS', YRM_VIEWS.'accordion/');
			$this->addDefine('YRM_VIEWS_ACCORDION', YRM_VIEWS.'accordionBuilder/');
			$this->addDefine('YRM_TEMPLATES_FIND', YRM_VIEWS.'templates/');
			$this->addDefine('YRM_JAVASCRIPT_PATH', YRM_PATH . 'js/');
			$this->addDefine('YRM_URL',  plugins_url('', __FILE__) . '/');
			$this->addDefine('YRM_JAVASCRIPT', YRM_URL . 'js/');
			$this->addDefine('YRM_ADMIN_JAVASCRIPT', YRM_JAVASCRIPT . 'admin/');
			$this->addDefine('YRM_ADMIN_JAVASCRIPT_GENERAL', YRM_ADMIN_JAVASCRIPT . 'general/');
            $this->addDefine('YRM_CSS_URL', YRM_URL . 'css/');
            $this->addDefine('YRM_ADMIN_CSS_URL', YRM_CSS_URL . 'admin/');
            $this->addDefine('YRM_ADMIN_CSS_GENERAL_URL', YRM_ADMIN_CSS_URL . 'general/');
            $this->addDefine('YRM_TYPES_PAGE_URL', admin_url()."admin.php?page=addNew");
			$this->addDefine('YRM_ACCORDION_PAGE_URL', admin_url()."admin.php?page=button&yrm_type=accordion");

			if(!defined('YRM_IMG_URL')) {
				define('YRM_IMG_URL', YRM_URL . 'img/');
			}

			if(!defined('YRM_LANG')) {
				define('YRM_LANG', 'yrm_lang');
			}

			$this->addDefine('EXPM_VERSION', 3.38);
			$this->addDefine('YRM_VERSION_TEXT', '3.3.8');
			$this->addDefine('EXPM_VERSION_PRO', 2.378);
			$this->addDefine('YRM_ADMIN_POST_NONCE', 'YRM_ADMIN_POST_NONCE');

			$this->addDefine('YRM_FREE_PKG', 1);
			$this->addDefine('YRM_SILVER_PKG', 2);
			$this->addDefine('YRM_GOLD_PKG', 3);
			$this->addDefine('YRM_PLATINUM_PKG', 4);

			require_once(dirname(__FILE__).'/config-pkg.php');

			if(!defined('YRM_SHOW_REVIEW_PERIOD')) {
				define('YRM_SHOW_REVIEW_PERIOD', 30);
			}

			$this->addDefine('YRM_PRO_URL', 'https://edmonsoft.com/');

			if(!defined('YRM_REVIEW_URL')) {
				define('YRM_REVIEW_URL', 'https://wordpress.org/support/plugin/expand-maker/reviews/?filter=5');
			}

			$this->addDefine('YRM_DEMO_URL', 'https://edmonsoft.com/demo/wp-admin/admin.php?page=readMore');
			$this->addDefine('YRM_BUTTON_ICON_URL', YRM_IMG_URL.'arrow.png');
			$this->addDefine('YRM_PAGE_URL', admin_url()."admin.php?page=readMore");

			$this->addDefine('YRM_SUPPORT_URL', 'https://wordpress.org/support/plugin/expand-maker/');
			
			self::addDefine('YRM_PRO_KEY', 'yrmProVersion');
			self::addDefine('YRM_STORE_URL', 'https://edmonsoft.com/');
			self::addDefine('YRM_VERSION_'.YRM_PRO_KEY, EXPM_VERSION_PRO);
			
			$this->addDefine('YRM_NUMBER_PAGES', 15);
			$this->addDefine('YRM_fY', 'yrmSupportKey');
			$this->addDefine('YRM_MAIN_TABLE', 'expm_maker');
			$this->addDefine('YRM_FIND_TABLE', 'find_and_replace');
			$this->addDefine('YRM_FAR_PLUGIN_KEY', 'read-more-advanced-far/read-more-advanced-far.php');
			$this->addDefine('YRM_SUBS_KEY', 'read-more-subscription/ReadMoreSubscription.php');

			$this->addDefine('YRM_ADD_NEW_MENU_KEY', 'addNew');
			$this->addDefine('YRM_SUPPORT_MENU_KEY', 'yrmSupportKey');
			$this->addDefine('YRM_VIDEO_TUTORIAL_KEY', 'yrmTutorialKey');
			$this->addDefine('YRM_FIND_PAGE', 'rmmoreFindReplace');
			$this->addDefine('YRM_ACCORDION_PAGE', 'rmmoreAccordion');
			$this->addDefine('YRM_SUBSCRIBERS_PAGE', 'rmmoreSubscribers');
			$this->addDefine('YRM_READ_MORE_VIDEO', 'https://www.youtube.com/watch?v=ML9Xmbs0TvU');
			$this->addDefine('YRM_ANALYTICS_VIDEO', 'https://www.youtube.com/watch?v=h_RTKiBJ1aE');
			$this->addDefine('YRM_FORMS_VIDEO', 'https://www.youtube.com/watch?v=SXYKH5Hlf5k');
			$this->addDefine('YRM_SCROLL_VIDEO', 'https://www.youtube.com/watch?v=Adj-xmePz7k');
			$this->addDefine('YRM_POPUP_VIDEO', 'https://www.youtube.com/watch?v=Iz8U2Ly-VN8&feature=emb_title');
			
			$this->globalVariables();
			$this->displaySettings();
		}

		public static function readMoreHeaderScript() {

			$headerScript = "EXPM_VERSION=" . EXPM_VERSION.";";
			if(YRM_PKG > YRM_FREE_PKG) {
				$headerScript = "EXPM_VERSION_PRO=" . EXPM_VERSION_PRO.";";
			}
            $headerScript .= "EXPM_AJAX_URL='" . admin_url( 'admin-ajax.php' )."';";
			$headerScript .= "
			function yrmAddEvent(element, eventName, fn) {
				if (element.addEventListener)
					element.addEventListener(eventName, fn, false);
				else if (element.attachEvent)
					element.attachEvent('on' + eventName, fn);
			}";
			return "<script type=\"text/javascript\">
				$headerScript
			</script>";
		}
		
		private function globalVariables() {
			global $YRM_TYPES_INFO;
			$tutorials = array(
				'proVersion' => YRM_POPUP_VIDEO,
				'yrm-forms' => YRM_FORMS_VIDEO,
				'analytics' => YRM_ANALYTICS_VIDEO,
				'scroll' => YRM_SCROLL_VIDEO
			);
			$tutorials = apply_filters('YrmYoutubeUrls', $tutorials);
			$YRM_TYPES_INFO['youtubeUrls'] = $tutorials;
		}

		public static function optionsValues() {
			global $YRM_OPTIONS;
			$options = array();
			$YRM_OPTIONS = apply_filters('yrmOptionsCongifFilter', $options);
		}

		public static function defaultOptions() {
			global $YRM_OPTIONS;
			$options = array();
			$options[] = array('name' => 'yrm-title', 'type' => 'text', 'defaultValue' => '');
			$options[] = array('name' => 'yrm-find-name', 'type' => 'text', 'defaultValue' => '');
			$options[] = array('name' => 'yrm-replace-name', 'type' => 'yrm', 'defaultValue' => '');
			$options[] = array('name' => 'yrm-far-selected-devices', 'type' => 'array', 'defaultValue' => '');
			$options[] = array('name' => 'yrm-far-enable-selected-devices', 'type' => 'checkbox', 'defaultValue' => '');
			$options[] = array('name' => 'yrm-display-settings', 'type' => 'yrm', 'defaultValue' => array(array('key1' => 'everywhere','key2' => 'is', 'key3' => array())));

			$YRM_OPTIONS = apply_filters('yrmDefaultOptions', $options);
		}

		public function displaySettings() {
			global $YRM_DISPLAY_SETTINGS_CONFIG;
			$keys = array(
				'select_settings' => 'Select settings',
				'everywhere' => 'Everywhere',
				'all_post' => 'All posts',
				'selected_posts' => 'Select posts',
				'selected_pages' => 'Select pages',
				'all_page' => 'All pages'
			);

			$values = array(
				'key1' => $keys,
				'key2' => array('is' => 'Is', 'isnot' => 'Is not'),
				'selected_posts' => array(),
				'selected_pages' => array(),
				'everywhere' => array()
			);

			$attributes = array(
				'key1' => array(
					'label' => __('Select Conditions'),
					'fieldType' => 'select',
					'fieldAttributes' => array(
						'class' => 'yrm-condition-select js-yrm-select yrm-js-select2 js-conditions-param',
						'value' => ''
					)
				),
				'key2' => array(
					'label' => __('Select Conditions'),
					'fieldType' => 'select',
					'fieldAttributes' => array(
						'class' => 'yrm-condition-select js-yrm-select yrm-js-select2',
						'value' => ''
					)
				),
				'selected_posts' => array(
					'label' => __('Select Post(s)'),
					'fieldType' => 'select',
					'fieldAttributes' => array(
						'data-post-type' => 'post',
						'data-select-type' => 'ajax',
						'multiple' => 'multiple',
						'class' => 'yrm-condition-select js-yrm-select yrm-js-select2',
						'value' => ''
					)
				),
				'selected_pages' => array(
					'label' => __('Select Page(s)'),
					'fieldType' => 'select',
					'fieldAttributes' => array(
						'data-post-type' => 'page',
						'data-select-type' => 'ajax',
						'multiple' => 'multiple',
						'class' => 'yrm-condition-select js-yrm-select yrm-js-select2',
						'value' => ''
					)
				),
			);

			$keys = apply_filters('yrmConditionsDisplayKeys', $keys);
			$values = apply_filters('yrmConditionsDisplayValues', $values);
			$attributes = apply_filters('yrmConditionsDisplayAttributes', $attributes);

			$YRM_DISPLAY_SETTINGS_CONFIG = array(
				'keys' => $keys,
				'values' => $values,
				'attributes' => $attributes
			);
		}
	}

	$configInit = new YrmConfig();
}