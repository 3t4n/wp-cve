<?php
/**
 * Plugin info metabox
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: PluginInfo.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package  IfwPsn_Wp
 */
class IfwPsn_Wp_Plugin_Metabox_PluginInfo extends IfwPsn_Wp_Plugin_Metabox_Abstract
{
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initId()
     */
    protected function _initId()
    {
        return 'plugin_info';
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initTitle()
     */
    protected function _initTitle()
    {
        return __('Plugin Info', 'ifw');
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initPriority()
     */
    protected function _initPriority()
    {
        return 'core';
    }


    /**
     * @return IfwPsn_Wp_Ajax_Response_Abstract
     */
    public function render()
    {
        if ($this->_pm->hasPremium() && $this->_pm->isPremium()) {
            $this->_addPremiumBlock();
        }
        $this->_addVersionBlock();
        $this->_addConnectBlock();
        $this->_addHelpBlock();

        $tpl = IfwPsn_Wp_Tpl::getInstance($this->_pm);

        $html = '';
        foreach ($this->_infoBlocks as $block) {
            $params = array(
                'id' => $block['id'],
                'label' => $block['label'],
                'content' => $block['content'],
                'iconClass' => $block['iconClass'],
            );
            $html .= $tpl->render('metabox_plugininfo_block.html.twig', $params);
        }

        $html .= '<p class="ifw-made-with-heart">This plugin was made with <img src="'. $this->_pm->getEnv()->getSkinUrl().
            'icons/heart.png" /> by <a href="http://www.ifeelweb.de/" target="_blank">ifeelweb.de</a></p>';

        echo $html;
    }


    /**
     * Adds a content block
     *
     * @param $id
     * @param string $label
     * @param string $content
     * @param string $iconClass
     */
    public function addBlock($id, $label, $content, $iconClass)
    {
        do_action($this->_pm->getAbbrLower() . '_plugininfo_before_'. $id, $this);

        $this->_infoBlocks[] = array(
            'id' => $id,
            'label' => $label,
            'content' => $content,
            'iconClass' => $iconClass
        );

        do_action($this->_pm->getAbbrLower() . '_plugininfo_after_'. $id, $this);
    }

    protected function _addPremiumBlock()
    {
        $content = __('You are using the premium version.', 'ifw') . ' <span class="dashicons dashicons-thumbs-up"></span>';

        $content = strtr($content, array(
            'target="_blank"' => 'target="_blank" class="ifw-external-link"',
        ));

        $content = apply_filters($this->_pm->getAbbrLower() . '_plugin_info_premium', $content);

        $icon = 'star-filled';

        $this->addBlock('premium',
            __('Premium', 'ifw'),
            $content,
            $icon);
    }

    protected function _addVersionBlock()
    {
        $content = $this->_pm->getEnv()->getVersion();

        if (isset($this->_pm->getConfig()->plugin->welcomePageAdminUrl)) {
            $content .= sprintf('<ul><li><a href="%s">%s</a></li>', admin_url($this->_pm->getConfig()->plugin->welcomePageAdminUrl), __('Welcome Page', 'ifw'));
        }
        if (isset($this->_pm->getConfig()->plugin->changelogUrl)) {
            $content .= sprintf('<li><a href="%s" target="_blank" class="ifw-external-link">Changelog</a>', $this->_pm->getConfig()->plugin->changelogUrl);
        }

        $content = strtr($content, array(
            'target="_blank"' => 'target="_blank" class="ifw-external-link"',
        ));

        $content = apply_filters($this->_pm->getAbbrLower() . '_plugin_info_version', $content);

        $icon = 'editor-code';

        $this->addBlock('version',
            __('Version', 'ifw'),
            $content,
            $icon);
    }

    protected function _addConnectBlock()
    {
        $content = '';

        $content .= '<a href="https://twitter.com/ifeelwebde" target="_blank"><span class="dashicons dashicons-twitter"></span> Twitter</a>';

        $content = apply_filters($this->_pm->getAbbrLower() . '_plugin_info_connect', $content);

        $icon = 'share';

        $this->addBlock('connect',
            __('Connect', 'ifw'),
            $content,
            $icon);
    }

    protected function _addHelpBlock()
    {
        $content = '';

        if (!empty($this->_pm->getConfig()->plugin->docUrl)) {
            $content .= sprintf('<a href="%s" target="_blank">%s</a>', $this->_pm->getConfig()->plugin->docUrl, __('Documentation', 'ifw')) . '<br>';
        }
        if (!empty($this->_pm->getConfig()->plugin->faqUrl)) {
            $content .= sprintf('<a href="%s" target="_blank">FAQ</a>', $this->_pm->getConfig()->plugin->faqUrl) . '<br>';
        }

        $homepage = $this->_pm->getEnv()->getHomepage();
        $content .= sprintf('<a href="%s" target="_blank" class="ifw-external-link">Plugin Homepage</a>', $homepage);
        $premiumUrl = $this->_pm->getConfig()->plugin->premiumUrl;
        if (!empty($premiumUrl) && $premiumUrl != $homepage) {
            $content .= '<br>' . sprintf(__('Visit the <a href="%s" target="_blank">premium homepage</a> for the latest news.', 'ifw'), $premiumUrl);
        }

        $content = strtr($content, array(
            'target="_blank"' => 'target="_blank" class="ifw-external-link"',
        ));

        $content = apply_filters($this->_pm->getAbbrLower() . '_plugin_info_help', $content);

        $icon = 'sos';

        $this->addBlock('help',
            __('Need help?', 'ifw'),
            $content,
            $icon);
    }
}
