<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: PluginInfoAjax.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
class IfwPsn_Wp_Plugin_Metabox_PluginInfoAjax extends IfwPsn_Wp_Ajax_Request
{
    public $action = 'load-plugin-info';

    /**
     * Stores info content blocks
     * @var array
     */
    protected $_infoBlocks = array();


    /**
     * @var IfwPsn_Wp_Plugin_Manager
     */
    protected $_pm;



    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     */
    function __construct(IfwPsn_Wp_Plugin_Manager $pm)
    {
        $this->_pm = $pm;
        $this->action .= '-' . $this->_pm->getAbbrLower();
    }

    /**
     * @return IfwPsn_Wp_Ajax_Response_Abstract
     */
    public function getResponse()
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
                'label' => $block['label'],
                'content' => $block['content'],
                'iconClass' => $block['iconClass'],
            );
            $html .= $tpl->render('metabox_plugininfo_block.html.twig', $params);
        }

        $html .= '<p class="ifw-made-with-heart">This plugin was made with <img src="'. $this->_pm->getEnv()->getSkinUrl().
            'icons/heart.png" /> by <a href="http://www.ifeelweb.de/" target="_blank">ifeelweb.de</a></p>';
        $success = true;

        $response = new IfwPsn_Wp_Ajax_Response_Json($success);
        $response->addData('html', $html);

        return $response;
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

        if (isset($this->_pm->getConfig()->plugin->changelogUrl)) {
            $content .= '&nbsp;&nbsp;(<a href="' . $this->_pm->getConfig()->plugin->changelogUrl .'" target="_blank" class="ifw-external-link">Changelog</a>)';
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
        $homepage = $this->_pm->getEnv()->getHomepage();
        $premiumUrl = $this->_pm->getConfig()->plugin->premiumUrl;

        $content = '';
        $content .= sprintf('<a href="%s" target="_blank" class="ifw-external-link">Plugin Homepage</a>', $homepage);

        if (!empty($premiumUrl) && $premiumUrl != $homepage) {
            $content .= sprintf(__('Visit the <a href="%s" target="_blank">premium homepage</a> for the latest news.', 'ifw'), $premiumUrl);
        }

        $content .= '<br><a href="https://twitter.com/ifeelwebde" target="_blank"><span class="dashicons dashicons-twitter"></span> Twitter</a>';

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
