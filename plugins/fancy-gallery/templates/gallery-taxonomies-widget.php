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
    <?php WP_List_Categories([
        'taxonomy'   => $options->taxonomy,
        'show_count' => $options->show_count,
        'number'     => $options->number,
        'order'      => $options->order,
        'orderby'    => $options->orderby,
        #'exclude'    => $options->exclude,
        'title_li'   => ''
    ]) ?>
</ul>

<?php echo $widget->after_widget;
