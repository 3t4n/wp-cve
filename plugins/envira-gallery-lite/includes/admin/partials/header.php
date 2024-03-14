<?php
/**
 * Outputs the green Envira Gallery Header
 *
 * @since   1.5.0
 *
 * @var array $data Array of data to pass to the view.
 *
 * @package Envira_Gallery
 * @author     David Bisset, Envira Team
 */

$upgrade_link = Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( 'https://enviragallery.com/pricing', 'topbar', 'goPro' );

// Load the base class object.
$base                = Envira_Gallery_Lite::get_instance();
$notifications_count = $base->notifications->get_count();
$dismissed_count     = $base->notifications->get_dismissed_count();
$data_count          = '';
if ( $notifications_count > 0 ) {
	$data_count = sprintf(
		'data-count="%d"',
		absint( $notifications_count )
	);
}
?>
<div id="envira-header-temp"></div>

	<div id="envira-top-notification" class="envira-header-notification">
		<p>You're using Envira Gallery Lite. To unlock more features, <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank"><strong>consider upgrading to Pro.</strong></a></p>
	</div>

<div id="envira-header" class="envira-header">
	<h1 class="envira-logo" id="envira-logo">
		<img src="<?php echo esc_url( $data['logo'] ); ?>" alt="<?php esc_attr_e( 'Envira Gallery', 'envira-gallery-lite' ); ?>" width="339"/>
	</h1>

	<div class="envira-right">
		<a type="button" id="envira-notifications-button" class="envira-button-just-icon envira-notifications-inbox envira-open-notifications" data-dismissed="<?php echo esc_attr( $dismissed_count ); ?>" <?php echo $data_count; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<svg class="envira-icon envira-icon-inbox" width="20" height="20" viewBox="0 0 15 16" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.3333 0.5H1.66667C0.75 0.5 0 1.25 0 2.16667V13.8333C0 14.75 0.741667 15.5 1.66667 15.5H13.3333C14.25 15.5 15 14.75 15 13.8333V2.16667C15 1.25 14.25 0.5 13.3333 0.5ZM13.3333 13.8333H1.66667V11.3333H4.63333C5.20833 12.325 6.275 13 7.50833 13C8.74167 13 9.8 12.325 10.3833 11.3333H13.3333V13.8333ZM9.175 9.66667H13.3333V2.16667H1.66667V9.66667H5.84167C5.84167 10.5833 6.59167 11.3333 7.50833 11.3333C8.425 11.3333 9.175 10.5833 9.175 9.66667Z" fill="#777777"></path></svg>
		</a>
	</div>

	<?php do_action( 'envira_admin_in_header', $data ); ?>

</div>
