<?php


if (!function_exists('ai_post_generator_add_integration_code_header')) {

	function ai_post_generator_add_integration_code_header()
	{

		$ai_post_generator_email = wp_get_current_user()->user_email;

		$ai_post_generator_name = wp_get_current_user()->user_firstname ? wp_get_current_user()->user_firstname : wp_get_current_user()->display_name;

?>


<div class="wrap">
	<div class="d-flex w-100 justify-content-center">
		<img class="autowriter-banner" src="<?php echo AI_POST_GENERATOR_PLUGIN_URL; ?>/images/banner-772x250.png">
	</div>
	<div class="ai-presentation mt-5" id="ai-presentation">

		<h4 class="mt-3 mb-5">Welcome to AI Post Generator | <a href="https://autowriter.tech"
				target="_blank">AutoWriter</a></h4>

		<p class="my-4" style="font-size: larger;">Creating content has never been easier.<br>

			Just type in the title of the post, and AI generates both the table of contents and the blog text, with a
			single click!</p>

		<button class="btn btn-lg btn-primary w-auto mt-5 mb-2 d-inline-block"
			onclick="start('<?php echo $ai_post_generator_email ?>', '<?php echo $ai_post_generator_name; ?>')">Create
			first post</button>

		<span style="font-size: 13px; margin-top: 10px;"><input type="checkbox" id="ai-gdpr" checked >You agree to have your Wordpress email and first
			name saved in AutoWriter Database and to receive promotional emails.</span>

	</div>

</div>

<div class="wrap" id="ai-body">

	<div class="ai-localhost" id="ai-localhost" style="display:none;">


		<h4 class="mt-3">

			<svg class="me-3" height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
				xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#000000">

				<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
				<g id="SVGRepo_iconCarrier">

					<path style="fill:#FFDC35;"
						d="M507.494,426.066L282.864,53.536c-5.677-9.415-15.87-15.172-26.865-15.172 c-10.994,0-21.188,5.756-26.865,15.172L4.506,426.066c-5.842,9.689-6.015,21.774-0.451,31.625 c5.564,9.852,16.001,15.944,27.315,15.944h449.259c11.314,0,21.751-6.093,27.315-15.944 C513.509,447.839,513.336,435.755,507.494,426.066z">
					</path>

					<path style="fill:#FFDC35;"
						d="M255.999,38.365c-10.994,0-21.188,5.756-26.865,15.172L4.506,426.066 c-5.842,9.689-6.015,21.774-0.451,31.625c5.564,9.852,16.001,15.944,27.315,15.944h224.629L255.999,38.365L255.999,38.365z">
					</path>

					<path style="fill:#FF4B00;"
						d="M445.326,432.791H67.108c-3.591,0-6.911-1.909-8.718-5.012c-1.807-3.104-1.827-6.934-0.055-10.056 L247.23,85.028c1.792-3.155,5.139-5.106,8.767-5.107c0.001,0,0.003,0,0.004,0c3.626,0,6.974,1.946,8.767,5.099l189.324,332.694 c1.777,3.123,1.759,6.955-0.047,10.061S448.918,432.791,445.326,432.791z M84.436,412.616h343.543L256.013,110.423L84.436,412.616z">
					</path>

					<path style="fill:#FF4B00;"
						d="M256.332,412.616H84.436l171.576-302.192l-0.01-30.501h-0.005c-3.628,0.001-6.976,1.951-8.767,5.107 L58.336,417.722c-1.773,3.123-1.752,6.953,0.055,10.056c1.807,3.104,5.127,5.012,8.718,5.012h189.224v-20.175H256.332z">
					</path>

					<path style="fill:#533F29;"
						d="M279.364,376.883c0,12.344-10.537,23.182-22.88,23.182c-13.246,0-23.182-10.838-23.182-23.182 c0-12.644,9.935-23.182,23.182-23.182C268.826,353.701,279.364,364.238,279.364,376.883z M273.644,319.681 c0,9.333-10.236,13.246-17.462,13.246c-9.634,0-17.762-3.914-17.762-13.246c0-35.826-4.214-87.308-4.214-123.134 c0-11.741,9.634-18.365,21.977-18.365c11.741,0,21.677,6.623,21.677,18.365C277.858,232.373,273.644,283.855,273.644,319.681z">
					</path>

				</g>

			</svg>

			¡Caution! Localhost detected

		</h4>

		<p class="my-4" style="font-size: larger;">This plugin works with the domain name.<br>

			If you buy posts on localhost, another website deployed on localhost will be able to use them.

			We strongly recommend not to buy posts while running on localhost</p>

	</div>
	<div class="ai-banner" id="ai-banner-review" style="display:none;">

	<button onclick="close_banner('review')" class="ai-close-banner">x</button>

		<h4 class="mt-3">

			<i class="fa fa-gift me-3" style="color:blue;"></i>Leave us a review and get 5 extra posts!

		</h4>

		<p class="my-4 d-none d-sm-block" style="font-size: larger;">
			We give you 5 extra posts if you leave us a review in wordpress!
		</p>
		<div class="d-flex justify-content-end">
			<a class="btn btn-primary m-3"
				href="https://wordpress.org/support/plugin/ai-post-generator/reviews/#new-post" target="_blank">Leave
				review</a>
			<a class="btn btn-success m-3" href="<?php echo get_admin_url(); ?>admin.php?page=autowriter_upgrade_plan">Get prize</a>
		</div>

	</div>
	<div class="ai-banner" id="ai-banner-buy" style="display:none;">
		<button onclick="close_banner('buy')" class="ai-close-banner">x</button>

		<h4 class="mt-3" style="font-style: italic;">

		<i class="fa fa-dollar-sign" style="color:#ff9900"></i><i class="fa fa-dollar-sign me-3" style="color:#ff9900"></i>
		Special offer! 30 post for <span style="font-size: xx-large; color: #ff9900" >7<span style="font-size: small;">.99</span>€ </span>

		</h4>
		<p class="my-4" style="font-size: larger;">
		If it is your first purchase, you will receive a <b>gift of 4 extra posts!</b>
		</p>
		<div class="d-flex justify-content-end">
			<a class="btn btn-primary m-3"
				href="<?php echo get_admin_url(); ?>admin.php?page=autowriter_upgrade_plan">Get free posts
			</a>
		</div>

	</div>

	<div class="ai-progress d-flex flex-row w-100 align-items-center">
		<div class="progress my-3 p-0 w-100" style="border-radius: 10px;">

			<div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-token" role="progressbar"
				aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; border-radius:10px;"></div>

		</div>

		<a class="ps-3 blue-color clickable" href="<?php echo get_admin_url(); ?>admin.php?page=autowriter_upgrade_plan"><span
				class="fa fa-question-circle" style="font-size: 1.2rem;"></span></a>
	</div>

	<div id="progress-n-tokens" class="mb-5 text-center" style="font-size: 1.3rem;"></div>

	<div id="payment-message" class="alert-success p-2  my-5 mx-2 hidden"></div>

</div>

</div>

<?php

	}
}