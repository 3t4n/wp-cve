<?php
/**
 * Template for Blog Page Item Thirteen.
 *
 * @package AbsoluteAddons
 */

?>
<div class="absp-blog-page-head">
	<?php
	$this->post_thumbnail( $settings );
	$this->post_category( $settings );
	?>
</div>
<div class="absp-blog-page-body">
	<?php
	$this->post_date_time( $settings );
	$this->post_title( $settings );
	?>
	<div class="absp-blog-page-meta">
		<?php
		$this->post_comment( $settings );
		?>
	</div>
</div>
