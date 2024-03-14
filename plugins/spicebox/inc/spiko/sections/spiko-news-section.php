<?php 

$theme=wp_get_theme();
if ('Spiko Dark' == $theme->name):
add_action('wp_enqueue_scripts', 'spiko_dark_enqueue_script');
function spiko_dark_enqueue_script() {
    wp_enqueue_script('spiko-dark-mp-masonry-js',SPICEB_PLUGIN_URL.'/inc/spiko/js/masonry/mansory.js');   
}
function custom_script(){?>
    <script>
        jQuery(document).ready(function () {
         jQuery('.grid').masonry({
            itemSelector: '.grid-item',
            transitionDuration: '0.2s',
            horizontalOrder: true,
            });
        });
</script>
<?php
}
add_action('wp_head', 'custom_script');
endif;

/* Call the action for news section */
add_action('spiceb_spiko_news_action','spiceb_spiko_news_section');

/* Function for news section*/
function spiceb_spiko_news_section()
{
$spiko_index_news_link = get_theme_mod('home_blog_more_btn_link', __('#', 'spicebox'));
$spiko_index_more_btn = get_theme_mod('home_blog_more_btn', __('Cras Vitae', 'spicebox'));
if (empty($spiko_index_news_link)) {
    $spiko_index_news_link = '#';
}
if(get_theme_mod('latest_news_section_enable',true)==true):

$theme=wp_get_theme();
if ('Spiko Dark' == $theme->name):
    $spiko_blog_section_class = 'blog blog-masonry';
    $spiko_blog_class='grid';
else:
    $spiko_blog_section_class = 'blog';
    $spiko_blog_class='row';
endif;

?>
<!-- Latest News section -->
<section class="section-space <?php echo esc_attr($spiko_blog_section_class);?> bg-default-color home-blog">
    <div class="spiko-newz container">
        <?php
        $spiko_home_news_section_title = get_theme_mod('home_news_section_title', __('Vitae Lacinia', 'spicebox'));
        $spiko_home_news_section_discription = get_theme_mod('home_news_section_discription', __('Cras Vitae Placerat', 'spicebox'));
        $spiko_home_meta_section_settings = get_theme_mod('home_meta_section_settings', true);
        if (($spiko_home_news_section_title) || ($spiko_home_news_section_discription) != '') {
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="section-header">
                        <?php if ($spiko_home_news_section_discription) { ?>
                        <p class="section-subtitle"><?php echo wp_kses_post($spiko_home_news_section_discription); ?></p>
                        <?php } ?>
                        <?php if ($spiko_home_news_section_title) { ?>
                            <h2 class="section-title"><?php echo esc_html($spiko_home_news_section_title); ?></h2>
                            <div class="section-separator border-center"></div>
                        <?php } ?>
                        
                    </div>
                </div>                      
            </div>
            <!-- /Section Title -->
        <?php } ?>

        <div class="<?php echo esc_attr($spiko_blog_class);?>">
            <?php
            $spiko_args = array('post_type' => 'post', 'post__not_in' => get_option("sticky_posts"), 'posts_per_page' => 4);
            query_posts($spiko_args);
            if (query_posts($spiko_args)) {
                while (have_posts()):the_post();
                    {
                    if ('Spiko Dark' == $theme->name){ ?>
                        <div class="grid-item col-md-6 col-sm-12">
                        <?php } else { ?>    
                        <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php } ?>     
                            <article class="post">  
                                <?php if (has_post_thumbnail()) { ?>
                                    <figure class="post-thumbnail">
                                        <?php $spiko_defalt_arg = array('class' => "img-fluid"); ?>
                                       <?php the_post_thumbnail('', $spiko_defalt_arg); ?>                          
                                    </figure>   
                                <?php } ?>
                                <div class="post-content">
                                    <?php
                                    if ($spiko_home_meta_section_settings == true) {
                                    if(has_post_thumbnail()) { ?> <div class="entry-date"> <span class="date"><?php echo esc_html(get_the_date()); ?></span> <?php }
                                        else{ ?><div class="remove-image"><span class="date"><?php echo esc_html(get_the_date()); ?></span> <?php  }?>
        
                                    <?php 
                                    echo '</div>';  } ?>
                                    <?php if ($spiko_home_meta_section_settings == true) { ?>
                                        <div class="entry-meta">
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"> <i class="fa fa-user"></i><span class="author postauthor"><?php echo esc_html(get_the_author()); ?></span></a>
                                            <?php
                                            $spiko_cat_list = get_the_category_list();
                                            if (!empty($spiko_cat_list)) {?>
                                                <i class="fa fa-folder-open"></i><span class="cat-links postcat"><?php the_category(', ');?></span>
                                            <?php } ?>
                                         
                                            <?php
                                            $tag_list = get_the_tag_list();
                                            if (!empty($tag_list)) {
                                                ?>
                                                <i class="fa fa-tag"></i>
                                                <span class="cat-links posttag"><?php the_tags('', ', ', ''); ?></span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>                                         
                                    <header class="entry-header">
                                        <h4 class="entry-title">
                                            <a class="home-blog-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h4>
                                    </header>   

                                    <div class="entry-content">
                                        <?php the_excerpt(); 
                                         $blog_button = get_theme_mod('home_news_button_title', __('Read More', 'spicebox'));
                                         if (!empty($blog_button)) {?>
                                        <p><a href="<?php the_permalink(); ?>" class="btn-small"><?php echo esc_html($blog_button); ?> <i class="fa <?php if(is_rtl()){echo 'fa-angle-double-left';} else{ echo 'fa-angle-double-right';}?>"></i></a></p>
                                        <?php } ?>
                                    </div>  
                                </div>          
                            </article>
                        </div>
                        <?php
                    }
                endwhile;
            }
            ?>
        </div>
        <?php if (!empty($spiko_index_more_btn)): ?>
            <div class="blog-btn text-center">
                    <a href="<?php echo esc_url($spiko_index_news_link); ?>" class="btn-small btn-default" <?php
                       if (get_theme_mod('home_blog_more_btn_link_target', false) == true) {
                           echo "target='_blank'";
                       };
                       ?>><?php echo esc_html($spiko_index_more_btn); ?><i class="fa <?php if(is_rtl()){echo 'fa-long-arrow-left';} else{ echo 'fa-long-arrow-right';}?>"></i></a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php
endif;
} ?>