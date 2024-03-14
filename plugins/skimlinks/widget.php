<?php

class sl_disclosure_widget extends WP_Widget {

	// constructor
	function sl_disclosure_widget() {
		parent::WP_Widget(false, $name = __('Skimlinks Disclosure/Referral Badge', 'skimlinks') );
	}

	// widget form creation
	function form($instance) {
		?>

			<style type="text/css">
				.sl-image-label {
					display: inline-block;
					vertical-align: middle;
					width: 60px;
					height: 45px;
				}
			</style>
			
			<p>Select badge colour:</p>

			<p>
				<label for="<?php echo $this->get_field_id('sl_disclosure_colour'); ?>">
	        <input 
	        	id="<?php echo $this->get_field_id('blue'); ?>"
	        	name="<?php echo $this->get_field_name('sl_disclosure_colour'); ?>"
	        	type="radio"
	        	value="blue" 
	        	<?php if($instance['sl_disclosure_colour'] === 'blue'){ echo 'checked="checked"'; } ?>
	        	/>
	        	
	        <img class="sl-image-label" alt="<?php _e('Blue', 'skimlinks') ?>" src="<?php echo sl_get_disclosure_badge_url('blue') ?>">
		    </label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('sl_disclosure_colour'); ?>">
	        <input 
	        	id="<?php echo $this->get_field_id('cyan'); ?>"
	        	name="<?php echo $this->get_field_name('sl_disclosure_colour'); ?>"
	        	type="radio"
	        	value="cyan" 
	        	<?php if($instance['sl_disclosure_colour'] === 'cyan'){ echo 'checked="checked"'; } ?>
	        	/>
	        	
	        <img class="sl-image-label" alt="<?php _e('Cyan', 'skimlinks') ?>" src="<?php echo sl_get_disclosure_badge_url('cyan') ?>">
		    </label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('sl_disclosure_colour'); ?>">
	        <input 
	        	id="<?php echo $this->get_field_id('grey'); ?>"
	        	name="<?php echo $this->get_field_name('sl_disclosure_colour'); ?>"
	        	type="radio"
	        	value="grey" 
	        	<?php if($instance['sl_disclosure_colour'] === 'grey'){ echo 'checked="checked"'; } ?>
	        	/>
	        	
	        <img class="sl-image-label" alt="<?php _e('Grey', 'skimlinks') ?>" src="<?php echo sl_get_disclosure_badge_url('grey') ?>">
		    </label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('sl_disclosure_colour'); ?>">
	        <input 
	        	id="<?php echo $this->get_field_id('white'); ?>"
	        	name="<?php echo $this->get_field_name('sl_disclosure_colour'); ?>"
	        	type="radio"
	        	value="white" 
	        	<?php if($instance['sl_disclosure_colour'] === 'white'){ echo 'checked="checked"'; } ?>
	        	/>
	        	
	        <img class="sl-image-label" alt="<?php _e('White', 'skimlinks') ?>" src="<?php echo sl_get_disclosure_badge_url('white') ?>" style="border:1px solid lightgrey;border-radius:2px">
		    </label>
			</p>

		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args );

		echo $before_widget;
		echo '<style>.skimlinks-disclosure-button p{margin-top:0;margin-bottom:0;}</style>' // Too much space
			. sl_get_disclosure_badge_html($instance['sl_disclosure_colour']);
   	echo $after_widget;
	}
}

// register widget
if (sl_is_disclosure_badge_enabled()) {
	add_action('widgets_init', create_function('', 'return register_widget("sl_disclosure_widget");'));
}