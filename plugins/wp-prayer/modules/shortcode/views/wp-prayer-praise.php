<?php

/**
 * Render Praise Report Shortcode.
 * @author Flipper Code <hello@flippercode.com>
 * @package  Maps
 */
global $wpdb, $paged, $max_num_pages, $wp_rewrite, $wp_query;

$modelFactory = new FactoryModelWPE();


wp_enqueue_script('wpp-frontend');
wp_enqueue_style('wpp-frontend');

$settings = unserialize(get_option('_wpe_prayer_engine_settings'));

$wcsl_js_lang = array(
	'ajax_url' => admin_url('admin-ajax.php'),
	'nonce' => wp_create_nonce('wpe-call-nonce'),
	'confirm' => __('Are you sure to delete item ?', WPE_TEXT_DOMAIN),
    'loading_image' => WPE_IMAGES.'loader.gif',
    'pagination_style' => isset($data['layout_post_setting']['pagination_style']),
    'WCSL_IMAGES' => WPE_IMAGES,
    'loading_text' => '...',
    'prayed_text' => '...',
	'pray1_text' => (isset( $settings['wpe_pray_text'] ) and ! empty( $settings['wpe_pray_text'] )) ? $settings['wpe_pray_text'] : __('Pray', WPE_TEXT_DOMAIN),
	'pray_time_interval' => (isset( $settings['wpe_prayer_time_interval'] ) and ! empty( $settings['wpe_prayer_time_interval'] )) ? $settings['wpe_prayer_time_interval'] : '',
);

if(isset($settings['wpe_prayer_time_interval'])){$wcsl_js_lang['pray_time_interval'] = intval($settings['wpe_prayer_time_interval']);}

$script = "var wcsl_js_lang = " . wp_json_encode($wcsl_js_lang) . ";";
wp_enqueue_script('wpp-frontend');
wp_add_inline_script('wpp-frontend', $script, 'before');

if(isset( $settings['wpe_pray_text'] ) and !empty ($settings['wpe_pray_text'])) {$wcsl_js_lang['pray1_text'] = $settings['wpe_pray_text'];} else {$wcsl_js_lang['pray1_text'] = __('Pray', WPE_TEXT_DOMAIN);} 


$prayer_obj = $modelFactory->create_object('prayer');
$paged = (isset($_GET['praise_num']) && intval($_GET['praise_num'])) ? $_GET['praise_num'] : 1;
if(!empty ($settings['wpe_num_prayer_per_page'])) {$prayers_per_page = intval($settings['wpe_num_prayer_per_page']);} else {$prayers_per_page = 10;}
$limit = ($paged - 1) * $prayers_per_page;
$wpe_fetch_req_from = array('1', '=', '1');
if (isset($settings['wpe_fetch_req_from']) != '') {
    switch ($settings['wpe_fetch_req_from']) {
        case 'all' :
            break;
        default :
            $wpe_fetch_req_from = array('DATEDIFF(now(), prayer_time)', '<=', $settings['wpe_fetch_req_from']);
            break;
    }
}
$prayers = $prayer_obj->fetch(array(
    array('prayer_status', '=', 'approved'),
    array('request_type', '=', 'praise_report'),
    $wpe_fetch_req_from,
), 'prayer_id', false, $limit, $prayers_per_page);

$total_prayers = $prayer_obj->fetch(array(
    array('prayer_status', '=', 'approved'),
    array('request_type', '=', 'praise_report'),
    $wpe_fetch_req_from,
));

$max_num_pages = ceil(sizeof($total_prayers) / $prayers_per_page);

echo '<p>&nbsp;</p>';
if (empty($prayers)) {
    if (current_user_can('manage_options')) {
        printf('<a href="%s">'.__('Share prayer request',
                WPE_TEXT_DOMAIN), admin_url('admin.php?page=wpe_form_prayer'));
    }
    //echo '</div>';

    return;
}
if ( ! empty($prayers)) {
    echo '<div class="wsl_prayer_engine">';
    echo '<div class="wsl_prayer_enginelist"><ul>';
    $settings = unserialize(get_option('_wpe_prayer_engine_settings'));
    foreach ($prayers as $pray) {
        $prayer_author_info = get_userdata($pray->prayer_author);
        echo '<li>
		<div class="wsl_prayer_left">';
        if (isset($settings['wpe_display_author']) && $settings['wpe_display_author'] == 'true') {
            echo ($pray->prayer_author_name != '') ? strtok(($pray->prayer_author_name),' ').' ' : (($prayer_author_info->display_name != '') ? strtok(($prayer_author_info->display_name),' ').' ' : '');
        }
        //echo '<div class="postmeta">';
		$offset = get_option('gmt_offset');
        if (isset($settings['wpe_date']) && $settings['wpe_date'] == 'true')
		{$timeago1 = date_i18n(get_option('date_format'),strtotime( $pray->prayer_time )+$offset*3600 );} else {
		if (isset($settings['wpe_ago']) && $settings['wpe_ago'] == 'true') {
		$timeago1= __('ago',WPE_TEXT_DOMAIN).' '.human_time_diff( strtotime($pray->prayer_time), current_time( 'timestamp', 1 ) );
		}else{$timeago1=human_time_diff( strtotime($pray->prayer_time), current_time( 'timestamp', 1 ) ).' '.__('ago',WPE_TEXT_DOMAIN);}}
        echo $timeago1.'<br>';echo '<div class="postmeta">';
        echo '</div></p>
		<p>'.nl2br($pray->prayer_messages).'</p>
		</div>
		</li>';
    }
    echo '</ul></div>';
    echo '</div><div class="clear"></div>';
}
$pagination = array(
    'base' => @add_query_arg('praise_num', '%#%', get_permalink()),
    'format' => '',
    'total' => $max_num_pages,
    'current' => $paged,
    'prev_text' => __('Prev', WPE_TEXT_DOMAIN),
    'next_text' => __('Next', WPE_TEXT_DOMAIN),
    'end_size' => 1,
    'mid_size' => 2,
    'show_all' => false,
    'type' => 'plain',
);
echo '<div class="prayers_pagination">'.paginate_links($pagination).'</div>';