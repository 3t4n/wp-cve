<style>
	.elex-banner-notice{
	border: 1px solid #2271b1 !important;
	border-left:4px solid #2271b1 !important;
}
.elex-banner-wrap .elex-banner-row {
	font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	gap: 10px;
	padding: 0.7rem 0;
	align-items: center;
}

.elex-banner-wrap .elex-banner-row-content {
	width: calc(100% - 260px);
	min-width: 400px;
	max-width: 1100px;
}

.elex-banner-wrap .elex-banner-row-logo {
	width: 250px;
}

.elex-banner-wrap .elex-banner-row-logo svg {
	width: 100%;
}

.elex-banner-wrap .elex-banner-button-wrap {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

.elex-banner-wrap .elex-banner-button-wrap .button-plain {
	border: none;
	background: transparent;
}

.elex-banner-wrap .elex-banner-button-wrap a {
	padding: .25rem .7rem;
	display: flex !important;
	align-items: center;
	gap: 5px;
}

.elex-banner-wrap button.elex-btn-hover-light:hover {
	background-color: #E7F4FF !important;
}
</style>
<div class="notice elex-banner-notice ">
		<div class="elex-banner-wrap">
			<div class="elex-banner-row">
				<div class="elex-banner-row-content">
					<h2 style="font-size: 18px;margin-top:0;margin-bottom:10px;">Leave Us a Review</h2>
					<p style="font-size:16px ; line-height: 1.5;margin-top:0;margin-bottom:10px;">
						Hi there! You`ve been using <?php echo esc_attr( $this->data['name'] ); ?> plugin for over 7 days now. Hope it helps you run your business smoothly. Please take a moment to rate us 5 star!
					</p>

					<div class="elex-banner-button-wrap">
						<a class="button-plain elex-btn-hover-light " href = "
						<?php 
						echo esc_url(
							wp_nonce_url(
								add_query_arg(
									array(
										'review_component_action' => 'review_never_ask_again',
										'plugin_basename' => $this->data['basename'],
									) 
								) 
							) 
						); 
						?>
						">
							<svg xmlns="http://www.w3.org/2000/svg" width="18.167" height="18.167" viewBox="0 0 18.167 18.167">
								<g id="Icon_feather-x-circle" data-name="Icon feather-x-circle" transform="translate(0.75 0.75)">
									<path id="Path_650" data-name="Path 650" d="M18.333,10A8.333,8.333,0,1,1,10,1.667,8.333,8.333,0,0,1,18.333,10Z" transform="translate(-1.667 -1.667)" fill="none" stroke="#10518d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
									<path id="Path_651" data-name="Path 651" d="M12.5,7.5l-5,5" transform="translate(-1.667 -1.667)" fill="none" stroke="#10518d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
									<path id="Path_652" data-name="Path 652" d="M7.5,7.5l5,5" transform="translate(-1.667 -1.667)" fill="none" stroke="#10518d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
								</g>
							</svg>

							Never ask again
						</a>
						<a class="button elex-btn-hover-light " href = "
						<?php 
						echo esc_url(
							wp_nonce_url(
								add_query_arg(
									array(
										'review_component_action' => 'review_will_do_it_later',
										'plugin_basename' => $this->data['basename'],
									) 
								) 
							) 
						); 
						?>
						">
							<svg xmlns="http://www.w3.org/2000/svg" width="13.417" height="18.394" viewBox="0 0 13.417 18.394">
								<g id="later_icon" data-name="later icon" transform="translate(0.893 1.284)">
									<path id="Path_648" data-name="Path 648" d="M1328.213,1024.895s-10.494,2.358-10.671-6.072c.059-9.9,9.643-7.065,9.643-7.065" transform="translate(-1317.686 -1008.864)" fill="none" stroke="#10518d" stroke-linecap="round" stroke-width="1.5" />
									<path id="Path_649" data-name="Path 649" d="M0,0,4.114,2.057,0,5.571" transform="matrix(0.951, 0.309, -0.309, 0.951, 7.587, 0)" fill="none" stroke="#10518d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
								</g>
							</svg>
							I will do it later
						</a>
						<a class="button button-primary " href="<?php echo esc_url( $this->data['rating_url'] ); ?>" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" width="16.707" height="16.692" viewBox="0 0 16.707 16.692">
								<g id="Icon_feather-link" data-name="Icon feather-link" transform="translate(0.75 0.75)">
									<path id="Path_646" data-name="Path 646" d="M15,11.363a3.8,3.8,0,0,0,5.73.41l2.28-2.28A3.8,3.8,0,1,0,17.637,4.12L16.33,5.42" transform="translate(-8.916 -3.008)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
									<path id="Path_647" data-name="Path 647" d="M12.116,15.016a3.8,3.8,0,0,0-5.73-.41l-2.28,2.28a3.8,3.8,0,1,0,5.373,5.373l1.3-1.3" transform="translate(-2.993 -8.18)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
								</g>
							</svg>
							Sure! I`d love to rate
						</a>
					</div>
				</div>
				<?php
						require __DIR__ . '/logo.php';
				?>
			</div>
		</div>
	</div>
