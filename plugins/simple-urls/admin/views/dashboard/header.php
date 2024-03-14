<!-- TITLE -->
<div class="row align-items-center">
	<div class="col-lg text-lg-left text-center mb-4">
		<h1 id="lasso-url-heading" class="font-weight-bold lasso-url-heading"><?php echo $url_details_h1; ?></h1>
		<?php if ( '#' !== $lasso_lite_url->permalink ) : ?>
			<a class="purple underline mt-2 js-permalink" href="<?php echo $lasso_lite_url->public_link ?? ''; ?>" target="_blank">
				<?php echo $lasso_lite_url->public_link ?? ''; ?></a>
		<?php endif; ?>
	</div>
</div>

<!-- SUB NAVIGATION -->
<div class="row align-items-center mb-4">
	<div class="col-lg js-sub-nav">
		<ul class="nav font-weight-bold">
			<li class="nav-item mr-3">
				<a class="nav-link purple hover-underline px-0 active">Details</a>
			</li>
			<li class="nav-item mx-3 lasso-lite-disabled">
				<a class="nav-link purple hover-underline px-0">
					Locations
				</a>
			</li>
			<li class="nav-item mx-3 lasso-lite-disabled">
				<a class="nav-link purple hover-underline px-0">
					Opportunities
				</a>
			</li>
		</ul>
	</div>

	<div class="col-lg-4 text-right">
		<input id="shortcode" type="text" style="opacity: 0;" value='[lasso rel="<?php echo $lasso_lite_url->slug; ?>" id="<?php echo $lasso_lite_url->id; ?>"]'">
		<a id="copy-shortcode" class="purple d-inline-block" data-tooltip="Copy this Display to your clipboard.">
			<i class="far fa-pager"></i> <strong>Copy Shortcode</strong>
		</a>
	</div>
</div>
