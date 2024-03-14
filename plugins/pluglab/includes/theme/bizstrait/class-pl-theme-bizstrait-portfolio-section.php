<?php

class PL_Theme_Bizstrait_Portfolio_Section {


	protected static $_instance = null;

	/**
	 * single itteration var
	 */
	protected $item_title;
	protected $item_subtitle;
	protected $image;
	protected $icon_value;
	protected $button;
	protected $link;

	// protected $newtab;

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
		$this->portfolioSection();
	}

	function portfolioSection() {
		$portfolio_title     = get_theme_mod( 'portfolio_title', 'We recent projects' );
		$portfolio_sub_title = get_theme_mod( 'portfolio_sub_title', 'Our Portfolio' );
		$portfolio_desc      = get_theme_mod( 'portfolio_description', 'Laoreet Pellentesque molestie laoreet laoreet.' );

		$project1_title = get_theme_mod( 'portfolio1_title', __( 'Project 1 title', 'pluglab' ) );
		$project1_desc  = get_theme_mod( 'portfolio1_desc', __( 'Project 1 description', 'pluglab' ) );
		$project1_img   = get_theme_mod( 'portfolio1_image', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );
		$project2_title = get_theme_mod( 'portfolio2_title', __( 'Project 2 title', 'pluglab' ) );
		$project2_desc  = get_theme_mod( 'portfolio2_desc', __( 'Project 2 description', 'pluglab' ) );
		$project2_img   = get_theme_mod( 'portfolio2_image', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );
		$project3_title = get_theme_mod( 'portfolio3_title', __( 'Project 3 title', 'pluglab' ) );
		$project3_desc  = get_theme_mod( 'portfolio3_desc', __( 'Project 3 description', 'pluglab' ) );
		$project3_img   = get_theme_mod( 'portfolio3_image', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );

		?>

		<div class="section portfolio project-section bg-grey" id="portfolio-section">
			<div class='container'>
				<?php
				/**
				 * Top title & description
				 */
				echo "<div class='section-heading'>";
				if ( $portfolio_title ) {
					echo "<h3 class='sub-title'>$portfolio_title</h3>";
				}
				if ( $portfolio_sub_title ) {
					echo "<h2 class='ititle'>$portfolio_sub_title</h2>";
				}
				echo '</div>';
				?>
	<div class="row">
						<!-- Project 1 -->

						<div class="col-sm-6 col-md-6 col-lg-4 pb-sm-4">
							<div class="portfolio-main hover_eff text-center ">
								<div class="inner">
								<div class="top_img">
									<img src="<?php echo esc_url( $project1_img ); ?>" class="img-fluid" alt="">
									<div class="icon">
										<a href="#"><i class="fa fa-search"></i></a>
													<a href="#"><i class="fa fa-link"></i></a>
									</div>
								</div>
									<?php
									/**
									 * Portfolio title & description
									 */
									if ( $project1_title || $project1_desc ) {
										?>
										<div class='bottom_content'>
											<?php
											if ( $project1_title ) {
												echo "<h4>$project1_title</h4>";
											}
											if ( $project1_desc ) {
												echo "<h5>$project1_desc</h5>";
											}
											?>
										</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>

						<!-- Project 2 -->

						<div class="col-sm-6 col-md-6 col-lg-4 pb-sm-4">
							<div class="portfolio-main hover_eff text-center ">
								<div class="inner">
								<div class="top_img">
									<img src="<?php echo esc_url( $project2_img ); ?>" class="img-fluid" alt="">
									<div class="icon">
										<a href="#"><i class="fa fa-search"></i></a>
													<a href="#"><i class="fa fa-link"></i></a>
									</div>
								</div>
									<?php
									/**
									 * Portfolio title & description
									 */
									if ( $project2_title || $project2_desc ) {
										?>
										<div class='bottom_content'>
											<?php
											if ( $project2_title ) {
												echo "<h4>$project2_title</h4>";
											}
											if ( $project2_desc ) {
												echo "<h5>$project2_desc</h5>";
											}
											?>
										</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>

						<!-- Project 3 -->

						<div class="col-sm-6 col-md-6 col-lg-4 pb-sm-4">
							<div class="portfolio-main hover_eff text-center ">
								<div class="inner">
								<div class="top_img">
									<img src="<?php echo esc_url( $project3_img ); ?>" class="img-fluid" alt="">
									<div class="icon">
										<a href="#"><i class="fa fa-search"></i></a>
													<a href="#"><i class="fa fa-link"></i></a>
									</div>
								</div>
									<?php
									/**
									 * Portfolio title & description
									 */
									if ( $project3_title || $project3_desc ) {
										?>
										<div class='bottom_content'>
											<?php
											if ( $project3_title ) {
												echo "<h4>$project3_title</h4>";
											}
											if ( $project3_desc ) {
												echo "<h5>$project3_desc</h5>";
											}
											?>
										</div>
										<?php
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
