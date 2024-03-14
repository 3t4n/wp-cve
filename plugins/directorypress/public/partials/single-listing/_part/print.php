<?php
global $directorypress_object;
$public_handler = new directorypress_directory_handler();
$public_handler->init();
$directorypress_object->public_handlers['directorypress-main'][] = $public_handler;
$directorypress_object->_public_handlers['directorypress-main'][] = $public_handler;

if ($directorypress_object->action == 'pdflisting')
	$pdflisting = true;
else
	$pdflisting = false;

?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php wp_head(); ?>
	
	<style type="text/css">
	.directorypress-print-buttons {
		margin: 10px;
	}
	@media print {
		.directorypress-print-buttons {
			display: none;
		}
	}
	</style>
</head>

<body <?php body_class(); ?> style="background-color: #FFF">
	<div id="page" class="site directorypress-content-wrap">
		<div id="main" class="wrapper">
			<div class="directorypress-container-fluid entry-content">
				<?php if (!$pdflisting): ?>
				<div class="directorypress-print-buttons row clearfix">
					<div class="col-sm-2">
						<input type="button" class="btn btn-primary" onclick="window.print();" value="<?php esc_attr_e('Print listing', 'DIRECTORYPRESS'); ?>" />
					</div>
					<div class="col-sm-2">
						<input type="button" class="btn btn-primary" onclick="window.close();" value="<?php esc_attr_e('Close window', 'DIRECTORYPRESS'); ?>" />
					</div>
				</div>
				<?php endif; ?>

			<?php while ($public_handler->query->have_posts()): ?>
				<?php $public_handler->query->the_post(); ?>
				<?php $listing = $public_handler->listings[get_the_ID()]; ?>
				<?php if (get_the_title()): ?>
				<div class="row clearfix directorypress-listing-title">
					<div class="col-sm-12">
						<h2><?php the_title(); ?> <?php do_action('directorypress_listing_title_html', $listing, false); ?></h2>
					</div>
				</div>
				<?php endif;?>

				<?php if ($listing->logo_image): ?>
				<div class="directorypress-listing-figure-wrap">
					<?php do_action('directorypress_listing_pre_logo_wrap_html', $listing); ?>
					<div class="directorypress-listing-figure">
						<?php $src = wp_get_attachment_image_src($listing->logo_image, 'full'); ?>
						<img src="<?php echo esc_url($src[0]); ?>" />
					</div>
				</div>
				<?php endif; ?>

				<div class="directorypress-listing-text-content-wrap entry-content">
					<?php do_action('directorypress_listing_pre_content_html', $listing); ?>

					<em class="directorypress-listing-date"><?php echo get_the_date(); ?> <?php echo get_the_time(); ?></em>

					<?php $listing->display_content_fields(true); ?>
					
					<?php if ($fields_groups = $listing->display_content_fields_ingroup()): ?>
					<?php foreach ($fields_groups AS $fields_group): ?>
						<?php echo wp_kses_post($fields_group->display_output($listing)); ?>
					<?php endforeach; ?>
					<?php endif; ?>
					
					<?php do_action('directorypress_listing_post_content_html', $listing); ?>
				</div>
				<div class="clear_float"></div>
				<?php if (directorypress_has_map()): ?>
					<?php if ($listing->is_map() && $listing->locations): ?>
					<h2><?php _e('Map', 'DIRECTORYPRESS'); ?></h2>
					<?php $listing->display_map($public_handler->hash, false, true); ?>
					<?php endif; ?>
				<?php endif; ?>
				
				<?php if (count($listing->images) > 1): ?>
				<h2><?php _e('Images', 'DIRECTORYPRESS'); ?> (<?php echo count($listing->images); ?>)</h2>
				<?php foreach ($listing->images AS $attachment_id=>$image): ?>
					<?php $src_thumbnail = wp_get_attachment_image_src($attachment_id,'large'); ?>
					<div style="margin: 10px">
						<img src="<?php echo esc_url($src_thumbnail[0]); ?>"/>
					</div>
				<?php endforeach; ?>
				<?php endif; ?>

				<?php if (get_comments_number()): ?>
				<h2 class="comments-title">
					<?php
						printf(_n('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'DIRECTORYPRESS'),
							number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
					?>
				</h2>
				<ol class="commentlist">
				<?php wp_list_comments(array('reply_text' => '', 'login_text' => '', 'style' => 'ol'), get_comments(array('post_id' => $listing->post->ID))); ?>
				</ol>
				<?php endif; ?>
			<?php endwhile; ?>

				<?php if (!$pdflisting): ?>
				<div class="directorypress-print-buttons row">
					<div class="col-sm-2">
						<input type="button" class="btn btn-primary" onclick="window.print();" value="<?php esc_attr_e('Print listing', 'DIRECTORYPRESS'); ?>" />
					</div>
					<div class="col-sm-2">
						<input type="button" class="btn btn-primary" onclick="window.close();" value="<?php esc_attr_e('Close window', 'DIRECTORYPRESS'); ?>" />
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php wp_footer(); ?>
</body>
</html>