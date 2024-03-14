<?php 
    $item_classes = array();
    $item_classes[]   = 'qodef-e';
    $item_classes[]   = 'qodef-blog-item qodef-grid-item qodef-item--full';
?>

<article <?php post_class( implode(" ", $item_classes) ); ?>>
    <div class="qodef-e-inner">
        <div class="qodef-e-media-holder">
            <?php 
                // Include post image
                include $this->__get_global_template_elements( 'featured-image' );                
                
                // Include post category
                include $this->__get_global_template_elements( 'date-boxed' );
            ?>
        </div>

		<div class="qodef-e-content">
			<?php if ( 'no' !== $settings['show_date'] || 'no' !== $settings['show_category'] || 'no' !== $settings['show_author'] ) { ?>
				<div class="qodef-e-info qodef-info--top">
					<?php
                    // Include post category
					include $this->__get_global_template_elements( 'category' );

                    // Include post author
					include $this->__get_global_template_elements( 'author' );
					?>
				</div>
			<?php } ?>
			<div class="qodef-e-text">
                <?php
                // Include post title
                include $this->__get_global_template_elements( 'title' );

                // Include post excerpt
                include $this->__get_global_template_elements( 'excerpt' );
                ?>
			</div>
            <?php
                // Include read more
                include $this->__get_global_template_elements( 'read-more' );
            ?>
		</div>
    </div> 
</article>