<?php 
if (! defined( 'ABSPATH' )) exit;
if(str_replace('-', '', get_option('gmt_offset'))<10) { $tempo = '-0'.str_replace('-', '', get_option('gmt_offset')); } else { $tempo = get_option('gmt_offset'); }
if(strlen($tempo)==3) { $tempo = $tempo.':00'; }
  $postsForSitemap = get_posts(array(
'numberposts' => -1,
'orderby' => 'modified',
'post_type'  => array('post','page','property','product'),
'order'=> 'DESC'));

$sitemap = esc_url( home_url( '/' ) );

foreach($postsForSitemap as $post) {
setup_postdata($post);
$postdate = explode(" ", $post->post_modified);
$sitemap .= get_permalink($post->ID);

  }
$ap = fopen(ABSPATH . "urllist.txt", 'w');
fwrite($ap, $sitemap);
fclose($ap);
?>
