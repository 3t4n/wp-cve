<?php
/**
 * Template for the UM Online status text.
 *
 * Caller: um_online_show_status()
 * @version 2.1.6
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-online/online-text.php
 * @var bool $is_online
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$class = $is_online ? 'online' : 'offline';
$title = $is_online ? __( 'online', 'um-online' ) : __( 'offline', 'um-online' ); ?>

<span class="um-online-status <?php echo esc_attr( $class ) ?>">
	<?php echo $title ?>
</span>
