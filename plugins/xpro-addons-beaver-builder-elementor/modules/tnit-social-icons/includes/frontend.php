<?php
/**
 *  TNIT Social social-icon Module front-end file
 *
 *  @package TNIT Social Icons Module
 */

?>
<div class="tnit-social-icon-wrap tnit-social-icon-<?php echo esc_attr( $settings->social_icon_layout ); ?>">
<?php

$social_icons_count = count( $settings->social_icons );
for ( $i = 0; $i < $social_icons_count; $i++ ) {

	$social_icon = $settings->social_icons[ $i ];

	$url = $social_icon->link;

	?>

	<div class="tnit-social-icon-link-wrap">
		<a class="tnit-social-icon-link tnit-social-icon-<?php echo esc_attr( $i ); ?>" target="<?php echo esc_attr( $social_icon->link_target ); ?>" <?php echo esc_attr( ( $social_icon->link_nofollow === 'yes' ) ? ' rel="nofollow"' : '' ); ?> href="<?php echo esc_url( $url ); ?>">
			<span class="tnit-social-icon-wrap tnit-social-icon-<?php echo esc_attr( $settings->social_icon_bg_style ); ?>">
				<?php $module->render_social_icon( $i ); ?>
				<?php $module->render_social_photo( $i ); ?>
			</span>
		</a>
	</div>

<?php } ?>

</div>
