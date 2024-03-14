<?php
/**
 * Template for the UM Online Users.
 * Used for "Ultimate Member - Online Users" widget.
 *
 * Caller: method Online_Shortcode->ultimatemember_online()
 * Shortcode: [ultimatemember_online]
 * @version 2.1.9
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-online/online.php
 * @var array  $online
 * @var int    $max
 * @var string $roles
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$previous_user_id = um_user( 'ID' );
?>

<div class="um-online" data-max="<?php echo esc_attr( $max ); ?>">
	<?php
	foreach ( $online as $user => $last_seen ) {

		um_fetch_user( $user );

		$user_meta  = get_userdata( $user );
		$user_roles = $user_meta->roles;
		if ( 'all' !== $roles && count( array_intersect( $user_roles, explode( ',', $roles ) ) ) <= 0 ) {
			continue;
		}

		$name = um_user( 'display_name' );
		if ( empty( $name ) ) {
			continue;
		}
		?>

		<div class="um-online-user">
			<div class="um-online-pic">
				<a href="<?php echo esc_url( um_user_profile_url() ); ?>" class="um-tip-n" title="<?php echo esc_attr( $name ); ?>">
					<?php echo get_avatar( um_user( 'ID' ), 40 ); ?>
				</a>
			</div>
		</div>

		<?php
	}

	if ( ! $previous_user_id ) {
		um_reset_user();
	} else {
		um_fetch_user( $previous_user_id );
	}
	?>
	<div class="um-clear"></div>
</div>
