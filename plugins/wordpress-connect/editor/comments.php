<?php

require_once( './common.php' );
require_once( './../src/WordpressConnectConstants.php' );

$number_of_posts = get_default( 'number_of_posts', 6, $filter = 'int' );
$width = get_default( 'width', 600, $filter = 'int' );

$colorscheme = get_default( 'colorscheme', 'light' );
$colorscheme_options = array(
	WPC_THEME_LIGHT,
	WPC_THEME_DARK
);


?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Comments Dialog</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>

		<div id="wp-connect-comments-form" class="wp-connect-form">

			<form method="POST">

				<h1>Wordpress Connect - Add Comments</h1>
				<p>Use this form to add a Comments box to the content of a post or a page.</p>

				<label for="param_href">URL to comment on <abbr title="The URL for this comments box">(?)</abbr></label>
				<input name="href" type="url" placeholder="Leave blank to use the post's url" size="32" />
				<span class="clear-both"></span>

				<label for="param_number_of_posts">Number of posts <abbr title="The number of posts to display by default">(?)</abbr></label>
				<input name="number_of_posts" id="param_number_of_posts" type="number" value="<?php echo $number_of_posts; ?>" size="7" min="1" step="1" />
				<span class="clear-both"></span>

				<label for="param_width">Width <abbr title="The width of the plugin, in pixels">(?)</abbr></label>
				<input name="width" id="param_width" type="number" value="<?php echo $width; ?>" size="7" min="200" max="1200" step="20" class="inputtext" />
				<span class="clear-both"></span>

				<label for="param_colorscheme">Color Scheme <abbr title="The color scheme of the plugin">(?)</abbr></label>
				<?php print_select( 'colorscheme', $colorscheme_options, $colorscheme ); ?>
				<span class="clear-both"></span>

				<input name="submit" type="submit" value="Add Comments" id="wp-connect-submit" />
				<span class="clear-both"></span>
		</form>
<?php

if ( isset( $_REQUEST['submit'] ) && $_REQUEST['submit'] == 'Add Comments' ){

	require_once( './../src/plugins/WordpressConnectComments.php' );

	$html = WordpressConnectComments::getShortCode(
		$_POST['href'],
		$_POST['width'],
		$_POST['number_of_posts'],
		$_POST['colorscheme']
	);

	media_send_to_editor( $html );
}

?>
	</body>
</html>