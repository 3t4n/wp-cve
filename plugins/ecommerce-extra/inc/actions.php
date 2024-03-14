<?php

//testimonial content
add_action('ecommerce_extra_testimonial', 'ecommerce_extra_testimonial_content');

function ecommerce_extra_testimonial_content() {

$default_content = array();
$items  = get_theme_mod( 'ecommerce_extra_testimonial_contents', $default_content );

if ( ! empty( $items ) ) {
	$items = json_decode( $items );				
	$i = 0;
?>
<div class="theme_testimonial_section padding-top-md padding-bottom-lg">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<div class="section-title"><?php echo esc_html(get_theme_mod( 'ecommerce_extra_testimonial_title', esc_html__('Testimonials', 'ecommerce-extra')) ); ?></div>
			</div>
		</div>
		<div class="row">
		<?php
		global $theme_extra_uniqueue_id ;
		$theme_extra_uniqueue_id += 1;		
		$carousal_id = 'product-carousal-'.$theme_extra_uniqueue_id;
		
		//carousal begin
		echo '<div id="'.esc_attr($carousal_id).'" class="product-slider carousel slide" data-ride="carousel" data-interval="4000" >';
		echo '<div class="carousel-inner">';
	
		$active = 'active';
		$item_count = count($items);
		
		foreach ($items as $data) {
			echo '<div class="item '.esc_attr($active).' theme-testimonial-block">';
			 
			echo '<div class="thumbnail"><img  class="rounded-circle" src="'.$data->image_url.'" /></div>';
			
			echo '<div class="rating">';
			for($i = 1; $i <= $data->shortcode ; $i++) {
				echo '<span></span>';
			}
			echo '</div>';			
			
			echo	'<p>'.wp_kses_post($data->text).'</p>';
			echo	'<cite class="testimonial-name">'.esc_html($data->title).'</cite>';			
			echo	'<h4 class="testimonial-subtitle">'.esc_html($data->subtitle).'</h4>';
   
			$active = '';
			echo '</div>';						
		}
		
		
		//indicators, navigation
		if($item_count > 1) {	
	
			echo '<a class="left carousel-control" href="#'.esc_attr($carousal_id).'" data-slide="prev">
			<span class="glyphicon glyphicon-menu-left"></span>
			<span class="sr-only">'.esc_html__('Previous', 'ecommerce-plus').'</span>
			</a>
			<a class="right carousel-control" href="#'.esc_attr($carousal_id).'" data-slide="next">
			<span class="glyphicon glyphicon-menu-right"></span>
			<span class="sr-only">'.esc_html__('Next', 'ecommerce-plus').'</span>
			</a>';	
		
			$active = 'active';		
			echo '<ol class="carousel-indicators">';	
			$s = 0;
			foreach ($items as $data) {
				echo '<li data-target="#'.esc_attr($carousal_id).'" data-slide-to="'.esc_attr($s).'" class="'.esc_attr($active).'"></li>';
				$active = '';
				$s++;
			}	
			echo '</ol>';

		}//indicators, navigation				
			echo '</div>';
		echo '</div>';		
		?>
		</div>
	</div>
</div>
<?php
	}
}






		
		

//team content
add_action('ecommerce_extra_team', 'ecommerce_extra_team_content');

function ecommerce_extra_team_content() {
		$default_content = array();
		$items  = get_theme_mod( 'ecommerce_extra_team_contents', $default_content );
		
	if ( ! empty( $items ) ) {
		$items = json_decode( $items );			
		$i = 0;
				
		$colums = get_theme_mod( 'ecommerce_extra_about_colums', 'col-md-4 col-sm-4 col-lg-4 col-xs-6');		
?>
<div class="theme_team_section  padding-top-md padding-bottom-md">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<div class="section-title"><?php echo esc_html(get_theme_mod( 'ecommerce_extra_team_title', esc_html__('Our Team', 'ecommerce-extra')) ); ?></div>
			</div>
		</div>
	
		<div class="row">
		<?php
				foreach ( $items as $data ) {
				?>
				<div class="<?php echo $colums; ?>">
					<div class="theme-team-block text-center">
						<img src="<?php echo esc_url($data->image_url) ?>" />
						<h3 class="team-title"><?php echo esc_html($data->title); ?></h3>
						<h4  class="team-subtitle"><?php echo esc_html($data->subtitle); ?></h4>
						<p><?php echo esc_html($data->text); ?></p>
					</div>
				</div>
				<?php		
				}					
			
		?>
		</div>
	</div>
</div>
<?php
	}
}


//service section content
add_action('ecommerce_extra_service', 'ecommerce_extra_service_content');

