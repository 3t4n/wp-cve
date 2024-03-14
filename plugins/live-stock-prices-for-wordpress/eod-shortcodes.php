<?php
/**
 * Shortcode initialization
 */
function eod_shortcodes_init(){
    // Fundamental data
    add_shortcode('eod_fundamental', 'eod_shortcode_fundamental');
    add_shortcode('eod_financials', 'eod_shortcode_financials');
    // News
    add_shortcode('eod_news', 'eod_shortcode_news');
    // Converter
    add_shortcode('eod_converter', 'eod_shortcode_converter');
    // Tickers
    add_shortcode('eod_historical', 'eod_shortcode_historical');
    add_shortcode('eod_live', 'eod_shortcode_live');
    add_shortcode('eod_realtime', 'eod_shortcode_realtime');
}
eod_shortcodes_init();


/**
 * Shortcode EOD Fundamental Data
 * @param array $attr
 * @param null $content
 * @param string $tag
 * @return string
 */
function eod_shortcode_fundamental($attr=[], $content = null, $tag = '')
{
    $attr = array_change_key_case((array)$attr, CASE_LOWER);
    $shortcode_attr = shortcode_atts([
        'target' => false,
        'id' => false,
    ], $attr, $tag);

    return eod_load_template("template/fundamental.php", array(
        'fd' => new EOD_Fundamental_Data( $shortcode_attr['id'] ),
        'target'  => $shortcode_attr['target'],
        'key'     => str_replace('.', '_', strtolower($shortcode_attr['target']))
    ));
}

/**
 * Shortcode EOD Financials
 * @param array $attr
 * @param null $content
 * @param string $tag
 * @return string
 */
function eod_shortcode_financials($attr=[], $content = null, $tag = '')
{
    $attr = array_change_key_case((array)$attr, CASE_LOWER);
    $shortcode_attr = shortcode_atts([
        'target' => false,
        'timeline' => 'yearly',
        'years' => false,
        'id' => false,
    ], $attr, $tag);

//    // Get Financials preset
//    $financial_group = '';
//    if($shortcode_attr['id'] && is_numeric($shortcode_attr['id'])) {
//        $financials_list = get_post_meta($shortcode_attr['id'], '_financials_list', true);
//        $financial_group = get_post_meta($shortcode_attr['id'], '_financial_group', true);
//
//        if($financials_list === '' || $financial_group === '') {
//            $financials_list = array('error' => 'Preset [' . $shortcode_attr['id'] . '] not found');
//        }else {
//            $financials_list = json_decode($financials_list);
//            foreach ($financials_list as &$item){
//                $path = explode('->', $item);
//                $item = end($path);
//            }
//        }
//
//    }else {
//        $financials_list = array('error' => 'Wrong preset id');
//    }
//
//    return eod_load_template("template/financials.php", array(
//        'financials_list' => $financials_list,
//        'financial_group' => $financial_group,
//        'target'          => $shortcode_attr['target'],
//        'years'           => $shortcode_attr['years'],
//        'key'             => str_replace('.', '_', strtolower($shortcode_attr['target']))
//    ));
    return eod_load_template("template/financials.php", array(
        'fd' => new EOD_Financial( $shortcode_attr['id'] ),
        'target'          => $shortcode_attr['target'],
        'years'           => $shortcode_attr['years'],
        'key'             => str_replace('.', '_', strtolower($shortcode_attr['target']))
    ));
}

/**
 * Shortcode EOD News
 * @param array $attr
 * @param null $content
 * @param string $tag
 * @return string
 */
function eod_shortcode_news($attr = [], $content = null, $tag = '')
{
    global $eod_api;
    $options = get_eod_display_options();

    $attr = array_change_key_case((array)$attr, CASE_LOWER);
    $shortcode_attr = shortcode_atts([
        'classname'      => false,
        'pagination' => false,
        'target'     => false,
        'tag'        => false,
        'limit'      => 50,
        'from'       => false,
        'to'         => false
    ], $attr, $tag);
    $data_attributes = '';
    foreach ($shortcode_attr as $key => $val){
        if($val && $key !== 'classname') $data_attributes .= " data-$key='$val'";
    }

    if(!$shortcode_attr['target'] && !$shortcode_attr['tag']){
        return eod_load_template("template/news.php", array(
            'news' => array('error' => 'wrong target or topic')
        ));
    }

    if($options['news_ajax'] === 'off'){
        $all_news = [];
        $targets = explode(', ', $shortcode_attr['target']);
        foreach ($targets as $target) {
            $news = $eod_api->get_news($target, array(
                'tag'    => $shortcode_attr['tag'],
                'limit'  => intval($shortcode_attr['limit']),
                'from'   => $shortcode_attr['from'],
                'to'     => $shortcode_attr['to']
            ));
            if(!$news || $news['error']) continue;
            $all_news = array_merge($all_news, $news);
        }
        return '<div class="eod_news_list '.($shortcode_attr['classname'] ? : '').'" '.$data_attributes.'>'
                    .eod_load_template("template/news.php", array(
                        'news'   => $all_news,
                        'target' => $shortcode_attr['target'],
                        'tag'    => $shortcode_attr['tag'],
                    )).
               '</div>';
    }

    return '<div class="eod_news_list '.($shortcode_attr['classname'] ? : '').'" '.$data_attributes.'></div>';
}


