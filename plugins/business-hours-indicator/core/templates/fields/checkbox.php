<?php
if(!defined('ABSPATH')){
	die;
}

?>
<input type="hidden" name="<?php echo $option->name ?>" value="false" class="skip-dependency"/>

<input
	type="checkbox"
	name="<?php echo $option->name ?>"
	value="true"
	<?php if(in_array($option->value, [ 'true','1',true ], true)) echo ' checked '; ?>
	id="ckb-<?php echo $option->id; ?>"
	<?php echo isset($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
/>

<label for="ckb-<?php echo $option->id ?>">
	<?php echo esc_html($option->label); ?>
</label>

<?php
if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>
