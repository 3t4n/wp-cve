<?php

class Youzify_Certificates_Tab {

	/**
	 * Tab Content
	 */
	function tab() {

        $user_id = bp_displayed_user_id();
        $certificates = bp_learndash_get_users_certificates( $user_id);
		youzify_log($certificates);
		// Prepare Posts Arguments.
		// if ($user_id == 0 || empty($certificates)) {
		// 	$args = null;
		// } else{
		// 	$args = array(
		// 		'post_type'		 => array('sfwd-courses'),
		// 		'order' 		 => 'DESC',
		// 		'paged' 		 => get_query_var( 'page' ) ? get_query_var( 'page' ) : 1,
		// 		'post_status'	 => 'publish',
		// 		'posts_per_page' => youzify_option( 'youzify_profile_posts_per_page', 6 ),
		// 		'post__in' 		 => $certificates,
		// 	);
		// }

		echo '<div class="youzify-tab youzify-certificates"><div id="youzify-main-certificates" class="youzify-tab youzify-tab-certificates">';
		?>

		<?php

		$this->certificates_core( $certificates );

		youzify_loading();

		echo '</div></div>';

		// Pagination Script.
 		youzify_profile_posts_comments_pagination();

	}

	/**
	 * Post Core .
	 */
	function certificates_core( $certificates ) {
		// $user_id = bp_displayed_user_id();
		if ( empty($certificates) ) {
			echo '<div class="youzify-info-msg youzify-failure-msg"><div class="youzify-msg-icon"><i class="fas fa-exclamation-triangle"></i></div>
			<p>'. __( 'Sorry, no posts found!', 'youzify' ) . '</p></div>';
		}

		// Init Vars.
			// $posts_exist = false;

			// $blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );

			// $blogs_ids = apply_filters( 'youzify_profile_posts_tab_blog_ids', $blogs_ids );

			// // Posts Pagination
			// $posts_page = ! empty( $args['page'] ) ? $args['page'] : 1 ;

			// // Get Base
			// $base = isset( $args['base'] ) ? $args['base'] : get_pagenum_link( 1 );

			echo '<div class="youzify-certificates-page" >';
			
			// // Show / Hide Post Elements
			// $display_meta 		= youzify_option( 'youzify_display_post_meta', 'on' );
			// $display_date 		= youzify_option( 'youzify_display_post_date', 'on' );
			// $display_cats 		= youzify_option( 'youzify_display_post_cats', 'on' );
			// $display_excerpt	= youzify_option( 'youzify_display_post_excerpt', 'on' );
			// $display_readmore 	= youzify_option( 'youzify_display_post_readmore', 'on' );
			// $display_comments 	= youzify_option( 'youzify_display_post_comments', 'on' );
			// $display_meta_icons = youzify_option( 'youzify_display_post_meta_icons', 'on' );
			
			foreach ( $certificates as $certificate ) {

				?>

				<div class="youzify-tab-certificate">


					<div class="youzify-certificate-container">
						<div class="youzify-certificate-icon">
							<i class="fa fa-award"></i>
						</div>
						<div class="youzify-certificate-inner-content">
							<?php do_action( 'youzify_before_certificates_tab_container' ); ?>
							<div class="youzify-certificate-head">
								<div class="youzify-certificate-subtitle">
									<p>Certificate In</p>
								</div>

								<div class="youzify-certificate-title">
									<?php echo $certificate->title ; ?>
								</div>
							</div>
							<div class="youzify-certificate-footer">
									<div class="date">
										<p class="date-title">Earned On</p>
										<?php $date = date_create($certificate->date)?>
										<p> <?php echo date_format($date,"M d, Y") ; ?></p>
									</div>
									<div class="dowload">
										<a href="<?php echo $certificate->link ; ?>"><i class="fa fa-download"></i></a>
									</div>
								</div>
							<?php do_action( 'youzify_after_posts_tab_container' ); ?>
						</div>

					</div>

				</div>

				<?php wp_reset_postdata(); ?>
			

			<?php

				restore_current_blog();
			}


			echo '</div>';
		
	}

	/**
	 * Pagination
	 */
	function pagination( $args = null, $numpages = '', $base = null ) {

		// Get current Page Number
		$paged = ! empty( $args['paged'] ) ? $args['paged'] : 1 ;

		// Get Total Pages Number
		if ( $numpages == '' ) {
			global $wp_query;
			$numpages = $wp_query->max_num_pages;
			if ( ! $numpages ) {
				$numpages = 1;
			}
		}

		// Get Next and Previous Pages Number
		if ( ! empty( $paged ) ) {
			$next_page = $paged + 1;
			$prev_page = $paged - 1;
		}

		// Pagination Settings
		$pagination_args = array(
			'base'            		=> $base . '%_%',
			'format'          		=> 'page/%#%',
			'total'           		=> $numpages,
			'current'         		=> $paged,
			'show_all'        		=> False,
			'end_size'        		=> 1,
			'mid_size'        		=> 2,
			'prev_next'       		=> True,
			'prev_text'       		=> '<div class="youzify-page-symbole">&laquo;</div><span class="youzify-next-nbr">'. $prev_page .'</span>',
			'next_text'       		=> '<div class="youzify-page-symbole">&raquo;</div><span class="youzify-next-nbr">'. $next_page .'</span>',
			'type'            		=> 'plain',
			'add_args'        		=> false,
			'add_fragment'    		=> '',
			'before_page_number' 	=> '<span class="youzify-page-nbr">',
			'after_page_number' 	=> '</span>',
		);

		// Call Pagination Function
		$paginate_links = paginate_links( $pagination_args );

		// Print Pagination
		if ( $paginate_links ) {
			echo sprintf( '<nav class="youzify-pagination" data-base="%1s">' , $base );
			echo '<span class="youzify-pagination-pages">';
			printf( __( 'Page %1$d of %2$d', 'youzify' ), $paged, $numpages );
			echo "</span><div class='certificates-nav-links youzify-nav-links'>$paginate_links</div></nav>";
		}

	}

}


