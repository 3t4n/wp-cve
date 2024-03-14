<?php 
    $categories = wp_get_post_terms( get_the_ID(), PE_CAT);
?>
<div class="gridblock-inner">
    <?php the_post_thumbnail('portfolio-large'); ?>
    <div class="gridblock-background-hover">
        <div class="gridblock-links-wrap">
            <a class="column-gridblock-icon" href="<?php the_permalink(); ?>">
                <span class="hover-icon-effect">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </a>
            <span class="lightbox-active column-gridblock-icon column-gridblock-lightbox lightbox-image" data-src="<?php the_post_thumbnail_url('full'); ?>" data-exthumbimage="<?php the_post_thumbnail_url('thumbnail'); ?>" data-sub-html="<?php the_title() ?>">
                <span class="hover-icon-effect">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </span>
            </span>

        </div>
    </div>
</div>
<div class="work-details">
    <?php if($show_category  === 'yes'): ?>
        <div class="portfolio-categories">
        <?php foreach( $categories as $categorie ) :
                $namect = $categorie->name.',';
                if ($categorie === end($categories) || count($categories) == 1){
                    $namect = $categorie->name;
                }?>
            <a href="<?php echo esc_url( get_term_link($categorie->term_id, PE_CAT) );?>" class="categories-link"><span><?php echo trim( $namect ); ?></span> </a>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    <?php if($show_description === 'yes'): ?>
        <p class="entry-content work-description"><?php echo wp_trim_words(get_the_excerpt(), 15,  '...'); ?></p>
    <?php endif; ?>
</div>
