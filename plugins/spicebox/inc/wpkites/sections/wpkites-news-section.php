<?php 
add_action('spiceb_wpkites_news_action','spiceb_wpkites_news_section');
/* Function for news section*/
function spiceb_wpkites_news_section()
{
$newz_animation_speed = get_theme_mod('news_animation_speed', 3000);
$newz_smooth_speed = get_theme_mod('news_smooth_speed', 1000);
$isRTL = (is_rtl()) ? (bool) true : (bool) false;
$newz_slide_layout = get_theme_mod('wpkites_homeblog_layout',4);
if($newz_slide_layout==4){
    $newz_slide_items=3;
}elseif($newz_slide_layout==6){
  $newz_slide_items=2;
}
$newz_nav_style = get_theme_mod('news_nav_style', 'bullets');
$newzsettings = array('slide_items' => $newz_slide_items, 'animationSpeed' => $newz_animation_speed, 'smoothSpeed' => $newz_smooth_speed, 'newz_nav_style' => $newz_nav_style, 'rtl' => $isRTL);
wp_register_script('wpkites-blog', SPICEB_PLUGIN_URL . 'inc/wpkites/js/front-page/blog.js', array('jquery'));
wp_localize_script('wpkites-blog','newz_settings', $newzsettings);
wp_enqueue_script('wpkites-blog');
$latest_news_section_enable = get_theme_mod('latest_news_section_enable', true);
if ($latest_news_section_enable != false) {
    ?>
    <!-- Latest News section -->
    <section class="section-space blog bg-default-color home-blog">
        <div class="wpkites-newz container">
            <?php
            $home_news_section_title = get_theme_mod('home_news_section_title', __('Vitae Lacinia', 'spicebox'));
            $home_news_section_discription = get_theme_mod('home_news_section_discription', __('Cras Vitae Placerat', 'spicebox'));
            $home_meta_section_settings = get_theme_mod('home_meta_section_settings', true);
            if (($home_news_section_title) || ($home_news_section_discription) != '') {
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="section-header">                            
                            <?php if($home_news_section_title){?><h2 class="section-title"><?php echo esc_html($home_news_section_title); ?></h2><?php }
                            if ($home_news_section_discription){?><h5 class="section-subtitle"><?php echo esc_html($home_news_section_discription); ?></h5>
                            <?php } ?>
                            <div class="separator"><i class="fa fa-crosshairs"></i></div>
                        </div>
                    </div>                      
                </div>
                <!-- /Section Title -->
            <?php } ?>
            <div class="row">
                <?php
                $no_of_post = 4;
                $args = array('post_type' => 'post', 'post__not_in' => get_option("sticky_posts"), 'posts_per_page' => $no_of_post);
                query_posts($args);
                if (query_posts($args)) {?>
                    <div id="blog-carousel1" class="owl-carousel owl-theme col-lg-12">
                    <?php
                    while (have_posts()):the_post(); {?>
                        <div class="item">
                            <article class="post">
                                 <?php if (has_post_thumbnail()) { ?>
                                <figure class="post-thumbnail">                     
                                    <?php 
                                    $defalt_arg = array('class' => "img-fluid");
                                    the_post_thumbnail('', $defalt_arg); ?>
                                </figure>
                                <?php } ?>
                                <div class="post-content <?php if(!has_post_thumbnail()){ echo 'remove-images';}?>"> 
                                    <?php
                                    if ($home_meta_section_settings == true) {?> 
                                            <div class="entry-date <?php if(!has_post_thumbnail()){ echo 'remove-image';}?>">
                                                <a href="<?php echo esc_url(home_url()).'/'.esc_html(date('Y/m', strtotime(get_the_date()))); ?>">
                                                    <?php echo esc_html(get_the_date()); ?>
                                                </a>
                                            </div>                                      

                                        <div class="entry-meta">
                                            <i class="fa fa-user"></i><span class="author postauthor"><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                <?php echo esc_html(get_the_author()); ?></a></span>
                                            

                                            <?php
                                            $cat_list = get_the_category_list();
                                            if (!empty($cat_list)) {?>
                                                <i class="fa fa-folder-open"></i><span class="cat-links postcat"><?php the_category(', '); ?></span>
                                            <?php } 
                                            
                                            $commt = get_comments_number();
                                            if (!empty($commt)) {
                                                ?>
                                                <i class="fa fa-comment-o"></i><span class="cat-links"><a href="<?php the_permalink();?>"><?php echo esc_html(get_comments_number());?></a></span>
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
                                            <p>
                                                <a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html($blog_button); ?>
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </article>
                        </div>                        
                        <?php
                    }
                    endwhile;?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
<?php } 
} ?>