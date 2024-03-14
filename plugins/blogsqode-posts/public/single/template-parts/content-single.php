<?php $single_layout = esc_attr(get_option('blogsqode_singlepage_layout'));	?>

<article id="single-layout-<?php echo esc_attr($single_layout); ?>" <?php post_class(); ?>>

	<div class="blogsqode-entry-header alignwide">

		<?php the_title( '<h2 class="entry-title blogsqode-single-post-title">', '</h2>' ); ?>


		<div class="blog-single-thumbnail-wrap" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">
		</div>
		

		<?php if(esc_attr($single_layout) === '1'){ 
			echo user_detail_meta_func();			
		} ?>

	</div><!-- .blogsqode-entry-header -->

	<div class="blogsqode-entry-content">
		<?php
		the_content();
		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'blogsqode' ) . '">',
				'after'    => '</nav>',
				/* translators: %: Page number. */
				'pagelink' => esc_html__( 'Page %', 'blogsqode' ),
			)
		);
		?>
	</div><!-- .blogsqode-entry-content -->

	<div class="blogsqode-tag-social">
		<?php 	if(esc_attr(get_option('blogsqode_single_tags_allow')) === 'Unable' && esc_attr($single_layout) === '1'){
			echo blogsqode_single_tags(); 
		} 

		if(esc_attr(get_option('blogsqode_single_sharebutton_allow')) === 'Unable' && esc_attr($single_layout) === '1'){	
			$permalink = get_the_permalink();
			echo '<div class="blogsqode-single-social-share"><h3>'.esc_html__("Share:", "blogsqode").'</h3>';
			$fb_icon = get_option('facebook_social_icon')?:BLOGSQODE_IMG_PATH.'/facebook.svg';
			echo '<a class="blogsqode-social-icon" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.esc_url($permalink).'"><img src='.esc_url($fb_icon).' alt="fb-share-icon" /></a>';
			$tw_icon = get_option('twitter_social_icon')?:BLOGSQODE_IMG_PATH.'/twitter.svg';
			echo '<a class="blogsqode-social-icon" target="_blank" href="https://twitter.com/intent/tweet?text='.get_the_title()." ".esc_url($permalink).'"><img src="'.esc_url($tw_icon).'"></a>';

			$ln_icon = get_option('linkedin_social_icon')?:BLOGSQODE_IMG_PATH.'/linkedin.svg';
			echo '<a class="blogsqode-social-icon" target="_blank" href="https://www.linkedin.com/uas/login?session_redirect='.get_the_title()." ".esc_url($permalink).'"><img src="'.esc_url($ln_icon).'"></a>';

			$pn_icon = get_option('pinterest_social_icon')?:BLOGSQODE_IMG_PATH.'/pinterest.svg';
			echo '<a class="blogsqode-social-icon" target="_blank" href="https://pinterest.com/pin/create/button/?url='.esc_url($permalink).'"><img src="'.esc_url($pn_icon).'"></a>';

			$wp_icon = get_option('whatsapp_social_icon')?:BLOGSQODE_IMG_PATH.'/whatsapp.svg';
			echo '<a class="blogsqode-social-icon" target="_blank" href="https://web.whatsapp.com/send?text='.esc_url($permalink).'" data-action="share/whatsapp/share"><img src="'.esc_url($wp_icon).'"></a>';

			$sp_icon = get_option('snapchat_social_icon')?:BLOGSQODE_IMG_PATH.'/snapchat.svg';
			echo '<a class="blogsqode-social-icon" target="_blank" href="https://www.snapchat.com/scan?attachmentUrl='.esc_url($permalink).'"><img src="'.esc_url($sp_icon).'"></a>';

			$wc_icon = get_option('wechat_social_icon')?:BLOGSQODE_IMG_PATH.'/wechat.svg';
			echo '<a class="blogsqode-social-icon" target="_blank" href="https://api.qrserver.com/v1/create-qr-code/?size=154x154&data='.esc_url($permalink).'"><img src="'.esc_url($wc_icon).'"></a>';

			echo '</div>';
		} ?>
	</div>
</article>

<?php 
function user_detail_meta_func(){
	$single_layout = esc_attr(get_option('blogsqode_singlepage_layout'));
	$blog_date_allow = esc_attr(get_option('blogsqode_single_blog_date_allow'));
	$author_name_allow = esc_attr(get_option('blogsqode_single_author_name_allow'));
	ob_start();
	if(esc_attr($single_layout) === '1'){
		?>
		<div class="blogsqode-single-post-foot">
			<div class="blogsqode-author-wrap">
				<?php if(esc_attr(get_option('blogsqode_single_auhtor_thumb_allow')) === 'Unable'){
					if(($avatar = get_avatar(get_the_author_meta(get_the_ID()))) !== FALSE): 
						echo wp_kses_post($avatar); 
					else: ?>
						<img src="/images/no-image-default.jpg">
					<?php endif; 
				}
				?> 
				<div class="blogsqode-authorname-date">
					<?php

					if(esc_attr($author_name_allow) === 'Unable' && esc_attr($blog_date_allow) === 'Unable'){
						echo '<h3 class="blogsqode-author-name">'.get_the_author_meta('display_name').'</h3>';
					} else {
						echo '<h3 class="blogsqode-author-name">'.get_the_author_meta('display_name').'</h3>';
					}

					if(esc_attr($blog_date_allow) === 'Unable'){
						echo '<p class="blogsqode-post-date">'.get_the_date().'</p>';
					}
					?>
				</div>
			</div>

			<div class="blogsqode-single-count-cat">
				<?php
				if(esc_attr(get_option('blogsqode_single_comment_count_allow')) === 'Unable'){
					echo '<p class="blogsqode-comments-number">'.get_comments_number().' Comment</p>';
				}

				$cats = get_the_category();
				$cat = $cats[0];

				if(esc_attr(get_option('blogsqode_single_category_allow')) === 'Unable' ){
					$cat_link = get_category_link($cat->term_id);
					echo '<a href="'.esc_url($cat_link).'" class="blogsqode_post_cat">'.esc_html($cat->cat_name).'</a>'; 
				}
				?>
			</div>
		</div>
	<?php }

	return ob_get_clean();
}

function blogsqode_single_tags(){
	$single_layout = $single_layout;
	ob_start();
		echo '<div class="blogsqode-single-tags"><h3>'.esc_html__("Tags:", "blogsqode").'</h3>';
	$post_tags = get_the_tags();
	if ( $post_tags ) {
		foreach( $post_tags as $tag ) {
			$tag_link = get_tag_link($tag->term_id);
			echo '<a href="'.esc_url($tag_link).'" class="blogsqode-single-tags-link">'.esc_html($tag->name) .'</a>'; 
		}
	}
	echo '</div>';
	$return = ob_get_clean();

	return $return;
}