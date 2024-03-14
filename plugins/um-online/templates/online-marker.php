<?php
/**
 * Template for the UM Online marker.
 *
 * Caller: um_online_show_user_status(), messaging_show_online_dot()
 * @version 2.1.6
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-online/online-marker.php
 * @var bool $is_online
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$class = $is_online ? 'online' : 'offline';
$title = $is_online ? __( 'online', 'um-online' ) : __( 'offline', 'um-online' ); ?>

<span class="um-online-status <?php echo esc_attr( $class ) ?> um-tip-n" title="<?php echo esc_attr( $title ) ?>">
	<i class="um-faicon-circle"></i>
</span>
