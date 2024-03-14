<?php
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'sakolawp_before_main_content' );

$running_year = get_option('running_year');

while ( have_posts() ) : the_post();

$category_name = array();
$category_slugs = array();
$category_terms = get_the_terms($post->ID, 'news-category');
if(!empty($category_terms)){
	if(!is_wp_error( $category_terms )){
	$category_slugs = array();
		foreach($category_terms as $term){
			$category_name[] = $term->name;
			$category_slugs[] = $term->slug;
		}
$porto_comas =  join( ", ", $category_name );
$porto_space =  join( " ", $category_slugs );
	}
}
else {
	$porto_comas =  "";
	$porto_space =  "";
}

$tags_name = array();
$tags_slugs = array();
$tags_terms = get_the_terms($post->ID, 'news-tags');
if(!empty($tags_terms)){
	if(!is_wp_error( $tags_terms )){
	$tags_slugs = array();
		foreach($tags_terms as $term){
			$tags_name[] = $term->name;
			$tags_slugs[] = $term->slug;
		}
$tags_comas =  join( ", ", $tags_name );
$tags_space =  join( " ", $tags_slugs );
	}
}
else {
	$tags_comas =  "";
	$tags_space =  "";
}

?>

<div class="single-post column column-75">
	<div class="container">

		<div class="single-wrapper clearfix">
			<div class="single-news">

				<div class="single-content-news">
					<h1 class="single-title">
						<?php the_title(); ?>
					</h1>

					<div class="date-excerpt single skwp-news-meta">
						<span class="thedate"><?php echo get_the_date('d'); ?></span>
						<span class="month"><?php echo get_the_date('M'); ?></span> 
						<span class="year"><?php echo get_the_date('Y'); ?></span>
					</div>

					<?php if(!empty($porto_comas)) { ?>
					<div class="single-category skwp-news-meta">
						<?php echo esc_html($porto_comas); ?>
					</div>
					<?php } ?>
				</div>
				
				<?php if(has_post_thumbnail()) { ?>
				<div class="single-img-thumb">
					<div class="image-news">
						<?php the_post_thumbnail(); ?>
						<div class="sakolawp-overlay"></div>
					</div>
				</div>
				<?php } ?>

				<div class="single-content">
					<?php if(!empty($tags_space)) { ?>
					<div class="the-tags">
						<?php esc_html_e( 'Tags:', 'sakolawp' ); ?>
						<a href="">
							<?php echo esc_html($tags_space); ?>
						</a>
					</div>
					<?php } ?>

					<?php the_content();?>
				</div>

	 		</div>
		</div>
	</div>
</div>

<?php 
  if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 
  if ( comments_open() || '0' != get_comments_number() ) comments_template(); 
?>

<?php 
endwhile; 

do_action( 'sakolawp_after_main_content' );
get_footer();
