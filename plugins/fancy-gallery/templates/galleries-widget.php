<?php

/*

Available environment vars:
 - $widget
 - $options

*/

echo $widget->before_widget;

if (!empty($widget->title))
    echo $widget->before_title . $widget->title . $widget->after_title;

while ($options->galleries->have_Posts()) : $options->galleries->the_Post(); ?>

    <div class="gallery gallery-<?php the_ID() ?>">
        <h4><a href="<?php the_Permalink() ?>" title="<?php the_Title_Attribute() ?>"><?php the_Title() ?></a></h4>
        <?php the_Excerpt() ?>
    </div>

<?php endwhile;

echo $widget->after_widget;
