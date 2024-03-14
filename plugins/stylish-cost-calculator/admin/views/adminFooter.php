<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
add_action(
    'admin_footer',
    function () {
        require_once __DIR__ . '/modalTemplates.php';
    }
);
require dirname( __DIR__, 2 ) . '/admin/views/partials/notificationBox.php';
wp_localize_script( 'scc-backend', 'notificationsNonce', [ 'nonce' => wp_create_nonce( 'notifications-box' ) ] );

if ( get_current_screen()->base !== 'stylish-cost-calculator_page_scc-tabs' ) {
    do_action( 'scc_render_notices' );
}
?>
</div> <!--Closing Main Content Container-->
<div class="row ms-0 align-items-center bg-white mt-5 py-4 justify-content-center w-100" id="scc-footer">
	<div class="row w-100">
		<div class="col-md-3">
			<a href="https://stylishcostcalculator.com/" class="scc-footer logo">
				<img src="<?php echo esc_url( SCC_URL . 'assets/images/scc-logo.png' ); ?>" class="img-responsive1" style="padding-bottom:10px;max-width: 160px" alt="Image">
			</a>
		</div>
		<div class="col-md-3">
			<ul class="list-group">
				<li>
					<a href="https://designful.freshdesk.com/support/solutions/folders/48000658562" target="_blank">
						<i class="material-icons-outlined">book</i>
						<span>User Guides</span>
					</a>
				</li>
				<li>
					<a href="https://stylishcostcalculator.com/support/" target="_blank">
						<i class="material-icons-outlined">support</i>
						<span>Submit A Ticket</span>
					</a>
				</li>
				<li>
					<a href="https://stylishcostcalculator.com/poll/new-features/" target="_blank" rel="noopener noreferrer">
						<i class="material-icons-outlined">chat_bubble_outline</i>
						<span>Request A New Feature</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="col-md-2">
			<ul class="list-group">
				<li>
					<a href="https://www.facebook.com/Stylish-Cost-Calculator-WordPress-Plugin-354068492335430" target="_blank" rel="noopener noreferrer">
						<i class="material-icons-outlined">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['facebook'] ); ?></span>
						</i>
						<span>Facebook</span>
					</a>
				</li>
				<li>
					<a href="https://www.youtube.com/c/StylishCostCalculator" target="_blank" rel="noopener noreferrer">
						<i class="material-icons-outlined">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
						</i>
						<span>YouTube</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="col-md-4">
			<?php if ( isset( $_REQUEST['id_form'] ) ) { ?>
				<ul class="scc-edit-nav-items">
					<li><a class="scc-nav-with-icons"
							href="<?php echo esc_url( admin_url( 'admin.php?page=scc-tabs' ) ); ?>"><i
								class="fas fa-plus"></i>Add New</a></li>
					<li><a class="scc-nav-with-icons"
							href="<?php echo esc_url( admin_url( 'admin.php?page=scc_edit_items' ) ); ?>"><i
								class="far fa-edit"></i>Edit Existing</a></li>
					<li class="dropdown">
						<a class="dropdown-toggle scc-nav-with-icons" data-bs-toggle="dropdown" role="button"
							aria-haspopup="true" aria-expanded="false"><i class="far fa-comment"></i>Feedback <span
								class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a target="_blank" href="https://stylishcostcalculator.com/how-can-we-be-better/">Send
									Feedback</a></li>
							<li><a target="_blank" href="https://stylishcostcalculator.com/poll/new-features/">Suggest
									Feature</a></li>
						</ul>
					</li>

					<li class="dropdown">
						<a class="dropdown-toggle scc-nav-with-icons" data-bs-toggle="dropdown" role="button"
							aria-haspopup="true" aria-expanded="false"><i class="far fa-life-ring"></i>Support <span
								class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a target="_blank"
									href="https://designful.freshdesk.com/support/solutions/48000446985">User Guides</a>
							</li>
							<li><a target="_blank"
									href="https://designful.freshdesk.com/support/solutions/folders/48000657938">Video
									Guides</a></li>
							<li><a target="_blank"
									href="<?php echo esc_url( admin_url( 'admin.php?page=scc-diagnostics' ) ); ?>">Diagnostic</a>
							</li>
							<li><a target="_blank"
									href="https://designful.freshdesk.com/support/solutions/folders/48000670797">Troubleshooting</a>
							</li>
							<li><a target="_blank" href="https://stylishcostcalculator.com/support/">Contact Support</a>
							</li>
							<li><a target="_blank" href="https://members.stylishcostcalculator.com/">Member's Portal</a>
							</li>
						</ul>
					</li>
				</ul>
			<?php } else { ?>
				<p>Do you like this plugin?<br/>We have many more 😍</p>
			<a href="https://stylishpricelist.com/" class="scc-header">
				<img src="<?php echo esc_url( SCC_URL . 'assets/images/promotions/SPL-Logo-2-1.svg' ); ?>" class="img-responsive1" style="padding-bottom:20px;max-width: 160px" alt="Image">
			</a>
				<?php } ?>
		</div>
	</div>
