<?php
/**
 * Save User Options
 * @param int $status
 * @param int $option
 * @param string $value
 * @return string
 */
function resads_table_set_option($status, $option, $value) {
    return $value;
}
add_filter('set-screen-option', 'resads_table_set_option', 10, 3);
/**
 * Stop output
 */
function resads_output_buffer() 
{
    ob_start();
}
add_action('init', 'resads_output_buffer');
/**
 * Generate the AdSpot
 * @param int $adspot_id
 * @return string
 */
function resads_adspot($adspot_id)
{
    if(is_numeric($adspot_id) && $adspot_id > 0)
    {
        if(file_exists(RESADS_CLASS_DIR . '/AdSpot.php') && file_exists(RESADS_CLASS_DIR . '/Resolution.php') && file_exists(RESADS_CLASS_DIR . '/AdManagement.php'))
        {          
            require_once RESADS_CLASS_DIR . '/Resolution.php';
            $Resolution = new ResAds_Resolution();
            $resolutions = $Resolution->get_correct_resolutions();
            if($resolutions)
            {
                require_once RESADS_CLASS_DIR . '/AdSpot.php';
                $AdSpot_DB = new ResAds_AdSpot_DB();
                if(!ResAds::is_cache_plugin_activate())
                {
                    $adspot = $AdSpot_DB->get_random_banner($adspot_id, $resolutions);
                    if($adspot && isset($adspot['adspot_ad']))
                    {
                        if(isset($adspot['adspot_ad']['ad_id']))
                        {
                            if(file_exists(RESADS_CLASS_DIR . '/AdStat.php'))
                            {
                                require_once RESADS_CLASS_DIR . '/AdStat.php';
                                $AdStat_DB = new ResAds_AdStat_DB();
                                $AdStat_DB->plus($adspot['adspot_ad']['ad_id'], 1, 0);
                            }
                        }     

                        require_once RESADS_CLASS_DIR . '/AdManagement.php';
                        $AdManagement = new ResAds_AdManagement();
                        $AdSpot = new ResAds_AdSpot();
                        return $AdSpot->render_adspot($adspot, $AdManagement->get_code($adspot['adspot_ad']), false);
                    }    
                }
                else
                {
                    $adspot = $AdSpot_DB->get_only_adspot($adspot_id);
                    if($adspot && isset($adspot['adspot_id']))
                    {
                        $AdSpot = new ResAds_AdSpot();
                        return $AdSpot->render_adspot($adspot, '', false);
                    }
                }
            }
        }
    }
}
/**
 * Ajax register click on ad
 */
function resads_set_click_on_ad()
{
    header("Content-Type: application/json");
    if(file_exists(RESADS_CLASS_DIR . '/AdStat.php') && isset($_POST['ad_id']) && is_numeric($_POST['ad_id']))
    {
        require_once RESADS_CLASS_DIR . '/AdStat.php';
        $AdStat = new ResAds_AdStat_DB();
        $AdStat->plus($_POST['ad_id'], 0, 1);
    }
    echo json_encode(array('return' => true));
    exit;
}
add_action('wp_ajax_nopriv_resads_set_click_on_ad', 'resads_set_click_on_ad');
add_action('wp_ajax_resads_set_click_on_ad', 'resads_set_click_on_ad');
/**
 * Ajax load ads
 */
function resads_load_ads()
{
    header("Content-Type: application/json");
    $return = array();
    
    if(file_exists(RESADS_CLASS_DIR . '/AdManagement.php') && file_exists(RESADS_CLASS_DIR . '/AdSpot.php') && file_exists(RESADS_CLASS_DIR . '/Resolution.php') && file_exists(RESADS_CLASS_DIR . '/AdStat.php') && isset($_POST['ads']) && is_array($_POST['ads']) && isset($_POST['resads_width']) && isset($_POST['resads_height']))
    {
        require_once RESADS_CLASS_DIR . '/AdManagement.php';
        require_once RESADS_CLASS_DIR . '/AdSpot.php';
        require_once RESADS_CLASS_DIR . '/Resolution.php';
        require_once RESADS_CLASS_DIR . '/AdStat.php';
        
        $Resolution = new ResAds_Resolution();
        $resolutions = $Resolution->get_correct_resolutions($_POST['resads_width'], $_POST['resads_height']);
        if($resolutions)
        {
            $AdManagement = new ResAds_AdManagement();
            $AdSpot_DB = new ResAds_AdSpot_DB();

            foreach($_POST['ads'] as $ad)
            {
                $current = array();
                
                if(isset($ad['adspot_id']))
                {
                    $adspot_id = $ad['adspot_id'];
                    $adspot = $AdSpot_DB->get_random_banner($adspot_id, $resolutions);
                    if($adspot && isset($adspot['adspot_ad']))
                    {
                        if(isset($adspot['adspot_ad']['ad_id']))
                        {
                            $AdStat_DB = new ResAds_AdStat_DB();
                            $AdStat_DB->plus($adspot['adspot_ad']['ad_id'], 1, 0);
                            
                            $current['ad_id'] = $adspot['adspot_ad']['ad_id'];
                        }
                        
                        $current['ad'] = $adspot['adspot_ad'];
                        $current['adspot_index'] = $ad['adspot_index'];
                        $current['adspot_id'] = $adspot_id;
                        $current['code'] = $AdManagement->get_code($adspot['adspot_ad']);
                        
                        $return[] = $current;
                    }
                }
            }
        }
    }
    echo json_encode(array('return' => $return));
    exit;
}
add_action('wp_ajax_nopriv_resads_load_ads', 'resads_load_ads');
add_action('wp_ajax_resads_load_ads', 'resads_load_ads');
/**
 * Register Shortcode
 * @param array $atts
 * @return string
 */
