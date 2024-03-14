<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();

// Header before hook
do_action( 'enteraddons/template/before_header_content' );

echo '<div class="enteraddons-header-wrapper">';
    $templateId = $args;
    echo Enteraddons\Classes\Helper::elementor_content_display( $templateId );
echo '</div>';
// Header after hook
do_action( 'enteraddons/template/after_header_content' );
?>
