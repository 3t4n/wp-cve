<?php
/*
 * @author WP doin
 * @package acf-recent-posts-widget/templates
 * @version 4.3
 * 
 * Don't remove these 3 lines, they contain the variables passed over to the query
 */
global $acf_rpw_instance, $acf_rpw_args, $acf_rpw_title;
extract( $acf_rpw_instance );
$title = $acf_rpw_title;
$args = $acf_rpw_args;
/**
 * @param string before WP generated content as specified in the register_sidebar function where the widget was used
 */
echo $args['before_widget'];
?>
<?php
/**
 * 
 * @param string title 
 * @param string before_title, after_title WP generated content as specified in the register_sidebar function where the widget was used
 */
if ( $title ) {
	echo $args['before_title'] . $title . $args['after_title'];
}

// If the default style is disabled then use the custom css if it's not empty.
if ( !isset( $default_styles ) && !empty( $custom_css ) ) {
	echo '<style>' . $custom_css . '</style>';
}

/*
 * Display the before posts block
 * 
 * @param string $css the custom CSS styles set
 * @param bool $default_styles whether or not to use the default theme styles
 * @param string $before_posts user specified content
 */
?>
<div class="acf-rpw-block <?php echo isset( $css ) ? $css : ''; ?> <?php echo isset( $default_styles ) ? 'acf-rpw-default' : ''; ?>">
	<?php if ( !empty( $before_posts ) ): ?>
		<div class="acf-rpw-before-whole">
			<?php echo htmlspecialchars_decode( $before_posts ); ?>
		</div>
	<?php endif;
	?>
	<ul class="acf-rpw-ul">