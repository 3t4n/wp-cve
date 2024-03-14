<?php
/**
 * GiveWP Form Grid template
 */

$settings           = $this->get_settings_for_display();

$preset             = $this->get_settings_for_display('preset');
$layout             = $this->get_settings_for_display('layout_type');
$enable_carousel    = filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN);
$enable_masonry     = filter_var($this->get_settings_for_display('enable_masonry'), FILTER_VALIDATE_BOOLEAN);
$query_post_type    = $this->get_settings_for_display('query_post_type');


$this->add_render_attribute( 'main-container', 'id', 'lagivewp_' . $this->get_id() );

$this->add_render_attribute( 'main-container', 'class', array(
	'lakit-posts',
	'lakit-givewp',
	'layout-type-' . $layout,
	'preset-' . $preset,
	'querycpt--' . (!empty($query_post_type) ? $query_post_type : 'default')
) );


$this->add_render_attribute( 'main-container', 'data-item_selector', '.lakit-posts__item' );

$this->add_render_attribute( 'list-container', 'class', 'lakit-posts__list' );

if('grid' == $layout && !$enable_carousel){
	$this->add_render_attribute( 'list-container', 'class', 'col-row' );
}

$this->add_render_attribute( 'list-wrapper', 'class', 'lakit-posts__list_wrapper');

$is_carousel = false;

$masonry_attr = '';

if($enable_masonry){
	$this->add_render_attribute( 'main-container', 'class', 'lakit-masonry-wrapper' );
	$masonry_attr = $this->get_masonry_options('.lakit-posts__item', '.lakit-posts__list');
}
else{
	if($enable_carousel){
		$slider_options = $this->get_advanced_carousel_options('columns');
		if(!empty($slider_options)){
			$is_carousel = true;
			$this->add_render_attribute( 'main-container', 'data-slider_options', json_encode($slider_options) );
			$this->add_render_attribute( 'main-container', 'dir', is_rtl() ? 'rtl' : 'ltr' );
			$this->add_render_attribute( 'list-wrapper', 'class', 'swiper-container');
			$this->add_render_attribute( 'list-container', 'class', 'swiper-wrapper' );
			$this->add_render_attribute( 'main-container', 'class', 'lakit-carousel' );
			$carousel_id = $this->get_settings_for_display('carousel_id');
			if(empty($carousel_id)){
				$carousel_id = 'lakit_carousel_' . $this->get_id();
			}
			$this->add_render_attribute( 'list-wrapper', 'id', $carousel_id );
		}
	}
}

$the_query = $this->the_query();

