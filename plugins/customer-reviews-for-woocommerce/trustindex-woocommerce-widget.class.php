<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class TrustindexWidget_woocommerce extends WP_Widget {
public function __construct()
{
parent::__construct(
'trustindex_woocommerce_widget',
'Review Widgets for Woocommerce',
[
'classname' => 'trustindex-widget',
'description' => TrustindexWoocommercePlugin::___('Embed reviews fast and easily into your WordPress site. Increase SEO, trust and sales using Trustindex reviews.')
]
);
}
function widget($args, $instance)
{
global $trustindex_woocommerce;
if($trustindex_woocommerce->is_enabled())
{
extract($args);
echo $before_widget;
if(0)//$trustindex_woocommerce->is_noreg_table_exists())
{
echo $trustindex_woocommerce->get_noreg_list_reviews();
}
else
{
echo TrustindexWoocommercePlugin::get_alertbox(
"error",
'<br />' . TrustindexWoocommercePlugin::___("Please fill out <strong>all the required fields</strong> in the <a href='%s'>widget settings</a> page", [ admin_url('admin.php?page='.$trustindex_woocommerce->get_plugin_slug().'/settings.php&tab=setup&step=4') ]),
false
);
}
echo $after_widget;
}
}
}
?>