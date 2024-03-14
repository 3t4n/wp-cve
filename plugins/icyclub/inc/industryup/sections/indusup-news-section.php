<?php
//News
if ( ! function_exists( 'icycp_industryup_news' ) ) :

function icycp_industryup_news() {

$news_section_show         = get_theme_mod('news_section_show','1');
if($news_section_show == '1') {
$news_section_title = get_theme_mod('news_section_title',__('Latest News','industryup'));
$news_section_subtitle = get_theme_mod('news_section_subtitle','Our Blog');

$news_section_description= get_theme_mod('news_section_description','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
$news_section_post_count = get_theme_mod('news_section_post_count', __('3','industryup'));
?>
<!--==================== BLOG SECTION ====================-->
  <section id="news-section" class="bs-section blog">
    <!--overlay-->
    <div class="overlay">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="col text-center">
          <div class="bs-heading">
          	<h3 class="bs-subtitle"><?php echo $news_section_title; ?></h3>
            <div class="clearfix"></div>
              <h2 class="bs-title"><?php echo $news_section_subtitle;?></h2>
			         <p><?php echo esc_html($news_section_description);?></p>
          </div>
        </div>
        <!--/row-->
        <!--row-->
        <div class="row">
          <!--col-md-4-->
          <?php $consultup_latest_loop = new WP_Query(array( 'post_type' => 'post', 'posts_per_page' => $news_section_post_count, 'order' => 'DESC','ignore_sticky_posts' => true, ''));
			if ( $consultup_latest_loop->have_posts() ) :
			$i = 1;
			 while ( $consultup_latest_loop->have_posts() ) : $consultup_latest_loop->the_post();?>
		  <div class="col-md-4">
            <div class="bs-blog-post shd"> 
              <div class="bs-blog-thumb">
                <?php 
                if(has_post_thumbnail()){
                echo '<a  href="'.esc_url(get_the_permalink()).'">';
                the_post_thumbnail( '', array( 'class'=>'img-fluid' ) );
                echo '</a>';
                ?>
                <?php } ?>
              </div>
              <article class="small">
                  <div class="bs-blog-category"> <?php $cat_list = get_the_category_list();
				if(!empty($cat_list)) { ?>
                <?php the_category(', '); ?>
                <?php } ?> </div>
                  <h4 class="title sm"><a title="<?php the_title(); ?>" href="<?php the_permalink();?>"><?php the_title(); ?></a></h4>
                    <div class="bs-blog-meta"> 
                      <span class="bs-blog-date"><a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
				  <?php echo esc_html(get_the_date('M j, Y')); ?></a></span> 
                      <span class="bs-author"><a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php the_author(); ?></a> </span>
                      <span class="comments-link"> <a href="#">2 Comments</a> </span>
                    </div>
                    <?php $consultup_post_content_type = get_theme_mod('consultup_post_content_type','content'); 
				if($consultup_post_content_type == 'content') {
				 the_content(__('Read More','consultup'));
					wp_link_pages( array( 'before' => '<div class="link btn-0">' . __( 'Pages:', 'industryup' ), 'after' => '</div>' ) ); }
					elseif($consultup_post_content_type == 'excerpt')
					{ ?>
						<p><?php echo icyclub_news_excerpt(); ?></p>

					<?php } ?>
			</article>
            </div>
          </div>
		  <?php 
		  if($i==3)
			  { 
			     echo '<div class="clearfix"></div>';
				 $i=0;
			  }$i++;

		endwhile; endif;	wp_reset_postdata(); ?>
          <!--/col-md-4-->
        </div>
        <!--/row-->
      </div>
      <!--/col-md-6-->
    </div>
    <!--/col-md-6-->
  </section>
<?php } }
endif;

		if ( function_exists( 'icycp_industryup_news' ) ) {
		$section_priority = apply_filters( 'icycp_industryup_homepage_section_priority', 15, 'icycp_industryup_news' );
		add_action( 'icycp_industryup_homepage_sections', 'icycp_industryup_news', absint( $section_priority ) );
}