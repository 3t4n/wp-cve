<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
	/* BEGIN Section: Dashboard */
	CSF::createSection(
		$prefix,
		[
			'title'  => __( 'Dashboard', 'w2w' ),
			'id'     => 'w2w-dashboard',
			'class'  => 'dashboard',
			'icon'   => 'fas fa-tachometer-alt',
			'fields' => [

				[
					'type'    => 'heading',
					/* translators: %s = WP Toolkit  */
					'content' => sprintf( __( 'Welcome to %s!', 'w2w' ), W2W_PLUGIN_NAME ),
				],
				[
					'type'    => 'content',
					/* translators: %s = WP Toolkit  */
					'content' => sprintf( __( 'Thank you for installing %s! We really hope you\'ll like our plugin and greatly benefit from it. On this page, you\'ll find a small introduction to the plugin\'s features, and a few other things. Let\'s begin!', 'w2w' ), W2W_PLUGIN_NAME ),
				],
				[
					'type'    => 'subheading',
					'content' => __( 'Heads up: This plugin is ALWAYS in beta!', 'w2w' ),
				],
				[
					'type'    => 'content',
					/* translators: 1. WP Toolkit 2. link to the shost.vn contact form 3. link to the GitHub page  */
					'content' => sprintf( __( 'We\'re constantly adding new features to %1$s, and improving existing ones. While it\'s safe to use on live websites, there are a lot of moving parts and there\'s a chance that it might cause conflicts. After configuring %1$s, make sure you check your website as a visitor and confirm all\'s well. If you find a bug, you can let us know about it via our contact form on %2$s.', 'w2w' ), W2W_PLUGIN_NAME, '<a href="https://www.shost.vn/lien-he" rel="external noopener" target="_blank">Shost.vn</a>' ),
				],
				[
					'type'    => 'subheading',
					/* translators: %s = WP Toolkit  */
					'content' => sprintf( __( '%s Features and Benefits', 'w2w' ), W2W_PLUGIN_NAME ),
				],
				[
					'type'    => 'content',
					/* translators: %s = WP Toolkit  */
					'content' => '<p>' . __( 'Currently, the technical team is developing many useful features, which will be updated in the next versions. Help users reduce the installation of many plugins, affecting website performance. ', 'w2w' ) . '</p>',
				], // Z_TODO: Fetching clouflare settings ibaresi ekle.						
				[
					'type'    => 'subheading',
					'content' => __( 'That\'s it, enjoy!', 'w2w' ),
				],
				[
					'type'    => 'content',
					'content' => '<p>' . __( 'We really hope that you\'ll enjoy working with our plugin. Always remember that this is a powerful tool, and using powerful tools might hurt you if you\'re not careful. Have fun!', 'w2w' ) . '</p>' .
								 /* translators: %s = Optimocha */
								 '<p style="font-style:italic;">' . sprintf( __( 'Your friends at %s', 'w2w' ), W2W_OWNER_NAME ) . '</p>' .
								 /* translators: 1. WP Toolkit 2. link to the plugin's reviews page on wp.org */
								 '<p>' . sprintf( __( 'Almost forgot: If you like %1$s, it would mean a lot to us if you gave a fair rating on %2$s, because highly rated plugins are shown to more users on the WordPress plugin directory, meaning that we\'ll have no choice but to take better care of %1$s!', 'w2w' ), W2W_PLUGIN_NAME, '<a href="https://wordpress.org/support/plugin/w2w/reviews/#new-post" rel="external noopener" target="_blank">wordpress.org</a>' ) . '</p>',
				],
			],
		]
	);
	/* END Section: Dashboard */