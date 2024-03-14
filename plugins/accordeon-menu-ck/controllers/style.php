<?php
Namespace Accordeonmenuck;

defined('CK_LOADED') or die;

class CKControllerStyle extends CKController {

	protected $view = 'style';

	function __construct() {
		parent::__construct();
	}

	public function edit() {
		$editIds = $this->input->get('cid', null, 'array');
		if (count($editIds)) {
			$editId = (int) $editIds[0];
		} else {
			$editId = (int) $this->input->get('id', null, 'int');
		}

		// Redirect to the edit screen.
		CKFof::redirect(ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL . '&view=' . $this->view . '&layout=edit&id=' . $editId);
	}

	/*
	 * Generate the CSS styles from the settings
	 */
	public function ajaxRenderCss() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		$fields = stripslashes($this->input->get('fields', '', 'raw'));
		$fields = json_decode($fields);
		$customstyles = stripslashes( $this->input->get('customstyles', '', 'string'));
		$customstyles = json_decode($customstyles);
		$customcss = $this->input->get('customcss', '', 'html');

		$css = $this->renderCss($fields, $customstyles);
		$css .= $customcss;

		echo $css;
		exit();
	}

	/*
	 * Render the CSS from the settings
	 */
	public function renderCss($fields, $customstyles) {
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckstyles.php';
		$ckstyles = new CKStyles();
		$css = $this->getDefaultCss($fields);
		$css .= $ckstyles->create($fields, $customstyles);

		return $css;
	}

	/*
	 * Render the CSS from the settings
	 */
	public function getDefaultCss($fields) {
		$imageposition = isset($fields->menustylesparentarrowposition) ? $fields->menustylesparentarrowposition : 'right';
		$level2imageposition = isset($fields->level2menustylesparentarrowposition) ? $fields->level2menustylesparentarrowposition : 'right';
		$level3imageposition = isset($fields->level3menustylesparentarrowposition) ? $fields->level3menustylesparentarrowposition : 'right';

		// level 1
		$level1imageplus = isset($fields->menustylesimageplus) ? $fields->menustylesimageplus : '';
		$level1imageplus = (substr($level1imageplus, 0, 4) == 'http') ? $level1imageplus : site_url() . '/' . $level1imageplus;
		$level1imageminus = isset($fields->menustylesimageminus) ? $fields->menustylesimageminus : '';
		$level1imageminus = (substr($level1imageminus, 0, 4) == 'http') ? $level1imageminus : site_url() . '/' . $level1imageminus;
		$level1imagepadding = isset($fields->menustylesparentarrowwidth) && $fields->menustylesparentarrowwidth ? 'padding-' . $imageposition . ': ' . CKFof::testUnit($fields->menustylesparentarrowwidth) . ';' : '';
		$level1imagewidth = isset($fields->menustylesparentarrowwidth) && $fields->menustylesparentarrowwidth ? 'width: ' . CKFof::testUnit($fields->menustylesparentarrowwidth) . ';' : '';

		// level 2
		$level2imageplus = isset($fields->level2menustylesimageplus) && $fields->level2menustylesimageplus != '' ? $fields->level2menustylesimageplus : $level1imageplus;
		$level2imageplus = (substr($level2imageplus, 0, 4) == 'http') ? $level2imageplus : site_url() . '/' . $level2imageplus;
		$level2imageminus = isset($fields->level2menustylesimageminus) && $fields->level2menustylesimageminus != ''  ?$fields->level2menustylesimageminus : $level1imageminus;
		$level2imageminus = (substr($level2imageminus, 0, 4) == 'http') ? $level2imageminus : site_url() . '/' . $level2imageminus;
		$level2imagepadding = isset($fields->level2menustylesparentarrowwidth) ? 'padding-' . $imageposition . ': ' . CKFof::testUnit($fields->level2menustylesparentarrowwidth) . ';' : '';
		$level2imagewidth = isset($fields->level2menustylesparentarrowwidth) ? 'width: ' . CKFof::testUnit($fields->level2menustylesparentarrowwidth) . ';' : '';

		// level 3
		$level3imageplus = isset($fields->level3menustylesimageplus) && $fields->level3menustylesimageplus != '' ? $fields->level3menustylesimageplus : $level1imageplus;
		$level3imageplus = (substr($level3imageplus, 0, 4) == 'http') ? $level3imageplus : site_url() . '/' . $level3imageplus;
		$level3imageminus = isset($fields->level3menustylesimageminus) && $fields->level3menustylesimageminus != '' ? $fields->level3menustylesimageminus : $level1imageminus;
		$level3imageminus = (substr($level3imageminus, 0, 4) == 'http') ? $level3imageminus : site_url() . '/' . $level3imageminus;
		$level3imagepadding = isset($fields->level3menustylesparentarrowwidth) ? 'padding-' . $imageposition . ': ' . CKFof::testUnit($fields->level3menustylesparentarrowwidth) . ';' : '';
		$level3imagewidth = isset($fields->level3menustylesparentarrowwidth) ? 'width: ' . CKFof::testUnit($fields->level3menustylesparentarrowwidth) . ';' : '';
		
		// base styles
		$menuID = '|ID|';
		$css = "\n" . $menuID . " { margin:0;padding:0; }";
		$css .= "\n" . $menuID . " .accordeonck_desc { display:block; }";
		$css .= "\n" . $menuID . " li.accordeonck { list-style: none;overflow: hidden; margin: 0;}";
		$css .= "\n" . $menuID . " ul[class^=\"content\"] { margin:0;padding:0;width:auto; }";
		$css .= "\n" . $menuID . " li.accordeonck > span { position: relative; display: block; " . $fields->menustylesfontfamily . "}";
		$css .= "\n" . $menuID . " li.accordeonck.parent > span { " . $level1imagepadding . "}";
		$css .= "\n" . $menuID . " li.parent > span span.toggler_icon { position: absolute; cursor: pointer; display: block; height: 100%; z-index: 10;" . $imageposition . ":0; background: url(" . $level1imageplus . ") center center no-repeat !important;" . $level1imagewidth . "}";
		$css .= "\n" . $menuID . " li.parent.open > span span.toggler_icon { " . $imageposition . ":0; background: url(" . $level1imageminus . ") center center no-repeat !important;}";
		$css .= "\n" . $menuID . " li.accordeonck.level2 > span { " . $level2imagepadding . "}";
		$css .= "\n" . $menuID . " li.level3 li.accordeonck > span { " . $level3imagepadding . "}";
		$css .= "\n" . $menuID . " a.accordeonck { display: block;text-decoration: none; }";
		$css .= "\n" . $menuID . " a.accordeonck:hover { text-decoration: none; }";
		$css .= "\n" . $menuID . " li.parent > span a { display: block;outline: none; }";
		$css .= "\n" . $menuID . " li.parent.open > span a {  }";
		if ($level2imageplus) $css .= "\n" . $menuID . " li.level2.parent > span span.toggler_icon { " . $level2imageposition . ":0;display: block;outline: none;background: url(" . $level2imageplus . ") center center no-repeat !important; " . $level12magewidth . "}";
		if ($level2imageminus) $css .= "\n" . $menuID . " li.level2.parent.open > span span.toggler_icon { background: url(" . $level2imageminus . ") center center no-repeat !important; }";
		if ($level3imageplus) $css .= "\n" . $menuID . " li.level2 li.accordeonck.parent > span span.toggler_icon { " . $level3imageposition . ":0;display: block;outline: none;background: url(" . $level3imageplus . ") center center no-repeat !important; " . $level3imagewidth . "}";
		if ($level3imageminus) $css .= "\n" . $menuID . " li.level2 li.accordeonck.open.parent > span span.toggler_icon { background: url(" . $level3imageminus . ") center center no-repeat !important; }";

		$css = str_replace('"', '|qq|', $css);

		return $css;
	}

	/*
	 * Generate the CSS styles from the settings
	 */
	public function ajaxSaveStyles() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		// Get the data.
		// $data = $this->input->getArray($_POST);
		$id = $this->input->get('id', 0, 'int');
		$name = $this->input->get('name', '', 'string');
		if (! $name) $name = 'style' . $id;
		$layoutcss = trim($this->input->get('layoutcss', '', 'html'));
		$fields = $this->input->get('fields', '', 'raw');
		$fields = stripslashes($this->input->get('fields', '', 'raw'));

		if (! $name) $name = 'style' . $id;
		$data['id'] = $id;
		$data['name'] = $name;
		$data['state'] = 1;
		$data['params'] = $fields;
		$data['layoutcss'] = str_replace('\"', '"', $layoutcss);

		$model = $this->getModel('style');
		$id = $model->save($data);

		if (! $id) {
			echo "{'result': '0', 'id': '" . $id . "', 'message': 'Error : Can not save the Styles !'}";
			die;
		}
		echo '{"result": "1", "id": "' . $id . '", "message": "Styles saved successfully"}';
		exit();
	}

	/**
	 * Ajax method to read the fields values from the selected preset
	 *
	 * @return  json - 
	 *
	 */
	function ajaxLoadPresetFields() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		$preset = $this->input->get('preset', '', 'string');
		$folder_path = ACCORDEONMENUCK_MEDIA_PATH . '/presets/';
		$fields = '{}';

		if ( file_exists($folder_path . $preset. '/styles.json') ) {
			$fields = @file_get_contents($folder_path . $preset. '/styles.json');
			$fields = str_replace("\n", "", $fields);
			$fields = $this->fieldsLegacyImport($fields);
		} else {
			echo '{"result" : 0, "message" : "File Not found : '.$folder_path . $preset. '/styles.json'.'"}';
			exit();
		}

		echo '{"result" : 1, "fields" : "'.$fields.'", "customcss" : ""}';
		exit();
	}

	/**
	 * Ajax method to read the custom css from the selected preset
	 *
	 * @return  string - the custom CSS on success, error message on failure
	 *
	 */
	function ajaxLoadPresetCustomcss() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		$preset = $this->input->get('folder', '', 'string');
		$folder_path = ACCORDEONMENUCK_MEDIA_PATH . '/presets/';

		// load the custom css
		$customcss = '';
		if ( file_exists($folder_path . $preset. '/custom.css') ) {
			$customcss = @file_get_contents($folder_path . $preset. '/custom.css');
		} else {
			echo '|ERROR| File Not found : '.$folder_path . $preset. '/custom.css';
			exit();
		}

		echo $customcss;
		exit();
	}

	private function fieldsLegacyImport($fields) {
		$legacy  = array(
			'level1itemnormalstylesfontsize' // link color + font
			, 'level1itemnormalstylesfontcolor'
			, 'level1itemhoverstylesfontcolor'
			, 'level1itemnormalstylesdescfontsize'
			, 'level1itemnormalstylesdescfontcolor'
			, 'level1itemhoverstylesdescfontcolor'
			, 'level1itemnormalstylestextshadowcolor' // link text shadow
			, 'level1itemnormalstylestextshadowblur'
			, 'level1itemnormalstylestextshadowoffsetx'
			, 'level1itemnormalstylestextshadowoffsety'
			, 'level1itemnormalstylespaddingtop' // padding
			, 'level1itemnormalstylespaddingright' 
			, 'level1itemnormalstylespaddingbottom' 
			, 'level1itemnormalstylespaddingleft' 
			
			, 'level1itemhoverstylestextshadowcolor' // link text shadow
			, 'level1itemhoverstylestextshadowblur'
			, 'level1itemhoverstylestextshadowoffsetx'
			, 'level1itemhoverstylestextshadowoffsety'
			, 'level1itemhoverstylespaddingtop' // padding
			, 'level1itemhoverstylespaddingright' 
			, 'level1itemhoverstylespaddingbottom' 
			, 'level1itemhoverstylespaddingleft' 
			
			,'level2itemnormalstylesfontsize' // link color + font
			, 'level2itemnormalstylesfontcolor'
			, 'level2itemhoverstylesfontcolor'
			, 'level2itemnormalstylesdescfontsize'
			, 'level2itemnormalstylesdescfontcolor'
			, 'level2itemhoverstylesdescfontcolor'
			, 'level2itemnormalstylestextshadowcolor' // link text shadow
			, 'level2itemnormalstylestextshadowblur'
			, 'level2itemnormalstylestextshadowoffsetx'
			, 'level2itemnormalstylestextshadowoffsety'
			, 'level2itemnormalstylespaddingtop' // padding
			, 'level2itemnormalstylespaddingright' 
			, 'level2itemnormalstylespaddingbottom' 
			, 'level2itemnormalstylespaddingleft'
			
			, 'level2itemhoverstylestextshadowcolor' // link text shadow
			, 'level2itemhoverstylestextshadowblur'
			, 'level2itemhoverstylestextshadowoffsetx'
			, 'level2itemhoverstylestextshadowoffsety'
			, 'level2itemhoverstylespaddingtop' // padding
			, 'level2itemhoverstylespaddingright' 
			, 'level2itemhoverstylespaddingbottom' 
			, 'level2itemhoverstylespaddingleft'
			
			,'level3itemnormalstylesfontsize' // link color + font
			, 'level3itemnormalstylesfontcolor'
			, 'level3itemhoverstylesfontcolor'
			, 'level3itemnormalstylesdescfontsize'
			, 'level3itemnormalstylesdescfontcolor'
			, 'level3itemhoverstylesdescfontcolor'
			, 'level3itemnormalstylestextshadowcolor' // link text shadow
			, 'level3itemnormalstylestextshadowblur'
			, 'level3itemnormalstylestextshadowoffsetx'
			, 'level3itemnormalstylestextshadowoffsety'
			, 'level3itemnormalstylespaddingtop' // padding
			, 'level3itemnormalstylespaddingright' 
			, 'level3itemnormalstylespaddingbottom' 
			, 'level3itemnormalstylespaddingleft'
			
			, 'level3itemhoverstylestextshadowcolor' // link text shadow
			, 'level3itemhoverstylestextshadowblur'
			, 'level3itemhoverstylestextshadowoffsetx'
			, 'level3itemhoverstylestextshadowoffsety'
			, 'level3itemhoverstylespaddingtop' // padding
			, 'level3itemhoverstylespaddingright' 
			, 'level3itemhoverstylespaddingbottom' 
			, 'level3itemhoverstylespaddingleft'

			, 'modules/mod_accordeonck/assets' // local path
			);
		$current = array(
			'level1itemnormaltextstylesfontsize' // link color + font
			, 'level1itemnormaltextstylescolor'
			, 'level1itemhovertextstylescolor'
			, 'level1itemnormaltextdescstylesfontsize'
			, 'level1itemnormaltextdescstylescolor'
			, 'level1itemhovertextdescstylescolor'
			, 'level1itemnormaltextstylestextshadowcolor' // link text shadow
			, 'level1itemnormaltextstylestextshadowblur'
			, 'level1itemnormaltextstylestextshadowoffsetx'
			, 'level1itemnormaltextstylestextshadowoffsety'
			, 'level1itemnormaltextstylespaddingtop' // padding
			, 'level1itemnormaltextstylespaddingright' 
			, 'level1itemnormaltextstylespaddingbottom' 
			, 'level1itemnormaltextstylespaddingleft' 
			
			, 'level1itemhovertextstylestextshadowcolor' // link text shadow
			, 'level1itemhovertextstylestextshadowblur'
			, 'level1itemhovertextstylestextshadowoffsetx'
			, 'level1itemhovertextstylestextshadowoffsety'
			, 'level1itemhovertextstylespaddingtop' // padding
			, 'level1itemhovertextstylespaddingright' 
			, 'level1itemhovertextstylespaddingbottom' 
			, 'level1itemhovertextstylespaddingleft'
			
			, 'level2itemnormaltextstylesfontsize' // link color + font
			, 'level2itemnormaltextstylescolor'
			, 'level2itemhovertextstylescolor'
			, 'level2itemnormaltextdescstylesfontsize'
			, 'level2itemnormaltextdescstylescolor'
			, 'level2itemhovertextdescstylescolor'
			, 'level2itemnormaltextstylestextshadowcolor' // link text shadow
			, 'level2itemnormaltextstylestextshadowblur'
			, 'level2itemnormaltextstylestextshadowoffsetx'
			, 'level2itemnormaltextstylestextshadowoffsety'
			, 'level2itemnormaltextstylespaddingtop' // padding
			, 'level2itemnormaltextstylespaddingright' 
			, 'level2itemnormaltextstylespaddingbottom' 
			, 'level2itemnormaltextstylespaddingleft' 
			
			, 'level2itemhovertextstylestextshadowcolor' // link text shadow
			, 'level2itemhovertextstylestextshadowblur'
			, 'level2itemhovertextstylestextshadowoffsetx'
			, 'level2itemhovertextstylestextshadowoffsety'
			, 'level2itemhovertextstylespaddingtop' // padding
			, 'level2itemhovertextstylespaddingright' 
			, 'level2itemhovertextstylespaddingbottom' 
			, 'level2itemhovertextstylespaddingleft' 
			
			, 'level3itemnormaltextstylesfontsize' // link color + font
			, 'level3itemnormaltextstylescolor'
			, 'level3itemhovertextstylescolor'
			, 'level3itemnormaltextdescstylesfontsize'
			, 'level3itemnormaltextdescstylescolor'
			, 'level3itemhovertextdescstylescolor'
			, 'level3itemnormaltextstylestextshadowcolor' // link text shadow
			, 'level3itemnormaltextstylestextshadowblur'
			, 'level3itemnormaltextstylestextshadowoffsetx'
			, 'level3itemnormaltextstylestextshadowoffsety'
			, 'level3itemnormaltextstylespaddingtop' // padding
			, 'level3itemnormaltextstylespaddingright' 
			, 'level3itemnormaltextstylespaddingbottom' 
			, 'level3itemnormaltextstylespaddingleft' 
			
			, 'level3itemhovertextstylestextshadowcolor' // link text shadow
			, 'level3itemhovertextstylestextshadowblur'
			, 'level3itemhovertextstylestextshadowoffsetx'
			, 'level3itemhovertextstylestextshadowoffsety'
			, 'level3itemhovertextstylespaddingtop' // padding
			, 'level3itemhovertextstylespaddingright' 
			, 'level3itemhovertextstylespaddingbottom' 
			, 'level3itemhovertextstylespaddingleft' 

			, str_replace(site_url(), '', plugins_url()) . '/accordeon-menu-ck/images/' 
			);
		return str_replace($legacy, $current, $fields);
	}
}