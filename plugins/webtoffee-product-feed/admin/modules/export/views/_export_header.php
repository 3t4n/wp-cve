<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wt-pfd-settings-header">
	<h3>
		<?php echo $this->step_title; ?><?php if($this->step!='post_type'){ ?> - <span class="wt_pf_step_head_post_type_name"></span><?php } ?>
	</h3>
	<span class="wt_pf_step_info" title="<?php echo $this->step_summary; ?>">
		<?php 
		echo $this->step_summary;
		?>
	</span>
</div>