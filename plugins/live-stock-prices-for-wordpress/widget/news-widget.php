<?php
if(!class_exists('EOD_Stock_Prices_Plugin')) {
    class EOD_News_Widget extends WP_Widget
    {
        public static $widget_base_id = 'EOD_News_Widget';

        function __construct()
        {
            parent::__construct(
                self::$widget_base_id,
                __('EODHD Financial news', 'eod-stock-prices'),
                array('description' => __('-', 'eod-stock-prices'))
            );
        }

        /*
         * Display on the site
         */
        public function widget($args, $instance)
        {
            $widget_html = eod_load_template(
                "widget/template/news-widget.php",
                array(
                    '_this'              => $this,
                    'args'               => $args,
                    'props'              => array(
                        'target'             => $instance['target'],
                        'tag'                => $instance['topic'],
                        'limit'              => $instance['limit'],
                        'pagination'         => $instance['pagination'],
                        'from'               => $instance['from'],
                        'to'                 => $instance['to'],
                    ),
                    'title'              => apply_filters('widget_title', $instance['title'])
                )
            );

            echo $widget_html;
        }

        /*
         * Display in admin panel
         */
        public function form($instance)
        {
            global $eod_api;
            $widget_html = eod_load_template(
                "widget/template/news-widget-form.php",
                array(
                    '_this'             => $this,
                    'topics'            => $eod_api->get_news_topics(),
                    'target'            => isset($instance['target']) ? $instance['target'] : '',
                    'topic'             => isset($instance['topic']) ? $instance['topic'] : '',
                    'limit'             => isset($instance['limit']) ? $instance['limit'] : 50,
                    'pagination'        => isset($instance['pagination']) ? $instance['pagination'] : 0,
                    'from'              => isset($instance['from']) ? $instance['from'] : '',
                    'to'                => isset($instance['to']) ? $instance['to'] : '',
                    'widget_title'      => isset($instance['title']) ? $instance['title'] : '',
                    'type'              => isset($instance['type']) ? $instance['type'] : 'ticker',
                    'eod_options'       => get_option('eod_options'),
                )
            );

            echo $widget_html;
        }

        /*
         * Update widget data
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['target'] = (!empty($new_instance['target'])) ? strip_tags($new_instance['target']) : '';
            $instance['topic'] = (!empty($new_instance['topic'])) ? strip_tags($new_instance['topic']) : '';
            $instance['limit'] = (!empty($new_instance['limit'])) ? $new_instance['limit'] : '';
            $instance['pagination'] = (!empty($new_instance['pagination'])) ? $new_instance['pagination'] : '';
            $instance['from'] = (!empty($new_instance['from'])) ? $new_instance['from'] : '';
            $instance['to'] = (!empty($new_instance['to'])) ? $new_instance['to'] : '';
            $instance['type'] = strip_tags($new_instance['type']);
            return $instance;
        }
    }
}