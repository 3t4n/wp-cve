<?php
class ReadMoreIncludeManager {

	private $data;
	private $id;
	private $toggleContent;
	private $rel;
	private $dataObj;

	public function __call($name, $args) {

		$methodPrefix = substr($name, 0, 3);
		$methodProperty = lcfirst(substr($name,3));

		if ($methodPrefix == 'get') {
			return $this->$methodProperty;
		}
		else if ($methodPrefix == 'set') {
			$this->$methodProperty = $args[0];
		}
	}

	private function randomName($length = 10) {

		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}

	public function render() {

		if (!ReadMore::allowRender($this)) {
			return $this->getToggleContent();
		}

		$rel = 'yrm-'.esc_attr($this->randomName(5));
		$this->setRel($rel);

		$data = $this->includeData();
		$data .= $this->includeScripts();

		$loadDataAction = array(
			'isAdmin' => is_admin()
		);
		
		do_action('readMoreLoaded', $loadDataAction);
		return $data;
	}

	private function includeData(){

		$allContent = '';
		$id = $this->getId();
		$data = $this->getData();
		$buttonData = $this->buttonContent();
		$accordionContent = $this->accordionContent();

		if(isset($data['vertical']) && $data['vertical'] == 'top') {
			$allContent = $buttonData.$accordionContent;
			return $allContent;
		}

		$allContent = $accordionContent.$buttonData;

		return apply_filters('ycdReadMoreFinalContent', $allContent, $data, $id);
	}

	private function accordionContent() {

		$rel = $this->getRel();
		$id = $this->getId();
		$dataObj = $this->getDataObj();
		$data = $this->getData();

		$tag = 'div';
		$hideClassName = 'yrm-content-hide';
		$showStatus = 'false';
		$inlineStyles = 'visibility: hidden;height: 0;';
		$inline = @$dataObj->getOptionValue('add-button-next-content');
		if(!empty($inline)) {
			$tag = 'span';
		}

		if(!empty($data['default-show-hidden-content'])) {
			$inlineStyles = '';
			$hideClassName = '';
			$showStatus = 'true';
		}

		$dataAfterAction = $dataObj->getOptionValue('load-data-after-action');
		$inlineHidden = '';
		if (!empty($dataObj->getOptionValue('add-button-next-content'))) {
			$inlineHidden = 'yrm-inline-content';
		}
		$content = $this->filterToggleContent();
		return "<$tag class='yrm-content yrm-content-".esc_attr($id)." ".esc_attr($hideClassName)." ".esc_attr($inlineHidden)."' id='".esc_attr($rel)."' data-id='".esc_attr($id)."' data-show-status='".esc_attr($showStatus)."' data-after-action='".esc_attr($dataAfterAction)."' style=\"$inlineStyles\">
			<$tag id='yrm-inner-content-$rel' class='yrm-inner-content-wrapper yrm-cntent-".esc_attr($id)."'>$content</$tag>
		</$tag>";
	}
	
	private function filterToggleContent() {
		$content = $this->getToggleContent();
		
		if (YRM_PKG == YRM_FREE_PKG) {
			return $content;
		}
		$dataObj = $this->getDataObj();
		$dataAfterAction = $dataObj->getOptionValue('load-data-after-action');
		
		if (!empty($dataAfterAction)) {
			
			$id = $this->getId();
			$rel = $this->getRel();
			
			ReadMoreAdminHelperPro::saveHiddenContent($id, $rel, $content);
			$content = '';
		}
		return $content;
	}

