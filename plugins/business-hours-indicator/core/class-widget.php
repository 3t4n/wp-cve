<?php

namespace MABEL_BHI_LITE\Core {

	use WP_Widget;

	class Widget extends WP_Widget
	{
		private $shortcode;

		private $widget_id;

		private $fields;

		private $option_manager;

		public $warning;

		public function __construct($id, $title, $description, $shortcode, Widget_Options_Manager $options)
		{
			$this->shortcode = $shortcode;
			$this->widget_id = $id;
			$this->fields = [];
			$this->option_manager = $options;
			parent::__construct($id, $title, [ 'description' => $description ] );
		}

		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			$showtitle = true;
			if(has_filter('widget_' .$this->widget_id.'_show_title')) {
				$showtitle = apply_filters('widget_' .$this->widget_id.'_show_title', $instance);
			}
			if ( $title && $showtitle) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$argument_list = [];

			foreach($instance as $k => $v) {
				if($k !== 'content')
					$argument_list[] = $k . '="' .$v . '"';
			}
			if(isset($instance['content']))
				echo do_shortcode('[' . $this->shortcode . ' ' .join(' ', $argument_list) . ']' . $instance['content'] . '[/'. $this->shortcode .']');
			else echo do_shortcode('[' . $this->shortcode . ' ' .join(' ', $argument_list) . ']');

			echo $args['after_widget'];
		}


		public function form( $instance )
		{
			if($this->warning){
				echo $this->warning;
			}else{

				foreach ($this->option_manager->options as $option){
					if(isset($instance[$option->id]))
						$option->value = $instance[$option->id];
					$option->name = $this->get_field_name($option->id);
				}

				ob_start();

				$option_manager = $this->option_manager;

				include Config_Manager::$dir . 'core/templates/widget_form.php';

				echo ob_get_clean();
			}
		}

	}
}