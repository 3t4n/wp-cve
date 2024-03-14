<?php $i = 0; foreach( $comments as $comment ) { $i++;
	
	$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
	
	?>

<div class="pg-woocommerce">
    <div class="pg-woocommerce-product">
            <div class="pg-woocommerce-img">
		<?php
			$post = get_post( $comment->comment_post_ID );
                        setup_postdata( $post );
                        $product = wc_get_product( $comment->comment_post_ID );
                        $product_link   = get_permalink( $comment->comment_post_ID );
                        if ( has_post_thumbnail( $comment->comment_post_ID ) ) 
                        {
                            $image = get_the_post_thumbnail( $comment->comment_post_ID );
                            echo sprintf( __('<a href="%s" class="pg-woocommerce-imgsrc">%s</a>','profilegrid-woocommerce'), esc_url($product_link), $image );
                        } 
                        else 
                        {
                            echo sprintf( __('<img src="%s" alt="%s" class="pg-woocommerce-imgsrc" />','profilegrid-woocommerce'), esc_url(wc_placeholder_img_src()),  esc_attr(__( 'Placeholder', 'profilegrid-woocommerce' )) );
                        }
                ?>
			
		</div>
		
		<span class="pg-woocommerce-title"><a href="<?php echo esc_url($product_link); ?>"><span><?php echo esc_html(get_the_title( $comment->comment_post_ID )); ?></span></a></span>
		<span class="pg-woocommerce-price"><?php echo $product->get_price_html(); ?></span>
                <span class="pg-woocommerce-avg" data-number="5" data-score="<?php echo esc_attr($rating); ?>"></span>
		<span class="pg-woocommerce-product-review"><?php echo '&ldquo;' . esc_html($comment->comment_content) . '&rdquo;'; ?></span>

	</div>

</div>


<?php } wp_reset_postdata(); ?>

<?php if ( !$i ) { ?>

<div class="pg-info"><span><?php echo ( $uid == get_current_user_id() ) ? __('You did not review any products yet.','profilegrid-woocommerce') : sprintf(__('%s did not review any product yet.','profilegrid-woocommerce'),$pmrequests->pm_get_display_name($uid)); ?></span></div>

<?php } ?>