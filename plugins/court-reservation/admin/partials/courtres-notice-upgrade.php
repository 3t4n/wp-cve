<?php

/**
 * Provide notice for pro-upgrade in admin view
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    Courtres
 * @subpackage Courtres/admin/partials
 */
?>


<?php if ( ! cr_fs()->is_plan( 'premium' ) ) { ?>
  <div class="notice notice-success is-dismissible"> 
	<p><strong>
	<?php
	// $s_upgrade = 'Hi, you are using the free version of Court Reservation. If you want to be able to create unlimited courts and have unlimited club members, please <a href="%s">upgrade</a> to our premium version of Court Reservation!';
	$url = admin_url( 'admin.php?page=courtres&tab=4' );
	echo sprintf( wp_kses( __( 'Hi, you are using the free version of Court Reservation. If you want to be able to create unlimited courts and have unlimited club members, please <a href="%s">upgrade</a> to our premium version of Court Reservation!', 'court-reservation' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
	?>
	</strong></p>
	<button type="button" class="notice-dismiss">
	  <span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'court-reservation' ); ?></span>
	</button>
  </div>
<?php } ?>
<?php if ( ! cr_fs()->is_plan( 'ultimate' ) ) { ?>
  <div class="notice notice-success is-dismissible"> 
	<p><strong>
	<?php
	// $s_upgrade = 'Thx a lot for using Court Reservation Premium. Do you know our <a href="%s">Ultimate</a> Plan? With Court Reservation Ultimate you can now create Ladder Competitions for your Tennis Club.';
	$url = admin_url( 'admin.php?page=courtres&tab=4' );
	echo sprintf( wp_kses( __( 'Thx a lot for using Court Reservation Premium. Do you know our <a href="%s">Ultimate</a> Plan? With Court Reservation Ultimate you can now create Ladder Competitions for your Tennis Club.', 'court-reservation' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
	?>
	</strong></p>
	<button type="button" class="notice-dismiss">
	  <span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'court-reservation' ); ?></span>
	</button>
  </div>
<?php } ?>
