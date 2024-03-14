<?php
/**
 * Template for Blog Item Twelve.
 *
 * @package AbsoluteAddonsPro
 * @version 1.0.5
 * @since 1.0.5
 *
 */
?>

<div class="absp-blog-page-head">
	<?php $this->post_thumbnail( $settings ); ?>
	<div class="absp-blog-page-overlay-content">
		<?php
		$this->post_date_time( $settings );
		$this->post_title( $settings );
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
	if ( 'yes' === $settings['absp_show_highlight_border'] ) { ?>
		<hr>
	<?php }
	$this->post_title( $settings );
	?>
</div>
