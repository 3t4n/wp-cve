<?php /* Global: $widget, $options */
global $post;

use WordPress\Plugin\Encyclopedia\{
    Core
};

?>
<ul class="related-items">
    <?php while ($widget->items->have_Posts()) : $widget->items->the_Post() ?>
        <li class="item"><a href="<?php the_Permalink() ?>" title="<?php echo esc_Attr(Core::getCrossLinkItemTitle($post)) ?>" class="encyclopedia"><?php the_Title() ?></a></li>
    <?php endwhile;
    WP_Reset_Postdata() ?>
</ul>