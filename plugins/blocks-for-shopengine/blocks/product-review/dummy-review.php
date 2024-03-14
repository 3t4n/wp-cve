<?php defined('ABSPATH') || exit; ?>

<div id="reviews" class="woocommerce-Reviews">

   <div id="comments">
      <h2 class="woocommerce-Reviews-title"><?php esc_html_e('2 reviews for', 'shopengine-gutenberg-addon') ?> <span><?php echo esc_html($product->get_name()); ?></span> <?php esc_html_e('(dummy reviews, only for preview)', 'shopengine-gutenberg-addon') ?></h2>
      <ol class="commentlist">
         <li class="review byuser comment-author-admin bypostauthor even thread-even depth-1" id="li-comment-16">
            <div id="comment-16" class="comment_container">
               <img alt="" src="<?php echo esc_url('https://secure.gravatar.com/avatar/7064f941bf63e90bfe35449c62586bc8?s=60&d=mm&r=g'); ?>" class="avatar avatar-60 photo" height="60" width="60" loading="lazy">
               <div class="comment-text">
                  <div class="star-rating" role="img" aria-label="<?php esc_attr_e('Rated 5 out of 5', 'shopengine-gutenberg-addon'); ?>"><span style="width:100%"><?php esc_html_e('Rated', 'shopengine-gutenberg-addon'); ?> <strong class="rating"><?php esc_html_e('5', 'shopengine-gutenberg-addon'); ?></strong> <?php esc_html_e('out of 5', 'shopengine-gutenberg-addon'); ?></span></div>
                  <p class="meta">
                     <strong class="woocommerce-review__author"><?php esc_html_e('Jhon Doe', 'shopengine-gutenberg-addon'); ?></strong>
                     <em class="woocommerce-review__verified verified"><?php esc_html_e('(verified owner)', 'shopengine-gutenberg-addon'); ?></em> <span class="woocommerce-review__dash"><?php echo esc_html('–'); ?></span> 
					 <time class="woocommerce-review__published-date" datetime="2021-05-26T13:07:16+00:00"><?php esc_html_e('May 26, 2021', 'shopengine-gutenberg-addon'); ?></time>
                  </p>
                  <div class="description">
				  	<p><?php echo esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'shopengine-gutenberg-addon'); ?></p>
                  </div>
               </div>
            </div>
         </li>
         <!-- #comment-## -->
         <li class="review byuser comment-author-admin bypostauthor odd alt thread-odd thread-alt depth-1" id="li-comment-17">
            <div id="comment-17" class="comment_container">
               <img alt="<?php esc_attr_e('Review Avatar', 'shopengine-gutenberg-addon') ?>" src="<?php echo esc_url('https://secure.gravatar.com/avatar/7064f941bf63e90bfe35449c62586bc8?s=60&d=mm&r=g'); ?>" class="avatar avatar-60 photo" height="60" width="60" loading="lazy">
               <div class="comment-text">
                  <div class="star-rating" role="img" aria-label="<?php esc_attr_e('Rated 3 out of 5', 'shopengine-gutenberg-addon'); ?>"><span style="width:60%"><?php esc_html_e('Rated', 'shopengine-gutenberg-addon'); ?> <strong class="rating">3</strong> <?php esc_html_e('out of 5', 'shopengine-gutenberg-addon'); ?></span></div>
                  <p class="meta">
                     <strong class="woocommerce-review__author"><?php esc_html_e('David', 'shopengine-gutenberg-addon'); ?> </strong>
                     <em class="woocommerce-review__verified verified"><?php esc_html_e('(verified owner)', 'shopengine-gutenberg-addon'); ?></em> <span class="woocommerce-review__dash">–</span>
					 <time class="woocommerce-review__published-date" datetime="2021-05-26T13:07:31+00:00"><?php esc_html_e('May 26, 2021', 'shopengine-gutenberg-addon'); ?></time>
                  </p>
                  <div class="description">
				  	<p><?php echo esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'shopengine-gutenberg-addon'); ?></p>
                  </div>
               </div>
            </div>
         </li>
      </ol>
   </div>

	<div id="review_form_wrapper">
		<div id="review_form">
			<div id="respond" class="comment-respond">
				<span id="reply-title" class="comment-reply-title">
				Add a review 
				<small><a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display:none;"><?php esc_html_e('Cancel reply', 'shopengine-gutenberg-addon'); ?></a></small>
				</span>
				<form action="#" method="post" id="commentform" class="comment-form" novalidate="">
					<div class="comment-form-rating">
					<label for="rating"><?php esc_html_e('Your rating', 'shopengine-gutenberg-addon'); ?> <span class="required"><?php esc_html_e('*', 'shopengine-gutenberg-addon'); ?></span></label>
					<p class="stars">
						<span>
						<a class="star-1" href="#"><?php esc_html_e('1', 'shopengine-gutenberg-addon'); ?></a>
						<a class="star-2" href="#"><?php esc_html_e('2', 'shopengine-gutenberg-addon'); ?></a>
						<a class="star-3" href="#"><?php esc_html_e('3', 'shopengine-gutenberg-addon'); ?></a>
						<a class="star-4" href="#"><?php esc_html_e('4', 'shopengine-gutenberg-addon'); ?></a>
						<a class="star-5" href="#"><?php esc_html_e('5', 'shopengine-gutenberg-addon'); ?></a>
						</span>
					</p>
					<select name="rating" id="rating" required="" style="display: none;">
						<option value=""><?php esc_html_e('Rate…', 'shopengine-gutenberg-addon'); ?></option>
						<option value="5"><?php esc_html_e('Perfect', 'shopengine-gutenberg-addon'); ?></option>
						<option value="4"><?php esc_html_e('Good', 'shopengine-gutenberg-addon'); ?></option>
						<option value="3"><?php esc_html_e('Average', 'shopengine-gutenberg-addon'); ?></option>
						<option value="2"><?php esc_html_e('Not that bad', 'shopengine-gutenberg-addon'); ?></option>
						<option value="1"><?php esc_html_e('Very poor', 'shopengine-gutenberg-addon'); ?></option>
					</select>
					</div>
					<p class="comment-form-comment">
					<label for="comment"> <?php esc_html_e('Your review', 'shopengine-gutenberg-addon'); ?><span class="required"><?php esc_html_e('*', 'shopengine-gutenberg-addon'); ?></span></label>
					<textarea id="comment" name="comment" cols="45" rows="8" required=""></textarea>
					</p>
					<p class="form-submit">
						<input name="submit" type="submit" id="submit" class="submit" value="<?php esc_attr_e('Submit', 'shopengine-gutenberg-addon'); ?>">
						<input type="hidden" name="comment_post_ID" value="307" id="comment_post_ID">
						<input type="hidden" name="comment_parent" id="comment_parent" value="0">
					</p>
				</form>
			</div>
			<!-- #respond -->
		</div>
	</div>

   <div class="clear"></div>
</div>