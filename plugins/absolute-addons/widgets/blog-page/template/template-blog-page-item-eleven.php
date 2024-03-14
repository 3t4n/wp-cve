<?php
/**
 * Template for Blog Item Eleven.
 *
 * @package AbsoluteAddons
 */
?>

<div class="absp-blog-page-head">
	<?php
	$this->post_thumbnail( $settings );
	?>
</div>
<div class="absp-blog-page-body">
	<?php
	$this->post_category( $settings );
	$this->post_title( $settings );
	$this->post_author( $settings );
	$this->post_date_time( $settings );
	$this->read_more_button( $settings, 'absp-btn-orange-yellow absp-btn-xl absp-btn-rounded' );
	?>
</div>
