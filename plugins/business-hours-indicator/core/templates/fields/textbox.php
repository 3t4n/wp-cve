<?php
if(!defined('ABSPATH')){
	die;
}

?>
<?php if($option->is_textarea){ ?>
	<textarea
		class="widefat"
		type="text"
		name="<?php echo $option->name; ?>"
		<?php echo isset($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
	><?php echo htmlspecialchars($option->value);?></textarea>
<?php } else { ?>
	<input
		class="widefat"
		type="text"
		name="<?php echo $option->name; ?>"
		value="<?php echo htmlspecialchars( $option->value ); ?>"
		<?php echo isset( $option->dependency ) ? 'data-dependency="' . htmlspecialchars( json_encode( $option->dependency, ENT_QUOTES ) ) . '"' : ''; ?>
	/>
	<?php
}
	if(isset($option->extra_info))
		echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>
