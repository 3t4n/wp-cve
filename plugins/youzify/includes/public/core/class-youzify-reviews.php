<?php

class Youzify_Reviews {

	public $query;
	
	function __construct() {

		$this->query = new Youzify_Reviews_Query();

		// Actions.
		add_action( 'wp_ajax_youzify_handle_user_reviews', array( $this, 'handle_user_reviews' ) );
		add_action( 'wp_ajax_youzify_delete_user_review', array( $this, 'delete_user_review' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'bp_setup_nav', array( $this, 'setup_tabs' ) );
		add_action( 'youzify_user_tools', array( $this, 'get_user_review_tool' ), 10, 2 );
		add_action( 'youzify_before_review_head', array( $this, 'get_review_tools' ) );
		add_action( 'youzify_author_box_ratings_content', array( $this, 'author_box_ratings' ) );
		add_action( 'bp_directory_members_item', array( $this, 'add_members_directory_cards_ratings' ), 100 );

		// Filters.
		add_filter( 'youzify_review_tools', array( $this, 'add_review_tools' ), 10, 2 );

		// Reviews - Ajax Pagination.
		add_action( 'wp_ajax_nopriv_youzify_reviews_pagination', array( $this, 'pagination' ) );
		add_action( 'wp_ajax_youzify_reviews_pagination', array( $this, 'pagination' ) );

		// Statistics
		add_filter( 'youzify_get_user_statistic_number', array( $this, 'get_ratings_statistics_values' ), 10, 3 );

	}

	/**
	 * Setup Tabs.
	 */
	function setup_tabs() {

		if ( ! youzify_is_user_can_see_reviews() || ! youzify_is_user_can_receive_reviews() ) {
			return false;
		}

		$bp = buddypress();

		$reviews_slug = youzify_reviews_tab_slug();

		// Add Follows Tab.
		bp_core_new_nav_item(
		    array(
		        'position' => 250,
		        'slug' => $reviews_slug,
		        'name' => __( 'Reviews' , 'youzify' ),
		        'default_subnav_slug' => 'reviews',
		        'parent_slug' => $bp->profile->slug,
		        'screen_function' => 'youzify_reviews_screen',
		        'parent_url' => bp_displayed_user_url() . "$reviews_slug/"
		    )
		);

	}

	/**
	 * Handle Posts Bookmark
	 */
	function handle_user_reviews() {

		// Hook.
		do_action( 'youzify_before_adding_user_review' );

		// Check Ajax Referer.
		check_ajax_referer( 'youzify-nonce', 'security' );

		if ( ! youzify_is_user_can_add_reviews() ) {
			$response['error'] = __( 'The action you have requested is not allowed.', 'youzify' );
			die( json_encode( $response ) );
		}

		$action = isset( $_POST['operation'] ) ? sanitize_text_field( $_POST['operation'] ) : null;

		// Get Table Data.
		$data = array(
			'reviewer' => bp_loggedin_user_id(),
			'rating' => isset( $_POST['rating'] ) ? sanitize_text_field( $_POST['rating'] ) : null,
			'review' => isset( $_POST['review'] ) ? sanitize_textarea_field( $_POST['review'] ) : null,
			'reviewed' => isset( $_POST['reviewed'] ) ? absint( $_POST['reviewed'] ) : null,
		);

		// Filter Data.
		$data = apply_filters( 'youzify_user_review_form_data', $data );

		// Allowed Actions
		$allowed_actions = array( 'add', 'edit' );

		// Check Requested Action.
		if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
			$response['error'] = __( 'The action you have requested does not exist.', 'youzify' );
			die( json_encode( $response ) );
		}

		// Check if The Post ID & The Component are Exist.
		if ( empty( $data['reviewer'] ) || empty( $data['reviewed'] ) ) {
			$response['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzify' );
			die( json_encode( $response ) );
		}

		if ( empty( $data['rating'] ) ) {
			$response['error'] = __( 'Please make sure to rate the user.', 'youzify' );
			die( json_encode( $response ) );
		}

		if ( youzify_is_user_review_description_required() && empty( $data['review'] ) ) {
			$response['error'] = __( 'The review field is empty.', 'youzify' );
			die( json_encode( $response ) );
		}

		global $Youzify;

		$youzify_query = $Youzify->reviews->query;

		// Check if user Already Reviewed Post.
		$review_id = $youzify_query->get_review_id( $data['reviewed'], $data['reviewer'] );

		if ( $action == 'add' ) {

			if ( $data['reviewed'] == $data['reviewer'] ) {
				$response['error'] = __( "Sorry you can't post a review on yourself.", 'youzify' );
				die( json_encode( $response ) );
			}

			if ( $review_id ) {
				$response['error'] = __( 'You already reviewed this user.', 'youzify' );
				die( json_encode( $response ) );
			}

			// Get Review Ad.
			$review_id = $youzify_query->add_review( $data );

			if ( $review_id ) {
				// Update User Ratings Count & Rate.
				$youzify_query->update_user_reviews_count( $data['reviewed'] );
				$youzify_query->update_user_ratings_rate( $data['reviewed'] );
				do_action( 'youzify_after_adding_user_review', $review_id, $data );
			}

			$response['review_id'] = $review_id;
			$response['button_icon'] = 'fas fa-edit';
			$response['button_title'] = __( 'Edit Review', 'youzify' );
			$response['action'] = youzify_is_user_can_edit_reviews() ? 'edit' : 'delete_button';
			$response['msg'] = __( 'Your review has been successfully submitted.', 'youzify' );

		}	elseif ( 'edit' == $action ) {

			if ( ! youzify_is_user_can_edit_reviews( $data ) ) {
				$response['error'] = __( 'You are not allowed to edit reviews.', 'youzify' );
				die( json_encode( $response ) );
			}

			// Update Review.
			$review_id = $youzify_query->update_review( absint( $_POST['review_id'] ), $data );

			if ( $review_id ) {
				$youzify_query->update_user_ratings_rate( $data['reviewed'] );
				// Hook.
				do_action( 'youzify_after_updating_user_review', $review_id, $data );
			}

			// $response['action'] = 'delete_button';
			$response['msg'] = __( 'The review is successfully updated.', 'youzify' );

		}

		// Response Words
        $response['add_review'] = __( 'Add Review', 'youzify' );
        $response['edit_review'] = __( 'Edit Review', 'youzify' );
        $response['delete_review'] = __( 'Delete Review', 'youzify' );

		die( json_encode( $response ) );

	}

