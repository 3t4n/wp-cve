<?php
if(!defined('ABSPATH')){
	die;
}

$has_pre_text = false;

if(isset($option->pre_text)) {
	echo '<span>' . esc_html($option->pre_text) . '</span>';
	$has_pre_text = true;
}
?>

<select
	class="widefat"
	<?php echo $has_pre_text ? 'style="padding:0 10px;width:auto;min-width:55px;"' : 'style="min-width:55px;"'; ?>
	name="<?php echo $option->name; ?>"
	<?php echo isset($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
>
	<?php
		foreach($option->options as $key=>$value){
			$selected = $key == $option->value;
			echo '<option ' . ($selected?'selected':'') .' value="'.$key.'">'.$value.'</option>';
		}
	?>
</select>

<?php
if(isset($option->post_text))
	echo '<span>' . esc_html($option->post_text) . '</span>';
?>

<?php

if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) . '</div>';
