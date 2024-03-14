<?php

use Google\Web_Stories\Story_Renderer\HTML;
use Google\Web_Stories\Model\Story;
$checknoof = ($atts['show-no-of-story'] !== 'all') ? $atts['show-no-of-story'] : -1;

$defaults = array(
    'numberposts'      => $checknoof,
    'post_type' => 'web-story',
    'order' => $atts['order'],
);
$the_query = get_posts($defaults);
$post_names = [];
$post_idss = [];
$showbtn=($atts['show-button']=="yes")?'block':'none';
$html .= '<style>
.wsae-grid-container {
  display: grid;
  grid-template-columns: repeat('.$atts['column'].', auto [col-start]);
  grid-gap:5px;
    overflow:auto;
  padding: 5px;
  
}
.wase_gridb_button{
     color:'.$atts['btn-text-color'].';
    display:'.$showbtn.';
 
  background-color: '.$atts['btn-color'].';
 
}
</style><div class="wsae-grid-container">';


foreach ($the_query as $key => $value) {
    $current_post = get_post($value->ID);

    $story = new Story();

    $story->load_from_post($current_post);
    //    echo"<pre>";
    //var_dump( $value->post_title);
    $post_names[$value->post_title] = $value->post_title;
    $post_idss[] = array('id' => $value->ID, 'title' => $value->post_title, 'url' => $story->get_url(), 'poster' => $story->get_poster_portrait());
    $args = '';

$defaults = [
    'align' => 'center',
    'height' => '400px',
    'width' =>'250px',
];
$args = wp_parse_args($args, $defaults);
$align = sprintf('align%s', $args['align']);
$url = $story->get_url();
$title = $story->get_title();
$poster = !empty($story->get_poster_portrait()) ? esc_url($story->get_poster_portrait()) : '';
$margin = ('center' === $args['align']) ? 'auto' : '0';
$player_style = sprintf('width: %s;height: %s;margin: %s', esc_attr($args['width']), esc_attr($args['height']), esc_attr($margin));
$poster_style = !empty($poster) ? sprintf('--story-player-poster: url(%s)', $poster) : '';

if (
    (function_exists('amp_is_request') && amp_is_request()) ||
    (function_exists('is_amp_endpoint') && is_amp_endpoint())
) {
    $player_style = sprintf('margin: %s', esc_attr($margin));

}


$html.= '   <div class="wp-block-web-stories-embed ' . esc_attr($align) . '">
                <amp-story-player  style="' . esc_attr($player_style) . '">
                    <a href="' . esc_url($url) . '" style="' . esc_attr($poster_style) . '">' . esc_html($title) . '</a>
                </amp-story-player>
                <a href="' . esc_url($url) . '" ><button class="wase_gridb_button">'.$atts['button-text'].'</button></a>
            </div>';




}

$html.='</div>';