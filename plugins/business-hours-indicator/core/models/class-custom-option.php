<?php

namespace MABEL_BHI_LITE\Core\Models
{

	class Custom_Option extends Option
	{

		public $data;

		public $template;

		public function __construct($title, $template, array $data)
		{
			parent::__construct( uniqid(), null, $title );

			$this->template = $template;
			$this->data = $data;

		}

	}

}