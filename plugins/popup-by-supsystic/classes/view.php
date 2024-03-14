<?php
#[\AllowDynamicProperties]
abstract class viewPps extends baseObjectPps {
    /*
     * @deprecated
     */
    protected $_tpl = PPS_DEFAULT;
    /*
     * @var string name of theme to load from templates, if empty - default values will be used
     */
    protected $_theme = '';
    protected $_nonceIsInited = false;
    /*
     * @var string module code for this view
     */
    protected $_code = '';
    static protected $_allowedHtml;
    static public function supStrRgbToHex($color) {
    preg_match_all("/\((.+?)\)/", $color, $matches);
    if (!empty($matches[1][0])) {
     $rgb = explode(',', $matches[1][0]);
     $size = count($rgb);
     if ($size == 3 || $size == 4) {
       if ($size == 4) {
         $alpha = array_pop($rgb);
         $alpha = floatval(trim($alpha));
         $alpha = ceil(($alpha * (255 * 100)) / 100);
         array_push($rgb, $alpha);
       }
       $result = '#';
       foreach ($rgb as $row) {
         $result .= str_pad(dechex(trim($row)), 2, '0', STR_PAD_LEFT);
       }
       return $result;
     }
    }
    return false;
    }
   static public function ksesString($str) {
      $allowedHtml = self::getAllowedHtml();
      if (!empty($str) && is_string($str)) {
        $str = htmlspecialchars_decode($str);
        $re = '/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/';
        $str = preg_replace_callback(
        $re,
        function($m) {
			       return self::supStrRgbToHex($m[0]);
        },
        $str);
        $re = '/rgba\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\d*(?:\.\d+)?\)/';
        $str = preg_replace_callback(
        $re,
        function($m) {
			       return self::supStrRgbToHex($m[0]);
        },
        $str);
        $str = wp_kses($str, $allowedHtml);
      }
      return $str;
   }
   static public function getAllowedHtml() {
      if (empty(self::$_allowedHtml)) {
         $allowedHtml = wp_kses_allowed_html();
         $allowedDiv = array(
           'style' => array(),
           'div' => array( 'data-wp-editor-id' => 1, 'data-switch-block' => 1, 'data-section' => 1, 'data-pos' => 1, 'data-type-id' => 1, 'data-label' => 1, 'data-key' => 1, 'data-show-class' => 1, 'data-hide-class' => 1, 'data-id' => 1, 'data-slider-type' => 1, 'data-unit' => 1, 'data-unit' => 1, 'data-mapid' => 1, 'data-viewid' => 1, 'onclick' => 1, 'data-is-mobile' => 1, 'data-tab-link' => 1, 'data-tab-item' => 1, 'data-today' => 1, 'data-tabs-for' => 1, 'data-type' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'title' => 1, 'id' => 1, 'data-tooltip-content' => 1,),
           'section' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'title' => 1, 'id' => 1,),
           'nav' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'title' => 1, 'id' => 1,),
           'small' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'span' => array( 'data-block-to-switch' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'pre' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'p' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'br' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'hr' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'hgroup' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'h1' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'h2' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'h3' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'h4' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'h5' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'h6' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'ul' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'ol' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'li' => array( 'data-section' => 1, 'data-key' => 1, 'data-id' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'dl' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'dt' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'dd' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'strong' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'em' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'b' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'i' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'data-tooltip-content' => 1,),
           'u' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'img' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'a' => array( 'data-id' => 1, 'data-txt-close' => 1, 'data-txt-open' => 1, 'data-nonce' => 1, 'data-url' => 1, 'onclick' => 1, 'data-active-label' => 1, 'data-apply-label' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'link' => 1, 'rel' => 1, 'href' => 1, 'target' => 1, ),
           'abbr' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'address' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,),
           'blockquote' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1,) ,
           'area' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'form' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'action' => 1, 'target' => 1, 'method' => 1, ),
           'fieldset' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'label' => array( 'for' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'input' => array( 'data-switch-block' => 1, 'data-pos' => 1, 'onclick' => 1, 'data-type' => 1, 'data-hideid' => 1, 'data-parent-selector' => 1, 'placeholder' => 1, 'readonly' => 1, 'checked' => 1, 'disabled' => 1, 'selected' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'value' => 1, 'type' => 1, 'id' => 1, 'name' => 1, 'src' => 1, 'border' => 1, 'alt' => 1, 'name' => 1, 'maxlength' => 1, ) ,
           'textarea' => array( 'rows' => 1, 'cols' => 1, 'name' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'caption' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'table' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'tbody' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'td' => array( 'colspan' => 1, 'rowspan' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'tfoot' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'th' => array( 'colspan' => 1, 'rowspan' => 1, 'scope' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'thead' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'tr' => array( 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'select' => array( 'data-iter' => 1, 'name' => 1, 'checked' => 1, 'disabled' => 1, 'selected' => 1, 'multiple' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, ) ,
           'option' => array( 'name' => 1, 'checked' => 1, 'disabled' => 1, 'selected' => 1, 'style' => 1, 'title' => 1, 'align' => 1, 'class' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'value' => 1, ),
           'sup' => array() ,
           'sub' => array() ,
           'button' => array( 'data-wp-editor-id' => 1, 'style' => 1, 'class' => 1, 'id' => 1, 'data-mapid' => 1, 'data-viewid' => 1, 'onclick' => 1, ) ,
           'img' => array( 'src' => 1, 'style' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'class' => 1, 'alt' => 1, 'border' => 1, ) ,
           'track' => array( 'src' => 1, 'kind' => 1, 'label' => 1, 'srclang' => 1, ) ,
           'source' => array( 'src' => 1, 'type' => 1, ) ,
           'audio' => array( 'src' => 1, 'style' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'class' => 1, 'autoplay' => 1, 'controls' => 1, 'crossorigin' => 1, 'loop' => 1, 'muted' => 1, 'preload' => 1, ) ,
           'iframe' => array( 'src' => 1, 'style' => 1, 'width' => 1, 'height' => 1, 'id' => 1, 'class' => 1, 'title' => 1, 'allow' => 1, 'allowfullscreen' => 1, 'allowpaymentrequest' => 1, 'csp' => 1, 'height' => 1, 'loading' => 1, 'name' => 1, 'referrerpolicy' => 1, 'sandbox' => 1, 'srcdoc' => 1, ) ,
         );
         self::$_allowedHtml = array_merge($allowedHtml, $allowedDiv);
      }
      return self::$_allowedHtml;
   }
   public function addPpsNonce() {
		$pps_nonce = wp_create_nonce('pps_nonce');
		$jsData = array(
		   'pps_nonce' => $pps_nonce,
		);
		framePps::_()->addScript('noncePps', PPS_JS_PATH . 'nonce.js');
		$jsData = dispatcherPps::applyFilters('', $jsData);
		framePps::_()->addJSVar('noncePps', 'PPS_NONCE', $jsData);
        if (!$this->_nonceIsInited) {
            $this->_nonceIsInited = true;
            if (is_admin()) {
                $pps_nonce = wp_create_nonce('pps_nonce');
                $jsData = array(
                'pps_nonce' => $pps_nonce,
                );
                framePps::_()->addScript('noncePps', PPS_JS_PATH . 'nonce.js');
                framePps::_()->addJSVar('noncePps', 'PPS_NONCE', $jsData);
            } else {
                $pps_nonce = wp_create_nonce('pps_nonce_frontend');
                $jsData = array(
                'pps_nonce_frontend' => $pps_nonce,
                );
                framePps::_()->addScript('noncePps', PPS_JS_PATH . 'nonce.js');
                framePps::_()->addJSVar('noncePps', 'PPS_NONCE_FRONTEND', $jsData);
            }
        }
	}
  public function display($tpl = '') {
        $tpl = (empty($tpl)) ? $this->_tpl : $tpl;
        if(($content = $this->getContent($tpl)) !== false) {
          $this->addPpsNonce();
          echo self::ksesString($content);
        }
  }
	public function getPath($tpl) {
		$path = '';
		$code = $this->_code;
		$parentModule = framePps::_()->getModule( $this->_code );
		$plTemplate = framePps::_()->getModule('options')->get('template');		// Current plugin template
		if(empty($plTemplate) || !framePps::_()->getModule($plTemplate))
			$plTemplate = '';
		if(file_exists(utilsPps::getCurrentWPThemeDir(). 'pps'. DS. $code. DS. $tpl. '.php')) {
            $path = utilsPps::getCurrentWPThemeDir(). 'pps'. DS. $code. DS. $tpl. '.php';
        } elseif($plTemplate && file_exists(framePps::_()->getModule($plTemplate)->getModDir(). 'templates'. DS. $code. DS. $tpl. '.php')) {
			$path = framePps::_()->getModule($plTemplate)->getModDir(). 'templates'. DS. $code. DS. $tpl. '.php';
		} elseif(file_exists($parentModule->getModDir(). 'views'. DS. 'tpl'. DS. $tpl. '.php')) { //Then try to find it in module directory
            $path = $parentModule->getModDir(). DS. 'views'. DS. 'tpl'. DS. $tpl. '.php';
        }
		return $path;
	}
	public function getModule() {
		return framePps::_()->getModule( $this->_code );
	}
	public function getModel($code = '') {
		return framePps::_()->getModule( $this->_code )->getController()->getModel($code);
	}
    public function getContent($tpl = '') {
        $tpl = (empty($tpl)) ? $this->_tpl : $tpl;
        $path = $this->getPath($tpl);
        if($path) {
            $content = '';
            ob_start();
            require($path);
            $content = ob_get_contents();
            ob_end_clean();
            return self::ksesString($content);
        }
        return false;
    }
    public function setTheme($theme) {
        $this->_theme = $theme;
    }
    public function getTheme() {
        return $this->_theme;
    }
    public function setTpl($tpl) {
        $this->_tpl = $tpl;
    }
    public function getTpl() {
        return $this->_tpl;
    }
    public function init() {

    }
    public function assign($name, $value) {
        $this->$name = $value;
    }
    public function setCode($code) {
        $this->_code = $code;
    }
    public function getCode() {
        return $this->_code;
    }

	/**
	 * This will display form for our widgets
	 */
	public function displayWidgetForm($data = array(), $widget = array(), $formTpl = 'form') {
		$this->assign('data', $data);
        $this->assign('widget', $widget);
		if(framePps::_()->isTplEditor()) {
			if($this->getPath($formTpl. '_ext')) {
				$formTpl .= '_ext';
			}
		}
		self::display($formTpl);
	}
	public function sizeToPxPt($size) {
		if(!strpos($size, 'px') && !strpos($size, '%'))
			$size .= 'px';
		return $size;
	}
	public function getInlineContent($tpl = '') {
		return preg_replace('/\s+/', ' ', $this->getContent($tpl));
	}
}
