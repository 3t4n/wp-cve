<?php wp_enqueue_style('sld-css-style-4' ); 
	
	$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'on':'';

	if($sld_enable_rtl =='on'){
		$css = '.qcopd-list-wrapper .style-4 .ca-menu li {float:right;}';
		wp_add_inline_style( 'sld-css-style-4', $css );
	}

?>


<?php
global $wpdb;
// The Loop
if ( $list_query->have_posts() ) 
{
	
	if(get_option('sld_enable_top_part')=='on') :
		
	 do_action('qcsld_attach_embed_btn', $shortcodeAtts);
	
	endif;

	//Directory Wrap or Container
	$sld_enable_rtl = ( get_option('sld_enable_rtl') == 'on' ) ? 'dir="rtl"':'';

	echo '<div class="qcopd-list-wrapper" '.$sld_enable_rtl.'><div id="opd-list-holder" class="qc-grid qcopd-list-holder">';

	$listId = 1;

	while ( $list_query->have_posts() ) 
	{
		$list_query->the_post();

		//$lists = get_post_meta( get_the_ID(), 'qcopd_list_item01' );
		
		$lists = array();
		$results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = ".get_the_ID()." AND meta_key = 'qcopd_list_item01' order by `meta_id` ASC");
		if(!empty($results)){
			foreach($results as $result){
				$unserialize = unserialize($result->meta_value);
				$lists[] = $unserialize;
			}
		}

		$conf = get_post_meta( get_the_ID(), 'qcopd_list_conf', true );
		
		

		if( $item_orderby == 'title' )
		{
			usort($lists, "custom_sort_by_tpl_title");
		}
		if( $item_orderby == 'upvotes' )
		{
			usort($lists, "custom_sort_by_tpl_upvotes");
		}
		if( $item_orderby == 'timestamp' )
		{
			usort($lists, "custom_sort_by_tpl_timestamp");
		}

		
		$customcss = '';
		$customcss .= '#qcopd-list-'.$listId .'-'. get_the_ID().'.style-4 .ca-menu li .ca-main {';
		if($title_font_size!=''){
			$customcss .= 'font-size:'.$title_font_size.' !important;';
		}
		if($title_line_height!=''){
			$customcss .= 'line-height:'.$title_line_height.' !important;';
		}
		$customcss .= '}';
		$customcss .= '#qcopd-list-'. $listId .'-'. get_the_ID().'.style-4 .ca-menu li .ca-sub {';
		if($subtitle_font_size!=''){
			$customcss .= 'font-size:'. $subtitle_font_size.' !important;';
		}
		if($subtitle_line_height!=''){
			$customcss .= 'line-height:'. $subtitle_line_height.'!important;';
		}
		$customcss .= '}';
		wp_add_inline_style( 'sld-css-style-4', $customcss );
		?>
        
		<div class="list-and-add qc-grid-item <?php echo "opd-list-id-" . esc_attr(get_the_ID()); ?>">

		<div id="qcopd-list-<?php echo esc_attr($listId) .'-'. esc_attr(get_the_ID()); ?>" class="qcopd-list-column <?php echo esc_attr($style); ?>">

			<div class="qcopd-single-list-1">
				<h2>
					<?php echo esc_html(get_the_title()); ?>
				</h2>
				<ul class="ca-menu">
					<?php $count = 1; 
					
					?>
					<?php foreach( $lists as $list ) : ?>
					<?php 
						$canContentClass = "subtitle-present";

						if( !isset($list['qcopd_item_subtitle']) || $list['qcopd_item_subtitle'] == "" )
						{
							$canContentClass = "subtitle-absent";
						}
					?>
					<li id="item-<?php echo get_the_ID() ."-". $count; ?>" style="<?php echo ( isset($list['list_item_bg_color']) && !empty($list['list_item_bg_color']) ) ? 'background:'. esc_attr($list['list_item_bg_color']) : ''; ?>">
						<?php 
							$item_url = esc_url($list['qcopd_item_link']);
							$masked_url = esc_url($list['qcopd_item_link']);
						?>
						<!-- List Anchor -->
						<a <?php echo (isset($list['qcopd_item_nofollow']) && $list['qcopd_item_nofollow'] == 1) ? 'rel="nofollow"' : ''; ?> href="<?php echo esc_url($masked_url); ?>" <?php echo (isset($list['qcopd_item_newtab']) && $list['qcopd_item_newtab'] == 1) ? 'target="_blank"' : ''; ?>>

							<!-- Image, If Present -->
							<?php if( ($list_img == "true") && isset($list['qcopd_item_img'])  && $list['qcopd_item_img'] != "" ) : ?>
								<span class="ca-icon list-img-1">
									<?php 
										$img = wp_get_attachment_image_src($list['qcopd_item_img']);
									?>
									<img src="<?php echo esc_url($img[0]); ?>" alt="<?php echo esc_html($list['qcopd_item_title']); ?>">
								</span>
							<?php else : ?>
								<span class="ca-icon list-img-1">
									<img src="<?php echo QCOPD_IMG_URL; ?>/list-image-placeholder.png" alt="">
								</span>
							<?php endif; ?>

							<!-- Link Text -->
							<div class="ca-content">
                                <h3 class="ca-main <?php echo esc_attr($canContentClass); ?>">
								<?php 
									echo esc_html(trim($list['qcopd_item_title'])); 
								?>
                                </h3>
                                <?php if( isset($list['qcopd_item_subtitle']) ) : ?>
	                                <p class="ca-sub">
	                                <?php 
										echo esc_html(trim($list['qcopd_item_subtitle'])); 
									?>
	                                </p>
	                            <?php endif; ?>

                            </div>

						</a>
						<?php if( $upvote == 'on' ) : ?>

							<!-- upvote section -->
							<div class="upvote-section style-4-upvote-section">
								<span data-post-id="<?php echo esc_attr(get_the_ID()); ?>" data-item-title="<?php echo trim($list['qcopd_item_title']); ?>" data-item-link="<?php echo $list['qcopd_item_link']; ?>" class="upvote-btn upvote-on">
									<i class="fa fa-thumbs-up"></i>
								</span>
								<span class="upvote-count">
									<?php
									  if( isset($list['qcopd_upvote_count']) && (int)$list['qcopd_upvote_count'] > 0 ){
									  	echo (int)$list['qcopd_upvote_count'];
									  }
									?>
								</span>
							</div>
							<!-- /upvote section -->

						<?php endif; ?>
						
						<?php if(isset($list['qcopd_featured']) and $list['qcopd_featured']==1):?>
							<!-- featured section -->
							<div class="featured-section">
								<i class="fa fa-bolt"></i>
							</div>
							<!-- /featured section -->
						<?php endif; ?>

					</li>
					<?php $count++; endforeach; ?>
				</ul>
				
			</div>
		</div>

		</div>

		<?php

		$listId++;
	}

	echo '<div class="sld-clearfix"></div>
			</div>
		<div class="sld-clearfix"></div>
	</div>';

}
