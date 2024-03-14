<?php

	use SocialLinkPages\Page;

?>
<?php if ( apply_filters( Social_Link_Pages()->plugin_name_friendly
                          . '_link_page_template_header', true )
) : ?>
	<!doctype html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport"
		      content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<?php wp_head(); ?>
	</head>
	<body>
<?php endif ?>
	<div
		id="<?php echo Social_Link_Pages()->plugin_name_friendly ?>-root"></div>
<?php wp_nonce_field( intval( $page_data->id ),
	Social_Link_Pages()->plugin_name_friendly . '_wpnonce' ) ?>
	<?php wp_footer(); ?>
<?php if ( apply_filters( Social_Link_Pages()->plugin_name_friendly
                          . '_link_page_template_footer', true )
) : ?>
	</body>
	</html>
<?php endif ?>