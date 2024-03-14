<?php

namespace Fab\Helper;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 * setComponent
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

use Fab\Metabox\FABMetaboxLocation;
use Fab\Metabox\FABMetaboxSetting;
use Fab\Metabox\FABMetaboxDesign;
use Fab\Metabox\FABMetaboxTrigger;
use Fab\Module\FABModuleAuthLogin;
use Fab\Module\FABModuleAuthLogout;
use Fab\Module\FABModuleSearch;

class FABModal {

    /**
     * @access   protected
     * @var      int    $ID    ID
     */
    protected $ID;

    /**
     * @access   protected
     * @var      array    $theme    layout
     */
    protected $theme = array();

    /**
     * @access   protected
     * @var      array    $layout    layout
     */
    protected $layout = array();

    /**
     * @access   protected
     * @var      array    $navigation    trigger
     */
    protected $navigation = array();

    /**
     * @param int $ID
     */
    public function __construct(int $ID)
    {
        /** Get Plugin Instance */
        $plugin   = \Fab\Plugin::getInstance();
        $this->WP = $plugin->getWP();
        $this->Helper = $plugin->getHelper();

        /** Construct Class */
        $this->ID = $ID;

        /** Construct Modal Data */
        $type = $this->WP->get_post_meta( $this->ID, FABMetaboxSetting::$post_metas['type']['meta_key'], true );
        $nonModalType = array('anchor_link', 'auth_logout', 'latest_post_link', 'link', 'print');
        if(!in_array($type, $nonModalType)){
            /** Layout */
            $default = FABMetaboxDesign::$input['fab_modal_layout']['default'];
            $this->layout = $this->WP->get_post_meta( $this->ID, FABMetaboxDesign::$post_metas['modal_layout']['meta_key'], true );
            $this->layout = ( $this->layout ) ? $this->Helper->ArrayMergeRecursive( (array) $default, (array) $this->layout ) : $default;

            /** Navigation */
            $default = FABMetaboxDesign::$input['fab_modal_navigation']['default'];
            $this->navigation = $this->WP->get_post_meta( $this->ID, FABMetaboxDesign::$post_metas['modal_navigation']['meta_key'], true );
            $this->navigation = ( $this->navigation ) ? $this->Helper->ArrayMergeRecursive( (array) $default, (array) $this->navigation ) : $default;

            /** Theme */
            $default = FABMetaboxDesign::$input['fab_modal_theme']['default'];
            $this->theme = $this->WP->get_post_meta( $this->ID, FABMetaboxDesign::$post_metas['modal_theme']['meta_key'], true );
            $this->theme = ( $this->theme ) ? $this->Helper->ArrayMergeRecursive( (array) $default, (array) $this->theme ) : $default;
        }

    }

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     */
    public function setID(int $ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return array
     */
    public function getTheme(): array
    {
        return $this->theme;
    }

    /**
     * @param array $theme
     */
    public function setTheme(array $theme): void
    {
        $this->theme = $theme;
    }

    /**
     * @return array
     */
    public function getLayout(): array
    {
        return $this->layout;
    }

    /**
     * @param array $layout
     */
    public function setLayout(array $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * @return array
     */
    public function getNavigation(): array
    {
        return $this->navigation;
    }

    /**
     * @param array $navigation
     */
    public function setNavigation(array $navigation): void
    {
        $this->navigation = $navigation;
    }

    /** Grab All Assigned Variables */
    public function getVars() {
        return get_object_vars( $this );
    }

    /** Get Padding String (1rem 1rem 1rem 1rem) */
    public function getSizing( $sizingType = 'padding' ){
        $sizing = $this->getLayout()['content'][$sizingType];
        return sprintf('%s%s %s%s %s%s %s%s',
            $sizing['top'], $sizing['sizing'],
            $sizing['right'], $sizing['sizing'],
            $sizing['bottom'], $sizing['sizing'],
            $sizing['left'], $sizing['sizing']
        );
    }

}
