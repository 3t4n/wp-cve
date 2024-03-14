<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeSlideoTransition
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeSlideoTransition extends WjsslDocumentNode
{
    public $x;

    public $y;

    // <summary>
    // Opacity
    // </summary>
    public $o;

    // <summary>
    // Index
    // </summary>
    public $i;

    // <summary>
    // Rotate
    // </summary>
    public $r;

    // <summary>
    // RotateX
    // </summary>
    public $rX;

    // <summary>
    // RotateY
    // </summary>
    public $rY;

    // <summary>
    // ScaleX
    // </summary>
    public $sX;

    // <summary>
    // ScaleY
    // </summary>
    public $sY;

    // <summary>
    // TranslateZ
    // </summary>
    public $tZ;

    // <summary>
    // SkewX
    // </summary>
    public $kX;

    // <summary>
    // SkewY
    // </summary>
    public $kY;

    // <summary>
    // Clip Horizontal
    // </summary>
    public $cHor;

    // <summary>
    // Clip Vertical
    // </summary>
    public $cVer;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'x', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'y', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'o', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'i', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'r', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'rX', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'rY', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'sX', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'sY', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'tZ', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'kX', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'kY', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'cHor', 'WjsslDesignTimeTransitionFactorClipHor', true);
        $this->deserialize_object($object_vars, 'cVer', 'WjsslDesignTimeTransitionFactorClipVer', true);
    }
}

