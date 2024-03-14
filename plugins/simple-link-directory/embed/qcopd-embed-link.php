<?php 
wp_head();

$order = isset($_GET['order']) ? sanitize_text_field($_GET['order']): '';
$mode = isset($_GET['mode']) ? sanitize_text_field($_GET['mode']): '';
$column = isset($_GET['column']) ? sanitize_text_field($_GET['column']): '';
$style = isset($_GET['style']) ? sanitize_text_field($_GET['style']): '';
$search = '';
$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']): '';
$upvote = '';
$list_id = isset($_GET['list_id']) ? sanitize_text_field($_GET['list_id']): '';

//$item_count = sanitize_text_field(isset($_GET['item_count'])?$_GET['item_count']:'');

echo '<div class="clear">';

echo do_shortcode('[qcopd-directory mode="' . $mode .  '" list_id="' . $list_id . '" style="' . $style . '" column="' . $column . '" search="' . $search . '" category="' . $category . '" upvote="' . $upvote . '" item_count="on" orderby="date" order="' . $order . '"]'); 

echo '</div>';


wp_footer();
?>





