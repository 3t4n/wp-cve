<?php

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Blogsqode
 * @subpackage Blogsqode/public
 * @author     The_Krishna
 */
class Blogsqode_Public_Templates {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}
	public function layout_one(?array $settings, $layout){
		ob_start();

		?> 
		<div class="blogsqode-post-item">
			<div class="blogsqode-post-item-inner">
				<div class="blog-thumbnail-wrap layout-<?php echo esc_attr($layout); ?>" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>')">
				</div>			
				<div class="content">
					<div class="blogsqode_extra_content">

						<?php
						$cats = get_the_category();
						$cat = $cats[0];
						if(esc_attr($settings['blogsqode_category_allow']) === 'unable'){
							echo '<h5 class="blogsqode_post_cat">'.esc_html($cat->cat_name).'</h5>'; 
						}
						$text = get_the_content();
						if(esc_attr($settings['blogsqode_read_time_allow']) === 'unable'){
							echo '<span class="blogsqode-post-readtime"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.99999 11.8333C2.77824 11.8333 0.166656 9.22175 0.166656 6C0.166656 2.77825 2.77824 0.166666 5.99999 0.166666C9.22174 0.166666 11.8333 2.77825 11.8333 6C11.8333 9.22175 9.22174 11.8333 5.99999 11.8333ZM5.99999 10.6667C7.23767 10.6667 8.42465 10.175 9.29982 9.29983C10.175 8.42466 10.6667 7.23768 10.6667 6C10.6667 4.76232 10.175 3.57534 9.29982 2.70017C8.42465 1.825 7.23767 1.33333 5.99999 1.33333C4.76231 1.33333 3.57533 1.825 2.70016 2.70017C1.82499 3.57534 1.33332 4.76232 1.33332 6C1.33332 7.23768 1.82499 8.42466 2.70016 9.29983C3.57533 10.175 4.76231 10.6667 5.99999 10.6667ZM6.58332 6H8.91666V7.16667H5.41666V3.08333H6.58332V6Z" fill="#808080"/></svg>'.$this->get_readtime($text).esc_html__("min read", "blogsqode").' </span>';
						}
						?>

					</div>	
					<div class="blogsqode-author-wrap">
						<?php if(esc_attr($settings['blogsqode_auhtor_thumb_allow']) === 'unable'){
							if(($avatar = get_avatar(get_the_author_meta(get_the_ID()))) !== FALSE): 
								echo $avatar; 
							else: ?>
								<img src="/images/no-image-default.jpg" class="blogsqode-auhtor-thumb-img " alt="Author Image" width="40" height="40">
							<?php endif; 
						}
						?> 
						<div class="blogsqode-authorname-date">
							<?php
							if(esc_attr($settings['blogsqode_author_name_allow']) === 'unable'){
								echo '<h3 class="blogsqode-author-name">'.get_the_author_meta('display_name').'</h3>';
							}

							if(esc_attr($settings['blogsqode_blog_date_allow']) === 'unable'){
								echo '<p class="blogsqode-post-date">'.get_the_date().'</p>';
							}
							?>
						</div>
					</div>
					<h2 class="blogsqode_post_title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php if(esc_attr($settings['blogsqode_short_desc_allow']) === 'unable'){
						echo '<p class="blogsqode_post_sort_desc">'.esc_html(strip_tags($text)).'</p>';
					}
					?>

					<div class="bottom">
						<?php if(esc_attr($settings['blogsqode_read_more_btn_allow']) === 'unable'){
							$rmlayout = esc_attr($settings['blogsqode_read_more_button_layout'])?:1;
							echo $this->get_readmore_layout($rmlayout);
						}



						if(esc_attr($settings['blogsqode_comment_count_allow']) === 'unable'){
							echo '<p class="blogsqode-comments-number">'.get_comments_number().esc_html__(" Comment", "blogsqode").'</p>';
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<?php
		return ob_get_clean();
	}
	public function layout_two(?array $settings, $layout){
		ob_start();
		?>
		<div class="blogsqode-post-item">
			<div class="blogsqode-post-item-inner">
				<div class="blog-thumbnail-wrap layout-<?php echo esc_attr($layout); ?> " style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">
					<?php
					$cats = get_the_category();
					$cat = $cats[0];
					if(esc_attr($settings['blogsqode_category_allow']) === 'unable') {
						echo '<h5 class="blogsqode_post_cat">'.esc_html($cat->cat_name).'</h5>';  
					} ?>
				</div>

				<div class="content">
					<h2 class="blogsqode_post_title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h2>

					<?php
					$text = get_the_content();
					if(esc_attr($settings['blogsqode_short_desc_allow']) === 'unable'){
						echo '<p class="blogsqode_post_sort_desc">'.esc_html(strip_tags($text)).'</p>';
					}

					if(esc_attr($settings['blogsqode_read_more_btn_allow']) === 'unable'){
						$rmlayout = esc_attr($settings['blogsqode_read_more_button_layout'])?:1;
						echo $this->get_readmore_layout($rmlayout);
					}?>
					<div class="bottom">
						<div class="blogsqode-author-wrap">
							<?php if(esc_attr($settings['blogsqode_auhtor_thumb_allow']) === 'unable'){
								if(($avatar = get_avatar(get_the_author_meta(get_the_ID()))) !== FALSE): 
									echo $avatar; 
								else: ?>
									<img src="/images/no-image-default.jpg" class="blogsqode-auhtor-thumb-img " alt="Author Image" height="40" width="40">
								<?php endif; 
							} ?> 
							<div class="blogsqode-authorname-date">
								<?php
								if(esc_attr($settings['blogsqode_author_name_allow']) === 'unable'){
									echo '<h3 class="blogsqode-author-name">'.get_the_author_meta('display_name').'</h3>';
								}

								if(esc_attr($settings['blogsqode_blog_date_allow']) === 'unable'){
									echo '<p class="blogsqode-post-date">'.get_the_date().'</p>'; 
								} ?>
							</div>
						</div>
						<?php if(esc_attr($settings['blogsqode_comment_count_allow']) === 'unable'){
							echo '<span class="blogsqode-comments-number">'.get_comments_number().esc_html__(" Comment", "blogsqode").'</span>';
						} ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}	

	public function layout_four(?array $settings, $layout){
		ob_start();
		?>
		<div class="blogsqode-post-item">
			<div class="blogsqode-post-item-inner">
				<?php
				$cats = get_the_category();
				$cat = $cats[0];
				if(esc_attr($settings['blogsqode_category_allow']) == 'unable') {
					echo '<h5 class="blogsqode_post_cat">'.esc_html($cat->cat_name).'</h5>';  
				} ?>
				<div class="blog-thumbnail-wrap layout-<?php echo esc_attr($layout); ?> " style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">

				</div>

				<div class="content">
					<div class="blogsqode-author-wrap">
						<?php if(esc_attr($settings['blogsqode_auhtor_thumb_allow']) === 'unable'){
							if(($avatar = get_avatar(get_the_author_meta(get_the_ID()))) !== FALSE): 
								echo $avatar; 
							else: ?>
								<img src="/images/no-image-default.jpg" class="blogsqode-auhtor-thumb-img " alt="Author Image" height="40" width="40">
							<?php endif; 
						} ?> 
						<div class="blogsqode-authorname-date">
							<?php
							if(esc_attr($settings['blogsqode_author_name_allow']) === 'unable'){
								echo '<h3 class="blogsqode-author-name">'.get_the_author_meta('display_name').'</h3>';
							}

							if(esc_attr($settings['blogsqode_blog_date_allow']) === 'unable'){
								echo '<p class="blogsqode-post-date">'.get_the_date().'</p>'; 
							} ?>
						</div>

					</div>
					<?php if(esc_attr($settings['blogsqode_comment_count_allow']) === 'unable'){
						echo '<span class="blogsqode-comments-number">'.get_comments_number().esc_html__(" Comment", "blogsqode").'</span>'; 
					} ?>
					<h2 class="blogsqode_post_title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h2>

					<?php
					$text = get_the_content();
					if(esc_attr($settings['blogsqode_short_desc_allow']) === 'unable'){
						echo '<p class="blogsqode_post_sort_desc">'.esc_html(strip_tags($text)).'</p>';
					}

					?>
					<div class="bottom">
						<?php
						if(esc_attr($settings['blogsqode_read_more_btn_allow']) === 'unable'){
							$rmlayout = esc_attr($settings['blogsqode_read_more_button_layout'])?:1;
							echo $this->get_readmore_layout($rmlayout);
						}?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}	

	public function layout_five(?array $settings, $layout){
		ob_start();
		?>
		<div class="blogsqode-post-item">
			<div class="blogsqode-post-item-inner">
				<div class="blog-thumbnail-wrap layout-<?php echo esc_attr($layout); ?> " style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">

				</div>

				<div class="content">
					<div class="layout-5-top">
						<?php
						$cats = get_the_category();
						$cat = $cats[0];
						if(esc_attr($settings['blogsqode_category_allow']) === 'unable') {
							echo '<h5 class="blogsqode_post_cat">'.esc_html($cat->cat_name).'</h5>';  
						}
						if(esc_attr($settings['blogsqode_blog_date_allow']) === 'unable'){
							echo '<p class="blogsqode-post-date">'.get_the_date().'</p>';
						}
						?>
					</div>
					<h2 class="blogsqode_post_title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php
					$text = get_the_content();
					if(esc_attr($settings['blogsqode_short_desc_allow']) === 'unable'){
						echo '<p class="blogsqode_post_sort_desc">'.esc_html(strip_tags($text)).'</p>';
					}

					if(esc_attr($settings['blogsqode_read_more_btn_allow']) === 'unable'){
						$rmlayout = esc_attr($settings['blogsqode_read_more_button_layout'])?:1;
						echo $this->get_readmore_layout($rmlayout);
					}?>
					<div class="bottom">
						<div class="blogsqode-author-wrap">
							<?php if(esc_attr($settings['blogsqode_auhtor_thumb_allow']) === 'unable'){
								if(($avatar = get_avatar(get_the_author_meta(get_the_ID()))) !== FALSE): 
									echo $avatar; 
								else: ?>
									<img src="/images/no-image-default.jpg" class="blogsqode-auhtor-thumb-img " alt="Author Image" height="40" width="40">
								<?php endif; 
							} ?> 
							<div class="blogsqode-authorname-date">
								<?php
								if(esc_attr($settings['blogsqode_author_name_allow']) === 'unable'){
									echo '<h3 class="blogsqode-author-name">'.get_the_author_meta('display_name').'</h3>';
								}

								?>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}	

	public function layout_six(?array $settings, $layout){
		ob_start();
		?>
		<div class="blogsqode-post-item">
			<div class="blogsqode-post-item-inner">
				<div class="layout-6-top">
					<?php
					$cats = get_the_category();
					$cat = $cats[0];
					if(esc_attr($settings['blogsqode_category_allow']) === 'unable') {
						echo '<h5 class="blogsqode_post_cat">'.esc_html($cat->cat_name).'</h5>';  
					}
					if(esc_attr($settings['blogsqode_blog_date_allow']) === 'unable'){
						echo '<p class="blogsqode-post-date">'.get_the_date().'</p>';
					}
					?>
				</div>
				<div class="blog-thumbnail-wrap layout-<?php echo esc_attr($layout); ?> " style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">
				</div>

				<div class="content">
					
					<h2 class="blogsqode_post_title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php
					$text = get_the_content();
					if(esc_attr($settings['blogsqode_short_desc_allow']) === 'unable'){
						echo '<p class="blogsqode_post_sort_desc">'.esc_html(strip_tags($text)).'</p>';
					}

					if(esc_attr($settings['blogsqode_read_more_btn_allow']) === 'unable'){
						$rmlayout = esc_attr($settings['blogsqode_read_more_button_layout'])?:1;
						echo $this->get_readmore_layout($rmlayout);
					}?>
					<div class="bottom layout-6-bottom">
						<div class="blogsqode-author-wrap">
							<?php if(esc_attr($settings['blogsqode_auhtor_thumb_allow']) === 'unable'){
								if(($avatar = get_avatar(get_the_author_meta(get_the_ID()))) !== FALSE): 
									echo $avatar; 
								else: ?>
									<img src="/images/no-image-default.jpg" class="blogsqode-auhtor-thumb-img " alt="Author Image" height="40" width="40">
								<?php endif; 
							} ?> 
							<div class="blogsqode-authorname-date">
								<?php
								if(esc_attr($settings['blogsqode_author_name_allow']) === 'unable'){
									echo '<h3 class="blogsqode-author-name">'.get_the_author_meta('display_name').'</h3>';
								}

								?>
							</div>
						</div>
						<?php if(esc_attr($settings['blogsqode_comment_count_allow']) === 'unable'){
							echo '<span class="blogsqode-comments-number">'.get_comments_number().esc_html__(" Comment", "blogsqode").'</span>';
						} ?>

					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}	

	public function get_social_sharing_html(){
		?>
		<div id="wrapper" class="social_share">
			<input type="checkbox" class="checkbox" id="share" />
			<label for="share" class="label entypo-export">
				<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_608_444)">
						<path d="M9.84015 12.7673L6.6909 11.0497C6.27975 11.4893 5.74589 11.795 5.15871 11.9272C4.57153 12.0593 3.95817 12.0118 3.39835 11.7908C2.83852 11.5698 2.35812 11.1855 2.01958 10.6878C1.68104 10.1902 1.5 9.60225 1.5 9.00037C1.5 8.3985 1.68104 7.81055 2.01958 7.31292C2.35812 6.81529 2.83852 6.43099 3.39835 6.20997C3.95817 5.98895 4.57153 5.94143 5.15871 6.07359C5.74589 6.20574 6.27975 6.51145 6.6909 6.951L9.8409 5.2335C9.66207 4.52556 9.74757 3.77669 10.0814 3.12728C10.4152 2.47786 10.9744 1.97249 11.6542 1.70588C12.3339 1.43927 13.0876 1.42973 13.7739 1.67905C14.4602 1.92837 15.032 2.41944 15.3821 3.0602C15.7323 3.70095 15.8367 4.44742 15.6758 5.15966C15.515 5.8719 15.0999 6.50103 14.5083 6.92911C13.9168 7.35719 13.1894 7.55483 12.4626 7.485C11.7358 7.41516 11.0594 7.08263 10.5602 6.54975L7.41015 8.26725C7.53106 8.74827 7.53106 9.25173 7.41015 9.73275L10.5594 11.4502C11.0586 10.9174 11.735 10.5848 12.4619 10.515C13.1887 10.4452 13.916 10.6428 14.5076 11.0709C15.0991 11.499 15.5142 12.1281 15.6751 12.8403C15.8359 13.5526 15.7315 14.299 15.3814 14.9398C15.0312 15.5806 14.4594 16.0716 13.7731 16.3209C13.0868 16.5703 12.3332 16.5607 11.6534 16.2941C10.9736 16.0275 10.4144 15.5221 10.0806 14.8727C9.74682 14.2233 9.66132 13.4744 9.84015 12.7665V12.7673ZM4.50015 10.5C4.89798 10.5 5.27951 10.342 5.56081 10.0607C5.84212 9.77936 6.00015 9.39782 6.00015 9C6.00015 8.60218 5.84212 8.22064 5.56081 7.93934C5.27951 7.65804 4.89798 7.5 4.50015 7.5C4.10233 7.5 3.7208 7.65804 3.43949 7.93934C3.15819 8.22064 3.00015 8.60218 3.00015 9C3.00015 9.39782 3.15819 9.77936 3.43949 10.0607C3.7208 10.342 4.10233 10.5 4.50015 10.5ZM12.7502 6C13.148 6 13.5295 5.84196 13.8108 5.56066C14.0921 5.27936 14.2502 4.89782 14.2502 4.5C14.2502 4.10218 14.0921 3.72064 13.8108 3.43934C13.5295 3.15804 13.148 3 12.7502 3C12.3523 3 11.9708 3.15804 11.6895 3.43934C11.4082 3.72064 11.2502 4.10218 11.2502 4.5C11.2502 4.89782 11.4082 5.27936 11.6895 5.56066C11.9708 5.84196 12.3523 6 12.7502 6ZM12.7502 15C13.148 15 13.5295 14.842 13.8108 14.5607C14.0921 14.2794 14.2502 13.8978 14.2502 13.5C14.2502 13.1022 14.0921 12.7206 13.8108 12.4393C13.5295 12.158 13.148 12 12.7502 12C12.3523 12 11.9708 12.158 11.6895 12.4393C11.4082 12.7206 11.2502 13.1022 11.2502 13.5C11.2502 13.8978 11.4082 14.2794 11.6895 14.5607C11.9708 14.842 12.3523 15 12.7502 15Z" fill="#808080"/>
					</g>
					<defs>
						<clipPath id="clip0_608_444">
							<rect width="18" height="18" fill="white"/>
						</clipPath>
					</defs>
				</svg>
			</label>
			<div class="social">
				<ul>
					<li class="facebook">
						<a href="https://www.facebook.com/" target="_blank">
							<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="30px" height="30px"><path d="M25,3C12.85,3,3,12.85,3,25c0,11.03,8.125,20.137,18.712,21.728V30.831h-5.443v-5.783h5.443v-3.848 c0-6.371,3.104-9.168,8.399-9.168c2.536,0,3.877,0.188,4.512,0.274v5.048h-3.612c-2.248,0-3.033,2.131-3.033,4.533v3.161h6.588 l-0.894,5.783h-5.694v15.944C38.716,45.318,47,36.137,47,25C47,12.85,37.15,3,25,3z"/></svg>
						</a>
					</li>
					<li class="twitter">
						<a href="https://twitter.com/" target="_blank">
							<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="30px" height="30px"><path d="M28,6.937c-0.957,0.425-1.985,0.711-3.064,0.84c1.102-0.66,1.947-1.705,2.345-2.951c-1.03,0.611-2.172,1.055-3.388,1.295 c-0.973-1.037-2.359-1.685-3.893-1.685c-2.946,0-5.334,2.389-5.334,5.334c0,0.418,0.048,0.826,0.138,1.215 c-4.433-0.222-8.363-2.346-10.995-5.574C3.351,6.199,3.088,7.115,3.088,8.094c0,1.85,0.941,3.483,2.372,4.439 c-0.874-0.028-1.697-0.268-2.416-0.667c0,0.023,0,0.044,0,0.067c0,2.585,1.838,4.741,4.279,5.23 c-0.447,0.122-0.919,0.187-1.406,0.187c-0.343,0-0.678-0.034-1.003-0.095c0.679,2.119,2.649,3.662,4.983,3.705 c-1.825,1.431-4.125,2.284-6.625,2.284c-0.43,0-0.855-0.025-1.273-0.075c2.361,1.513,5.164,2.396,8.177,2.396 c9.812,0,15.176-8.128,15.176-15.177c0-0.231-0.005-0.461-0.015-0.69C26.38,8.945,27.285,8.006,28,6.937z"/></svg>
						</a>
					</li>
					<li class="pinterest">
						<a href="https://in.pinterest.com/" target="_blank">
							<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="30px" height="30px">    <path d="M 12 2 C 6.477 2 2 6.477 2 12 C 2 17.523 6.477 22 12 22 C 17.523 22 22 17.523 22 12 C 22 6.477 17.523 2 12 2 z M 12 4 C 16.418 4 20 7.582 20 12 C 20 16.418 16.418 20 12 20 C 11.264382 20 10.555494 19.892969 9.8789062 19.707031 C 10.09172 19.278284 10.282622 18.826454 10.386719 18.425781 C 10.501719 17.985781 10.972656 16.191406 10.972656 16.191406 C 11.278656 16.775406 12.173 17.271484 13.125 17.271484 C 15.958 17.271484 18 14.665734 18 11.427734 C 18 8.3227344 15.467031 6 12.207031 6 C 8.1520313 6 6 8.7215469 6 11.685547 C 6 13.063547 6.73325 14.779172 7.90625 15.326172 C 8.08425 15.409172 8.1797031 15.373172 8.2207031 15.201172 C 8.2527031 15.070172 8.4114219 14.431766 8.4824219 14.134766 C 8.5054219 14.040766 8.4949687 13.958234 8.4179688 13.865234 C 8.0299688 13.394234 7.71875 12.529656 7.71875 11.722656 C 7.71875 9.6496562 9.2879375 7.6445312 11.960938 7.6445312 C 14.268937 7.6445313 15.884766 9.2177969 15.884766 11.466797 C 15.884766 14.007797 14.601641 15.767578 12.931641 15.767578 C 12.009641 15.767578 11.317063 15.006312 11.539062 14.070312 C 11.804063 12.953313 12.318359 11.747406 12.318359 10.941406 C 12.318359 10.220406 11.932859 9.6191406 11.130859 9.6191406 C 10.187859 9.6191406 9.4296875 10.593391 9.4296875 11.900391 C 9.4296875 12.732391 9.7109375 13.294922 9.7109375 13.294922 C 9.7109375 13.294922 8.780375 17.231844 8.609375 17.964844 C 8.5246263 18.326587 8.4963381 18.755144 8.4941406 19.183594 C 5.8357722 17.883113 4 15.15864 4 12 C 4 7.582 7.582 4 12 4 z"/></svg>
						</a>
					</li>
					<li class="linkedin">
						<a href="https://www.linkedin.com/" target="_blank">
							<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="30px" height="30px">    <path d="M9,25H4V10h5V25z M6.501,8C5.118,8,4,6.879,4,5.499S5.12,3,6.501,3C7.879,3,9,4.121,9,5.499C9,6.879,7.879,8,6.501,8z M27,25h-4.807v-7.3c0-1.741-0.033-3.98-2.499-3.98c-2.503,0-2.888,1.896-2.888,3.854V25H12V9.989h4.614v2.051h0.065 c0.642-1.18,2.211-2.424,4.551-2.424c4.87,0,5.77,3.109,5.77,7.151C27,16.767,27,25,27,25z"/></svg>
						</a>
					</li>
					<li class="whatsapp">
						<a href="https://www.whatsapp.com/" target="_blank">
							<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="30px" height="30px">    <path d="M 12.011719 2 C 6.5057187 2 2.0234844 6.478375 2.0214844 11.984375 C 2.0204844 13.744375 2.4814687 15.462563 3.3554688 16.976562 L 2 22 L 7.2324219 20.763672 C 8.6914219 21.559672 10.333859 21.977516 12.005859 21.978516 L 12.009766 21.978516 C 17.514766 21.978516 21.995047 17.499141 21.998047 11.994141 C 22.000047 9.3251406 20.962172 6.8157344 19.076172 4.9277344 C 17.190172 3.0407344 14.683719 2.001 12.011719 2 z M 12.009766 4 C 14.145766 4.001 16.153109 4.8337969 17.662109 6.3417969 C 19.171109 7.8517969 20.000047 9.8581875 19.998047 11.992188 C 19.996047 16.396187 16.413812 19.978516 12.007812 19.978516 C 10.674812 19.977516 9.3544062 19.642812 8.1914062 19.007812 L 7.5175781 18.640625 L 6.7734375 18.816406 L 4.8046875 19.28125 L 5.2851562 17.496094 L 5.5019531 16.695312 L 5.0878906 15.976562 C 4.3898906 14.768562 4.0204844 13.387375 4.0214844 11.984375 C 4.0234844 7.582375 7.6067656 4 12.009766 4 z M 8.4765625 7.375 C 8.3095625 7.375 8.0395469 7.4375 7.8105469 7.6875 C 7.5815469 7.9365 6.9355469 8.5395781 6.9355469 9.7675781 C 6.9355469 10.995578 7.8300781 12.182609 7.9550781 12.349609 C 8.0790781 12.515609 9.68175 15.115234 12.21875 16.115234 C 14.32675 16.946234 14.754891 16.782234 15.212891 16.740234 C 15.670891 16.699234 16.690438 16.137687 16.898438 15.554688 C 17.106437 14.971687 17.106922 14.470187 17.044922 14.367188 C 16.982922 14.263188 16.816406 14.201172 16.566406 14.076172 C 16.317406 13.951172 15.090328 13.348625 14.861328 13.265625 C 14.632328 13.182625 14.464828 13.140625 14.298828 13.390625 C 14.132828 13.640625 13.655766 14.201187 13.509766 14.367188 C 13.363766 14.534188 13.21875 14.556641 12.96875 14.431641 C 12.71875 14.305641 11.914938 14.041406 10.960938 13.191406 C 10.218937 12.530406 9.7182656 11.714844 9.5722656 11.464844 C 9.4272656 11.215844 9.5585938 11.079078 9.6835938 10.955078 C 9.7955938 10.843078 9.9316406 10.663578 10.056641 10.517578 C 10.180641 10.371578 10.223641 10.267562 10.306641 10.101562 C 10.389641 9.9355625 10.347156 9.7890625 10.285156 9.6640625 C 10.223156 9.5390625 9.737625 8.3065 9.515625 7.8125 C 9.328625 7.3975 9.131125 7.3878594 8.953125 7.3808594 C 8.808125 7.3748594 8.6425625 7.375 8.4765625 7.375 z"/></svg>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}

	public function get_readmore_layout($layout){
		$result = '';
		$rm_fill = "fill".(get_option('blogsqode_read_more_fill_allow'))?:"Unable";
		if(esc_attr($layout) == 1){
			$result = '<a href='.get_the_permalink().' class="blogsqode_read_more_button blogsqode-rmlayout-1">'.esc_html__("Read More", "blogsqode").'<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.40846 3.87524H1.18762C0.946122 3.87524 0.750122 3.67924 0.750122 3.43774C0.750122 3.19624 0.946122 3.00024 1.18762 3.00024H6.40846C6.64996 3.00024 6.84596 3.19624 6.84596 3.43774C6.84596 3.67924 6.64996 3.87524 6.40846 3.87524" fill="#6F39FD"/><mask id="mask0_33539_2" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="0" width="7" height="7"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.97095 0.0835724H11.4759V6.79249H5.97095V0.0835724Z" fill="white"/></mask><g mask="url(#mask0_33539_2)"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.84595 1.31348V5.5619L10.2176 3.43798L6.84595 1.31348ZM6.40845 6.79273C6.33553 6.79273 6.2632 6.77406 6.19728 6.7379C6.05728 6.6609 5.97095 6.51448 5.97095 6.35523V0.52073C5.97095 0.360897 6.05728 0.21448 6.19728 0.13748C6.3367 0.0610635 6.50761 0.0657301 6.6412 0.150313L11.2717 3.06756C11.3994 3.14806 11.4759 3.28748 11.4759 3.43798C11.4759 3.5879 11.3994 3.7279 11.2717 3.80781L6.6412 6.72506C6.57061 6.76998 6.48953 6.79273 6.40845 6.79273V6.79273Z" fill="#6F39FD"/></g></svg></a>';
		} else if( esc_attr($layout) == 2){
			$result = '<a href='.get_the_permalink().' class="blogsqode_read_more_button blogsqode-rmlayout-2">'.esc_html__("Read More", "blogsqode").'</a>';
		} else if( esc_attr($layout) == 3){
			$result = '<a href='.get_the_permalink().' class="blogsqode_read_more_button blogsqode-rmlayout-3 '.esc_attr($rm_fill).' ">'.esc_html__("Read More", "blogsqode").'</a>';
		} else if( esc_attr($layout) == 4){
			$result = '<a href='.get_the_permalink().' class="blogsqode_read_more_button blogsqode-rmlayout-4 '.esc_attr($rm_fill).'">'.esc_html__("Read More", "blogsqode").'</a>';
		} else if( esc_attr($layout) == 5){
			$result = '<a href='.get_the_permalink().' class="blogsqode_read_more_button blogsqode-rmlayout-5 '.esc_attr($rm_fill).'">'.esc_html__("Read More", "blogsqode").'</a>';
		} else if( esc_attr($layout) == 6){
			$result = '<a href='.get_the_permalink().' class="blogsqode_read_more_button blogsqode-rmlayout-6 '.esc_attr($rm_fill).'">'.esc_html__("Read More", "blogsqode").'</a>';
		}       
		return $result;
	}

	public function get_readtime($text, $wpm=200){
		$totalwords = str_word_count(strip_tags($text));
		$minutes = floor($totalwords/$wpm);
		return $minutes;
	}
}