/**
 * Shortcode EOD Converter
 * @param array $attr
 * @param null $content
 * @param string $tag
 * @return string
 */
function eod_shortcode_converter($attr = [], $content = null, $tag = '')
{
    $attr = array_change_key_case((array)$attr, CASE_LOWER);
    $shortcode_attr = shortcode_atts([
        'target'     => false,
        'amount'     => 1,
        'changeable' => '1',
        'whitelist' => '',
    ], $attr, $tag);

    $error = '';
    $targets_data = [];
    if(!$shortcode_attr['target']){
        $error .= 'The target was not found. ';
    }else {
        $targets = explode(':', $shortcode_attr['target']);
        if (count($targets) !== 2){
            $error .= 'The target must contain only two elements, separated by ":". ';
        }else{
            foreach ($targets as $i=>$target){
                $items = explode('.', $target);
                if (count($items) !== 2)
                    $error .= "The target of the #".($i+1)." element was wrong. ";
                else
                    $targets_data[$i] = ['code' => strtoupper($items[0]), 'type' => strtolower($items[1])];
            }
        }
    }

    return eod_load_template("template/converter.php", array(
        'error'         => $error,
        'targets_data'  => $targets_data,
        'value'         => abs($shortcode_attr['amount']),
        'changeable'    => $shortcode_attr['changeable'] === '1',
        'whitelist'     => sanitize_text_field( $shortcode_attr['whitelist'] )
    ));
}

/**
 * Shortcode EOD Ticker
 * @param array $attr
 * @param null $content
 * @param string $tag
 * @return string
 */
function eod_shortcode_historical($attr=[], $content = null, $tag = '')
{
    $attr = array_change_key_case((array)$attr, CASE_LOWER);
    $shortcode_attr = shortcode_atts([
        'target'  => false,
        'title'   => false,
        'ndap'    => false,
        'ndape'    => false
    ], $attr, $tag);

    return eod_load_template("template/ticker.php", array(
        'type'       => 'eod_historical',
        'target'     => $shortcode_attr['target'],
        'title'      => $shortcode_attr['title'],
        'ndap'       => $shortcode_attr['ndap'],
        'ndape'      => $shortcode_attr['ndape'],
        'key'        => str_replace('.', '_', strtolower($shortcode_attr['target']))
    ));
}


/**
 * Shortcode EOD Live
 * @param array $attr
 * @param null $content
 * @param string $tag
 * @return string
 */
function eod_shortcode_live($attr=[], $content = null, $tag = '')
{
    $attr = array_change_key_case((array)$attr, CASE_LOWER);
    $shortcode_attr = shortcode_atts([
        'target'  => false,
        'title'   => false,
        'ndap'    => false,
        'ndape'    => false
    ], $attr, $tag);

    return eod_load_template("template/ticker.php", array(
        'type'       => 'eod_live',
        'target'     => $shortcode_attr['target'],
        'title'      => $shortcode_attr['title'],
        'ndap'       => $shortcode_attr['ndap'],
        'ndape'      => $shortcode_attr['ndape'],
        'key'        => str_replace('.', '_', strtolower($shortcode_attr['target']))
    ));
}


/**
 * Shortcode EOD Realtime
 * @param array $attr
 * @param null $content
 * @param string $tag
 * @return string
 */
function eod_shortcode_realtime($attr=[], $content = null, $tag = '')
{
    $attr = array_change_key_case((array)$attr, CASE_LOWER);
    $shortcode_attr = shortcode_atts([
        'target'  => false,
        'title'   => false,
        'ndap'    => false
    ], $attr, $tag);

    $error = false;
    $key_target = explode('.', strtolower($shortcode_attr['target']) );
    if(count($key_target) !== 2) $error = 'wrong target';

    return eod_load_template("template/realtime_ticker.php", array(
        'error'      => $error,
        'target'     => $shortcode_attr['target'],
        'title'      => $shortcode_attr['title'],
        'ndap'       => $shortcode_attr['ndap'],
        'key'        => implode('_', $key_target)
    ));
}
