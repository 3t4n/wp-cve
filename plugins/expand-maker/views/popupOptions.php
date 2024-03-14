<?php
$popupOptions =  $dataObj->params();
?>
<div class="panel panel-default yrm-popup-options">
	<div class="panel-heading">
		<?php _e('Popup options', YRM_LANG);?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body">
		<div class="row yrm-popup-theme-wrapper">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Popup theme', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<?php echo ReadMoreFunctions::createRadioButtons($popupOptions['themesPopup'], $dataObj->getOptionValue('yrm-popup-theme'), array('class' => 'yrm-popup-theme','name' => 'yrm-popup-theme')); ?><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Width', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="form-control yrm-popup-width" name="yrm-popup-width" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-width'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Height', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="form-control yrm-popup-height" name="yrm-popup-height" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-height'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Max width', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="form-control yrm-popup-max-width" name="yrm-popup-max-width" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-max-width'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Max height', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="form-control yrm-popup-max-height" name="yrm-popup-max-height" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-max-height'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Initial width', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="form-control yrm-popup-initial-width" name="yrm-popup-initial-width" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-initial-width'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Initial hight', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="form-control yrm-popup-initial-height" name="yrm-popup-initial-height" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-initial-height'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-popup-esc-key"><?php _e('Dismiss on "esc" key', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="yrm-popup-esc-key" id="yrm-popup-esc-key" class="yrm-popup-esc-key" <?php echo esc_attr($dataObj->getOptionValue('yrm-popup-esc-key', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-popup-close-button"><?php _e('Show "close" button', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="yrm-popup-close-button" id="yrm-popup-close-button" class="yrm-popup-close-button" <?php echo esc_attr($dataObj->getOptionValue('yrm-popup-close-button', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-popup-overlay-click"><?php _e('Dismiss on overlay click', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="yrm-popup-overlay-click" id="yrm-popup-overlay-click" class="yrm-popup-overlay-click" <?php echo esc_attr($dataObj->getOptionValue('yrm-popup-overlay-click', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="overlay-color"><?php _e('Overlay color', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" id="overlay-color" class="yrm-popup-overlay-color" name="yrm-popup-overlay-color" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-overlay-color'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="overlay-color"><?php _e('Content color', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" id="overlay-color" class="yrm-popup-content-color" name="yrm-popup-content-color" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-content-color'))?>"><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="content-padding"><?php _e('Content padding', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="number" id="content-padding" class="yrm-popup-content-padding form-control" name="yrm-popup-content-padding" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-popup-content-padding'))?>"><br>
			</div>
		</div>
	</div>
</div>
