<?php
/**
 * It fires in case of emergency mode if defined( 'FDP_EMERGENCY_LOG_ADMIN',true ) in wp-config.php.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

static $called = false;
if ( ! $called ) {
	$log       = '<html><head><title>FDP Emergency mode | Debugging</title>';
	$log      .= "<style>::selection{background:#253042;color:#fff}body{color:#253042}.button:hover{background:#fff;color:#253042}.button{border:1px solid #253042;display:inline-block;transition:background 0.3s linear;background:#253042;color:#fff;text-decoration: none;padding:10px}body{font-family:-apple-system,system-ui,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,Cantarell,'Fira Sans','Droid Sans','Helvetica Neue',sans-serif}code{font-family:Menlo,Consolas,Monaco,Liberation Mono,Lucida Console,monospace}</style>";
	$log      .= '<meta name=”robots” content=”noarchive,noindex,nofollow”>';
	$log      .= '</head><body style="margin:0;padding:20px"><section style="max-width:1200px;margin:0 auto;padding-bottom:32px">';
	$logo_path = WP_PLUGIN_DIR . '/freesoul-deactivate-plugins/admin/assets/img/fdp-logo-128x128.png';
	if ( file_exists( $logo_path ) ) {
		$type = pathinfo( $logo_path, PATHINFO_EXTENSION ); // @codingStandardsIgnoreLine.
		$data = file_get_contents( $logo_path );
		$log .= '<div style="text-align:center"><a alt="FDP logo" href="https://freesoul-deactivate-plugins.com" target="_bank" rel="noopener"><img class="fdp-logo" src="data:image/' . $type . ';base64,' . base64_encode( $data ) . '" style="width:80px;height:80px" /></a></div>';
	} else {
		$log .= '<h2 style="text-align:center;font-size:18px">FREESOUL DEACTIVATE PLUGINS</h2>';
	}
	$log .= '<h1 style="text-align:center;font-size:22px">TROUBLESHOT IN EMERGENCY MODE.</h1>';
	$log .= '<br /><br />Here you will find some tips to help you to find out why your backend doesn\'t load.';
	$log .= '<br /><br />We suggest you follow the following steps. In most of the cases it will be enough for finding the root cause.';
	if ( file_exists( ABSPATH . '.maintenance' ) ) {
		$log .= '<br /><br /><br /><br />First of all, delete the file .maintenance to see your backend. If you still have issue after deleting that file, we will give you more tips.';
	} else {
		$n    = 0;
		$r    = random_int( 0, count( $plugins ) - 1 );
		$log .= '<br /><br /><br /><br />1) First of all, add these lines to the file <b>wp-config.php</b> before the comment "<b>/* That\'s all, stop editing! Happy blogging. */</b>"';
		$log .= '<br /><pre style="margin-top:32px;background-color:#F0F0F1;padding:20px;width: max-content">define( "WP_DEBUG",true );';
		$log .= PHP_EOL . 'define( "WP_DEBUG_LOG",true );';
		$log .= PHP_EOL . 'define( "WP_DEBUG_DISPLAY",false );';
		$log .= '</pre>';
		$log .= '<br /><br />2) Reload the backend page where you have issues';
		$log .= '<br /><br />3) Open the file <b>wp-content/debug.log</b>, and check if it contains useful information about the issue.';
		$log .= '<br /><br />4) If you haven\'t found anything useful, let\' try loading the FDP page to disable plugins and theme in the backend.';
		$log .= '<br /><br /><div style="text-align:center;margin:32px 0"><a class="button" href="' . esc_url( admin_url( 'admin.php?page=eos_dp_admin&emergency=true' ) ) . '" target="_blank" rel="noopener">OPEN FDP SETTINGS PAGE</a></div>';
		$log .= '<br /><br />5) If the FDP page loads without problems, uncheck a plugin after the other and then the theme. Open the action icons by clicking on the plus symbol, and click always on the lens icon to preview the page unloading the disabled plugin or theme.';
		$log .= '<br /><br />6) If the FDP page doesn\'t load, add the following code to <b>wp-config.php</b> before the comment "<b>/* That\'s all, stop editing! Happy blogging. */</b>":';
		$log .= '<pre style="margin-top:32px;background-color:#F0F0F1;padding:20px;width: max-content">define( "FDP_EMERGENCY_ADMIN_PLUGINS",array(';
		foreach ( $plugins as $plugin ) { // @codingStandardsIgnoreLine.
			$log    .= PHP_EOL . chr( 9 ) . ( $r === $n ? '//' : '' ) . '"' . esc_html( $plugin ) . '",';
			++$n;
		}
		$log        = PHP_EOL . rtrim( $log, ',' ) . PHP_EOL . ') );</pre>';
		$log       .= '<br />7) Deactivate a plugin after the other until you find that one that was giving the issue.';
		$log       .= '<br /><br /><br />Add // to deactivate a specific plugin (in the example above we deactivate the plugin ' . esc_html( $plugins[ $r ] ) . ').';
		$log       .= '<br /><br />' . PHP_EOL;
		$log       .= '<br /><br />8) If after disabling all the plugins you still have issues, try disabling the theme by adding this line of code to <b>wp-config.php</b> before the comment "<b>/* That\'s all, stop editing! Happy blogging. */</b>":';
		$log       .= '<br /><br /><br /><pre style="background-color:#F0F0F1;padding:20px;width: max-content">define( "FDP_EMERGENCY_ADMIN_THEME_OFF",true );</pre>';
		$log       .= '<br /><br /><br />If the issue is solved after disabling the theme, then it means the theme was the cause of the issue. In this case rename the active theme folder, and remove the line of code written above from wp-config.php. By doing so you will be able to switch to another theme.';
		$mu_ps = wp_get_mu_plugins();
		unset( $mu_ps[ array_search( untrailingslashit( plugin_basename( __FILE__ ) ), $mu_ps, true ) ] );
		$files_list = '<ul>';
		if ( ! empty( $mu_ps ) ) {
			$files_list .= '<ul style="margin-top:32px">';
			foreach ( $mu_ps as $mu_plugin ) {
				$files_list .= '<li><b>' . str_replace( ABSPATH, '', $mu_plugin ) . '</b></li>';
			}
		}
		$dropins = array(
			'advanced-cache.php',
			'db.php',
			'db-error.php',
			'install.php',
			'maintenance.php',
			'object-cache.php',
			'php-error.php',
			'fatal-error-handler.php',
		);
		foreach ( $dropins as $dropin ) {
			$file = WP_CONTENT_DIR . '/' . $dropin;
			if ( file_exists( $file ) ) {
				$files_list .= '<li><b>' . str_replace( ABSPATH, '', $file ) . '</b></li>';
			}
		}
		$files_list .= '</ul>';

		if ( '<ul></ul>' !== $files_list ) {
			$log .= '<br /><br /><br />9) If neither the theme nor the plugins were the cause of the issue, make a backup and then delete the following files:' . PHP_EOL . $files_list;
			$log .= '<br /><br />Be careful, before deleting any file, save that file to a save place, to be ready to restore them after the troubleshot.';
		}

		$wp_download  = '<br /><br /><div style="text-align:center;margin:32px 0"><a class="button" href="https://wordpress.org/wordpress-' . esc_attr( get_bloginfo( 'version' ) ) . '.zip" download>DOWNLOAD YOUR CURRENT WordPress VERSION (' . esc_attr( get_bloginfo( 'version' ) ) . ')</a></div>';
		$wp_download .= '<br /><br /><div style="text-align:center;margin:32px 0"><a class="button" href="https://wordpress.org/latest.zip" download="wordpress-latest.zip"">DOWNLOAD LAST WordPress VERSION</a></div>';

		if ( file_exists( ABSPATH . '/.htaccess' ) ) {
			$log     .= '<br /><br />10) If after all these tests you haven\'t found the cause of the issue, check the file <b>.htaccess</b>';
			$home_url = str_replace( 'www.', '', esc_url( get_home_url() ) );
			if ( substr_count( $home_url, '.' ) < 2 ) {
				$log .= '<br /><br />A basic .htaccess file will be like this:<br /><br /><pre style="background-color:#F0F0F1;padding:20px;width: max-content"># BEGIN WordPress';
				$log .= PHP_EOL . 'RewriteEngine On';
				$log .= PHP_EOL . 'RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]';
				$log .= PHP_EOL . 'RewriteBase /';
				$log .= PHP_EOL . 'RewriteRule ^index\.php$ - [L]';
				$log .= PHP_EOL . 'RewriteCond %{REQUEST_FILENAME} !-f';
				$log .= PHP_EOL . 'RewriteCond %{REQUEST_FILENAME} !-d';
				$log .= PHP_EOL . 'RewriteRule . /index.php [L]';
				$log .= PHP_EOL . '# END WordPress</pre>';
				$log .= '<br /><br />If you have doubts about your .htaccess file, save it to a safe place, and then replace it with the basic one shown above';
			}
			$log .= '<br /><br />11) If tha file <b>.htaccess</b> has nothing strange, update the core of WordPress via FTP';
		} else {
			$log .= '<br /><br />' . esc_html( $n ) . '10) If after all these tests you haven\'t found the cause of the issue, update the core of WordPress via FTP.';
		}
		$log            .= $wp_download;
		$log            .= '<br /><br /><br /><br />We hope it was helpful to find the cause of the problem.';
		$log            .= '<br /><br /><br /><br />After the troubleshot you should be able to say which one of the following possible causes was the root cause:';
		$possible_causes = array(
			'A plugin',
			'The theme',
			'WordPress Core',
			'An mu-plugin (a file included in wp-content/mu-plugiins)',
			'A dropin plugin (a file included in wp-content)',
		);
		if ( file_exists( ABSPATH . '/.htaccess' ) ) {
			$possible_causes[] = 'The file .htaccess';
		}
		$log .= '<ol style="margin-top:32px">';
		foreach ( $possible_causes as $cause ) {
			$log .= '<li>' . esc_html( $cause ) . '</li>';
		}
		$log .= '</ol>';
		$log .= '<br /><br />In the cases 1), 2), try update the plugin or theme via FTP. If it still doesn˙t work, maybe ask the author of the plugin or theme.';
		$log .= '<br /><br />In the cases 4), 5), 6), you should try to understand which plugin created or changed the content of that file, and then ask the author of that plugin.';
		$log .= '<br /><br /><br /><br />If you still have no clue, try opening a thread on the WordPress support forum.';
		$log .= '<br /><br /><div style="text-align:center;margin:32px 0"><a class="button" href="https://wordpress.org/support/forum/how-to-and-troubleshooting/" target="_blank" rel="noopener">OPEN A THREAD ON THE WordPress SUPPORT FORUM</a></div>';
	}
	$log .= '</section></body></html>';
	echo $log; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied while building $log.
	$called = true;
	exit;
}
