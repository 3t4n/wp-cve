<?php
/**
 * Template Main Navigation.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// It displays the admin navigation.
function eos_dp_navigation() {
	$plugins_table = eos_dp_plugins_table();
	$post_types    = array_keys( $plugins_table );
	$show_on_front = eos_dp_get_option( 'show_on_front' );
	$upload_dir    = wp_upload_dir();
	$fdp_main_nav  = '';
	if ( 'page' === $show_on_front ) {
		$page = 'eos_dp_menu';
		$sec  = 'by-posts';
	} elseif ( 'posts' === $show_on_front ) {
		$page = 'eos_dp_by_archive';
		$sec  = 'archives';
	}
	$slug_names = eos_dp_get_option( 'fdp_plugin_slug_names' );
	$plugins_integration = eos_dp_plugins_integration();
	$pro                 = defined( 'EOS_DP_PRO_VERSION' ) ? ', PRO v' . EOS_DP_PRO_VERSION : '';
	do_action( 'fdp_before_main_nav_menu_items' );
	$menu_items = eos_dp_menu_items();
	if ( 'page' !== eos_dp_get_option( 'show_on_front' ) ) {
		$menu_items['archives']['subitems'] = array( $page );
	}
	$user_meta = get_user_meta( get_current_user_id(), 'fdp_admin_notices', true );
	?>
	<noscript>
		<div class="eos-dp-notice notice notice-error">
			<p><?php esc_html_e( 'Without JavaScript FDP cannot work. Please, activate JavaScript in your browser if you want to use FDP!', 'freesoul-deactivate-plugins' ); ?></p>
		</div>
	</noscript>
	<nav id="eos-dp-setts-nav-wrp" class="<?php echo defined( 'EOS_DP_PRO_VERSION' ) ? 'fdp-pro-nav-wrp' : 'fdp-free-nav-wrp'; ?>" style="width:100vw">
		<?php require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-before-main-nav.php'; ?>
		<ul id="eos-dp-setts-nav" style="display:inline-block">
		<?php
		$arrFiles = glob( $upload_dir['basedir'] . '/FDP/cache/fdp-nav-*.json' );
		if ( $arrFiles && is_array( $arrFiles ) && ! empty( $arrFiles ) ) {
			foreach ( $arrFiles as $file ) {
				if ( file_exists( $file ) ) {
					$json         = file_get_contents( $file );
					$cacheArr     = json_decode( $json, true );
					$fdp_main_nav = $cacheArr['nav'];
					break;
				}
			}
		}
		if ( '' === $fdp_main_nav || eos_dp_is_migrated() || ( defined( 'FDP_NAV_CACHE' ) && false === FDP_NAV_CACHE ) ) {
			// Build the FDP navigation and then cache it if the cache was empty
			ob_start();
			foreach ( $menu_items as $slug => $arr ) {
				?>
				<li id="fdp-menu-<?php echo esc_attr( $slug ); ?>" data-section="eos-dp-<?php echo esc_attr( $arr['section'] ); ?>" class="hover
											<?php
											echo isset( $arr['active_if'] ) ? ' eos-dp-has-children' : '';
											echo '' === $pro && isset( $arr['pro_docu'] ) ? ' fdp-pro-feature' : '';
											?>
				 eos-dp-setts-menu-item">
					<a href="<?php echo '' === $pro && isset( $arr['pro_docu'] ) ? esc_url( $arr['pro_docu'] ) : esc_url( $arr['href'] ); ?>" data-suburls="<?php echo isset( $arr['active_if'] ) ? esc_attr( implode( ',', $arr['active_if'] ) ) : ''; ?>">
						<?php echo esc_html( $arr['title'] ); ?>
					</a>
					<?php
					if ( isset( $arr['subitems'] ) ) {
						?>
						<span class="dashicons dashicons-arrow-down"></span>
						<ul class="eos-dp-sub-menu">
						<?php
						if ( isset( $arr['file'] ) ) {
							$file = esc_attr( apply_filters( 'fdp_menu_item_file_' . sanitize_key( $slug ), $arr['file'] ) );
							if ( file_exists( $file ) ) {
								require_once $file;
							}
						}
						do_action( 'fdp_submenu_item_' . sanitize_key( $slug ) );
						?>
					</ul>
					<?php } ?>
				</li>
				<?php
			}
			$fdp_main_nav = ob_get_clean();
			eos_dp_update_fdp_cache( 'nav', wp_kses_post( $fdp_main_nav ) );
		}
			echo wp_kses_post( $fdp_main_nav );
			do_action( 'eos_dp_tabs' );
		?>
			<li></li>
		</ul>
		<div id="fdp-topbar-icons" style="float:<?php echo is_rtl() ? 'left' : 'right'; ?>;display:inline-block;z-index:9999999;position:relative">
			<span id="fdp-notifications-icon" class="dashicons dashicons-bell" style="position:relative;color:inherit;top:2px" title="<?php esc_html_e( 'Notifications' ); ?>"><span id="fdp-notifications-count"></span>
				<div id="fdp-notifications">
						<div id="fdp-notifications-list" style="position:absolute;width:300px;width:max-content;padding:0 10px;background:#fff;<?php echo is_rtl() ? 'left' : 'right'; ?>:-100%">
						<?php
						do_action( 'fdp_top_bar_notifications' );
						if ( ! $user_meta || ! isset( $user_meta['rewrite_rules'] ) || 'dismissed' !== $user_meta['rewrite_rules'] ) {
							$rewrite_notice = get_site_transient( 'fdp_admin_notice_rewrite_rules' );
							if ( $rewrite_notice ) {
								eos_dp_display_admin_notice( 'rewrite_rules', __( 'Issue with the rewrite rules.', 'freesoul-deactivate-plugins' ), wp_kses_post( wpautop( $rewrite_notice ) ), 'warning', __( 'If you dismiss this notice and it still appears over again and again, it means the rewrite rules are being flushed again and again. This is not good for performance.', 'freesoul-deactivate-plugins' ) );
							}
						}
						if ( isset( $GLOBALS['fdp_all_plugins'] ) && is_array( $GLOBALS['fdp_all_plugins'] ) ) {
							foreach ( $GLOBALS['fdp_all_plugins'] as $active_plugin ) {
								if ( ! $user_meta || ! isset( $user_meta[ 'conflicts_' . sanitize_key( dirname( $active_plugin ) ) ] ) || 'dismissed' !== $user_meta[ 'conflicts_' . sanitize_key( dirname( $active_plugin ) ) ] ) {
									if ( file_exists( EOS_DP_PLUGIN_DIR . '/inc/plugin-conflicts/' . dirname( $active_plugin ) . '.php' ) ) {
										require_once EOS_DP_PLUGIN_DIR . '/inc/plugin-conflicts/' . dirname( $active_plugin ) . '.php';
										$plugin_name = strtoupper( str_replace( '-', ' ', dirname( $active_plugin ) ) );
										$conflicts   = sprintf( __( 'Another user had an issue with the plugin %1$s. Read this %2$ssupport thread%3$s for more details. It may help you to avoid the same issue on your website.', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ), '<a title="' . __( 'Link to support thread', 'freesoul-deactivate-plugins' ) . '" href="' . esc_url( $support_thread_url ) . '" target="_blank" rel="noopener">', '</a>' );
										eos_dp_display_admin_notice( 'conflicts_' . sanitize_key( dirname( $active_plugin ) ), sprintf( __( 'Potential conflict with %s.', 'freesoul-deactivate-plugins' ), $plugin_name ), wp_kses_post( wpautop( $conflicts ) ), 'warning' );
									}
								}
							}
						}

						?>
					</div>
				</div>
			</span>
			<script>if('' === document.getElementById('fdp-notifications-count').innerText) document.getElementById('fdp-notifications-count').className = 'eos-hidden';</script>
			<a id="fdp-visit-site" class="dashicons dashicons-admin-home" style="margin-<?php echo is_rtl() ? 'right' : 'left'; ?>:10px;color:inherit" title="<?php esc_html_e( 'Visit Site' ); ?>" href="<?php echo esc_url( get_home_url() ); ?>" target="fdp_home_url"></a>
			<span id="fdp-toggle-top-bar" class="hover dashicons dashicons-arrow-down" style="font-size:35px;line-height:22px"></span>
		</div>
	</nav>
	<?php
	do_action( 'eos_dp_before_settings_nav' );
	$globally_active = eos_dp_active_plugins();
	$active_plugins  = absint( count( $globally_active ) );	
	if ( in_array(
		$_GET['page'],
		apply_filters(
			'fdp_plugin_filter_pages',
			array(
				'eos_dp_by_post_type',
				'eos_dp_menu',
				'eos_dp_by_archive',
				'eos_dp_by_term_archive',
				'eos_dp_url',
				'eos_dp_admin',
				'eos_dp_admin_url',
				'eos_dp_by_post_requests',
				'eos_dp_rest_api',
				'eos_dp_integration',
				'eos_dp_ajax',
				'eos_dp_logged',
				'eos_dp_by_plugin',
				'eos_dp_plugin_conflicts'
			)
		)
	) ) {
		if ( ! in_array( $_GET['page'], array( 'eos_dp_integration' ) ) ) {
			++$active_plugins;
		}
		if ( $active_plugins > 20 ) {
			$g = $active_plugins > 30 ? 20 : 10;
			$k = ceil( $active_plugins / $g );
			$l = 0;
			?>
			<div style="clear:both"></div>
			<div id="fdp-plugins-filters" style="display:inline-block">
				<span id="fdp-plug-filter-all" class="fdp-plug-filter button eos-active" data-min="all">
					<span class="dashicons dashicons-admin-plugins" style="padding:4px 0"></span>
					<?php esc_html_e( 'All', 'freesoul-deactivate-plugins' ); ?>
					<ul class="fdp-plugins-filter-list">
						<li><?php esc_html_e( 'No filter.', 'freesoul-deactivate-plugins' ); ?></li>
					</ul>
				</span>
				<?php for ( $n = 1;$n < $k + 1;++$n ) { ?>
				<span class="fdp-plug-filter button" data-min="<?php echo esc_attr( max( 1, ( $l * $g ) ) ); ?>" data-max="<?php echo esc_attr( min( $active_plugins, ( ( $n * $g ) - 1 ) ) ); ?>">
					<span class="dashicons dashicons-admin-plugins"  style="padding:4px 0"></span><?php printf( '%s - %s', esc_html( max( 1, ( $l * $g ) ) ), esc_html( min( $active_plugins - 1, ( ( $n * $g ) - 1 ) ) ) ); ?>
					<ul class="fdp-plugins-filter-list">
						<li><?php esc_html_e( 'Filter the following plugins:', 'freesoul-deactivate-plugins' ); ?></li>
						<li><br /></li>
					<?php
					for ( $lin = max( 1, ( $l * $g ) );$lin <= min( $active_plugins, ( ( $n * $g ) - 1 ) );++$lin ) {
						if ( isset( $globally_active[ $lin - 1 ] ) && '' !== $globally_active[ $lin - 1 ] && ( ! isset( $_GET['int_plugin'] ) || sanitize_text_field( $_GET['int_plugin'] ) !== dirname( $globally_active[ $lin - 1 ] ) ) ) {
							?>
						<li><?php echo sprintf( '%s - %s', esc_html( $lin ), esc_html( strtoupper( eos_dp_get_plugin_name_by_slug( $globally_active[ $lin - 1 ] ) ) ) ); ?></li>
							<?php
						}
					}
					?>
					</ul>
				</span>
					<?php
					$l = $n; }
				if ( in_array(
					$_GET['page'],
					apply_filters(
						'fdp_theme_filter_pages',
						array(
							'eos_dp_admin',
							'eos_dp_by_post_requests',
							'eos_dp_rest_api',
							'eos_dp_integration',
							'eos_dp_ajax',
						)
					)
				) ) {
					$tn = 1 + $active_plugins;
					?>
				<span class="fdp-plug-filter button" data-min="<?php echo esc_attr( $active_plugins ); ?>" data-max="<?php echo esc_attr( $active_plugins ); ?>">
					<span class="dashicons dashicons-admin-appearance"  style="padding:4px 0"></span>
					<ul class="fdp-plugins-filter-list">
						<li><?php esc_html_e( 'Filter the theme.', 'freesoul-deactivate-plugins' ); ?></li>
					</ul>
				</span>
					<?php
				}
			if( $slug_names && is_array( $slug_names ) && !empty( $slug_names ) ) {
?>
				<div id="fdp-plugin-filter-search-wrp" style="position:relative;z-index:999;display:inline-block;margin:5px 26px">
					<span id="fdp-plugin-filter-search-icon" style="position:absolute;top:1px;<?php echo is_rtl() ? 'right' : 'left'; ?>:-15px" class="hover dashicons dashicons-search" title="<?php esc_html_e( 'Search plugin', 'freesoul-deactivate-plugins' ); ?>"></span>
					<input id="fdp-plugin-filter-input" style="margin:0 10px;border-bottom-color:#D3C4B8;padding:0 4px;width:0;transition:1s linear;outline:none;border-top-width:0;border-left-width: 0;border-right-width: 0;background-color: transparent" onkeyup="eos_dp_filter_plugin_by_text(this.value);" type="text" />
				</div>
			<?php } ?>
				<p style="margin:0"><small><?php esc_html_e( 'Plugin filters', 'freesoul-deactivate-plugins' ); ?></small></p>
			</div>
			<?php
		}
	}
	do_action( 'eos_dp_after_settings_nav' );
	?>
	<div id="fdp-after-navigation"></div>
	<?php if ( $active_plugins > 20 && $slug_names && is_array( $slug_names ) && !empty( $slug_names ) ) { ?>
	<script id="fdp-plugin-search-filter-js">
		function eos_dp_plugin_filter_events(){
			var searchIcon = document.getElementById('fdp-plugin-filter-search-icon'),input = document.getElementById('fdp-plugin-filter-input');;
			searchIcon.addEventListener('click',function(){
				input.style.width = input.offsetWidth > 10 ? '0' : '200px';
			});			
			var plugins = document.querySelectorAll('.fdp-plugins-filter-list li'),filter_buttons = document.getElementsByClassName('fdp-plug-filter'),n = 0;
			for(n;n<plugins.length;++n){
				plugins[n].addEventListener('click',function(){
					plugin = this.innerText.replace(' - ',' ___ ').split(' ___ ')[1];
					eos_dp_filter_plugin_by_text(plugin);
					input.value = plugin;
					searchIcon.click();
					input.style.width = '200px';
					this.parentNode.style.top = '-999999999px';
				});
			}
			for(n = 0;n<filter_buttons.length;++n){
				filter_buttons[n].addEventListener('mouseover',function(){
					this.getElementsByClassName('fdp-plugins-filter-list')[0].removeAttribute('style');
					input.value = '';
					input.style.width = '0';
				});
			}
			for(n = 0;n<filter_buttons.length;++n){
				filter_buttons[n].addEventListener('click',function(e){
					if('span' === e.target.tagName.toLowerCase()){
						document.getElementById('fdp-plugin-filter-css').innerText = '';
					}
				});
			}

		}
		eos_dp_plugin_filter_events();
		function eos_dp_filter_plugin_by_text(txt){
			var found = false;css_parents = '',css = 'th div{max-width:32px}.eos-dp-plugin-name,.eos-dp-name-th,td,.eos-dp-global-chk-col-wrp,.fdp-p-n{display:none !important;white-space:normal !imortant}';
			if('' !== txt){
				slug_names = <?php echo wp_json_encode( array_flip( array_filter( $slug_names ) ) ); ?>;
				for(var idx in slug_names){
					if(
						idx.toLowerCase().indexOf(txt.toLowerCase()) > -1 
						|| idx.toLowerCase().indexOf(txt.toLowerCase().split(' ').join('-')) > -1 
						|| idx.split(' ').join('-').toLowerCase().indexOf(txt.toLowerCase().split(' ').join('-')) > -1
					){
						css += '[data-path="' + slug_names[idx] + '"],.eos-dp-post-name-wrp{display:table-cell !important}';
						css += '[data-path="' + slug_names[idx] + '"]+.eos-dp-global-chk-col-wrp{display:block !important}';
						css_parents += 'th:has(div[data-path="' + slug_names[idx] + '"]){display:table-cell !important}';
						found = true;
					}
				}
				if(found){
					css += css_parents;
				}
				else{
					css = '';
				}
			}
			else{
				css = '';
			}
			document.getElementById('fdp-plugin-filter-css').innerText = css;
		}
	</script>
	<?php } ?>
	<script id="fdp-nav-js">
	function eos_dp_update_nav(){
		var as=document.querySelectorAll('#eos-dp-setts-nav a,#eos-dp-before-nav a'),n=0;
		for(n;n<as.length;++n){
			as[n].parentNode.className = as[n].parentNode.className.replace(' eos-active','');
			if(as[n].href === location.href.split('&')[0] || as[n].href === location.href){
				as[n].parentNode.className += ' eos-active';
				if('undefined' !== typeof(as[n].parentNode.parentNode) && 'undefined' !== typeof(as[n].parentNode.parentNode.parentNode)){
					as[n].parentNode.parentNode.parentNode.className += ' eos-active';
				}
			}
		}
	}
	eos_dp_update_nav();
	</script>
	<?php
}
