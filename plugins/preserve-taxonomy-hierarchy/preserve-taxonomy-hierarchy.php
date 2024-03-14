<?php
namespace preserveHierarchy;

// prevent direct access to file
if (!defined('WPINC')) {
    die;
}

class TaxonomyHierarchy
{

    function __construct()
    {

        add_action('admin_init', array($this, 'post_cat'));
        add_action('load-nav-menus.php', array($this, 'nav_menu'));
    }

    function post_cat()
    {
        add_filter('wp_terms_checklist_args', array($this, 'checklist_args'));
    }

    function nav_menu()
    {
        add_action('pre_get_posts', array($this, 'disable_paging_for_hierarchical_post_types'));
        add_filter('get_terms_args', array($this, 'remove_hierarchical_limits'), 10, 2);
        add_filter('get_terms_fields', array($this, 'remove_page_links_for_hierarchical_taxonomies'), 10, 3);

    }

    function checklist_args($args)
    {
        add_action('admin_footer', array($this, 'scroll_script'));
        $args['checked_ontop'] = false;
        return $args;
    }

    function disable_paging_for_hierarchical_post_types($query)
    {
        if (!is_admin() || 'nav-menus' !== get_current_screen()->id) {
            return;
        }

        if (!is_post_type_hierarchical($query->get('post_type'))) {
            return;
        }

        if (50 == $query->get('posts_per_page')) {
            $query->set('nopaging', true);
        }
    }

    function remove_hierarchical_limits($args, $taxonomies)
    {
        if (!is_admin() || 'nav-menus' !== get_current_screen()->id) {
            return $args;
        }

        if (!is_taxonomy_hierarchical(reset($taxonomies))) {
            return $args;
        }

        if (50 == $args['number']) {
            $args['number'] = '';
        }

        return $args;
    }

    function remove_page_links_for_hierarchical_taxonomies($selects, $args, $taxonomies)
    {
        if (!is_admin() || 'nav-menus' !== get_current_screen()->id) {
            return $selects;
        }

        if (!is_taxonomy_hierarchical(reset($taxonomies))) {
            return $selects;
        }

        if ('count' === $args['fields']) {
            $selects = array('1');
        }

        return $selects;
    }


    function scroll_script()
    {
        ?>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('[id$="-all"] > ul.categorychecklist').each(function () {
                    var $list = jQuery(this);
                    var $firstChecked = $list.find(':checkbox:checked').first();
                    if (!$firstChecked.length) return;
                    var first_one = $list.find(':checkbox').position().top;
                    var checked_one = $firstChecked.position().top;
                    $list.closest('.tabs-panel').scrollTop(checked_one - first_one + 10);
                });
            });
        </script>
        <?php
    }

}