?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?> <?php echo $this->render_variable($masonry_attr); ?>><?php

	if($the_query->have_posts()){
		if($is_carousel){
			echo '<div class="lakit-carousel-inner">';
		}

		if( $enable_masonry ){
			$this->render_masonry_filters('#lagivewp_'.$this->get_id().' .lakit-posts__list');
		}

		?>
        <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'list-container' ); ?>>
				<?php

				// reset custom var
				$post_count = $the_query->post_count;
				$need_open = false;
				$need_close = false;
				$this->item_counter = 0;
				$this->cflag = false;

				$c_item_classes = ['lakit-posts__item-g lakit-posts__item'];

				if($enable_carousel){
					$c_item_classes[] = 'swiper-slide';
				}
				else{
					$c_item_classes[] = lastudio_kit_helper()->col_new_classes('columns', $this->get_settings_for_display());
				}

                remove_action( 'give_pre_form_output', 'give_form_content', 10);

				while ($the_query->have_posts()){

					$the_query->the_post();

					if(!$enable_masonry && $preset == 'grid-2a'){

						if($this->item_counter == 3){
							$this->item_counter = 0;
						}

						if($this->item_counter == 1){
							$need_open  = true;
							$need_close  = true;
							$this->cflag = true;
							echo '<div class="'.esc_attr( join(' ', $c_item_classes) ).'">';
						}
					}

					$this->_load_template( $this->_get_global_template( 'loop-item' ) );

					if(!$enable_masonry && $preset == 'grid-2a'){
						if($this->item_counter == 2){
							echo '</div>';
							$need_open = false;
							$need_close = false;
							$this->cflag = false;
						}
					}

					$this->item_counter++;
					$this->_processed_index++;
				}

                add_action( 'give_pre_form_output', 'give_form_content', 10, 2 );

				if(!$enable_masonry && $preset == 'grid-2a'){
					if($need_close){
						echo '</div>';
						$this->cflag = false;
					}
				}
				?>
            </div>
        </div>
		<?php
		if($is_carousel){
			echo '</div>';
		}

		if ($enable_carousel && !$enable_masonry ) {
			if (filter_var($this->get_settings_for_display('carousel_dots'), FILTER_VALIDATE_BOOLEAN)) {
				echo '<div class="lakit-carousel__dots lakit-carousel__dots_'.$this->get_id().' swiper-pagination"></div>';
			}
			if (filter_var($this->get_settings_for_display('carousel_arrows'), FILTER_VALIDATE_BOOLEAN)) {
				echo sprintf('<div class="lakit-carousel__prev-arrow-%s lakit-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_prev_arrow', '%s', '', false));
				echo sprintf('<div class="lakit-carousel__next-arrow-%s lakit-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_next_arrow', '%s', '', false));
			}
			if (filter_var($this->get_settings_for_display('carousel_scrollbar'), FILTER_VALIDATE_BOOLEAN)) {
				echo sprintf('<div class="lakit-carousel__scrollbar swiper-scrollbar lakit-carousel__scrollbar_%1$s"></div>', $this->get_id());
			}
		}

		if( $this->get_settings_for_display('paginate') == 'yes' ){

			if( $this->get_settings_for_display('loadmore_text') ) {
				$load_more_text = $this->get_settings_for_display('loadmore_text');
			}
			else{
				$load_more_text = esc_html__('Load More', 'lastudio-kit');
			}

			$nav_classes = array('post-pagination', 'lakit-pagination', 'clearfix', 'lakit-ajax-pagination');

			if( $this->get_settings_for_display('paginate_as_loadmore') == 'yes') {
				$nav_classes[] = 'active-loadmore';
			}

			if( $this->get_settings_for_display('paginate_infinite') == 'yes') {
				$nav_classes[] = 'active-infinite-loading';
			}

			$paginated = ! $the_query->get( 'no_found_rows' );

			$p_total_pages = $paginated ? (int) $the_query->max_num_pages : 1;
			$p_current_page = $paginated ? (int) max( 1, $the_query->get( 'paged', 1 ) ) : 1;

			$paged_key = 'post-page' . esc_attr($this->get_id());

			if( $query_post_type == 'current_query'){
				$paged_key = 'paged';
			}

			$p_base = add_query_arg(null, null, false);
			$p_base = esc_url_raw( add_query_arg( $paged_key, '%#%', $p_base ) );
			$p_format = '?'.$paged_key.'=%#%';

			if( $p_total_pages == $p_current_page ) {
				$nav_classes[] = 'nothingtoshow';
			}

			$pagination_args = array(
				'total'        => $p_total_pages,
				'type'         => 'list',
				'prev_text'    => __( '&laquo;', 'lastudio-kit' ),
				'next_text'    => __( '&raquo;', 'lastudio-kit' ),
				'end_size'     => 3,
				'mid_size'     => 3
			);

			if($query_post_type != 'current_query'){
				$pagination_args['base']    = $p_base;
				$pagination_args['format']  = $p_format;
				$pagination_args['current'] = max( 1, $p_current_page );
			}

			?>
            <nav class="<?php echo join(' ', $nav_classes) ?>" data-parent-container="#lagivewp_<?php echo $this->get_id() ?>" data-container="#lagivewp_<?php echo $this->get_id() ?> .lakit-posts__list" data-item-selector=".lakit-posts__item" data-ajax_request_id="<?php echo $paged_key ?>">
                <div class="lakit-ajax-loading-outer"><span class="lakit-css-loader"></span></div>
                <div class="lakit-post__loadmore_ajax lakit-pagination_ajax_loadmore">
                    <a href="javascript:;"><span><?php echo esc_html($load_more_text); ?></span></a>
                </div>
				<?php
				echo paginate_links( apply_filters( 'lastudio-kit/posts/pagination_args', $pagination_args, 'give_forms' ) );
				?>
            </nav>
			<?php
		}
		?>

		<?php
		$this->item_counter = 0;
		$this->_processed_index = 0;
	}

	else{

		$nothing_found_message = $this->get_settings_for_display('nothing_found_message');
		if(!empty($nothing_found_message)){
			echo sprintf('<div class="nothing-found-message">%1$s</div>', $nothing_found_message);
		}
	}

	?>
</div>
