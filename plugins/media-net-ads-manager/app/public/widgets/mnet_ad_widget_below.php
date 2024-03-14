<?php
namespace Mnet\PublicViews\widgets;

use Mnet\PublicViews\MnetAdPublicHooks;
class mnet_ad_widget_below extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct('mnet_widget_below', __('mnet_ad_widget_below'), array('description' => __('Media.net AD below sidebar'),));
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        MnetAdPublicHooks::mnetPublicInjectAd(null, null, MNET_AD_POSITION_BELOW_SIDEBAR);
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}
