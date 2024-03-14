<?php $items_size = $this->get_settings('items_size'); ?>
<div class="elementor-lakit-countdown-timer">
	<div class="lakit-countdown-timer timer-<?php echo $items_size; ?>" data-due-date="<?php echo $this->due_date(); ?>" data-show-days="<?php echo $this->get_settings_for_display('show_days'); ?>">
		<?php $this->_glob_inc_if( '00-days', array( 'show_days' ) ); ?>
		<?php $this->_glob_inc_if( '01-hours', array( 'show_hours' ) ); ?>
		<?php $this->_glob_inc_if( '02-minutes', array( 'show_min' ) ); ?>
		<?php $this->_glob_inc_if( '03-seconds', array( 'show_sec' ) ); ?>
	</div>
</div>