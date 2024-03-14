<?php
/**
 * Podcast pod entry for episode entry list.
 *
 * This template can be overridden by copying it to yourtheme/podcast-player/header/image.php.
 *
 * HOWEVER, on occasion Podcast Player will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Podcast Player
 * @version 1.0.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="pod-header__image-wrapper">
	<img class="podcast-cover-image" src="<?php echo esc_attr( esc_url( $this->args['imgurl'] ) ); ?>" srcset="<?php echo esc_attr( $this->args['imgset'] ); ?>" sizes="(max-width: 640px) 100vw, 25vw" alt="<?php echo esc_attr( $this->info['title'] ); ?>">
</div>
<span class="pod-header__image-style" style="display: block; width: 100%; padding-top: <?php echo esc_attr( $this->args['imgratio'] ); ?>">
