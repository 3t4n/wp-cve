<?php

global $wp_meta_boxes;

wp_enqueue_script( 'accordion' );
	
if ( empty( $screen ) )
	$screen = get_current_screen();
elseif ( is_string( $screen ) )
	$screen = convert_to_screen( $screen );

$page_id = $screen->id;

$hidden = get_hidden_meta_boxes( $screen );
?>
<div id="side-sortables" class="accordion-container">
	<ul class="outer-border as">
<?php
$i = 0;
$first_open = false;

if ( isset( $wp_meta_boxes[ $page_id ][ $context ] ) ) {
       
	foreach ( array( 'high', 'core', 'low','default' ) as $priority ) {
            
		if ( isset( $wp_meta_boxes[ $page_id ][ $context ][ $priority ] ) ) {
                 
			foreach ( $wp_meta_boxes[ $page_id ][ $context ][ $priority ] as $box ) {
				if ( false === $box || ! $box['title'] )
					continue;
				$i++;
				$hidden_class = in_array( $box['id'], $hidden, true ) ? 'hide-if-js' : '';

				$open_class = '';
				if ( ! $first_open && empty( $hidden_class ) ) {
					$first_open = true;
					$open_class = 'open';
				}
				?>
				<li class="control-section accordion-section <?php echo esc_attr($hidden_class); ?> <?php echo esc_attr($open_class); ?> <?php echo esc_attr( $box['id'] ); ?>" id="<?php echo esc_attr( $box['id'] ); ?>">
					<h3 class="accordion-section-title hndle" tabindex="0">
						<?php echo esc_html( $box['title'] ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Press return or enter to expand', 'advance-menu-manager' ); ?></span>
					</h3>
					<div class="accordion-section-content <?php postbox_classes( $box['id'], $page_id ); ?>">
						<div class="inside">
							<?php
							$md_custom_fun='';
							switch ($box['callback']){
								
								case 'wp_nav_menu_item_link_meta_box':
									$md_custom_fun = 'dsamm_nav_menu_item_link_meta_box_own';
									break;
									
								case 'wp_nav_menu_item_post_type_meta_box':
									$md_custom_fun = 'dsamm_nav_menu_item_post_type_meta_box_own';
									break;
									
								case 'wp_nav_menu_item_taxonomy_meta_box':
									$md_custom_fun = 'dsamm_nav_menu_item_taxonomy_meta_box_own';
									break;
							}
							
							if(!empty($md_custom_fun)) {
								call_user_func( $md_custom_fun, $object, $box );
							}else{
								call_user_func( $box['callback'], $object, $box );
							}
							?>
						</div><!-- .inside -->
					</div><!-- .accordion-section-content -->
				</li><!-- .accordion-section -->
				<?php
			}
		}
	}
}
?>
	</ul><!-- .outer-border -->
</div><!-- .accordion-container -->
<?php
return $i;