<?php
defined( 'EOS_CARDS_DIR' ) || exit; //exit if file not inclued by the plugin
if( !current_user_can( 'edit_others_posts' ) ) wp_redirect( get_home_url(),301 );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
	<?php wp_head(); ?>
</head>
<body id="oracle-cards-preview" style="min-height:100vh">
  <h1 style="text-align:center;margin-top:32px"><?php esc_html_e( 'Deck preview','oracle-cards' ); ?></h1>
<?php
echo do_shortcode( '[oracle_cards deck="'.esc_attr( $_GET['deck'] ).'" deck_type="'.esc_attr( $_GET['deck_type'] ).'"]' );
wp_footer();
?>
<script id="oracle-cards-preview-js">setTimeout(function(){document.getElementsByClassName('eos-card')[0].click();},1000);</script>
<?php
die();
exit;
?>
</body>
</html>
