<?php

class Library_Viewer_Plugin_Page {

	/**
	 * Library_Viewer_Plugin_Page constructor.
	 *
	 * This function calls the hooks.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter('plugin_action_links_' . plugin_basename(LIBRARY_VIEWER_FILE_ABSPATH), array($this, 'add_plugin_action_link'));
		add_filter('plugin_row_meta', array($this, 'filter__plugin_row_meta__view_documentation'), 15,2);
	}

	/** Hook callback to a filter, that add a link in the plugin's action links.
	 *
	 * @since 2.0.0
	 *
	 * @param array $links Plugin's action links.
	 * @return array $links Plugin's action links.
	 */
	public function add_plugin_action_link($links) {
		if ( defined('LIBRARY_VIEWER_PRO_FILE_ABSPATH') ) {
			return $links;
		} else {
			$mylinks = array(
				'<a target="_blank" style="color:red;" href="' . LIBRARY_VIEWER_PRO_BUY_URL . '">Go Pro!</a>',
			);
			$links = array_merge($links, $mylinks);
			return $links;
		}
	}

	/** Hook callback to a filter, that add a link in the plugin's meta links.
	 *
	 * @since 2.0.0
	 *
	 * @param array $links Plugin's action links.
	 * @param string $file Plugin's basename
	 * @return array $links Plugin's action links.
	 */
	public function filter__plugin_row_meta__view_documentation($links, $file)
	{
		if ( LIBRARY_VIEWER_FILE_ABSPATH === WP_PLUGIN_DIR  . '/' . $file ) {
			$links[] = '<a target="_blank" href="'. LIBRARY_VIEWER_DOCUMENTATION_URL . '">' . __('Documentation', 'library-viewer') . '</a>';
		}
		return $links;
	}

}
