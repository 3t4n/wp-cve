<?php

namespace MABEL_BHI_LITE\Core{

	class Shortcode
	{

		private $shortcode_tag;

		private $defaults;

		private $view;

		private $data_loader;

		public function __construct($shortcode_tag, $view, $data_loader, array $defaults = [] )
		{
			$this->shortcode_tag = $shortcode_tag;
			$this->defaults = $defaults;
			$this->view = $view;
			$this->data_loader = $data_loader;

			add_shortcode($shortcode_tag, [ $this, 'render_shortcode'] );
		}

		public function render_shortcode($attributes, $content, $code)
		{
			if(!is_array($attributes))
				$attributes = [];

			$attributes = shortcode_atts( $this->defaults, $attributes, $this->shortcode_tag );
			$this->sanitize_attribures( $attributes );

			if(!is_null($this->data_loader))
				$model = call_user_func_array($this->data_loader, [$attributes, $content, $code] );

			ob_start();

			include Config_Manager::$dir . 'templates/' . $this->view;

			return ob_get_clean();

		}

		private function sanitize_attribures(array &$attributes)
		{

			foreach($attributes as $key => $value){

				if($value === 'true' )
					$attributes[$key] = true;

				if($value === 'false')
					$attributes[$key] = false;
			}

		}

	}

}