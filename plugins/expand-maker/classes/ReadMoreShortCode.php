<?php
class ReadMoreShortCode {
	private $savedData;
	private $id;
	private $shortcodeArgs = array();
	private $shortcodeContent = '';

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setSavedData($savedData) {
		$this->savedData = $savedData;
	}

	public function getSavedData() {
		return $this->savedData;
	}

	public function setShortcodeArgs($shortcodeArgs) {
		$this->savedData = $shortcodeArgs;
	}

	public function getShortcodeArgs() {
		return $this->shortcodeArgs;
	}

	public function setShortcodeContent($shortcodeContent) {
		$this->shortcodeContent = $shortcodeContent;
	}

	public function getShortcodeContent() {
		return $this->shortcodeContent;
	}

	public function __construct() {
		global $includeIds;
		$includeIds = array();

		add_shortcode('expander_maker', array($this, 'doShortCode'));
		add_shortcode('yrm_accordion', array($this, 'accordionShortcode'));
		add_shortcode('yrm_accordion_popup', array($this, 'accordionPopupShortcode'));
	}

	public function doShortCode($args, $content) {

		$id = 1;
		if(strpos($content, 'expander_maker')) {
            $content .= '[/expander_maker]';
        }
		$content = do_shortcode($content);
		$this->setShortcodeArgs($args);
		$this->setShortcodeContent($content);
		$moreName = '';
		$lessName = '';

		if(isset($args['id'])) {
			$id = $args['id'];
		}
		if(!empty($args['more'])) {
			$moreName = $args['more'];
		}
		if(!empty($args['less'])) {
			$lessName = $args['less'];
		}

		$dataObj = new ReadMoreData();
		$dataObj->setId($id);
		$this->setId($id);
		$savedData = $dataObj->getSavedOptions();
		$this->setSavedData($savedData);

		if(empty($savedData)) {
			return $content;
		}
		$type = $savedData['type'];

		$className = ucfirst($type).'TypeReadMore';
		$classPaths = YRM_CLASSES;
		global $YRM_TYPES;
		global $YRM_EXTENSIONS;
		
		if(!empty($YRM_TYPES[$type])) {
			$classPaths = $YRM_TYPES[$type];
		}
        $extensionsInfo = YrmConfig::extensions();
		$typeObj = $this->getTypeObjFromClass($classPaths, $className);
		if(in_array($type, $YRM_EXTENSIONS)  && !empty($extensionsInfo[$type]) && empty($extensionsInfo[$type]['useMainOptions'])) {
			return $this->renderExtensionContent($typeObj);
		}

		$savedData['attrMoreName'] = $moreName;
		$savedData['attrLessName'] = $lessName;

		foreach($args as $name => $value) {
			if (!empty($value)) {
				$savedData[$name] = $value;
			}
		}

		if(!empty($args['url'])) {
            $savedData['shortcodeURL'] = $args['url'];
        }

		$includeManagerObj = new ReadMoreIncludeManager();
		$includeManagerObj->setId($id);
		$includeManagerObj->setData($savedData);
		$includeManagerObj->setDataObj($dataObj);
		$includeManagerObj->setToggleContent($content);

		return $includeManagerObj->render();
	}

	public function accordionShortcode($args, $content) {
		$id = 1;
		if (!empty($args['id'])) {
			$id = $args['id'];
		}

		require_once(YRM_CLASSES.'AccordionTypeReadMore.php');
		$accordion = new AccordionTypeReadMore();
		$accordion->setId($id);

		return $accordion->renderContent();
	}

	public function accordionPopupShortcode($args, $content) {
		$id = 1;
		if (!empty($args['id'])) {
			$id = $args['id'];
		}

		require_once(YRM_CLASSES.'AccordionPopupTypeReadMore.php');
		$accordion = new AccordionPopupTypeReadMore();
		$accordion->setId($id);

		return $accordion->renderContent();
	}

	public function renderExtensionContent($typeObj) {

		if(!empty($typeObj)) {
			$id = $this->getId();
			$content = $this->getShortcodeContent();
			if($typeObj instanceof ReadMoreTypes) {
				$typeObj->setId($id);
				$typeObj->prepareSavedValue();
				return $typeObj->renderContent($content);
			}
		}

		return '';
	}
	
	private function getTypeObjFromClass($classPaths, $className) {
		if(file_exists($classPaths.$className.'.php')) {
			require_once($classPaths.$className.'.php');
			$typeObj = new $className();
			return $typeObj;
		}
		
		return false;
	}
}