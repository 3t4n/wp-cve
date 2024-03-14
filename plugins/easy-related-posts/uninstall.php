<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Easy related posts
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'easy_related_posts.php';

class erpUninstall {

    /**
     * Fired when the plugin is deactivated.
     *
     * @since 2.0.0
     */
    public static function uninstall() {
	if (function_exists('is_multisite') && is_multisite()) {
	    // Get all blog ids
	    $blog_ids = self::get_blog_ids();

	    foreach ($blog_ids as $blog_id) {

		switch_to_blog($blog_id);
		self::single_uninstall();
	    }

	    restore_current_blog();
	} else {
	    self::single_uninstall();
	}
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since 2.0.0
     * @return array false blog ids, false if no matches.
     */
    private static function get_blog_ids() {
	global $wpdb;

	// get an array of blog ids
	$sql = "SELECT blog_id FROM $wpdb->blogs
		WHERE archived = '0' AND spam = '0'
		AND deleted = '0'";

	return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since 2.0.0
     */
    private static function single_uninstall() {
	
	/**
	 * Del main options
	 */
	self::delMainOptions();
	
	/**
	 * Del templates options
	 */
	self::delTemplateOptions();
	
	/**
	 * Del wid options
	 */
	self::delWidOptions();
	        
        /**
         * Del version numbers
         */
        self::deleteVersionNumbers();
        
        delete_option(ERP_SLUG.'AdminNotices');
    }
    
    private static function delMainOptions() {
	erpPaths::requireOnce(erpPaths::$erpMainOpts);
	$mOpts = new erpMainOpts();
	delete_option($mOpts->getOptionsArrayName());
    }
    
    private static function delTemplateOptions() {
        erpPaths::requireOnce(erpPaths::$VPluginThemeFactory);
        VPluginThemeFactory::registerThemeInPathRecursive(erpPaths::getAbsPath(erpPaths::$mainThemesFolder));
        $themes = VPluginThemeFactory::getRegisteredThemes();
        
        foreach ((array)$themes as $key => $value) {
            delete_option($value->getOptionsArrayName());
        }
    }
    
    private static function delWidOptions() {
	erpPaths::requireOnce(erpPaths::$erpWidOpts);
	$wOpts = new erpWidOpts();
	delete_option($wOpts->getOptionsArrayName());
    }
    
    private static function deleteVersionNumbers() {
        delete_option(erpDefaults::versionNumOptName);
    }
}

erpUninstall::uninstall();