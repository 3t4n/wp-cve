<?php

namespace WcMipConnector\View\Assets;

use WcMipConnector\Manager\ConfigurationOptionManager;

defined('ABSPATH') || exit;

class Assets
{
    /**
     * @param string $fileName
     * @return string
     */
    public function getCssAsset(string $fileName): string
    {
        return plugin_dir_url(__FILE__).'../../../app/assets/css/'.$fileName;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getImageAsset(string $fileName): string
    {
        return plugin_dir_url(__FILE__).'../../../app/assets/image/'.$fileName;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getJsAsset(string $fileName): string
    {
        return plugin_dir_url(__FILE__).'../../../app/assets/js/'.$fileName;
    }

    public function getHeaderAssets(): void
    {
        if (is_admin()) {
            wp_enqueue_style(
                'font-awesome.min.css',
                $this->getCssAsset('font-awesome.min.css'),
                [],
                '4.7.0'
            );
            wp_enqueue_style(
                'viewManage.css',
                $this->getCssAsset('viewManage.css'),
                [],
                ConfigurationOptionManager::getPluginFilesVersion()
            );
        }
    }

    public function getHeaderOauthAssets(): void
    {
        wp_enqueue_style(
            'font-awesome.min.css',
            $this->getCssAsset('font-awesome.min.css'),
            [],
            '4.7.0'
        );
        wp_enqueue_style(
            'oauth.css',
            $this->getCssAsset('oauth.css'),
            [],
            ConfigurationOptionManager::getPluginFilesVersion()
        );
    }

    public function getFooterAssets(): void
    {
        if (is_admin()) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(
                'viewManager.js',
                $this->getJsAsset('viewManager.js'),
                [],
                ConfigurationOptionManager::getPluginFilesVersion()
            );
        }
    }
}