	/**
	 * Handle Delete User Review
	 */
	function delete_user_review() {

		// Check Ajax Referer.
		check_ajax_referer( 'youzify-nonce', 'security' );

		do_action( 'youzify_before_delete_user_review' );

		// Get Review ID.
		$review_id = isset( $_POST['review_id'] ) ? absint( $_POST['review_id'] ) : null;

		if ( empty( $review_id ) ) {
			$response['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzify' );
			die( json_encode( $response ) );
		}

		global $Youzify;

		// Get User Query.
		$youzify_query = $Youzify->reviews->query;

		// Get Review Data.
		$review_data = $youzify_query->get_review_data( $review_id );

		if ( ! $review_data ) {
			$response['error'] = __( 'The review is already deleted or does not exist.', 'youzify' );
			die( json_encode( $response ) );
		}

		do_action( 'youzify_before_deleting_user_review', $review_id, $review_data );

		// Delete Review.
		if ( $youzify_query->delete_review( $review_id ) ) {
			// Update User Ratings Count & Rate.
			$youzify_query->update_user_reviews_count( $review_data['reviewed'] );
			$youzify_query->update_user_ratings_rate( $review_data['reviewed'] );
			$response['msg'] = __( 'The review is successfully deleted.', 'youzify' );
		}

		die( json_encode( $response ) );
	}

	/**
	 * Reviews Scripts
	 */
	function scripts() {

	    // Call Review Script.
	    wp_enqueue_style( 'youzify-reviews', YOUZIFY_ASSETS . 'css/youzify-reviews.min.css', array(), YOUZIFY_VERSION );

	    $reviews_slug = youzify_reviews_tab_slug();

	    if ( bp_is_current_component( $reviews_slug ) ) {
	        wp_enqueue_script( 'youzify-reviews-pagination', YOUZIFY_ASSETS . 'js/youzify-reviews-pagination.min.js', array(), YOUZIFY_VERSION );
	    }

	}


	/**
	 * Add Reviews User Var.
	 */
	function add_query_vars( $vars ) {
		$vars['displayed_user_id'] = bp_displayed_user_id();
		return $vars;
	}

