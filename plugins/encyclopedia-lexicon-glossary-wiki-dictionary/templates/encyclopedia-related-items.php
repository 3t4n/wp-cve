<?php /* Globals: $related_items */

global $post;

use WordPress\Plugin\Encyclopedia\{
    Core,
    I18n,
    PostTypeLabels
};

if ($related_items) : ?>
    <h3><?php printf(I18n::__('Related %s'), PostTypeLabels::getItemPluralName()) ?></h3>
    <ul class="related-items">
        <?php while ($related_items->have_Posts()) : $related_items->the_Post() ?>
            <li class="item"><a href="<?php the_Permalink() ?>" title="<?php echo esc_Attr(Core::getCrossLinkItemTitle($post)) ?>" class="encyclopedia"><?php the_Title() ?></a></li>
        <?php endwhile;
        WP_Reset_Postdata() ?>
    </ul>
<?php endif;
