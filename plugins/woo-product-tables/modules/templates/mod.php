<?php
class TemplatesWtbp extends ModuleWtbp {
	protected $_styles = array();
	private $_cdnUrl = '';
	
	public function __construct( $d ) {
		parent::__construct($d);
		$this->getCdnUrl();	// Init CDN URL
	}
	public function getCdnUrl() {
		if (empty($this->_cdnUrl)) {
			if ((int) FrameWtbp::_()->getModule('options')->get('use_local_cdn')) {
				$uploadsDir = wp_upload_dir( null, false );
				$this->_cdnUrl = $uploadsDir['baseurl'] . '/' . WTBP_CODE . '/';
				if (UriWtbp::isHttps()) {
					$this->_cdnUrl = str_replace('http://', 'https://', $this->_cdnUrl);
				}
				DispatcherWtbp::addFilter('externalCdnUrl', array($this, 'modifyExternalToLocalCdn'));
			} else {
				$this->_cdnUrl = ( UriWtbp::isHttps() ? 'https' : 'http' ) . '://woobewoo-14700.kxcdn.com/';
			}
		}
		return $this->_cdnUrl;
	}
	public function modifyExternalToLocalCdn( $url ) {
		$url = str_replace(
			array('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css'), 
			array(FrameWtbp::_()->getModule('templates')->getModPath() . 'css'),
			$url);
		return $url;
	}
	public function init() {
		if (is_admin()) {
			$isAdminPlugOptsPage = FrameWtbp::_()->isAdminPlugOptsPage();
			if ($isAdminPlugOptsPage) {
				$this->loadCoreJs();
				$this->loadAdminCoreJs();
				$this->loadCoreCss();
				$this->loadChosenSelects();
				FrameWtbp::_()->addScript('adminOptionsWtbp', WTBP_JS_PATH . 'admin.options.js', array(), false, true);
				add_action('admin_enqueue_scripts', array($this, 'loadMediaScripts'));
				add_action('init', array($this, 'connectAdditionalAdminAssets'));
				
				// Some common styles - that need to be on all admin pages - be careful with them
				FrameWtbp::_()->addStyle('woobewoo-for-all-admin-' . WTBP_CODE, WTBP_CSS_PATH . 'woobewoo-for-all-admin.css');
			}
		}
		parent::init();
	}
	public function connectAdditionalAdminAssets() {
		if (is_rtl()) {
			FrameWtbp::_()->addStyle('styleWtbp-rtl', WTBP_CSS_PATH . 'style-rtl.css');
		}
	}
	public function loadMediaScripts() {
		if (function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}
	public function loadAdminCoreJs() {
		FrameWtbp::_()->addScript('jquery-ui-dialog');
		FrameWtbp::_()->addScript('jquery-ui-slider');
		FrameWtbp::_()->addScript('wp-color-picker');
		FrameWtbp::_()->addScript('wtbp.icheck', WTBP_JS_PATH . 'icheck.min.js');
		$this->loadTooltipster();
	}
	public function loadCoreJs() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addScript('jquery');

			FrameWtbp::_()->addScript('commonWtbp', WTBP_JS_PATH . 'common.js');
			FrameWtbp::_()->addScript('coreWtbp', WTBP_JS_PATH . 'core.js');

			$ajaxurl = admin_url('admin-ajax.php');
			$jsData = array(
				'siteUrl'					=> WTBP_SITE_URL,
				'imgPath'					=> WTBP_IMG_PATH,
				'cssPath'					=> WTBP_CSS_PATH,
				'loader'					=> WTBP_LOADER_IMG, 
				'close'						=> WTBP_IMG_PATH . 'cross.gif', 
				'ajaxurl'					=> $ajaxurl,
				'options'					=> FrameWtbp::_()->getModule('options')->getAllowedPublicOptions(),
				'WTBP_CODE'					=> WTBP_CODE,
				'jsPath'					=> WTBP_JS_PATH,
			);
			if (is_admin()) {
				$jsData['isPro'] = FrameWtbp::_()->isPro();
				$jsData['mainLink'] = FrameWtbp::_()->getModule('promo')->getMainLink();
			}
			$jsData = DispatcherWtbp::applyFilters('jsInitVariables', $jsData);
			FrameWtbp::_()->addJSVar('coreWtbp', 'WTBP_DATA', $jsData);
			$loaded = true;
		}
	}
	public function loadTooltipster() {
		FrameWtbp::_()->addScript('wtbp.tooltipster', FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/tooltipster/jquery.tooltipster.min.js');
		FrameWtbp::_()->addStyle('wtbp.tooltipster', FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/tooltipster/tooltipster.css');
	}
	public function loadSlimscroll() {
		FrameWtbp::_()->addScript('wtbp.jquery.slimscroll', WTBP_JS_PATH . 'slimscroll.min.js');
	}
	public function loadCodemirror() {
		$modPath = FrameWtbp::_()->getModule('templates')->getModPath();
		FrameWtbp::_()->addStyle('wtbpCodemirror', $modPath . 'lib/codemirror/codemirror.css');
		FrameWtbp::_()->addStyle('wtbp-codemirror-addon-hint', $modPath . 'lib/codemirror/addon/hint/show-hint.css');
		FrameWtbp::_()->addScript('wtbpCodemirror', $modPath . 'lib/codemirror/codemirror.js');
		FrameWtbp::_()->addScript('wtbp-codemirror-addon-show-hint', $modPath . 'lib/codemirror/addon/hint/show-hint.js');
		FrameWtbp::_()->addScript('wtbp-codemirror-addon-xml-hint', $modPath . 'lib/codemirror/addon/hint/xml-hint.js');
		FrameWtbp::_()->addScript('wtbp-codemirror-addon-html-hint', $modPath . 'lib/codemirror/addon/hint/html-hint.js');
		FrameWtbp::_()->addScript('wtbp-codemirror-mode-xml', $modPath . 'lib/codemirror/mode/xml/xml.js');
		FrameWtbp::_()->addScript('wtbp-codemirror-mode-javascript', $modPath . 'lib/codemirror/mode/javascript/javascript.js');
		FrameWtbp::_()->addScript('wtbp-codemirror-mode-css', $modPath . 'lib/codemirror/mode/css/css.js');
		FrameWtbp::_()->addScript('wtbp-codemirror-mode-htmlmixed', $modPath . 'lib/codemirror/mode/htmlmixed/htmlmixed.js');
	}
	public function loadCoreCss() {
		$this->_styles = array(
			'styleWtbp'			=> array('path' => WTBP_CSS_PATH . 'style.css', 'for' => 'admin'), 
			'woobewoo-uiWtbp'	=> array('path' => WTBP_CSS_PATH . 'woobewoo-ui.css', 'for' => 'admin'), 
			'dashicons'			=> array('for' => 'admin'),
			'bootstrap-alertsWtbp'	=> array('path' => WTBP_CSS_PATH . 'bootstrap-alerts.css', 'for' => 'admin'),
			'icheckWtbp'			=> array('path' => WTBP_CSS_PATH . 'jquery.icheck.css', 'for' => 'admin'),
			'wp-color-picker'	=> array('for' => 'admin'),
		);
		foreach ($this->_styles as $s => $sInfo) {
			if (!empty($sInfo['path'])) {
				FrameWtbp::_()->addStyle($s, $sInfo['path']);
			} else {
				FrameWtbp::_()->addStyle($s);
			}
		}
		$this->loadFontAwesome();
	}
	public function loadJqueryUi() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addStyle('jquery-ui', WTBP_CSS_PATH . 'jquery-ui.min.css');
			FrameWtbp::_()->addStyle('wtbp-jquery-ui.structure', WTBP_CSS_PATH . 'jquery-ui.structure.min.css');
			FrameWtbp::_()->addStyle('wtbp-jquery-ui.theme', WTBP_CSS_PATH . 'jquery-ui.theme.min.css');
			FrameWtbp::_()->addStyle('wtbp-jquery-slider', WTBP_CSS_PATH . 'jquery-slider.css');
			$loaded = true;
		}
	}
	public function loadJqGrid() {
		static $loaded = false;
		if (!$loaded) {
			$this->loadJqueryUi();
			FrameWtbp::_()->addScript('wtbp-jq-grid', FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/jqgrid/jquery.jqGrid.min.js');
			FrameWtbp::_()->addStyle('wtbp-jq-grid', FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/jqgrid/ui.jqgrid.css');
			$langToLoad = UtilsWtbp::getLangCode2Letter();
			$availableLocales = array('ar', 'bg', 'bg1251', 'cat', 'cn', 'cs', 'da', 'de', 'dk', 'el', 'en', 'es', 'fa', 'fi', 'fr', 'gl', 'he', 'hr', 'hr1250', 'hu', 'id', 'is', 'it', 'ja', 'kr', 'lt', 'mne', 'nl', 'no', 'pl', 'pt', 'pt', 'ro', 'ru', 'sk', 'sr', 'sr', 'sv', 'th', 'tr', 'tw', 'ua', 'vi');
			if (!in_array($langToLoad, $availableLocales)) {
				$langToLoad = 'en';
			}
			FrameWtbp::_()->addScript('wtbp-jq-grid-lang', FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/jqgrid/i18n/grid.locale-' . $langToLoad . '.js');
			$loaded = true;
		}
	}
	public function loadFontAwesome() {
		//FrameWtbp::_()->addStyle('font-awesomeWtbp', DispatcherWtbp::applyFilters('externalCdnUrl', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'));
		FrameWtbp::_()->addStyle('font-awesomeWtbp', FrameWtbp::_()->getModule('templates')->getModPath() . 'css/font-awesome.min.css');
	}
	public function loadChosenSelects() {
		FrameWtbp::_()->addStyle('wtbp.jquery.chosen', FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/chosen/chosen.min.css');
		FrameWtbp::_()->addScript('wtbp.jquery.chosen', FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/chosen/chosen.jquery.min.js');
	}
	public function loadDatePicker() {
		FrameWtbp::_()->addScript('jquery-ui-datepicker');
	}
	public function loadJqplot() {
		static $loaded = false;
		if (!$loaded) {
			$jqplotDir = FrameWtbp::_()->getModule('templates')->getModPath() . 'lib/jqplot/';

			FrameWtbp::_()->addStyle('wtbp.jquery.jqplot', $jqplotDir . 'jquery.jqplot.min.css');

			FrameWtbp::_()->addScript('wtbp.jplot', $jqplotDir . 'jquery.jqplot.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.canvasAxisLabelRenderer', $jqplotDir . 'jqplot.canvasAxisLabelRenderer.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.canvasTextRenderer', $jqplotDir . 'jqplot.canvasTextRenderer.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.dateAxisRenderer', $jqplotDir . 'jqplot.dateAxisRenderer.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.canvasAxisTickRenderer', $jqplotDir . 'jqplot.canvasAxisTickRenderer.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.highlighter', $jqplotDir . 'jqplot.highlighter.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.cursor', $jqplotDir . 'jqplot.cursor.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.barRenderer', $jqplotDir . 'jqplot.barRenderer.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.categoryAxisRenderer', $jqplotDir . 'jqplot.categoryAxisRenderer.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.pointLabels', $jqplotDir . 'jqplot.pointLabels.min.js');
			FrameWtbp::_()->addScript('wtbp.jqplot.pieRenderer', $jqplotDir . 'jqplot.pieRenderer.min.js');
			$loaded = true;
		}
	}
	public function loadSortable() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addScript('jquery-ui-core');
			FrameWtbp::_()->addScript('jquery-ui-widget');
			FrameWtbp::_()->addScript('jquery-ui-mouse');

			FrameWtbp::_()->addScript('jquery-ui-draggable');
			FrameWtbp::_()->addScript('jquery-ui-sortable');
			$loaded = true;
		}
	}
	public function loadMagicAnims() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addStyle('wtbp.magic.anim', FrameWtbp::_()->getModule('templates')->getModPath() . 'css/magic.min.css');
			$loaded = true;
		}
	}
	public function loadCssAnims() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addStyle('wtbp.animate.styles', FrameWtbp::_()->getModule('templates')->getModPath() . 'css/magic.min.css');
			$loaded = true;
		}
	}
	public function loadBootstrapSimple() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addStyle('wtbp-bootstrap-simple', WTBP_CSS_PATH . 'bootstrap-simple.css');
			$loaded = true;
		}
	}
	public function loadBootstrap() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addStyle('wtbp.bootstrap', WTBP_CSS_PATH . 'bootstrap.min.css');
			$loaded = true;
		}
	}
	public function loadGoogleFont( $font ) {
		static $loaded = array();
		if (!isset($loaded[ $font ])) {
			FrameWtbp::_()->addStyle('wtbp.google.font.' . str_replace(array(' '), '-', $font), 'https://fonts.googleapis.com/css?family=' . urlencode($font));
			$loaded[ $font ] = 1;
		}
	}
	public function loadBxSlider() {
		static $loaded = false;
		if (!$loaded) {
			FrameWtbp::_()->addStyle('wtbp-bx-slider', WTBP_JS_PATH . 'bx-slider/jquery.bxslider.css');
			FrameWtbp::_()->addScript('wtbp-bx-slider', WTBP_JS_PATH . 'bx-slider/jquery.bxslider.min.js');
			$loaded = true;
		}
	}
}