	/**
	 * Get User Review Tool.
	 */
	function get_user_review_tool( $user_id = null, $icons = null ) {

		if ( ! apply_filters( 'youzify_display_user_review_tool', true ) ) {
			return;
		}

		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		if ( ! youzify_is_user_can_receive_reviews( $user_id ) ) {
			return;
		}

		if ( bp_loggedin_user_id() == $user_id ) {
			return;
		}

		if ( ! youzify_is_user_can_add_reviews( $user_id ) && ! youzify_is_user_can_edit_reviews( $user_id ) ) {
			return false;
		}

		// Get User Value.
		$is_user_reviewed = youzify_is_user_already_reviewed( $user_id );

		// Get Action.

		if ( $is_user_reviewed ) {
			$action = 'edit';
			$button_icon = 'far fa-edit';
			$review_id = $is_user_reviewed;
			$button_title = __( 'Edit Review', 'youzify' );
		} else {
			$action = 'add';
			$review_id = 0;
			$button_icon = 'far fa-star';
			$button_title = __( 'Add Review', 'youzify' );
		}

		?>

		<div class="youzify-tool-btn youzify-review-btn" <?php if ( 'only-icons' == $icons ) { ?> data-youzify-tooltip="<?php echo $button_title; ?>"<?php } ?> data-action="<?php echo $action; ?>" data-review-id="<?php echo $review_id; ?>">
				<div class="youzify-tool-icon"><i class="<?php echo $button_icon; ?>"></i></div><?php if ( 'full-btns' == $icons ) : ?><div class="youzify-tool-name"><?php echo $button_title; ?></div><?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Reviews Tab Pagination.
	 */
	function pagination() {

		// Init Vars
		$page = absint( $_POST['page'] );
		$per_page = absint( $_POST['per_page'] );

		// Get Page Offset.
		$offset = ( $page - 1 ) *  $per_page;

		// Pagination Args
		$args = array(
			'pagination' => true,
			'page'   	=> $page,
			'offset'  	=> $offset,
			'per_page'  => $per_page,
			'base' 	  	=> sanitize_text_field( $_POST['base'] ),
			'user_id' 	=> absint( $_POST['user_id'] )
		);

		// Get Content
		echo youzify_get_user_reviews( $args );

	    die();

	}

	/**
	 * Author Box - Display Ratings
	 */
	function author_box_ratings( $args = null ) {

	    // Check Ratings Visibility
	    if ( 'off' == youzify_option( 'youzify_enable_author_box_ratings', 'on' ) ) {
	        return;
	    }

	    ?>

	    <div class="youzify-user-ratings"><?php youzify_get_ratings_details( $args ); ?></div>

	    <?php
	}

	/**
	 * Posts Tools Function
	 */
	function get_review_tools( $review = null ) {

		// Get Activity ID.
		$review_id = $review['id'];

		// Get Tools Data.
		$tools = array();

		// Filter.
		$tools = apply_filters( 'youzify_review_tools', $tools, $review );

		if ( empty( $tools ) ) {
			return false;
		}

		?>

		<div class="youzify-show-item-tools"><i class="fas fa-ellipsis-v"></i></div>
		<div class="youzify-item-tools" data-review-id="<?php echo $review_id; ?>" data-user-id="<?php echo $review['reviewer']; ?>">
			<?php foreach ( $tools as $tool ) : ?>
				<?php $attributes = isset( $tool['attributes'] ) ? $tool['attributes'] : null; ?>
				<div class="youzify-item-tool <?php echo youzify_generate_class( $tool['class'] ); ?>" <?php youzify_get_item_attributes( $attributes ); ?> <?php if ( isset( $tool['action'] ) ) { echo 'data-action="' . $tool['action'] .'"'; } ?>>
					<div class="youzify-tool-icon"><i class="<?php echo $tool['icon'] ?>"></i></div>
					<div class="youzify-tool-name"><?php echo $tool['title']; ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * Add New Review Tool.
	 */
	function add_review_tools( $tools, $review ) {

		if ( youzify_is_user_can_edit_reviews( $review ) ) {

			// Get Tool Data.
			$tools[] = array(
				'icon' => 'fas fa-edit',
				'title' => __( 'Edit', 'youzify' ),
				'action' => 'edit',
				'class' => array( 'youzify-review-tool', 'youzify-edit-tool' )
			);

		}

		if ( youzify_is_user_can_delete_reviews() ) {

			// Get Tool Data.
			$tools[] = array(
				'icon'   => 'fas fa-trash',
				'title'  => __( 'Delete', 'youzify' ),
				'action' => 'delete',
				'class' => array( 'youzify-review-tool', 'youzify-delete-tool' )
			);

		}

		return $tools;
	}

	/**
	 * Add Members Directory Cards Ratings.
	 */
	function add_members_directory_cards_ratings() {

		if ( ! bp_is_directory() ) {
			return false;
		}

	    // Get User id.
	    $user_id = bp_get_member_user_id();

		// Get User ID.
		youzify_get_ratings_details( array( 'user_id' => $user_id ) );

	}

	/**
	 * Get Statistics Value
	 */
	function get_ratings_statistics_values( $value, $user_id, $type ) {

		if ( $type == 'ratings' ) {
			return youzify()->reviews->query->get_user_reviews_count( $user_id );
		}

		return $value;

	}

}