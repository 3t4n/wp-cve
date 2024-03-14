<?php /* Global: $widget, $options */
global $post;

use WordPress\Plugin\Encyclopedia\{
    Core
};

?>
<ul>
    <?php while ($widget->items->have_Posts()) : $widget->items->the_Post() ?>
        <li><a href="<?php the_Permalink() ?>" title="<?php echo esc_Attr(Core::getCrossLinkItemTitle($post)) ?>" class="encyclopedia"><?php the_Title() ?></a></li>
    <?php endwhile ?>
</ul>