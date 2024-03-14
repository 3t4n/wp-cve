<?php

/*

Available environment vars:
 - $widget
 - $options

*/

echo $widget->before_widget;

if (!empty($widget->title))
    echo $widget->before_title . $widget->title . $widget->after_title;

?>

<ul>
    <?php WP_Tag_Cloud([
        'taxonomy' => $options->taxonomy,
        'number'   => (int) $options->number,
        'order'    => $options->order,
        'orderby'  => $options->orderby,
        #'exclude'  => $options->exclude
    ]) ?>
</ul>

<?php echo $widget->after_widget;
