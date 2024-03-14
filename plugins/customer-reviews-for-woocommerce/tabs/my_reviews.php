<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if(isset($_POST['save-highlight']))
{
check_admin_referer('ti-woocommerce-save-highlight');
$id = null;
$start = null;
$length = null;
if(isset($_POST['id']))
{
$id = intval(sanitize_text_field($_POST['id']));
}
if(isset($_POST['start']))
{
$start = sanitize_text_field($_POST['start']);
}
if(isset($_POST['length']))
{
$length = sanitize_text_field($_POST['length']);
}
if($id)
{
$highlight = "";
if(!is_null($start))
{
$highlight = $start . ',' . $length;
}
$wpdb->query("UPDATE `". $trustindex_woocommerce->get_noreg_tablename() ."` SET highlight = '$highlight' WHERE id = '$id'");
}
exit;
}
$reviews = [];
if($trustindex_woocommerce->is_noreg_table_exists())
{
$reviews = $wpdb->get_results('SELECT * FROM '. $trustindex_woocommerce->get_noreg_tablename() .' ORDER BY date DESC');
}
function trustindex_plugin_write_rating_stars($score)
{
global $trustindex_woocommerce;
$text = "";
$link = "https://cdn.trustindex.io/assets/platform/Trustindex/star/";
if(!is_numeric($score))
{
return $text;
}
for ($si = 1; $si <= $score; $si++)
{
$text .= '<img src="'. $link .'f.svg" class="ti-star" />';
}
$fractional = $score - floor($score);
if( 0.25 <= $fractional )
{
if ( $fractional < 0.75 )
{
$text .= '<img src="'. $link .'h.svg" class="ti-star" />';
}
else
{
$text .= '<img src="'. $link .'f.svg" class="ti-star" />';
}
$si++;
}
for (; $si <= 5; $si++)
{
$text .= '<img src="'. $link .'e.svg" class="ti-star" />';
}
return $text;
}
wp_enqueue_style('trustindex-widget-css', 'https://cdn.trustindex.io/assets/widget-presetted-css/4-light-background.css');
wp_enqueue_script('trustindex-review-js', 'https://cdn.trustindex.io/assets/js/trustindex-review.js', [], false, true);
wp_add_inline_script('trustindex-review-js', '
jQuery(".ti-review-content").TI_shorten({
"showLines": 2,
"lessText": "'. TrustindexWoocommercePlugin::___("Show less") .'",
"moreText": "'. TrustindexWoocommercePlugin::___("Show more") .'",
});
jQuery(".ti-review-content").TI_format();
');
?>
<?php if(!$trustindex_woocommerce->is_trustindex_connected()): ?>
<div class="ti-notice notice-warning" style="margin-left: 0">
<p>
<?php echo TrustindexWoocommercePlugin::___("Finish setup review summary page first!"); ?>
 <a href="?page=<?php echo esc_attr($_GET['page']); ?>&tab=setup"><?php echo TrustindexWoocommercePlugin::___("Setup Guide"); ?></a>
</p>
</div>
<?php else: ?>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexWoocommercePlugin::___("My Reviews"); ?></div>
<?php if(!count($reviews)): ?>
<div class="ti-notice notice-warning" style="margin-left: 0">
<p><?php echo TrustindexWoocommercePlugin::___("You had no reviews at the time of last review downloading."); ?></p>
</div>
<?php else: ?>
<table class="wp-list-table widefat fixed striped table-view-list ti-my-reviews ti-widget">
<thead>
<tr>
<th class="text-center"><?php echo TrustindexWoocommercePlugin::___("Reviewer"); ?></th>
<th class="text-center" style="width: 90px;"><?php echo TrustindexWoocommercePlugin::___("Rating"); ?></th>
<th class="text-center"><?php echo TrustindexWoocommercePlugin::___("Date"); ?></th>
<th style="width: 48%"><?php echo TrustindexWoocommercePlugin::___("Text"); ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ($reviews as $review): ?>
<tr data-id="<?php echo $review->id; ?>">
<td class="text-center">
<img src="<?php echo $review->user_photo; ?>" class="ti-user-avatar" /><br />
<?php echo $review->user; ?>
</td>
<td class="text-center source-Trustindex"><?php echo trustindex_plugin_write_rating_stars($review->rating); ?></td>
<td class="text-center"><?php echo $review->date; ?></td>
<td><div class="ti-review-content"><?php echo $trustindex_woocommerce->getReviewHtml($review); ?></div></td>
<td>
<a href="<?php echo $review->id; ?>" class="btn-text btn-highlight<?php if(isset($review->highlight) && $review->highlight): ?> has-highlight<?php endif; ?>" style="margin-left: 0"><?php echo TrustindexWoocommercePlugin::___("Highlight text") ;?></a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>
<!-- Modal -->
<div class="ti-modal" id="ti-highlight-modal">
<?php wp_nonce_field('ti-woocommerce-save-highlight'); ?>
<div class="ti-modal-dialog">
<div class="ti-modal-content">
<div class="ti-modal-header">
<span class="ti-modal-title"><?php echo TrustindexWoocommercePlugin::___("Highlight text") ;?></span>
</div>
<div class="ti-modal-body">
<?php echo TrustindexWoocommercePlugin::___("Just select the text you want to highlight") ;?>:
<div class="ti-highlight-content"></div>
</div>
<div class="ti-modal-footer">
<a href="#" class="btn-text btn-modal-close"><?php echo TrustindexWoocommercePlugin::___("Back") ;?></a>
<a href="#" class="btn-text btn-primary btn-highlight-confirm" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>"><?php echo TrustindexWoocommercePlugin::___("Save") ;?></a>
<a href="#" class="btn-text btn-danger btn-highlight-remove" style="position: absolute; left: 15px" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>"><?php echo TrustindexWoocommercePlugin::___("Remove highlight") ;?></a>
</div>
</div>
</div>
</div>
<?php endif; ?>