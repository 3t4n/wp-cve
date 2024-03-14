<?php
/**
 * Template for Blog Item Two.
 *
 * @package AbsoluteAddons
 */
?>

<div class="absp-blog-page-head">
	<?php
	$this->post_thumbnail( $settings ); ?>
	<div class="absp-blog-page-meta-date">
		<?php
		$this->post_date_time( $settings );
		$this->post_category( $settings );
		?>
	</div>
</div>
<div class="absp-blog-page-body">
	<div class="absp-blog-page-meta">
		<?php
		$this->post_comment( $settings );
		$this->post_author( $settings );
		?>
	</div>
	<?php
	$this->post_title( $settings );
	if ( 'yes' === $settings['absp_show_highlight_border'] ) { ?>
		<hr>
	<?php }
	$this->post_content( $settings );
	$this->read_more_button( $settings, 'absp-btn-orange absp-btn-round' );
	?>
</div>
