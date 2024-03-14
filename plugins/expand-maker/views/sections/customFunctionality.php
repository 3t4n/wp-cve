<div class="panel panel-default">
	<div class="panel-heading">
		<?php _e('Custom functionality', YRM_LANG); ?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body">
		<div class="row row-static-margin-bottom">
			<label for="yrm-more-button-custom-class" class="yrm-label-of-switch col-md-6"><?php _e('More Button Custom Class Name', YRM_LANG); ?>:</label>
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="<?php _e('Class Name', YRM_LANG); ?>" name="yrm-more-button-custom-class" id="yrm-more-button-custom-class" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-more-button-custom-class'))?>">
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<label for="yrm-less-button-custom-class" class="yrm-label-of-switch col-md-6"><?php _e('Less Button Custom Class Name', YRM_LANG); ?>:</label>
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="<?php _e('Class Name', YRM_LANG); ?>" name="yrm-less-button-custom-class" id="yrm-less-button-custom-class" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-less-button-custom-class'))?>">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div>
					<label for="yrm-edtitor-css" class="yrm-label-of-switch"><?php _e('Custom CSS', YRM_LANG); ?>:</label>
					<textarea id="yrm-edtitor-css" rows="5" name="yrm-custom-css" class="widefat textarea"><?php echo esc_attr($savedObj->getOptionValue('yrm-custom-css')); ?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div>
					<label for="yrm-editor-js" class="yrm-label-of-switch"><?php _e('Custom js', YRM_LANG); ?>:</label>
					<textarea id="yrm-editor-js" rows="5" name="yrm-editor-js" class="widefat textarea" value=""><?php echo stripslashes($savedObj->getOptionValue('yrm-editor-js')); ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>