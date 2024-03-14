<?php

namespace ZPOS\Admin\Setting;

abstract class PageTab extends Tab
{
	public function __construct()
	{
		parent::__construct();
		$this->init();
	}

	public function init()
	{
		do_action(__METHOD__, $this);
		do_action(static::class . '::' . __FUNCTION__, $this);
		foreach ($this->getProcessedBoxes() as $box) {
			if (method_exists($box, 'init')) {
				call_user_func_array([$box, 'init'], [$this->path]);
			}
		}
	}

	public function settings_fields()
	{
		ob_start();
		settings_fields('pos' . $this->path);
		$data = ob_get_contents();
		ob_end_clean();

		return $data;
	}
}
