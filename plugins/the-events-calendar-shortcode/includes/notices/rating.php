<?php

define( 'ECS_RATING_OPTION_NAME', 'ecs_asked_for_rating' );

function ecs_get_activation_date() {
    $activation_date = get_option( 'ecs_activation_date', '' );

    if ( ! $activation_date ) {
        $activation_date = time();

        update_option( 'ecs_activation_date', $activation_date );
    }

    return $activation_date;
}

function ecs_setup_rating_notice() {
    $activated = ecs_get_activation_date();

    if ( ! is_numeric( $activated ) || ( $activated + ( DAY_IN_SECONDS * 30 ) ) > time() ) {
        return;
    }

    if ( !current_user_can( 'manage_options' ) ||
         defined( 'TECS_VERSION' ) ||
         get_option( ECS_RATING_OPTION_NAME, false )
    ) {
        return;
    }
    add_action( 'admin_notices', 'ecs_display_rating_notice' );
}
add_action( 'admin_init', 'ecs_setup_rating_notice' );

function ecs_display_rating_notice() {
    $screen = get_current_screen();

    if ( ! is_object( $screen ) ||
         (
             'dashboard' !== $screen->id &&
             'tribe_events' !== $screen->post_type
         ) ) {
        return;
    } ?>
	<div class="notice notice-success ecs_notice_server ecs-dismissible-notice is-dismissible">
    <?php wp_nonce_field( 'ecs-rating-nonce', 'ecs-rating-nonce' );

    ?>
		<p><?php esc_html_e( 'Hey, I noticed you\'ve been using The Events Calendar Shortcode & Block for a while! Could you please do me a BIG favour and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'the-events-calendar-shortcode' ); ?></p>
		<p><?php echo wp_kses( __( '- Brian Hogg<br>Developer of The Events Calendar Shortcode', 'the-events-calendar-shortcode' ), ['br' => []] ); ?></p>
		<p>
			<a class="ecs-button button button-primary ecs-notice-dismiss" target="_blank" href="https://wordpress.org/support/plugin/the-events-calendar-shortcode/reviews/?filter=5#new-post">
				<?php esc_html_e( 'Ok, you deserve it!', 'the-events-calendar-shortcode' ); ?>
			</a>
      <button class="button-link ecs-notice-dismiss"><?php esc_html_e( 'No thanks', 'the-events-calendar-shortcode' ); ?></button>
		</p>
		<script>jQuery(function($) {$(document).on("click", ".ecs-notice-dismiss",function dismiss() {$.ajax(window.ajaxurl,{type: "POST",data: {action: "ecs_dismiss_rating_notice", nonce: $('#ecs-rating-nonce').val()}}); $('.ecs-dismissible-notice').hide()});});</script><p></p><button type="button" class="notice-dismiss ecs-notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
	<?php
}

function ecs_dismiss_rating_notice() {
    if ( ! is_user_logged_in() || ! wp_verify_nonce( $_POST['nonce'], 'ecs-rating-nonce' ) ) {
        die( -1 );
    }
    update_option( ECS_RATING_OPTION_NAME, true );

    wp_die();
}
add_action( 'wp_ajax_ecs_dismiss_rating_notice', 'ecs_dismiss_rating_notice' );
