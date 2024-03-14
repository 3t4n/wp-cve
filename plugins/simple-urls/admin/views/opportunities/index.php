<?php
/**
 * Opportunities
 *
 * @package Opportunities
 */

use LassoLite\Classes\Config;
?>
<?php Config::get_header(); ?>

<!-- OPPORTUNITIES -->
<input id="total-posts" class="d-none" value="0" />
<section class="px-3 py-5">
	<div class="lite-container text-center">

		<!-- TITLE BAR -->
		<div class="align-items-center">

			<!-- TITLE -->
			<div class="mb-4">
				<h1 class="m-0 mr-2 align-middle">Opportunities</h1>
			</div>
		</div>
		<div class="align-items-center">
			
			<p class="large">Convert any link or keyword into an affiliate link. Earn more with your existing creations.</p>
			
			<p class="large">Opportunities are available with Lasso Pro. <a href="https://getlasso.co/upgrade/" target="_blank">Click here to upgrade</a>.</p>
			
			<div class="text-center">
				<a href="https://getlasso.co/features/opportunities/" target="_blank">
					<img src="<?php echo SIMPLE_URLS_URL; ?>/admin/assets/images/opportunities-thumbnail.png" style="max-width: 800px;">
				</a>
			</div>
		</div>
		
	</div>	
</section>
		

<?php Config::get_footer(); ?>
