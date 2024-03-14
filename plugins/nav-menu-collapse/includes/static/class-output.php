<?php
/*!
 * Plugin output functionality.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Output
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the output functionality.
 *
 * @since 2.0.0
 */
final class Nav_Menu_Collapse_Output
{
	/**
	 * Admin page tabs.
	 *
	 * @since 2.0.0
	 *
	 * @access private static
	 * @var    array
	 */
	private static $_tabs = array();

	/**
	 * Add an admin page tab.
	 *
	 * @since 2.0.1 Removed escape from admin URL and improved condition.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $menu_parent Parent page for the admin page.
	 * @param  string $menu_slug   Menu slug for the admin page.
	 * @param  string $page_title  Title for the admin page tab.
	 * @return void
	 */
	public static function add_tab($menu_parent, $menu_slug, $title)
	{
		if (!empty($menu_parent))
		{
			$url = admin_url($menu_parent);

			if (!empty($menu_slug))
			{
				$url = add_query_arg('page', $menu_slug, $url);
			}

			self::$_tabs[] = array
			(
				'title' => $title,
				'url' => $url,

				'active_class' => ($menu_slug === Nav_Menu_Collapse()->cache->option_name)
				? ' nmc-tab-active'
				: ''
			);
		}
	}

	/**
	 * Output an admin form page.
	 *
	 * @since 2.0.3 Changed admin page output.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $heading     Heading displayed at the top of the admin form page.
	 * @param  string $action      AJAX action to request on form submission.
	 * @param  string $option_name Option name to generate the admin form page for.
	 * @return void
	 */
	public static function admin_form_page($heading, $action = '', $option_name = '')
	{
		$nmc = Nav_Menu_Collapse();
		
		echo '<div class="wrap">';

		self::admin_nav_bar($heading);

		$screen = $nmc->cache->screen;
		$columns = $screen->get_columns();
		
		if (empty($columns))
		{
			$columns = 2;
		}

		echo '<form method="post" id="nmc-form">'
			. '<input name="admin-page" type="hidden" value="' . esc_attr($nmc->cache->admin_page) . '" />';
		
		if (!empty($action))
		{
			$action = sanitize_key($action);
			
			echo '<input name="action" type="hidden" value="' . $action . '" />';
			
			wp_nonce_field($action);
		}

		if (!empty($option_name))
		{
			echo '<input name="option-name" type="hidden" value="' . sanitize_key($option_name) . '" />';
		}

		if (!empty($admin_page))
		{
			echo '<input name="admin-page" type="hidden" value="' . esc_attr($admin_page) . '" />';
		}

		wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
		wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);

		echo '<div id="poststuff">'
			. '<div id="post-body" class="metabox-holder columns-' . $columns . '">'
				. '<div id="postbox-container-1" class="postbox-container">';

		do_meta_boxes($screen->id, 'side', '');

		echo '</div>'
		. '<div id="nmc-primary-wrapper">'
			. '<div id="postbox-container-2" class="postbox-container">';

		do_meta_boxes($screen->id, 'advanced', '');
		do_meta_boxes($screen->id, 'normal', '');

		echo '</div>'
						. '</div>'
						. '<div class="nmc-clear"></div>'
					. '</div>'
				. '</div>'
			. '</form>'
		. '</div>';
	}

	/**
	 * Output the admin page nav bar.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $heading Heading displayed in the nav bar.
	 * @return void
	 */
	public static function admin_nav_bar($heading)
	{
		$nmc = Nav_Menu_Collapse();
		$buttons = '';
		
		echo '<div class="nmc-nav">'
			. '<div class="nmc-nav-title">'
				. '<h1>'
					. '<strong>' . $nmc->cache->plugin_data['Name'] . '</strong> | ' . $heading
				. '</h1>'
				. '<div class="nmc-clear"></div>'
			. '</div>';
		
		if (count(self::$_tabs) > 1)
		{
			echo '<div class="nmc-tab-wrapper">';

			foreach (self::$_tabs as $tab)
			{
				echo '<a class="nmc-tab' . $tab['active_class'] . '" href="' . $tab['url'] . '">' . $tab['title'] . '</a>';
			}

			echo '</div>';
		}
		
		echo '</div>'
		. '<hr class="wp-header-end" />';
	}
	
	/**
	 * Add the plugin name to a page title.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $page_title Current page title.
	 * @return string             Modified page title.
	 */
	public static function page_title($page_title)
	{
		return $page_title . ' &#0139; ' . Nav_Menu_Collapse()->cache->plugin_data['Name'];
	}
}
