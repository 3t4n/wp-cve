<?php

/**
 * Check is User Reviewed.
 */
function youzify_is_user_already_reviewed( $reviewed, $reviewer = null ) {

	// Init Var.
	$is_reviewed = false;

	// Get Reviewer.
	$reviewer = ! empty( $reviewer ) ? $reviewer : bp_loggedin_user_id();

 	// Check if user Already Reviewed.
	$review_id = youzify()->reviews->query->get_review_id( $reviewed, $reviewer );

	if ( $review_id ) {
		$is_reviewed = $review_id;
	}

	return apply_filters( 'youzify_is_user_already_reviewed', $is_reviewed );

}

/**
 * Reviews Slug.
 */
function youzify_reviews_tab_slug() {
	return apply_filters( 'youzify_reviews_tab_slug', 'reviews' );
}

/**
 * Get Reviews Tab Screen Function.
 */
function youzify_reviews_screen() {

	do_action( 'youzify_reviews_screen' );

    add_action( 'bp_template_content', 'youzify_get_user_reviews_template' );

    // Load Tab Template
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Get Reviews Tab Content.
 */
function youzify_get_user_reviews_template() {
	bp_get_template_part( 'members/single/reviews' );
}

/**
 * Check is User Can see Reviews.
 */
function youzify_is_user_can_receive_reviews( $user_id = null ) {
	return apply_filters( 'youzify_is_user_can_receive_reviews', true, $user_id );
}

/**
 * Check is User Can see Reviews.
 */
function youzify_is_user_can_see_reviews() {

	if ( bp_core_can_edit_settings() ) {
		$visibility = true;
	} else {

		// Get Who can see reviews.
		$privacy = youzify_option( 'youzify_user_reviews_privacy', 'public' );

		switch ( $privacy ) {

			case 'public':
				$visibility = true;
				break;

			case 'private':

				$visibility = bp_core_can_edit_settings() ? true : false;

				break;

			case 'loggedin':

				$visibility = is_user_logged_in() ? true : false;

				break;

			case 'friends':

				if ( bp_is_active( 'friends' ) ) {

					// Get User ID
					$loggedin_user = bp_loggedin_user_id();

					// Get Profile User ID
					$profile_user = bp_displayed_user_id();

					$visibility = friends_check_friendship( $loggedin_user, $profile_user ) ? true : false;

				}

				break;

			default:
				$visibility = false;
				break;

		}

	}

	return apply_filters( 'youzify_is_user_can_see_reviews', $visibility );

}

/**
 * Get Reviews Rating System
 */
function youzify_get_ratings_stars_data() {

	$args = array(
		array( 'number' => 5, 'description' => __( 'Excellent', 'youzify' ) ),
		array( 'number' => 4, 'description' => __( 'Good', 'youzify' ) ),
		array( 'number' => 3, 'description' => __( 'Average', 'youzify' ) ),
		array( 'number' => 2, 'description' => __( 'Below Average', 'youzify' ) ),
		array( 'number' => 1, 'description' => __( 'Poor', 'youzify' ) )
	);

 	return apply_filters( 'youzify_ratings_stars_data', $args );

}

/**
 * Get Review Stars
 */
function youzify_get_review_stars_form( $args = null ) {

	$args = wp_parse_args( $args , array(
		'fractional' => false,
		'rating' => 0,
	) );

	$html = '<div class="youzify-rate-user">';
	$html .= '<span class="youzify-rate-user-desc">' . __( 'What is your rating?', 'youzify' ) . '</span>';

	// Get
	$ratings = youzify_get_ratings_stars_data();

 	foreach ( $ratings as $rating ) {

 		$rating = $rating['number'];
		$class = is_integer( $rating ) ? 'full' : 'half';
		$checked = $rating == $args['rating'] ? 'checked' : '';
		$id = is_integer( $rating ) ? $rating : ( $rating - 0.5 ) . 'half' ;

		// Get Star HTML.
		$star_html = "<input type='radio' id='star$id' name='rating' value='$rating' $checked><label class='$class' for='star$id' ></label>";

		// Filter
		$html .= apply_filters( 'youzify_review_star_html', $star_html, $rating );

	}

	$html .= '</div>';

	return apply_filters( 'youzify_review_stars_form', $html );

}

function youzify_is_user_review_description_required() {
	return apply_filters( 'youzify_is_user_review_description_required', 'true' );
}

/**
 * Get Profiles/Groups Share Buttons
 */
function youzify_get_user_review_form() {

    // Get Data.
    $options = array();
    $reviewer = bp_loggedin_user_id();
    $operation = isset( $_POST['operation'] ) ? sanitize_text_field( $_POST['operation'] ) : null;
    $user_id = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : null;
    $review_id = isset( $_POST['review_id'] ) ? absint( $_POST['review_id'] ) : null;

    if ( empty( $operation ) || empty( $user_id ) ) {
        $response['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzify' );
		die( json_encode( $response ) );
    }

    // Args
    $modal_args = array(
        'show_close'=> false,
        'id'        => 'youzify-review-form',
        'button_id' => 'youzify-add-review',
        'operation' => $operation
    );

    if ( $operation == 'add' ) {

		// Check if user Already Reviewed Post.
		$review_id = youzify()->reviews->query->get_review_id( $user_id, $reviewer );

		if ( $review_id ) {
			$response['error'] = __( 'You already reviewed this user.', 'youzify' );
			die( json_encode( $response ) );
		}

    	$options['reviewed'] = $user_id;
	    $modal_args['title'] = __( 'Add Review', 'youzify' );
	    $modal_args['button_title'] = __( 'Submit Review', 'youzify' );

    } elseif ( $operation == 'edit' ) {

		// Get Review Data.
		$options = youzify()->reviews->query->get_review_data( $review_id );

    	if ( ! youzify_is_user_can_edit_reviews( $options ) ) {
			$response['error'] = __( 'You are not allowed to edit reviews.', 'youzify' );
			die( json_encode( $response ) );
    	}

	    $modal_args['title'] = __( 'Edit Review', 'youzify' );
	    $modal_args['button_title'] = __( 'Update Review', 'youzify' );
	    $modal_args['delete_button_title'] = __( 'Delete Review', 'youzify' );
	    $modal_args['delete_button_id'] = 'youzify-delete-review';
	    $modal_args['show_delete_button'] = true;

    }

    // Add Data.
    $options['action'] = $operation;

    // Get User Review Form.
    youzify_modal( $modal_args, 'youzify_user_review_form', $options );

}

add_action( 'wp_ajax_youzify_get_user_review_form', 'youzify_get_user_review_form' );

/**
 * User Review Form
 */
function youzify_user_review_form( $args = null ) {

	// Get Data
	$review_id = isset( $args['id'] ) ? $args['id'] : null;
	$action = isset( $args['action'] ) ? $args['action'] : null;
	$review = isset( $args['review'] ) ? $args['review'] : null;
	$reviewed = isset( $args['reviewed'] ) ? $args['reviewed'] : null;

	// Get Ratins
	$rating_args['rating'] = isset( $args['rating'] ) ? $args['rating'] : null;

	echo youzify_get_review_stars_form( $rating_args );

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-networks-form'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Review', 'youzify' ),
            'desc'         => __( 'Type your review', 'youzify' ),
            'id'           => 'review',
            'type'         => 'textarea',
            'std' 		   => $review,
            'no_options'   => true,
        )
    );

    ?>

	<input type="hidden" name="reviewed" value="<?php echo $reviewed; ?>">
	<input type="hidden" name="operation" value="<?php echo $action; ?>">

	<?php if ( ! empty( $review_id ) ) : ?>
		<input type="hidden" name="review_id" value="<?php echo $review_id; ?>">
	<?php endif; ?>

    <?php

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Check is User Can Review Users.
 */
