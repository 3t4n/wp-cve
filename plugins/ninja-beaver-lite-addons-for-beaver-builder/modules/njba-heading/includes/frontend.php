<div class="njba-heading">
    <!-- Main Title -->
	<?php if ( $settings->main_title !== '' ) : ?>
    <div class="njba-heading-main">
        <<?php echo $settings->main_title_tag; ?> class="njba-heading-title">
		<?php if ( ! empty( $settings->main_title_link ) ) : ?>
        <a href="<?php echo $settings->main_title_link; ?>" title="<?php echo $settings->main_title; ?>" target="<?php echo $settings->main_title_link_target; ?>">
			<?php endif; ?>
			<?php echo $settings->main_title; ?>
			<?php if ( ! empty( $settings->main_title_link ) ) : ?>
        </a>
	<?php endif; ?>
    </<?php echo $settings->main_title_tag; ?>>
</div>
<?php endif; ?>
<!-- Separator -->
<?php if ( $settings->separator_select !== 'no' ) : ?>
	<?php
	$separator_img          = '';
	$img_separator  	    = '';
	if ( ! empty( $settings->separator_image_select_src ) ) {
		$separator_img = $settings->separator_image_select_src;
	}
	if ( ! empty( $settings->select_image_separator_src ) ) {
		$img_separator = $settings->select_image_separator_src;
	} ?>
	<?php
	$separator_settings = array(
		'icon_position'              => $settings->icon_position,
		'separator_type'             => $settings->separator_type,
		'separator_icon_text'        => $settings->separator_icon_text,
		'separator_text_select'      => $settings->separator_text_select,
		'separator_image_select'     => $settings->separator_image_select,
		'separator_image_select_src' => $separator_img,
		'select_image_separator' 	 => $settings->select_image_separator,
		'select_image_separator_src' => $img_separator,
	);
	?>
	<?php FLBuilder::render_module_html( 'njba-separator', $separator_settings ); ?>
<?php endif; ?>
<!-- subtitle section -->
<?php if ( $settings->sub_title !== '' ) : ?>
    <div class="njba-heading-sub">
        <h4 class="njba-heading-sub-title"><?php echo $settings->sub_title; ?></h4>
    </div>
<?php endif; ?>
</div>
