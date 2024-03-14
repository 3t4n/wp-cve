<?php
require_once(YRM_CLASSES.'ReadMoreTypes.php');

class AccordionTypeReadMore extends ReadMoreTypes {
	public function renderContent() {
		$this->prepareSavedValue();
		$tabs = $this->getOptionValue('yrm-accordion');
		require_once(dirname(__FILE__).'/ReadMoreAccordionView.php');

        $beforeContent = ($this->getOptionValue('yrm-accordion-before-content') ? $this->getOptionValue('yrm-accordion-before-content'): '');
        $afterContent = ($this->getOptionValue('yrm-accordion-after-content') ? $this->getOptionValue('yrm-accordion-after-content'): '');

        $content = '<div class="before-accordion-content">'.wp_kses($beforeContent, ReadMoreAdminHelper::getAllowedTags()).'</div>';
        $content .= new ReadMoreAccordionView($this);
        $content .= '<div class="after-accordion-content">'.wp_kses($afterContent, ReadMoreAdminHelper::getAllowedTags()).'</div>';

        return $content;
	}

	public function __construct() {
		$this->filters();
		$this->scripts();
	}

	private function filters() {
		add_filter('yrmTypesViewFiles', array($this, 'yrmTypesViewFiles'), 10, 1);
		add_filter('yrmOptionsCongifFilter', array($this, 'defaultOption'), 10, 1);
	}

	public function defaultOption($options)
	{
		$tabs = array(
			array('label' => 'Tab 1', 'content' => 'Content', 'contentType' => 'content'),
			array('label' => 'Tab 2', 'content' => 'Content', 'contentType' => 'content')
		);

		$options[] = array('name' => 'yrm-accordion', 'type' => 'yrm', 'defaultValue' => $tabs);
		$options[] = array('name' => 'yrm-accordion-mode', 'type' => 'text', 'defaultValue' => 'allFolded');
		$options[] = array('name' => 'yrm-accordion-activate-event', 'type' => 'text', 'defaultValue' => 'click');
		$options[] = array('name' => 'yrm-accordion-animate-easings', 'type' => 'text', 'defaultValue' => 'swing');
		$options[] = array('name' => 'yrm-accordion-animate-duration', 'type' => 'text', 'defaultValue' => '500');
		$options[] = array('name' => 'yrm-accordion-advanced-tab-font-size', 'type' => 'text', 'defaultValue' => '20px');
		$options[] = array('name' => 'yrm-accordion-icons', 'type' => 'text', 'defaultValue' => 'fa-chevron-right_fa-chevron-down');
		$options[] = array('name' => 'yrm-accordion-advanced-tab-border-color', 'type' => 'text', 'defaultValue' => '#e2e2e2');
		$options[] = array('name' => 'yrm-accordion-advanced-tab-border-size', 'type' => 'text', 'defaultValue' => '1px');
		$options[] = array('name' => 'yrm-accordion-advanced-tab-border-size', 'type' => 'text', 'defaultValue' => '1px');
		$options[] = array('name' => 'yrm-accordion-enable-max-height', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'yrm-accordion-scroll-to-active-item', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'yrm-accordion-enable-max-height', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'yrm-accordion-max-height', 'type' => 'text', 'defaultValue' => '200px');
		$options[] = array('name' => 'yrm-accordion-icons-position', 'type' => 'text', 'defaultValue' => 'left');
		$options[] = array('name' => 'yrm-accordion-icons-size', 'type' => 'text', 'defaultValue' => '18px');
		$options[] = array('name' => 'yrm-accordion-before-content', 'type' => 'yrm', 'defaultValue' => '');
		$options[] = array('name' => 'yrm-accordion-after-content', 'type' => 'yrm', 'defaultValue' => '');

		return $options;
	}

	private function scripts() {
		wp_enqueue_script('tiny_mce');
		wp_enqueue_editor();

		wp_register_script('AccordionBuilder.js', YRM_ADMIN_JAVASCRIPT.'AccordionBuilder.js', array());
		wp_enqueue_script('AccordionBuilder.js');

		wp_register_style('accordion.css', YRM_ADMIN_CSS_URL.'/accordion.css', array());
		wp_enqueue_style('accordion.css');

		wp_enqueue_style( 'yrm-awesome-free', '//use.fontawesome.com/releases/v6.2.0/css/all.css' );
	}

	public function yrmTypesViewFiles($files) {
		$functions = new ReadMoreFunctions();
		$files[] = YRM_VIEWS.'accordion/generalView.php';
		return $files;
	}

	public function defaultOptions($defaultData) {
		$defaultData['btn-background-color'] = '';
		$defaultData['add-button-next-content'] = '1';
		return $defaultData;
	}

	public function allSavedOptions($options) {
		$options['btn-background-color'] = '';
		$options['add-button-next-content'] = '1';
		return $options;
	}

	public function getRemoveOptions() {

		return array(
			'button-width' => 1,
			'button-height' => 1,
			'btn-background-color' => 1,
			'btn-border-radius' => 1,
			'button-border' => 1,
			'button-box-shadow' => 1,
			'btn-hover-bg-color' => 1,
			'btn-dimension-mode' => 1,
			'button-border-bottom' => 1
		);
	}

	public static function params() {

		$data = array();

		return $data;
	}

	public function includeOptionsBlock($dataObj) {
		wp_register_script('YrmLink', YRM_JAVASCRIPT.'YrmLink.js', array('readMoreJs', 'jquery-effects-core'), EXPM_VERSION);
		wp_enqueue_script('YrmLink');

		require_once(YRM_VIEWS_SECTIONS.'aLinkCutsomOptions.php');
	}

	public function create($postData) {
		$editorKey = 'yrm-accordion-content-';
		$postType = 'yrm-accordion-content-post-';

		foreach ($postData['yrm-accordion'] as $index => $value) {
			if (isset($postData[$editorKey.$index])) {
				$postData['yrm-accordion'][$index]['content'] = $postData[$editorKey.$index];
			}
			if (isset($postData[$postType.$index])) {
				$postData['yrm-accordion'][$index]['post'] = $postData[$postType.$index];
			}
		}
		parent::create($postData);
	}
}