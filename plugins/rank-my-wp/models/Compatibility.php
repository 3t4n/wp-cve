<?php

class RKMW_Models_Compatibility {

    public function __construct() {
        add_action('admin_bar_menu', array($this, 'hookTopMenu'), PHP_INT_MAX);;
    }

    public function hookTopMenu($wp_admin_bar) {
        if (!is_admin()) {
            return false;
        }

        if (current_user_can('edit_posts')) {
            $menuid = 'rkmw_research';
            //Check Compatibility with Yoast SEO
            if(RKMW_Classes_Helpers_Tools::isPluginInstalled('wordpress-seo/wp-seo.php')) {
                $wp_admin_bar->add_node(array(
                    'id' => $menuid . '_yoast',
                    'title' => RKMW_NAME . ' ' . esc_html__('Research',RKMW_PLUGIN_NAME),
                    'href' => RKMW_Classes_Helpers_Tools::getAdminUrl($menuid),
                    'parent' => 'wpseo-kwresearch'
                ));
            }

            //Check Compatibility with Yoast SEO
            if(RKMW_Classes_Helpers_Tools::isPluginInstalled('seo-by-rank-math/rank-math.php')) {
                $wp_admin_bar->add_node(array(
                    'id' => $menuid . '_rm',
                    'title' => RKMW_NAME . ' ' . esc_html__('Research',RKMW_PLUGIN_NAME),
                    'href' => RKMW_Classes_Helpers_Tools::getAdminUrl($menuid),
                    'parent' => 'rank-math'
                ));
            }

            //Check Compatibility with Yoast SEO
            if(RKMW_Classes_Helpers_Tools::isPluginInstalled('all-in-one-seo-pack/all_in_one_seo_pack.php')) {
                $wp_admin_bar->add_node(array(
                    'id' => $menuid . '_aioseo',
                    'title' => RKMW_NAME . ' ' . esc_html__('Research',RKMW_PLUGIN_NAME),
                    'href' => RKMW_Classes_Helpers_Tools::getAdminUrl($menuid),
                    'parent' => 'aioseo-main'
                ));
            }


        }
    }

    /**
     * Show Compatibility Notices
     * @return string
     */
    public function getNotificationBar() {
        return '';
    }


    /**
     * Prevent other plugins javascript
     */
    public function fixEnqueueErrors() {
        $exclude = array('boostrap',
            'wpcd-admin-js', 'ampforwp_admin_js', '__ytprefs_admin__' //collor picker compatibility
        );

        foreach ($exclude as $name) {
            wp_dequeue_script($name);
            wp_dequeue_style($name);
        }
    }

    /**
     * Clear the styles from other plugins
     */
    public function clearStyles() {
        $this->fixEnqueueErrors();
    }
}
