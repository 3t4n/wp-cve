<?php
class GatorCachePurge
{
    protected $options;
    protected $configPath;

    public function __construct($options, $configPath)
    {
        $this->options = $options;
        $this->configPath = $configPath;
    }

    public function renderToolbar($wp_admin_bar)
    {
        if (!isset($wp_admin_bar) || !current_user_can('install_plugins') || (is_multisite() && !current_user_can('activate_plugins')) || !$this->options['installed']) {
            return;
        }

        if (!($isDashboard = is_admin())) {
            $page = GatorCache::getRequest()->getPathInfo();
        }
        $wp_admin_bar->add_node(array(
            'id'    => 'gc-purge',
            'title' => '<span class="ab-icon dashicons-update" style="padding-top:5px"></span> <span class="ab-label">' . __('Gator Cache', 'gator-cache'). '</span>',
            'href'  => admin_url() . '?page=' . WpGatorCache::PREFIX . '#tab-debug',
            // 'href' => '#TB_inline?inlineId=gatorcache-purge',
            'meta' => array(
                'class' => 'purge-cache',
                'title' => __('Refresh Gator Cache', 'gator-cache'),
            ),
        ));
        if (!$isDashboard) {
            $wp_admin_bar->add_node( array(
                'id'    => 'gc-purge-page',
                'title' => __('Refresh this page', 'gator-cache'),
                'href'   => '#',
                'parent'=> 'gc-purge',
            ));
        }
        $wp_admin_bar->add_node( array(
            'id'    => 'gc-purge-zap',
            'title' => __('Purge entire cache', 'gator-cache'),
            'href'   => '#',
            'parent'=> 'gc-purge',
        ));
    }

    public function handleXhr()
    {
        if (!current_user_can('install_plugins') || (is_multisite() && !current_user_can('activate_plugins'))) {
            die('{"success":0,"error":"Unauthorized"}');
        }
        if (empty($_POST['token']) || 1 !== wp_verify_nonce($_POST['token'], 'gc_purge')) {
            die('{"success":0,"error":"Could not verify request"}');
        }
        if (empty($_POST['path'])) {
            $_POST['path'] = '/';
        }
        if (!empty($_POST['type']) && 'zap' === $_POST['type']) {
            GatorCache::flushCache($this->configPath);
            die('{"success":1}');
        }
        WpGatorCache::purgePath(str_replace('../', '', $_POST['path']));
        die('{"success":1}');
    }
}
