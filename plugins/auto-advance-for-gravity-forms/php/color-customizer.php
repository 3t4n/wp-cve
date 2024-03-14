<form id="color-panel">
	
	<input type="hidden" name="form_id" value="<?php echo $form['id']; ?>">
	
	<h2><?php echo esc_html__( 'Color Customizer', 'gf-autoadvanced' ); ?></h2>
	
	<div class="color-option">
		<label for="cf_form_bg_color_input"><?php echo esc_html__( 'Form Theme', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_form_bg_color_input" name="bg-color" value="#bcd6ec">
	</div>
	
	<div class="color-option">
		<label for="cf_primary_color_input"><?php echo esc_html__( 'Body Text', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_primary_color_input" name="primary-color" value="#1a3d5c">
	</div>
	
	<div class="color-option">
		<label for="cf_progressbar_color_input"><?php echo esc_html__( 'Footer Background', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_progressbar_color_input" name="progressbar-color" value="#448ccb">
	</div>
	
	<div class="color-option">
		<label for="cf_secondary_color_input"><?php echo esc_html__( 'Footer Text', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_secondary_color_input" name="secondary-color" value="#e4eef7">
	</div>
	
	<!-- <div class="color-option">
		<label for="cf_border_color_input"><?php echo esc_html__( 'Border Colors', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_border_color_input" name="border-color" value="#ffffff">
	</div> -->
	
	<div class="color-option">
		<label for="cf_button_bg_input"><?php echo esc_html__( 'Buttons', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_button_bg_input" name="button-bg" value="#448ccb">
	</div>
	
	<div class="color-option">
		<label for="cf_button_text_input"><?php echo esc_html__( 'Buttons Text', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_button_text_input" name="button-text" value="#f1f1f1">
	</div>
	
	<div class="color-option">
		<label for="cf_button_hover_bg_input"><?php echo esc_html__( 'Buttons Hover', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_button_hover_bg_input" name="button-hover-bg" value="#357fc0">
	</div>
	
	<div class="color-option">
		<label for="cf_button_hover_text_input"><?php echo esc_html__( 'Buttons Text Hover', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_button_hover_text_input" name="button-hover-text" value="#f1f1f1">
	</div>
	
	<div class="color-option">
		<label for="cf_confirmation_color"><?php echo esc_html__( 'Confirmation Text', 'gf-autoadvanced' ); ?></label>
		<input type="color" id="cf_confirmation_color" name="confirmation-text" value="#448ccb">
	</div>
	
	
	
	<button id="save-colors-btn" 
		data-saved=<?php echo esc_html__( 'Saved', 'gf-autoadvanced' ); ?> 
		data-error=<?php echo esc_html__( 'Error', 'gf-autoadvanced' ); ?>
		data-default=<?php echo esc_html__( 'Save', 'gf-autoadvanced' ); ?>
		data-saving=<?php echo esc_html__( 'Saving...', 'gf-autoadvanced' ); ?>
	><?php echo esc_html__( 'Save', 'gf-autoadvanced' ); ?></button>
</form>

<div id="color-panel-toggle">
	<img src="<?php echo ZZD_AAGF_URL ?>images/color.svg">
</div>