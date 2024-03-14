<div class="app-container">
	<div class="main-container">		
		<div id="gs-behance-shortcode-app">
			<header class="gs-behance-header">
				<div class="gs-containeer-f">
					<div class="gs-roow">
						<div class="logo-area gs-col-xs-6">
							<router-link to="/">
								<img src="<?php echo GSBEH_PLUGIN_URI . '/assets/img/icon-128x128.png'; ?>" alt="GS Behance Logo">
							</router-link>
						</div>
						<div class="menu-area gs-col-xs-6 text-right">
							<ul>
								<router-link to="/" tag="li"><a><?php _e( 'Shortcodes', 'gs-behance' ); ?></a></router-link>
								<router-link to="/shortcode" tag="li"><a><?php _e( 'Create New', 'gs-behance' ); ?></a></router-link>
								<router-link to="/preferences" tag="li"><a><?php _e( 'Preferences', 'gs-behance' ); ?></a></router-link>
								<router-link to="/tools" tag="li"><a><?php _e( 'Tools', 'gs-behance' ); ?></a></router-link>
							</ul>
						</div>
					</div>
				</div>
			</header>

			<div class="gs-behance-app-view-container">
				<router-view :key="$route.fullPath"></router-view>
			</div>

		</div>		
	</div>
</div>