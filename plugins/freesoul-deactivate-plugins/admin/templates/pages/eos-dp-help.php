<?php
/**
 * Template Help.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivate by archive settings page.
function eos_dp_help_callback() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		?>
		<h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
		<?php
		return;
	}
	eos_dp_alert_plain_permalink();
	eos_dp_navigation();
	$dir = is_rtl() ? 'left' : 'right';
	$tab = isset( $_GET['tab'] ) ? str_replace( '-', '_', sanitize_text_field( $_GET['tab'] ) ) : 'home';
	if ( function_exists( 'eos_dp_help_' . sanitize_key( $tab ) . '_section' ) ) {
		call_user_func( 'eos_dp_help_' . sanitize_key( $tab ) . '_section' );
	}
}

function eos_dp_help_home_section() {
	if ( defined( 'FDP_PRO_ACTIVE' ) ) {
		$support_url  = 'https://freesoul-deactivate-plugins.com/plugins/log-a-support-ticket/';
		$support_text = esc_html__( 'Premium Support', 'freesoul-deactivate-plugins' );
	} else {
		$support_url  = 'https://wordpress.org/support/plugin/freesoul-deactivate-plugins/';
		$support_text = esc_html__( 'Support Forum', 'freesoul-deactivate-plugins' );
	}
	?>
	<style id="fdp-help-css">
		#fdp-help-section .fdp-help-button p{
			height:100px;
			background:#253042;
			transition:1s linear;
			color:#fff;
			text-transform: uppercase;
		line-height: 100px;
		text-align: center;
			font-size: 30px;
			letter-spacing: 2px;
		}

		#fdp-help-section .fdp-help-button p a{
			transition: 0.3s linear;
			color:#fff;
			text-decoration:none;
			outline:none !important;
			box-shadow:none !important
		}
		#fdp-help-section .fdp-help-button:hover p{
			cursor: pointer;
		background: #fff;
		}
		#fdp-help-section .fdp-help-button:hover p a{
		color: #253042;
		}
	</style>
	<section id="fdp-help-section" class="eos-dp-section" style="min-width:0 !important">
		<table id="eos-dp-help-home" style="width:800px;margin: 64px auto 0 auto;max-width:100%">
			<tr>
				<td class="fdp-help-button"><p><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_menu&reopen_pointer=true' ) ); ?>"><?php esc_html_e( 'Guided tour', 'freesoul-deactivate-plugins' ); ?></a></p></td>
				<td class="fdp-help-button"><p><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_help&tab=common_issues' ) ); ?>"><?php esc_html_e( 'Common issues', 'freesoul-deactivate-plugins' ); ?></a></p></td>
			</tr>
				<td class="fdp-help-button"><p><a href="https://freesoul-deactivate-plugins.com/how-deactivate-plugiins-on-specific-pages/" target="_blank" rel="noopener"><?php esc_html_e( 'Documentation', 'freesoul-deactivate-plugins' ); ?></p></td>
				<td class="fdp-help-button"><p><a href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $support_text ); ?></a></p></td>
			</tr>
		</table>
	</section>
	<?php
}

// SOS section.
function eos_dp_help_common_issues_section() {
	$post_types      = '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=eos_dp_by_post_type' ) ) . '">Post Types Settings</a>';
	$singles         = '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=eos_dp_menu' ) ) . '">Singles Settings</a>';
	$custom_urls     = '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=eos_dp_url' ) ) . '">Custom URLs Settings</a>';
	$support_forum   = '<a target="_blank" rel="noopener" href="https://wordpress.org/support/plugin/freesoul-deactivate-plugins/">Support Forum</a>';
	$repository_tags = 'https://plugins.svn.wordpress.org/freesoul-deactivate-plugins/tags/';
	$response        = wp_remote_get( $repository_tags );
	$download_url    = 'https://downloads.wordpress.org/plugin/freesoul-deactivate-plugins.';
	$versions_list   = false;
	if ( ! is_wp_error( $response ) ) {
		$body = wp_remote_retrieve_body( $response );
		if ( class_exists( 'DOMDocument' ) ) {
			libxml_use_internal_errors( true );
			$dom = new DOMDocument();
			$dom->loadHTML( $body );
			$ul = $dom->getElementsByTagName( 'ul' );
			if ( $ul ) {
				$ul    = $ul->item( 0 );
				$links = $ul->getElementsByTagName( 'a' );
				if ( $links ) {
					$versions_list   = array();
					$versions_list[] = '</select>';
					foreach ( $links as $link ) {
						$version = str_replace( '/', '', $link->nodeValue );
						if ( version_compare( $version, EOS_DP_VERSION ) < 0 && version_compare( $version, '1.8.0' ) > 0 && substr_count( $version, '.' ) < 4 ) {
							if ( EOS_DP_VERSION !== $version && absint( $version ) > 0 && false === strpos( $version, 'beta' ) && false === strpos( $version, 'RC' ) ) {
								$versions_list[] = '<option><a href="' . esc_url( $download_url . esc_attr( $version ) . '.zip' ) . '">' . esc_html( $version ) . '</a></option>';
							}
						}
					}
					$versions_list[] = '<select id="eos-dp-previuos-versions" style="margin-top:-4px">';
					$versions_list   = implode( '', array_reverse( $versions_list ) );
				}
			}
		}
	}
	$versions_download = $versions_list . ' <a href="#" id="eos-dp-download-previous" class="button" download onclick="this.href=\'' . esc_url( $download_url ) . '\' + document.getElementById(\'eos-dp-previuos-versions\').value + \'.zip\';">' . esc_html__( 'Download', 'freesoul-deactivate-plugins' ) . '</a>';
	?>
	<section class="eos-dp-section">
	<style>
	#eos-dp-help-table td{padding:0 10px}
	table#eos-dp-help-table tr{position:relative}
	table#eos-dp-help-table tr:after {
		position: absolute;
		bottom: -2px;
		width: 100%;
		height: 1px;
		background-color:#253042;
		content: " ";
		left: 0;
		right: 0;
	}
	</style>
		<h2><?php esc_html_e( 'Common issues', 'freesoul-deactivate-plugins' ); ?></h2>
		<table id="eos-dp-help-table" class="wp-list-table striped table-view-list" style="max-width:1200px;max-width:90vw;max-width: calc(100vw - 180px)">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Issue', 'freesoul-deactivate-plugins' ); ?></th>
					<th><?php esc_html_e( 'Tip', 'freesoul-deactivate-plugins' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>On a certain page I have a fatal error.</td>
					<td>
						<p>This usually happens when a plugin calls a function that is defined in another plugin without first checking if that function exists, and you have disabled that plugin on that page.</p>
						<p>Probably, you can solve this issue adding this line of code in wp-config.php before the comment /* That’s all, stop editing! Happy blogging. */</p>
						<pre><?php echo defined( 'FDP_REMOVE_FILTERS_BEFORE_FIRST_PLUGIN' ) && FDP_REMOVE_FILTERS_BEFORE_FIRST_PLUGIN ? "define( 'FDP_REMOVE_FILTERS_BEFORE_FIRST_PLUGIN',false );" : "define( 'FDP_REMOVE_FILTERS_BEFORE_FIRST_PLUGIN',true );"; ?></pre>
						<p>In any case, please, open a thread on the <?php echo wp_kses_post( $support_forum ); ?>. We would like to know which disabled plugin was causing this issue to inform the authors of that plugin.</p>
					</td>
				</tr>
				<tr>
					<td>On a certain page it doesn't disable the right plugins.</td>
					<td>
						<p>Be sure that the <?php echo wp_kses_post( $post_types ); ?> are not overriding the <?php echo wp_kses_post( $singles ); ?>. The Post Types settings will override the inactive rows of the Singles settings.</p>
						<p>On every single page you have also a section to disable the plugins. Be sure you, or someone else don't save the page with the wrong options.</p>
						<p>Be sure that the <?php echo wp_kses_post( $custom_urls ); ?> are not overriding the other options.</p>
						<p>Remember that in the Singles and Archives settings, you can check which plugins are really disabled clicking on the help icon <span class="dashicons dashicons-editor-help" style="display:inline-block;margin-top:-5px;color:#D3C4B8;font-size:30px"></span>.</p>
					</td>
				</tr>
				<tr>
					<td>On mobile it doesn't disable the right plugins.</td>
					<td>
						<p>Be sure that you have a system for full page cache that distinguishes between mobile and desktop devices.</p>
					</td>
				</tr>
				<tr>
					<td>I don't see all the post types.</td>
					<td>
						<p>Try deactivating and reactivating Freesoul Deactivate Plugins (without delete it).</p>
					</td>
				</tr>
				<tr>
					<td>External service not working when FDP is active.</td>
					<td>
						<p>This usually happens when the external service instead of calling a standard endpoint, calls the homepage without any Post request, but just. adding some query arguments to the URL, and you disabled the integration plugin on the homepage.</p>
						<p>Read <a href="https://freesoul-deactivate-plugins.com/integration-external-service-not-working/" target="_FDP_external_service_not_working">this post</a> for more details.</p>
					</td>
				</tr>
				<tr>
					<td>I don't see a certain page in the <?php echo wp_kses_post( $singles ); ?>.</td>
					<td>
						<p>Probably the URL of that page is created by a plugin. In this case, use the <?php echo wp_kses_post( $custom_urls ); ?>.</p>
					</td>
				</tr>
				<tr>
					<td>The new version doesn't work on my site.</td>
					<td>
						<p>If it's urgent, go back to the previus version.</p>
						<?php if ( $versions_list ) { ?>
						<p>Download a version that was working <?php echo $versions_download; //phpcs:ignore WordPress.Security.EscapeOutput -- Escaping already applied while building the value of $versions_download. ?></p>
						<?php } else { ?>
						<p>You will find <a rel="noopener" target="_blank" href="https://wordpress.org/plugins/freesoul-deactivate-plugins/advanced/#plugin-download-history-stats">here</a> the previous versions of Freesoul Deactivate Plugins.</p>
						<?php } ?>
						<?php if ( version_compare( get_bloginfo( 'version' ), '5.7.0' ) > 0 ) { ?>
						<p>To upload the previous version, click on <a class="button" href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=upload' ) ); ?>" target="_blank">Upload Zip</a> and chose the downloaded zip. Then confirm that you want to replace the current version with the zip.</p>
						<?php } else { ?>
						<p>Extract the zip and update the plugin via FTP. After the transfer you have to <strong>deactivate and reactivate again the plugin. Be careful, only deacctivate not delete. If you delete it, you will lose all the settings.</strong></p>
						<?php } ?>
						<p>In any case, please open a thread on the <?php echo wp_kses_post( $support_forum ); ?>, so we can be aware of the issue and do our investigations to solve it.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<div style="margin-top:64px">
			<input style="min-width:25rem" type="text" id="eos-dp-search-on-support-input" placeholder="<?php esc_html_e( 'Search on the support forum', 'freesoul-deactivate-plugins' ); ?>" />
			<a class="button"style="margin:0 8px" id="eos-dp-search-on-support-button" href="https://wordpress.org/search/freesoul+deactivate+plugins" target="_blank" rel="noopener"><?php esc_html_e( 'Search', 'freesoul-deactivate-plugins' ); ?></a>
		</div>
	</section>
	<?php
}
// Flowchart section.
function eos_dp_help_shortcuts_section() {
	$active_plugins = eos_dp_active_plugins();
	$n              = $active_plugins ? count( $active_plugins ) : 0;
	$shortcuts      = array(
		array(
			'key'    => 'O',
			'action' => esc_html__( 'Open row bar', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'C',
			'action' => esc_html__( 'Copy row settings', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'V',
			'action' => esc_html__( 'Paste row settings', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'I',
			'action' => esc_html__( 'Invert selection', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'P',
			'action' => esc_html__( 'Preview page', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'G',
			'action' => esc_html__( 'Enable/disable entire row', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'arrow right',
			'action' => esc_html__( 'Slide plugins to the right', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'arrow left',
			'action' => esc_html__( 'Slide plugins to the left', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'arrow up',
			'action' => esc_html__( 'Scroll to top', 'freesoul-deactivate-plugins' ),
		),
		array(
			'key'    => 'A',
			'action' => esc_html__( 'Show all plugins', 'freesoul-deactivate-plugins' ),
		),
	);
	if ( $active_plugins ) {
		$active_plugins = count( $active_plugins );
		if ( $active_plugins > 20 ) {
			$filters_key = '';
			$g           = $active_plugins > 30 ? 20 : 10;
			$k           = ceil( $active_plugins / $g );
			$filters_key = '';
			for ( $n = 0;$n < $k && $n < 9;++$n ) {
				$filters_key .= ( $n + 1 ) . ', ';
			}
			$filters_key = rtrim( $filters_key, ', ' );
			$shortcuts[] = array(
				'key'    => $filters_key,
				'action' => esc_html__( 'Filter related group of plugins', 'freesoul-deactivate-plugins' ),
			);
		}
	}
	?>
	<section id="eos-dp-shortcuts" class="eos-dp-section">
		<style>#fdp-shortcuts{max-width:calc(100vw - 60px)}@media screen and (min-width:468px){#fdp-shortcuts{max-width:500px}}</style>
		<h2><?php esc_html_e( 'Shortcuts.', 'freesoul-deactivate-plugins' ); ?></h2>
		<table id="fdp-shortcuts" class="wp-list-table widefat fixed striped">
		<tbody>
			<tr>
				<th><?php esc_html_e( 'Shortcut', 'freesoul-deactivate-plugins' ); ?></th>
				<th><?php esc_html_e( 'Action', 'freesoul-deactivate-plugins' ); ?></th>
			</tr>
		<?php foreach ( $shortcuts as $arr ) { ?>
			<tr>
				<td><?php echo esc_html( $arr['key'] ); ?></td>
				<td><?php echo esc_html( $arr['action'] ); ?></td>
			</tr>
		<?php } ?>
		</tbody>
		</table>
	</section>
	<?php
}
