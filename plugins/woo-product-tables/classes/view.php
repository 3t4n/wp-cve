<?php
abstract class ViewWtbp extends BaseObjectWtbp {
	/* 
	 * @deprecated
	 */
	protected $_tpl = WTBP_DEFAULT;
	/*
	 * @var string name of theme to load from templates, if empty - default values will be used
	 */
	protected $_theme = '';
	/*
	 * @var string module code for this view
	 */
	protected $_code = '';

	public function display( $tpl = '' ) {
		$tpl = empty($tpl) ? $this->_tpl : $tpl;
		$content = $this->getContent($tpl);
		if (false !== $content) {
			HtmlWtbp::echoEscapedHtml($content);
		}
	}
	public function getPath( $tpl ) {
		$path = '';
		$parentModule = FrameWtbp::_()->getModule( $this->_code );
		if (file_exists($parentModule->getModDir() . 'views' . DS . 'tpl' . DS . $tpl . '.php')) { //Then try to find it in module directory
			$path = $parentModule->getModDir() . DS . 'views' . DS . 'tpl' . DS . $tpl . '.php';
		}
		return $path;
	}
	public function getModule() {
		return FrameWtbp::_()->getModule( $this->_code );
	}
	public function getModel( $code = '' ) {
		return FrameWtbp::_()->getModule( $this->_code )->getController()->getModel($code);
	}
	public function getContent( $tpl = '' ) {
		$tpl = empty($tpl) ? $this->_tpl : $tpl;
		$path = $this->getPath($tpl);
		if ($path) {
			$content = '';
			ob_start();
			require($path);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		return false;
	}

	public function getContentWithParams( $tpl = '', $params = array() ) {
		$tpl = empty($tpl) ? $this->_tpl : $tpl;
		$path = $this->getPath($tpl);
		if ($path) {
			$content = '';
			ob_start();
			extract($params);
			require($path);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		return false;
	}

	public function setTheme( $theme ) {
		$this->_theme = $theme;
	}
	public function getTheme() {
		return $this->_theme;
	}
	public function setTpl( $tpl ) {
		$this->_tpl = $tpl;
	}
	public function getTpl() {
		return $this->_tpl;
	}
	public function init() {

	}
	public function assign( $name, $value ) {
		$this->$name = $value;
	}
	public function setCode( $code ) {
		$this->_code = $code;
	}
	public function getCode() {
		return $this->_code;
	}
	
	/**
	 * This will display form for our widgets
	 */
	public function displayWidgetForm( $data = array(), $widget = array(), $formTpl = 'form' ) {
		$this->assign('data', $data);
		$this->assign('widget', $widget);
		if (FrameWtbp::_()->isTplEditor()) {
			if ($this->getPath($formTpl . '_ext')) {
				$formTpl .= '_ext';
			}
		}
		self::display($formTpl);
	}
	public function sizeToPxPt( $size ) {
		if (!strpos($size, 'px') && !strpos($size, '%')) {
			$size .= 'px';
		}
		return $size;
	}
	public function getInlineContent( $tpl = '' ) {
		return preg_replace('/\s+/', ' ', $this->getContent($tpl));
	}

	public function getTableSetting( $settings, $name, $default = '', $num = false, $arr = false, $zero = false ) {
		if (!isset($settings[$name])) {
			return $default;
		}
		if (empty($settings[$name])) {
			return ( $zero && ( '0' === $settings[$name] ) ) ? '0' : $default;
		}
		$value = $settings[$name];
		if ($num && !is_numeric($value)) {
			return $default;
		}
		if (( false !== $arr ) && !in_array($value, $arr)) {
			return $default;
		}
		return $value;
	}
}
