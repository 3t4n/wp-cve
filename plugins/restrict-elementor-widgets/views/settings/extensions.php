<p class="rew-desc"><?php _e( 'Enhance your Elementor experience with these amazing extensions!', 'restrict-elementor-widgets' ); ?></p>
<div id="rew-extensions-panel">
	
	<?php
	$all_plugins = get_plugins();
	foreach ( rew_get_extensions() as $slug => $extension ) {
		$plugin		= "{$slug}/{$slug}.php";
		$image		= plugins_url( "assets/img/{$slug}.png", REW );

		// extension already activated
		if( is_plugin_active( $plugin ) ) {
			$button_text	= __( 'Activated', 'restrict-elementor-widgets' );
			$button_link	= '#';
			$button			= "<div class='rewe-btn'><a href='{$button_link}' disabled>{$button_text}</a></div>";
		}

		// installed, not activated
		elseif( array_key_exists( $plugin, $all_plugins ) ) {
			$button_text	= __( 'Activate', 'restrict-elementor-widgets' );
			$button_link	= rew_action_link( $plugin, 'activate' );
			$button			= "<div class='rewe-btn'><a href='{$button_link}'>{$button_text}</a></div>";
		}

		// not installed
		else {
			$button_text	= $extension['button'];
			$button_link	= $extension['url'];
			$button			= "<div class='rewe-btn'><a href='{$button_link}' target='_blank'>{$button_text}</a></div>";
		}

		echo "
		<div id='{$slug}' class='rew-extension'>
			<div class='rew-extension-top'>
				<img src='{$image}'>
			</div>
			<div class='rew-extension-bottom'>
				<h3>{$extension['title']}</h3>
				<p>{$extension['desc']}</p>
				{$button}
			</div>
		</div>";
	}
	echo "
	<div id='rewe-more' class='rew-extension'>
		<a href='https://codexpert.io/hire/?utm_campaign=rew-extension'><div class='rew-extension-top'>
			<span class='dashicons dashicons-insert'></span>
		</div>
		<div class='rew-extension-bottom'>
			<div>" . __( 'Request for Integration', 'restrict-elementor-widgets' ) . "</div>
		</div><a>
	</div>";
	?>
</div>