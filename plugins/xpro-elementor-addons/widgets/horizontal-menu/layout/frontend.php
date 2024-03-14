<?php use Elementor\Icons_Manager; ?>
<div class="xpro-elementor-horizontal-navbar-wrapper xpro-elementor-horizontal-menu-responsive-<?php echo esc_attr( $settings['responsive_show'] ); ?> xpro-push-<?php echo esc_attr( $settings['hamburger_entrance_animation'] ); ?>">

	<?php if ( $settings['responsive_show'] !== 'none' ) { ?>
	<button type="button" class="xpro-elementor-horizontal-menu-close">
		<?php Icons_Manager::render_icon( $settings['hamburger_close_icon'], array( 'aria-hidden' => 'true' ) ); ?>
	</button>
	<?php } ?>

	<?php

	$classes = ' xpro-elementor-horizontal-menu-style-' . $settings['menu_style'];

	if ( ! empty( $settings['nav_menu'] ) ) {
		wp_nav_menu(
			array(
				'menu'            => $settings['nav_menu'],
				'container_class' => 'xpro-elementor-horizontal-navbar' . $classes,
				'menu_class'      => 'xpro-elementor-horizontal-navbar-nav',
				'fallback_cb'     => 'wp_page_menu',
				'echo'            => true,
				'walker'          => new Xpro_Elementor_Navwalker(),
			)
		);
	}

	?>
</div>

<div class="xpro-elementor-horizontal-menu-overlay"></div>

<div class="xpro-elementor-horizontal-menu-toggler-wrapper">
	<button type="button" class="xpro-elementor-horizontal-menu-toggler">
		<?php Icons_Manager::render_icon( $settings['hamburger_icon'], array( 'aria-hidden' => 'true' ) ); ?>
	</button>
</div>
