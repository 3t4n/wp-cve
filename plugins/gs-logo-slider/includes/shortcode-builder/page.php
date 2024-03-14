<?php

$routes = [
	[
		'slug'  => '/',
		'title' => __('Shortcodes', 'gslogo')
	],
	[
		'slug'  => '/shortcode',
		'title' => __( 'Create New', 'gslogo' )
	],
	[
		'slug'  => '/preferences',
		'title' => __( 'Preferences', 'gslogo' )
	],
	[
		'slug'  => '/demo-data',
		'title' => __( 'Demo Data', 'gslogo' )
	]
];

?>
<div class="app-container">
	<div class="main-container">
		<div id="gs-logo-slider-shortcode-app">
			<header class="gs-logo-slider-header">
				<div class="gs-containeer-f">
					<div class="gs-roow">
						<div class="logo-area col-xs-6">
							<router-link to="/"><img src="<?php echo GSL_PLUGIN_URI . 'assets/img/logo.svg'; ?>" alt="GS Logo Slider Logo"></router-link>
						</div>
						<div class="menu-area col-xs-6 text-right">
							<ul>
								<?php
								foreach($routes as $route) { ?>

									<router-link to=<?php echo esc_attr($route['slug']); ?> custom v-slot="{ isActive, href, navigate, isExactActive }">
										<li :class="[isActive ? 'router-link-active' : '', isExactActive ? 'router-link-exact-active' : '']">
											<a :href="href" @click="navigate" @keypress.enter="navigate" role="link"><?php echo esc_html($route['title']); ?></a>
										</li>
									</router-link>
									
								<?php
								}
								?>								
							</ul>
						</div>
					</div>
				</div>
			</header>

			<div class="gs-logo-slider-app-view-container">
				<router-view :key="$route.fullPath"></router-view>
			</div>

		</div>

	</div>
</div>