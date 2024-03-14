<?php
/**
 * Build Elementor Element
 *
 * @package Kadence Woocommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Element Product Reviews
 */
class Product_Reviews_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-reviews';
	}

	public function get_title() {
		return __( 'Product Reviews', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Reviews', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products reviews and comment form.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			// This just helps it load in elementor.
			add_filter( 'comments_template', array( 'WC_Template_Loader', 'comments_template_loader' ) );
			echo '<div class="woocommerce-tabs-list">';
        		comments_template();
        	echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product">';
	    	echo '<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<h2 class="woocommerce-Reviews-title">1 review for <span>Product</span></h2>

		
			<ol class="commentlist">
				<li class="comment byuser comment-author-admin bypostauthor even thread-even depth-1" id="li-comment-8">

	<div id="comment-8" class="comment_container">

		<img alt="" src="http://0.gravatar.com/avatar/c2b06ae950033b392998ada50767b50e?s=60&amp;d=mm&amp;r=g" srcset="http://0.gravatar.com/avatar/c2b06ae950033b392998ada50767b50e?s=120&amp;d=mm&amp;r=g 2x" class="avatar avatar-60 photo" height="60" width="60">
		<div class="comment-text">

			<div class="star-rating"><span style="width:80%">Rated <strong class="rating">4</strong> out of 5</span></div>
	<p class="meta">
		<strong class="woocommerce-review__author">A Customer </strong>
				<span class="woocommerce-review__dash">–</span> <time class="woocommerce-review__published-date" datetime="2018-06-07T22:31:28+00:00">June 7, 2018</time>
	</p>

<div class="description"><p>An review of the product!</p>
</div>
		</div>
	</div>
</li><!-- #comment-## -->
			</ol>

			
			</div>

	
		<div id="review_form_wrapper">
			<div id="review_form">
					<div id="respond" class="comment-respond">
		<span id="reply-title" class="comment-reply-title">Add a review <small><a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display:none;">Cancel Reply</a></small></span>			<form action="#" method="post" id="ast-commentform" class="comment-form">
				<div class="comment-form-rating"><label for="rating">Your rating</label><p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p><select name="rating" id="rating" aria-required="true" required="" style="display: none;">
							<option value="">Rate…</option>
							<option value="5">Perfect</option>
							<option value="4">Good</option>
							<option value="3">Average</option>
							<option value="2">Not that bad</option>
							<option value="1">Very poor</option>
						</select></div><p class="comment-form-comment"><label for="comment">Your review&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required=""></textarea></p><p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Submit"> <input type="hidden" name="comment_post_ID" value="89" id="comment_post_ID">
<input type="hidden" name="comment_parent" id="comment_parent" value="0">
</p>
			</form>
			</div><!-- #respond -->
				</div>
		</div>

	
	<div class="clear"></div>
</div>';
		echo '</div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Reviews_Element());
