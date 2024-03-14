<?php
/**
 * Admin customizations.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class REVIVESO_Admin extends REVIVESO_BaseController {
	use REVIVESO_Hooker;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( "plugin_action_links_{$this->plugin}", 'settings_link', 10, 1 );
		$this->action( 'admin_menu', 'menu_items', 99 );
		$this->action( 'admin_footer', 'do_footer', 99 );
		$this->action( 'plugin_row_meta', 'meta_links', 10, 2 );
		$this->action( 'admin_footer_text', 'admin_footer', 999 );
		$this->filter( 'action_scheduler_pastdue_actions_check_pre', 'as_exclude_pastdue_actions' );
		// Remove admin notices from Revive.so pages
		add_action( 'admin_notices', array( $this, 'remove_admin_notices' ), 9 );
	}

	/**
	 * Register settings link.
	 */
	public function settings_link( $links ) {
		$settings = array(
			'<a href="' . admin_url( 'admin.php?page=reviveso' ) . '">' . __( 'Settings', 'revive-so' ) . '</a>',
		);

		return array_merge( $settings, $links );
	}

	/**
	 * Add roadmap item to submenu
	 */
	public function menu_items() {
		// Don't show the Scheduled Tasks menu item if it's not explicitly enabled.
		/**
		 * Filter to enable/disable the Scheduled Tasks menu item.
		 *
		 * @hook reviveso_display_scheduled_tasks
		 *
		 * @param  bool  $display_scheduled_tasks  True to display the Scheduled Tasks menu item, false to hide it.
		 */
		if ( ! apply_filters( 'reviveso_display_scheduled_tasks', false ) ) {
			return;
		}
		$manage_options_cap = $this->do_filter( 'manage_options_capability', 'manage_options' );

		// Add custom Action Schedular page.
		if ( class_exists( 'ActionScheduler_AdminView' ) ) {
			$as          = \ActionScheduler_AdminView::instance();
			$hook_suffix = add_submenu_page(
				'reviveso',
				__( 'Scheduled Tasks', 'revive-so' ),
				__( 'Scheduled Tasks', 'revive-so' ),
				$manage_options_cap,
				'reviveso-scheduled-tasks',
				array( $as, 'render_admin_ui' )
			);
			add_action( 'load-' . $hook_suffix, array( $as, 'process_admin_ui' ) );
		}

		// Filter to redefine that Reviveso > Scheduled Tasks menu item.
		if ( $this->do_filter( 'tasks_admin_hide_as_menu', true ) ) {
			remove_submenu_page( 'tools.php', 'revive-so' );
		}
	}

	/**
	 * Open External links in new tab
	 */
	public function do_footer() { ?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				let revsTaskItem = $("ul#adminmenu .toplevel_page_reviveso ul.wp-submenu li a[href*='reviveso-scheduled-tasks']");
				revsTaskItem.attr({
									  target: '_blank',
									  href  : revsTaskItem.attr('href') + '&status=pending&s=reviveso'
								  });
			});
		</script>
		<?php
	}

	/**
	 * Register meta links.
	 */
	public function meta_links( $links, $file ) {
		if ( $this->plugin === $file ) { // only for this plugin
			$links[] = '<a href="https://revive.so/docs/?utm_source=plugin_page&utm_medium=plugin" target="_blank">' . __( 'Documentation', 'revive-so' ) . '</a>';
		}

		return $links;
	}

	/**
	 * Custom Admin footer text
	 */
	public function admin_footer( $content ) {
		$current_screen = get_current_screen();
		if ( 'toplevel_page_reviveso' === $current_screen->id ) {
			$content = __( 'Thank you for using', 'revive-so' ) . ' <a href="https://revive.so/" target="_blank" style="font-weight: 500;">Reviveso</a>';
			$content .= ' &bull; <a href="https://wordpress.org/support/plugin/revive-so/reviews/?filter=5#new-post" target="_blank" style="font-weight: 500;">' . __( 'Rate it', 'revive-so' ) . '</a> (<span style="color:#ffa000;">★★★★★</span>) on WordPress.org, if you like this plugin.</span>';
			$content = '<span class="reviveso-footer">' . $content . '</span>';
		}

		return $content;
	}

	/**
	 * Action Scheduler: exclude our actions from the past-due checker.
	 * Since this is a *_pre hook, it replaces the original checker.
	 *
	 * We first do the same check as what ActionScheduler_AdminView->check_pastdue_actions() does,
	 * but then we also count how many of those past-due actions are ours.
	 *
	 * @param  null  $null  Null value.
	 */
	public function as_exclude_pastdue_actions( $null ) {
		$query_args = array(
			'date'     => as_get_datetime_object( time() - DAY_IN_SECONDS ),
			'status'   => \ActionScheduler_Store::STATUS_PENDING,
			'per_page' => 1,
		);

		$store               = \ActionScheduler_Store::instance();
		$num_pastdue_actions = (int) $store->query_actions( $query_args, 'count' );

		if ( 0 !== $num_pastdue_actions ) {
			$query_args['group']      = 'reviveso';
			$num_pastdue_revs_actions = (int) $store->query_actions( $query_args, 'count' );

			$num_pastdue_actions -= $num_pastdue_revs_actions;
		}

		$threshold_seconds = (int) apply_filters( 'action_scheduler_pastdue_actions_seconds', DAY_IN_SECONDS );
		$threshhold_min    = (int) apply_filters( 'action_scheduler_pastdue_actions_min', 1 );

		$check = ( $num_pastdue_actions >= $threshhold_min );

		return (bool) apply_filters( 'action_scheduler_pastdue_actions_check', $check, $num_pastdue_actions, $threshold_seconds, $threshhold_min );
	}

	/**
	 * Remove all notices that are in Revive.so's pages
	 *
	 * @return void
	 * @since 1.0.4
	 */
	public function remove_admin_notices() {
		$screen = get_current_screen();

		if ( 'reviveso' === $screen->parent_base ) {
			remove_all_actions( 'admin_notices' );
		}
	}
}
