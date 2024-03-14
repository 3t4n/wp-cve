<?php
function cloudlyup_multi_locatin_map(){
  ob_start();
  require_once( CLOUDLYUP_MULTILOCATION_GMAP_PLUGIN_DIR . 'map.php' );
  return ob_get_clean();

}
if (function_exists('cloudlyup_multi_locatin_map')){
add_shortcode('CLOUPLYUP_MAP', 'cloudlyup_multi_locatin_map');
}
