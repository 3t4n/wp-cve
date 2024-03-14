<?php

/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 * @package Xpro Addons
 * @sub-package Creative Icon List Module
 * @since 1.1.3
 */

?>

<div class="tnit-icon-list-outer">
	<div class="tnit-icon-list">
		<?php
		$icon_list_count = count( $settings->list_items );
		for ( $i = 0; $i < $icon_list_count; $i++ ) :
			$list_item = $settings->list_items[ $i ];
			?>
			<div class="tnit-icon-list-item tnit-icon-list-item-<?php echo esc_attr( $i ); ?>">
				<div class="tnit-image-icon-wrap">
					<?php $module->render_icon( $i ); ?>
				</div>
				<div class="tnit-icon-list-title-wrap">
					<<?php echo esc_attr( $settings->title_tag ); ?> class="tnit-icon-list-title">
						<?php if ( ! empty( $list_item->link ) ) : ?>
							<a href="<?php echo esc_url( $list_item->link ); ?>" target="<?php echo esc_attr( $list_item->link_target ); ?>"<?php echo ( 'yes' === $list_item->link_nofollow ) ? ' rel="nofollow"' : ''; ?>>
						<?php endif; ?>
						<?php echo esc_attr( $list_item->title ); ?>
						<?php if ( ! empty( $list_item->link ) ) : ?>
							</a>
						<?php endif; ?>
					</<?php echo esc_attr( $settings->title_tag ); ?>>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>
