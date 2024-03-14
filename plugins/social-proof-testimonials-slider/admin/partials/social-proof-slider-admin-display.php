<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/admin/partials
 */
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form action="options.php" method="post">
		<?php
			settings_fields( $this->plugin_name );
			do_settings_sections( $this->plugin_name );
			submit_button();
		?>
	</form>

	<?php /* INSTRUCTIONS */ ?>
	<div class="social-proof-slider-instructions">
		<?php
			echo '<h3>' . __( 'Shortcode Instructions', 'social-proof-slider' ) . '</h3>';
			echo '<p><b>' . __( 'Default Display', 'social-proof-slider' ) . '</b><br>' . __( 'To display the Social Proof Slider with a shortcode, use the following format:', 'social-proof-slider' ) . '</p>';
			echo '<pre>[social-proof-slider]</pre>';
			echo '<h3>' . __( 'Filter Posts', 'social-proof-slider' ) . '</h3>';
			echo '<p><b>' . __( 'Show Only Testimonials by ID', 'social-proof-slider' ) . '</b><br>' . __( 'To display ONLY testimonials by ID, simply provide one or more testimonial post IDs:', 'social-proof-slider' ) . '</p>';
			echo '<pre>[social-proof-slider ids="1,2,3"]</pre>';
			echo '<p><b>' . __( 'Exclude Testimonials by ID', 'social-proof-slider' ) . '</b><br>' . __( 'To Exclude testimonials by ID, use the', 'social-proof-slider' ) . ' <span class="code">exclude="true"</span> ' . __( 'attribute, along with one or more testimonial post IDs:', 'social-proof-slider' ) . '</p>';
			echo '<pre>[social-proof-slider exclude="true" ids="99,100"]</pre>';
			echo '<p><b>' . __( 'Show Only Testimonials by Category', 'social-proof-slider' ) . '</b><br>' . __( 'To display ONLY testimonials in a category, simply provide one or more category slugs:', 'social-proof-slider' ) . '</p>';
			echo '<pre>[social-proof-slider category="quotes,movies"]</pre>';
			echo '<p><b>' . __( 'Exclude Testimonials by Category', 'social-proof-slider' ) . '</b><br>' . __( 'To Exclude testimonials by category, use the ', 'social-proof-slider' ) . ' <span class="code">exclude="true"</span> ' . __( 'attribute, along with one or more testimonial category slugs:', 'social-proof-slider' ) . '</p>';
			echo '<pre>[social-proof-slider exclude="true" category="quotes,movies"]</pre>';
		?>
	</div>

</div>
