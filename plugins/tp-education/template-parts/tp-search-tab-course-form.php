<?php
/**
 * Course Search Tab Form.
 *
 * @package TP Education
 * @since 1.0
 */

function tp_education_search_course_form() {
	?>
		<div class="search-wrapper clear">
			<form role="search" action="<?php echo esc_url( site_url('/') ); ?>" method="get" id="searchform">
				<label><?php esc_html_e( 'Search', 'tp-education' ); ?></label>
				<div class="search two-columns">
					<div class="column-wrapper">
						<?php
						$taxonomy = 'tp-course-category';
		                $args = array(
		                    'option_none_value'  => esc_html__( 'Course Category', 'tp-education' ),
		                    'hide_empty'         => 0,              
		                    'selected'           => 1,
		                    'hierarchical'       => 1,
		                    'name'               => 'tp_term',
		                    'class'              => 'form-control',              
		                    'taxonomy'           => $taxonomy,
		                    'selected'           => ( isset( $_GET[$taxonomy] ) ) ? esc_textarea( $_GET[$taxonomy] ) : 0,
		                    'value_field'        => 'slug'
		                );

		                wp_dropdown_categories( $args, $taxonomy );
						?>
						<input type="text" name="s" placeholder="<?php esc_attr_e( 'keyword', 'tp-education' ); ?>">
						<input type="hidden" name="post_type" value="tp-course" />
					</div><!-- .column-wrapper -->
					<div class="column-wrapper">
						<input type="submit" name="search" value="<?php esc_attr_e( 'Search', 'tp-education' ); ?>"> 
					</div><!-- .column-wrapper -->
				</div><!-- .search/.two-columns -->
			</form>
		</div><!-- .search-wrapper -->
<?php
}
add_action( 'tp_education_search_course_form_action', 'tp_education_search_course_form', 10 );
