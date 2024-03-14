<?php

namespace ZPOS\Admin\Setting;

abstract class Tab
{
	protected $exact = false;
	protected $name;
	public $path;
	protected $boxes = [];

	public function isVisible()
	{
		return true;
	}

	public function __construct()
	{
		$this->admin_init();
	}

	protected function getProcessedBoxes()
	{
		return apply_filters(static::class . '::getBoxes', call_user_func([$this, 'getBoxes']), $this);
	}

	public function getBoxes()
	{
		return [];
	}

	private function admin_init()
	{
		do_action(__METHOD__, $this);
		do_action(static::class . '::' . __FUNCTION__, $this);
		foreach ($this->getProcessedBoxes() as $box) {
			if (is_object($box)) {
				$this->boxes[] = $box;
			} elseif (is_a($box, CoreBox::class, true)) {
				$this->boxes[] = new $box($this);
			}
		}
	}

	public function getData(...$args)
	{
		return array_map(function ($box) use ($args) {
			$box = is_a($box, Box::class) ? $box->getData(...$args) : $box;
			$box['inputs'] = array_map(function ($input) use ($args) {
				return is_a($input, InputBase::class) ? $input->getData(...$args) : $input;
			}, $box['inputs']);
			return $box;
		}, $this->boxes);
	}

	public function toJSON(...$args)
	{
		return [
			'exact' => $this->exact,
			'name' => $this->name,
			'path' => $this->path,
			'boxes' => $this->getData(...$args),
			'settings_fields' => $this->settings_fields(),
		];
	}
}