function resads_adspot_shortcode($atts)
{
    if(function_exists('resads_adspot'))
    {
        $a = shortcode_atts(
                array('id' => 0),
                $atts);
        
        return resads_adspot($a['id'], false);
    }
}
add_shortcode('resads_adspot', 'resads_adspot_shortcode');
/**
 * Add AdSpot to Top or Bottom of an article
 * @param string $content
 * @return stromg
 */
function resads_add_adspot_to_content($content)
{
    if(file_exists(RESADS_CLASS_DIR . '/AdSpot.php') && file_exists(RESADS_CLASS_DIR . '/Resolution.php') && is_single() && in_the_loop())
    {
        $return = '';
        require_once RESADS_CLASS_DIR . '/AdSpot.php';
        $AdSpot_DB = new ResAds_AdSpot_DB();
        $adspots = $AdSpot_DB->get_random_by_article_position(true, true, true);
        
        if(isset($adspots['top']) && is_array($adspots['top']) && isset($adspots['top']['adspot_id']) && isset($adspots['top']['adspot_show_top_article']))
        {
            $return .= sprintf('<div class="resads-position resads-position-%s">%s</div><div style="clear:both;"></div>', $adspots['top']['adspot_show_top_article'], resads_adspot($adspots['top']['adspot_id']));
        }
        
        if(isset($adspots['top_inside']) && is_array($adspots['top_inside']) && isset($adspots['top_inside']['adspot_id']) && isset($adspots['top_inside']['adspot_show_top_inside_article']))
        {
            require_once RESADS_CLASS_DIR . '/Resolution.php';
            $Resolution = new ResAds_Resolution();
            $device = $Resolution->get_device($Resolution->check_cookie_width(), $Resolution->check_cookie_height());
            
            if($device['smartphone'] == 0)
            {
                $return .= sprintf('<div class="resads-position resads-position-inside-%s">%s</div>', $adspots['top_inside']['adspot_show_top_inside_article'], resads_adspot($adspots['top_inside']['adspot_id']));
            }
        }
        
        $return .= $content;
        
        if(isset($adspots['bottom']) && is_array($adspots['bottom']) && isset($adspots['bottom']['adspot_id']) && isset($adspots['bottom']['adspot_show_bottom_article']))
        {
            $return .= sprintf('<div class="resads-position resads-position-%s">%s</div><div style="clear:both;"></div>', $adspots['bottom']['adspot_show_bottom_article'], resads_adspot($adspots['bottom']['adspot_id'])); 
        }
        
        $content = $return;
    }
    return $content;
}
add_filter('the_content', 'resads_add_adspot_to_content'); 
/**
 * Register Widget
 * @return type
 */
function resads_register_widget()
{
    if(file_exists(RESADS_CLASS_DIR . '/AdSpot.php'))
    {
        require_once RESADS_CLASS_DIR . '/AdSpot.php';
        return register_widget('ResAds_AdSpot_Widget');
    }
}
add_action('widgets_init', 'resads_register_widget');
/**
 * Ajax get adspots
 */
function resads_get_adspots()
{
    header("Content-Type: application/json");
    $return = array();
    
    if(file_exists(RESADS_CLASS_DIR . '/AdSpot.php'))
    {
        require_once RESADS_CLASS_DIR . '/AdSpot.php';
        
        $AdSpot_DB = new ResAds_AdSpot_DB();
        $adspots = $AdSpot_DB->get_all('adspot_id DESC');
        $return = $adspots;
    }
    echo json_encode(array('return' => $return));
    exit;
}
add_action('wp_ajax_nopriv_resads_get_adspots', 'resads_get_adspots');
add_action('wp_ajax_resads_get_adspots', 'resads_get_adspots');
?>