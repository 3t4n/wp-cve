<?php
/**
 * header meta for schema.org
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_json_structured_data_page(){
	if(!have_posts()) return;

	while ( have_posts() ) : the_post();

		$json = array();
		$option = get_option('yahman_addons') ;

		$json['logo_url'] = !empty($option['json']['logo_image']) ? $option['json']['logo_image'] : YAHMAN_ADDONS_URI . 'assets/images/json_logo.png';

		//if ( is_singular( 'post' )){
		$json['schema_type'] = 'BlogPosting';
		//}else{
		//$json['schema_type'] = 'Article';
		//}

		$json['schema_title'] = get_the_title();

		//$json['post_author'] = $post->post_author;


		if($json['schema_title']){
			$json['schema_title'] = mb_strimwidth($json['schema_title'], 0, 110);
		}else{
			$json['schema_title'] = __( 'No title', 'yahman-add-ons' );
		}
		$json['schema_description'] = mb_strimwidth( wp_strip_all_tags(strip_shortcodes(get_the_content()), true), 0 , 198, '…' , 'UTF-8');
  //strip_tags(get_the_excerpt());

		$json['author_url'] = get_the_author_meta('user_url');

		if($json['author_url'] === ''){
			$json['author_url'] = get_author_posts_url(get_the_author_meta( 'ID' ));
		}

		
		require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
		$json['meta_thum'] = yahman_addons_get_thumbnail('' , 'full');
		?>
		<script type="application/ld+json">{"@context":"https://schema.org","@type":"<?php echo esc_attr($json['schema_type']); ?>","mainEntityOfPage":{"@type":"WebPage","@id":"<?php echo esc_url( get_permalink() ); ?>"},"headline":"<?php echo esc_attr( $json['schema_title'] ); ?>","image":{"@type":"ImageObject","url":"<?php echo esc_url( $json['meta_thum'][0] ); ?>"},"datePublished":"<?php echo get_date_from_gmt(get_post_time('c', true), 'c');?>","dateModified":"<?php echo get_date_from_gmt(get_post_modified_time('c', true), 'c');?>","author":{"@type": "Person","name":"<?php echo esc_attr( get_the_author_meta('nickname') ); ?>","url": "<?php echo esc_url( $json['author_url'] ); ?>"},"publisher":{"@type": "Organization","name":"<?php bloginfo( 'name' ); ?>","logo":{"@type":"ImageObject","url":"<?php echo esc_url( $json['logo_url'] ); ?>"}},
			"description": "<?php echo esc_attr( $json['schema_description'] ); ?>"}</script>
			<?php

		endwhile;

		wp_reset_postdata();
	}
