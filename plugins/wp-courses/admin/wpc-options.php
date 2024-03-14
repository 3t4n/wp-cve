<?php 

function wpc_register_settings() {
	register_setting( 'wpc_options', 'wpc_enable_powered_by', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_enable_powered_by', 'false');

	register_setting( 'wpc_options', 'wpc_enable_rest_lesson', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_enable_rest_lesson', 'false');

	register_setting( 'wpc_options', 'wpc_show_course_search', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_show_course_search', 'true');

	register_setting( 'wpc_options', 'wpc_show_course_counters', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_show_course_counters', 'true');

	register_setting( 'wpc_options', 'wpc_fix_toolbar_top', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_fix_toolbar_top', 'false');

	register_setting( 'wpc_options', 'wpc_fixed_toolbar_offset', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_fixed_toolbar_offset', '0px');

	register_setting( 'wpc_options', 'wpc_show_breadcrumb_trail', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_show_breadcrumb_trail', 'true');

	register_setting( 'wpc_options', 'wpc_show_login_button', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_show_login_button', 'true');

	register_setting( 'wpc_options', 'wpc_show_completed_lessons', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_show_completed_lessons', 'true');

	register_setting( 'wpc_options', 'wpc_courses_per_page', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_courses_per_page', 10);

	register_setting( 'wpc_options', 'wpc_logged_out_message' );
	add_option( 'wpc_logged_out_message' );

	register_setting( 'wpc_options', 'wpc_modules_opened', array( "sanitize_callback" => 'sanitize_title' ) );
	add_option( 'wpc_modules_opened', 'true');

	/* NEW COLOR CUSTOMIZATION */
	$wpc_primary_color = get_option('wpc_primary_color');
	$wpc_secondary_color = get_option('wpc_secondary_color');

	// Course overview - toolbar buttons
	// Course view - toolbar buttons
	register_setting('wpc_options', 'wpc_toolbar_buttons_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
	add_option( 'wpc_toolbar_buttons_color', $wpc_primary_color ?: '#4f646d');

	// Course overview - selected category background color
	// Course view - selected lesson/quiz backgroud color
	// Profile view - selected tab background color
	register_setting('wpc_options', 'wpc_selected_bg_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
	add_option( 'wpc_selected_bg_color', $wpc_primary_color ?: '#afafaf');

	// Course overview - teacher link color
	// Course view - breadcrumb/link color
	// Profile view - link color
	register_setting('wpc_options', 'wpc_link_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
	add_option( 'wpc_link_color', $wpc_primary_color ?: '#3adfa9');

	// All other buttons:
	// Course view / lesson - "Post Comment" button
	// Course view / quiz - "Start Quizt" button
	// Course view / quiz - "Next Question" button
	// etc.
	register_setting('wpc_options', 'wpc_standard_button_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
	add_option( 'wpc_standard_button_color', $wpc_secondary_color ?: '#4f646d');

	/* OLD COLOR CUSTOMIZATION */

	// Course overview - "Details" button
	// Profile view - "Details" button
	register_setting( 'wpc_options', 'wpc_primary_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
	add_option( 'wpc_primary_color', '#3adfa9'); // --green

	// Course overview - "Start Course" button
	// Course overview - "Add to Cart" button
	// Course detail modal - "Start Course" button
	// Course detail modal - "Add to Cart" button
	register_setting( 'wpc_options', 'wpc_secondary_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
	add_option( 'wpc_secondary_color', '#019ee5'); // --blue

	// Course overview - background color
	// Course view - background color
	register_setting('wpc_options', 'wpc_primary_bg_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
	add_option( 'wpc_primary_bg_color', '#ffffff'); // --wpcbg

	register_setting('wpc_options', 'wpc_row_width', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_row_width', '100%');

	register_setting('wpc_options', 'wpc_row_max_width',  array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_row_max_width', '1300px');

	register_setting('wpc_options', 'wpc_h1_font_size',  array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_h1_font_size');

	register_setting('wpc_options', 'wpc_h2_font_size',  array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_h2_font_size');

	register_setting('wpc_options', 'wpc_h3_font_size',  array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_h3_font_size');

	register_setting('wpc_options', 'wpc_container_padding_top', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_container_padding_top', '0');

	register_setting('wpc_options', 'wpc_container_padding_bottom', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_container_padding_bottom', '0');

	register_setting('wpc_options', 'wpc_container_padding_left', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_container_padding_left', '0');

	register_setting('wpc_options', 'wpc_container_padding_right', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_container_padding_right', '0');

	register_setting('wpc_options', 'wpc_container_margin_top', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_container_margin_top', '0');

	register_setting('wpc_options', 'wpc_container_margin_bottom', array( "sanitize_callback" => 'sanitize_text_field' ) );
	add_option('wpc_container_margin_bottom', '0');
}
add_action( 'admin_init', 'wpc_register_settings' );


function wpc_options_page(){ ?>

	<?php include 'admin-nav-menu.php'; ?>

	<div class="wrap">
		<div class="wpc-flex-container wpc-sticky-wrapper" id="wpc-options-page-wrapper">
			<div class="wpc-flex-4 wpc-admin-options-menu-wrapper" id="wpc-sticky-sidebar">
				<div class="wpc-admin-options-menu sidebar__inner">
					<ul class="wpc-nav-list wpc-nav-list-contained">
						<?php do_action( 'wpc_before_options_menu' ); ?>
						<li data-elem-id="wpc-general-options"><?php esc_html_e('General', 'wp-courses'); ?></li>
						<li data-elem-id="wpc-display-options"><?php esc_html_e('Display', 'wp-courses'); ?></li>
						<li data-elem-id="wpc-design-options"><?php esc_html_e('Design', 'wp-courses'); ?></li>
						<?php do_action( 'wpc_after_options_menu' ); ?>
					</ul>
				</div>
			</div>
			<div class="wpc-flex-8 wpc-sticky-tall">
				<?php // screen_icon(); ?>

				<form method="post" action="options.php">
					<?php 

					settings_fields( 'wpc_options' );

					$wpc_enable_powered_by = get_option('wpc_enable_powered_by');

					$wpc_enable_rest_lesson = get_option('wpc_enable_rest_lesson');

					$wpc_show_course_search = get_option('wpc_show_course_search');
					$wpc_show_course_counters = get_option('wpc_show_course_counters');

					$fix_toolbar = get_option('wpc_fix_toolbar_top');
					$fixed_toolbar_offset = get_option('wpc_fixed_toolbar_offset');

					$wpc_show_breadcrumb_trail = get_option('wpc_show_breadcrumb_trail');
					$wpc_show_login_button = get_option('wpc_show_login_button');
					$wpc_show_completed_lessons = get_option('wpc_show_completed_lessons');
					$wpc_courses_per_page = get_option('wpc_courses_per_page');
					$wpc_logged_out_message = get_option('wpc_logged_out_message');
					$wpc_modules_opened = get_option('wpc_modules_opened');
					
					$wpc_primary_bg_color = get_option('wpc_primary_bg_color', 'transparent');
					$wpc_primary_color = get_option('wpc_primary_color', '#3adfa9');
					$wpc_secondary_color = get_option('wpc_secondary_color', '#019EE5');

					$wpc_toolbar_buttons_color = get_option('wpc_toolbar_buttons_color', '#4f646d');
					$wpc_selected_bg_color = get_option('wpc_selected_bg_color', '#afafaf');
					$wpc_link_color = get_option('wpc_link_color', '#3adfa9');
					$wpc_standard_button_color = get_option('wpc_standard_button_color', '#4f646d');

					$width = get_option('wpc_row_width');
					$max_width = get_option('wpc_row_max_width');
					$h1 = get_option('wpc_h1_font_size');
					$h2 = get_option('wpc_h2_font_size');
					$h3 = get_option('wpc_h3_font_size');

				    $container_padding_top = get_option('wpc_container_padding_top'); 
				    $container_padding_top = wpc_esc_unit($container_padding_top, 'px');
				    $container_padding_bottom = get_option('wpc_container_padding_bottom'); 
				    $container_padding_bottom = wpc_esc_unit($container_padding_bottom, 'px');
				    $container_padding_left = get_option('wpc_container_padding_left'); 
				    $container_padding_left = wpc_esc_unit($container_padding_left, 'px');
				    $container_padding_right = get_option('wpc_container_padding_right'); 
				    $container_padding_right = wpc_esc_unit($container_padding_right, 'px');

				    $container_margin_top = get_option('wpc_container_margin_top'); 
				    $container_margin_top = wpc_esc_unit($container_margin_top, 'px');
				    $container_margin_bottom = get_option('wpc_container_margin_bottom'); 
				    $container_margin_bottom = wpc_esc_unit($container_margin_bottom, 'px');

					$id = get_the_ID();

					if( $wpc_enable_powered_by == 'true' ){
						$powered_by_checked = 'checked';
					} else {
						$powered_by_checked = '';
					}

					if( $wpc_enable_rest_lesson == 'true' ){
						$rest_lesson_checked = 'checked';
					} else {
						$rest_lesson_checked = '';
					}

					if( $wpc_show_course_search == 'true' ){
						$course_search_checked = 'checked';
					} else {
						$course_search_checked = '';
					}

					if( $wpc_show_course_counters == 'true' ){
						$course_counters_checked = 'checked';
					} else {
						$course_counters_checked = '';
					}

					if( $fixed_toolbar_offset == true ){
						$fixed_toolbar_offset_checked = 'checked';
					} else {
						$fixed_toolbar_offset_checked = '';
					}

					if( $fix_toolbar == 'true' ){
						$fix_toolbar_checked = 'checked';
					} else {
						$fix_toolbar_checked = '';
					}

					if($wpc_show_breadcrumb_trail == 'true'){
						$breadcrumb_checked = 'checked';
					} else {
						$breadcrumb_checked = '';
					}

					if($wpc_show_login_button == 'true'){
						$login_checked = 'checked';
					} else {
						$login_checked = '';
					}

					if($wpc_show_completed_lessons == 'true'){
						$completed_checked = 'checked';
					} else {
						$completed_checked = '';
					}

					if($wpc_modules_opened == 'true'){
						$modules_checked = 'checked';
					} else {
						$modules_checked = '';
					}

					?>

					<?php do_action('wpc_before_options'); ?>

					<div id="wpc-general-options" class="wpc-material wpc-flex-12">

						<h2 class="wpc-material-heading"><?php esc_html_e('General Options', 'wp-courses'); ?></h2>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Display', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Show "Powered by WP Courses" at the bottom of your courses page', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-enable-powered-by">
										<input type="checkbox" id="wpc-enable-powered-by" name="wpc_enable_powered_by" value="true" <?php echo esc_attr($powered_by_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Rest API', 'wp-courses'); ?></h2>
								<label> <?php esc_html_e('Show lesson content in the REST API. Warning! If checked, restricted lesson content is accessable TO ANYONE via the REST API', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-enable-lesson-rest">
										<input type="checkbox" id="wpc-enable-lesson-rest" name="wpc_enable_rest_lesson" value="true" <?php echo esc_attr($rest_lesson_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

					</div>

					<div id="wpc-display-options" class="wpc-material wpc-flex-12">

						<h2 class="wpc-material-heading"><?php esc_html_e('Display Options', 'wp-courses'); ?></h2>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Course Search', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Display the course search input on the course archive page', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-course-search">
										<input type="checkbox" id="wpc-course-search" name="wpc_show_course_search" value="true" <?php echo esc_attr($course_search_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Course Counters', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Display the number of lessons, modules and quizzes while viewing single course details.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-course-counters">
										<input type="checkbox" id="wpc-course-counters" name="wpc_show_course_counters" value="true" <?php echo esc_attr($course_counters_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Fix Course Toolbar to the Top', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Toggles whether or not the course, lesson and quiz toolbars stick to the top of the screen on mobile devices.  Enabling this option creates a much better user experience however sticky toolbars aren\'t always compatible with certain themes.  If it\'s covering your theme\'s navigation, try adjusting the toolbar offset in the option below.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-fix-toolbar-top">
										<input type="checkbox" id="wpc-fix-toolbar-top" name="wpc_fix_toolbar_top" value="true" <?php echo esc_attr($fix_toolbar_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Fixed Course Toolbar Offset from Top', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Sets the offset in px from the top of the page for the fixed mobile toolbar (if enabled above).  This will need adjusting if your theme has a fixed menu at the top of the screen.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-2">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-fixed-toolbar-offset" type="number" value="<?php echo esc_textarea($fixed_toolbar_offset); ?>" name="wpc_fixed_toolbar_offset" placeholder="0"/>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Breadcrumb Trail', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Display the breadcrumb trail on lesson pages', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-show-breadcrumb-trail">
										<input type="checkbox" id="wpc-show-breadcrumb-trail" name="wpc_show_breadcrumb_trail" value="true" <?php echo esc_attr($breadcrumb_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Login Button', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Display the login button in the course and lesson toolbar.', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-show-login-button">
										<input type="checkbox" id="wpc-show-login-button" name="wpc_show_login_button" value="true" <?php echo esc_attr($login_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Completed Button', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Display the completed lesson button and completed lessons progress bar on lesson pages', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-show-completed-lessons">
										<input type="checkbox" id="wpc-show-completed-lessons" name="wpc_show_completed_lessons" value="true" <?php echo esc_attr($completed_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Courses Per Page', 'wp-courses'); ?></h2>
								<label for="wpc-courses-per-page"><?php esc_html_e('Number of courses that display per page in the course archive', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-courses-per-page" type="number" value="<?php echo (int) $wpc_courses_per_page; ?>" name="wpc_courses_per_page"/>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-4">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Messages', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Displays a custom restricted lesson message for logged out users on lesson pages', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-8">
								<?php $settings = array(
								    'teeny' => true,
								    'textarea_rows' => 6,
								    'tabindex' => 2,
								    'textarea_name'	=> 'wpc_logged_out_message',
								);
								wp_editor( wp_kses($wpc_logged_out_message, 'post'), 'wpc_logged_out_message', $settings); ?>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-10">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Modules opened', 'wp-courses'); ?></h2>
								<label for="wpc-courses-per-page"><?php esc_html_e('Display modules as opened', 'wp-courses'); ?>.</label>
							</div>
							<div class="wpc-flex-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-modules-opened">
										<input type="checkbox" id="wpc-modules-opened" name="wpc_modules_opened" value="true" <?php echo esc_attr($modules_checked); ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								
								</div>
							</div>
						</div>
											
						<?php do_action( 'wpc_after_display_options' ); ?>

					</div>

					<div id="wpc-design-options" class="wpc-material wpc-flex-12">
						<h2 class="wpc-material-heading"><?php esc_html_e('Design Options', 'wp-courses'); ?></h2>

						<?php $font_size_text = __('Font Size', 'wp-courses'); ?>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-6">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Container Width', 'wp-courses'); ?> (px, em, %)</h2>
								<label><?php esc_html_e('Sets the page width for all WP Courses templates such as the course archive, single course and single lesson templates.  Set to % to stay mobile-friendly.  For example 80%.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-6">
								<input style="margin-top:20px;" class="wpc-wide-input" type="text" value="<?php echo esc_textarea($width); ?>" name="wpc_row_width" placeholder="0"/>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-6">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Container Max Width', 'wp-courses'); ?> (px, em, %)</h2>
								<label><?php esc_html_e('Sets the maximum page width for all WP Courses templates such as the course archive, single course and single lesson templates.  Set in px or %.  For example 1080px or 80%.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-6">
								<input style="margin-top:20px;" class="wpc-wide-input" type="text" value="<?php echo esc_textarea($max_width); ?>" name="wpc_row_max_width" placeholder="0"/>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-6">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Container Padding', 'wp-courses'); ?> (px, em, %)</h2>
								<label><?php esc_html_e('Sets the padding for the main container that wraps every WP Courses template page including course archives, single lesson, single course, etc.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-6">
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_top); ?>" name="wpc_container_padding_top" placeholder="0"/>
									<label>Top</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_bottom); ?>" name="wpc_container_padding_bottom" placeholder="0"/>
									<label>Bottom</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_left); ?>" name="wpc_container_padding_left" placeholder="0"/>
									<label>Left</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_right); ?>" name="wpc_container_padding_right" placeholder="0"/>
									<label>Right</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-6">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Container Margin', 'wp-courses'); ?> (px, em, %)</h2>
								<label><?php esc_html_e('Sets the margin for the main container that wraps every WP Courses template page including course archives, single lesson, single course, etc.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-6">
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_margin_top); ?>" name="wpc_container_margin_top" placeholder="0"/>
									<label>Top</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_margin_bottom); ?>" name="wpc_container_margin_bottom" placeholder="0"/>
									<label>Bottom</label>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-2">
								<h2 class="wpc-single-option-header"><?php esc_html_e('Colors', 'wp-courses'); ?></h2>
								<label><?php esc_html_e('Set colors for overall WP Courses styling.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-10">
								<div class="wpc-flex-container">
									<div class="wpc-flex-10">
										<input class="wpc-options-color-field" name="wpc_primary_bg_color" type="color" value="<?php echo esc_attr($wpc_primary_bg_color); ?>"/>
										<label><?php esc_html_e('Background', 'wp-courses'); ?></label>
									</div>
								
									<div class="wpc-flex-10">
										<input class="wpc-options-color-field" name="wpc_primary_color" type="color" value="<?php echo esc_attr($wpc_primary_color); ?>"/>
										<label><?php esc_html_e('Detail Buttons', 'wp-courses'); ?></label>
									</div>

									<div class="wpc-flex-10">
										<input class="wpc-options-color-field" name="wpc_secondary_color" type="color" value="<?php echo esc_attr($wpc_secondary_color); ?>"/>
										<label><?php esc_html_e('Start Course & Add to Cart Buttons', 'wp-courses'); ?></label>
									</div>



									<div class="wpc-flex-10">
										<input class="wpc-options-color-field" name="wpc_toolbar_buttons_color" type="color" value="<?php echo esc_attr($wpc_toolbar_buttons_color); ?>"/>
										<label><?php esc_html_e('Toolbar Buttons', 'wp-courses'); ?></label>
									</div>
								
									<div class="wpc-flex-10">
										<input class="wpc-options-color-field" name="wpc_selected_bg_color" type="color" value="<?php echo esc_attr($wpc_selected_bg_color); ?>"/>
										<label><?php esc_html_e('Selected Item', 'wp-courses'); ?></label>
									</div>

									<div class="wpc-flex-10">
										<input class="wpc-options-color-field" name="wpc_link_color" type="color" value="<?php echo esc_attr($wpc_link_color); ?>"/>
										<label><?php esc_html_e('Links'); ?></label>
									</div>

									<div class="wpc-flex-10">
										<input class="wpc-options-color-field" name="wpc_standard_button_color" type="color" value="<?php echo esc_attr($wpc_standard_button_color); ?>"/>
										<label><?php esc_html_e('Standard Buttons'); ?></label>
									</div>
								</div>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-6">
								<h2 class="wpc-single-option-header">H1 <?php echo esc_html($font_size_text); ?> (px, em, %)</h2>
								<label><?php esc_html_e('Sets the size of all H1 Headers in WP Courses Templates.  This includes lesson titles, course titles and more.  Set in px or em.  For example 32px or 1em.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-6">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-h1-font-size" type="text" value="<?php echo esc_textarea($h1); ?>" name="wpc_h1_font_size" placeholder="0"/>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-6">
								<h2 class="wpc-single-option-header">H2 <?php echo esc_html($font_size_text); ?> (px, em, %)</h2>
								<label><?php esc_html_e('Sets the size of all H2 Headers in WP Courses Templates.  This includes course titles in course archives.  Set in px or em.  For example 32px or 1em.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-6">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-h2-font-size" type="text" value="<?php echo esc_textarea($h2); ?>" name="wpc_h2_font_size" placeholder="0"/>
							</div>
						</div>

						<div class="wpc-flex-container wpc-material-item">
							<div class="wpc-flex-6">
								<h2 class="wpc-single-option-header">H3 <?php echo esc_html($font_size_text); ?> (px, em, %)</h2>
								<label><?php esc_html_e('Sets the size of all H3 Headers in WP Courses Templates.  This includes lesson titles on the profile page.  Set in px or em.  For example 32px or 1em.', 'wp-courses'); ?></label>
							</div>
							<div class="wpc-flex-6">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-h3-font-size" type="text" value="<?php echo esc_textarea($h3); ?>" name="wpc_h3_font_size" placeholder="0"/>
							</div>
						</div>

						<?php do_action( 'wpc_after_design_options' ); ?>
					</div>

					<?php do_action( 'wpc_after_options' ); ?>

					<div class="wpc-flex-container wpc-flex-12">
						<?php submit_button(); ?>
					</div>

				</form>
			</div>
		</div>
	</div>

<?php } ?>