<?php
defined('ABSPATH') || exit;

$id = filter_input(INPUT_GET, 'imagelinks', FILTER_SANITIZE_NUMBER_INT);
$class = NULL;

global $wpdb;
$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;
$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id=%s", $id);
$item = $wpdb->get_row($query, OBJECT);
if($item) {
	$version = strtotime(mysql2date('d M Y H:i:s', $item->modified));
	$itemData = unserialize($item->data);
	$id = $item->id;
	$id_postfix = strtolower(wp_generate_password(5, false)); // generate unique postfix for $id to avoid clashes with multiple same shortcode use
	$id_element = 'imgl-' . $id . '-' . $id_postfix;
	$plugin_url = plugin_dir_url(dirname(__FILE__));

    $preview_css_src = $plugin_url . 'assets/css/preview.min.css?ver=' . IMAGELINKS_PLUGIN_VERSION;
    $loader_js_src = $plugin_url . 'assets/js/loader.min.js?ver=' . IMAGELINKS_PLUGIN_VERSION;

    $imagelinks_globals = [
        'plan' => IMAGELINKS_PLUGIN_PLAN,
        'version' => $version,
        'fontawesome_url' => $plugin_url . 'assets/css/font-awesome.min.css',
        'effects_url' => $plugin_url . 'assets/js/lib/imagelinks/imagelinks-effects.min.css',
	    'theme_base_url' => $plugin_url . 'assets/themes/',
	    'plugin_base_url' => $plugin_url . 'assets/js/lib/imagelinks/',
	    'plugin_upload_base_url' => IMAGELINKS_PLUGIN_UPLOAD_URL
    ];
?>
<!DOCTYPE html>
<html>
<head>
 <?php
    wp_enqueue_style('imagelinks_preview_css', $preview_css_src, [], IMAGELINKS_PLUGIN_VERSION, 'all');
    wp_enqueue_script('imagelinks-loader-js', $loader_js_src, ['jquery'], IMAGELINKS_PLUGIN_VERSION, false);
    wp_localize_script('imagelinks-loader-js', 'imagelinks_globals', $imagelinks_globals);
    wp_head();
?>
</head>
<body>
<div class="imgl-map-wrap">
    <!-- imagelinks begin -->
	<div id="<?php esc_attr_e($id_element) ?>"
         class="imgl-map imgl-map-<?php esc_attr_e($id . ' ' . ($class ? ' ' . $class : '')) ?>"
         data-json-src="<?php echo esc_url(IMAGELINKS_PLUGIN_UPLOAD_URL . $item->id . '/config.json?ver=' . $version) ?>"
         data-item-id="<?php esc_attr_e($item->id) ?>"
         style="display:none;"
	>
        <div class="imgl-store">
            <?php
            $markerId = 0;
            foreach($itemData->markers as $markerKey => $marker) {
                if(!$marker->visible) {
                    continue;
                }
                $markerId++;

                //=============================================
                // MARKER BEGIN
                echo '<div class="imgl-pin imgl-pin-' . esc_attr($markerId) . '" data-id="' . esc_attr($markerId) . '">' . PHP_EOL;

                if($marker->view->pulse->active) {
                    echo '<div class="imgl-pin-pulse"></div>' . PHP_EOL;
                }

                echo '<div class="imgl-pin-data">' . PHP_EOL;
                if(!$this->IsNullOrEmptyString($marker->view->icon->name) || !$this->IsNullOrEmptyString($marker->view->icon->label)) {
                    echo '<div class="imgl-ico-wrap">' . PHP_EOL;
                    if(!$this->IsNullOrEmptyString($marker->view->icon->name)) {
                        echo '<div class="imgl-ico"><i class="fa ' . esc_attr($marker->view->icon->name) . '"></i></div>' . PHP_EOL;
                    }
                    if(!$this->IsNullOrEmptyString($marker->view->icon->label)) {
                        echo '<div class="imgl-ico-lbl">' . esc_attr($marker->view->icon->label) . '</div>' . PHP_EOL;
                    }
                    echo '</div>' . PHP_EOL;
                }
                echo '</div>' . PHP_EOL;

                echo '</div>' . PHP_EOL;
                // MARKER END
                //=============================================
            }

            $markerId = 0;
            foreach($itemData->markers as $markerKey => $marker) {
                if(!$marker->visible) {
                    continue;
                }
                $markerId++;
                //=============================================
                // TOOLTIP BEGIN
                echo '<div ';
                echo 'class="imgl-tt imgl-tt-' . esc_attr($markerId) . '" ';
                echo 'data-id="' . esc_attr($markerId) . '" ';
                echo '>' . PHP_EOL;
                echo do_shortcode($marker->tooltip->data);
                echo '</div>' . PHP_EOL;
                // TOOLTIP END
                //=============================================
            }
            ?>
        </div>
    </div>
    <!-- imagelinks end -->
</div>
</body>
</html>
<?php
} else {
	echo '<p>' . esc_html__('Error: invalid imagelinks database record', 'imagelinks') . '</p>';
	die;
}
?>