	private function buttonContent() {

		$data = $this->getData();
		$dataObj = $this->getDataObj();
		$mode = $dataObj->getOptionvalue('yrm-dimension-mode');
		$modeClassName = '';
		$textWrapperClass = 'yrm-text-wrapper-custom-dimensions';

		if($mode == 'autoMode') {
			$textWrapperClass = '';
			$modeClassName = 'yrm-button-auto-mode';
		}

		$id = $this->getId();
		$rel = $this->getRel();
		$more = __('Read More', YRM_LANG);
		$lessName = __('Read Less', YRM_LANG);
		
		if (!empty($data['more-button-title'])) {
			$more = $data['more-button-title'];
		}
		if (!empty($data['attrMoreName'])) {
			$more = $data['attrMoreName'];
		}
		
		if (!empty($data['less-button-title'])) {
			$lessName = $data['less-button-title'];
		}
		if (!empty($data['attrLessName'])) {
			$lessName = $data['attrLessName'];
		}
		$type = $data['type'];
		$moreTitle = @$data['more-title'];
		$lessTitle = @$data['less-title'];

		$enableTooltip = @$data['enable-tooltip'];
		$tooltipText = @$data['enable-tooltip-text'];

		$buttonLabel = $more;
		if(!empty($data['default-show-hidden-content'])) {
			$buttonLabel = $lessName;
		}
		$moreButtonClassName = $dataObj->getOptionvalue('yrm-more-button-custom-class');
		$lessButtonClassName = $dataObj->getOptionvalue('yrm-less-button-custom-class');

		$tooltipWrapperClass = '';
		if (!empty($enableTooltip)) {
			$tooltipWrapperClass .= ' yrm-tooltip';
		}

		$button = "<div class='yrm-btn-wrapper yrm-btn-wrapper-".esc_attr($id)." yrm-more-button-wrapper ".esc_attr($moreButtonClassName)." ".esc_attr($tooltipWrapperClass)."'
		data-custom-more-class-name='".esc_attr($moreButtonClassName)."' data-custom-less-class-name='".esc_attr($lessButtonClassName)."'>";

		if($dataObj->getOptionvalue('show-content-gradient')) {
			$button .=	"<div class='yrm-content-gradient-".esc_attr($id)." yrm-content-gradient'></div>";
		}

		$button .= "<span title='".esc_attr($moreTitle)."' data-less-title='".esc_attr($lessTitle)."' data-more-title='".esc_attr($moreTitle)."' class='yrm-toggle-expand  yrm-toggle-expand-".esc_attr($id)." ".esc_attr($modeClassName)."' data-rel='".esc_attr($rel)."' data-more='".esc_attr($more)."' data-less='".esc_attr($lessName)."'>";

				// Copy to clipboard
				if (!empty($enableTooltip)) { $button .= $this->getToolTipContent($tooltipText); }

				$button .= "<span class='yrm-text-wrapper ".esc_attr($textWrapperClass)."'>";

				// Arrow image
				$button .= $this->getButtonIcon($dataObj, 'left');

				$button .= "<span class=\"yrm-button-text-$id yrm-button-text-span\">$buttonLabel</span>";
				$button .= $this->getButtonIcon($dataObj, 'right');

				$button .= "</span>";
			$button .= "</span>";
		$button .= "</div>";
		
		$inlineTypes = array('inline', 'inlinePopup', 'alink');
		$inlineTypes = apply_filters('yrmInlineTypes', $inlineTypes);
		if(in_array($type, $inlineTypes)) {
			$tag = 'div';
			$inlineClass = '';
			$inline = $dataObj->getOptionValue('add-button-next-content');
			if(!empty($inline)) {
				$tag = 'span';
				$inlineClass = 'yrm-btn-inline';
			}
			$button = "<$tag class='yrm-btn-wrapper yrm-inline-wrapper yrm-btn-wrapper-".esc_attr($id)." ".esc_attr($inlineClass)." yrm-more-button-wrapper ".esc_attr($tooltipWrapperClass)."'>";
				if($dataObj->getOptionvalue('show-content-gradient')) {
					$button .= "<".esc_attr($tag)." class='yrm-content-gradient-".esc_attr($id)." yrm-content-gradient'></".esc_attr($tag).">";
				}
			$button .= "<span title='".esc_attr($moreTitle)."' data-less-title='".esc_attr($lessTitle)."' data-more-title='".esc_attr($moreTitle)."'  class='yrm-toggle-expand  yrm-toggle-expand-".esc_attr($id)."' data-rel='".esc_attr($rel)."' data-more='".esc_attr($more)."' data-less='".esc_attr($lessName)."' style='border: none; width: 100%;'>";

					// Arrow image
					$button .= $this->getButtonIcon($dataObj, 'left');
					// Copy to clipboard
					if (!empty($enableTooltip)) { $button .= $this->getToolTipContent($tooltipText); }

					$button .= "<span class=\"yrm-button-text-$id yrm-button-text-span\">$buttonLabel</span>";
					$button .= $this->getButtonIcon($dataObj, 'right');

				$button .= '</span>';
			$button .= "</$tag>";
		}

		return $button;
	}

