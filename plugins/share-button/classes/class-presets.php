<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

/** Class for loading Social Share Presets. Presets are in JSON format */ 
class Presets
{

  protected $presets = array();


  public function __construct()
  {
      $this->presets = apply_filters('mbsocial/presets/', $this->setPresets());
      $this->presets_labels = apply_filters('mbsocail/presets/labels', $this->setPresetsLabels() );
  }

  public function setPresets()
  {
    $presets = array(
        'round' => '
        {"style":{"mbs-style":"round","mbs-width":"55","mbs-height":"55"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"12","font_label_style":"normal","font_label_weight":"normal","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#fff","color_hover":"#fff","button_spacing":"10","ignore_container":0},"effect":{"effect_type":"transform","scale":"120"},"count":{"font_count_size":"16"}}',
        'round_flip' => '{"style":{"mbs-style":"round","mbs-width":"55","mbs-height":"55"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"12","font_label_style":"normal","font_label_weight":"normal","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#fff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"flip"},"count":{"font_count_size":"16"}}',
        'square' => '
{"style":{"mbs-style":"square","mbs-width":"45","mbs-height":"45"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"13","font_label_style":"normal","font_label_weight":"normal","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"none"}}',
        'square_hover' => '
{"style":{"mbs-style":"square","mbs-width":"55","mbs-height":"55"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"13","font_label_style":"normal","font_label_weight":"normal","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"hover"},"count":{"font_count_size":"16"}}',
        'square_drop' => '
{"style":{"mbs-style":"square","mbs-width":"55","mbs-height":"55"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"9","font_label_style":"normal","font_label_weight":"normal","font_label_upper":"uppercase","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"drop"},"count":{"font_count_size":"14"}}',

        'square_lift' => '
{"style":{"mbs-style":"square","mbs-width":"55","mbs-height":"55"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"10","font_label_style":"normal","font_label_weight":"bold","font_label_upper":"uppercase","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"lift"},"count":{"font_count_size":"12"}}
',
        'square_shift' => '{"style":{"mbs-style":"square","mbs-width":"55","mbs-height":"55"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"10","font_label_style":"normal","font_label_weight":"normal","font_label_upper":"uppercase","font_icon_size":"24","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"shift"},"count":{"font_count_size":"10"}}',

        'square_flip' => '
{"style":{"mbs-style":"square","mbs-width":"40","mbs-height":"40"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"12","font_label_style":"normal","font_label_weight":"normal","font_label_upper":"none","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"flip"},"count":{"font_count_size":"12"}}',

        'rectangle_flip' => '{"style":{"mbs-style":"square","mbs-width":"90","mbs-height":"35"},"layout":{"margin_left":"5","margin_right":"0","margin_top":"0","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"13","font_label_style":"normal","font_label_weight":"bold","font_label_upper":"uppercase","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"bold","use_background":"1","background_color":"#000","background_color_hover":"#000","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"flip","scale":"120"},"count":{"font_count_size":"15"}}',

        'stretch' => '{"style":{"mbs-style":"square","mbs-width":"55","mbs-height":"55"},"layout":{"margin_left":"0","margin_right":"0","margin_top":"36","margin_bottom":"0","orientation":"auto","font":"","font_label_size":"13","font_label_style":"normal","font_label_weight":"normal","font_label_upper":"uppercase","font_icon_size":"20","font_icon_style":"normal","font_icon_weight":"normal","use_background":"1","background_color":"#666666","background_color_hover":"#31f41f","color":"#ffffff","color_hover":"#fff","button_spacing":"5","ignore_container":0},"effect":{"effect_type":"stretch","scale":"120"},"count":{"font_count_size":"13"}}',
    );

    return $presets;

  }

  public function setPresetsLabels()
  {
    $labels = array(
        'round' => __("Simple Round with Hover", 'mbsocial'),
        'round_flip' => __('Round Buttons with Flip effect', 'mbsocial'),
        'square' => __('Square small without effect', 'mbsocial'),
        'square_hover' => __("Square with Hover Effect", 'mbsocial'),
        'square_drop' => __('Square with drop effect', 'mbsocial'),
        'square_lift' => __('Square with lift effect', 'mbsocial'),
        'square_shift' => __('Square with shift effect', 'mbsocial'),
        'square_flip' => __('Square small with flip effect', 'mbsocial'),
        'rectangle_flip' => __('Rectangle with flip effect', 'mbsocial'),
        'stretch' => __('Stretch buttons', 'mbsocial'),
      );

    return $labels;
  }

  public function get()
  {
     return $this->presets;
  }

  public function getLabels()
  {
    return $this->presets_labels;
  }
}
