<?php

require_once( './common.php' );
require_once( './../src/WordpressConnectConstants.php' );

$send_button = get_default( 'send_button', WPC_OPTION_DISABLED );
$send_button_options = array(
	WPC_OPTION_ENABLED,
	WPC_OPTION_DISABLED
);

$layout = get_default( 'layout', WPC_LAYOUT_STANDARD );
$layout_options = array(
	WPC_LAYOUT_STANDARD,
	WPC_LAYOUT_BUTTON_COUNT,
	WPC_LAYOUT_BOX_COUNT
);


$width = get_default( 'width', 600 );

$show_faces = get_default( 'show_faces', WPC_OPTION_ENABLED );
$show_faces_options = array(
	WPC_OPTION_ENABLED,
	WPC_OPTION_DISABLED
);

$verb = get_default( 'verb', WPC_ACTION_LIKE );
$verb_options = array(
	WPC_ACTION_LIKE,
	WPC_ACTION_RECOMMEND
);

$colorscheme = get_default( 'colorscheme', 'light' );
$colorscheme_options = array(
	WPC_THEME_LIGHT,
	WPC_THEME_DARK
);

$font = get_default( 'font', WPC_FONT_DEFAULT );
$font_options = array(
	WPC_FONT_ARIAL,
	WPC_FONT_LUCIDA_GRANDE,
	WPC_FONT_SEGOE_UI,
	WPC_FONT_TAHOMA,
	WPC_FONT_TREBUCHET_MS,
	WPC_FONT_VERDANA
);

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Like Button Dialog</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div id="wp-connect-like-button-form" class="wp-connect-form">

			<form method="POST">

				<h1>Wordpress Connect - Add Like Button</h1>
				<p>Use this form to add a Like Button to the content of a post or a page.</p>

				<label for="param_href">URL to like <abbr title="The URL for to like">(?)</abbr></label>
				<input name="href" type="url" placeholder="Leave blank to use the post's url" size="32" />
				<span class="clear-both"></span>

				<label for="param_send_button">Send Button <abbr title="Include a Send button">(?)</abbr></label>
				<?php print_select( 'send_button', $send_button_options, $send_button ); ?>
				<span class="clear-both"></span>

				<label for="param_layout">Layout Style <abbr title="determines the size and amount of social context next to the button">(?)</abbr></label>
				<?php print_select( 'layout', $layout_options, $layout ); ?>
				<span class="clear-both"></span>

				<label for="param_width">Width <abbr title="The width of the plugin, in pixels">(?)</abbr></label>
				<input name="width" id="param_width" type="number" value="<?php echo $width; ?>" size="7" min="200" max="1200" step="20" class="inputtext" />
				<span class="clear-both"></span>

				<label for="param_show_faces">Show Faces <abbr title="Show profile pictures below the button">(?)</abbr></label>
				<?php print_select( 'show_faces', $show_faces_options, $show_faces ); ?>
				<span class="clear-both"></span>

				<label for="param_verb">Verb to display <abbr title="The verb to display in the button. Currently only 'like' and 'recommend' are supported.">(?)</abbr></label>
				<?php print_select( 'verb', $verb_options, $verb ); ?>
				<span class="clear-both"></span>

				<label for="param_colorscheme">Color Scheme <abbr title="The color scheme of the plugin">(?)</abbr></label>
				<?php print_select( 'colorscheme', $colorscheme_options, $colorscheme ); ?>
				<span class="clear-both"></span>

				<label for="param_font">Font <abbr title="The color scheme of the plugin">(?)</abbr></label>
				<?php print_select( 'font', $font_options, $font ); ?>
				<span class="clear-both"></span>

				<label for="param_ref">Ref <abbr title="A label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_).">(?)</abbr></label>
				<input name="ref" id="param_ref" type="text" value="<?php echo $ref; ?>" size="8" class="inputtext" />
				<span class="clear-both"></span>

				<input name="submit" type="submit" value="Add Like Button" id="wp-connect-submit" />
				<span class="clear-both"></span>

			</form>
		</div>
<?php

if ( isset( $_REQUEST['submit'] ) && $_REQUEST['submit'] == 'Add Like Button' ){

	require_once( './../src/plugins/WordpressConnectLikeButton.php' );

	$html = WordpressConnectLikeButton::getShortCode(
		$_POST['href'],
		$_POST['send_button'],
		$_POST['layout'],
		$_POST['width'],
		$_POST['show_faces'],
		$_POST['verb'],
		$_POST['colorscheme'],
		$_POST['font'],
		$_POST['ref']
	);

	media_send_to_editor( $html );
}

?>
	</body>
</html>