</div>
<style type="text/css">
	.scc-new .clearfix {
		clear: both;
		display: block;
		/* width: 100%; */
	}

	.scc-new .clearfix a img {
		max-width: 130px;
	}

	img.img-responsive-scc {
		max-width: 100%;
		height: auto;
	}

	.foot-img-li .col-md-5 .scc-footer,
	.foot-img-li .col-md-5 ul.foot-li,
	.foot-img-li .col-md-5 ul.foot-li li {
		display: inline-block;
	}

	.foot-img-li .scc-footer {
		width: 100%;
	}

	.foot-img-li .scc-footer {
		width: 100%;
	}

	.foot-img-li {
		margin-top: 100px;
	}

	ul.foot-li {
		width: 100%;
		float: left;
	}

	ul.foot-li li a {
		list-style-type: none;
		text-decoration: none;
		padding: 6px;
		font-size: 12px;
		color: #314af3;
	}

	ul.foot-li li {
		width: 30%;
		float: left;
	}

	ul.foot-li li:last-child:after {
		display: none;
	}

	.design,
	.design-2,
	.design-3 {
		position: relative;
	}

	.foot-img-li .design:after,
	.design-2:after,
	.design-3:after {
		content: "";
		width: 2px;
		height: 65px;
		background-color: #9c9c9c;
		position: absolute;
		right: 3px;
		top: 0;
	}

	p.foot-social i.fa {
		padding-top: -20px;
		width: 35px;
		font-size: 20px;
		color: #314af3;
	}

	.foot-text-img p span img {
		width: 100%;
	}

	.foot-text-img p span {
		display: inline-block;
		width: 100%;
		float: left;
	}

	.foot-url {
		text-align: center;
	}

	.foot-url p.col-me {
		color: #314af3;
	}

	.foot-url p {
		margin: 2px;
	}

	.foot-text-img a.plugin_text {
		font-size: 15px;
	}

	.foot-text-img p span img {
		width: 100px;
	}

	.price_wrapper {
		border-top: 1px solid #dcdcdc;
		margin-top: 50px;
		width: 100%;
		max-width: 98%;
	}

	.url-foot:after {
		content: "";
		width: 1px;
		height: 100px;
		background-color: #000;
		position: absolute;
		top: 0px;
	}

	.foot-text-img p {
		width: 100%;
		float: left;
		margin: 3px 0px;
		padding-left: 15px;
	}

	.foot-img-li .foot-li {
		margin-top: 12px;
	}

	@media screen and (max-width:768px) {

		.foot-img-li .col-md-1,
		.foot-img-li .design,
		.foot-img-li .design-2,
		.foot-img-li .design-3,
		.foot-img-li .col-md-3 {
			width: 100%;
			float: left;
		}

		.foot-img-li .scc-footer {
			width: 100%;
			float: left;
			max-width: 200px;
		}

		.foot-url {
			text-align: left;
			margin: 15px 0px;
		}

		.foot-text-img {
			width: 100%;
			float: left;
			margin-bottom: 16px;
		}

		.foot-text-img p {
			padding-left: 0px;
		}

		.foot-img-li .design:after,
		.design-2:after,
		.design-3:after {
			display: none;
		}
	}

	@media screen and (max-width:366px) {
		ul.foot-li li {
			width: 100%;
			float: left;
		}

		ul.foot-li li:after {
			display: none;
		}
	}

	.scc-separator {
		margin-top: 20px;
	}
</style>
