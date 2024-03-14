<?php 


// This function for render FAQ title background color
function jw_faq_title_bg_color() {
	if ( get_post_meta( get_the_ID(), 'faq-title-bg-color', true ) ) {
		echo get_post_meta( get_the_ID(), 'faq-title-bg-color', true );
	} else {
		echo jltmaf_options('faq-title-bg-color', 'jltmaf_content' );
	}
}

// This function for render FAQ title text color
function jw_faq_title_text_color() {
	if ( get_post_meta( get_the_ID(), 'faq-title-text-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-title-text-color', true );

	} else {
		echo jltmaf_options('faq-title-text-color', 'jltmaf_content' );
	}
}

// This function for render FAQ content background color
function jw_faq_bg_color() {
	if ( get_post_meta( get_the_ID(), 'faq-bg-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-bg-color', true );

	} else {
		echo jltmaf_options('faq-bg-color', 'jltmaf_content' );
	}
}

// This function for render FAQ Content Text color
function jw_faq_text_color() {
	if ( get_post_meta( get_the_ID(), 'faq-text-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-text-color', true );

	} else {
		echo jltmaf_options('faq-text-color', 'jltmaf_content' );
	}
}

// This function for render FAQ border color
function jw_faq_border_color() {
	if ( get_post_meta( get_the_ID(), 'faq-border-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-border-color', true );

	} else {
		echo jltmaf_options('faq-border-color', 'jltmaf_content' );
	}
}

// Accordion Position
function jltmaf_heading_icon_position() {
	if ( get_post_meta( get_the_ID(), 'faq_icon_position', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq_icon_position', true );

	} else {
		if(jltmaf_options('faq_icon_position', 'jltmaf_settings' ) !="none"){
			echo jltmaf_options('faq_icon_position', 'jltmaf_settings' );	
		}else{
			echo "no-icon";
		}
		
	}
}


 // Accordion Icon
function jltmaf_heading_icon(){

	if ( get_post_meta( get_the_ID(), 'close_icon', true ) ) {
		echo '<i class="' . get_post_meta( get_the_ID(), 'close_icon', true ) . '"></i>';
	} elseif( jltmaf_options('faq_close_icon', 'jltmaf_settings' ) !="" ){
		echo '<i class="' . jltmaf_options('faq_close_icon', 'jltmaf_settings' ) . '"></i>';
	}

}

 // Accordion Heading Tabs
function jltmaf_heading_tags(){
	if( jltmaf_options('faq_heading_tags', 'jltmaf_settings' ) !="" ){
		echo esc_attr( jltmaf_options('faq_heading_tags', 'jltmaf_settings', 'div' ));	
	}
}


/*
 * FAQ Post Query And Short Code
 */

function jltmaf_awesome_faq_shortcode( $atts , $content = null ) {

	$posts_per_page = jltmaf_options('posts_per_page', 'jltmaf_content', '-1' );

	ob_start();

	extract( shortcode_atts(
		array(
			'items' => $posts_per_page,
			'cat' => '',
			'tag' => '',
			'orderby' => 'menu_order title',
			'order'   => 'ASC',
		), $atts )
	);

	// WP_Query arguments
	$args = array (
		'post_type'              => 'faq',
		'faq_cat'          		 => $cat,
		'faq_tags'               => $tag,
		'posts_per_page'         => $items,
		'order'                  => $order,
	);

	// The Query
	$faqQuery = new WP_Query( $args );

	//First Post Active
	$count = 0; 
	$accordion = 'accordion-' . time() . rand();

	$jltmaf_id = $accordion .  $count;

	?>
	<div id="jltmaf-awesome-faq-<?php echo esc_attr($accordion);?>">
		<div class="panel-group" id="<?php echo esc_attr( $jltmaf_id );?>">
		
		<?php // The Loop
		if ( $faqQuery->have_posts() ) {
			while ( $faqQuery->have_posts() ) {
				$faqQuery->the_post(); ?>

			<div class="panel panel-default">
				<div class="jltmaf-item panel-heading" style="background:<?php jw_faq_title_bg_color(); ?>;">
					<div class="panel-title">
						<a data-toggle="collapse" class="collapsed" data-parent="#<?php echo esc_attr( $jltmaf_id );?>" href="#<?php echo $accordion;?>-<?php the_ID(); ?>" style="color:<?php jw_faq_title_text_color(); ?>;">
								<span class="pull-<?php jltmaf_heading_icon_position();?> jltmaf-icon">
									<?php jltmaf_heading_icon();?>
								</span>
							<?php the_title() ?>
						</a>
					</div>
				</div>

				<div id="<?php echo $accordion;?>-<?php the_ID(); ?>" class="panel-collapse collapse" style="background:<?php jw_faq_bg_color(); ?>; color:<?php jw_faq_text_color(); ?>; border-color:<?php jw_faq_border_color(); ?>;">
					<div class="panel-body">					
						<?php the_content(); ?>
					</div>
				</div>
			</div>

		<?php $count ++;
		 } } ?>

		</div>
	</div><!-- /#awesome-colorful-faq -->

<?php
	wp_reset_query();
	wp_reset_postdata();

    $output = ob_get_contents(); // end output buffering
    ob_end_clean(); // grab the buffer contents and empty the buffer
    return $output;
}

add_shortcode( 'faq', 'jltmaf_awesome_faq_shortcode' );





//Option Based Script Loads FAQ
function jltmaf_wp_awesome_faq_accordion_scripts() { ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			<?php $faq_layout = jltmaf_options('faq_collapse_style', 'jltmaf_settings', 'close_all' );
				if( $faq_layout == "first_open"){ ?>
			    	<!-- jQuery(".accordion").accordion({heightStyle: "content", collapsible: true, active: 0}); -->
			    	<!-- $("#accordion a:first").trigger("click"); -->
					$('.panel.panel-default').on('show.bs.collapse', function () {
					    if (active) $('panel.panel-default .in').collapse('hide');
					});			    	
			<?php } if( $faq_layout == "close_all"){ ?>
			    jQuery(".accordion").accordion({heightStyle: "content", collapsible: true, active: false});
			<?php } if( $faq_layout == "open_all"){ ?>
		        jQuery('.ui-accordion-header').removeClass('ui-corner-all').addClass('ui-accordion-header-active ui-state-active ui-corner-top').attr({
		            'aria-selected': 'true',
		            'tabindex': '0'
		        });
			<?php } ?>
		});

	</script>
<?php
}
// add_action( 'wp_footer', 'jltmaf_wp_awesome_faq_accordion_scripts', 9999 );