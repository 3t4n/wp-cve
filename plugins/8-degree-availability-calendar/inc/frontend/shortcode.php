<?php
defined('ABSPATH') or die("No script kiddies please!");
$edac_data = $this->edac_settings;
$booked_dates = $edac_data['booked_date'];
$booked_dates = implode(',',$booked_dates);
$edac_data['edac_layout'] = isset($atts['layout'])?$atts['layout']:$edac_data['edac_layout'];
//  echo '<pre>';
//  print_r($edac_data);
//  echo '</pre>';
?>
<div class="edac-av-calendar-wrap">
    <?php if($edac_data['edac_layout']==1){?>
        <div class="edac-av-calendar edac-calendar"></div>
    <?php }else{?>
        <div class="edac-sec-av-calendar edac-sec-calendar"></div>
    <?php }?>
    <?php if($edac_data['edac_legend']){?>
        <div class="edac-legend-wrap">
            <span class="edac-legend-color-box <?php if($edac_data['edac_layout']==1){echo 'edac-legend-box';}else{echo 'edac-sec-legend-box';}?>"><?php if($edac_data['edac_layout']==2){ echo '01';}?></span><span class="edac-legend-text"><?php echo esc_attr_e($edac_data['edac_legend_text'],'edac-plugin')?></span>
        </div>
    <?php }?>
    <div class="edac-hidden-field">
        <input type="hidden" class="edac-dates" value="<?php echo $booked_dates; ?>"/>
        <input type="hidden" class="edac-date" data-from-date="<?php echo esc_attr($edac_data['edac_from']);?>" data-to-date="<?php echo esc_attr($edac_data['edac_to']);?>" data-language="<?php echo esc_attr($edac_data['edac_language']);?>" />
    </div>
</div>
<style>
    <?php if($edac_data['edac_layout']==1){ ?>
        .edac-calendar .event a, .edac-legend-box{
            background: <?php echo $edac_data['edac_unavailable_color'];?>;
        }
    <?php }else{ ?>
        .edac-sec-calendar .event a, .edac-sec-legend-box{
            text-decoration: line-through;
        }
    <?php } ?>
</style>