function bp_learndash_get_users_certificates( $user_id = '' ) {
    if ( empty( $user_id ) ) {
        return false;
    }
 
    /**
     * Course Certificate
     **/
    $user_courses = ld_get_mycourses( $user_id, array() );
	youzify_log($user_courses);
    $certificates = array();
    foreach ( $user_courses as $course_id ) {
 
        $certificateLink = learndash_get_course_certificate_link( $course_id, $user_id );
        $filename        = "Certificate.pdf";
        $course_title    = get_the_title( $course_id );
        $certificate_id  = learndash_get_setting( $course_id, 'certificate' );
        $image           = '';
 
        if ( ! empty( $certificate_id ) ) {
            $certificate_data = get_post( $certificate_id );
            $filename         = sanitize_file_name( $course_title ) . "-" . sanitize_file_name( $certificate_data->post_title ) . ".pdf";
            $image            = wp_get_attachment_url( get_post_thumbnail_id( $certificate_id ) );
        }
 
        $date = get_user_meta( $user_id, 'course_completed_' . $course_id, true );
 
        if ( ! empty( $certificateLink ) ) {
            $certificate           = new \stdClass();
            $certificate->ID       = $course_id;
            $certificate->link     = $certificateLink;
            $certificate->title    = get_the_title( $course_id );
            $certificate->filename = $filename;
            $certificate->date     = date_i18n( "Y-m-d h:i:s", $date );
            $certificate->time     = $date;
            $certificate->type     = 'course';
            $certificates[]        = $certificate;
        }
    }
 
    /**
     * Quiz Certificate
     **/
    $quizzes  = get_user_meta( $user_id, '_sfwd-quizzes', true );
    $quiz_ids = empty( $quizzes ) ? array() : wp_list_pluck( $quizzes, 'quiz' );
    if ( ! empty( $quiz_ids ) ) {
        $quiz_total_query_args = array(
            'post_type' => 'sfwd-quiz',
            'fields'    => 'ids',
            'orderby'   => 'title', //$atts['quiz_orderby'],
            'order'     => 'ASC', //$atts['quiz_order'],
            'nopaging'  => true,
            'post__in'  => $quiz_ids
        );
        $quiz_query            = new \WP_Query( $quiz_total_query_args );
        $quizzes_tmp           = array();
        foreach ( $quiz_query->posts as $post_idx => $quiz_id ) {
            foreach ( $quizzes as $quiz_idx => $quiz_attempt ) {
                if ( $quiz_attempt['quiz'] == $quiz_id ) {
                    $quiz_key                 = $quiz_attempt['time'] . '-' . $quiz_attempt['quiz'];
                    $quizzes_tmp[ $quiz_key ] = $quiz_attempt;
                    unset( $quizzes[ $quiz_idx ] );
                }
            }
        }
        $quizzes = $quizzes_tmp;
        krsort( $quizzes );
        if ( ! empty( $quizzes ) ) {
            foreach ( $quizzes as $quizdata ) {
                if ( ! in_array( $quizdata['quiz'], wp_list_pluck( $certificates, 'ID' ) ) ) {
                    $quiz_settings         = learndash_get_setting( $quizdata['quiz'] );
                    $certificate_post_id   = intval( $quiz_settings['certificate'] );
                    $certificate_post_data = get_post( $certificate_post_id );
                    $certificate_data      = learndash_certificate_details( $quizdata['quiz'], $user_id );
                    if ( ! empty( $certificate_data['certificateLink'] ) && $certificate_data['certificate_threshold'] <= $quizdata['percentage'] / 100 ) {
                        $filename              = sanitize_file_name( get_the_title( $quizdata['quiz'] ) ) . "-" . sanitize_file_name( get_the_title( $certificate_post_id ) ) . ".pdf";
                        $certificate           = new \stdClass();
                        $certificate->ID       = $quizdata['quiz'];
                        $certificate->link     = $certificate_data['certificateLink'];
                        $certificate->title    = get_the_title( $quizdata['quiz'] );
                        $certificate->filename = $filename;
                        $certificate->date     = date_i18n( "Y-m-d h:i:s", $quizdata['time'] );
                        $certificate->time     = $quizdata['time'];
                        $certificate->type     = 'quiz';
                        $certificates[]        = $certificate;
                    }
                }
 
            }
        }
    }
 
    usort( $certificates, function ( $a, $b ) {
        return strcmp( $b->time, $a->time );
    } );
 
    return $certificates;
}