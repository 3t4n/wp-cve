<?php

	$nav_class = 'tnit-advance-menu-container';

if ( 'horizontal' === $settings->menu_layout ) {
	$nav_class .= ' tnit-advance-horizontal-menu';
	$nav_class .= ' tnit-advance-horizontal-menu-style-' . $settings->menu_hover_style;
} elseif ( 'vertical' === $settings->menu_layout ) {
	$nav_class .= ' tnit-advance-vertical-menu';
} else {
	$nav_class .= ' tnit-advance-accordion-menu';
}

if ( 'all' === $settings->responsive_breakpoint ) {
	$nav_class = ' tnit-hamburger-menu-expand';
}


?>

<div class="tnit-advance-menu-wrapper tnit-hamburger-layout-<?php echo esc_attr( $settings->responsive_layout ); ?>">

	<?php if ( 'accordion' !== $settings->menu_layout ) { ?>
	<button class="tnit-advance-menu-toggle-wrapper" type="button">
		<?php if ( ! empty( $settings->hamburger_button_icon ) && 'before' === $settings->hamburger_button_icon_position ) { ?>
		<i class="tnit-advance-menu-toggle-icon-before <?php echo esc_attr( $settings->hamburger_button_icon ); ?>"></i>
		<?php } ?>

		<?php if ( ! empty( $settings->hamburger_button_text ) ) : ?>
		<span class="tnit-advance-menu-toggle-text"><?php echo esc_attr( $settings->hamburger_button_text ); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $settings->hamburger_button_icon ) && 'after' === $settings->hamburger_button_icon_position ) { ?>
			<i class="tnit-advance-menu-toggle-icon-after <?php echo esc_attr( $settings->hamburger_button_icon ); ?>"></i>
		<?php } ?>
	</button>
	<?php } ?>

	<nav class="<?php echo esc_attr( $nav_class ); ?>">

		<?php if ( 'accordion' !== $settings->responsive_layout ) { ?>
			<span class="tnit-advance-menu-close">
				<i class="fas fa-times"></i>
			</span>
		<?php } ?>

		<ul class="tnit-advance-menu-list">

			<?php
			$count_menu_items = count( $settings->menu_items );
			for ( $i = 0; $i < $count_menu_items; $i++ ) {

				$menu_item = $settings->menu_items[ $i ];

				/**
				 * Menu item classes
				 */

				$menu_item_classes  = 'tnit-advance-menu-item';
				$menu_item_classes .= ( $menu_item->add_submenu ) ? ' tnit-menu-has-child' : '';
				$menu_item_classes .= ( '' !== $menu_item->menu_classes ) ? ' ' . $menu_item->menu_classes : '';

				if ( 'page' === $menu_item->menu_item_type && is_page( $menu_item->menu_item_page ) ) {
					$menu_item_classes .= ' tnit-current-item';
				} elseif ( 'category' === $menu_item->menu_item_type && is_category( $menu_item->menu_item_category ) ) {
					$menu_item_classes .= ' tnit-current-item';
				} elseif ( 'post' === $menu_item->menu_item_type && is_single( $menu_item->menu_item_post ) ) {
					$menu_item_classes .= ' tnit-current-item';
				} elseif ( 'custom-link' === $menu_item->menu_item_type ) {
					$current_url = get_page_link();
					$menu_url    = $menu_item->menu_item_custom_link;

					if ( $current_url === $menu_url ) {
						$menu_item_classes .= ' tnit-current-item';
					} elseif ( $current_url === $menu_url . '/' ) {
						$menu_item_classes .= ' tnit-current-item';
					}
				}


				/**
				 * Submenu classes
				 */

				$submenu_classes = 'tnit-advance-sub-menu';

				if ( 'accordion' !== $settings->menu_layout ) {
					$submenu_classes .= ' tnit-advance-sub-menu-hover-style-' . $settings->submenu_hover_style;
					$submenu_classes .= ' tnit-advance-sub-menu-style-' . $settings->submenu_dropdown_effect;
					$submenu_classes .= ' tnit-advance-sub-menu-bg-' . $settings->submenu_outer_bg_type;
				}

				?>

				<li class="<?php echo esc_attr( $menu_item_classes ); ?>">
					<?php echo $module->render_menu_item_link( $i ); ?>

					<?php if ( $menu_item->add_submenu ) { ?>
					<ul class="<?php echo esc_attr( $submenu_classes ); ?>">

							<?php
							$submenu_item_count = count( $menu_item->submenu_items );
							for ( $j = 0; $j < $submenu_item_count; $j++ ) {

								$submenu_item_json = $menu_item->submenu_items[ $j ];
								$submenu_item      = json_decode( $submenu_item_json );

								/**
								 * Get submenu item classes
								 */
								$submenu_item_classes  = "tnit-submenu-item tnit-submenu-item-$j tnit-submenu-item-type-$submenu_item->submenu_item_type";
								$submenu_item_classes .= ( '' !== $submenu_item->submenu_classes ) ? ' ' . $submenu_item->submenu_classes : '';

								if ( 'page' === $submenu_item->submenu_item_type && is_page( $submenu_item->submenu_item_page ) ) {
									$submenu_item_classes .= ' tnit-current-item';
								} elseif ( 'category' === $submenu_item->submenu_item_type && is_category( $submenu_item->submenu_item_category ) ) {
									$submenu_item_classes .= ' tnit-current-item';
								} elseif ( 'post' === $submenu_item->submenu_item_type && is_single( $submenu_item->submenu_item_post ) ) {
									$submenu_item_classes .= ' tnit-current-item';
								} elseif ( 'custom-link' === $submenu_item->submenu_item_type ) {
									$current_url = get_page_link();
									$submenu_url = $submenu_item->submenu_item_custom_link;

									if ( $current_url === $submenu_url ) {
										$submenu_item_classes .= ' tnit-current-item';
									} elseif ( $current_url === $submenu_url . '/' ) {
										$submenu_item_classes .= ' tnit-current-item';
									}
								}

								?>

								<li class="<?php echo esc_attr( $submenu_item_classes ); ?>">
									<?php echo $module->render_submenu_item_link( $i, $j ); ?>
								</li>

							<?php } ?>

						</ul>
					<?php } ?>
				</li>

			<?php } ?>

		</ul>
	</nav>

</div>
