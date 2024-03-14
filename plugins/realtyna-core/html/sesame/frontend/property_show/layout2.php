<?php
/**
 * Overriden file of WPL Plugin to make it customized for Sesame theme. 
 * This view is showing WPL Single Property Page.
 * @author Realtyna Inc.
 */
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$prp_type           = isset($this->wpl_properties['current']['materials']['property_type']['value']) ? $this->wpl_properties['current']['materials']['property_type']['value'] : '';
$prp_listings       = isset($this->wpl_properties['current']['materials']['listing']['value']) ? $this->wpl_properties['current']['materials']['listing']['value'] : '';
$build_up_area      = isset($this->wpl_properties['current']['materials']['living_area']['value']) ? $this->wpl_properties['current']['materials']['living_area']['value'] : (isset($this->wpl_properties['current']['materials']['lot_area']['value']) ? $this->wpl_properties['current']['materials']['lot_area']['value'] : '');
$build_up_area_name = isset($this->wpl_properties['current']['materials']['living_area']['value']) ? $this->wpl_properties['current']['materials']['living_area']['name'] : (isset($this->wpl_properties['current']['materials']['lot_area']['value']) ? $this->wpl_properties['current']['materials']['lot_area']['name'] : '');
$bedroom            = isset($this->wpl_properties['current']['materials']['bedrooms']['value']) ? $this->wpl_properties['current']['materials']['bedrooms']['value'] : '';
$bathroom           = isset($this->wpl_properties['current']['materials']['bathrooms']['value']) ? $this->wpl_properties['current']['materials']['bathrooms']['value'] : '';
$listing_id         = isset($this->wpl_properties['current']['materials']['mls_id']['value']) ? $this->wpl_properties['current']['materials']['mls_id']['value'] : '';
$price              = isset($this->wpl_properties['current']['materials']['price']['value']) ? $this->wpl_properties['current']['materials']['price']['value'] : '';
$price_type         = isset($this->wpl_properties['current']['materials']['price_period']['value']) ? $this->wpl_properties['current']['materials']['price_period']['value'] : '';
$location_string 	= (isset($this->wpl_properties['current']['location_text']) and $this->location_visibility === true) ? $this->wpl_properties['current']['location_text'] : $this->location_visibility;
$prp_title          = isset($this->wpl_properties['current']['property_title']) ? $this->wpl_properties['current']['property_title'] : '';
$visits             = wpl_property::get_property_stats_item($this->pid, 'visit_time');
$add_date           = isset($this->wpl_properties['current']['raw']['add_date']) ? $this->wpl_properties['current']['raw']['add_date'] : '0000-00-00 00:00:00';

/** Calculate how many vists per days **/
$days = 0;
if($add_date != '0000-00-00 00:00:00')
{
    $datetime1 = strtotime($add_date);
    $datetime2 = time();
    $interval = abs($datetime2 - $datetime1);
    $days = round($interval / 60 / 60 / 24);
}

$pshow_gallery_activities = wpl_activity::get_activities('pshow_gallery', 1);
$pshow_googlemap_activities = wpl_activity::get_activities('pshow_googlemap', 1, '', 'loadObject');
$pshow_walkscore_activities = wpl_activity::get_activities('pshow_walkscore', 1);
$pshow_bingmap_activities = wpl_activity::get_activities('pshow_bingmap', 1, '', 'loadObject');

$this->pshow_googlemap_activity_id = isset($pshow_googlemap_activities->id) ? $pshow_googlemap_activities->id : NULL;
$this->pshow_bingmap_activity_id = isset($pshow_bingmap_activities->id) ? $pshow_bingmap_activities->id : NULL;

/** video tab for showing videos **/
$pshow_video_activities = count(wpl_activity::get_activities('pshow_video', 1));
if(!isset($this->wpl_properties['current']['items']['video']) or (isset($this->wpl_properties['current']['items']['video']) and !count($this->wpl_properties['current']['items']['video']))) $pshow_video_activities = 0;

