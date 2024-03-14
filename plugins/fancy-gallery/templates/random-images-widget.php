<?php

/*

Available environment vars:
 - $widget
 - $options

*/

echo $widget->before_widget;

if (!empty($widget->title))
    echo $widget->before_title . $widget->title . $widget->after_title;

echo Gallery_Shortcode([
    'id' => 0,
    'ids' => $options->image_ids,
    'columns' => $options->columns,
    'size' => $options->thumb_size
]);

echo $widget->after_widget;
