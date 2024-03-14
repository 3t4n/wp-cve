<?php


if (!function_exists('ai_post_generator_add_integration_code_body_buy_tokens')) {


	function ai_post_generator_add_integration_code_body_buy_tokens()
	{
		$get_data = autowriter_callAPI('GET', "https://webator.es/gpt3_api/get_prices.php", false);
		$response = json_decode($get_data, true);
		$prices = $response['response'];


?>



<div id="wp-body-content" class="container">
	<?php
			ai_post_generator_add_integration_code_header()
			?>

	<div class="wrap container" id="autowriter-content">
		<ul class="nav nav-pills mb-3 justify-content-around" id="pills-tab" role="tablist">


			<li class="nav-item" role="presentation">


				<button class="nav-link active" style="border: 1px solid #0d6efd;" id="pills-tokens-tab"
					data-bs-toggle="pill" data-bs-target="#pills-tokens" type="button" role="tab"
					aria-controls="pills-tokens" aria-selected="false">Upgrade Plan</button>


			</li>

			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #0d6efd;" id="pills-single-posts-tab"
					data-bs-toggle="pill" data-bs-target="#pills-single-posts" type="button" role="tab"
					aria-controls="pills-single-posts" aria-selected="false">Get Single Posts</button>


			</li>

			<li class="nav-item" role="presentation">

				<button class="nav-link" style="border: 1px solid #0d6efd;" id="pills-free-tokens-tab"
					data-bs-toggle="pill" data-bs-target="#pills-free-tokens" type="button" role="tab"
					aria-controls="pills-free-tokens" aria-selected="false">Free posts</button>


			</li>


		</ul>

		<div class="tab-content" id="pills-tabContent">



			<!--BUY TOKENS-->


			<div class="tab-pane fade show active" id="pills-tokens" role="tabpanel" aria-labelledby="pills-tokens-tab">
				<p class="mt-5 mb-4" style="font-size: large; text-align:center;">You can unsubscribe whenever you want and you will not lose the posts you haven't used!</p>
				<div
					class="d-flex flex-row flex-xl-row overflow-auto payment-scrollbar align-items-center justify-content-between pb-4">

					<!-- FREE PLAN -->
					<div class="card autowriter-price-card mx-2"
						style="min-width: 300px; width: 300px; min-height: 454px;">

						<div class="d-flex flex-column align-items-center justify-content-evenly mt-4 mb-5">

							<h3 class="mb-5">Free Plan</h3>

							<div class="d-flex flex-column my-4 w-100 align-items-center justify-content-between">
								<div class="mb-5">
									<h3>5 posts<span style="font-size:0.8rem;">/mo</span></h3>
								</div>
								<h3 class="my-1" style="font-size: 32px; color: #0d6efd;">0<span
										style="font-size:0.8rem;">.00</span>€<span style="font-size:0.8rem;">/mo</span></h3>
							</div>
						</div>
						<button disabled class="btn btn-lg w-auto btn-success mt-2 my-5 d-inline-block"
							><i class="fa fa-check me-2"></i>Subscribed</button>

					</div>
					<?php 
						$i = 0;
						foreach($prices as $price){
						if($i%2==0){
							$bg = '#fbfcfc!important';
						}else{
							$bg = 'initial';

						}
					?>
					<div class="card autowriter-price-card mx-2"
					    style="min-width: 300px; width: 300px; min-height: 454px; background: <?php echo esc_attr($bg); ?>">

					    <div class="d-flex flex-column align-items-center justify-content-evenly mt-4 mb-5">

					        <h3 class="mb-5"><?php echo esc_html($price['name']); ?> Plan
					        <!--DISCOUNT-->
					        <em class="text-danger" style="font-size: 1.3rem;">
					        <?php 
					        if($price['discount']){
					            echo esc_html($price['discount']) . "%"; 
					        }
					        ?>
					        </em></h3>

					        <div class="d-flex flex-column my-4 w-100 align-items-center justify-content-between">

					            <div class="mb-5">
					                <h3><?php echo esc_html($price['posts']); ?> posts<span style="font-size:0.8rem;">/mo</span></h3>
					            </div>
					            <h3 class="my-1" style="font-size: 32px; color: #0d6efd;"><?php echo esc_html(floor($price['price'])); ?><span
					                style="font-size:0.8rem;"><?php echo esc_html(strstr(strval($price['price'] - floor($price['price'])), '.')); ?></span>€<span style="font-size:0.8rem;">/mo</span></h3>
					        </div>
					    </div>
					    <a href="https://webator.es/gpt3_api/checkout_stripe.php?id=<?php echo esc_attr($price['price_id'])?>" class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block"
					        >Upgrade plan</a>

					</div>

					<?php 
						$i++;
						}
					?>

					<div class="card autowriter-price-card mx-2"
						style="min-width: 300px; width: 300px; min-height: 454px;">


						<div class="d-flex flex-column align-items-center justify-content-between mt-4 mb-5 ">


							<h3 class="mb-5">Enterprise Plan</h3>


							<p class="my-4" style="font-size: 19px;">Need more posts or want to get a special plan? Get
								in touch!</p>




							<a class="btn btn-lg w-auto btn-primary my-5 d-inline-block"
								href="https://autowriter.tech/contact" target="_blank">Get in touch</a>


						</div>


					</div>


				</div>
				<div id="pop-cont"></div>


			</div>

			<!--END BUY TOKENS-->

			<!--SINGLE POSTS-->
			<div class="tab-pane fade" id="pills-single-posts" role="tabpanel" aria-labelledby="pills-single-posts-tab">
					<div class="d-flex flex-column align-items-center justify-content-evenly mt-4 mb-5 price-posts-single">


						
						<div class="d-flex flex-column">


							<p class="my-1 res-text" style="font-size:xx-large;" id="n_posts_text">10 posts</p>


						</div>

						<input class="my-5" type="range" id="n_posts" name="n_posts" min="10" value="10"
							step="5" max="100">

						<div class="res-text mb-5" style="font-size:2rem"><span id="price_text" data-price="3">3€</span> <span id="ai-percent" style="color:red;font-size: 15px;"></span></div>




					<button class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block"
						onclick="show_pay()">Get posts</button>

					</div>


				<div id="ai-payment-pop-cont"></div>
			</div>


			</div>
			<!--END SINGLE POSTS-->

			<!--FREE TOKENS-->
			<div class="tab-pane fade" id="pills-free-tokens" role="tabpanel" aria-labelledby="pills-free-tokens-tab">


				<div class="d-flex flex-column align-items-center mt-5">

					<div id="form-errors"></div>
					<div class="card my-3 align-items-center"
						style="min-width: 100%; min-height: 250px;background: whitesmoke;box-shadow: 0 0 5px #00000069;">

						<div class="d-flex flex-column align-items-center justify-content-evenly my-3">

							<h3 class="mb-5">Make your first purchase</h3>

							<p style="font-size : 1rem;">Make your first purchase and get this 4 posts reward!</p>

						</div>

						<button class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block"
							onclick="ai_post_promotion('first-purchase')">Get 4 posts</button>
						<p style="font-size:1rem; display: none;" id="ai-post-first-purchase-error" class="text-danger">
						</p>
						<p style="font-size:1rem; display: none;" id="ai-post-first-purchase" class="my-3 text-success">
							Obtained! 🦾 </p>

					</div>


					

					<div class="card my-3 align-items-center"
						style="min-width: 100%; min-height: 250px;background: whitesmoke;box-shadow: 0 0 5px #00000069;">

						<div class="d-flex flex-column align-items-center justify-content-evenly my-3">

							<h3 class="mb-5">Pro customer! Complete 5 purchases</h3>

							<p style="font-size : 1rem;">Make 5 purchases to enjoy this AutoWriter gift.</p>

						</div>

						<button class="btn btn-lg w-auto btn-primary mt-2 my-5 d-inline-block"
							onclick="ai_post_promotion('fifth-purchase')">Get 20 posts</button>
						<p style="font-size:1rem; display: none;" id="ai-post-fifth-purchase-error" class="text-danger">
						</p>
						<p style="font-size:1rem; display: none;" id="ai-post-fifth-purchase" class="my-3 text-success">
							Obtained! 🦾 </p>


					</div>


				</div>
				<div id="pop-cont"></div>


			</div>
			<!--END FREE TOKENS-->


		</div>


	</div>


	<div class="wrap mt-5" style="text-align: right;">



		<p>Developed By <a href="https://autowriter.tech" target="_blank">AutoWriter</a></p>



	</div>


</div>

<?php


	}
}