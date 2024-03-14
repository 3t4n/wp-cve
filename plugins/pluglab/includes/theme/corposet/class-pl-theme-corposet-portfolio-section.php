<?php

class PL_Theme_Corposet_Portfolio_Section
{

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
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct()
	{
		$this->portfolioSection();
	}

	function portfolioSection()
	{
		$portfolio_title     = get_theme_mod('portfolio_title', 'We recent projects');
		$portfolio_sub_title = get_theme_mod('portfolio_sub_title', 'Our Portfolio');
		$portfolio_desc      = get_theme_mod('portfolio_description', 'Laoreet Pellentesque molestie laoreet laoreet.');

		$project1_title      = get_theme_mod('portfolio1_title', __('Project 1 title', 'pluglab'));
		$project1_desc      = get_theme_mod('portfolio1_desc', __('Project 1 description', 'pluglab'));
		$project1_img = 		get_theme_mod('portfolio1_image', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );
		$project2_title      = get_theme_mod('portfolio2_title', __('Project 2 title', 'pluglab'));
		$project2_desc      = get_theme_mod('portfolio2_desc', __('Project 2 description', 'pluglab'));
		$project2_img = 		get_theme_mod('portfolio2_image', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );
		$project3_title      = get_theme_mod('portfolio3_title', __('Project 3 title', 'pluglab'));
		$project3_desc      = get_theme_mod('portfolio3_desc', __('Project 3 description', 'pluglab'));
		$project3_img = 		get_theme_mod('portfolio3_image', PL_PLUGIN_URL . 'assets/images/about-img.jpg' );

?>

		<div class="section bg-grey project-section" id="portfolio-section">
			<div class='<?php echo (get_theme_mod('corposet_portfolio_width', 'container')=='container-full-width') ? 'container-full-width' : 'container' ?>'>
				<?php
				/**
				 * Top title & description
				 */
				echo "<div class='section-heading text-center'>";
				if ($portfolio_title) {
					echo "<h3 class='sub-title'>$portfolio_title</h3>";
				}
				if ($portfolio_sub_title) {
					echo "<h2 class='ititle'>$portfolio_sub_title</h2>";
				}
				if ($portfolio_desc) {
					echo "<p class='ititle'>$portfolio_desc</p>";
				}
				echo '</div>';
				?>
				<div class='px-3 project-content'>
					<div class="portfolio_crowsel row">

						<!-- Project 1 -->

						<div class="col-md-4">
						<div class="portfolio hover_eff cover-bg " style="background-image: url(<?php echo esc_url($project1_img); ?>);">
							<div class="inner">
								<div class="icon">
									<a class="prt_view" href="<?php echo esc_url($project1_img); ?>"><i class="fa fa-search"></i></a>
								</div>
								<?php
								/**
								 * Portfolio title & description
								 */
								if ($project1_title || $project1_desc) {
								?>
									<div class='bottom_text'>
										<?php
										if ($project1_title) {
											echo "<h2>$project1_title</h2>";
										}
										if ($project1_desc) {
											echo "<p>$project1_desc</p>";
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

						<div class="col-md-4">
						<div class="portfolio hover_eff cover-bg " style="background-image: url(<?php echo esc_url($project2_img); ?>);">
							<div class="inner">
								<div class="icon">
									<a class="prt_view" href="<?php echo esc_url($project2_img); ?>"><i class="fa fa-search"></i></a>
								</div>
								<?php
								/**
								 * Portfolio title & description
								 */
								if ($project2_title || $project2_desc) {
								?>
									<div class='bottom_text'>
										<?php
										if ($project2_title) {
											echo "<h2>$project2_title</h2>";
										}
										if ($project2_desc) {
											echo "<p>$project2_desc</p>";
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

						<div class="col-md-4">
						<div class="portfolio hover_eff cover-bg " style="background-image: url(<?php echo esc_url($project3_img); ?>);">
							<div class="inner">
								<div class="icon">
									<a class="prt_view" href="<?php echo esc_url($project3_img); ?>"><i class="fa fa-search"></i></a>
								</div>
								<?php
								/**
								 * Portfolio title & description
								 */
								if ($project3_title || $project3_desc) {
								?>
									<div class='bottom_text'>
										<?php
										if ($project3_title) {
											echo "<h2>$project3_title</h2>";
										}
										if ($project3_desc) {
											echo "<p>$project3_desc</p>";
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
		</div>
	<?php
	}
}
