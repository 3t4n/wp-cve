<?php

namespace ZPOS\Admin\Setting;

class Box
{
	protected $label = null;
	protected $description = null;
	public $inputs = [];
	protected $args = [];

	public function __construct($label, $args, ...$inputs)
	{
		$this->label = $label;
		$this->args = $args;
		$this->description = isset($args['description']) ? $args['description'] : null;

		$this->inputs = array_values(
			array_filter($inputs, function ($input) {
				return $input !== null;
			})
		);
	}

	public function getData()
	{
		return [
			'label' => $this->label,
			'description' => $this->description,
			'inputs' => $this->inputs,
			'args' => $this->args,
		];
	}
}
