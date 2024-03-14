<?php

global $wp_embed;
$tag   = ( $settings->adv_box_link ) ? 'a' : 'div';
$attr  = ( 'yes' === $settings->adv_box_link_target ) ? ' target=_blank' : '';
$attr .= ( 'yes' === $settings->adv_box_link_nofollow ) ? ' rel=nofollow' : '';
$attr .= $settings->adv_box_link ? ' href=' . $settings->adv_box_link . '' : '';

?>

<div class="xpro-heading-wrapper">
	<div class="xpro-heading-wrapper-inner">
		<div class="xpro-heading-content">
			<?php if ( '1' === $settings->adv_enable_shadow ) : ?>
				<<?php echo esc_attr( $settings->adv_shadow_title_tag ); ?> class="xpro-shadow-text"><?php echo esc_attr( $settings->adv_shadow_title ); ?></<?php echo esc_attr( $settings->adv_shadow_title_tag ); ?>>
			<?php endif; ?>
			<div class="xpro-heading-top">
				<div  class="xpro-heading-top-inner">

					<?php if ( '1' === $settings->adv_enable_sub_title && 'before-title' === $settings->adv_subtitle_position ) : ?>
						<<?php echo esc_attr( $settings->adv_subtitle_title_tag ); ?> class="xpro-heading-subtitle"><?php echo esc_attr( $settings->adv_subtitle_title ); ?></<?php echo esc_attr( $settings->adv_subtitle_title_tag ); ?>>
					<?php endif; ?>

					<?php if ( 'before-title' === $settings->adv_separator_styles->adv_separator_position && 'enable' === $settings->adv_title_separator_toggle ) : ?>
						<div class="xpro-heading-separator-<?php echo esc_attr( $settings->adv_separator_style ); ?>">

							<!-- separator text-->
							<?php if ( 'text' === $settings->adv_separator_style && ! empty( $settings->adv_separator_title ) ) { ?>
								<span> <?php echo esc_attr( $settings->adv_separator_title ); ?></span>
							<?php } ?>

							<!-- separator icon -->
							<?php if ( 'icon' === $settings->adv_separator_style && ! empty( $settings->adv_separator_icon ) ) { ?>
								<i class="<?php echo esc_attr( $settings->adv_separator_icon ); ?>"></i>
							<?php } ?>
						</div>
					<?php endif; ?>

					<<?php echo esc_attr( $tag ); ?> <?php echo esc_attr( $attr ); ?>>
						<<?php echo esc_attr( $settings->adv_title_tag ); ?> class="xpro-heading-title">
							<span><?php echo esc_attr( $settings->adv_title_before ); ?></span>
							<span class="xpro-title-focus"><?php echo esc_attr( $settings->adv_title_center ); ?></span>
							<span><?php echo esc_attr( $settings->adv_title_after ); ?></span>
						</<?php echo esc_attr( $settings->adv_title_tag ); ?>>
					</<?php echo esc_attr( $tag ); ?>>

					<?php if ( '1' === $settings->adv_enable_sub_title && 'after-title' === $settings->adv_subtitle_position ) : ?>
						<<?php echo esc_attr( $settings->adv_subtitle_title_tag ); ?> class="xpro-heading-subtitle"><?php echo esc_attr( $settings->adv_subtitle_title ); ?></<?php echo esc_attr( $settings->adv_subtitle_title_tag ); ?>>
					<?php endif; ?>

					<?php if ( 'after-title' === $settings->adv_separator_styles->adv_separator_position && 'enable' === $settings->adv_title_separator_toggle ) : ?>
						<div class="xpro-heading-separator-<?php echo esc_attr( $settings->adv_separator_styles->adv_separator_style ); ?>">
							<?php if ( 'text' === $settings->adv_separator_styles->adv_separator_style && ! empty( $settings->adv_separator_styles->adv_separator_title ) ) { ?>
								<!-- separator text-->
								<span> <?php echo esc_attr( $settings->adv_separator_styles->adv_separator_title ); ?></span>
							<?php } ?>

							<?php if ( 'icon' === $settings->adv_separator_styles->adv_separator_style && ! empty( $settings->adv_separator_styles->adv_separator_icon ) ) { ?>
								<!-- separator icon -->
								<i class="<?php echo esc_attr( $settings->adv_separator_styles->adv_separator_icon ); ?>"></i>
							<?php } ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( '1' === $settings->adv_enable_general_desc ) : ?>
				<div class="xpro-heading-bottom">
					<div class="xpro-heading-description"><?php echo wpautop( $wp_embed->autoembed( $settings->adv_general_description ) ); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
