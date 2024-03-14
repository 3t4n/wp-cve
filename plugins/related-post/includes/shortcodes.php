<?php




if (!defined('ABSPATH')) exit; // if direct access 


add_shortcode('related_post', 'related_post_display');

function related_post_display($atts, $content = null)
{

  $atts = shortcode_atts(
    array(
      'post_id' => "",
      'post_ids' => "",
      'headline' => "",
      'view_type' => "",
    ),
    $atts
  );

  $related_post_settings = get_option('related_post_settings');

  $post_id = isset($atts['post_id']) ? (int) $atts['post_id'] : get_the_ID();
  $related_post_enable = get_post_meta($post_id, 'related_post_enable', true);

  if ($related_post_enable == 'yes') return false;

  $post_type = get_post_type($post_id);

  $atts['settings'] = $related_post_settings;
  $atts['post_type'] = $post_type;

  $atts = apply_filters('related_post_atts', $atts);

  $view_type = isset($atts['view_type']) ?  esc_html($atts['view_type']) : 'grid';
  $layout_type = !empty($view_type) ? $view_type :  esc_html($related_post_settings['layout_type']);



  require_once(related_post_plugin_dir . 'templates/related-post-hook.php');





  ob_start();

?>
  <div class="related-post <?php echo esc_attr($layout_type); ?>">
    <?php

    do_action('related_post_main', $atts);

    ?>
  </div>
<?php



  return ob_get_clean();
}
