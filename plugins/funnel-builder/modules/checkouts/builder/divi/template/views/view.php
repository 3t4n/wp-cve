<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head();
	do_action( 'wfacp_header_print_in_head' );
	?>

</head>

<body class="<?php echo $this->get_class_from_body() ?>">
<?php
//get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

wp_footer();

?>
</body>
</html>