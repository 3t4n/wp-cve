<?php
/**
 * Template Before Main Navigation.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<ul id="eos-dp-before-nav" style="display:inline-block">
  <li style="margin-<?php echo is_rtl() ? 'right' : 'left'; ?>:-14px;margin-<?php echo is_rtl() ? 'left' : 'right'; ?>:10px;margin-top:-10px;float:<?php echo is_rtl() ? 'right' : 'left'; ?>;">
	<?php
	$logo_path = EOS_DP_PLUGIN_DIR . '/admin/assets/img/fdp-logo-128x128.png';
	if ( file_exists( $logo_path ) ) {
		$type = pathinfo( $logo_path, PATHINFO_EXTENSION );
		$data = file_get_contents( $logo_path );
		$text = apply_filters( 'fdp_pre_navigation_title', sprintf( 'FDP v%s%s', EOS_DP_VERSION, $pro ) );
		?>
	  <a title="<?php echo esc_attr( $text ); ?>" alt="FDP logo" href="https://freesoul-deactivate-plugins.com" target="_bank" rel="noopener">
		<img class="fdp-logo" src="data:image/<?php echo esc_attr( $type ); ?>;base64,<?php echo esc_attr( base64_encode( $data ) ); ?>" style="width:50px;height:50px" />
	  </a>
		<?php
	}
	?>
  </li>
  <li data-section="eos-dp-control-panel-section" class="eos-dp-has-children hover eos-dp-setts-menu-item">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_menu' ) ); ?>"><span title="<?php esc_attr_e( 'Settings', 'freesoul-deactivate-plugins' ); ?>" class="dashicons dashicons-admin-generic"></span></a>
	<ul id="eos-dp-main-settings" class="eos-dp-sub-menu">
	  <li class="hover eos-dp-setts-menu-item">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_menu' ) ); ?>" title="<?php esc_attr_e( 'Disable plugins', 'freesoul-deactivate-plugins' ); ?>"><span class="dashicons dashicons-plugins-checked"></span><?php esc_html_e( 'Plugins Deactivation', 'freesoul-deactivate-plugins' ); ?></a>
	  </li>
	  <li class="eos-dp-submenu-item hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'main-settings?fbk=1' ) . '" target="_blank" rel="noopener' : esc_url( admin_url( 'admin.php?page=eos_dp_pro_settings' ) ); ?>"><?php esc_html_e( 'Main settings', 'freesoul-deactivate-plugins' ); ?></a>
	  </li>
	  <li class="eos-dp-submenu-item hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'plugins?fbk=1' ) . '" target="_blank" rel="noopener' : esc_url( admin_url( 'admin.php?page=eos_dp_pro_plugins' ) ); ?>"><?php esc_html_e( 'Plugin settings', 'freesoul-deactivate-plugins' ); ?></a>
	  </li>
	  <?php if ( current_user_can( 'manage_options' ) ) { ?>
	  <li class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_roles_manager' ) ); ?>"><?php esc_html_e( 'Roles Manager', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <?php } ?>
	  <li class="hover">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_firing_order' ) ); ?>" title="<?php esc_attr_e( 'Firing Order', 'freesoul-deactivate-plugins' ); ?>"><?php esc_html_e( 'Firing Order', 'freesoul-deactivate-plugins' ); ?></a>
	  </li>
	  <li class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="
		<?php
		echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'css-js-further-cleanup-pro?fbk=1' ) . '" target="_blank" rel="noopener' : esc_url(
			add_query_arg(
				array(
					'fdp-assets' => 'styles',
					't'          => time(),
				),
				home_url()
			)
		);
		?>
		" title="<?php esc_attr_e( 'CSS/JS', 'freesoul-deactivate-plugins' ); ?>" target="_blank">
		  <span class="dashicons dashicons-editor-code"></span>
		  <?php esc_html_e( 'CSS/JS', 'freesoul-deactivate-plugins' ); ?>
		</a>
	  </li>
	  <li class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'general-bloat-pro?fbk=1' ) . '" target="_blank" rel="noopener' : esc_url( admin_url( 'admin.php?page=eos_dp_pro_general_bloat' ) ); ?>" title="<?php esc_attr_e( 'General Bloat', 'freesoul-deactivate-plugins' ); ?>">
		  <span class="dashicons dashicons-wordpress-alt"></span>
		  <?php esc_html_e( 'General Bloat', 'freesoul-deactivate-plugins' ); ?>
		</a>
	  </li>
	  <?php if ( defined( 'CODE_PROFILER_MU_ON' ) || defined( 'CODE_PROFILER_PRO_MU_ON' ) ) { ?>
	  <li class="hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=cp-fdp' ) ); ?>"><?php esc_html_e( 'Code Profiler', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <?php } ?>
	  <?php do_action( 'fdp_settings_submenu_end' ); ?>
	</ul>
  </li>
  <li data-section="eos-dp-control-panel-section" class="eos-dp-has-children hover eos-dp-setts-menu-item">
	<a href="<?php echo esc_url( admin_url( 'tools.php?page=eos_dp_pro_import_export' ) ); ?>"><span title="<?php esc_attr_e( 'Tools', 'freesoul-deactivate-plugins' ); ?>" class="dashicons dashicons-admin-tools"></span></a>
	<ul id="eos-dp-import-export-sub" class="eos-dp-sub-menu">
	  <li class="eos-dp-submenu-item hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'import-export-settings?fbk=1' ) . '" target="_blank" rel="noopener' : esc_url( admin_url( 'tools.php?page=eos_dp_pro_import_export' ) ); ?>"><?php esc_html_e( 'Import/Export', 'freesoul-deactivate-plugins' ); ?></a>
	  </li>
	  <li class="eos-dp-submenu-item hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_pro_bulk_actions' ) ); ?>"><?php esc_html_e( 'Bulk actions', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'hooks-pro?fbk=1' ) . '" target="_blank" rel="noopener' : esc_url( add_query_arg( 'fdp-hooks', 'actions', home_url() ) ); ?>" title="<?php esc_attr_e( 'Hooks | Frontent', 'freesoul-deactivate-plugins' ); ?>" target="_blank">
		  <?php esc_html_e( 'Hooks | Frontend', 'freesoul-deactivate-plugins' ); ?>
		</a>
	  </li>
	  <li class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'hooks-pro?fbk=1' ) . '" target="_blank" rel="noopener' : esc_url( admin_url( 'admin.php?page=eos_dp_pro_hooks_recorder' ) ); ?>" title="<?php esc_attr_e( 'Hooks Recorder', 'freesoul-deactivate-plugins' ); ?>">
		  <?php esc_html_e( 'Hooks Recorder', 'freesoul-deactivate-plugins' ); ?>
		</a>
	  </li>
	  <?php if ( current_user_can( 'manage_options' ) ) { ?>
	  <li class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_create_plugin' ) ); ?>"><?php esc_html_e( 'Create custom plugin', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_reset_settings' ) ); ?>"><?php esc_html_e( 'Reset FDP Settings', 'freesoul-deactivate-plugins' ); ?> </a></li>
	  <?php } ?>
	  <li class="eos-dp-submenu-item hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_favorite_plugins' ) ); ?>"><?php esc_html_e( 'Favorite plugins', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="fdp-show-only-on-pro eos-dp-submenu-item hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>">
		<a href="<?php echo '' === $pro ? esc_url( EOS_DP_DOCUMENTATION_URL . 'source-checker-pro?fbk=1' ) . ' " target="_blank" rel="noopener' : esc_url( admin_url( 'admin.php?page=eos_dp_pro_whois' ) ); ?>"><?php esc_html_e( 'Source Checker', 'freesoul-deactivate-plugins' ); ?></a>
	  </li>
	  <?php do_action( 'fdp_tools_submenu_end' ); ?>
	</ul>
  </li>
  <?php do_action( 'eos_dp_before_tabs' ); ?>
  <li class="eos-dp-has-children hover eos-dp-setts-menu-item">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_smoke_tests' ) ); ?>" title="<?php esc_attr_e( 'Testing', 'freesoul-deactivate-plugins' ); ?>"><span class="dashicons dashicons-info"></span></a>
	<ul class="eos-dp-sub-menu">
	  <li class="hover eos-dp-setts-menu-item"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_smoke_tests' ) ); ?>"><?php esc_html_e( 'Plugin Tests', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>"><a href="<?php echo esc_url( add_query_arg( 'page', 'eos_dp_testing', admin_url( 'admin.php?page=eos_dp_testing' ) ) ); ?>"><?php esc_html_e( 'Testing Settings', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>"><a href="<?php echo esc_url( add_query_arg( 'page', 'eos_dp_report', admin_url( 'admin.php?page=eos_dp_report' ) ) ); ?>"><?php esc_html_e( 'Reports', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <?php do_action( 'fdp_testing_submenu_end' ); ?>
	</ul>
  </li>
  <li class="eos-dp-has-children hover eos-dp-setts-menu-item" title="<?php esc_attr_e( 'Help', 'freesoul-deactivate-plugins' ); ?>">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_help' ) ); ?>"><span title="<?php esc_attr_e( 'Help', 'freesoul-deactivate-plugins' ); ?>" class="dashicons dashicons-editor-help"></span></a>
	<ul class="eos-dp-sub-menu">
	  <li class="hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_menu&reopen_pointer=true' ) ); ?>"><?php esc_html_e( 'Guided tour', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_help&tab=common_issues' ) ); ?>"><?php esc_html_e( 'Common issues', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_help&tab=shortcuts' ) ); ?>"><?php esc_html_e( 'Shortcuts', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover"><a target="_blank" rel="noopener" href="https://freesoul-deactivate-plugins.com/how-deactivate-plugiins-on-specific-pages/"><?php esc_html_e( 'Documentation', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover"><a target="_blank" rel="noopener" href="https://wordpress.org/support/plugin/freesoul-deactivate-plugins/"><?php esc_html_e( 'Support Forum', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <li class="hover<?php echo '' === $pro ? ' fdp-pro-feature fdp-dismiss-pro-notice' : ''; ?>"><a target="_blank" rel="noopener" href="https://support.freesoul-deactivate-plugins.com/"><?php esc_html_e( 'Premium Support', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <?php if( !is_multisite() ) { ?>
	  <li class="hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_hireus' ) ); ?>"><?php esc_html_e( 'Hire Us', 'freesoul-deactivate-plugins' ); ?></a></li>
	  <?php } ?>
	  <?php do_action( 'fdp_help_submenu_end' ); ?>
	</ul>
  </li>
  <li></li>
</ul>