	private function includeScripts() {
		global $includeIds;

		$id = $this->getId();
		$savedData = $this->getData();

		$dataObj = $this->getDataObj();
		$type = $savedData['type'];
		$scripts = '';

		$scripts .= '<script type="text/javascript">';
		$scripts .= "function yrmAddEvent(element, eventName, fn) {
				if (element.addEventListener)
					element.addEventListener(eventName, fn, false);
				else if (element.attachEvent)
					element.attachEvent('on' + eventName, fn);
		};";
		$scripts .= "readMoreArgs[".esc_attr($id)."] = ".json_encode($savedData).";";
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-effects-core');
		wp_register_script('readMoreJs', YRM_JAVASCRIPT.'yrmMore.js', array(), EXPM_VERSION);
		wp_register_script('yrmMorePro', YRM_JAVASCRIPT.'yrmMorePro.js', array(), EXPM_VERSION);
		wp_enqueue_script('readMoreJs');
		if(YRM_PKG > 1) {
			wp_register_script('yrmGoogleFonts', YRM_JAVASCRIPT.'yrmGoogleFonts.js');
		//	wp_enqueue_script('yrmGoogleFonts');
			wp_localize_script('yrmMorePro', 'YRM_PRO_ARG', array(
				'nonce' => wp_create_nonce('YrmProNonce'),
				'ajaxUrl' => admin_url('admin-ajax.php')
			));
			wp_enqueue_script('yrmMorePro');
		}
		wp_register_style('readMoreStyles', YRM_CSS_URL."readMoreStyles.css", array(), EXPM_VERSION);
		wp_enqueue_style('readMoreStyles');
		wp_register_style('yrmanimate', YRM_CSS_URL."animate.css");
		wp_enqueue_style('yrmanimate');

		do_action('YrmScriptsInclude', $dataObj, $id);

		if(in_array($type, ['accordionPopup', 'inlinePopup', 'popup'])) {

			wp_register_script('YrmPopup', YRM_JAVASCRIPT.'YrmPopup.js', array('readMoreJs'), EXPM_VERSION);
			wp_enqueue_script('YrmPopup');
			wp_register_script('jquery.colorbox', YRM_JAVASCRIPT.'jquery.colorbox.js', array('YrmPopup'), EXPM_VERSION);
			wp_enqueue_script('jquery.colorbox');
			wp_register_style('colorbox.css', YRM_CSS_URL."colorbox/colorbox.css");
			wp_enqueue_style('colorbox.css');

				$scripts .= 'yrmAddEvent(window, "DOMContentLoaded",function() {';
					$scripts .= 'var obj = new YrmPopup();';
					$scripts .= "obj.id = ".esc_attr($id).";";
					$scripts .= 'obj.init();';
				$scripts .= '});';
		}
		if($type == 'link' || $type == 'alink') {
			wp_register_script('YrmLink', YRM_JAVASCRIPT.'YrmLink.js', array('readMoreJs', 'jquery-effects-core'), EXPM_VERSION);
			wp_enqueue_script('YrmLink');

			$scripts .= 'yrmAddEvent(document, "DOMContentLoaded",function() {';
			$scripts .= 'var obj = new YrmLink();';
			$scripts .= "obj.id = ".esc_attr($id).";";
			$scripts .= 'obj.init();';
			$scripts .= '});';
		}
		if($type == 'button') {
			wp_register_script('YrmClassic', YRM_JAVASCRIPT.'YrmClassic.js', array('readMoreJs', 'jquery-effects-core'), EXPM_VERSION);
			wp_enqueue_script('YrmClassic');
				$scripts .= 'yrmAddEvent(document, "DOMContentLoaded",function() {';
					$scripts .= 'var obj = new YrmClassic();';
					$scripts .= "obj.id = ".esc_attr($id).";";
					$scripts .= 'obj.init();';
				$scripts .= '});';
		}
		if($type == 'inline') {
			wp_register_script('YrmInline', YRM_JAVASCRIPT.'YrmInline.js', array('readMoreJs'), EXPM_VERSION);
			wp_enqueue_script('YrmInline');
				$scripts .= 'yrmAddEvent(document, "DOMContentLoaded",function() {';
				$scripts .= 'var obj = new YrmInline();';
				$scripts .= "obj.id = ".esc_attr($id).";";
				$scripts .= 'obj.init();';
			$scripts .= '});';
		}
		$customScript = @$savedData['yrm-editor-js'];
		$scripts .=  stripslashes($customScript);

