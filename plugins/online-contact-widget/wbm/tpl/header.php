<?php

/**
 * The template for displaying the header
 *
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */
global $current_user;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php bloginfo('blog_name'); ?></title>
  <?php do_action('wbm_head'); ?>
</head>

<body class="wbm-page<?php echo wp_is_mobile() ? ' wb-is-mobile' : ''; ?>">
  <?php include __DIR__ . '/svg.php'; ?>

  <div class="wbm-header">
    <div class="wbm-hd-in wbm-pw">
      <?php do_action('wbm_header_logo'); ?>
      <?php do_action('wbm_header'); ?>

      <?php if (wp_is_mobile()) : ?>
        <nav class="wbm-nav-top">
          <ul class="wbm-side-nav wbm-nav-m">
            <?php do_action('wbm_get_menu'); ?>
          </ul>

          <a class="wbm-btn-close"><?php echo wbolt_svg_icon('wbm-sico-close'); ?></a>
        </nav>
      <?php endif; ?>

      <div class="wbm-ctrl-bar">
        <div class="wbm-user-cover" title="<?php echo $current_user->display_name; ?>">
          <?php echo get_avatar($current_user->user_email, 60); ?>
        </div>
      </div>
      <div class="wbm-btn-nav"><i></i></div>
    </div>
  </div>