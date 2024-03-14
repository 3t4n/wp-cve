<?php 
/* Call the action for news section */
add_action('spiceb_busicare_news_action','spiceb_busicare_news_section');
/* Function for news section*/
function spiceb_busicare_news_section()
{
$busicare_index_news_link = get_theme_mod('home_blog_more_btn_link', __('#', 'spicebox'));
$busicare_index_more_btn = get_theme_mod('home_blog_more_btn', __('Cras Vitae', 'spicebox'));
if (empty($busicare_index_news_link)) {
    $busicare_index_news_link = '#';
}
if(get_theme_mod('latest_news_section_enable',true)==true):?>
<!-- Latest News section -->
<section class="section-space blog home-blog">
    <div class="container">
        <?php
        $busicare_home_news_section_title = get_theme_mod('home_news_section_title', __('Vitae Lacinia', 'spicebox'));
        $busicare_home_news_section_discription = get_theme_mod('home_news_section_discription', __('Cras Vitae Placerat', 'spicebox'));
        $busicare_home_meta_section_settings = get_theme_mod('home_meta_section_settings', true);
        if (($busicare_home_news_section_title) || ($busicare_home_news_section_discription) != '') {
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="section-header">
                        <?php if ($busicare_home_news_section_title) { ?>
                            <h2 class="section-title"><?php echo esc_html($busicare_home_news_section_title); ?></h2>
                            <div class="title_seprater"></div>
                        <?php } ?>
                        <?php if ($busicare_home_news_section_discription) { ?>
                            <h5 class="section-subtitle"><?php echo wp_kses_post($busicare_home_news_section_discription); ?></h5>
                        <?php } ?>
                    </div>
                </div>                      
            </div>
            <!-- /Section Title -->
        <?php } ?>
        <div class="row">
            <?php
            $busicare_no_of_post = get_theme_mod('busicare_homeblog_counts', 3);
            $busicare_args = array('post_type' => 'post', 'post__not_in' => get_option("sticky_posts"), 'posts_per_page' => $busicare_no_of_post);
            query_posts($busicare_args);
            if (query_posts($busicare_args)) {
                while (have_posts()):the_post();
                    {
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <article class="post">  
                                <?php if (has_post_thumbnail()) { ?>
                                    <figure class="post-thumbnail">
                                        <?php $busicare_defalt_arg = array('class' => "img-fluid"); ?>
                                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('', $busicare_defalt_arg); ?></a>                            
                                    </figure>   
                                <?php } ?>
                                <div class="post-content">

                                    <?php if ($busicare_home_meta_section_settings == true) { ?>
                                        <div class="entry-date <?php
                                        if (!has_post_thumbnail()) {
                                            echo 'remove-image';
                                        }
                                        ?>">
                                            <a href="<?php echo esc_url(home_url('/')); ?>/<?php echo esc_html(date('Y/m', strtotime(get_the_date()))); ?>"><time><?php echo esc_html(get_the_date()); ?></time></a>
                                        </div>
                                    <?php } ?>


                                    <?php if ($busicare_home_meta_section_settings == true) { ?>
                                        <div class="entry-meta">
                                            <span class="author"><?php
                                                echo esc_html__('By', 'spicebox');
                                                echo '&nbsp;';
                                                ?><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a></span>
                                            <?php
                                            $busicare_cat_list = get_the_category_list();
                                            if (!empty($busicare_cat_list)) {
                                                ?>
                                                <span class="cat-links"><?php
                                                    echo esc_html('in ','spicebox');
                                                    the_category(', ');
                                                    ?></span>
                                            <?php } ?>
                                        </div>  
                                    <?php } ?>

                                    <header class="entry-header">
                                        <h3 class="entry-title">
                                            <a class="home-blog-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                    </header>   

                                    <div class="entry-content">
                                        <?php the_excerpt(); ?>
                                        <?php if(get_theme_mod('home_news_button_title','Cras Vitae')!=''):?>
                                        <p><a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html(get_theme_mod('home_news_button_title', __('Read More', 'spicebox'))); ?><i class="fa <?php if(is_rtl()){echo 'fa-long-arrow-left';} else{ echo 'fa-long-arrow-right';}?>"></i></a></p>
                                        <?php endif;?>
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

        <?php if (!empty($busicare_index_more_btn)): ?>
            <div class="row index_extend_class">
                <div class="mx-auto mt-5">
                    <a href="<?php echo esc_url($busicare_index_news_link); ?>" class="btn-small btn-default-dark business-view-more-post" <?php
                       if (get_theme_mod('home_blog_more_btn_link_target', false) == true) {
                           echo "target='_blank'";
                       };
                       ?>><?php echo esc_html($busicare_index_more_btn); ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php
endif;
} ?>