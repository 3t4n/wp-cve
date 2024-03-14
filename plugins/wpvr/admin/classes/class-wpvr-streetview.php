<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing streetview content on Setup metabox
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_StreetView {

    /**
     * Instance of WPVR_Validator class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $validator;

    function __construct()
    {
        $this->validator = new WPVR_Validator();
    }


    /**
     * Render shortcode while post has streetview data
     * 
     * @param array $postdata
     * 
     * @return array 
     * @since 8.0.0
     */
    public function render_streetview_shortcode($postdata, $width, $height)
    {
      if (empty($width)) {
        $width = '600px';
      }
      if (empty($height)) {
          $height = '400px';
      }
      $streetviewurl = $postdata['streetviewurl'];
      $html = '';
      $html .= '<div class="vr-streetview" style="text-align: center; max-width:100%; width:'.$width.'; height:'.$height.'; margin: 0 auto;">';
      $html .= '<iframe src="'.$streetviewurl.'" frameborder="0" style="border:0; width:100px; height:100%;" allowfullscreen=""></iframe>';
      $html .= '</div>';
      return $html;
    }

}