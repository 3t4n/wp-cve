<?php
use LassoLite\Classes\Enum;
use LassoLite\Classes\Page;
?>

<div class="tab-item d-none text-center" data-step="get-started">
	<div class="progressbar_container">
		<ul class="progressbar">
			<li class="step-get-started complete">Welcome</li>
			<li class="step-display-design complete" data-step="display">Display Designer</li>
			<li class="step-amazon-info complete" data-step="amazon">Amazon Associates</li>
			<li class="step-enable-support complete" data-step="enable-support">Enable Support</li>
			<?php if ( $should_show_import_step ) : ?>
				<li class="step-import" data-step="import">Imports</li>
			<?php endif; ?>
			<li class="step-get-started active">Done</li>
		</ul>
	</div>

	<h1 class="font-weight-bold">You're Ready</h1>
	<p>That's it! You're now ready to add Lasso Displays to your site.</p>
	<div class="pt-4">
		<button class="btn mr-4" data-toggle="modal" data-target="#url-add">
			<i class="far fa-plus-circle large-screen-only"></i> Add New Link
		</button>
		<a href="<?php echo esc_url( Page::get_lite_page_url( Enum::PAGE_DASHBOARD ) ) ?>" type="button" class="btn badge-pill font-weight-bold hover-down mx-1 purple-bg">Go to Dashboard</a>
	</div>
</div>
