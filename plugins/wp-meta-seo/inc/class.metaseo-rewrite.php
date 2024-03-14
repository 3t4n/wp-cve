<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * MetaSeoRewrite class
 */
class MetaSeoRewrite
{
    /**
     * Settings
     *
     * @var array
     */
    public $settings = array();

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->settings = get_option('_metaseo_settings');

        add_filter('query_vars', array($this, 'wpmsQueryVars'));

        add_filter('request', array($this, 'wpmsRequest'));

        add_filter('category_link', array($this, 'wpmsRemoveCategoryBase'));

        add_filter('category_rewrite_rules', array($this, 'wpmsCategoryRewriteRules'));

        add_action('init', array($this, 'wpmsFlush'), 999);

        add_action('created_category', array($this, 'wpmsScheduleFlush'));
        add_action('edited_category', array($this, 'wpmsScheduleFlush'));
        add_action('delete_category', array($this, 'wpmsScheduleFlush'));
    }

    /**
     * Save an option that triggers a flush on the next init.
     *
     * @since  1.2.8
     * @return void
     */
    public function wpmsScheduleFlush()
    {
        update_option('wpms_reflush_rewrite', 1);
    }

    /**
     * If the flush option is set, flush the rewrite rules.
     *
     * @return boolean
     * @since  1.2.8
     */
    public function wpmsFlush()
    {
        $reflush = get_option('wpms_reflush_rewrite');
        if ($reflush) {
            add_action('shutdown', 'flush_rewrite_rules');
            delete_option('wpms_reflush_rewrite');

            return true;
        }

        return false;
    }

    /**
     * Override the category link to remove the category base.
     *
     * @param string $link Unused, overridden by the function.
     *
     * @return string
     */
    public function wpmsRemoveCategoryBase($link)
    {
        $category_base = get_option('category_base');

        if (empty($category_base)) {
            $category_base = 'category';
        }

        /*
         * Remove initial slash, if there is one (we remove the trailing slash
         * in the regex replacement and don't want to end up short a slash).
         */
        if (substr($category_base, 0, 1) === '/') {
            $category_base = substr($category_base, 1);
        }

        $category_base .= '/';

        return preg_replace('`' . preg_quote($category_base, '`') . '`u', '', $link, 1);
    }

    /**
     * Update the query vars with the redirect var when remove category prefix is active.
     *
     * @param array $query_vars Main query vars to filter.
     *
     * @return array
     */
    public function wpmsQueryVars($query_vars)
    {

        if (isset($this->settings['metaseo_removecatprefix']) && $this->settings['metaseo_removecatprefix'] === '1') {
            $query_vars[] = 'wpms_category_redirect';
        }

        return $query_vars;
    }

    /**
     * Checks whether the redirect needs to be created.
     *
     * @param array $query_vars Query vars to check for existence of redirect var.
     *
     * @return array|void The query vars.
     */
    public function wpmsRequest($query_vars)
    {
        if (!isset($query_vars['wpms_category_redirect'])) {
            return $query_vars;
        }

        $this->redirect($query_vars['wpms_category_redirect']);
    }

    /**
     * This function taken and only slightly adapted from WP No Category Base plugin by Saurabh Gupta.
     *
     * @return array
     */
    public function wpmsCategoryRewriteRules()
    {
        global $wp_rewrite;

        $category_rewrite = array();

        $taxonomy = get_taxonomy('category');
        $permalink_structure = get_option('permalink_structure');

        $blog_prefix = '';
        if (is_multisite() && !is_subdomain_install() && is_main_site() && strpos($permalink_structure, '/blog/') === 0) {
            $blog_prefix = 'blog/';
        }

        $categories = get_categories(array('hide_empty' => false));

        if (is_array($categories) && !empty($categories)) {
            foreach ($categories as $category) {
                $category_nicename = $category->slug;
                if ($category->parent === $category->cat_ID) {
                    // Recursive recursion.
                    $category->parent = 0;
                } elseif ($taxonomy->rewrite['hierarchical'] !== false && $category->parent !== 0) {
                    $parents = get_category_parents($category->parent, false, '/', true);
                    if (!is_wp_error($parents)) {
                        $category_nicename = $parents . $category_nicename;
                    }
                    unset($parents);
                }

                $category_rewrite = $this->addCategoryRewrites($category_rewrite, $category_nicename, $blog_prefix, $wp_rewrite->pagination_base);

                // Adds rules for the uppercase encoded URIs.
                $category_nicename_filtered = $this->convertEncodedToUpper($category_nicename);

                if ($category_nicename_filtered !== $category_nicename) {
                    $category_rewrite = $this->addCategoryRewrites($category_rewrite, $category_nicename_filtered, $blog_prefix, $wp_rewrite->pagination_base);
                }
            }
            unset($categories, $category, $category_nicename, $category_nicename_filtered);
        }

        // Redirect support from Old Category Base.
        $old_base = $wp_rewrite->get_category_permastruct();
        $old_base = str_replace('%category%', '(.+)', $old_base);
        $old_base = trim($old_base, '/');
        $category_rewrite[$old_base . '$'] = 'index.php?wpms_category_redirect=$matches[1]';

        return $category_rewrite;
    }

    /**
     * Adds required category rewrites rules.
     *
     * @param array  $rewrites        The current set of rules.
     * @param string $category_name   Category nicename.
     * @param string $blog_prefix     Multisite blog prefix.
     * @param string $pagination_base WP_Query pagination base.
     *
     * @return array The added set of rules.
     */
    protected function addCategoryRewrites($rewrites, $category_name, $blog_prefix, $pagination_base)
    {
        $rewrite_name = $blog_prefix . '(' . $category_name . ')';

        $rewrites[$rewrite_name . '/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
        $rewrites[$rewrite_name . '/' . $pagination_base . '/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
        $rewrites[$rewrite_name . '/?$'] = 'index.php?category_name=$matches[1]';

        return $rewrites;
    }

    /**
     * Walks through category nicename and convert encoded parts
     * into uppercase using $this->encodeToUpper().
     *
     * @param string $name The encoded category URI string.
     *
     * @return string The convered URI string.
     */
    protected function convertEncodedToUpper($name)
    {
        // Checks if name has any encoding in it.
        if (strpos($name, '%') === false) {
            return $name;
        }

        $names = explode('/', $name);
        $names = array_map(array($this, 'encodeToUpper'), $names);

        return implode('/', $names);
    }

    /**
     * Converts the encoded URI string to uppercase.
     *
     * @param string $encoded The encoded string.
     *
     * @return string The uppercased string.
     */
    public function encodeToUpper($encoded)
    {
        if (strpos($encoded, '%') === false) {
            return $encoded;
        }

        return strtoupper($encoded);
    }

    /**
     * Redirect the "old" category URL to the new one.
     *
     * @param string $category_redirect The category page to redirect to.
     *
     * @return void
     */
    protected function redirect($category_redirect)
    {
        $catlink = trailingslashit(get_option('home')) . user_trailingslashit($category_redirect, 'category');

        wp_redirect($catlink, 301, 'Yoast SEO');
        exit;
    }
} /* End of class */
