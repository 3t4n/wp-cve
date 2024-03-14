<?php
namespace Mnet\PublicViews\widgets;

use Mnet\PublicViews\MnetAdPublicHooks;

class mnet_ad_widget_above extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct('mnet_widget_above', __('mnet_ad_widget_above'), array('description' => __('Media.net AD above sidebar'),));
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        MnetAdPublicHooks::mnetPublicInjectAd(null, null, MNET_AD_POSITION_ABOVE_SIDEBAR);
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}

