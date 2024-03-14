<?php
use Tussendoor\OpenRDW\Config;
	/**
	 * Our widget back-end view.
	 */
?>
<div class="rdw-sort-fields rdw-expand-fields">
<p>
	<label for="<?php echo $this->get_field_name('title'); ?>"><?php echo esc_html__('Widgettitel:', 'open-rdw-kenteken-voertuiginformatie'); ?></label>
	<input class="widefat" type="text" id="<?php echo $this->get_field_name('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($settings['title']); ?>">
</p>
<p>
	<label for="<?php echo $this->get_field_id('class'); ?>"><?php echo esc_html__('Widget-klasse:', 'open-rdw-kenteken-voertuiginformatie'); ?></label>
	<input class="widefat" type="text" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" value="<?php echo esc_attr($settings['class']); ?>">
</p>

<ul>
<?php

$categories = array();

if (isset($settings['savedfields'])) {
	$fields = $settings['savedfields'];
} else {
	foreach ($settings['allfields'] as $key => $value) {
		$fields[] = $key;
	}
}
if (!isset($settings['checkedfields'])) {
	$settings['checkedfields'] = array();
}

foreach ($fields as $value) {
	
	if (!in_array($settings['allfields'][$value]['category'], $categories)) {

		$categories[] = $settings['allfields'][$value]['category'];

		echo '<li class="ui-sortable">';
		echo '<a>'.$settings['allfields'][$value]['category'].'</a>';
		echo '<ul style="display:none;">';

		foreach ($fields as $value) {
			
			$checked = array_search($value, $settings['checkedfields']) !== false ? 'checked="checked"' : '';

			if (end($categories) == $settings['allfields'][$value]['category']) {

				echo '<li class="ui-sortable-handle">';
				echo '<label style="display: block;">';
				echo '<input type="checkbox" class="checkbox" '.$checked.' id="'.$value.'" name="'.$this->get_field_name('checkedfields[]').'" value="'.$value.'">'.$settings['allfields'][$value]['label'];
				echo '<input type="hidden" id="'.$value.'-hidden" name="'.$this->get_field_name('savedfields[]').'" value="'.$value.'">';
				echo '</label>';
				echo '</li>';

			}

		}

		echo '</ul>';
		echo '</li>';

	}

}
?>
</ul>
</div>