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
	align-items: center !important;
	gap: 5px;
}

.elex-banner-wrap button.elex-btn-hover-light:hover {
	background-color: #E7F4FF !important;
}
</style>
<div class="notice elex-banner-notice">
		<div class="elex-banner-wrap">
			<div class="elex-banner-row">

				<div class="elex-banner-row-content" id='elex-review-component'>
					<h5 style="font-size: 18px;margin-top:0;margin-bottom:10px;">Trouble setting up the Plugin?</h5>
					<p style="font-size:16px ; line-height: 1.5;margin-top:0;margin-bottom:10px;">
						Need help on setting up <?php echo esc_attr( $this->data['name'] ); ?> plugin? You may go through our detailed documentation. For any further assistance, please don`t hesitate to reach out to our amazing support team.
					</p>

					<div class="elex-banner-button-wrap">
						<a class="button-plain elex-btn-hover-light" id = 'elex_review_never_ask' href = "
						<?php 
						echo esc_url(
							wp_nonce_url(
								add_query_arg(
									array(
										'review_component_action' => 'troubleshoot_never_ask_again',
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
						<a class="button elex-btn-hover-light text-nowrap d-flex align-items-center gap-2" href="<?php echo esc_url( $this->data['documentation_url'] ); ?>" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="22" viewBox="0 0 18 22">
								<g id="Icon_feather-book" data-name="Icon feather-book" transform="translate(-3 -1)">
									<path id="Path_659" data-name="Path 659" d="M4,19.5A2.5,2.5,0,0,1,6.5,17H20" fill="none" stroke="#10518d" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
									<path id="Path_660" data-name="Path 660" d="M6.5,2H20V22H6.5A2.5,2.5,0,0,1,4,19.5V4.5A2.5,2.5,0,0,1,6.5,2Z" fill="none" stroke="#10518d" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
								</g>
							</svg>
							Read Documentation
						</a>
						<a class="button button-primary" href="<?php echo esc_url( $this->data['support_url'] ); ?>" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" width="30" height="20.087" viewBox="0 0 30 20.087" >
								<g id="Icon_feather-users" data-name="Icon feather-users" transform="translate(6 -1.913)">
									<path id="Path_653" data-name="Path 653" d="M17,21V19a4,4,0,0,0-4-4H5a4,4,0,0,0-4,4v2" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
									<path id="Path_654" data-name="Path 654" d="M13,7A4,4,0,1,1,9,3,4,4,0,0,1,13,7Z" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
									<path id="Path_655" data-name="Path 655" d="M23,21V19a4,4,0,0,0-3-3.87" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
									<path id="Path_657" data-name="Path 657" d="M20,21V19a4,4,0,0,1,3-3.87" transform="translate(-25 0)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
									<path id="Path_656" data-name="Path 656" d="M16,3.13a4,4,0,0,1,0,7.75" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
									<path id="Path_658" data-name="Path 658" d="M19.008,3.13a4,4,0,0,0,0,7.75" transform="translate(-17.008 0)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
								</g>
							</svg>
							Contact Support
						</a>
					</div>
				</div>
				<?php
					require __DIR__ . '/logo.php';
				?>
			</div>
		</div>
	</div>
