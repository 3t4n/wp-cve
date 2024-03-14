<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_4_width'] ) ) {
			$sf_4_width = $slider['sf_4_width'];
		} else {
			$sf_4_width = '100%';
		}
		?>
		<h5 for="sf_4_width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_4_width" name="sf_4_width" value="<?php echo esc_attr( $sf_4_width ); ?>" aria-describedby="sf_4_width-help">
		<div id="sf_4_width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_4_height'] ) ) {
			$sf_4_height = $slider['sf_4_height'];
		} else {
			$sf_4_height = '100%';
		}
		?>
		<h5 for="sf_4_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_4_height" name="sf_4_height" value="<?php echo esc_attr( $sf_4_height ); ?>" aria-describedby="sf_4_height-help">
		<div id="sf_4_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_sliderHeight" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Height', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="text" class="form-control w-50" id="sf_4_sliderHeight" name="sf_4_sliderHeight" value="60%" aria-describedby="sf_4_sliderHeight-help" disabled>
		<div id="sf_4_sliderHeight-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Define the slider container height. You can use any unit you like: percent 0% to 100%', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Default is 60%, change this value according to image aspect ratio to best fit the image in slider.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_minHeight" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Minimum Height', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="text" class="form-control w-50" id="sf_4_minHeight" name="sf_4_minHeight" value="" aria-describedby="sf_4_minHeight-help" disabled>
		<div id="sf_4_minHeight-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Define the slider minimum height(in px).', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'You can leave it blank.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_4_auto_play'] ) ) {
			$sf_4_auto_play = $slider['sf_4_auto_play'];
		} else {
			$sf_4_auto_play = 'true';
		}
		?>
		<h5 for="sf_4_auto_play" class="form-label sf-title"><?php esc_html_e( 'Auto Play', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_auto_play-1" name="sf_4_auto_play" value="true" <?php checked( $sf_4_auto_play, 'true' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_4_auto_play-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_auto_play-2" name="sf_4_auto_play" value="false" <?php checked( $sf_4_auto_play, 'false' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_4_auto_play-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_auto_play-help" class="form-text sf-tooltip"><?php esc_html_e( 'Enable or disable automatic slide show.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_auto_play_mobile" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Auto Play on Mobile', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_auto_play_mobile-1" name="sf_4_auto_play_mobile" value="true" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_auto_play_mobile-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_auto_play_mobile-2" name="sf_4_auto_play_mobile" value="false" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_auto_play_mobile-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_auto_play_mobile-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable automatic slide show on mobile.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_auto_play_time" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Autoplay Time', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_4_auto_play_time" min="100" max="10000" step="50" value="1500" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_auto_play_time-value" disabled>1500</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_4_auto_play_time-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Time in milliseconds between the end of the sliding effect and the start of the next on.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_transPeriod" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Animation time for slide', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_4_transPeriod" min="100" max="7000" step="50" value="1000" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_transPeriod-value" disabled>1000</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_4_transPeriod-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Length of the sliding effect in milliseconds.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_img_alignment" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Image Alignment', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<select class="form-select" id="sf_4_img_alignment" aria-label="Default select example" disabled>
			<option value="center" selected>Center</option>
		</select>
		<div id="sf_4_img_alignment-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the image alignment for the slide, in case they are cropped.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'Use this to best fit image when Portrait Mode is OFF or the image is larger then slider.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_portrait" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Portrait Mode', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_portrait-1" name="sf_4_portrait" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_portrait-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_portrait-2" name="sf_4_portrait" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_portrait-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_portrait-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'If enabled the images will not be cropped.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_easing" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Animation Easing Effect', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<select class="form-select" id="sf_4_easing" aria-label="Default select example" disabled>
			<option value="easeInOutExpo" selected disabled>easeInOutExpo</option>
		</select>
		<div id="sf_4_easing-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the easing effect animation for the slide.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_easing_mobile" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Animation Easing Effect on Mobile', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<select class="form-select" id="sf_4_easing_mobile" aria-label="Default select example"  disabled>
			<option value="" selected>None</option>
		</select>
		<div id="sf_4_easing_mobile-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the easing effect animation for the slide on mobile.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?> : </strong><?php esc_html_e( 'Leave [None] if you want to display the same easing on mobile devices and on desktop etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_fx" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Animation FX Effect', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<select class="form-select" id="sf_4_fx" aria-label="Default select example"disabled>
			<option value="simpleFade" selected>simpleFade</option>
		</select>
		<div id="sf_4_fx-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the animation FX effect for the slide.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_fx_mobile" class="form-label sf-title  sf-title-disabled"><?php esc_html_e( 'Animation FX Effect on Mobile', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<select class="form-select" id="sf_4_fx_mobile" aria-label="Default select example" disabled>
			<option value="" selected>None</option>
		</select>
		<div id="sf_4_fx_mobile-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the animation FX effect for the slide on mobile.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?> : </strong><?php esc_html_e( 'Leave [None] if you want to display the same FX on mobile devices and on desktop etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_cols" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'No. of Columns in Grid Animations', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_4_cols" min="2" max="20" step="1" value="6" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_cols-value" disabled>6</button> <?php esc_html_e( 'Columns', 'slider-factory' ); ?>
		<div id="sf_4_cols-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'No. of columns. in grid type animation.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'Works with grid type animations.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_rows" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'No. of Rows  in Grid Animations', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_4_rows" min="2" max="20" step="2" value="4" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_rows-value" disabled>4</button> <?php esc_html_e( 'Rows', 'slider-factory' ); ?>
		<div id="sf_4_rows-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'No. of rows. in grid type animation.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'Works with grid type animations.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_gridDifference" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Animation time for each Grid in Grid Animation', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_4_gridDifference" min="100" max="5000" step="50" value="250" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_gridDifference-value" disabled>250</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_4_gridDifference-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'To make the grid blocks slower than the slices, this value must be smaller than Transition Period', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_slicedCols" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'No. of Columns in Sliced Animations', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_4_slicedCols" min="2" max="20" step="1" value="12" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_slicedCols-value" disabled>12</button> <?php esc_html_e( 'Columns', 'slider-factory' ); ?>
		<div id="sf_4_slicedCols-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'No. of sliced columns in sliced type animations.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'Works with sliced type animations.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_slicedRows" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'No. of Rows in Sliced Animations', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_4_slicedRows" min="2" max="20" step="1" value="8" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_slicedRows-value" disabled>8</button> <?php esc_html_e( 'Rows', 'slider-factory' ); ?>
		<div id="sf_4_slicedRows-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'No. of sliced rows in sliced type animations.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'Works with sliced type animations.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_auto_play_pause_on_hover" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Pause On Mouse Hover', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_auto_play_pause_on_hover-1" name="sf_4_auto_play_pause_on_hover" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_auto_play_pause_on_hover-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_auto_play_pause_on_hover-2" name="sf_4_auto_play_pause_on_hover" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_auto_play_pause_on_hover-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_auto_play_pause_on_hover-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable auto play on mouse hover on the slide.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_pauseOnClick" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Pause On Mouse Click', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_pauseOnClick-1" name="sf_4_pauseOnClick" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_pauseOnClick-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_pauseOnClick-2" name="sf_4_pauseOnClick" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_pauseOnClick-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_pauseOnClick-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'When enable, it stops the auto play on mouse click on the slide.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_loader-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Loader Type', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_loader-1" name="sf_4_loader" value="none" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_loader-1"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_4_loader-2" name="sf_4_loader" value="pie" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_loader-2"><?php esc_html_e( 'Pie', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_4_loader-3" name="sf_4_loader" value="bar" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_loader-3"><?php esc_html_e( 'Bar', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_loader-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the slide loader type.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_loaderColor" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Loader Color', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<p><input type="color" class="col-2" id="sf_4_loaderColor" value="#eeeeee" oninput="SFprintRange(this.id, this.value);" disabled></p>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_loaderColor-value" disabled>#eeeeee</button> <?php esc_html_e( 'HEX', 'slider-factory' ); ?>
		<div id="sf_4_loaderColor-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the loader color.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_4_loaderBgColor" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Loader Background Color', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<p><input type="color" class="col-2" id="sf_4_loaderBgColor" value="#222222" oninput="SFprintRange(this.id, this.value);" disabled></p>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_4_loaderBgColor-value" disabled>#222222</button> <?php esc_html_e( 'HEX', 'slider-factory' ); ?>
		<div id="sf_4_loaderBgColor-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the loader background color.', 'slider-factory' ); ?></div>
	</div>
	
	<div id="pieOptions">
		<div class="mb-3 col-md-6">
			<h5 for="sf_4_pieDiameter" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Diameter of Pie Loader', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<input type="range" class="form-range" id="sf_4_pieDiameter" min="2" max="100" step="1" value="38" oninput="SFprintRange(this.id, this.value);" disabled>
			<button class="btn btn-sm btn-secondary pl-2" id="sf_4_pieDiameter-value" disabled>38</button> <?php esc_html_e( 'px', 'slider-factory' ); ?>
			<div id="sf_4_pieDiameter-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Set the diameter of the pie loader.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Works with Pie Loader enabled.', 'slider-factory' ); ?></div>
		</div>
		
		<div class="mb-3">
			<h5 for="sf_4_piePosition-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Pie Loader Position', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				<input type="radio" class="btn-check" id="sf_4_piePosition-1" name="sf_4_piePosition" value="rightTop" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_piePosition-1"><?php esc_html_e( 'Right Top', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_piePosition-2" name="sf_4_piePosition" value="leftTop" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_piePosition-2"><?php esc_html_e( 'Left Top', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_piePosition-3" name="sf_4_piePosition" value="leftBottom" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_piePosition-3"><?php esc_html_e( 'Left Bottom', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_piePosition-4" name="sf_4_piePosition" value="rightBottom" checked disabled>
				<label class="btn btn-outline-secondary" for="sf_4_piePosition-4"><?php esc_html_e( 'Right Bottom', 'slider-factory' ); ?></label>
			</div>
			<div id="sf_4_piePosition-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the pie loader position.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Works with Pie Loader enabled.', 'slider-factory' ); ?></div>
		</div>
	</div>
	
	<div id="barOptions">
		<div class="mb-3">
			<h5 for="sf_4_barDirection-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Bar Loader Direction', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				<input type="radio" class="btn-check" id="sf_4_barDirection-1" name="sf_4_barDirection" value="leftToRight" checked disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barDirection-1"><?php esc_html_e( 'Left To Right', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_barDirection-2" name="sf_4_barDirection" value="rightToLeft" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barDirection-2"><?php esc_html_e( 'Right To Left', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_barDirection-3" name="sf_4_barDirection" value="topToBottom" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barDirection-3"><?php esc_html_e( 'Top To Bottom', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_barDirection-4" name="sf_4_barDirection" value="bottomToTop" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barDirection-4"><?php esc_html_e( 'Bottom To Top', 'slider-factory' ); ?></label>
			</div>
			<div id="sf_4_barDirection-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the bar loader direction.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Works with Bar Loader enabled.', 'slider-factory' ); ?></div>
		</div>
		
		<div class="mb-3">
			<h5 for="sf_4_barDirection-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Bar Loader Position', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				<input type="radio" class="btn-check" id="sf_4_barPosition-1" name="sf_4_barPosition" value="bottom"  checked disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barPosition-1"><?php esc_html_e( 'Bottom', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_barPosition-2" name="sf_4_barPosition" value="left" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barPosition-2"><?php esc_html_e( 'Left', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_barPosition-3" name="sf_4_barPosition" value="top" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barPosition-3"><?php esc_html_e( 'Top', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_4_barPosition-4" name="sf_4_barPosition" value="right" disabled>
				<label class="btn btn-outline-secondary" for="sf_4_barPosition-4"><?php esc_html_e( 'Right', 'slider-factory' ); ?></label>
			</div>
			<div id="sf_4_barPosition-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the bar loader position.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Works with Bar Loader enabled.', 'slider-factory' ); ?></div>
		</div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_navigation" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Next/Prev Buttons', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_navigation-1" name="sf_4_navigation" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_navigation-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_navigation-2" name="sf_4_navigation" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_navigation-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_navigation-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable next/prev buttons on slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_navigationHover" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Show Next/Prev Buttons on Hover', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_navigationHover-1" name="sf_4_navigationHover" value="true" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_navigationHover-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_navigationHover-2" name="sf_4_navigationHover" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_navigationHover-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_navigationHover-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Show next/prev buttons only on hover (Next/Prev Buttons setting should be ON).', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_mobileNavHover" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Show Next/Prev Buttons on Hover for Mobile', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_mobileNavHover-1" name="sf_4_mobileNavHover" value="true" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_mobileNavHover-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_mobileNavHover-2" name="sf_4_mobileNavHover" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_mobileNavHover-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_mobileNavHover-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Show next/prev buttons only on hover for mobiles.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_playPause" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Show Play/Pause Button', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_playPause-1" name="sf_4_playPause" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_playPause-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_playPause-2" name="sf_4_playPause" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_playPause-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_playPause-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable play/pause button on slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_pagination-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Pagination Style', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_pagination-1" name="sf_4_pagination" value="1" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_pagination-1"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_4_pagination-2" name="sf_4_pagination" value="2" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_pagination-2"><?php esc_html_e( 'Dots', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_4_pagination-3" name="sf_4_pagination" value="3" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_pagination-3"><?php esc_html_e( 'Thumbnails', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_pagination-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select the pagination style.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_captions_mobile" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Show/Hide Captions on Mobile', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_captions_mobile-1" name="sf_4_captions_mobile" value="true" disabled>
			<label class="btn btn-outline-secondary" for="sf_4_captions_mobile-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_captions_mobile-2" name="sf_4_captions_mobile" value="false" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_4_captions_mobile-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_captions_mobile-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable Title/Description on mobile/screen screens.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_4_sorting'] ) ) {
			$sf_4_sorting = $slider['sf_4_sorting'];
		} else {
			$sf_4_sorting = 0;
		}
		?>
		<h5 for="sf_4_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_4_sorting-0" name="sf_4_sorting" value="0" <?php checked( $sf_4_sorting, 0 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_4_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
		
			<input type="radio" class="btn-check" id="sf_4_sorting-1" name="sf_4_sorting" value="1" <?php checked( $sf_4_sorting, 1 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_4_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_sorting-2" name="sf_4_sorting" value="2" <?php checked( $sf_4_sorting, 2 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_4_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_4_sorting-3" name="sf_4_sorting" value="3" <?php checked( $sf_4_sorting, 3 ); ?> disabled>
			<label class="btn btn-outline-secondary  sf-tooltip-disabled" for="sf_4_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_4_sorting-4" name="sf_4_sorting" value="4" <?php checked( $sf_4_sorting, 4 ); ?> disabled>
			<label class="btn btn-outline-secondary  sf-tooltip-disabled" for="sf_4_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_4_sorting-5" name="sf_4_sorting" value="5" <?php checked( $sf_4_sorting, 5 ); ?> disabled>
			<label class="btn btn-outline-secondary  sf-tooltip-disabled" for="sf_4_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_4_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it was at time of creation.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_4_custom_css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?>  <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<textarea type="text" class="form-control w-50" id="sf_4_custom_css" name="sf_4_custom_css" aria-describedby="sf-1-custom-css-help" disabled></textarea>
		<div id="sf_4_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
	</div>
</div>
<script>
// save slider JS start
jQuery(document).ready(function () {
	jQuery('#sf-save-slider').click(function(){
		// show processing icon
		jQuery('button#sf-save-slider').addClass('d-none');
		jQuery('div#sf-slider-process').removeClass('d-none');
		
		//slider info
		var sf_slider_id = jQuery('#sf_slider_id').val();
		var sf_slider_layout = jQuery('#sf_slider_layout').val();
		var sf_slider_title = jQuery('#sf_slider_title').val();
		
		//slides
		var sf_slide_ids = jQuery('.sf_slide_id').serialize();
		var sf_slide_titles = jQuery('input:text.sf_slide_title').serialize();
		var sf_slide_descs = jQuery('.sf_slide_desc').serialize();
		
		//settings
		var sf_4_width = jQuery("#sf_4_width").val();
		var sf_4_height = jQuery("#sf_4_height").val();
		var sf_4_auto_play = jQuery("input[name='sf_4_auto_play']:checked").val();
		var sf_4_sorting = jQuery("input[name='sf_4_sorting']:checked").val();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'sf_save_slider', //this is the name of the AJAX method called in WordPress
				'nonce': "<?php echo esc_js( wp_create_nonce( 'save-slider' ) ); ?>",
				//slider info
				'sf_slider_id': sf_slider_id,
				'sf_slider_layout': sf_slider_layout,
				'sf_slider_title': sf_slider_title,
				
				//slides info
				'sf_slide_ids': sf_slide_ids,
				'sf_slide_titles': sf_slide_titles,
				'sf_slide_descs': sf_slide_descs,
				
				//slider settings
				'sf_4_width': sf_4_width,
				'sf_4_height': sf_4_height,
				'sf_4_auto_play': sf_4_auto_play,
				'sf_4_sorting': sf_4_sorting,
			}, 
			success: function (result) {
				//alert(result);
				jQuery(function() {
					// setTimeout() function will be fired after page is loaded
					// it will wait for 5 sec. and then will fire
					// $("#successMessage").hide() function
					setTimeout(function() {
						// hide processing icon and show button
						jQuery('button#sf-save-slider').removeClass('d-none');
						jQuery('div#sf-slider-process').addClass('d-none');
						// show shortcode
						jQuery("#sf-shortcode-content").removeClass('d-none').slideDown("slow");
					}, 1500);
				});
			},
			error: function () {
				//alert("error");
			}
		});
		
	});
});
// save slider JS end
</script>