function youzify_allow_anonymous_reviews() {
	return apply_filters( 'youzify_allow_anonymous_reviews', false );
}

/**
 * Check is User Can Add Reviews .
 */
function youzify_is_user_can_add_reviews( $user_id = null ) {

	// Init Vars
	$can = false;

	if ( ! youzify_allow_anonymous_reviews() && ! is_user_logged_in()  ) {
		$can = false;
	} else {
		$can = true;
	}

	if ( bp_loggedin_user_id() == $user_id ) {
		$can = false;
	}

	return apply_filters( 'youzify_is_user_can_add_reviews', $can, $user_id );

}

/**
 * Check is User Can Edit Reviews .
 */
function youzify_is_user_can_edit_reviews( $review = null ) {

	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Init Vars
	$can = false;

	if ( 'on' == youzify_option( 'youzify_allow_users_reviews_edition', 'off' ) && isset( $review['reviewer'] ) && $review['reviewer'] == bp_loggedin_user_id() ) {
		$can = true;
	}

	// Get Current User Data.
	$user = wp_get_current_user();

	// Filter Allowed Roles.
	$allowed_roles = apply_filters( 'youzify_allowed_roles_to_edit_reviews', array( 'administrator' ) );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			$can = true;
		}
	}

	return apply_filters( 'youzify_is_user_can_edit_reviews', $can, $user );

}

/**
 * Check is User Can Delete Reviews .
 */
