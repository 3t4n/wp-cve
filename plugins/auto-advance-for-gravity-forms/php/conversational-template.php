<?php
/**
 * Template Name: Conversational Page Template
 * This template will only display the content you entered in the page editor
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<?php 
	$current_page_id = get_the_ID();
	$forms = GFAPI::get_forms();

	$print_form_id = false;
	$auto_advanced_data = array();
	$selected_form = array();
	if( is_page() ) {
		$custom_template = false;
		foreach( $forms as $form ) {
			
			if( isset( $form['gfaa'] ) && isset( $form['gfaa']['enable_conversational'] ) && isset( $form['gfaa']['page'] ) ){
				if( $form['gfaa']['enable_conversational'] == 1 && $form['gfaa']['page'] == $current_page_id ) {
					$print_form_id = $form['id'];
					$auto_advanced_data = $form['gfaa'];
					$selected_form = $form;
					
					break;
				}
			}
		}
	}
	
	$headingtitle = $auto_advanced_data['intro_heading'] ? $auto_advanced_data['intro_heading'] : $selected_form['title'];
?>

<body class="<?php echo GFAutoAdvancedAddOn::get_body_classes( $selected_form ); ?>" 
	style="<?php echo GFAutoAdvancedAddOn::get_body_style( $selected_form ); ?>"
	<?php echo GFAutoAdvancedAddOn::get_body_attrs( $selected_form ); ?>
>
	
	
	<?php if( $auto_advanced_data && $print_form_id ) { ?>
	
		<?php if( isset ( $auto_advanced_data['turn_off_intro'] ) && $auto_advanced_data['turn_off_intro'] == 1 ) { 
			// Leaving blank for future use
		} 
		else { ?>
			<div class="conv-intro show-intro element-hidden" style="display: nonex;">
				<div class="conv-intro-container ">
					
					<?php if( $auto_advanced_data['logo-image'] ) { ?>
						<img decoding="async" src="<?php echo $auto_advanced_data['logo-image']; ?>" alt="" class="conv-intro-logo">
					<?php } ?>
					
					<h1 class="conv-intro-title">
						<?php echo $headingtitle; ?>
					</h1>
					
					<?php if( $auto_advanced_data['intro_description'] ) { ?>
						<p class="conv-intro-description">
							<?php echo $auto_advanced_data['intro_description']; ?>
						</p>
					<?php } ?>
					
					<div class="conv-intro-bottom">
						<button class="conv-intro-btn">Start</button>				
						<span class="conv-intro-enter">Press <span>Enter</span> ↵</span>

					</div>
				</div>
			</div>
		
		<?php } ?>
	
		<div class="conv-form-container element-hidden" style="display: nonex;">
		
			<?php
				
				$arrow_down = '<img src="' . ZZD_AAGF_URL . 'images/icon.svg">';
				$arrow_up = '<img src="' . ZZD_AAGF_URL . 'images/icon.svg">';
				
				$arrow_down = '<svg xmlns="http://www.w3.org/2000/svg" class="fontawesomesvg" viewBox="0 0 448 512">
								  <path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" fill="#fff"/>
								</svg>';
				$arrow_up = '<svg xmlns="http://www.w3.org/2000/svg" class="fontawesomesvg" viewBox="0 0 448 512">
								  <path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" fill="#fff"/>
								</svg>';
				
				$left_side_markup = '<div class="image-left side-image"></div>';
				$right_side_markup = '<div class="image-right side-image"></div>';
				
				$shortcode = '[gravityforms id='.$print_form_id.' title="false" description="false" ajax="true"]';
				echo '<div class="conv-intro-title-wrap"><h1 class="conv-intro-title">'. $headingtitle .'</h1></div>
					<div class="custom-template-wrap">
						<div class="custom-template-wrap-intro">
							
						</div>
						<div class="custom-template-wrap-inner">
							'. $left_side_markup .'
							<div class="formgf">'. do_shortcode( $shortcode ) .'</div>
							'. $right_side_markup .'
						</div>
						
						<div class="conv-form-footer">
							<div class="conv-form-footer-wrap">
								<div class="conv-form-footer-progress">
									<div class="conv-form-footer-progress-status">
										<div class="conv-form-footer-progress-status-percentage"> <span class="completed"></span> completed </div>
									</div>
									<div class="conv-form-footer-progress-bar">
										<div class="conv-form-footer-progress-completed" style="width: 25%;"></div>
									</div>
								</div>
								<div class="conv-form-footer-right-container">
									<div class="conv-form-footer-switch-step">
										<div class="conv-form-footer-switch-step-up">'. $arrow_down .'</div>
										<div class="conv-form-footer-switch-step-down">'. $arrow_down .'</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				';				
				?>
				
		</div>
	<?php 
	} 
	else {
		 while ( have_posts() ) : the_post();  
			the_content();
		endwhile;
	} 
	
	wp_footer(); 
	
	if( current_user_can('administrator') ) {
		GFAutoAdvancedAddOn::add_color_customizer( $selected_form );
	}
	
	?>
</body>
</html>
