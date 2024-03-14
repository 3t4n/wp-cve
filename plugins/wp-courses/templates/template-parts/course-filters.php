<?php 
	if(is_tax('course-category')) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
		$url = get_term_link($term);
	} else {
		$url = get_post_type_archive_link( 'course' );
	}
?>

<?php $show_login_button = get_option('wpc_show_login_button'); ?>

<div id="wpc-toolbar-top" class="wpc-flex-toolbar wpc-flex-container">
	<div class="wpc-flex-4 wpc-flex-no-margin wpc-classic-search-wrapper">
		<?php $show_course_search = get_option('wpc_show_course_search'); ?>

		<?php if( $show_course_search == 'true' ) { ?>
			<form id="wpc-course-filters" method="get" action="<?php echo esc_url( $url ); ?>" style="display: inline;">

				<?php $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : ''; 
				
				if($show_course_search == 'true'){ ?>
					<input type="search" name="search" placeholder="<?php _e('Search', 'wp-courses'); ?>" value="<?php echo isset( $search ) ? esc_textarea( $search ) : ''; ?>" class="wpc-input wpc-course-filter" id="wpc-course-search"/>
				<?php } ?>

				<?php if( isset( $_GET['course-category'] ) ) { ?>
					<input type="hidden" value="<?php echo esc_attr($_GET['course-category']); ?>" name="course-category"/>
				<?php } ?>

				<?php if( isset( $_GET['post_type'] ) ) { ?>
					<input type="hidden" value="<?php echo esc_attr($_GET['post_type']); ?>" name="post_type"/>
				<?php } ?>

			</form>
		<?php } ?>
	</div>

	<div class="wpc-flex-8 wpc-flex-no-margin wpc-classic-filters-wrapper">
		<div class="wpc-ajax-tool wpc-ajax-course-sort-wrapper wpc-classic-filters-wrapper-right">

				<form id="wpc-course-order" method="get" action="<?php echo esc_url( $url ); ?>" style="display: inline;">
					<select id="wpc-course-order-select" class="wpc-select" name="order" onchange="this.form.submit()">
						<?php if( isset($_GET['order']) ) {
							$value = $_GET['order'];
						} else {
							$value = 'default';
						} ?>
						<option value="default" <?php echo $value == '' || $value == 'default' ? 'selected' : ''; ?>><?php _e('Default', 'wp-courses'); ?></option>
						<option value="newest" <?php echo $value == 'newest' ? 'selected' : ''; ?>><?php esc_html_e('Newest', 'wp-courses'); ?></option>
						<option value="oldest" <?php echo $value == 'oldest' ? 'selected' : ''; ?>><?php esc_html_e('Oldest', 'wp-courses'); ?></option>
						<option value="alphabetical" <?php echo $value == 'alphabetical' ? 'selected' : ''; ?>><?php esc_html_e('Alphabetical', 'wp-courses'); ?></option>
						<?php do_action('wpc-after-course-order-options'); ?>
					</select>
				</form>

					<?php do_action('wpc-after-course-archive-filters'); ?>

					<button type="button" class="wpc-btn wpc-btn-soft wpc-load-category-list wpc-mobile-btn wpc-open-bottom-sidebar" data-ajax="false" title="Course Categories" data-location="right-sidebar-toggle" style="margin-left: 10px;"><i class="fa-solid fa-list"></i> <?php _e('Categories', 'wp-courses'); ?></button>
				
				<?php if(is_user_logged_in()) { ?>
					<button class="wpc-btn wpc-btn-soft wpc-open-sidebar wpc-load-profile-nav" data-visible="false"><i class="fa-solid fa-user"></i></button>
				<?php } else { ?>
					<?php if($show_login_button == 'true') { ?>
						<button class="wpc-btn wpc-load-login"><i class="fa-solid fa-right-to-bracket"></i> <?php _e('Log In', 'wp-courses'); ?></button>
					<?php } ?>
				<?php } ?>

		</div>
	</div>	
</div>