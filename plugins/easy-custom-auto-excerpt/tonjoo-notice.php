<?php
/**
 * Rating notice
 *
 * @package ECAE
 */

/**
 * Enqueue notice script & style
 */
function ecae_notice_admin_enqueue() {
	wp_enqueue_style( 'ecae-notice-css', plugin_dir_url( __FILE__ ) . 'assets/notice/notice.css', array(), ECAE_VERSION );
	wp_enqueue_style( 'ecae-notice-js', plugin_dir_url( __FILE__ ) . 'assets/notice/notice.js', array(), ECAE_VERSION );
}
add_action( 'admin_enqueue_scripts', 'ecae_notice_admin_enqueue' );

/**
 * Notice action
 */
function ecae_notice_action() {
	if ( ! isset( $_GET['ecae_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['ecae_nonce'] ), 'notice_action' ) ) { // input var ok.
		return;
	} else {
		$action = isset( $_GET['ecae_notice_action'] ) ? sanitize_key( $_GET['ecae_notice_action'] ) : ''; // input var ok.
	}

	if ( 'ok' === $action ) {
		update_option( 'ecae_ignore_notice', true );
		wp_redirect( 'https://wordpress.org/support/plugin/easy-custom-auto-excerpt/reviews/#new-post' );
		exit();
	} elseif ( 'later' === $action ) {
		update_option( 'ecae_later_date', date( 'Y-m-d H:i:s' ) );
		$location = admin_url( 'admin.php?page=tonjoo_excerpt' ) . '&settings-updated=true';
		wp_safe_redirect( $location );
		exit();
	} elseif ( 'done' === $action ) {
		update_option( 'ecae_ignore_notice', true );
		$location = admin_url( 'admin.php?page=tonjoo_excerpt' ) . '&settings-updated=true';
		wp_safe_redirect( $location );
		exit();
	}
}
add_action( 'admin_init', 'ecae_notice_action' );

/**
 * Display notice
 */
function ecae_rating_notice() {
	$ignore_notice = get_option( 'ecae_ignore_notice' );

	if ( $ignore_notice ) {
		return;
	}

	$ecae_start_date = get_option( 'ecae_start_date' );
	$ecae_later_date = get_option( 'ecae_later_date' );
	$now             = date( 'Y-m-d H:i:s' );

	if ( ! $ecae_start_date ) {
		add_option( 'ecae_start_date', $now );
		return;
	}

	$time        = WEEK_IN_SECONDS;
	$check_date  = $ecae_start_date;
	$week_number = 1;

	if ( $ecae_later_date ) {
		$check_date  = $ecae_later_date;
		$diff        = strtotime( $now ) - strtotime( $ecae_start_date );
		$week_number = floor( $diff / WEEK_IN_SECONDS );
		if ( empty( $week_number ) ) {
			$week_number = 1;
		}
	}

	/* Translators: %s: Number of weeks */
	$week_text = sprintf( _n( '%s week', '%s weeks', $week_number, 'easy-custom-auto-excerpt' ), $week_number );

	if ( strtotime( $now ) - strtotime( $check_date ) < $time ) {
		return;
	}

	$ok_url    = wp_nonce_url( admin_url( 'admin.php?page=tonjoo_excerpt&ecae_notice_action=ok' ), 'notice_action', 'ecae_nonce' );
	$later_url = wp_nonce_url( admin_url( 'admin.php?page=tonjoo_excerpt&ecae_notice_action=later' ), 'notice_action', 'ecae_nonce' );
	$done_url  = wp_nonce_url( admin_url( 'admin.php?page=tonjoo_excerpt&ecae_notice_action=done' ), 'notice_action', 'ecae_nonce' );
	?>

	<div class="tonjoo-notice updated">
		<div class="tonjoo-notice-img">
			<img src="<?php echo esc_url( plugins_url( ECAE_DIR_NAME ) ); ?>/assets/notice/ecae_logo.png">
			<img src="<?php echo esc_url( plugins_url( ECAE_DIR_NAME ) ); ?>/assets/notice/stars.png">
		</div>

		<p>
			<?php
			/* Translators: %s: Number of weeks */
			printf( esc_html__( "Hey, we noticed you've been using Easy Custom Auto Excerpt for %s - that's awesome!", 'easy-custom-auto-excerpt' ), $week_text );
			?>
			<br>
			<?php esc_html_e( 'Could you please do us a BIG favor and give it 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', 'easy-custom-auto-excerpt' ); ?>
		</p>

		<p>
			<a class="button-primary" href="<?php echo esc_url( $ok_url ); ?>"><?php esc_html_e( 'Ok, you deserve it', 'easy-custom-auto-excerpt' ); ?></a>

			<a class="button" href="<?php echo esc_url( $later_url ); ?>"><?php esc_html_e( 'Nope, maybe later', 'easy-custom-auto-excerpt' ); ?></a>

			<a class="button" href="<?php echo esc_url( $done_url ); ?>"><?php esc_html_e( 'I already did', 'easy-custom-auto-excerpt' ); ?></a>
		</p>
	</div>

	<?php
}
add_action( 'admin_notices', 'ecae_rating_notice' );
