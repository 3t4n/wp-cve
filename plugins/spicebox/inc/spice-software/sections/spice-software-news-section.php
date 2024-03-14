<?php 
/* Call the action for news section */
add_action('spiceb_spice_software_news_action','spiceb_spice_software_news_section');
/* Function for news section*/
function spiceb_spice_software_news_section(){
$spice_software_index_news_link = get_theme_mod('home_blog_more_btn_link', __('#', 'spicebox'));
$spice_software_index_more_btn = get_theme_mod('home_blog_more_btn', __('Cras Vitae', 'spicebox'));
if (empty($spice_software_index_news_link)) {
    $spice_software_index_news_link = '#';
}
if(get_theme_mod('latest_news_section_enable',true)==true):
    $theme=wp_get_theme();?>
<!-- Latest News section -->
<section class="section-space blog home-blog bg-default">
    <div class="container">
        <?php
        $spice_software_home_news_section_title = get_theme_mod('home_news_section_title', __('Vitae Lacinia', 'spicebox'));
        $spice_software_home_news_section_discription = get_theme_mod('home_news_section_discription', __('Cras Vitae Placerat', 'spicebox'));
        $spice_software_home_meta_section_settings = get_theme_mod('home_meta_section_settings', true);
        if (($spice_software_home_news_section_title) || ($spice_software_home_news_section_discription) != '') {
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="section-header">
                        <?php if ($spice_software_home_news_section_title) { ?>
                            <h2 class="section-title"><?php echo esc_html($spice_software_home_news_section_title); ?></h2>
                            <div class="title_seprater"></div>
                        <?php } ?>
                        <?php if ($spice_software_home_news_section_discription) { ?>
                            <h5 class="section-subtitle"><?php echo wp_kses_post($spice_software_home_news_section_discription); ?></h5>
                        <?php } ?>
                    </div>
                </div>                      
            </div>
            <!-- /Section Title -->
        <?php } ?>
        <div class="row">
            <?php
            $spice_software_dark=0;
            if ('Spice Software Dark' == $theme->name){?>
                <div class="col-lg-12 col-md-12 col-sm-12 list-view">
                <?php 
                $spice_software_no_of_post = get_theme_mod('spice_software_homeblog_counts', 3);
                $spice_software_args = array('post_type' => 'post', 'post__not_in' => get_option("sticky_posts"), 'posts_per_page' => $spice_software_no_of_post);
                query_posts($spice_software_args);
                if (query_posts($spice_software_args)) {
                    while (have_posts()):the_post();{?>
                    <article class="post media <?php if($spice_software_dark%2!=0 && has_post_thumbnail()): echo 'right'; endif;?>">  
                        <?php 
                        if($spice_software_dark%2==0):
                            if (has_post_thumbnail()) { ?>
                            <figure class="post-thumbnail">
                                <?php $spice_software_defalt_arg = array('class' => "img-fluid"); ?>
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('', $spice_software_defalt_arg); ?></a>                            
                            </figure>   
                            <?php }
                        endif; ?>
                        <div class="post-content media-body">
                            <?php if(!has_post_thumbnail() || $spice_software_dark%2==0):
                            if ($spice_software_home_meta_section_settings == true) { ?>
                                <div class="<?php if(!has_post_thumbnail()){echo 'remove-image';}else{echo 'entry-date';}?>">
                                    <a href="<?php echo esc_url(home_url('/')); ?>/<?php echo esc_html(date('Y/m', strtotime(get_the_date()))); ?>"><span class="date"><?php echo esc_html(get_the_date()); ?></span></a>
                                </div>
                            <?php } 
                            endif;

                            if ($spice_software_home_meta_section_settings == true) { ?>
                                <div class="entry-meta">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"> <i class="fa fa-user"></i><span class="author postauthor"><?php echo esc_html(get_the_author()); ?></span></a>
                                    <?php
                                    $spice_software_cat_list = get_the_category_list();
                                    if (!empty($spice_software_cat_list)) {?>
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
                                 $blog_button = get_theme_mod('home_news_button_title', __('Cras Vitae', 'spicebox'));
                                 if (!empty($blog_button)) {?>
                                <p><a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html($blog_button); ?> <i class="fa <?php if(is_rtl()){echo 'fa-long-arrow-left';} else{ echo 'fa-long-arrow-right';}?>"></i></a></p>
                                <?php } ?>
                            </div> 

                        </div>
                        <?php if($spice_software_dark%2!=0 && has_post_thumbnail()):
                            if ($spice_software_home_meta_section_settings == true) { ?>
                                <div class="<?php if(!has_post_thumbnail()){echo 'remove-image';}else{echo 'entry-date';}?>">
                                    <a href="<?php echo esc_url(home_url('/')); ?>/<?php echo esc_html(date('Y/m', strtotime(get_the_date()))); ?>"><span class="date"><?php echo esc_html(get_the_date()); ?></span></a>
                                </div>
                            <?php } 

                            if (has_post_thumbnail()) { ?>
                                <figure class="post-thumbnail">
                                    <?php $spice_software_defalt_arg = array('class' => "img-fluid"); ?>
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('', $spice_software_defalt_arg); ?></a>                            
                                </figure>   
                            <?php } 
                        endif;?>          
                    </article>
                    <?php
                    $spice_software_dark++;
                        }
                endwhile;
                }?>            
            </div>
            <?php  
            }
            else{
                $spice_software_no_of_post = get_theme_mod('spice_software_homeblog_counts', 3);
                $spice_software_args = array('post_type' => 'post', 'post__not_in' => get_option("sticky_posts"), 'posts_per_page' => $spice_software_no_of_post);
                query_posts($spice_software_args);
                if (query_posts($spice_software_args)) {
                    while (have_posts()):the_post();{?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <article class="post">  
                                <?php if (has_post_thumbnail()) { ?>
                                    <figure class="post-thumbnail">
                                        <?php $spice_software_defalt_arg = array('class' => "img-fluid"); ?>
                                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('', $spice_software_defalt_arg); ?></a>                            
                                    </figure>   
                                <?php } ?>
                                <div class="post-content">

                                    <?php if ($spice_software_home_meta_section_settings == true) { ?>
                                        <div class="<?php if(!has_post_thumbnail()){echo 'remove-image';}else{echo 'entry-date';}?>">
                                            <a href="<?php echo esc_url(home_url('/')); ?>/<?php echo esc_html(date('Y/m', strtotime(get_the_date()))); ?>"><span class="date"><?php echo esc_html(get_the_date()); ?></span></a>
                                        </div>
                                    <?php } ?>


                                    <?php if ($spice_software_home_meta_section_settings == true) { ?>
                                        <div class="entry-meta">
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"> <i class="fa fa-user"></i><span class="author postauthor"><?php echo esc_html(get_the_author()); ?></span></a>
                                            <?php
                                            $spice_software_cat_list = get_the_category_list();
                                            if (!empty($spice_software_cat_list)) {?>
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
                                         $blog_button = get_theme_mod('home_news_button_title', __('Cras Vitae', 'spicebox'));
                                         if (!empty($blog_button)) {?>
                                        <p><a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html($blog_button); ?> <i class="fa <?php if(is_rtl()){echo 'fa-long-arrow-left';} else{ echo 'fa-long-arrow-right';}?>"></i></a></p>
                                        <?php } ?>
                                    </div>  
                                </div>          
                            </article>
                        </div>
                            <?php
                        }
                    endwhile;
                }            
            }?>            
        </div>

        <?php if (!empty($spice_software_index_more_btn)): ?>
            <div class="row index_extend_class">
                <div class="mx-auto mt-5">
                    <a href="<?php echo esc_url($spice_software_index_news_link); ?>" class="btn-small btn-default-dark business-view-more-post" <?php
                       if (get_theme_mod('home_blog_more_btn_link_target', false) == true) {
                           echo "target='_blank'";
                       };
                       ?>><?php echo esc_html($spice_software_index_more_btn); ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php
endif;
} ?>