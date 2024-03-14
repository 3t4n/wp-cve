<!DOCTYPE html>

<html>
<head>
	<title>
		<?php
		global $wpdb, $page;
		wp_title( '|', true, 'right' );
		bloginfo( 'name' );
		$site_description = get_bloginfo( 'description', 'display' );
		?>
	</title>
	<meta name="robots" content="noindex,nofollow">
	<?php
	$printme_options = get_option( 'print-me_options' );
	if ( !empty( $printme_options['head'] ) ) wp_head();
	?>
	<link rel="stylesheet" id="print-css" href='<?php echo plugins_url( '/css/print.css', __FILE__ ); ?>' type="text/css" media="all">
</head>

<body>

<div id="content" class="clearfix">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) : the_post();
			?>
			<div id="article">
				<h1 class="article-title"><?php the_title(); ?></h1>
				<p id="print"><a href="javascript:window.print()">print</a></p>
				<div class="article-content"><?php the_content(); ?></div>
			</div>
		<?php
		endwhile;
	}
	?>
</div>

<div id="footer" class="clearfix">Copyright &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?></div>

</body>
</html>