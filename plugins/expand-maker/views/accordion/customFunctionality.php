<?php
$params = ReadMoreData::params();
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php _e('Custom functionality', YRM_LANG);?></div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div>
					<label for="yrm-edtitor-css" class="yrm-label-of-switch"><?php _e('Custom CSS', YRM_LANG); ?>:</label>
					<textarea id="yrm-edtitor-css" rows="5" name="yrm-accordion-custom-css" class="widefat textarea"><?php echo esc_attr($this->getOptionValue('yrm-accordion-custom-css')); ?></textarea>
				</div>
			</div>
			<div class="col-md-12">
				<div>
					<label for="yrm-editor-js" class="yrm-label-of-switch"><?php _e('Custom js', YRM_LANG); ?>:</label>
					<textarea id="yrm-editor-js" rows="5" name="yrm-accordion-custom-js" class="widefat textarea" value=""><?php echo stripslashes($this->getOptionValue('yrm-accordion-custom-js')); ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>