<?php
class templatesGmp extends moduleGmp {
    protected $_styles = array();
	private $_cdnUrl = '';

	public function __construct($d) {
		parent::__construct($d);
	}
    public function init() {
        if (is_admin()) {
			if($isAdminPlugOptsPage = frameGmp::_()->isAdminPlugOptsPage()) {
				$this->loadCoreJs();
				$this->loadAdminCoreJs();
				$this->loadCoreCss();
				$this->loadJqueryUi();
				//$this->loadChosenSelects();
        frameGmp::_()->addScript('gmpAcPromoScript', GMP_JS_PATH. 'acPromoScript.js');
        frameGmp::_()->addStyle('gmpAcPromoStyle', GMP_CSS_PATH. 'acPromoStyle.css');
				frameGmp::_()->addScript('adminOptionsGmp', GMP_JS_PATH. 'admin.options.js', array(), false, true);
				add_action('admin_enqueue_scripts', array($this, 'loadMediaScripts'));
			}
			// Some common styles - that need to be on all admin pages - be careful with them
			frameGmp::_()->addStyle('supsystic-for-all-admin-'. GMP_CODE, GMP_CSS_PATH. 'supsystic-for-all-admin.css');
		}
        parent::init();
    }
	public function loadMediaScripts() {
		if(function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}
	public function loadAdminCoreJs() {
		frameGmp::_()->addScript('jquery-ui-dialog', '', array('jquery'));
		frameGmp::_()->addScript('jquery-ui-slider', '', array('jquery'));
		frameGmp::_()->addScript('wp-color-picker');
		frameGmp::_()->addScript('icheck', GMP_JS_PATH. 'icheck.min.js');
		frameGmp::_()->addScript('jquery-ui-autocomplete', '', array('jquery'));
		$this->loadTooltipstered();
		$this->loadChosenSelects();	// Init Chosen library

    add_filter( 'safe_style_css', function( $styles ) {
        $styles[] = 'display';
        return $styles;
    } );
	}
	public function loadCoreJs() {
		static $loaded = false;

    add_filter( 'safe_style_css', function( $styles ) {
        $styles[] = 'display';
        return $styles;
    } );

		if(!$loaded) {
			frameGmp::_()->addScript('jquery');
			frameGmp::_()->addScript('commonGmp', GMP_JS_PATH. 'common.js', array('jquery'));
			frameGmp::_()->addScript('coreGmp', GMP_JS_PATH. 'core.js', array('jquery'));

			$ajaxurl = admin_url('admin-ajax.php');

			if(frameGmp::_()->getModule('options')->get('ssl_on_ajax')) {
				$ajaxurl = uriGmp::makeHttps($ajaxurl);
			}
			$jsData = array(
				'siteUrl'					=> GMP_SITE_URL,
				'imgPath'					=> GMP_IMG_PATH,
				'cssPath'					=> GMP_CSS_PATH,
				'modPath'					=> GMP_MODULES_PATH,
				'loader'					=> GMP_LOADER_IMG,
				'close'						=> GMP_IMG_PATH. 'cross.gif',
				'ajaxurl'					=> $ajaxurl,
				'GMP_CODE'				=> GMP_CODE,
				'isAdmin'					=> is_admin(),
				'gmapApiUrl'		  => frameGmp::_()->getModule('gmap')->getView()->getApiUrl(),
			);
			if(is_admin()) {
				$jsData['isPro'] = frameGmp::_()->getModule('supsystic_promo')->isPro();
        $show = true;
        $acRemind = get_option('gmp_ac_remind', false);
        if (!empty($acRemind)) {
          $currentDate = date('Y-m-d h:i:s');
          if ($currentDate > $acRemind) {
            $show = true;
          } else {
            $show = false;
          }
        }
        $acSubscribe = get_option('gmp_ac_subscribe', false);
        if (!empty($acSubscribe)) {
          $show = false;
        }
        $acDisabled = get_option('gmp_ac_disabled', false);
        if (!empty($acDisabled)) {
          $show = false;
        }
        $jsData['gmpAcShow'] = $show;
			}
			$jsData = dispatcherGmp::applyFilters('jsInitVariables', $jsData);
			frameGmp::_()->addJSVar('coreGmp', 'GMP_DATA', $jsData);
			$loaded = true;
		}
	}
	public function loadCoreCss() {
		$this->_styles = array(
			'styleGmp'			=> array('path' => GMP_CSS_PATH. 'style.css', 'for' => 'admin'),
			'supsystic-uiGmp'	=> array('path' => GMP_CSS_PATH. 'supsystic-ui.css', 'for' => 'admin'),
			'dashicons'			=> array('for' => 'admin'),
			'icheck'			=> array('path' => GMP_CSS_PATH. 'jquery.icheck.css', 'for' => 'admin'),
			'wp-color-picker'	=> array('for' => 'admin'),
		);
		foreach($this->_styles as $s => $sInfo) {
			if(!empty($sInfo['path'])) {
				frameGmp::_()->addStyle($s, $sInfo['path']);
			} else {
				frameGmp::_()->addStyle($s);
			}
		}
		$this->loadFontAwesome();
	}
	public function loadJqueryUi() {
		static $loaded = false;
		if(!$loaded) {
			frameGmp::_()->addStyle('jquery-ui', GMP_CSS_PATH. 'jquery-ui.min.css');
			frameGmp::_()->addStyle('jquery-ui.structure', GMP_CSS_PATH. 'jquery-ui.structure.min.css');
			frameGmp::_()->addStyle('jquery-ui.theme', GMP_CSS_PATH. 'jquery-ui.theme.min.css');
			frameGmp::_()->addStyle('jquery-slider', GMP_CSS_PATH. 'jquery-slider.css');
			$loaded = true;
		}
	}
	public function loadTooltipstered() {
		frameGmp::_()->addScript('tooltipster', GMP_ASSETS_PATH. 'lib/tooltipster/dist/js/tooltipster.bundle.min.js');
		frameGmp::_()->addStyle('tooltipster', GMP_ASSETS_PATH. 'lib/tooltipster/dist/css/tooltipster.bundle.min.css');
    frameGmp::_()->addStyle('tooltipster-theme', GMP_ASSETS_PATH. 'lib/tooltipster/dist/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-shadow.min.css');
    frameGmp::_()->addStyle('editor-buttons-css',  includes_url() . '/css/editor.min.css');
	}
	public function loadSlimscroll() {
  		frameGmp::_()->addScript('jquery.slimscroll', GMP_ASSETS_PATH. 'lib/js/jquery.slimscroll.js');	// Don't use CDN here - as this lib is modified
	}
	public function loadCodemirror() {
		frameGmp::_()->addStyle('ptsCodemirror', GMP_ASSETS_PATH. 'lib/codemirror/codemirror.css');
		frameGmp::_()->addStyle('codemirror-addon-hint', GMP_ASSETS_PATH. 'lib/codemirror/addon/hint/show-hint.css');
		frameGmp::_()->addScript('ptsCodemirror', GMP_ASSETS_PATH. 'lib/codemirror/codemirror.js');
		frameGmp::_()->addScript('codemirror-addon-show-hint', GMP_ASSETS_PATH. 'lib/codemirror/addon/hint/show-hint.js');
		frameGmp::_()->addScript('codemirror-addon-xml-hint', GMP_ASSETS_PATH. 'lib/codemirror/addon/hint/xml-hint.js');
		frameGmp::_()->addScript('codemirror-addon-html-hint', GMP_ASSETS_PATH. 'lib/codemirror/addon/hint/html-hint.js');
		frameGmp::_()->addScript('codemirror-mode-xml', GMP_ASSETS_PATH. 'lib/codemirror/mode/xml/xml.js');
		frameGmp::_()->addScript('codemirror-mode-javascript', GMP_ASSETS_PATH. 'lib/codemirror/mode/javascript/javascript.js');
		frameGmp::_()->addScript('codemirror-mode-css', GMP_ASSETS_PATH. 'lib/codemirror/mode/css/css.js');
		frameGmp::_()->addScript('codemirror-mode-htmlmixed', GMP_ASSETS_PATH. 'lib/codemirror/mode/htmlmixed/htmlmixed.js');
	}
	public function loadJqTreeView() {
		frameGmp::_()->addStyle('jqtree', GMP_CSS_PATH. 'jqtree.css');
		frameGmp::_()->addScript('tree.jquery', GMP_JS_PATH. 'tree.jquery.js');
	}
	public function loadJqGrid() {
		static $loaded = false;
		if(!$loaded) {
			$this->loadJqueryUi();
			frameGmp::_()->addScript('jq-grid', GMP_ASSETS_PATH. 'lib/jqgrid/jquery.jqGrid.min.js');
			frameGmp::_()->addStyle('jq-grid', GMP_ASSETS_PATH. 'lib/jqgrid/ui.jqgrid.css');
			$langToLoad = utilsGmp::getLangCode2Letter();
			$availableLocales = array('ar','bg','bg1251','cat','cn','cs','da','de','dk','el','en','es','fa','fi','fr','gl','he','hr','hr1250','hu','id','is','it','ja','kr','lt','mne','nl','no','pl','pt','pt','ro','ru','sk','sr','sr','sv','th','tr','tw','ua','vi');
			if(!in_array($langToLoad, $availableLocales)) {
				$langToLoad = 'en';
			}
			frameGmp::_()->addScript('jq-grid-lang', GMP_ASSETS_PATH. 'lib/jqgrid/i18n/grid.locale-'. $langToLoad. '.js');
			$loaded = true;
		}
	}
	public function loadFontAwesome() {
    frameGmp::_()->addStyle('font-awesomeGmp', GMP_CSS_PATH. 'font-awesome.min.css');
	}
	public function loadChosenSelects() {
		frameGmp::_()->addStyle('jquery.chosen', GMP_ASSETS_PATH. 'lib/chosen/chosen.min.css');
		frameGmp::_()->addScript('jquery.chosen', GMP_ASSETS_PATH. 'lib/chosen/chosen.jquery.min.js');
	}
	public function loadJqplot() {
		static $loaded = false;
		if(!$loaded) {
			$jqplotDir = GMP_ASSETS_PATH. 'lib/jqplot/';

			frameGmp::_()->addStyle('jquery.jqplot', $jqplotDir. 'jquery.jqplot.min.css');

			frameGmp::_()->addScript('jplot', $jqplotDir. 'jquery.jqplot.min.js');
			frameGmp::_()->addScript('jqplot.canvasAxisLabelRenderer', $jqplotDir. 'jqplot.canvasAxisLabelRenderer.min.js');
			frameGmp::_()->addScript('jqplot.canvasTextRenderer', $jqplotDir. 'jqplot.canvasTextRenderer.min.js');
			frameGmp::_()->addScript('jqplot.dateAxisRenderer', $jqplotDir. 'jqplot.dateAxisRenderer.min.js');
			frameGmp::_()->addScript('jqplot.canvasAxisTickRenderer', $jqplotDir. 'jqplot.canvasAxisTickRenderer.min.js');
			frameGmp::_()->addScript('jqplot.highlighter', $jqplotDir. 'jqplot.highlighter.min.js');
			frameGmp::_()->addScript('jqplot.cursor', $jqplotDir. 'jqplot.cursor.min.js');
			frameGmp::_()->addScript('jqplot.barRenderer', $jqplotDir. 'jqplot.barRenderer.min.js');
			frameGmp::_()->addScript('jqplot.categoryAxisRenderer', $jqplotDir. 'jqplot.categoryAxisRenderer.min.js');
			frameGmp::_()->addScript('jqplot.pointLabels', $jqplotDir. 'jqplot.pointLabels.min.js');
			frameGmp::_()->addScript('jqplot.pieRenderer', $jqplotDir. 'jqplot.pieRenderer.min.js');
			$loaded = true;
		}
	}
	public function loadMagicAnims() {
		static $loaded = false;
		if(!$loaded) {
			frameGmp::_()->addStyle('jquery.jqplot', GMP_ASSETS_PATH. 'css/magic.min.css');
			$loaded = true;
		}
	}
	public function loadDatePicker() {
		frameGmp::_()->addScript('jquery-ui-datepicker');
	}
	public function loadSuptables() {
		static $loaded = false;
		if(!$loaded) {
			frameGmp::_()->addStyle('suptablesui', GMP_CSS_PATH. 'suptablesui.min.css');
			$loaded = true;
		}
	}
	public function loadGoogleFont( $font ) {
		static $loaded = array();
		if(!isset($loaded[ $font ])) {
			frameGmp::_()->addStyle('google.font.'. str_replace(array(' '), '-', $font), 'https://fonts.googleapis.com/css?family='. urlencode($font));
			$loaded[ $font ] = 1;
		}
	}
}
