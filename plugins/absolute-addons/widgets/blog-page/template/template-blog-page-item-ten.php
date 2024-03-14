<?php
/**
 * Template for Blog Page Item Ten.
 *
 * @package AbsoluteAddons
 */

?>
<div class="absp-blog-page-head">
	<?php $this->post_thumbnail( $settings ); ?>

	<div class="absp-blog-page-meta-date">
		<?php
		$this->post_date_time( $settings );
		?>
	</div>
</div>
<div class="absp-blog-page-body">
	<div class="absp-blog-page-body-wrapper">
		<?php $this->post_title( $settings ); ?>
		<div class="absp-blog-page-meta">
			<?php
			$this->post_category( $settings );
			$this->post_author( $settings );
			?>
		</div>
	</div>
	<?php
	$this->post_content( $settings );
	$this->read_more_button( $settings, 'absp-btn absp-btn-gray-2 absp-btn-lg absp-btn-rounded' );
	?>
</div>
