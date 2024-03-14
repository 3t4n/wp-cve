<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing Hotspot tab content on Setup meta box
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Hotspot {

    function __construct()
    {
        /**
         * TODO hotspot setting class initiate 
         */
    }

    /**
     * Render hotspot for default repeater item
     * 
     * @param $s value of scene html id
     * @param $h value of hotspot html id
     * 
     * @return void
     * @since 8.0.0
     */
    public function render_hotspot($s, $h)
    {
        ob_start();
        ?>
        
        <nav class="rex-pano-tab-nav rex-pano-nav-menu hotspot-nav">
            <?php $this->render_hotspot_nav($s, $h); ?>
        </nav>

        <div data-repeater-list="hotspot-list" class="rex-pano-tab-content">
            <div data-repeater-item class="single-hotspot rex-pano-tab active clearfix" id="scene-<?php echo $s; ?>-hotspot-<?php echo $h; ?>">
                <?php $this->render_hotspot_repeater_item(); ?>
            </div>
        </div>

        <?php
        ob_end_flush();
    }

    /**
     * Render hotspot which has panaromic data
     * 
     * @param array $pano_hotspots value of hotspot list
     * @param $s value of scene html id
     * 
     * @return void
     * @since 8.0.0
     */
    public function render_hotspot_with_panodata($pano_hotspots, $s)
    {
        ob_start();
        ?>
        <nav class="rex-pano-tab-nav rex-pano-nav-menu hotspot-nav">
            <?php $this->render_hotspot_nav_with_panodata($pano_hotspots, $s); ?>
        </nav>

        <div data-repeater-list="hotspot-list" class="rex-pano-tab-content">
            <?php $this->render_hotspot_repeater_item_with_panodata($pano_hotspots, $s); ?>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render hotspot nav for default scene
     * 
     * @param int $s
     * @param int $h
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_hotspot_nav($s, $h)
    {
        ob_start();
        ?>
            <ul>    
                <li class="active"><span data-index="1" data-href="#scene-<?php echo $s; ?>-hotspot-<?php echo $h; ?>"><i class="far fa-dot-circle"></i></span></li>
                <li class="add" data-repeater-create><span><i class="fa fa-plus-circle"></i> </span></li>
            </ul>
        <?php
        ob_end_flush();
    }


    /**
     * Render hotspot repeater item for default scene
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_hotspot_repeater_item()
    {
        $postdata = WPVR_Meta_Field::get_primary_meta_fields();
        $pano_hotspot = $postdata['panodata']['scene-list'][0]['hotspot-list'][0];
        ob_start();
        ?>
        <h6 class="title"><i class="fa fa-cog"></i> <?php echo __('Hotspot Setting','wpvr') ?> </h6>

        <div class="wrapper">
            <?php WPVR_Meta_Field::render_hotspot_setting_left_fields($pano_hotspot); ?>
        </div>

        <div class="hotspot-type hotspot-setting">
            <?php WPVR_Meta_Field::render_hotspot_setting_right_fields(); ?>
        </div>
        <!-- Hotspot type End -->
        <button data-repeater-delete title="Delete Hotspot" type="button" class="delete-hotspot"><i class="far fa-trash-alt"></i></button>
        <?php
        ob_end_flush();
    }


    /**
     * Render hotspot nav if scene has hotspot data
     * 
     * @param array $pano_hotspots
     * @param int $s
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_hotspot_nav_with_panodata($pano_hotspots, $s)
    {
        ob_start();
        ?>
            <ul>
                <?php $j = 1;

                $firstvaluehotspot = reset($pano_hotspots);
                foreach ($pano_hotspots as $pano_hotspot) { ?>
                <li class="<?php if($pano_hotspot['hotspot-title'] == $firstvaluehotspot['hotspot-title']) { echo 'active'; } ?>"><span data-index="<?php echo $j;?>" data-href="#scene-<?php echo $s;?>-hotspot-<?php echo $j;?>"><i class="far fa-dot-circle"></i></span></li>
                <?php $j++; } ?>
                <li class="add" data-repeater-create><span><i class="fa fa-plus-circle"></i></span></li>
            </ul>
        <?php
        ob_end_flush();
    }


    /**
     * Render hotspot repeater item if scene has panodata
     * 
     * @param array $pano_hotspots
     * @param int $s
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_hotspot_repeater_item_with_panodata($pano_hotspots, $s)
    {
        $h = 1;
        $firstvaluehotspotset = reset($pano_hotspots);
        $is_wpvr_premium = apply_filters('is_wpvr_premium', false);
        foreach ($pano_hotspots as $pano_hotspot) { ob_start(); ?>
            <div data-repeater-item class="single-hotspot rex-pano-tab clearfix <?php if($pano_hotspot['hotspot-title'] == $firstvaluehotspotset['hotspot-title']) { echo 'active'; }  ?>" id="scene-<?php echo $s;?>-hotspot-<?php echo $h;?>">

                <h6 class="title"><i class="fa fa-cog"></i> <?php echo __('Hotspot Setting','wpvr') ?> </h6>

                <div class="wrapper">
                    <?php WPVR_Meta_Field::render_hotspot_setting_left_fields($pano_hotspot); ?>
                </div>

                <!-- Hotspot type -->

            <?php if ($pano_hotspot['hotspot-type'] == "info") { ?>
                <div class="hotspot-type hotspot-setting">
                    <?php WPVR_Meta_Field::render_hotspot_setting_info_fields($pano_hotspot); ?>
                </div>
            <?php }elseif($pano_hotspot['hotspot-type'] == "fluent_form" && $is_wpvr_premium == 1){ ?>
                <div class="hotspot-type hotspot-setting">
                    <?php WPVR_Meta_Field::render_hotspot_setting_fluent_form_fields($pano_hotspot); ?>
                </div>
            <?php }elseif($pano_hotspot['hotspot-type'] == "wc_product" && $is_wpvr_premium == 1){ ?>
                <div class="hotspot-type hotspot-setting">
                    <?php WPVR_Meta_Field::render_hotspot_setting_wc_product_fields($pano_hotspot); ?>
                </div>
            <?php } else { ?>
                <div class="hotspot-type hotspot-setting">
                    <?php WPVR_Meta_Field::render_hotspot_setting_scene_fields($pano_hotspot); ?>
                </div>
            <?php } ?>
                <!-- Hotspot type End -->
                <button data-repeater-delete type="button" title="Delete Hotspot" class="delete-hotspot"><i class="far fa-trash-alt"></i></button>
            </div>
        <?php ob_end_flush(); $h++; }
    }
}