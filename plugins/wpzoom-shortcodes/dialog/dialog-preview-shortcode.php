<?php
if (
    ! class_exists( 'WPZOOM_Shortcodes_Plugin_Init' )
 || ! current_user_can( 'edit_posts' )
 || ! isset( $_GET['shortcode'] )
) {
    die();
}

$valid_shortcodes = array(
    'box', 'button', 'ilink', 'unordered_list', 'ordered_list',
    'twocol_one', 'twocol_one_last',
    'threecol_one', 'threecol_one_last', 'threecol_two', 'threecol_two_last',
    'fourcol_one', 'fourcol_one_last', 'fourcol_two', 'fourcol_two_last', 'fourcol_three', 'fourcol_three_last',
    'fivecol_one', 'fivecol_one_last', 'fivecol_two', 'fivecol_two_last', 'fivecol_three', 'fivecol_three_last', 'fivecol_four', 'fivecol_four_last',
    'sixcol_one', 'sixcol_one_last', 'sixcol_two', 'sixcol_two_last', 'sixcol_three', 'sixcol_three_last', 'sixcol_four', 'sixcol_four_last', 'sixcol_five', 'sixcol_five_last',
    'tabs', 'tab'
);


// WordPress automatically adds slashes to quotes
// http://stackoverflow.com/questions/3812128/although-magic-quotes-are-turned-off-still-escaped-strings
$shortcode = stripslashes( $_GET['shortcode'] );

$regex = get_shortcode_regex();
$code = trim( urldecode( $shortcode ) );
preg_match( "/$regex/s", $code, $matches );
$shortcode_name = isset( $matches[2] ) ? $matches[2] : '';

if (
      empty( $shortcode_name )
 || ! in_array( $shortcode_name, $valid_shortcodes )
) {
    return false;
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <script type="text/javascript" src="<?php echo WPZOOM_Shortcodes_Plugin_Init::$assets_path. '/js/jquery.min.1.4.3.js'; ?>" ></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo WPZOOM_Shortcodes_Plugin_Init::$assets_path. '/css/shortcodes.css'; ?>" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo WPZOOM_Shortcodes_Plugin_Init::$assets_path. '/css/font-awesome.min.css'; ?>" media="all" />
    <style>
        .post  { margin: -5px 0 0 0; }
        .shortcode-typography { display: block; margin-top: 20px; }
    </style>
</head>
<body>

<?php echo do_shortcode($shortcode); ?>

<script type="text/javascript">
    jQuery( '#wpz-preview h3:first', window.parent.document).removeClass('wpz-loading');
</script>
</body>
</html>
