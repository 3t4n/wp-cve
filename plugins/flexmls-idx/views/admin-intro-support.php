<?php

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

global $wp_version;
$options = get_option( 'fmc_settings' );

$active_theme = wp_get_theme();
$active_plugins = get_plugins();
$known_plugin_conflicts = array(
			'screencastcom-video-embedder/screencast.php', // Screencast Video Embedder, JS syntax errors in 0.4.4 breaks all pages
		);

$known_plugin_conflicts_tag = ' &ndash; <span class="flexmls-known-plugin-conflict-tag">Known issues</span>';

?>

<div class="suport-content">
	<h3>FBS Products Support</h3>
	<table>
		<tr>
			<td>Email:</td>
			<td><a href="<?php echo antispambot( 'mailto:idxsupport@flexmls.com' ); ?>"><?php echo antispambot( 'idxsupport@flexmls.com' ); ?></td>
		</tr>
		<tr>
			<td>Online:</td>
			<td><a href="https://fbsdata.zendesk.com/hc/en-us" target="_blank">fbsidx.com/help</a></td>
		</tr>
		<tr>
			<td>Phone:</td>
			<td>888-525-4747 x.171</td>
		</tr>
		<tr>
			<td><strong>Hours of operation:</strong> 8am - 5pm Central Time</td>
		</tr>
	</table>

	<div class="getting-started">
		<h3 class="bg-blue-head">Getting Started with your WordPress Plugin</h3>
		<p>Visit our <a href="https://fbsdata.zendesk.com/hc/en-us/categories/204268307-Flexmls-IDX-WordPress-Plugin" target="_blank">online help center here</a> for step by step instructions.</p>
	</div>

	<div class="installation-info">
		<h3 class="bg-blue-head">Installation Information</h3>
		<div class="content">
			<p><strong>Website URL:</strong> <?php echo home_url(); ?></p>
			<p><strong>WordPress URL:</strong> <?php echo site_url(); ?></p>
			<p><strong>WordPress Version:</strong> <?php echo $wp_version; ?></p>
			<p><strong>Flexmls&reg; IDX Plugin Version:</strong> <?php echo FMC_PLUGIN_VERSION; ?></p>
			<p><strong>Web Server:</strong> <?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></p>
			<p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
			<p><strong>Theme:</strong> <?php
				if( $active_theme->get( 'ThemeURI' ) ){
					printf( "<a href=\"%s\" target=\"_blank\">%s</a> (Version %s)",
						$active_theme->get( 'ThemeURI' ),
						$active_theme->get( 'Name' ),
						$active_theme->get( 'Version' )
					);
				} else {
					printf( "%s (Version %s)",
						$active_theme->get( 'Name' ),
						$active_theme->get( 'Version' )
					);
				}
			?></p>
			<p><strong>Parent Theme:</strong> <?php
				if( is_child_theme() ){
					$parent_theme = $active_theme->get( 'Template' );
					$parent_theme = wp_get_theme( $parent_theme );
					if( $parent_theme->get( 'ThemeURI' ) ){
						printf( "<a href=\"%s\" target=\"_blank\">%s</a> (Version %s)",
							$parent_theme->get( 'ThemeURI' ),
							$parent_theme->get( 'Name' ),
							$parent_theme->get( 'Version' )
						);
					} else {
						printf( "%s (Version %s)",
							$parent_theme->get( 'Name' ),
							$parent_theme->get( 'Version' )
						);
					}
				} else {
					echo 'N/A';
				}
			?></p>
			<p><strong>Active Plugins:</strong></p>
			<ul class="flexmls-list-active-plugins">
				<?php foreach( $active_plugins as $plugin_file => $active_plugin ): ?>
					<?php
						printf(
							'<li><a href="%s" target="_blank">%s</a> (Version %s) by <a href="%s" target="_blank">%s</a>%s</li>',
							$active_plugin[ 'PluginURI' ],
							$active_plugin[ 'Name' ],
							$active_plugin[ 'Version' ],
							$active_plugin[ 'AuthorURI' ],
							$active_plugin[ 'Author' ],
							in_array( $plugin_file, $known_plugin_conflicts ) ? $known_plugin_conflicts_tag : ''
						);
					?>
				<?php endforeach; ?>
			</ul>
			<p><strong>cURL Version:</strong> <?php $curl_version = curl_version(); echo $curl_version[ 'version' ]; ?></p>
			<p><strong>Permalinks:</strong> <?php echo ( get_option( 'permalink_structure' ) ? 'Yes' : 'No' ); ?></p>
		</div>
	</div>

</div>