function youzify_is_user_can_delete_reviews() {

	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Init Vars
	$can = false;

	// Get Current User Data.
	$user = wp_get_current_user();

	// Filter Allowed Roles.
	$allowed_roles = apply_filters( 'youzify_allowed_roles_to_delete_reviews', array( 'administrator' ) );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			$can = true;
		}
	}

	return apply_filters( 'youzify_is_user_can_delete_reviews', $can );

}

/**
 * Get Rating Stars.
 */
function youzify_star_rating( $args = array() ) {

    $defaults = array(
        'rating' => 0,
        'type'   => 'rating',
        'number' => 0,
        'echo'   => true,
    );

    $r = wp_parse_args( $args, $defaults );

    // Non-English decimal places when the $rating is coming from a string
    $rating = (float) str_replace( ',', '.', $r['rating'] );

    // Convert Percentage to star rating, 0..5 in .5 increments
    if ( 'percent' === $r['type'] ) {
        $rating = round( $rating / 10, 0 ) / 2;
    }

    // Calculate the number of each type of star needed
    $full_stars = floor( $rating );
    $half_stars = ceil( $rating - $full_stars );
    $empty_stars = 5 - $full_stars - $half_stars;

    if ( $r['number'] ) {
        /* translators: 1: The rating, 2: The number of ratings */
        $format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
        $title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
    } else {
        /* translators: 1: The rating */
        $title = sprintf( __( '%s Rating' ), number_format_i18n( $rating, 1 ) );
    }

    $output = '<div class="youzify-star-rating">';
    $output .= str_repeat( '<i class="fas fa-star star-full"></i>', $full_stars );
    $output .= str_repeat( '<i class="fas fa-star star-half"></i>', $half_stars );
    $output .= str_repeat( '<i class="fas fa-star star-empty"></i>', $empty_stars );
    $output .= '</div>';

    if ( $r['echo'] ) {
        echo $output;
    }

    return $output;
}

/**
 * Get User Reviews.
 */
function youzify_get_user_reviews( $args = null ) {
	$args = wp_parse_args( $args,
		array(
			'return' => false,
			'show_review' => true,
			'pagination' => false,
			'show_more' => false,
			'order_by' => 'desc',
			'per_page' => youzify_option( 'youzify_profile_reviews_per_page', 25 ),
		)
	);

	// Filter.
	$args = apply_filters( 'youzify_get_user_reviews_args', $args );

	// Get User ID.
	$user_id = isset( $args['user_id'] ) ? $args['user_id'] : null;

    global $Youzify;

	// Get Reviews Count
	$reviews_count = $Youzify->reviews->query->get_user_reviews_count( $user_id );

	if ( $args['return'] == true && $reviews_count <= 0 ) {
		return;
	}

	// Get Reviews
	$reviews = $Youzify->reviews->query->get_user_reviews( $args );

	ob_start();

	?>

	<div class="youzify-user-reviews">


		<?php do_action( 'youzify_before_reviews' ); ?>

		<?php foreach ( $reviews as $review ) : ?>

		<div class="youzify-item youzify-review-item">

			<?php do_action( 'youzify_before_review_head', $review ); ?>

			<div class="youzify-item-head">
				<div class="youzify-item-img"><?php echo bp_core_fetch_avatar( array( 'item_id' => $review['reviewer'], 'type' => 'thumb' ) ); ?></div>
				<div class="youzify-head-meta">

					<div class="youzify-item-name"><?php echo bp_core_get_userlink( $review['reviewer'] ); ?></div>
					<div class="youzify-item-date"><?php echo date( 'F j, Y', strtotime( $review['time'] ) ); ?></div>
				</div>

				<div class="youzify-item-rating"><?php youzify_star_rating( array( 'rating' => $review['rating'] ) ); ?></div>
			</div>

			<?php do_action( 'youzify_after_review_head', $review ); ?>

			<div class="youzify-item-content">
				<?php do_action( 'youzify_before_review_content' ); ?>
				<?php if ( $args['show_review'] == true ) : ?>
					<div class="youzify-item-desc"><?php echo stripslashes( esc_html( $review['review'] ) ); ?></div>
				</div>
				<?php do_action( 'youzify_after_review_content' ); ?>
			<?php endif; ?>

		</div>

		<?php endforeach; ?>

		<?php do_action( 'youzify_after_reviews' ); ?>

		<?php
			if ( $args['pagination'] == true ) {
				youzify_reviews_pagination( $reviews_count, $args['per_page'] );
			}
		?>

		<?php if ( empty( $reviews ) ) : ?>
			<div id="message" class="info">
				<p><?php _e( 'Sorry, there are no reviews.', 'youzify' ); ?></p>
			</div>
		<?php endif; ?>


		<?php if ( $args['show_more'] == true && $reviews_count > 0 && $args['per_page'] <= $reviews_count ) : ?>
			<?php $reviews_slug = youzify_reviews_tab_slug();  ?>
			<a href="<?php echo youzify_get_user_profile_page( $reviews_slug ); ?>" class="youzify-rating-show-more"><?php echo sprintf( __( 'Show All Ratings ( %s )', 'youzify' ), $reviews_count ); ?></a>
		<?php endif; ?>

	</div>

	<?php

    return ob_get_clean();
}

