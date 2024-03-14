<?php
use yrm\Tickbox;

class ReadMoreFilters
{
	private $isLoadedMediaData = false;

	public function isLoadedMediaData() {
		return $this->isLoadedMediaData;
	}

	public function setIsLoadedMediaData($isLoadedMediaData) {
		$this->isLoadedMediaData = $isLoadedMediaData;
	}

	public function __construct()
	{
		$this->init();
	}

	public function yrmMediaButton()
	{
		$isLoadedMediaData = $this->isLoadedMediaData();
		new Tickbox(true, $isLoadedMediaData);
	}

	public function init()
	{
		$this->shortcodeButtons();
	}

	private function shortcodeButtons()
	{
		if (get_option('yrm-hide-media-buttons')) {
			return false;
		}
		add_filter('mce_external_plugins', array($this, 'editorButton'));
		add_action('media_buttons', array($this, 'yrmMediaButton'));

		return true;
	}

	public function editorButton($buttons)
	{
		$buttons['readMoreButton'] = YRM_ADMIN_JAVASCRIPT.'yrm-tinymce-plugin.js';
		$this->yrmMediaButton();

		return $buttons;
	}
}

new ReadMoreFilters();