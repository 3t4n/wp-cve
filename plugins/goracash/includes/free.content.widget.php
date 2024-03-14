<?php

class Goracash_Free_Content_Widget extends WP_Widget
{

	public function __construct()
	{
		parent::__construct('goracash_free_content', __('Goracash Free Content', 'goracash'), array(
			'description' => __('Daily horoscope, Sex daily horoscope, ...', 'goracash'),
		));
	}

	public function get_dropdown($values, $value)
	{
		$content = '';
		foreach ($values as $key => $label) {
			$content .= sprintf('<option value="%s" %s>%s</option>',
				$key,
				$key == $value ? 'selected="selected"' : '',
				$label
			);
		}
		return $content;
	}

	public function widget($args, $instance)
	{
		echo $args['before_widget'];
		echo $args['before_title'];
		echo apply_filters('widget_title', $instance['title']);
		echo $args['after_title'];

		$type = isset($instance['type']) ? $instance['type'] : 'daily_horoscope';
		$idw = get_option('goracash_idw', '1234');
		$height = isset($instance['height']) && $instance['height'] ? $instance['height'] : Goracash_Free_Content::get_height_from_type($type);
		$width = isset($instance['width']) && $instance['width'] ? $instance['width'] : '100%';
		$tracker = isset($instance['tracker']) ? $instance['tracker'] : '';
		$backgroundColor = isset($instance['background-color']) ? $instance['background-color'] : '#FFFFFF';
		$backgroundColor = str_replace('#', '', $backgroundColor);
		$textColor = isset($instance['text-color']) && $instance['text-color'] ? $instance['text-color'] : '#333333';
		$textColor = str_replace('#', '', $textColor);
		$transparent = isset($instance['transparent']) && $instance['transparent'] ? $instance['transparent'] : '0';
		printf('<iframe src="%s&idw=%s&datas=%s&clf=%s&clt=%s&trs=%s&app=wordpress" border="0" frameborder="0" width="%s" height="%s"></iframe>',
			Goracash_Free_Content::get_url_from_type($type),
			$idw,
			$tracker,
			$backgroundColor,
			$textColor,
			(int)$transparent ? '1' : '0',
			$width,
			$height
		);
		echo $args['after_widget'];
	}

	public function form($instance)
	{
		$type = isset($instance['type']) ? $instance['type'] : '';

		printf(
			'<p>
				<label for="%s">%s :</label>
				<input class="widefat" id="%s" name="%s" type="text" value="%s" />
			</p>
			<p>
				<label for="%s">%s :</label>
				<select class="widefat" id="%s" name="%s">
					%s
				</select>
			</p>
			<p>
				<label for="%s">%s :</label>
				<input class="widefat" id="%s" name="%s" type="text" value="%s" />
			</p>
			<p>
				<label for="%s">%s :</label>
				<input class="widefat" id="%s" name="%s" type="text" value="%s" placeholder="200px | 100%%" />
			</p>
			<p>
				<label for="%s">%s :</label>
				<input class="widefat" id="%s" name="%s" type="text" value="%s" placeholder="850px" />
			</p>
			<p>
				<label for="%s">%s :</label>
				<input class="widefat" id="%s" name="%s" type="text" value="%s" placeholder="#FFFFFF" maxlength="7" />
			</p>
			<p>
				<label for="%s">%s :</label>
				<input class="widefat" id="%s" name="%s" type="text" value="%s" placeholder="#CCCCCC" maxlength="7" />
			</p>
			<p>
				<label for="%s">%s :</label>
				<input class="widefat" id="%s" name="%s" type="checkbox" value="1" %s />
			</p>',
			$this->get_field_name('title'),
			__('Title', 'goracash'),
			$this->get_field_id('title'),
			$this->get_field_name('title'),
			isset($instance['title']) ? $instance['title'] : '',
			$this->get_field_name('type'),
			__('Type', 'goracash'),
			$this->get_field_id('type'),
			$this->get_field_name('type'),
			$this->get_dropdown(Goracash_Free_Content::get_types(), $type),
			$this->get_field_name('tracker'),
			__('Your tracker', 'goracash'),
			$this->get_field_id('tracker'),
			$this->get_field_name('tracker'),
			isset($instance['tracker']) ? $instance['tracker'] : '',
			$this->get_field_name('width'),
			__('Width', 'goracash'),
			$this->get_field_id('width'),
			$this->get_field_name('width'),
			isset($instance['width']) ? $instance['width'] : '',
			$this->get_field_name('height'),
			__('Height', 'goracash'),
			$this->get_field_id('height'),
			$this->get_field_name('height'),
			isset($instance['height']) ? $instance['height'] : '',
			$this->get_field_name('background-color'),
			__('Background color', 'goracash'),
			$this->get_field_id('background-color'),
			$this->get_field_name('background-color'),
			isset($instance['background-color']) ? $instance['background-color'] : '',
			$this->get_field_name('text-color'),
			__('Text color', 'goracash'),
			$this->get_field_id('text-color'),
			$this->get_field_name('text-color'),
			isset($instance['text-color']) ? $instance['text-color'] : '',
			$this->get_field_name('transparent'),
			__('Transparent background', 'goracash'),
			$this->get_field_id('transparent'),
			$this->get_field_name('transparent'),
			isset($instance['transparent']) ? 'checked="checked"' : ''
		);
	}

}