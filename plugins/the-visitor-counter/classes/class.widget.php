<?php

class WTVCP_Widget_Visitors extends WP_Widget
{

    function __construct()
    {
        if(session_id() == '' || !isset($_SESSION)) {
            // session isn't started
            session_start();
        }

        parent::__construct(
            'widget_visitors',
            __('The Visitor Counter', 'widget_visitors'),
            ['description' => __('Shows the number of active users on the site', 'widget_visitors')]
        );
    }

    public function form($instance)
    { ?>
        <p>
            <?= __('Make your changes on the widget settings page located in the tools menu.'); ?>
        </p>
    <?php }


    public function widget($args, $instance)
    {
        WTVCP_Visitors::WTVCP_show_widget_content();
    }
}
