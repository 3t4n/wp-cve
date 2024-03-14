<?php
/*
 *  T4B News Ticker v1.2.9 - 23 November, 2023
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
function t4b_show_news_ticker( $atts ){
	extract(shortcode_atts(array(
		'orderby'	=> '', // ticker orderby
		'order'		=> 'DESC', // ticker order
		'id'		=> 1
	), $atts, 't4b-ticker'));

	ob_start();
	$ticker_news = t4bnt_get_option( 'ticker_news', 't4bnt_general', 'on' );
	$ticker_home = t4bnt_get_option( 'ticker_home', 't4bnt_general', 'off' );
	$ticker_ntab = t4bnt_get_option( 'ticker_ntab', 't4bnt_general', 'off' );
	if( $ticker_news == 'on' && (  $ticker_home == 'off' || ( $ticker_home == 'on' && is_home() ) ) ):
		$ticker_type = t4bnt_get_option( 'ticker_type', 't4bnt_general', 'category');
		$ticker_cat = t4bnt_get_option( 'ticker_cat', 't4bnt_general', '');
		$ticker_tag = t4bnt_get_option( 'ticker_tag', 't4bnt_general', '');
		$ticker_title = t4bnt_get_option( 'ticker_title', 't4bnt_general', 'Trending Now' );
		$ticker_postno = t4bnt_get_option( 'ticker_postno', 't4bnt_general', '-1' );
		$ticker_effect = t4bnt_get_option( 'ticker_effect', 't4bnt_general', 'scroll' );
		$timeout = t4bnt_get_option( 'ticker_fadetime', 't4bnt_general', '2000');
		$scroll_control = t4bnt_get_option( 'scroll_control', 't4bnt_general', 'off');
		$scroll_speed = t4bnt_get_option( 'scroll_speed', 't4bnt_general', '0.05');
		$reveal_speed = t4bnt_get_option( 'reveal_speed', 't4bnt_general', '0.10');
		$order_by = t4bnt_get_option( 'ticker_order_by', 't4bnt_general', $orderby );
		$ticker_order = t4bnt_get_option( 'ticker_order', 't4bnt_general', $order );
		$ticker_custom = t4bnt_get_option('ticker_custom', 't4bnt_general', '');
		$target = ''; ?>

		<div class="ticker-news"><?php
			if( $ticker_effect == 'scroll' ) { ?>
			<span><?php echo esc_attr($ticker_title); ?></span><?php
			} else {
				if($ticker_effect == 'ticker') { $ticker_effect = 'reveal'; }
			}
			global $post;
			$orig_post = $post;
			if( $ticker_type != 'custom' ):
				if( $ticker_type == 'tag' ) {
					$fea_tags = $sep = '';
					$tag_lists = explode (',' , $ticker_tag );
					foreach ($tag_lists as $tag) {
						$theTagId = get_term_by( 'name', $tag, 'post_tag' );
						if($fea_tags) $sep = ' , ';
						$fea_tags .=  $sep . $theTagId->slug;
					}
					$args = array(
						'post_type' 		=> 'post',
						'tag'				=> $fea_tags,
						'posts_per_page'	=> $ticker_postno,
						'orderby'			=> $order_by,
						'order'				=> $ticker_order,
					);
				} else {
					$args = array(
						'post_type' 		=> 'post',
						'cat'				=> $ticker_cat,
						'posts_per_page'	=> $ticker_postno,
						'orderby'			=> $order_by,
						'order'				=> $ticker_order,
					);
				}
				$ticker_query = new WP_Query( $args );
				if( $ticker_query->have_posts() ) : $count = 0; ?>
			<div class='tickercontainer'>
				<div class='ticker-mask'>
            		<ul id="ticker" class="js-hidden"><?php
						while( $ticker_query->have_posts() ) :
							$ticker_query->the_post();
							$count++;
							if($ticker_ntab == 'on') :
								$target = ' target="_blank"';
							endif; ?>
						<li><a href="<?php the_permalink()?>" title="<?php the_title(); ?>"<?php echo esc_attr($target); ?>><?php the_title(); ?></a></li><?php
						endwhile;
						wp_reset_postdata();
						wp_reset_query(); ?>
					</ul>
				</div>
			</div><?php
				endif;
			else:
				if( $ticker_custom ) :
					$all_custom_texts = explode( "\n", $ticker_custom ); ?>
			<div class='tickercontainer'>
				<div class='ticker-mask'>
					<ul id="ticker"><?php
						foreach ($all_custom_texts as $custom_text) : ?>
						<li><?php echo wp_kses_post($custom_text) ?></li><?php
						endforeach; ?>
					</ul>
				</div>
			</div><?php
				endif;
			endif;
			$post = $orig_post; echo '
			<script type="text/javascript">';
				if( $ticker_effect == 'scroll' ) : echo '
				jQuery(function() {
					jQuery("ul#ticker").liScroll({
						travelocity: '.esc_attr($scroll_speed);
						if($scroll_control == 'on') { echo ',
						showControls: true';
						} echo '
					});
				});';
				else: echo '
				jQuery(function () {
					jQuery("ul#ticker").ticker({
						speed: '.esc_attr($reveal_speed).',
						titleText: "'.esc_attr($ticker_title).'",
						displayType: "'.esc_attr($ticker_effect).'",
						pauseOnItems: '.esc_attr($timeout).',
					});
				});';
				endif; echo '
			</script>'; ?>
		</div> <!-- .ticker-news --><?php
	endif;
	return ob_get_clean();
}
add_shortcode( 't4b-ticker','t4b_show_news_ticker' );
?>