<?php

if (!defined('WPINC')) die('No access outside of wordpress.');

add_action('wp_footer', 'pzat_render_script_tag');

function pzat_render_script_tag() {
  $opts = pzat_zoom_options();

  $file = PZAT_ASSETS_URL . 'content.js';
  $asset_path = PZAT_ASSETS_URL;
  $design = esc_attr($opts->get('zoom_settings_design'));
  $main_color = esc_attr($opts->get('zoom_settings_main_color'));
  $accent_color = esc_attr($opts->get('zoom_settings_accent_color'));
  $opacity = esc_attr($opts->get('zoom_settings_opacity'));
  $position = esc_attr($opts->get('zoom_settings_position'));
  $blocklist = json_encode(array_filter(explode("\r\n", $opts->get('zoom_settings_blocklist'))));
  $strict_blocklist = json_encode(array_filter(explode("\r\n", $opts->get('zoom_settings_strict_blocklist'))));
  $content_deselectors = json_encode(array_filter(explode("\r\n", $opts->get('zoom_settings_content_deselectors'))));
  $logo_url = esc_attr($opts->get('zoom_settings_logo_url'));
  $logo_title = esc_attr($opts->get('zoom_settings_logo_title'));
  $logo_link = esc_attr($opts->get('zoom_settings_logo_link'));

  $script_tag = <<<SCRIPT
<script type="text/javascript">
  window.PZAT = {
    design: '$design',
    mainColor: '$main_color',
    accentColor: '$accent_color',
    opacity: '$opacity',
    position: '$position',
    assetPath: '$asset_path',
    blocklist: JSON.parse('$blocklist'),
    strictBlockList: JSON.parse('$strict_blocklist'),
    contentDeselector: JSON.parse('$content_deselectors'),
    logoUrl: '$logo_url',
    logoTitle: '$logo_title',
    logoLinkTarget: '$logo_link'
  };
  (function(){
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = '$file';
    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(s);
  })();
</script>
SCRIPT;

  echo $script_tag;
}
