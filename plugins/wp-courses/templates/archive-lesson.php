<?php get_header(); ?>

<?php
	$url = get_post_type_archive_link( 'lesson' );
?>

<div class="wpc-container">
	<div class="wpc-row">

		<h1 class="wpc-h1"><?php _e('Lesson Archive', 'wp-courses'); ?></h1>

		<form id="wpc-lesson-archive-filters" method="get" action="<?php echo esc_url( $url ); ?>" style="margin-bottom: 20px;" >
			<label for="wpc-lesson-order-select"><?php _e('Sort by', 'wp-courses'); ?>: </label>
			<select id="wpc-lesson-order-select" name="order" onchange="this.form.submit()" class="wpc-input wpc-lesson-filter" style="padding: 4px;">
				<?php $value = sanitize_title_with_dashes($_GET['order']); ?>
				<option value="default" <?php echo $value == '' || $value == 'default' ? 'selected' : ''; ?>><?php _e('Default', 'wp-courses'); ?></option>
				<option value="newest" <?php echo $value == 'newest' ? 'selected' : ''; ?>><?php esc_html_e('Newest', 'wp-courses'); ?></option>
				<option value="oldest" <?php echo $value == 'oldest' ? 'selected' : ''; ?>><?php esc_html_e('Oldest', 'wp-courses'); ?></option>
				<option value="alphabetical" <?php echo $value == 'alphabetical' ? 'selected' : ''; ?>><?php esc_html_e('Alphabetical', 'wp-courses'); ?></option>
				<?php do_action('wpc-after-lesson-order-options'); ?>
			</select>

			<div class="wpc-course-filter-sep" style="display: none;"></div>

			<?php $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : ''; ?>
			<input type="search" name="search" placeholder="<?php _e('Search', 'wp-courses'); ?>" value="<?php echo isset( $search ) ? esc_textarea( $search ) : ''; ?>" class="wpc-input wpc-lesson-filter" id="wpc-course-search"/>
			<input type="submit" value="<?php esc_html_e('Search', 'wp-courses'); ?>" class="wpc-button" />

			<?php do_action('wpc-after-course-archive-filters'); ?>
		</form>
			
		<?php // include 'template-parts/course-filters.php'; ?>

		<?php
			if(have_posts()){
				while(have_posts()){
					the_post(); 

					$post_id = get_the_ID();

					$status = wpc_get_lesson_status(get_current_user_id(), $post_id);

				    $args = array(
				    	'post_from'			=> $post_id,
				        'connection_type'   => array('lesson-to-course'),
				        'order_by'          => 'menu_order',
				        'order'             => 'asc',
				        'limit'				=> 999,
				        'join'              => false,
				    );

				    $courses = wpc_get_connected($args);

					?>
					<div class="wpc-light-box" style="margin-bottom: 20px;">
						<h2 class="wpc-h2">
							<?php 

								if($status['completed'] == 1){
									echo '<i class="fa-regular fa-square-check"></i> ';
								} elseif($status['viewed'] == 1){
									echo '<i class="fa-regular fa-square"></i> ';
								}

							?>

							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

						</h2>
						<?php the_excerpt(); ?>

						<a href="<?php the_permalink(); ?>" class="wpc-button">
							<?php _e('View Lesson', 'wp-courses'); ?>
						</a>

						<div class="course-meta-wrapper">
							<div class="cm-item">
								<?php 

									echo count($courses) == 1 ? __('Course', 'wp-courses') . ': ' : __('Courses', 'wp-courses') . ': ';
									
									$count = 1;

									foreach($courses as $course) {
										echo '<a href="' . get_the_permalink($course->post_to) . '">' . get_the_title($course->post_to) . '</a>';
										echo count($courses) == $count ? '' : ', ';
										$count++;
									}

								?>
							</div>
						</div>

					</div>
				<?php }

				echo '<br><div class="wpc-paginate-links">' . paginate_links() . '</div>';

			}
		?>
	</div>
</div>
<?php get_footer(); ?>