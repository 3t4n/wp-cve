<?php
/**
 * Get main options with default values
 * @return array
 */
function get_eod_options()
{
    return wp_parse_args( get_option( 'eod_options' ), EOD_DEFAULT_OPTIONS );
}


/**
 * Get display options with default values
 * @return array
 */
function get_eod_display_options()
{
    return wp_parse_args( get_option( 'eod_display_settings' ), EOD_DEFAULT_SETTINGS );
}


/**
 * Converting saved JSON string in widget to targets list
 * @param array $instance widget data
 * @return array
 */
function eod_get_ticker_list_from_widget_instance($instance)
{
    $targets = array();
    if( isset($instance['target']) && !empty($instance['target']) )
        $targets = json_decode($instance['target'], true);

    // Support old version with flat array
    if(is_array($targets) && count($targets) && is_array( $targets[0] )){
        $list_of_targets = $targets;
    }else{
        // (old version) $targets is an array without parameters
        $list_of_targets = [];
        foreach($targets as $item) {
            $list_of_targets[] = array(
                'target' => $item
            );
        }
    }
    return $list_of_targets;
}

/**
 * Static load template method
 * @param $templatePath
 * @param $vars
 * @return string
 */
function eod_load_template($templatePath, $vars)
{
    //Load template
    $template = EOD_PATH.$templatePath;
    ob_start();
    extract($vars);
    include $template;
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

/**
 * Includes a file within the EOD plugin.
 *
 * @param string $filename The specified file.
 * @return void
 */
function eod_include( $filename = '' ) {
    $file_path = EOD_PATH . ltrim( $filename, '/' );
    if ( file_exists( $file_path ) ) {
        include_once $file_path;
    }
}

/**
 * Display sortable flat list
 *
 * @param $fd EOD_Fundamental_Data|EOD_Financial
 */
function eod_display_saved_list( $fd ){
    foreach ($fd->list as $slug){
        echo "<li>
                <span data-slug='$slug'>
                    {$fd->get_item_title($slug)}
                    <button title='remove item'>-</button>
                </span>
              </li>";
    }
}

/**
 * Display sortable source list
 *
 * @param array $list {
 *     @type string $name
 *     @type string $type
 * }
 * @param array $path list of keys
 */
function eod_display_source_list($list, $path = array()) {
    foreach ($list as $key=>$var){
        if( $key[0] === '_' ) continue;

        $is_fundamental = get_post_type() === 'fundamental-data';
        $is_group = isset($var['list']);

        $current_path = array_merge($path, [$key]);
        $slug = implode('->', $current_path);
        $depth = count($current_path);
        $title = isset($var['title']) ? $var['title'] : $key;

        // Display item
        if( $is_group ){
            // deepen
            $path[] = $key;

            echo "<li class='has_child draggable' title='add whole group'>
                    <span style='padding-left: ".($depth*10)."px;' data-slug='$slug'>
                        $title
                        <button>+</button>
                    </span>";
            echo   '<ul>';
            eod_display_source_list($var['list'], $path);
            echo   '</ul>';
            echo '</li>';

            // get up
            array_pop($path);

        } else {
            echo "<li class='draggable'>
                    <span style='padding-left: ".($depth*10)."px;' data-slug='$slug' title='add item'>
                        $title
                        <button>+</button>
                    </span>
                  </li>";
        }
    }
}

