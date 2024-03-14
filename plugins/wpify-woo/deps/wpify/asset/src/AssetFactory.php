<?php

namespace WpifyWooDeps\Wpify\Asset;

class AssetFactory
{
    public function admin_wp_script(string $asset_path, $args = null) : Asset
    {
        return $this->wp_script($asset_path, (new AssetConfig($args))->set_is_admin(\true));
    }
    public function wp_script(string $asset_path, $args = null) : Asset
    {
        $config = new AssetConfig($args);
        $asset_base = \pathinfo($asset_path, \PATHINFO_FILENAME);
        $build_path = \pathinfo($asset_path, \PATHINFO_DIRNAME);
        $assets_info = wp_normalize_path($build_path . '/assets.php');
        $extension = \current(\explode('?', \pathinfo($asset_path, \PATHINFO_EXTENSION)));
        $filename = $asset_base . '.' . $extension;
        $asset_info = array(wp_normalize_path($build_path . '/' . $asset_base . '.asset.php'));
        if (AssetConfigInterface::TYPE_STYLE) {
            $asset_info[] = \preg_replace('/style-(\\S+?)\\.css$/', '$1.asset.php', $asset_path);
        }
        if (\file_exists($asset_path)) {
            $content_url = content_url();
            $config->set_src(\str_replace(WP_CONTENT_DIR, $content_url, $asset_path));
        }
        $info = null;
        if (\file_exists($assets_info)) {
            $infos = (require $assets_info);
            if (isset($infos[$asset_path])) {
                $info = $infos[$asset_path];
            }
        } else {
            foreach ($asset_info as $asset_info_item) {
                if (\file_exists($asset_info_item)) {
                    $info = (require $asset_info_item);
                    break;
                }
            }
        }
        if (!empty($info)) {
            $config->set_version($info['version'] ?? $config->get_version() ?? '');
            if ($config->get_type() === AssetConfigInterface::TYPE_SCRIPT) {
                $config->merge_dependencies($info['dependencies'] ?? array());
            }
        }
        return $this->factory($config);
    }
    public function factory(AssetConfigInterface $config)
    {
        return new Asset($config);
    }
    public function login_wp_script(string $asset_path, $args = null) : Asset
    {
        return $this->wp_script($asset_path, (new AssetConfig($args))->set_is_login(\true));
    }
    public function admin_url(string $src, $args = null) : Asset
    {
        return $this->url($src, (new AssetConfig($args))->set_is_admin(\true));
    }
    public function url(string $src, $args = null) : Asset
    {
        return $this->factory((new AssetConfig($args))->set_src($src));
    }
    public function login_url(string $src, $args = null) : Asset
    {
        return $this->url($src, (new AssetConfig($args))->set_is_login(\true));
    }
    public function admin_theme(string $asset_path, $args = null) : Asset
    {
        return $this->theme($asset_path, (new AssetConfig($args))->set_is_admin(\true));
    }
    public function theme(string $path, $args = null) : Asset
    {
        return $this->factory((new AssetConfig($args))->set_src(get_theme_file_uri($path)));
    }
    public function login_theme(string $asset_path, $args = null) : Asset
    {
        return $this->theme($asset_path, (new AssetConfig($args))->set_is_login(\true));
    }
    public function admin_parent_theme(string $asset_path, $args = null) : Asset
    {
        return $this->parent_theme($asset_path, (new AssetConfig($args))->set_is_admin(\true));
    }
    public function parent_theme(string $path, $args = null) : Asset
    {
        return $this->factory((new AssetConfig($args))->set_src(wp_normalize_path(get_template_directory_uri() . '/' . $path)));
    }
    public function login_parent_theme(string $asset_path, $args = null) : Asset
    {
        return $this->parent_theme($asset_path, (new AssetConfig($args))->set_is_login(\true));
    }
}
