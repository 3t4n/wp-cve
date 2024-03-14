<?php

namespace MABEL_BHI_LITE\Models {

	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}

	class IfOpenClosed_VM
	{
		public $content;

		public $show_content;

		public $show_location_error;

		public $slug;

		public function __construct()
		{
			$this->show_location_error = false;
			$this->show_content = false;
			$this->content = '';
		}
	}
}
