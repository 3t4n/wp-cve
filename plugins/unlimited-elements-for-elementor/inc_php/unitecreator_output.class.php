<?php
/**
 * @package Unlimited Elements
 * @author unlimited-elements.com
 * @copyright (C) 2021 Unlimited Elements, All Rights Reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorOutputWork extends HtmlOutputBaseUC{

	private static $serial = 0;

	const SELECTOR_VALUE_PLACEHOLDER = "{{VALUE}}";

	const TEMPLATE_HTML = "html";
	const TEMPLATE_CSS = "css";
	const TEMPLATE_CSS_ITEM = "css_item";
	const TEMPLATE_JS = "js";
	const TEMPLATE_HTML_ITEM = "item";
	const TEMPLATE_HTML_ITEM2 = "item2";

	private $addon;
	private $isInited = false;
	private $objTemplate;
	private $isItemsExists = false;
	private $itemsType = null;
	private $paramsCache = null;
	private $cacheConstants = null;
	private $processType = null;
	private $generatedID = null;
	private $systemOutputID = null;
	private $isModePreview = false;
	private $arrOptions;

	private $isShowDebugData = false;
	private $debugDataType = "";
	private $valuesForDebug = null;

	private $itemsSource = "";

	private static $arrScriptsHandles = array();

	private static $arrUrlCacheCss = array();
	private static $arrHandleCacheCss = array();

	private static $arrUrlCacheJs = array();
	private static $arrHandleCacheJs = array();

	public static $isBufferingCssActive = false;
	public static $bufferBodyCss;
	public static $bufferCssIncludes;

	private static $arrGeneratedIDs = array();

	private $lastSelectorStyle = "";
	private $htmlDebug = "";


	/**
	 * construct
	 */
	public function __construct(){
		$this->addon = new UniteCreatorAddon();

		if(GlobalsUC::$isProVersion)
			$this->objTemplate = new UniteCreatorTemplateEnginePro();
		else
			$this->objTemplate = new UniteCreatorTemplateEngine();


		$this->processType = UniteCreatorParamsProcessor::PROCESS_TYPE_OUTPUT;

	}


	/**
	* set output type
	 */
	public function setProcessType($type){

		UniteCreatorParamsProcessor::validateProcessType($type);

		$this->processType = $type;

	}

	/**
	 * validate inited
	 */
	private function validateInited(){
		if($this->isInited == false)
			UniteFunctionsUC::throwError("Output error: addon not inited");

	}

	private function a_________INCLUDES_______(){}

	/**
	 * clear includes cache, avoid double render bug
	 */
	public static function clearIncludesCache(){

		self::$arrHandleCacheCss = array();
		self::$arrHandleCacheJs = array();

		self::$arrUrlCacheCss = array();
		self::$arrUrlCacheJs = array();

	}


	/**
	 * cache include
	 */
	private function cacheInclude($url, $handle, $type){

		if($type == "css"){	  //cache css

			self::$arrUrlCacheCss[$url] = true;
			self::$arrHandleCacheCss[$handle] = true;

		}else{
				//cache js

			self::$arrUrlCacheJs[$url] = true;
			self::$arrHandleCacheJs[$handle] = true;

		}

	}

	/**
	 * check that the include located in cache
	 */
	private function isIncludeInCache($url, $handle, $type){

		if(empty($url) || empty($handle))
			return(false);

		if($type == "css"){

			if(isset(self::$arrUrlCacheCss[$url]))
				return(true);

			if(isset(self::$arrHandleCacheCss[$handle]))
				return(true);

		}else{	//js

			if(isset(self::$arrUrlCacheJs[$url]))
				return(true);

			if(isset(self::$arrHandleCacheJs[$handle]))
				return(true);

		}

		return(false);
	}



	/**
	 * check include condition
	 * return true  to include and false to not include
	 */
	private function checkIncludeCondition($condition){

		if(empty($condition))
			return(true);

		if(!is_array($condition))
			return(true);

		$name = UniteFunctionsUC::getVal($condition, "name");
		$value = UniteFunctionsUC::getVal($condition, "value");

		if(empty($name))
			return(true);

		if($name == "never_include")
			return(false);

		$params = $this->getAddonParams();

		if(array_key_exists($name, $params) == false)
			return(true);

		$paramValue = $params[$name];

		if(is_array($value)){

			$index = array_search($paramValue, $value);

			$isEqual = ($index !== false);

		}else
			$isEqual = ($paramValue === $value);

		return($isEqual);
	}


	/**
	 * process includes list, get array("url", type)
	 */
	private function processIncludesList($arrIncludes, $type){

		$arrIncludesProcessed = array();

		foreach($arrIncludes as $handle => $include){

			$urlInclude = $include;

			if(is_array($include)){

				$urlInclude = UniteFunctionsUC::getVal($include, "url");
				$condition = UniteFunctionsUC::getVal($include, "condition");
				$isIncludeByCondition = $this->checkIncludeCondition($condition);

				if($isIncludeByCondition == false)
					continue;
			}

			if(is_numeric($handle) || empty($handle)){
				$addonName = $this->addon->getName();
				$handle = HelperUC::getUrlHandle($urlInclude, $addonName);
			}

			$urlInclude = HelperUC::urlToSSLCheck($urlInclude);

			$deps = array();

			$includeAsModule = false;

			//process params
			$params = UniteFunctionsUC::getVal($include, "params");
			if(!empty($params)){
				$includeAfterFrontend = UniteFunctionsUC::getVal($params, "include_after_elementor_frontend");
				$includeAfterFrontend = UniteFunctionsUC::strToBool($includeAfterFrontend);

				if($includeAfterFrontend == true)
					$deps[]= "elementor-frontend";

				//include as module handle.
				//add to handles array, and later check if need to add the module addition to output

				$includeAsModule = UniteFunctionsUC::getVal($params, "include_as_module");
				$includeAsModule = UniteFunctionsUC::strToBool($includeAsModule);

				if($includeAsModule == true)
					GlobalsProviderUC::$arrJSHandlesModules[$handle] = true;

				//change the handle
				$customHandle = UniteFunctionsUC::getVal($params, "include_handle");
				$customHandle = trim($customHandle);

				if(!empty($customHandle))
					$handle = $customHandle;

			}

			$arrIncludeNew = array();
			$arrIncludeNew["url"] = $urlInclude;
			$arrIncludeNew["type"] = $type;

			if(!empty($handle))
				$arrIncludeNew["handle"] = $handle;

			if(!empty($deps))
				$arrIncludeNew["deps"] = $deps;

			if($includeAsModule == true)
				$arrIncludeNew["is_module"] = true;


			$arrIncludesProcessed[] = $arrIncludeNew;

		}



		return($arrIncludesProcessed);
	}

	/**
	 * exclude alrady existing includes on page
	 * like font awesome
	 * function for override
	 */
	protected function excludeExistingInlcudes($arrIncludes){

		return($arrIncludes);
	}

	/**
	 * get processed includes list
	 * includes type = js / css / all
	 */
	public function getProcessedIncludes($includeLibraries = false, $processProviderLibrary = false, $includesType = "all"){

		$this->validateInited();

		//get list of js and css
		$arrLibJs = array();
		$arrLibCss = array();

		if($includeLibraries == true){

			//get all libraries without provider process
			$arrLibraries = $this->addon->getArrLibraryIncludesUrls($processProviderLibrary);
		}

		$arrIncludesJS = array();
		$arrIncludesCss = array();

		//get js
		if($includesType != "css"){

			if($includeLibraries)
				$arrLibJs = $arrLibraries["js"];

			$arrIncludesJS = $this->addon->getJSIncludes();

			$arrIncludesJS = array_merge($arrLibJs, $arrIncludesJS);
			$arrIncludesJS = $this->processIncludesList($arrIncludesJS, "js");
		}


		//get css
		if($includesType != "js"){
			if($includeLibraries)
				$arrLibCss = $arrLibraries["css"];

			$arrIncludesCss = $this->addon->getCSSIncludes();
			$arrIncludesCss = array_merge($arrLibCss, $arrIncludesCss);
			$arrIncludesCss = $this->processIncludesList($arrIncludesCss, "css");
		}

		$arrProcessedIncludes = array_merge($arrIncludesJS, $arrIncludesCss);

		$arrProcessedIncludes = $this->excludeExistingInlcudes($arrProcessedIncludes);


		// add widget scripts to editor

		if(!empty(HelperUC::$arrWidgetScripts)){

			foreach(HelperUC::$arrWidgetScripts as $handle=>$urlScript){

				$arrScript = array(
					"type"=>"js",
					"handle"=>$handle,
					"url"=>$urlScript,
				);

				$arrProcessedIncludes[] = $arrScript;
			}

			//empty the array
			HelperUC::$arrWidgetScripts = array();

		}


		return($arrProcessedIncludes);
	}


	/**
	 * get includes html
	 */
	private function getHtmlIncludes($arrIncludes = null, $filterType = null){

		$this->validateInited();

		if(empty($arrIncludes))
			return("");

		$addonName = $this->addon->getName();

		$html = "";

		foreach($arrIncludes as $include){

			$type = $include["type"];

			//filter
			if($filterType == "js" && $type != "js")
				continue;

			if($filterType == "css" && $type != "css")
				continue;

			$url = $include["url"];
			$handle = UniteFunctionsUC::getVal($include, "handle");

			if(empty($handle))
				$handle = HelperUC::getUrlHandle($url, $addonName);

			$isInCache = $this->isIncludeInCache($url, $handle, $type);

			if($isInCache == true){

				continue;
			}

			$this->cacheInclude($url, $handle, $type);

			switch($type){
				case "js":

					$htmlType = "text/javascript";
					$isModule = UniteFunctionsUC::getVal($include, "is_module");
					$isModule = UniteFunctionsUC::strToBool($isModule);

					if($isModule == true)
						$htmlType = "module";

					$html .= self::TAB2."<script type='{$htmlType}' src='{$url}'></script>".self::BR;
					break;
				case "css":
					$cssID = "{$handle}-css";

					$isDelayedScript = apply_filters("unlimited_element_is_style_delayed", $cssID);

					if($isDelayedScript === true){
						$styleHtml = "<link id='{$cssID}' data-debloat-delay='' data-href='{$url}' type='text/css' rel='stylesheet' media='all' >";

						$html .= self::TAB2.$styleHtml.self::BR;
					}
					else
						$html .= self::TAB2."<link id='{$cssID}' href='{$url}' type='text/css' rel='stylesheet' >".self::BR;

					break;
				default:
					UniteFunctionsUC::throwError("Wrong include type: {$type} ");
				break;
			}

		}



		return($html);
	}


	/**
	 * process includes
	 * includes type = "all,js,css"
	 */
	public function processIncludes($includesType = "all"){

		$arrIncludes = $this->getProcessedIncludes(true, true, $includesType);

		$addonName = $this->addon->getName();

		$arrDep = $this->addon->getIncludesJsDependancies();

		foreach($arrIncludes as $include){

			$type = $include["type"];
			$url = $include["url"];
			$handle = UniteFunctionsUC::getVal($include, "handle");
			$deps = UniteFunctionsUC::getVal($include, "deps");


			if(empty($handle))
				$handle = HelperUC::getUrlHandle($url, $addonName);

			$isInCache = $this->isIncludeInCache($url, $handle, $type);
			if($isInCache == true){
				continue;
			}
			$this->cacheInclude($url, $handle, $type);

			$arrIncludeDep = $arrDep;

			if(!empty($deps))
				$arrIncludeDep = array_merge($arrIncludeDep, $deps);

			switch($type){
				case "js":

					UniteProviderFunctionsUC::addScript($handle, $url, false, $arrIncludeDep);
				break;
				case "css":

						UniteProviderFunctionsUC::addStyle($handle, $url);
				break;
				default:
					UniteFunctionsUC::throwError("Wrong include type: {$type} ");
				break;
			}

		}

	}

	private function a________PREVIEW_HTML________(){}

	/**
	 * put header additions in header html, functiob for override
	 */
	protected function putPreviewHtml_headerAdd(){
	}

	/**
	 * put footer additions in body html, functiob for override
	 */
	protected function putPreviewHtml_footerAdd(){
	}

	/**
	 * function for override
	 */
	protected function onPreviewHtml_scriptsAdd(){
		/*function for override */
	}

	/**
	 * modify preview includes, function for override
	 */
	protected function modifyPreviewIncludes($arrIncludes){

		return($arrIncludes);
	}


	private function ______CSS_SELECTORS_______(){}

	/**
	 * process css selector of number param
	 */
	private function processParamCSSSelector_number($param, $selector){

		$selectorValue = UniteFunctionsUC::getVal($param, "selector_value");

		if(empty($selectorValue) === true)
			return null;

		$values = array(
			"desktop" => UniteFunctionsUC::getVal($param, "value"),
			"tablet" => UniteFunctionsUC::getVal($param, "value_tablet"),
			"mobile" => UniteFunctionsUC::getVal($param, "value_mobile"),
		);

		$style = "";

		foreach($values as $device => $value){
			if(empty($value) === true)
				continue;

			$css = $this->prepareCSSSelectorValueCSS($selectorValue, $value);
			$style .= $this->prepareCSSSelectorStyle($selector, $css, $device);
		}

		return $style;
	}

	/**
	 * process css selector of background param
	 */
	private function processParamCSSSelector_background($param, $selector){

		$name = UniteFunctionsUC::getVal($param, "name");
		$value = UniteFunctionsUC::getVal($param, "value");
		$type = UniteFunctionsUC::getVal($value, $name . "_type");

		$style = "";

		switch($type){
			case "solid":
				$regularFields = array(
					$name . "_solid_color" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "color"),
					$name . "_solid_image_attachment" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "attachment"),
				);

				$responsiveFields = array(
					$name . "_solid_image" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "image"),
					$name . "_solid_image_position" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "position"),
					$name . "_solid_image_repeat" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "repeat"),
					$name . "_solid_image_size" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "size"),
				);

				$style .= $this->prepareCSSSelectorFieldsStyle($regularFields, $selector, $value);
				$style .= $this->prepareCSSSelectorResponsiveFieldsStyle($responsiveFields, $selector, $value);
			break;
			case "gradient":
				$color1 = UniteFunctionsUC::getVal($value, $name . "_gradient1_color");
				$stop1 = UniteFunctionsUC::getVal($value, $name . "_gradient1_stop");
				$color2 = UniteFunctionsUC::getVal($value, $name . "_gradient2_color");
				$stop2 = UniteFunctionsUC::getVal($value, $name . "_gradient2_stop");
				$type = UniteFunctionsUC::getVal($value, $name . "_gradient_type");
				$angle = UniteFunctionsUC::getVal($value, $name . "_gradient_angle");
				$position = UniteFunctionsUC::getVal($value, $name . "_gradient_position");

				$stop1 = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $stop1);
				$stop2 = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $stop2);
				$angle = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $angle);

				if($color1 !== "" && $stop1 !== "" && $color2 !== "" && $stop2 !== "" && $type !== "" && $angle !== "" && $position !== ""){
					$selectorValue = ($type === "radial")
						? HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "radial-gradient")
						: HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BACKGROUND, "linear-gradient");

					$css = str_replace(
						array("{{ANGLE}}", "{{POSITION}}", "{{COLOR1}}", "{{STOP1}}", "{{COLOR2}}", "{{STOP2}}"),
						array($angle, $position, $color1, $stop1, $color2, $stop2),
						$selectorValue
					);

					$style .= $this->prepareCSSSelectorStyle($selector, $css);
				}
			break;
		}

		return $style;
	}

	/**
	 * process css selector of border param
	 */
	private function processParamCSSSelector_border($param, $selector){

		$value = UniteFunctionsUC::getVal($param, "value");
		$type = UniteFunctionsUC::getVal($value, "type", "none");
		$color = UniteFunctionsUC::getVal($value, "color", "#000000");

		if($type === "none")
			return null;

		$style = "";

		$selectorValue = HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BORDER, "style");
		$css = $this->prepareCSSSelectorValueCSS($selectorValue, $type);
		$style .= $this->prepareCSSSelectorStyle($selector, $css);

		$selectorValue = HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BORDER, "color");
		$css = $this->prepareCSSSelectorValueCSS($selectorValue, $color);
		$style .= $this->prepareCSSSelectorStyle($selector, $css);

		$widths = array(
			"desktop" => UniteFunctionsUC::getVal($value, "width"),
			"tablet" => UniteFunctionsUC::getVal($value, "width_tablet"),
			"mobile" => UniteFunctionsUC::getVal($value, "width_mobile"),
		);

		$selectorValue = HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BORDER, "width");

		foreach($widths as $device => $value){
			if(empty($value) === true)
				continue;

			$css = $this->prepareCSSSelectorDimentionsCSS($selectorValue, $value);
			$style .= $this->prepareCSSSelectorStyle($selector, $css, $device);
		}

		return $style;
	}

	/**
	 * process css selector of dimentions param
	 */
	private function processParamCSSSelector_dimentions($param, $selector, $type){

		$selectorValue = HelperHtmlUC::getCSSSelectorValueByParam($type);

		$values = array(
			"desktop" => UniteFunctionsUC::getVal($param, "value"),
			"tablet" => UniteFunctionsUC::getVal($param, "value_tablet"),
			"mobile" => UniteFunctionsUC::getVal($param, "value_mobile"),
		);

		$style = "";

		foreach($values as $device => $value){
			if(empty($value) === true)
				continue;

			$css = $this->prepareCSSSelectorDimentionsCSS($selectorValue, $value);
			$style .= $this->prepareCSSSelectorStyle($selector, $css, $device);
		}

		return $style;
	}

	/**
	 * process css selector of slider param
	 */
	private function processParamCSSSelector_slider($param, $selector){

		$selectorValue = UniteFunctionsUC::getVal($param, "selector_value");

		if(empty($selectorValue) === true)
			return null;

		$values = array(
			"desktop" => UniteFunctionsUC::getVal($param, "value"),
			"tablet" => UniteFunctionsUC::getVal($param, "value_tablet"),
			"mobile" => UniteFunctionsUC::getVal($param, "value_mobile"),
		);

		$style = "";

		foreach($values as $device => $value){
			if(empty($value) === true)
				continue;

			$css = $this->prepareCSSSelectorSliderCSS($selectorValue, $value);
			$style .= $this->prepareCSSSelectorStyle($selector, $css, $device);
		}

		return $style;
	}

	/**
	 * process css selector of typography param
	 */
	private function processParamCSSSelector_typography($param, $selector){

		$value = UniteFunctionsUC::getVal($param, "value");

		$style = "";

		// import font family
		$fontFamily = UniteFunctionsUC::getVal($value, "font_family");

		if(empty($fontFamily) === false){
			$fontData = HelperUC::getFontPanelData();
			$googleFonts = UniteFunctionsUC::getVal($fontData, "arrGoogleFonts");

			if(empty($googleFonts[$fontFamily]) === false){
				$fontUrl = HelperHtmlUC::getGoogleFontUrl($googleFonts[$fontFamily]);

				$this->addon->addCssInclude($fontUrl);
			}
		}

		$regularFields = array(
			"font_family" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "family"),
			"font_style" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "style"),
			"font_weight" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "weight"),
			"text_decoration" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "decoration"),
			"text_transform" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "transform"),
		);

		$responsiveFields = array(
			"font_size" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "size"),
			"line_height" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "line-height"),
			"letter_spacing" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "letter-spacing"),
			"word_spacing" => HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TYPOGRAPHY, "word-spacing"),
		);

		$style .= $this->prepareCSSSelectorFieldsStyle($regularFields, $selector, $value);
		$style .= $this->prepareCSSSelectorResponsiveFieldsStyle($responsiveFields, $selector, $value);

		return $style;
	}

	/**
	 * process css selector of text shadow param
	 */
	private function processParamCSSSelector_textShadow($param, $selector){

		$value = UniteFunctionsUC::getVal($param, "value");
		$x = UniteFunctionsUC::getVal($value, "x");
		$y = UniteFunctionsUC::getVal($value, "y");
		$blur = UniteFunctionsUC::getVal($value, "blur");
		$color = UniteFunctionsUC::getVal($value, "color");

		$x = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $x);
		$y = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $y);
		$blur = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $blur);

		$css = "";

		if($x !== "" && $y !== "" && $blur !== "" && $color !== ""){
			$selectorValue = HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_TEXTSHADOW);

			$css = str_replace(
				array("{{X}}", "{{Y}}", "{{BLUR}}", "{{COLOR}}"),
				array($x, $y, $blur, $color),
				$selectorValue
			);
		}

		$style = $this->prepareCSSSelectorStyle($selector, $css);

		return $style;
	}

	/**
	 * process css selector of box shadow param
	 */
	private function processParamCSSSelector_boxShadow($param, $selector){

		$value = UniteFunctionsUC::getVal($param, "value");
		$x = UniteFunctionsUC::getVal($value, "x");
		$y = UniteFunctionsUC::getVal($value, "y");
		$blur = UniteFunctionsUC::getVal($value, "blur");
		$spread = UniteFunctionsUC::getVal($value, "spread");
		$color = UniteFunctionsUC::getVal($value, "color");
		$position = UniteFunctionsUC::getVal($value, "position");

		$x = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $x);
		$y = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $y);
		$blur = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $blur);
		$spread = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $spread);

		$css = "";

		if($x !== "" && $y !== "" && $blur !== "" && $spread !== "" && $color !== "" && $position !== ""){
			$selectorValue = HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_BOXSHADOW);

			$css = str_replace(
				array("{{X}}", "{{Y}}", "{{BLUR}}", "{{SPREAD}}", "{{COLOR}}", "{{POSITION}}"),
				array($x, $y, $blur, $spread, $color, $position),
				$selectorValue
			);
		}

		$style = $this->prepareCSSSelectorStyle($selector, $css);

		return $style;
	}

	/**
	 * process css selector of css filters param
	 */
	private function processParamCSSSelector_cssFilters($param, $selector){

		$value = UniteFunctionsUC::getVal($param, "value");
		$blur = UniteFunctionsUC::getVal($value, "blur");
		$brightness = UniteFunctionsUC::getVal($value, "brightness");
		$contrast = UniteFunctionsUC::getVal($value, "contrast");
		$saturation = UniteFunctionsUC::getVal($value, "saturation");
		$hue = UniteFunctionsUC::getVal($value, "hue");

		$blur = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $blur);
		$brightness = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $brightness);
		$contrast = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $contrast);
		$saturation = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $saturation);
		$hue = $this->prepareCSSSelectorSliderCSS(self::SELECTOR_VALUE_PLACEHOLDER, $hue);

		$css = "";

		if($blur !== "" && $brightness !== "" && $contrast !== "" && $saturation !== "" && $hue !== ""){
			$selectorValue = HelperHtmlUC::getCSSSelectorValueByParam(UniteCreatorDialogParam::PARAM_CSS_FILTERS);

			$css = str_replace(
				array("{{BLUR}}", "{{BRIGHTNESS}}", "{{CONTRAST}}", "{{SATURATE}}", "{{HUE}}"),
				array($blur, $brightness, $contrast, $saturation, $hue),
				$selectorValue
			);
		}

		$style = $this->prepareCSSSelectorStyle($selector, $css);

		return $style;
	}

	/**
	 * process css selector based on value
	 */
	private function processParamCSSSelector_value($param, $selector){

		$selectorValue = UniteFunctionsUC::getVal($param, "selector_value");

		if(empty($selectorValue) === true)
			return null;

		$values = array(
			"desktop" => UniteFunctionsUC::getVal($param, "value"),
			"tablet" => UniteFunctionsUC::getVal($param, "default_value_tablet"),
			"mobile" => UniteFunctionsUC::getVal($param, "default_value_mobile"),
		);

		$options = UniteFunctionsUC::getVal($param, "options");

		if(empty($options) === false){
			// check if the value exists in the options
			foreach($values as $device => $value){
				if(in_array($value, $options) === false)
					unset($values[$device]);
			}
		}

		$style = "";

		foreach($values as $device => $value){
			if(empty($value) === true)
				continue;

			$css = $this->prepareCSSSelectorValueCSS($selectorValue, $value);
			$style .= $this->prepareCSSSelectorStyle($selector, $css, $device);
		}

		return $style;
	}

	/**
	 * prepare css selector dimentions css
	 */
	private function prepareCSSSelectorDimentionsCSS($selectorValue, $value){

		$top = UniteFunctionsUC::getVal($value, "top");
		$right = UniteFunctionsUC::getVal($value, "right");
		$bottom = UniteFunctionsUC::getVal($value, "bottom");
		$left = UniteFunctionsUC::getVal($value, "left");
		$unit = UniteFunctionsUC::getVal($value, "unit", "px");

		$css = str_replace(
			array("{{TOP}}", "{{RIGHT}}", "{{BOTTOM}}", "{{LEFT}}"),
			array($top . $unit, $right . $unit, $bottom . $unit, $left . $unit),
			$selectorValue
		);

		return $css;
	}

	/**
	 * prepare css selector image css
	 */
	private function prepareCSSSelectorImageCSS($selectorValue, $value){

		$id = UniteFunctionsUC::getVal($value, "id");
		$url = UniteFunctionsUC::getVal($value, "url");
		$size = UniteFunctionsUC::getVal($value, "size", "full");

		if(empty($id) === false)
			$url = UniteProviderFunctionsUC::getImageUrlFromImageID($id, $size);
		else
			$url = HelperUC::URLtoFull($url);

		$css = $this->prepareCSSSelectorValueCSS($selectorValue, $url);

		return $css;
	}

	/**
	 * prepare css selector slider css
	 */
	private function prepareCSSSelectorSliderCSS($selectorValue, $value){

		$size = UniteFunctionsUC::getVal($value, "size");
		$unit = UniteFunctionsUC::getVal($value, "unit", "px");

		if($size === "")
			return "";

		$css = str_replace(
			array(self::SELECTOR_VALUE_PLACEHOLDER, "{{SIZE}}", "{{UNIT}}"),
			array($size . $unit, $size, $unit),
			$selectorValue
		);

		return $css;
	}

	/**
	 * prepare css selector value css
	 */
	private function prepareCSSSelectorValueCSS($selectorValue, $value){

		if($value === null || $value === "")
			return "";

		$css = str_replace(self::SELECTOR_VALUE_PLACEHOLDER, $value, $selectorValue);

		return $css;
	}

	/**
	 * prepare css selector field css
	 */
	private function prepareCSSSelectorFieldCSS($selectorValue, $value){

		if (is_array($value) === false)
			return $this->prepareCSSSelectorValueCSS($selectorValue, $value);

		if(array_key_exists("top", $value) === true
			&& array_key_exists("right", $value) === true
			&& array_key_exists("bottom", $value) === true
			&& array_key_exists("left", $value) === true
			&& array_key_exists("unit", $value) === true)
			return $this->prepareCSSSelectorDimentionsCSS($selectorValue, $value);

		if(array_key_exists("id", $value) === true
			&& array_key_exists("url", $value) === true
			&& array_key_exists("size", $value) === true)
			return $this->prepareCSSSelectorImageCSS($selectorValue, $value);

		if(array_key_exists("size", $value) === true
			&& array_key_exists("unit", $value) === true)
			return $this->prepareCSSSelectorSliderCSS($selectorValue, $value);

		UniteFunctionsUC::throwError(__FUNCTION__ . " Error: Value processing is not implemented (" . json_encode($value) . ")");
	}

	/**
	 * prepare css selector fields style
	 */
	private function prepareCSSSelectorFieldsStyle($fields, $selector, $value){

		$css = "";

		foreach($fields as $fieldName => $selectorValue){
			$fieldValue = UniteFunctionsUC::getVal($value, $fieldName);
			$css .= $this->prepareCSSSelectorFieldCSS($selectorValue, $fieldValue);
		}

		return $this->prepareCSSSelectorStyle($selector, $css);
	}

	/**
	 * prepare css selector responsive fields style
	 */
	private function prepareCSSSelectorResponsiveFieldsStyle($fields, $selector, $value){

		$style = "";

		$responsive = array(
			"desktop" => "",
			"tablet" => "_tablet",
			"mobile" => "_mobile",
		);

		foreach($responsive as $device => $suffix){
			$css = "";

			foreach($fields as $fieldName => $selectorValue){
				$fieldValue = UniteFunctionsUC::getVal($value, $fieldName . $suffix);
				$css .= $this->prepareCSSSelectorFieldCSS($selectorValue, $fieldValue);
			}

			$style .= $this->prepareCSSSelectorStyle($selector, $css, $device);
		}

		return $style;
	}

	/**
	 * prepare css selector style
	 */
	private function prepareCSSSelectorStyle($selector, $css, $device = "desktop"){

		if(empty($css) === true)
			return "";

		$style = $selector . "{" . $css . "}";

		switch($device){
			case "tablet":
				$style = HelperHtmlUC::wrapCssMobile($style, true);
			break;
			case "mobile":
				$style = HelperHtmlUC::wrapCssMobile($style);
			break;
		}

		return $style;
	}

	/**
	 * process param css selector
	 */
	private function processParamCSSSelector($param){

		$selector = UniteFunctionsUC::getVal($param, "selector");
		$selector = trim($selector);

		if(empty($selector) === true) {
			$selector = array(
				UniteFunctionsUC::getVal($param, "selector1"),
				UniteFunctionsUC::getVal($param, "selector2"),
				UniteFunctionsUC::getVal($param, "selector3"),
			);

			$selector = array_map("trim", $selector);
			$selector = array_filter($selector);
			$selector = array_unique($selector);
			$selector = implode(",", $selector);
		}

		if(empty($selector) === true){
			$this->lastSelectorStyle = null;

			return null;
		}

		$type = UniteFunctionsUC::getVal($param, "type");

		switch($type){
			case UniteCreatorDialogParam::PARAM_NUMBER:
				$style = $this->processParamCSSSelector_number($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_BACKGROUND:
				$style = $this->processParamCSSSelector_background($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_BORDER:
				$style = $this->processParamCSSSelector_border($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_PADDING:
			case UniteCreatorDialogParam::PARAM_MARGINS:
			case UniteCreatorDialogParam::PARAM_BORDER_DIMENTIONS:
				$style = $this->processParamCSSSelector_dimentions($param, $selector, $type);
			break;
			case UniteCreatorDialogParam::PARAM_SLIDER:
				$style = $this->processParamCSSSelector_slider($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_TYPOGRAPHY:
				$style = $this->processParamCSSSelector_typography($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_TEXTSHADOW:
				$style = $this->processParamCSSSelector_textShadow($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_BOXSHADOW:
				$style = $this->processParamCSSSelector_boxShadow($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_CSS_FILTERS:
				$style = $this->processParamCSSSelector_cssFilters($param, $selector);
			break;
			case UniteCreatorDialogParam::PARAM_DROPDOWN:
			case UniteCreatorDialogParam::PARAM_COLORPICKER:
				$style = $this->processParamCSSSelector_value($param, $selector);
			break;
		}

		if(empty($style) === true){
			$this->lastSelectorStyle = null;

			return null;
		}

		UniteProviderFunctionsUC::printCustomStyle($style);

		$this->lastSelectorStyle = $style;

		return $style;
	}

	/**
	 * check what params has selectors in them, and include their css
	 */
	private function processPreviewParamsSelectors($isOutput = false){

		$mainParams = $this->addon->getParams();

		if(empty($mainParams) === true)
			return null;

		$styles = "";

		foreach($mainParams as $param){

			$this->processParamCSSSelector($param);

			if($isOutput === true && empty($this->lastSelectorStyle) === false)
				$styles .= $this->lastSelectorStyle;
		}

		if($isOutput === true)
			return $styles;

		return null;
	}

	/**
	 * get selectors css
	 */
	public function getSelectorsCss(){

		$style = $this->processPreviewParamsSelectors(true);


		return $style;
	}

	/**
	 * get addon preview html
	 */
	public function getPreviewHtml(){

		$this->validateInited();

		$outputs = "";

		$title = $this->addon->getTitle();
		$title .= " ". esc_html__("Preview","unlimited-elements-for-elementor");
		$title = htmlspecialchars($title);

		//get libraries, but not process provider
		$htmlBody = $this->getHtmlBody(false);

		$arrIncludes = $this->getProcessedIncludes(true, false);

		$arrIncludes = $this->modifyPreviewIncludes($arrIncludes);

		$htmlInlcudesCss = $this->getHtmlIncludes($arrIncludes,"css");
		$htmlInlcudesJS = $this->getHtmlIncludes($arrIncludes,"js");

		//process selectors only for preview (for elementor outputs will be used elementor)
		$this->processPreviewParamsSelectors();

		$arrCssCustomStyles = UniteProviderFunctionsUC::getCustomStyles();

		$htmlCustomCssStyles = HelperHtmlUC::getHtmlCustomStyles($arrCssCustomStyles);

		$arrJsCustomScripts = UniteProviderFunctionsUC::getCustomScripts();
		$htmlJSScripts = HelperHtmlUC::getHtmlCustomScripts($arrJsCustomScripts);

		$options = $this->addon->getOptions();

		$bgCol = $this->addon->getOption("preview_bgcol");
		$previewSize = $this->addon->getOption("preview_size");

		$previewWidth = "100%";

		switch($previewSize){
			case "column":
				$previewWidth = "300px";
			break;
			case "custom":
				$previewWidth = $this->addon->getOption("preview_custom_width");
				if(!empty($previewWidth)){
					$previewWidth = (int)$previewWidth;
					$previewWidth .= "px";
				}
			break;
		}


		$style = "";
		$style .= "max-width:{$previewWidth};";
		$style .= "background-color:{$bgCol};";

		$urlPreviewCss = GlobalsUC::$urlPlugin."css/unitecreator_preview.css";

		$html = "";
		$htmlHead = "";

		$htmlHead = "<!DOCTYPE html>".self::BR;
		$htmlHead .= "<html>".self::BR;

		//output head
		$htmlHead .= self::TAB."<head>".self::BR;
		$html .= $htmlHead;

		//get head html
		$htmlHead .= self::TAB2."<title>{$title}</title>".self::BR;
		$htmlHead .= self::TAB2."<link rel='stylesheet' href='{$urlPreviewCss}' type='text/css'>".self::BR;
		$htmlHead .= $htmlInlcudesCss;

		if(!empty($htmlCustomCssStyles))
			$htmlHead .= self::BR.$htmlCustomCssStyles;

		$html .= $htmlHead;
		$output["head"] = $htmlHead;

		$htmlAfterHead = "";
		$htmlAfterHead .= self::TAB."</head>".self::BR;

		//output body
		$htmlAfterHead .= self::TAB."<body>".self::BR;
		$htmlAfterHead .= self::BR.self::TAB2."<div class='uc-preview-wrapper' style='{$style}'>";
		$htmlAfterHead .= self::BR.$htmlBody;
		$htmlAfterHead .= self::BR.self::TAB2."</div>";

		$html .= $htmlAfterHead;
		$output["after_head"] = $htmlAfterHead;

		$htmlEnd = "";
		$htmlEnd .= $htmlInlcudesJS.self::BR;
		$htmlEnd .= $htmlJSScripts.self::BR;

		$htmlEnd .= self::BR.self::TAB."</body>".self::BR;
		$htmlEnd .= "</html>";

		$html .= $htmlEnd;
		$output["end"] = $htmlEnd;

		$output["full_html"] = $html;


		return($output);
	}



	/**
	 * put html preview
	 */
	public function putPreviewHtml(){

		$output = $this->getPreviewHtml();

		echo UniteProviderFunctionsUC::escCombinedHtml($output["head"]);

		//$this->putPreviewHtml_headerAdd();

		echo UniteProviderFunctionsUC::escCombinedHtml($output["after_head"]);

		$this->putPreviewHtml_footerAdd();

		echo UniteProviderFunctionsUC::escCombinedHtml($output["end"]);
	}

	private function a________DYNAMIC___________(){}


	/**
	 * init dynamic params
	 */
	protected function initDynamicParams(){

		$isDynamicAddon = UniteFunctionsUC::getVal($this->arrOptions, "dynamic_addon");
		$isDynamicAddon = UniteFunctionsUC::strToBool($isDynamicAddon);

		if($isDynamicAddon == false)
			return(false);

		$postID = $this->getDynamicPostID();

		if(!empty($postID)){

			$arrPostAdditions = HelperProviderUC::getPostAdditionsArray_fromAddonOptions($this->arrOptions);

			$this->addPostParamToAddon($postID, $arrPostAdditions);
		}

	}


	/**
	 * get post ID
	 */
	protected function getDynamicPostID(){

		$postID = "";

		//get post from preview
		if($this->isModePreview){

			$postID = UniteFunctionsUC::getVal($this->arrOptions, "dynamic_post");

			return($postID);
		}

		//if not preview get the current post

		$post = get_post();

		if(!empty($post))
			$postID = $post->ID;

		return($postID);
	}


	/**
	 * add post param to addon
	 */
	private function addPostParamToAddon($postID, $arrPostAdditions){

		$arrParam = array();
		$arrParam["type"] = UniteCreatorDialogParam::PARAM_POST;
		$arrParam["name"] = "current_post";
		$arrParam["default_value"] = $postID;
		$arrParam["post_additions"] = $arrPostAdditions;


		$this->addon->addParam($arrParam);
	}

	private function ___________DEBUG_DATA___________(){}

	/**
	 * check and output debug if needed
	 */
	public function checkOutputDebug($objAddon = null){

		if(empty($objAddon))
			$objAddon = $this->addon;

		$arrValues = $objAddon->getOriginalValues();

		if(empty($arrValues))
			return(false);

		$isShowData = UniteFunctionsUC::getVal($arrValues, "show_widget_debug_data");

		$isShowData = UniteFunctionsUC::strToBool($isShowData);

		if($isShowData == false)
			return(false);

		$dataType = UniteFunctionsUC::getVal($arrValues, "widget_debug_data_type");

		$this->showDebugData($isShowData, $dataType, $arrValues);

	}


	/**
	 * set to show debug data of the addon
	 */
	public function showDebugData($isShow = true, $dataType = null, $arrValues = null){

		$this->isShowDebugData = $isShow;
		$this->debugDataType = $dataType;

		$this->valuesForDebug = $arrValues;

	}


	/**
	 * put debug data html
	 */
	private function putDebugDataHtml_default($arrData, $arrItemData){

		$isShowData = $this->debugDataType != "items_only";

		$html = "";

		if($isShowData == true){

			//modify the data
			$arrData = UniteFunctionsUC::modifyDataArrayForShow($arrData);

			$html .= dmpGet($arrData);
		}

		//show settings values

		if($this->debugDataType == "settings_values"){

			$html .= dmpGet("<b>----------- Settings Values -----------</b>");

			$html .= dmpGet($this->valuesForDebug);
		}


		$html .= dmpGet("<b>Widget Items Data</b>");

		if(empty($arrItemData)){
			$html .= dmpGet("no items found");
			return($html);
		}

		$arrItemData = $this->modifyItemsDataForShow($arrItemData);

		$html .= dmpGet($arrItemData);

		return($html);
	}

	/**
	 * modify debug array
	 */
	private function modifyDebugArray($arrDebug){

		if(is_array($arrDebug) == false)
			$arrDebug = (array)$arrDebug;

		if(empty($arrDebug))
			return($arrDebug);

		$output = array();

		foreach($arrDebug as $key => $value){

			if(is_array($value) && count($value) == 1)
				$value = $value[0];

			if(is_string($value) == false)
				continue;

			$value = htmlspecialchars($value);

			if(strlen($value) > 200)
				$value = substr($value, 0, 200)."...";

			$key = " ".$key;

			$output[$key] = $value;
		}


		return($output);
	}


	/**
	 * put debug data - current post
	 */
	private function putDebugDataHTML_currentPostData(){

		$post = get_post();

		if(empty($post)){

			$html = "no current post found";

			return($html);
		}

		$arrPost = $this->modifyDebugArray($post);

		$html = htmlGet("<b> ------- Post  ------- </b>");

		$html .= htmlGet($arrPost);

		dmp("<b> ------- Post Meta ------- </b>");

		$meta = get_post_meta($post->ID);

		$meta = $this->modifyDebugArray($meta);

		$html .= htmlGet($meta);

		$html .= htmlGet("<b> ----------Terms--------- </b>");

		$terms = UniteFunctionsWPUC::getPostTerms($post);

		$html .= htmlGet($terms);

		return($html);
	}

	/**
	 * put debug data - posts
	 */
	private function putDebugDataHtml_posts($arrItemData){

		$numPosts = count($arrItemData);

		$html = "";

		$html .= dmpGet("Found $numPosts posts.");

		if(empty($arrItemData))
			return($html);

		$isShowMeta = ($this->debugDataType == "post_meta");

		foreach($arrItemData as $index => $item){

			$isPost = false;
			if($item instanceof WP_Post)
				$isPost = true;

			if($isPost == false){

				$item = UniteFunctionsUC::getVal($item, "item");

				$postData = UniteFunctionsUC::getArrFirstValue($item);

				$title = UniteFunctionsUC::getVal($postData, "title");
				$alias = UniteFunctionsUC::getVal($postData, "alias");
				$id = UniteFunctionsUC::getVal($postData, "id");
				$post = get_post($id);

			}else{

				$post = $item;
				$title = $post->post_title;
				$id = $post->ID;
				$alias = $post->post_name;
			}

			$num = $index+1;

			$status = $post->post_status;
			$menuOrder = $post->menu_order;

			$arrTermsNames = UniteFunctionsWPUC::getPostTermsTitles($post, true);

			$strTerms = implode(",", $arrTermsNames);

			$htmlAfterAlias = "";
			if($status != "publish")
				$htmlAfterAlias = ", [$status post]";

			$text = "{$num}. <b>$title</b> (<i style='font-size:13px;'>$alias{$htmlAfterAlias}, $id | $strTerms </i>), menu order: $menuOrder";

			$html .= dmpGet($text);

			if($isShowMeta == false)
				continue;

			$postMeta = get_post_meta($id, "", false);

			$postMeta = UniteFunctionsUC::modifyDataArrayForShow($postMeta, true);

			$html .= dmpGet($postMeta);

			//$postMeta = get_post_meta($post_id)

			return($html);
		}


	}

	/**
	 * get items from listing
	 */
	private function putDebugDataHtml_getItemsFromListing($paramListing, $arrData){

		$name = UniteFunctionsUC::getVal($paramListing, "name");

		$source = UniteFunctionsUC::getVal($arrData, $name."_source");

		$arrItemsRaw = UniteFunctionsUC::getVal($arrData, $name."_items");

		if(empty($arrItemsRaw))
			$arrItemsRaw = array();

		$useFor = UniteFunctionsUC::getVal($paramListing, "use_for");
    	$useForGallery = ($useFor == "gallery");


		$arrItems = array();
		foreach($arrItemsRaw as $item){

			if($useForGallery == true && isset($item["postid"])){

				$post = get_post($item["postid"]);
				$arrItems[] = $post;
				continue;
			}

			$object = UniteFunctionsUC::getVal($item, "object");
			$arrItems[] = $object;
		}

		return($arrItems);
	}

	/**
	 * put debug data
	 */
	private function putDebugDataHtml($arrData, $arrItemData){

		$html = "<div style='font-size:16px;color:black;text-decoration:none;background-color:white;padding:3px;'>";

		$html .= dmpGet("<b>Widget Debug Data</b> (turned on by setting in widget advanced section)<br>",true);

		//get data from listing
		$paramListing = $this->addon->getListingParamForOutput();

		if(!empty($paramListing) && $this->itemsType == "template"){

			$arrItemData = $this->putDebugDataHtml_getItemsFromListing($paramListing, $arrData);
		}


		switch($this->debugDataType){
			case "post_titles":
			case "post_meta":

				$html .= $this->putDebugDataHtml_posts($arrItemData);

			break;
			case "current_post_data":

				$html .= $this->putDebugDataHTML_currentPostData();

			break;
			default:
				$html .= $this->putDebugDataHtml_default($arrData, $arrItemData);
			break;
		}

		$html .= "</div>";

		$this->htmlDebug = $html;
	}


	private function a________GENERAL___________(){}


	/**
	 * modify items data for show
	 */
	private function modifyItemsDataForShow($arrItemData){

		if(is_array($arrItemData) == false)
			return(null);

		$arrItemsForShow = array();


		foreach($arrItemData as $item){

			if(is_array($item) == false){
				$arrItemsForShow[] = $item;
				continue;
			}


			$item = UniteFunctionsUC::getVal($item, "item");

			$itemFirstValue = UniteFunctionsUC::getArrFirstValue($item);

			if(is_array($itemFirstValue))
				$item = UniteFunctionsUC::modifyDataArrayForShow($itemFirstValue);
			else
				$item = UniteFunctionsUC::modifyDataArrayForShow($item);

			$arrItemsForShow[] = $item;
		}

		return($arrItemsForShow);
	}




	/**
	 * process html before output, function for override
	 */
	protected function processHtml($html){

		return($html);
	}



	/**
	 * get only processed html template
	 */
	public function getProcessedHtmlTemplate(){

		$html = $this->objTemplate->getRenderedHtml(self::TEMPLATE_HTML);
		$html = $this->processHtml($html);

		return($html);
	}

	/**
	 * get items html
	 */
	public function getHtmlItems(){

		$keyTemplate = "uc_template_items_special";
		$htmlTemplate = "{{put_items()}}";

		$keyTemplate2 = "uc_template_items_special2";
		$htmlTemplate2 = "{{put_items2()}}";

		$this->objTemplate->addTemplate($keyTemplate, $htmlTemplate, false);
		$this->objTemplate->addTemplate($keyTemplate2, $htmlTemplate2, false);

		$html = $this->objTemplate->getRenderedHtml($keyTemplate);
		$html2 = $this->objTemplate->getRenderedHtml($keyTemplate2);

		$html = $this->processHtml($html);
		$html2 = $this->processHtml($html2);

		$output = array();
		$output["html_items1"] = $html;
		$output["html_items2"] = $html2;

		return($output);
	}


	/**
	 * get only html template output, no css and no js
	 */
	public function getHtmlOnly(){

		$this->validateInited();

		$html = $this->objTemplate->getRenderedHtml(self::TEMPLATE_HTML);
		$html = $this->processHtml($html);

		return($html);
	}

	/**
	 * get script handle with serial
	 */
	private function getScriptHandle($handle){

		if(isset(self::$arrScriptsHandles[$handle]) == false){
			self::$arrScriptsHandles[$handle] = true;
			return($handle);
		}

		$counter = 2;

		do{

			$outputHandle = $handle.$counter;

			$isExists = isset(self::$arrScriptsHandles[$outputHandle]);

			$counter++;

		}while($isExists);

			self::$arrScriptsHandles[$outputHandle] = true;

		return($outputHandle);
	}

	/**
	 * place output by shortcode
	 */
	public function getHtmlBody($scriptHardCoded = true, $putCssIncludes = false, $putCssInline = true, $params = null){

		$this->validateInited();

		
		//render the js inside "template" tag always if available
		
		if(GlobalsProviderUC::$renderJSForHiddenContent == true)
			$scriptHardCoded = true;
			
		
		$title = $this->addon->getTitle(true);

		$isOutputComments = HelperProviderCoreUC_EL::getGeneralSetting("output_wrapping_comments");

		$settings = HelperProviderCoreUC_EL::getGeneralSettingsValues();
		$isOutputComments = UniteFunctionsUC::strToBool($isOutputComments);

		try{

			$html = $this->objTemplate->getRenderedHtml(self::TEMPLATE_HTML);
			$html = $this->processHtml($html);

			if(!empty($this->htmlDebug)){

				$html = $this->htmlDebug . $html;

			}

			//make css
			$css = $this->objTemplate->getRenderedHtml(self::TEMPLATE_CSS);

			$js = $this->objTemplate->getRenderedHtml(self::TEMPLATE_JS);

			//fetch selectors (add google font includes on the way)

			$isAddSelectors = UniteFunctionsUC::getVal($params, "add_selectors_css");
			$isAddSelectors = UniteFunctionsUC::strToBool($isAddSelectors);

			$cssSelectors = "";

			if($isAddSelectors == true)
				$cssSelectors = $this->getSelectorsCss();


			//get css includes if needed

			$arrCssIncludes = array();
			if($putCssIncludes == true)
				$arrCssIncludes = $this->getProcessedIncludes(true, true, "css");

			if($isOutputComments == true)
				$output = "<!-- start {$title} -->";
			else
				$output = "";

			//add css includes if needed

			if(!empty($arrCssIncludes)){

				$htmlIncludes = $this->getHtmlIncludes($arrCssIncludes);

				if(self::$isBufferingCssActive == true)
					self::$bufferCssIncludes .= self::BR.$htmlIncludes;
				else
					$output .= "\n".$htmlIncludes;

			}

			//add css
			if(!empty($css)){

				$css = "/* widget: $title */".self::BR2.$css.self::BR2;

				if(self::$isBufferingCssActive == true){

					//add css to buffer
					if(!empty(self::$bufferBodyCss))
						self::$bufferBodyCss .= self::BR2;

					self::$bufferBodyCss .= $css;

				}else{

					if($putCssInline == true)
						$output .= "\n			<style type=\"text/css\">{$css}</style>";
					else
						HelperUC::putInlineStyle($css);

				}

			}

			//add css selectors:
			if($isAddSelectors == true){

				$selectorsStyleID = "selectors_css_".$this->generatedID;

				if(empty($cssSelectors))
					$cssSelectors = "";

				$output .= "\n			<style id=\"{$selectorsStyleID}\" name=\"uc_selectors_css\" type=\"text/css\">{$cssSelectors}</style>";
			}


			//add html

			$output .= "\n\n			".$html;

			$isOutputJs = false;
			if(!empty($js))
				$isOutputJs = true;

			if(isset($params["wrap_js_start"]) || isset($params["wrap_js_timeout"]))
				$isOutputJs = true;

			//output js

			if($isOutputJs == true){

				$isJSAsModule = $this->addon->getOption("js_as_module");
				$isJSAsModule = UniteFunctionsUC::strToBool($isJSAsModule);

				$title = $this->addon->getTitle();

				$js = "\n/* $title scripts: */ \n\n".$js;

				$addonName = $this->addon->getAlias();

				$handle = $this->getScriptHandle("ue_script_".$addonName);
				
				if($scriptHardCoded == false){
					UniteProviderFunctionsUC::printCustomScript($js, false, $isJSAsModule, $handle);
				}
				else{
					$wrapInTimeout = UniteFunctionsUC::getVal($params, "wrap_js_timeout");
					$wrapInTimeout = UniteFunctionsUC::strToBool($wrapInTimeout);

					$wrapStart = UniteFunctionsUC::getVal($params, "wrap_js_start");
					$wrapEnd = UniteFunctionsUC::getVal($params, "wrap_js_end");

					$jsType = "text/javascript";
					if($isJSAsModule == true)
						$jsType = "module";

					$htmlHandle = "";
					if($wrapInTimeout == false){	 //add id's in front
						$htmlHandle = " id=\"{$handle}\"";
					}

					$output .= "\n\n			<script type=\"{$jsType}\" {$htmlHandle} >";
					
					//$output .= "console.log('run script: ".$addonName."');";	//uncomment for some tests if needed
					
					if(!empty($wrapStart))
						$output .= "\n		".$wrapStart;

					if($wrapInTimeout == true)
						$output .= "\n			setTimeout(function(){";

					$output .= "\n			".$js;

					if($wrapInTimeout == true)
						$output .= "\n			},300);";

					if(!empty($wrapEnd))
						$output .= "\n		".$wrapEnd;

					$output .= "\n			</script>";
				}

			}

			if($isOutputComments == true)
				$output .= "\n			<!-- end {$title} -->";


		}catch(Exception $e){

			$message = $e->getMessage();

			$message = "Error in widget $title, ".$message;

			if(GlobalsUC::$SHOW_TRACE == true){

				dmp($message);
				UniteFunctionsUC::throwError($e);
			}


			UniteFunctionsUC::throwError($message);
		}

		return($output);
	}

	/**
	 * get addon uc_id
	 */
	public function getWidgetID(){

		$data = $this->getConstantData();

		$widgetID = UniteFunctionsUC::getVal($data, "uc_id");

		return($widgetID);
	}


	/**
	 * get addon contstant data that will be used in the template
	 */
	public function getConstantData(){

		$this->validateInited();

		if(!empty($this->cacheConstants))
			return($this->cacheConstants);

		$data = array();

		$prefix = "ucid";
		if($this->isInited)
			$prefix = "uc_".$this->addon->getName();

		//add serial number:
		self::$serial++;

		//set output  widget id

		$generatedSerial = self::$serial.UniteFunctionsUC::getRandomString(4, true);

		if(!empty($this->systemOutputID))
			$generatedID = $prefix."_".$this->systemOutputID;
		else
			$generatedID = $prefix.$generatedSerial;

		//protection in listings
		if(isset(self::$arrGeneratedIDs[$generatedID]))
			$generatedID .= self::$serial;

		//double protection
		if(isset(self::$arrGeneratedIDs[$generatedID]))
			$generatedID .= $generatedSerial;


		self::$arrGeneratedIDs[$generatedID] = true;

		$this->generatedID = $generatedID;


		$data["uc_serial"] = $generatedSerial;
		$data["uc_id"] = $this->generatedID;

		//add assets url
		$urlAssets = $this->addon->getUrlAssets();
		if(!empty($urlAssets))
			$data["uc_assets_url"] = $urlAssets;

		//set if it's for editor
		$isInsideEditor = false;
		if($this->processType == UniteCreatorParamsProcessor::PROCESS_TYPE_OUTPUT_BACK)
			$isInsideEditor = true;

		//$data["is_inside_editor"] = $isInsideEditor;

		$data = UniteProviderFunctionsUC::addSystemConstantData($data);

		$data = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_ADD_ADDON_OUTPUT_CONSTANT_DATA, $data);

		$this->cacheConstants = $data;

		return($data);
	}


	/**
	 * get item extra variables
	 */
	public function getItemConstantDataKeys(){

		$arrKeys = array(
				"item_id",
				"item_index",
				"item_repeater_class",
		);

		return($arrKeys);
	}



	/**
	 * get constant data keys
	 */
	public function getConstantDataKeys($filterPlatformKeys = false){

		$constantData = $this->getConstantData();

		if($filterPlatformKeys == true){
			unset($constantData["uc_platform"]);
			unset($constantData["uc_platform_title"]);
		}

		$arrKeys = array_keys($constantData);

		return($arrKeys);
	}


	/**
	 * get addon params
	 */
	private function getAddonParams(){

		if(!empty($this->paramsCache))
			return($this->paramsCache);

		$this->paramsCache = $this->addon->getProcessedMainParamsValues($this->processType);

		return($this->paramsCache);
	}


	/**
	 * modify items data, add "item" to array
	 */
	protected function normalizeItemsData($arrItems, $extraKey=null, $addObjectID = false){

		if(empty($arrItems))
			return(array());

		foreach($arrItems as $key=>$item){

			if(!empty($extraKey)){
				$arrAdd = array($extraKey=>$item);

				//add object id
				if($addObjectID === true){

					$objectID = UniteFunctionsUC::getVal($item, "id");
					if(!empty($objectID))
						$arrAdd["object_id"] = $objectID;

					$postType = UniteFunctionsUC::getVal($item, "post_type");
					if(!empty($postType))
						$arrAdd["object_type"] = $postType;

				}

			}
			else
				$arrAdd = $item;


			$arrItems[$key] = array("item"=>$arrAdd);
		}

		return($arrItems);
	}

	/**
	 * get special items - instagram
	 */
	private function getItemsSpecial_Instagram($arrData){

		$paramInstagram = $this->addon->getParamByType(UniteCreatorDialogParam::PARAM_INSTAGRAM);
		$instaName = UniteFunctionsUC::getVal($paramInstagram, "name");
		$dataInsta = $arrData[$instaName];

		$instaMain = UniteFunctionsUC::getVal($dataInsta, "main");
		$instaItems = UniteFunctionsUC::getVal($dataInsta, "items");
		$error = UniteFunctionsUC::getVal($dataInsta, "error");

		if(empty($instaMain))
			$instaMain = array();

		$instaMain["hasitems"] = !empty($instaItems);

		if(!empty($error))
			$instaMain["error"] = $error;

		$arrItemData = $this->normalizeItemsData($instaItems, $instaName);
		$arrData[$instaName] = $instaMain;

		$output = array();
		$output["main"] = $arrData;
		$output["items"] = $arrItemData;

		return($output);
	}

	/**
	 * get params for modify
	 */
	private function modifyTemplatesForOutput_getParamsForModify(){

		$arrParams = $this->addon->getParams();

		$arrParamsForModify = array();

		foreach($arrParams as $param){

			$type = UniteFunctionsUC::getVal($param, "type");

			if($type != UniteCreatorDialogParam::PARAM_SPECIAL)
				continue;

			$attributeType = UniteFunctionsUC::getVal($param, "attribute_type");

			switch($attributeType){

				case "schema":
				case "entrance_animation":

					$param["modify_type"] = $attributeType;

					$arrParamsForModify[] = $param;
				break;
			}
		}

		return($arrParamsForModify);
	}


	/**
	 * modify template for output, add some code according the params
	 */
	private function modifyTemplatesForOutput($html, $css, $js){

		$isModify = false;

		$arrParams = $this->modifyTemplatesForOutput_getParamsForModify();

		if(empty($arrParams))
			return(null);

		foreach($arrParams as $param){

			$name = UniteFunctionsUC::getVal($param, "name");
			$type = UniteFunctionsUC::getVal($param, "modify_type");

			switch($type){
				case "entrance_animation":

					$css = "{{ucfunc(\"put_entrance_animation_css\",\"{$name}\")}}\n\n".$css;
					$js = "{{ucfunc(\"put_entrance_animation_js\",\"{$name}\")}}\n\n".$js;

					$isModify = true;
				break;
				case "schema":

					$html .= "{{ucfunc(\"put_schema_items_json_byparam\",\"{$name}\")}}\n\n";

					$isModify = true;
				break;
			}

		}

		if($isModify == false)
			return(null);


		$output = array();
		$output["html"] = $html;
		$output["css"] = $css;
		$output["js"] = $js;

		return($output);
	}


	/**
	 * init the template
	 */
	private function initTemplate(){

		$this->validateInited();

		//set params
		$arrData = $this->getConstantData();

		$arrParams = $this->getAddonParams();

		$arrData = array_merge($arrData, $arrParams);

		//set templates
		$html = $this->addon->getHtml();
		$css = $this->addon->getCss();

		//set item css call
		$cssItem = $this->addon->getCssItem();
		$cssItem = trim($cssItem);
		if(!empty($cssItem))
			$css .= "\n{{put_css_items()}}";


		$js = $this->addon->getJs();

		$arrModify = $this->modifyTemplatesForOutput($html, $css, $js);

		if(!empty($arrModify)){
			$html = $arrModify["html"];
			$css = $arrModify["css"];
			$js = $arrModify["js"];
		}


		$this->objTemplate->setAddon($this->addon);

		$this->objTemplate->addTemplate(self::TEMPLATE_HTML, $html);
		$this->objTemplate->addTemplate(self::TEMPLATE_CSS, $css);
		$this->objTemplate->addTemplate(self::TEMPLATE_JS, $js);

		//add custom templates

		$arrCustomTemplates = array();

		$arrCustomTemplates = apply_filters("ue_get_twig_templates", $arrCustomTemplates);

		if(!empty($arrCustomTemplates)){

			foreach($arrCustomTemplates as $templateName=>$templateValue)
				$this->objTemplate->addTemplate($templateName, $templateValue);
		}


		$arrItemData = null;

		$paramPostsList = null;

		$itemsSource = null;		//from what object the items came from

		//set items template
		if($this->isItemsExists == false){

			$this->objTemplate->setParams($arrData);
		}
		else{		//items exists

			if($this->processType == UniteCreatorParamsProcessor::PROCESS_TYPE_CONFIG)
				$arrItemData = array();
			else
			switch($this->itemsType){
				case "instagram":

					$response = $this->getItemsSpecial_Instagram($arrData);
					$arrData = $response["main"];
					$arrItemData = $response["items"];

				break;
				case "post":		//move posts data from main to items

					$paramPostsList = $this->addon->getParamByType(UniteCreatorDialogParam::PARAM_POSTS_LIST);

					if(empty($paramPostsList))
						UniteFunctionsUC::throwError("Some posts list param should be found");

					$postsListName = UniteFunctionsUC::getVal($paramPostsList, "name");

					$arrItemData = $this->normalizeItemsData($arrData[$postsListName], $postsListName, true);

					//set main param (true/false)
					$arrData[$postsListName] = !empty($arrItemData);

					$itemsSource = "posts";

				break;
				case UniteCreatorAddon::ITEMS_TYPE_DATASET:

					$paramDataset = $this->addon->getParamByType(UniteCreatorDialogParam::PARAM_DATASET);
					if(empty($paramDataset))
						UniteFunctionsUC::throwError("Dataset param not found");

					$datasetType = UniteFunctionsUC::getVal($paramDataset, "dataset_type");
					$datasetQuery = UniteFunctionsUC::getVal($paramDataset, "dataset_{$datasetType}_query");

					$arrRecords = array();
					$arrItemData = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_GET_DATASET_RECORDS, $arrRecords, $datasetType, $datasetQuery);

					if(!empty($arrItemData)){

						$paramName = $paramDataset["name"];
						$arrItemData = $this->normalizeItemsData($arrItemData, $paramName);
					}

				break;
				case "listing":

					$paramListing = $this->addon->getListingParamForOutput();

					if(empty($paramListing))
						UniteFunctionsUC::throwError("Some listing param should be found");

					$paramName = UniteFunctionsUC::getVal($paramListing, "name");

					$arrItemData = UniteFunctionsUC::getVal($arrData, $paramName."_items");

					if(empty($arrItemData))
						$arrItemData = array();
					else
						$arrItemData = $this->normalizeItemsData($arrItemData, $paramName);

				break;
				case "multisource":

					$paramListing = $this->addon->getListingParamForOutput();

					if(empty($paramListing))
						UniteFunctionsUC::throwError("Some multisource dynamic attribute should be found");

					$paramName = UniteFunctionsUC::getVal($paramListing, "name");

					$dataValue = UniteFunctionsUC::getVal($arrData, $paramName);

					if(is_string($dataValue) && $dataValue === "uc_items"){

						$arrItemData = $this->addon->getProcessedItemsData($this->processType);

					}
					elseif(is_array($dataValue)){

						$arrItemData = $dataValue;
					}else{

						dmp($arrItemData);
						UniteFunctionsUC::throwError("Wrong multisouce data");
					}


					UniteCreatetorParamsProcessorMultisource::checkShowItemsDebug($arrItemData);


				break;
				default:

					$arrItemData = $this->addon->getProcessedItemsData($this->processType);
				break;
			}

			//some small protection
			if(empty($arrItemData))
				$arrItemData = array();

			$itemIndex = 0;
			foreach($arrItemData as $key=>$item){

			    $arrItem = $item["item"];

			    $itemIndex++;

			    $arrItem["item_index"] = $itemIndex;
			    $arrItem["item_id"] = $this->generatedID."_item".$itemIndex;

			    $arrItemData[$key]["item"] = $arrItem;
			}

			$this->objTemplate->setParams($arrData);

			$this->objTemplate->setArrItems($arrItemData);

			if(!empty($itemsSource))
				$this->objTemplate->setItemsSource($itemsSource);


			$htmlItem = $this->addon->getHtmlItem();

			$this->objTemplate->addTemplate(self::TEMPLATE_HTML_ITEM, $htmlItem);

			$htmlItem2 = $this->addon->getHtmlItem2();
			$this->objTemplate->addTemplate(self::TEMPLATE_HTML_ITEM2, $htmlItem2);

			$this->objTemplate->addTemplate(self::TEMPLATE_CSS_ITEM, $cssItem);

		}

		if(!empty($paramPostsList)){
			$postListValue = UniteFunctionsUC::getVal($paramPostsList, "value");

			if(!empty($paramPostsList) && is_array($postListValue) )
				$arrData = array_merge($arrData, $postListValue);
		}

		//show debug data
		if($this->isShowDebugData == true)
			$this->putDebugDataHtml($arrData, $arrItemData);


	}


	/**
	 * preview addon mode
	 * dynamic addon should work from the settings
	 */
	public function setPreviewAddonMode(){

		$this->isModePreview = true;
	}

	/**
	 * set system output id for the generated id
	 */
	public function setSystemOutputID($systemID){

		$this->systemOutputID = $systemID;
	}


	/**
	 * init by addon
	 */
	public function initByAddon(UniteCreatorAddon $addon){

		if(empty($addon))
			UniteFunctionsUC::throwError("Wrong addon given");

		//debug data
		HelperUC::clearDebug();

		$this->isInited = true;

		$this->addon = $addon;
		$this->isItemsExists = $this->addon->isHasItems();

		$this->itemsType = $this->addon->getItemsType();

		$this->arrOptions = $this->addon->getOptions();

		//modify by special type

		switch($this->itemsType){
			case "instagram":
			case "post":
			case "listing":
			case "multisource":
				$this->isItemsExists = true;
			break;
		}

		$this->initDynamicParams();

		$this->initTemplate();

	}


}

?>
