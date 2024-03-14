<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslSkin
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslSkin extends WjsslDocumentNode
{
    public $id;

    public $name;

    public $note;

    public $css;

    public $html;

    public $itemHtml;

    /**
     * @var WjsslThemeDefaultValue
     */
    public $defaultValue;

    public $itemFullDimension;

    /**
     * @param array $object_vars
     */
    protected function deserialize(&$object_vars) {
        parent::deserialize_object($object_vars, 'defaultValue', 'WjsslThemeDefaultValue');
        parent::deserialize($object_vars);
    }
}

class WjsslThemeDefaultValue extends WjsslDocumentNode
{
    public $itemWidth;
    public $itemHeight;
    public $cntrBgColor;
}