function ecommerce_extra_service_content() {

$default_content = array();
$items  = get_theme_mod( 'ecommerce_extra_service_contents', $default_content );

if ( ! empty( $items ) ) {
	$items = json_decode( $items );
	$i = 0;
	
$style = get_theme_mod( 'ecommerce_extra_service_style');
$colums = get_theme_mod( 'ecommerce_extra_service_colums', 'col-md-4 col-sm-4 col-lg-4 col-xs-6');
?>
<div class="theme_service_section  padding-top-md padding-bottom-md">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<div class="section-title"><?php echo esc_html(get_theme_mod( 'ecommerce_extra_service_title', esc_html__('Services', 'ecommerce-extra')) ); ?></div>
			</div>
		</div>	
		<div class="row">
		<?php
		
		if ($style == 'list') {
				foreach ( $items as $data ) {
				?>
				<div class="<?php echo $colums; ?> ">
					<div class="theme-service-content text-center">
						<?php
						if ($data->choice == 'customizer_repeater_icon') {
							?><i class="fa <?php echo esc_attr($data->icon_value); ?>" style="color:<?php  echo esc_attr($data->color); ?>"></i><?php
						} else {
							?><img src="<?php echo esc_url($data->image_url) ?>" /><?php					
						}
						?>					
						<h3 class="service-title"><?php echo esc_html($data->title); ?></h3>
						<p><?php echo esc_html($data->text); ?></p>
					</div>
				</div>
				<?php		
				}//
		} else {
				foreach ( $items as $data ) {
				?>
				<div class="<?php echo $colums; ?> ">
				
				<div class="theme-service-content card">
					
						<?php
						if ($data->choice == 'customizer_repeater_icon') {
							?><i class="fa <?php echo esc_attr($data->icon_value); ?>" style="color:<?php  echo esc_attr($data->color); ?>"></i><?php
						} else {
							?><img src="<?php echo esc_url($data->image_url) ?>" /><?php					
						}
						?>				
					
					<div>					
						<h3 class="service-title"><?php echo esc_html($data->title); ?></h3>
						<p><?php echo esc_html($data->text); ?></p>
					</div>	
				</div>
				</div>
				<?php		
				}
		}		
		?>
		</div>
	</div>
</div>
<?php
	}
}

//about section code
add_action('ecommerce_extra_about', 'ecommerce_extra_about_content');

function ecommerce_extra_about_content() {

        // Get about section details
        $section_details = array();
        
		
        $content = array();

        $page_id = get_theme_mod('ecommerce_extra_about_page', '');
        $subtitle = array();
        
        $args = array(
            'post_type'         => 'page',
            'page_id'           => $page_id,
            'posts_per_page'    => 1,
            );  
			

            // Run The Loop.
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) : 
                $i = 0;
                while ( $query->have_posts() ) : $query->the_post();
                    $page_post['title']     = get_the_title();
                    $page_post['url']       = get_the_permalink();
                    $page_post['excerpt']   = ecommerce_extra_trim_content( 25 );
                    $page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'large' ) : '';

                    // Push to the main array.
                    array_push( $content, $page_post );
                    $i++;
                endwhile;
            endif;
            wp_reset_postdata();
            
        if ( ! empty( $content ) ) {
            $section_details = $content;
        }


		$content_details = $section_details;


        if ( empty( $content_details ) ) {
            return;
        } ?>

        <div id="about-us" class="relative page-section  padding-top-md padding-bottom-md">
            <div class="page-section-container">
                <?php foreach ( $content_details as $content ) : ?>
                    <article class="<?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-post-thumbnail">
                        <?php if ( ! empty( $content['image'] ) ) : ?>
                            <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                                <a href="<?php echo esc_url( $content['url'] ); ?>" class="post-thumbnail-link"></a>
                            </div><!-- .featured-image -->
                        <?php endif; ?>

                        <div class="entry-container">
                            <header class="entry-header">
                                <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                            </header>

                            <div class="entry-content">
                                 <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                            </div><!-- .entry-content -->

                        </div><!-- .entry-container -->
                    </article>
                <?php endforeach; ?>
            </div><!-- .wrapper -->
        </div><!-- #about-us -->
<?php
}




/*
 * Change sections / Add sections
 */

function theme_extra_edit_home_sections( $sections ) {
	
	$sections['ecommerce_extra_testimonial'] = esc_html__('Testimonials', 'ecommerce-extra');
	$sections['ecommerce_extra_team'] = esc_html__('Team', 'ecommerce-extra');
	$sections['ecommerce_extra_service'] = esc_html__('Services', 'ecommerce-extra');
	$sections['ecommerce_extra_about'] = esc_html__('About', 'ecommerce-extra');	
	
	return $sections;
}
add_filter( 'ecommerce_plus_home_sections', 'theme_extra_edit_home_sections' );


if ( ! function_exists( 'ecommerce_extra_trim_content' ) ) :
	/**
	 * custom excerpt function
	 * @since 1.0.0
	 * @return  no of words to display
	 */
	function ecommerce_extra_trim_content( $length = 40, $post_obj = null ) {
		global $post;
		if ( is_null( $post_obj ) ) {
			$post_obj = $post;
		}

		$length = absint( $length );
		if ( $length < 1 ) {
			$length = 40;
		}

		$source_content = $post_obj->post_content;
		if ( ! empty( $post_obj->post_excerpt ) ) {
			$source_content = $post_obj->post_excerpt;
		}

		$source_content = preg_replace( '`\[[^\]]*\]`', '', $source_content );
		$trimmed_content = wp_trim_words( $source_content, $length, '...' );

	   return apply_filters( 'ecommerce_extra_trim_content', $trimmed_content );
	}
endif;