<?php

class PL_Theme_Bizstrait_About_Section {

	protected static $_instance = null;

	/**
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @return Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {
		$this->aboutSection();
	}

	public function aboutSection() {
		$about_title            = get_theme_mod( 'about_title', __( 'Lorem Ipsum', 'bizstrait' ) );
		$about_sub_title        = get_theme_mod( 'about_sub_title', __( 'Lorem Ipsum', 'bizstrait' ) );
		$is_aboutButtonReadMore = get_theme_mod( 'about_button_display', true );
		$about_button_text      = get_theme_mod( 'about_button', __( 'READ MORE', 'bizstrait' ) );
		$about_button_link      = get_theme_mod( 'about_button_link', __( 'Read More', 'bizstrait' ) );
		$about_button_target    = ( (bool) get_theme_mod( 'about_button_link_target', true ) ) ? 'target=_blank' : 'target=_self';
		$about_image1           = get_theme_mod( 'about_image1', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );

		$about_count   = get_theme_mod( 'about_count', __( '10', 'bizstrait' ) );
		$about_tagline = get_theme_mod( 'about_tagline', __( 'Years Of Experience', 'bizstrait' ) );
		// @todo
		// $about_image2 = get_theme_mod('about_image2', BIZNOL_URI . 'assets/images/about.jpg');
		$signature = get_theme_mod( 'signature', PL_PLUGIN_URL . 'assets/images/signature.png' );

		$editorContent = get_theme_mod( 'bizstrait_about_section_content', bizstrait_about_section_brief_default_content() );
		?>
	<div class="section about pdt0">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
					<div class="ab-img "><img class="img-fluid" src="<?php echo $about_image1; ?>" title="about-us" alt="about-us" >
					
					<?php if ( $about_count != 0 && $about_tagline != '' ) { ?>
						<div class="bs-text">
						<h4><?php echo $about_count; ?></h4>
						<span><?php echo $about_tagline; ?></span>
					</div>
					<?php } ?>
					</div>


					</div>
					<div class="col-md-6 pr-5">
						<div class="section-heading">
							<?php
							/**
							 * Title & Description
							 */
							if ( $about_title || $about_sub_title ) {
								if ( $about_title ) {
									echo "<h3 class='sub-title'>$about_title</h3>";
								}
								if ( $about_sub_title ) {
									echo "<h2 class='ititle'>$about_sub_title</h2> ";
								}
							}
							/**
							 * Content
							 */
							echo '<div class="edirc">';
							echo wp_kses_post( $editorContent );
							echo '</div>';
							/**
							 * Read More button
							 */
							?>
							<div class="signature">
								<!--ToDo-->
								<?php
								// if (!empty($signature)) {
								// echo '<img src="' . esc_url($signature) . '" class="signature-image" alt="about-us-signature" title="about-us-signature">';
								// }

								if ( $is_aboutButtonReadMore && $about_button_text && $about_button_link ) {
									echo "<a class='btn btn-default' $about_button_target href='$about_button_link'><span>$about_button_text</span></a>";
								}
								?>
							</div>

						</div>
					</div>

				</div>
			</div>
							</div>
		<?php
	}

	function aboutSection1() {
		$about_title            = get_theme_mod( 'about_title', __( 'Lorem Ipsum', 'bizstrait' ) );
		$about_sub_title        = get_theme_mod( 'about_sub_title', __( 'Lorem Ipsum', 'bizstrait' ) );
		$is_aboutButtonReadMore = get_theme_mod( 'about_button_display', true );
		$about_button_text      = get_theme_mod( 'about_button', __( 'READ MORE', 'bizstrait' ) );
		$about_button_link      = get_theme_mod( 'about_button_link', __( 'Read More', 'bizstrait' ) );
		$about_button_target    = ( (bool) get_theme_mod( 'about_button_link_target', true ) ) ? 'target=_blank' : 'target=_self';
		$about_image1           = get_theme_mod( 'about_image1', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );
		// @todo
		// $about_image2 = get_theme_mod('about_image2', BIZNOL_URI . 'assets/images/about.jpg');
		$signature = get_theme_mod( 'signature', PL_PLUGIN_URL . 'assets/images/signature.png' );

		$editorContent = get_theme_mod( 'bizstrait_about_section_content', bizstrait_about_section_brief_default_content() );
		?>
		<div class="section about pdt0">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
					<div class="ab-img ">
					<img class="img-fluid" src="<?php echo $about_image1; ?>" title="about-us" alt="about-us" >
					<div class="bs-text">
						<h4>24</h4>
						<span><?php echo apply_filters( 'tag_line_about_sec', __( 'Years Of Experience', 'pluglab' ) ); ?></span>
					</div>
					</div>


					</div>
					<div class="col-md-6 pr-5">
						<div class="section-heading">
							<?php
							/**
							 * Title & Description
							 */
							if ( $about_title || $about_sub_title ) {
								if ( $about_title ) {
									echo "<h3 class='sub-title'>$about_title</h3>";
								}
								if ( $about_sub_title ) {
									echo "<h2 class='ititle'>$about_sub_title</h2> ";
								}
							}
							/**
							 * Content
							 */
							echo '<div class="edirc">';
							echo wp_kses_post( $editorContent );
							echo '</div>';
							/**
							 * Read More button
							 */
							?>
							<div class="signature">
								<!--ToDo-->
								<?php
								// if (!empty($signature)) {
								// echo '<img src="' . esc_url($signature) . '" class="signature-image" alt="about-us-signature" title="about-us-signature">';
								// }

								if ( $is_aboutButtonReadMore && $about_button_text && $about_button_link ) {
									echo "<a class='btn btn-default' $about_button_target href='$about_button_link'><span>$about_button_text</span></a>";
								}
								?>
							</div>

						</div>
					</div>

				</div>
			</div>
							</div>
		<?php
	}

}
