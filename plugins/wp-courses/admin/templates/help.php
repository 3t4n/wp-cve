<div class="wrap wpc-wrap-generic">

	<div class="wpc-flex-container">

		<div class="wpc-flex-content wpc-flex-content-large">
			<div class="wpc-flex-container">
				<div class="wpc-flex-4 wpc-material">
					<h2 class="wpc-material-heading"><?php esc_html_e('Setup', 'wp-courses'); ?></h2>

					<p class="wpc-description wpc-image-anchor"><?php esc_html_e('Hi', 'wp-courses'); ?> <?php echo esc_html($userName); ?>,<img class="waving-hand" src="<?php echo esc_url(WPC_PLUGIN_URL . 'images/waving-hand.svg'); ?>" alt="waving hand"></p>

					<p class="wpc-description wpc-description-main"><?php esc_html_e('Welcome to WP COURSES', 'wp-courses'); ?></p>

					<p class="wpc-description"><?php esc_html_e('Follow these steps to setup your first course:', 'wp-courses'); ?></p>

					<p class="wpc-description wpc-description-sub"><?php esc_html_e('STEP #1: Create a course:', 'wp-courses'); ?></p>

					<ol class="wpc-description">
						<li><?php esc_html_e('Visit', 'wp-courses'); ?> <a href="edit.php?post_type=course"><?php esc_html_e('All Courses', 'wp-courses'); ?></a> <?php esc_html_e('and create a course', 'wp-courses'); ?></li>
					</ol>

					<p class="wpc-description wpc-description-sub"><?php esc_html_e('STEP #2: Create lesson(s):', 'wp-courses'); ?></p>

					<ol class="wpc-description">
						<li><?php esc_html_e('Visit', 'wp-courses'); ?> <a href="edit.php?post_type=lesson"><?php esc_html_e('All Lessons', 'wp-courses'); ?></a> <?php esc_html_e('and create one or more lessons', 'wp-courses'); ?></li>
						<li><?php esc_html_e('Make sure to link the lesson(s) to the previously created course', 'wp-courses'); ?></li>
					</ol>

					<p class="wpc-description wpc-description-sub"><?php esc_html_e('STEP #3: Display the course:', 'wp-courses'); ?></p>

					<ol class="wpc-description">
						<li><?php esc_html_e('Make a new page called something like "Courses"', 'wp-courses'); ?></li>
						<li><?php esc_html_e('Paste the following shortcode in the page:', 'wp-courses'); ?> <b>[wpc_courses]</b></li>
						<li><?php esc_html_e('Visit the page and have a look at your first course', 'wp-courses'); ?></li>
					</ol>
					
				</div>

				<div class="wpc-flex-4 wpc-material">
					<h2 class="wpc-material-heading"><?php esc_html_e('Help', 'wp-courses'); ?></h2>

					<p class="wpc-description wpc-description-sub"><?php esc_html_e('SHORTCODE LIST:', 'wp-courses'); ?></p>

					<ul class="wpc-description">
						<li><?php esc_html_e('Display collection of courses:', 'wp-courses'); ?> <b>[wpc_courses]</b></li>
						<li><?php esc_html_e('Display user profile and progress:', 'wp-courses'); ?> <b>[wpc_profile]</b></li>
						<li><?php esc_html_e('Display number of courses:', 'wp-courses'); ?> <b>[course_count]</b></li>
						<li><?php esc_html_e('Display number of lessons:', 'wp-courses'); ?> <b>[lesson_count]</b></li>
					</ul>

					<p class="wpc-description wpc-description-sub"><?php esc_html_e('LINKS:', 'wp-courses'); ?></p>

					<ul class="wpc-description">
						<li><a href="https://wpcoursesplugin.com/contact" target="_blank"><?php esc_html_e('Contact', 'wp-courses'); ?></a></li>
						<li><a href="https://wpcoursesplugin.com/pricing" target="_blank"><?php esc_html_e('Pricing', 'wp-courses'); ?></a></li>
						<li><a href="https://wpcoursesplugin.com/courses-page-width/" target="_blank"><?php esc_html_e('Full Tutorials', 'wp-courses'); ?></a></li>
						<li><a href="https://wpcoursesplugin.com/cart/?add-to-cart=829" target="_blank"><?php esc_html_e('Get Premium', 'wp-courses'); ?></a></li>
					</ul>
				</div>

			</div>

		</div>

	</div>

</div> <!-- wrap -->