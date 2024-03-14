<?php

global $wp_embed;
$title_tag   = ( $settings->title_link ) ? 'a' : 'h2';
$title_attr  = $settings->title_link_target ? ' target=_blank' : '';
$title_attr .= $settings->title_link_nofollow ? ' rel=nofollow' : '';
$title_attr .= $settings->title_link ? ' href=' . $settings->title_link . '' : '';

?>

<div class="xpro-team-wrapper xpro-team-layout-<?php echo esc_attr( $settings->layout ); ?>">

	<?php if ( $settings->designation && '2' === $settings->layout ) : ?>
		<h4 class="xpro-team-designation"><?php echo esc_attr( $settings->designation ); ?></h4>
	<?php endif; ?>

	<div class="xpro-team-image">
		<img src="<?php echo esc_url( $settings->image_src ? $settings->image_src : XPRO_ADDONS_FOR_BB_URL . 'assets/images/placeholder-sm.webp' ); ?>" alt="image">

		<?php if ( '8' === $settings->layout || '9' === $settings->layout ) { ?>
			<div class="xpro-team-inner-content">
				<?php if ( $settings->title ) : ?>
					<<?php echo esc_attr( $title_tag ); ?><?php echo $title_attr; ?> class="xpro-team-title"><?php echo esc_attr( $settings->title ); ?></<?php echo esc_attr( $title_tag ); ?>>
				<?php endif; ?>

				<?php if ( $settings->designation ) : ?>
					<h4 class="xpro-team-designation"><?php echo esc_attr( $settings->designation ); ?></h4>
				<?php endif; ?>
			</div>
		<?php } ?>

		<?php if ( '2' === $settings->layout || '3' === $settings->layout || '5' === $settings->layout || '8' === $settings->layout || '12' === $settings->layout || '13' === $settings->layout || '15' === $settings->layout ) { ?>
			<ul class="xpro-team-social-list">
				<?php
                $social_icon_list_count = count( $settings->social_icon_list );
				for ( $i = 0; $i < $social_icon_list_count; $i++ ) {
					$item = $settings->social_icon_list[ $i ];

					$html_tag = ( $item->icon_link ) ? 'a' : 'span';
					$attr     = $item->icon_link_target ? ' target=_blank' : '';
					$attr    .= $item->icon_link_nofollow ? ' rel=nofollow' : '';
					$attr    .= $item->icon_link ? ' href=' . $item->icon_link . '' : '';

					?>
					<li class="xpro-team-item-<?php echo esc_attr( $i ); ?>">
					<<?php echo esc_attr( $html_tag ); ?> <?php echo $attr; ?> class="xpro-team-social-icon">
						<i class="<?php echo esc_attr( $item->social_icon ); ?>" aria-hidden="true"></i>
					</<?php echo esc_attr( $html_tag ); ?>>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>

	<div class="xpro-team-content">
		<?php if ( '8' !== $settings->layout && '9' !== $settings->layout ) : ?>

			<?php if ( $settings->title ) : ?>
				<<?php echo esc_attr( $title_tag ); ?><?php echo $title_attr; ?> class="xpro-team-title"><?php echo esc_attr( $settings->title ); ?></<?php echo esc_attr( $title_tag ); ?>>
			<?php endif; ?>

			<?php if ( $settings->designation && '2' !== $settings->layout ) : ?>
				<h4 class="xpro-team-designation"><?php echo esc_attr( $settings->designation ); ?></h4>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ( $settings->description ) : ?>
			<div class="xpro-team-description"><?php echo wpautop( $wp_embed->autoembed( $settings->description ) ); ?></div>
		<?php endif; ?>

		<?php if ( '2' !== $settings->layout && '3' !== $settings->layout && '5' !== $settings->layout && '8' !== $settings->layout && '12' !== $settings->layout && '13' !== $settings->layout && '15' !== $settings->layout ) { ?>
			<ul class="xpro-team-social-list">
				<?php
                $social_icon_list_count = count( $settings->social_icon_list );
				for ( $i = 0; $i < $social_icon_list_count; $i++ ) {
					$item = $settings->social_icon_list[ $i ];

					$html_tag = ( $item->icon_link ) ? 'a' : 'span';
					$attr     = $item->icon_link_target ? ' target=_blank' : '';
					$attr    .= $item->icon_link_nofollow ? ' rel=nofollow' : '';
					$attr    .= $item->icon_link ? ' href=' . $item->icon_link . '' : '';

					?>
					<li class="xpro-team-item-<?php echo esc_attr( $i ); ?>">
					<<?php echo esc_attr( $html_tag ); ?> <?php echo $attr; ?> class="xpro-team-social-icon">
						<i class="<?php echo esc_attr( $item->social_icon ); ?>" aria-hidden="true"></i>
					</<?php echo esc_attr( $html_tag ); ?>>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>

</div>