add_shortcode( 'youzify_reviews', 'youzify_get_user_reviews' );

/**
 * Pagination.
 */
function youzify_reviews_pagination( $total_items, $per_page = null ) {

	// Get Base
	$base = isset( $_POST['base'] ) ? sanitize_text_field( $_POST['base'] ) : get_pagenum_link( 1 );

	// Get items Per Page Number
	$per_page = ! empty( $per_page ) ? $per_page : 1;

	// Get total Pages Number
	$max_page = ceil( $total_items / $per_page );

	// Get Current Page Number
	$cpage = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1 ;

	// Get Next and Previous Pages Number
	if ( ! empty( $cpage ) ) {
		$next_page = $cpage + 1;
		$prev_page = $cpage - 1;
	}

	// Pagination Settings
	$comments_args = array(
		'base'        => $base . '%_%',
		'format' 	  => 'page/%#%',
		'total'       => $max_page,
		'current'     => $cpage,
		'show_all'    => false,
		'end_size'    => 1,
		'mid_size'    => 2,
		'prev_next'   => True,
		'prev_text'   => '<div class="youzify-page-symbole">&laquo;</div><span class="youzify-next-nbr">'. $prev_page .'</span>',
		'next_text'   => '<div class="youzify-page-symbole">&raquo;</div><span class="youzify-next-nbr">'. $next_page .'</span>',
		'type'         => 'plain',
		'add_args'     => false,
		'add_fragment' => '',
		'before_page_number' => '<span class="youzify-page-nbr">',
		'after_page_number'  => '</span>',
	);

	// Call Pagination Function
	$paginate_comments = paginate_links( $comments_args );

	// Print Comments Pagination
	if ( $paginate_comments ) {
		echo sprintf( '<nav class="youzify-pagination" data-base="%1s" data-per-page="%2s">' , $base, $per_page );
		echo '<span class="youzify-pagination-pages">';
		printf( __( 'Page %1$d of %2$d' , 'youzify' ), $cpage, $max_page );
		echo "</span><div class='youzify-reviews-nav-links youzify-nav-links'>$paginate_comments</div></nav>";
	}

}

/**
 * Get User Rating Rate
 */
function youzify_get_user_rating_rate_format( $user_rate ) {
	return round( $user_rate, 2 );
}

add_filter( 'youzify_get_user_ratings_rate', 'youzify_get_user_rating_rate_format', 10 );

/**
 * Get User User Reviews Details
 */
function youzify_get_ratings_details( $args = null ) {

	$args = wp_parse_args( $args , array(
		'show_rate' => true,
		'show_stars' => true,
		'show_total' => true,
		'user_id' => bp_displayed_user_id(),
		'separator' => '<span class="youzify-separator">•</span>',
	) );

	if ( ! youzify_is_user_can_receive_reviews( $args['user_id'] ) ) {
		return;
	}

	$youzify_query = youzify()->reviews->query;
	$user_rate = $youzify_query->get_user_ratings_rate( $args['user_id'] );

	?>

	<div class="youzify-user-ratings-details">

		<?php if ( $args['show_stars'] == true ) :?>
		<div class="youzify-user-rating-stars"><?php youzify_star_rating( array( 'rating' => $user_rate ) ); ?></div>
		<?php endif; ?>

		<?php if ( $args['show_rate'] == true ) :?>
		<?php echo $args['separator']; ?>
			<div class="youzify-user-ratings-rate"><?php echo sprintf( __( '%s out of 5', 'youzify' ) , $user_rate ); ?></div>
		<?php endif; ?>

		<?php if ( $args['show_total'] == true ) :  ?>
			<?php $reviews_count = $youzify_query->get_user_reviews_count( $args['user_id'] ); echo $args['separator']; ?>
			<div class="youzify-user-ratings-total"><?php echo sprintf( _n( '%s Rating', '%s Ratings', $reviews_count, 'youzify' ), number_format_i18n( $reviews_count ) ); ?></div>
		<?php endif; ?>

	</div>

	<?php

}

/**
 * Add Reviews
 */
function youzify_reviews_script_vars( $vars ) {

	// Get User ID.
	$user_id = bp_loggedin_user_id();

	// Add Var.
	$vars['is_user_can_edit_reviews'] = youzify_is_user_can_edit_reviews( $user_id );

	return $vars;

}