/** Import JS file **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_prp_show_layout2_container wpl_prp_show_container" id="wpl_prp_show_container">
    <div class="wpl_prp_container" id="wpl_prp_container<?php echo esc_attr($this->pid); ?>" <?php echo $this->itemscope.' '.$this->itemtype_SingleFamilyResidence; ?>>
        <div class="wpl_prp_container_content_top clearfix">
            <?php /** listing result **/ wpl_activity::load_position('pshow_listing_results', array('wpl_properties'=>$this->wpl_properties)); ?>
        </div>

        <div class="wpl-row wpl-expanded wpl_prp_container_content">
            <div class="wpl-large-8 wpl-medium-8 wpl-small-12 wpl-column wpl_prp_container_content_left">

                <div class="wpl_prp_left_box wpl_prp_gallery">
                <?php if($pshow_gallery_activities): ?>
                        <?php /** load position gallery **/ wpl_activity::load_position('pshow_gallery', array('wpl_properties'=>$this->wpl_properties)); ?>
                <?php endif; ?>
                </div>

                <?php
                $i = 0;
                $details_boxes_num = count($this->wpl_properties['current']['rendered']);

                foreach($this->wpl_properties['current']['rendered'] as $values)
                {
                    /** skip for Basic details **/
                    if($values['self']['id']== 1) continue;

                    /** skip empty categories **/
                    if(!count($values['data'])) continue;

                    /** skip location if property address is hiden **/
                    if($values['self']['prefix'] == 'ad' and $this->location_visibility !== true) continue;

                    echo '<div class="wpl_prp_show_detail_boxes wpl_category_'.esc_attr($values['self']['id']).'">
                            <div class="wpl_prp_show_detail_boxes_title"><span>';
                              echo esc_html__($values['self']['name'], 'sesame');
                    echo '</span></div>
                        <div class="wpl-small-up-1 wpl-medium-up-1 wpl-large-up-'.esc_attr($this->fields_columns).' wpl_prp_show_detail_boxes_cont">';

                    foreach($values['data'] as $key => $value)
                    {
                        if(!isset($value['type'])) continue;

                        elseif($value['type'] == 'neighborhood')
                        {
                            echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="wpl-column rows neighborhood"><label>';
                             echo esc_html__($value['name'], 'sesame' );
                             echo (isset($value['distance']) ? '</label> <span class="'.$value['vehicle_type'].'">'. esc_html($value['distance']) .' '. esc_html__('Minutes','sesame'). '</span>':''). '</div>';
                        }
                        elseif($value['type'] == 'feature')
                        {
                            echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="wpl-column rows feature ';
                            if(!isset($value['values'][0])) echo ' single ';

                            echo '"><label>';
                            echo esc_html__($value['name'], 'sesame' );
                            echo '</label>';

                            if(isset($value['values'][0]))
                            {
                                $html = '';
                                echo ' : <span>';
                                foreach($value['values'] as $val) $html .= $val.' , ';
                                $html = rtrim($html, ', ');
                                echo $html;
                                echo '</span>';
                            }

                            echo '</div>';
                        }
                        elseif($value['type'] == 'locations' and isset($value['locations']) and is_array($value['locations']))
                        {
                            if(isset($value['settings']) and is_array($value['settings']))
                            {
                                foreach($value['settings'] as $ii=>$lvalue)
                                {
                                    if(isset($lvalue['enabled']) and !$lvalue['enabled']) continue;

                                    $lk = $value['keywords'][$ii];
                                    if(trim($lk) == '') continue;

                                    echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="wpl-column rows location '.esc_attr($value['keywords'][$ii]).'"><label>';
                                    echo esc_html__($lk, 'sesame' );
                                    echo ' : </label>';
                                    echo '<span>'.esc_html($value['locations'][$ii]).'</span>';
                                    echo '</div>';
                                }
                            }
                            else
                            {
                                foreach($value['locations'] as $ii=>$lvalue)
                                {
                                    echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="wpl-column rows location '.esc_attr($value['keywords'][$ii]).'"><label>';
                                    echo esc_html__($value['keywords'][$ii], 'sesame' );
                                    echo ' : </label>';
                                    echo '<span>'.esc_html($lvalue).'</span>';
                                    echo '</div>';
                                }
                            }
                        }
                        elseif($value['type'] == 'separator')
                        {
                            echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="wpl-column rows separator">';
                             echo esc_html__($value['name'], 'sesame' );
                             echo '</div>';
                        }
                        else
                            {
                                echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="wpl-column rows other"><label>';
                                echo esc_html__($value['name'], 'sesame' );
                                echo ' : </label><span>';
                                echo __((isset($value['value']) ? $value['value'] : ''), 'sesame' );
                                echo '</span></div>';
                            }
                    }

                    echo '</div></div>';
                    $i++;
                }
                ?>

                <?php
                $description_column = 'field_308';
                if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

                if(isset($this->wpl_properties['current']['data'][$description_column]) and $this->wpl_properties['current']['data'][$description_column]):
                    ?>
                    <div class="wpl_prp_show_detail_boxes wpl_category_description">
                        <div class="wpl_prp_show_detail_boxes_title">
                            <span>
                            <?php
                                echo esc_html__(wpl_flex::get_dbst_key('name', wpl_flex::get_dbst_id('field_308', $this->kind)), 'sesame');
                            ?>
                                
                            </span>
                        </div>
                        <div class="wpl_prp_show_detail_boxes_cont" <?php echo $this->itemprop_description; ?>>
                            <?php echo apply_filters('the_content', stripslashes($this->wpl_properties['current']['data'][$description_column])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($pshow_video_activities): ?>
                    <div class="wpl_prp_show_detail_boxes">
                        <div class="wpl_prp_show_detail_boxes_title">
                            <span><?php echo esc_html__('Video', 'sesame') ?></span>
                        </div>
                        <div class="wpl_prp_left_box">
                            <?php /** load position video **/ wpl_activity::load_position('pshow_video', array('wpl_properties'=>$this->wpl_properties)); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="wpl_prp_show_tabs wpl_prp_show_tabs-maps wpl_prp_left_box">
                    <div class="tabs_box">
                        <ul class="tabs clearfix">
                            <?php if($pshow_googlemap_activities and $this->location_visibility === true): ?>
                                <li><a href="#tabs-1" data-for="tabs-1" data-init-googlemap="1"><?php echo esc_html__('Google Map', 'sesame') ?></a></li>
                            <?php endif; ?>
                            <?php if($pshow_bingmap_activities and $this->location_visibility === true): ?>
                                <li><a href="#tabs-2" data-for="tabs-2" data-init-bingmap="1"><?php echo esc_html__('Birds Eye', 'sesame') ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="tabs_container">
                        <?php if($pshow_googlemap_activities and $this->location_visibility === true): ?>
                        <div id="tabs-1" class="tabs_contents">
                            <?php /** load position googlemap **/ wpl_activity::load_position('pshow_googlemap', array('wpl_properties'=>$this->wpl_properties)); ?>
                        </div>
                        <?php endif; ?>
                        <?php if($pshow_bingmap_activities and $this->location_visibility === true): ?>
                        <div id="tabs-2" class="tabs_contents">
                            <?php /** load position bingmap **/ wpl_activity::load_position('pshow_bingmap', array('wpl_properties'=>$this->wpl_properties)); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                $activities = wpl_activity::get_activities('pshow_contact');
                if($activities) echo '<div class="wpl_prp_show_position_contact">';
                foreach($activities as $activity)
                {
                    $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                    if(trim($content) == '') continue;

                    $activity_title =  explode(':', $activity->activity);
                    ?>
                    <div class="wpl_prp_position_contact_boxes <?php echo esc_attr($activity_title[0]); ?>">
                        <?php
                        if($activity->show_title and trim($activity->title) != '')
                        {
                            $activity_box_title = NULL;
                            $title_parts = explode(' ', __(stripslashes($activity->title), 'sesame'));
                            $i_part = 0;

                            foreach($title_parts as $title_part)
                            {
                                if($i_part == 0) $activity_box_title .= '<span>'.esc_html($title_part).'</span> ';
                                else $activity_box_title .= $title_part.' ';

                                $i_part++;
                            }

                            echo '<div class="wpl_prp_position_contact_title">'.$activity_box_title.'</div>';
                        }
                        ?>
                        <div class="wpl_prp_position_contact_boxes_content clearfix">
                            <?php echo $content; ?>
                        </div>
                    </div>
                    <?php
                }
                if($activities) echo '</div>';
                ?>
                
                <div class="wpl_prp_show_position3">
                    <?php
                    $activities = wpl_activity::get_activities('pshow_position3');
                    foreach($activities as $activity)
                    {
                        $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                        if(trim($content) == '') continue;

                        $activity_title =  explode(':', $activity->activity);
                        ?>
                        <div class="wpl_prp_position3_boxes <?php echo esc_attr($activity_title[0]); ?>">
                            <?php
                            if($activity->show_title and trim($activity->title) != '')
                            {
                                $activity_box_title = NULL;
                                $title_parts = explode(' ', __(stripslashes($activity->title), 'sesame'));
                                $i_part = 0;

                                foreach($title_parts as $title_part)
                                {
                                    if($i_part == 0) $activity_box_title .= '<span>'.esc_html($title_part).'</span> ';
                                    else $activity_box_title .= $title_part.' ';

                                    $i_part++;
                                }

                                echo '<div class="wpl_prp_position3_boxes_title">'.$activity_box_title.'</div>';
                            }
                            ?>
                            <div class="wpl_prp_position3_boxes_content clearfix">
                                <?php echo $content; ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="wpl-large-4 wpl-medium-4 wpl-small-12 wpl-column wpl_prp_container_content_right">
                <div class="wpl_prp_right_boxes details">
                    <div class="wpl_prp_right_boxes_content wpl-prp-basic-info">
                        <div class="wpl_prp_right_boxe_details_top clearfix">
                            <div class="wpl_prp_show_title">
                                <?php
                                echo '<h1 class="title_text" '.$this->itemprop_name.'>'.esc_html($prp_title).'</h1>';
                                echo '<h2 class="location_build_up" '.$this->itemprop_address.' '.$this->itemscope.' '.$this->itemtype_PostalAddress.'><span '.$this->itemprop_addressLocality.'>'. esc_html($location_string) .'</span></h2>';
                                ?>
                            </div>
                            <div class="wpl_prp_listing_icon_box">
                                <ul>
                                    <?php if(trim($bedroom) != ''): ?><li class="re-beds" <?php echo $this->itemprop_numberOfRooms.' '.$this->itemscope.' '.$this->itemtype_QuantitativeValue; ?>><span <?php echo $this->itemprop_name; ?> >
                                        <?php 
                                        echo esc_html__($this->wpl_properties['current']['materials']['bedrooms']['name'], 'sesame' );
                                        echo ' : </span> <span '.$this->itemprop_value.' class="value">'.esc_html($bedroom).'</span>'; ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(trim($bathroom) != ''): ?><li class="re-baths"<?php echo $this->itemprop_numberOfRooms.' '.$this->itemscope.' '.$this->itemtype_QuantitativeValue; ?>><span <?php echo $this->itemprop_name; ?>>
                                        <?php 
                                        echo esc_html__($this->wpl_properties['current']['materials']['bathrooms']['name'], 'sesame' );
                                        echo ' : </span> <span '.$this->itemprop_value.' class="value">'.esc_html($bathroom).'</span>'; ?>
                                    </li>
                                    <?php endif; ?>
                                    <?php if(trim($build_up_area) != ''): ?><li class="re-build_area"><span>
                                        <?php 
                                        echo esc_html__($build_up_area_name, 'sesame' );
                                        echo ': </span><span class="value" '.$this->itemprop_floorSize.' '.$this->itemscope.' '.$this->itemtype_QuantitativeValue.'><span class="value" '.$this->itemprop_value.'>'.esc_html($build_up_area).'</span></span>'; ?></li><?php endif; ?>
                                    <?php if(wpl_global::get_setting('show_plisting_visits')): ?>
                                        <li class="re-visits"><span><?php echo esc_html__('Visits', 'sesame').' : </span><span class="value">'.esc_html($visits).($days ? ' '.sprintf(esc_html__('in %d days', 'sesame'), $days) : '').'</span>'; ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="re-row">
                                <div class="re-col-md-7">
                                    <?php echo '<div class="wpl_prp_right_boxe_details_bot" '.$this->itemscope.' '.$this->itemtype_offer.'><div class="price_box" '.$this->itemprop_price.'>'.esc_html($price).'</div></div>'; ?>
                                    <?php if(trim($listing_id) != ''): ?><div class="wpl_prp_mls_id_box">
                                        <?php 
                                            echo esc_html__($this->wpl_properties['current']['materials']['mls_id']['name'], 'sesame' );
                                            echo '<span>#'.esc_html($listing_id).'</span>'; ?>
                                        </div><?php endif; ?>
                                </div>
                                <div class="re-col-md-5">
                                    <?php /** load QR Code **/ wpl_activity::load_position('pshow_qr_code', array('wpl_properties'=>$this->wpl_properties)); ?>
                                </div>
                            </div>
                        </div>
                        <?php

                        $values = $this->wpl_properties['current']['rendered'][1];
                        echo '<div class="wpl_prp_show_detail_boxes wpl_category_'.esc_attr($values['self']['id']).'">
                            <div class="wpl_prp_show_detail_boxes_title"><span>';
                            echo esc_html__($values['self']['name'], 'sesame');
                            echo '</span></div>
                            <div class="wpl_prp_show_detail_boxes_cont">';

                        foreach($values['data'] as $key => $value)
                        {
                            if(!isset($value['type'])) continue;

                            elseif($value['type'] == 'neighborhood')
                            {
                                echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="rows neighborhood">';
                                echo __($value['name'], 'sesame' );
                                echo (isset($value['distance']) ? ' <span class="'.esc_attr($value['vehicle_type']).'">'. $value['distance'] .' '. esc_html__('Minutes','sesame'). '</span>':''). '</div>';
                            }
                            elseif($value['type'] == 'feature')
                            {
                                echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="rows feature ';
                                if(!isset($value['values'][0])) echo ' single ';

                                echo '">';
                                echo __($value['name'], 'sesame' );

                                if(isset($value['values'][0]))
                                {
                                    $html = '';
                                    echo ' : <span>';
                                    foreach($value['values'] as $val) $html .= $val .', ';
                                    $html = rtrim($html, ', ');
                                    echo $html;
                                    echo '</span>';
                                }

                                echo '</div>';
                            }
                            elseif($value['type'] == 'locations' and isset($value['locations']) and is_array($value['locations']))
                            {
                                foreach($value['locations'] as $ii=>$lvalue)
                                {
                                    echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="rows location '.esc_attr($value['keywords'][$ii]).'">';
                                    echo __($value['keywords'][$ii], 'sesame' );
                                    echo ' : ';
                                    echo '<span>'.esc_html($lvalue).'</span>';
                                    echo '</div>';
                                }
                            }
                            elseif($value['type'] == 'separator')
                            {
                                echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="rows separator">';
                                echo esc_html__($value['name'], 'sesame' );
                                echo '</div>';
                            }
                            else
                            {
                                echo '<div id="wpl-dbst-show'.esc_attr($value['field_id']).'" class="rows other">';
                                echo __($value['name'], 'sesame' );
                                echo ' : <span>';
                                echo __((isset($value['value']) ? $value['value'] : ''), 'sesame' );
                                echo '</span></div>';
                            }
                        }

                        echo '</div></div>';
                        ?>
                    </div>

                    <div class="wpl_prp_show_position2">
                        <?php
                        $activities = wpl_activity::get_activities('pshow_position2');
                        foreach($activities as $activity)
                        {
                            $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                            if(trim($content) == '') continue;

                            $activity_title =  explode(':', $activity->activity);
                            ?>
                            <div class="wpl_prp_right_boxes <?php echo esc_attr($activity_title[0]); ?>">
                                <?php
                                if($activity->show_title and trim($activity->title) != '')
                                {
                                    $activity_box_title = NULL;
                                    $title_parts = explode(' ', __(stripslashes($activity->title), 'sesame'));
                                    $i_part = 0;

                                    foreach($title_parts as $title_part)
                                    {
                                        if($i_part == 0) $activity_box_title .= '<span>'.esc_html($title_part).'</span> ';
                                        else $activity_box_title .= $title_part.' ';

                                        $i_part++;
                                    }

                                    echo '<div class="wpl_prp_right_boxes_title">'.$activity_box_title.'</div>';
                                }
                                ?>
                                <div class="wpl_prp_right_boxes_content clearfix">
                                    <?php echo $content; ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                </div>

            </div>
            <div class="wpl-large-12 wpl-medium-12 wpl-small-12 wpl-column wpl_prp_show_bottom">
                <?php if($pshow_walkscore_activities): ?>
                    <div class="wpl_prp_show_walkscore">
                        <?php /** load position walkscore **/ wpl_activity::load_position('pshow_walkscore', array('wpl_properties'=>$this->wpl_properties)); ?>
                    </div>
                <?php endif; ?>
                <?php if(is_active_sidebar('wpl-pshow-bottom')) dynamic_sidebar('wpl-pshow-bottom'); ?>
            </div>
        </div>
    </div>
    <?php /** Don't remove this element **/ ?>
    <div id="wpl_pshow_lightbox_content_container" class="wpl-util-hidden"></div>
    <?php if(wpl_global::check_addon('membership') and wpl_session::get('wpl_dpr_popup')): ?>
        <a id="wpl_dpr_lightbox" class="wpl-util-hidden" data-realtyna-href="#wpl_pshow_lightbox_content_container" data-realtyna-lightbox-opts="title:<?php echo esc_attr__('Login to continue', 'sesame'); ?>"></a>
    <?php endif; ?>
    <?php if($this->show_signature): ?>
    <div class="wpl-powered-by-realtyna">
        <a href="https://realtyna.com/wpl-platform/ref/<?php echo esc_attr($this->affiliate_id); ?>/">
            <img src="<?php echo wpl_global::get_wpl_url().'assets/img/idx/powered-by-realtyna.png'; ?>" alt="Powered By Realtyna"/>
        </a>
    </div>
    <?php endif;?>
</div>

