<?php

namespace ZPOS\Admin\Setting;

abstract class CoreBox extends Box
{
	/**
	 * @property Tab $parent
	 */
	protected $parent;

	/**
	 * CoreBox constructor.
	 * @param Tab $parent
	 * @param string|null $label
	 * @param array $args
	 * @param InputBase ...$inputs
	 */
	public function __construct($parent, $label, $args, ...$inputs)
	{
		$this->parent = $parent;
		parent::__construct($label, $args, ...$inputs);
		if (method_exists(static::class, 'getDefaultValue')) {
			add_filter(
				PostTab::class . '::getDefaultValueByPost',
				[static::class, 'getDefaultValue'],
				10,
				3
			);
		}
	}
}