		$scripts .= '</script>';
		$scripts .= $this->includeCustomStyle();



		$footerAction = 'wp_footer';
		if(is_admin()) {
			$footerAction= 'admin_footer';
		}
		if (!isset($includeIds)) {
			$includeIds = array();
		}
		if (in_array($id, $includeIds)) {
			$scripts = '';
		}else {
			array_push($includeIds, $id);
		}
		add_action($footerAction, function() use ($scripts, $id) {
			echo  $scripts;
		});
	}

	public function includeCustomStyle() {
		
		$styles = '';
		$id = $this->getId();
		$savedData = $this->getData();
		$type = $savedData['type'];
		$dataObj = $this->getDataObj();

		$important = '!important';

		if(is_admin()) {
			$important = '';
		}

		$hiddenContentPadding = (int)$dataObj->getOptionValue('hidden-content-padding').'px';
		$hiddenFontFamily = $dataObj->getOptionValue('hidden-content-font-family');
		$cursor = $dataObj->getOptionValue('yrm-cursor');
		
		if ($hiddenFontFamily == 'customFont') {
			$hiddenFontFamily =  $dataObj->getOptionValue('hidden-content-custom-font-family');
		}
		$hiddenContentAlign = $dataObj->getOptionValue('hidden-content-align');
		
		$styles .= '<style type="text/css">';
			$styles .= '.yrm-toggle-expand-'.esc_attr($id).' {';
			$styles .= 'cursor: '.esc_attr($cursor).' '.esc_attr($important);
			$styles .= '}';
			$styles .= '.yrm-cntent-'.esc_attr($id).' {';
				$styles .= 'padding: '.esc_attr($hiddenContentPadding).';';
				$styles .= 'font-family: '.esc_attr($hiddenFontFamily).';';
				if (!empty($hiddenContentAlign)) {
					$styles .= 'text-align: '.esc_attr($hiddenContentAlign).';';
				}
			$styles .= '}';


		$hoverTextColor = $dataObj->getOptionValue('btn-hover-text-color');
		$fontSize = $dataObj->getOptionvalue('font-size');
		$fontWeight = $dataObj->getOptionvalue('yrm-btn-font-weight');
		$btnColor = $dataObj->getOptionvalue('btn-text-color');
		$fontFamily = $dataObj->getOptionvalue('expander-font-family');
		$borderRadius = $dataObj->getOptionvalue('btn-border-radius');
		$opacity = $dataObj->getOptionvalue('yrm-button-opacity');

		$generalStyles = '';
		$generalStyles .= '.yrm-toggle-expand-'.esc_attr($id).' {';
			$generalStyles .= 'font-size: '.esc_attr(ReadMoreAdminHelper::getCSSSafeSize($fontSize)).';';
			$generalStyles .= 'opacity: '.esc_attr($opacity).';';
			$generalStyles .= 'font-weight: '.esc_attr($fontWeight).';';
			if(YRM_PKG > 1) {
				$generalStyles .= 'color: '.esc_attr($btnColor).' !important;';
				$generalStyles .= 'font-family: '.esc_attr($fontFamily).';';
				$generalStyles .= 'border-radius: '.esc_attr($borderRadius).';';
			}
		$generalStyles .= '}';


		// general pro styles
		if(YRM_PKG > 1) {
			if (!\ReadMoreAdminHelperPro::isMobile()) {
				$generalStyles .= '.yrm-toggle-expand-'.esc_html($id).':hover {';
				$generalStyles .= '		color: '.esc_html($hoverTextColor).' !important;';
				$generalStyles .= '}';
			}
			$arrowIconWidth = $dataObj->getOptionvalue('arrow-icon-width');
			$arrowIconHeight = $dataObj->getOptionvalue('arrow-icon-height');
			$buttonIcon = $dataObj->getOptionvalue('yrm-button-icon');

			$generalStyles .= '.yrm-btn-wrapper-'.esc_html($id).' .yrm-arrow-img {';
			$generalStyles .= 'width: '.esc_html($arrowIconWidth).'px;';
			$generalStyles .= 'height: '.esc_html($arrowIconHeight).'px;';
			$generalStyles .= 'background-image: url("'.esc_html($buttonIcon).'") '.esc_html($important).';';
			$generalStyles .= 'background-size: cover;';
			$generalStyles .= 'vertical-align: middle;';
			$generalStyles .= '}';
		}
		if ($dataObj->getOptionValue('yrm-enable-decoration', true)) {
			$color = $dataObj->getOptionValue('yrm-decoration-color');
			$generalStyles .= '.yrm-button-text-'.esc_attr($id).' {';
			$generalStyles .= 'text-decoration-line: '.esc_attr($dataObj->getOptionValue('yrm-decoration-type')).";";
			$generalStyles .= 'text-decoration-style: '.esc_attr($dataObj->getOptionValue('yrm-decoration-style')).";";
			if ($color) {
				$generalStyles .= 'text-decoration-color: '.esc_attr($dataObj->getOptionValue('yrm-decoration-color')).";";
			}
			$generalStyles .= '}';
		}

		if(YRM_PKG > YRM_FREE_PKG && !get_option('yrm-hide-google-fonts')) {
			$generalStyles .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . esc_attr($fontFamily) . '|'.esc_attr($hiddenFontFamily).'&display=swap">';
		}
		
		$styles .= $generalStyles;
		$generalForTypes = array('button', 'popup', 'link');
		$generalForTypes = apply_filters('yrmGeneralStylesForType', $generalForTypes);
		if(in_array($type, $generalForTypes)) {
			$hoverBgColor = $dataObj->getOptionValue('btn-hover-bg-color');
			$buttonBorderWidth = $dataObj->getOptionValue('button-border-width');
			$buttonBorderColor = $dataObj->getOptionValue('button-border-color');
			$paddingTop = $dataObj->getOptionValue('yrm-button-padding-top');
			$paddingRight = $dataObj->getOptionValue('yrm-button-padding-right');
			$paddingBottom = $dataObj->getOptionValue('yrm-button-padding-bottom');
			$paddingLeft = $dataObj->getOptionValue('yrm-button-padding-left');
			
			// general styling of button type
			$buttonWidth = (int)$dataObj->getOptionValue('button-width').'px';
			$buttonHeight = (int)$dataObj->getOptionValue('button-height').'px';
			$btnBackgroundColor = $dataObj->getOptionValue('btn-background-color');
			
			$buttonGeneralStyles = '';
			$buttonGeneralStyles .= '.yrm-button-auto-mode.yrm-toggle-expand-'.esc_attr($id).' {';
			$buttonGeneralStyles .= 'width: auto !important;';
			$buttonGeneralStyles .= 'height: auto !important;';
			$buttonGeneralStyles .= 'padding: '.esc_attr(ReadMoreAdminHelper::getCSSSafeSize($paddingTop, false)).' '.esc_attr(ReadMoreAdminHelper::getCSSSafeSize($paddingRight, false)).' '.esc_attr(ReadMoreAdminHelper::getCSSSafeSize($paddingBottom, false)).' '.esc_attr(ReadMoreAdminHelper::getCSSSafeSize($paddingLeft, false)).';';
			$buttonGeneralStyles .= '}';

			$buttonGeneralStyles .= '.yrm-button-auto-mode.yrm-toggle-expand-'.esc_attr($id).' .yrm-text-wrapper {';
			$buttonGeneralStyles .= 'position: inherit !important;';
			$buttonGeneralStyles .= 'left: 0 !important;';
			$buttonGeneralStyles .= 'margin: 0 !important;';
			$buttonGeneralStyles .= 'transform: inherit !important;';
			$buttonGeneralStyles .= '}';

			$buttonGeneralStyles .= '.yrm-toggle-expand-'.esc_attr($id).' {';
			$buttonGeneralStyles .= 'width: '.esc_attr($buttonWidth).';';
			$buttonGeneralStyles .= 'height: '.esc_attr($buttonHeight).';';
			$buttonGeneralStyles .= 'line-height: 1;';
			if(YRM_PKG > 1 && !empty($btnBackgroundColor)) {
				$buttonGeneralStyles .= 'background-color: '.esc_attr($btnBackgroundColor).';';
			}
			$buttonGeneralStyles .= '}';
			$buttonGeneralStyles .= $dataObj->getOptionValue('yrm-custom-css');

			
			$styles .= $buttonGeneralStyles;

			if($dataObj->getOptionValue('hover-effect') && !\ReadMoreAdminHelperPro::isMobile()) {

					$styles .= ".yrm-toggle-expand-".esc_attr($id).":hover {";
					$styles .= "background-color: ".esc_attr($hoverBgColor)." !important;";
					$styles .= "color: ".esc_attr($hoverTextColor)." !important;";
				$styles .= '}';
			}

			if($dataObj->getOptionValue('button-border')) {
				$styles .= ".yrm-toggle-expand.yrm-toggle-expand-".esc_attr($id)." {";
					$styles .= "border-width: ".esc_attr($buttonBorderWidth)." ".esc_attr($important).";";
					$styles .= "border-color: ".esc_attr($buttonBorderColor)." ".esc_attr($important).";";
				$styles .= '}';
			}

			if($dataObj->getOptionValue('button-box-shadow')) {
				$shadowHorizontal = $dataObj->getOptionValue('button-box-shadow-horizontal-length').'px';
				$shadowVertical = $dataObj->getOptionValue('button-box-shadow-vertical-length').'px';
				$shadowBlurRadius = $dataObj->getOptionValue('button-box-blur-radius').'px';
				$shadowSpreadRadius = $dataObj->getOptionValue('button-box-spread-radius').'px';
				$shadowColor = $dataObj->getOptionvalue('button-box-shadow-color');

					$styles .= '.yrm-toggle-expand.yrm-toggle-expand-'.esc_attr($id).'  {';
					$styles .= "-webkit-box-shadow: ".esc_attr($shadowHorizontal)." ".esc_attr($shadowVertical)." ".esc_attr($shadowBlurRadius)." ".esc_attr($shadowSpreadRadius)." ".esc_attr($shadowColor).";";
					$styles .= "-moz-box-shadow:".esc_attr($shadowHorizontal)." ".esc_attr($shadowVertical)." ".esc_attr($shadowBlurRadius)." ".esc_attr($shadowSpreadRadius)." ".esc_attr($shadowColor).";";
					$styles .= "box-shadow: ".esc_attr($shadowHorizontal)." ".esc_attr($shadowVertical)." ".esc_attr($shadowBlurRadius)." ".esc_attr($shadowSpreadRadius)." ".esc_attr($shadowColor).";";
				$styles .= "}";
			}

			if(YRM_PKG > 1) {
				$styles .= '.yrm-inner-content-wrapper.yrm-cntent-'.esc_attr($id).' {';
				$styles .= 'width: '.esc_attr($dataObj->getOptionvalue('hidden-inner-width')).'; margin: 0 auto;max-width: 100%;';
				$styles .= '}';
			}
			
		}

		$styles .= '.yrm-content-gradient-'.esc_attr($id).' {';
		$styles .= 'position: absolute;';
		$styles .= 'top: '.esc_attr($dataObj->getOptionValue('show-content-gradient-position')).'px;';
		$styles .= 'width: 100%;';
		$styles .= 'text-align: center;';
		$styles .= 'margin: 0;';
		$styles .= 'padding: '.esc_attr($dataObj->getOptionValue('show-content-gradient-height')).'px 0;';
		$styles .= 'background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, rgba(255,255,255,0)),color-stop(1, '.esc_attr($dataObj->getOptionvalue('show-content-gradient-color')).')) !important;';
		$styles .= '}</style>';
		
		$styles = apply_filters('yrmContentStyles', $styles, $dataObj, $id);

		return $styles;
	}

	private function getButtonIcon($dataObj, $position)
	{
		$button = '';
		$enableIcon = false;

		if(YRM_PKG > 1) {
			$enableIcon = $dataObj->getOptionvalue('enable-button-icon');
		}
		if(!empty($enableIcon) && $dataObj->getOptionvalue('arrow-icon-alignment') === $position) {
			$button .= "<span class='yrm-arrow-img'></span>";
		}

		return $button;
	}

	private function getToolTipContent($text = '')
	{
		return '<span class="yrm-tooltiptext" id="yrm-myTooltip">'.esc_attr($text).'</span>';
	}

}