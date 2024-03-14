<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Premium landing template
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogether
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly.

?>
<style>
	.section {
		margin-left: -20px;
		margin-right: -20px;
		font-family: "Raleway", san-serif;
	}

	.section h1 {
		text-align: center;
		text-transform: uppercase;
		color: #808a97;
		font-size: 35px;
		font-weight: 700;
		line-height: normal;
		display: inline-block;
		width: 100%;
		margin: 50px 0 0;
	}

	.section ul {
		list-style-type: disc;
		padding-left: 15px;
	}

	.section .section-title img {
		display: table-cell;
		vertical-align: middle;
		width: auto;
		margin-right: 15px;
	}

	.section h2,
	.section h3 {
		display: inline-block;
		vertical-align: middle;
		padding: 0;
		font-size: 24px;
		font-weight: 700;
		color: #808a97;
		text-transform: uppercase;
	}

	.section .section-title h2 {
		display: table-cell;
		vertical-align: middle;
		line-height: 25px;
		border: none;
		background: transparent;
	}

	.section-title {
		display: table;
	}

	.section h3 {
		font-size: 14px;
		line-height: 28px;
		margin-bottom: 0;
		display: block;
	}

	.section p {
		font-size: 13px;
		margin: 25px 0;
	}

	.section ul li {
		margin-bottom: 4px;
	}

	.landing-container {
		max-width: 750px;
		margin-left: auto;
		margin-right: auto;
		padding: 50px 0 30px;
	}

	.landing-container:after {
		display: block;
		clear: both;
		content: '';
	}

	.landing-container .col-1,
	.landing-container .col-2 {
		float: left;
		box-sizing: border-box;
		padding: 0 15px;
	}

	.landing-container .col-1 img {
		width: 100%;
	}

	.landing-container .col-1 {
		width: 55%;
	}

	.landing-container .col-2 {
		width: 45%;
	}

	.premium-cta {
		background-color: #808a97;
		color: #fff;
		border-radius: 6px;
		padding: 20px 15px;
	}

	.premium-cta:after {
		content: '';
		display: block;
		clear: both;
	}

	.premium-cta p {
		margin: 7px 0;
		font-size: 14px;
		font-weight: 500;
		display: inline-block;
		width: 60%;
	}

	.premium-cta a.button {
		border-radius: 6px;
		height: 60px;
		float: right;
		background: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/upgrade.png) #ff643f no-repeat 13px 13px;
		border-color: #ff643f;
		box-shadow: none;
		outline: none;
		color: #fff;
		position: relative;
		padding: 9px 50px 9px 70px;
	}

	.premium-cta a.button:hover,
	.premium-cta a.button:active,
	.premium-cta a.button:focus {
		color: #fff;
		background: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/upgrade.png) #971d00 no-repeat 13px 13px;
		border-color: #971d00;
		box-shadow: none;
		outline: none;
	}

	.premium-cta a.button:focus {
		top: 1px;
	}

	.premium-cta a.button span {
		line-height: 13px;
	}

	.premium-cta a.button .highlight {
		display: block;
		font-size: 20px;
		font-weight: 700;
		line-height: 20px;
	}

	.premium-cta .highlight {
		text-transform: uppercase;
		background: none;
		font-weight: 800;
		color: #fff;
	}

	.section-even {
		background-color: #ffffff;
		background-repeat: no-repeat;
		background-position: 85% 75%;
	}

	.section-odd {
		background-color: #f1f1f1;
		background-repeat: no-repeat;
		background-position: 15% 75%;
	}


	.section.one {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/01-bg.png);
	}

	.section.two {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/02-bg.png);
	}

	.section.three {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/03-bg.png);
	}

	.section.four {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/04-bg.png);
	}

	.section.five {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/05-bg.png);
	}

	.section.six {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/06-bg.png);
	}

	.section.seven {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/07-bg.png);
	}

	.section.eight {
		background-image: url(<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/08-bg.png);
	}

	@media (max-width: 768px) {
		.section {
			margin: 0
		}

		.premium-cta p {
			width: 100%;
		}

		.premium-cta {
			text-align: center;
		}

		.premium-cta a.button {
			float: none;
		}
	}

	@media (max-width: 480px) {
		.wrap {
			margin-right: 0;
		}

		.section {
			margin: 0;
		}

		.landing-container .col-1,
		.landing-container .col-2 {
			width: 100%;
			padding: 0 15px;
		}

		.section-odd .col-1 {
			float: left;
			margin-right: -100%;
		}

		.section-odd .col-2 {
			float: right;
			margin-top: 65%;
		}
	}

	@media (max-width: 320px) {
		.premium-cta a.button {
			padding: 9px 20px 9px 70px;
		}

		.section .section-title img {
			display: none;
		}
	}
</style>
<div class="landing">
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Frequently Bought Together%2$s to benefit from all features!', 'yith-woocommerce-frequently-bought-together' ), '<span class="highlight">', '</span>' ); ?>
				</p>
				<a href="<?php echo esc_url( $this->get_premium_landing_uri() ); ?>" target="_blank"
					class="premium-cta-button button btn">
					<span
						class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-frequently-bought-together' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-frequently-bought-together' ); ?></span>
				</a>
			</div>
		</div>
	</div>
	<div class="one section section-even clear">
		<h1><?php esc_html_e( 'Premium Features', 'yith-woocommerce-frequently-bought-together' ); ?></h1>
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/01.png"
					alt="Number of products associated"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/01-icon.png" alt="icon 01"/>
					<h2><?php esc_html_e( 'Number of products associated', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'You might decide to suggest your customers to buy products in combination, but you do not want to weigh your shop page down. Do not be afraid, you can do that with %1$sYITH WooCommerce Frequently Bought Together%2$s.%3$sAssociate as many products as you want to a product of your shop, but then decide how many of them showing. They will be selected randomly and suggested to your customers.', 'yith-woocommerce-frequently-bought-together' ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="two section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/02-icon.png" alt="icon 02"/>
					<h2><?php esc_html_e( 'Custom labels', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'you can customise more and more labels so that you are able to enter the text that you want and that is %1$sinteresting%2$s and %1$scaptivating%2$s for your customers.%3$sFixity is never synonym of a good product and for this reason the plugin aims at being more and more flexible and suitable for your needs.', 'yith-woocommerce-frequently-bought-together' ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/02.png" alt="4 layouts"/>
			</div>
		</div>
	</div>
	<div class="three section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/03.png" alt="Style"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/03-icon.png" alt="icon 03"/>
					<h2><?php esc_html_e( 'Position', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'Users sight is always very quick and it is often caught by a specific detail or by a combination of details that take their sight to a specific spot in the page. Choose %1$sstrategic spots%2$s for showing your combined products in the page and you\'ll find easily the best solution for you.', 'yith-woocommerce-frequently-bought-together' ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="four section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/04-icon.png" alt="icon 04"/>
					<h2><?php esc_html_e( 'Image size', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'All items added by the plugin into product detail page will be shown with their image too.%3$sDo not be afraid of the quality, just care about choosing its final %1$ssize%2$s and crop it if you think it\'s better to do so.', 'yith-woocommerce-frequently-bought-together' ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/04.png" alt="Image size"/>
			</div>
		</div>
	</div>
	<div class="five section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/05.png" alt="Social network"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/05-icon.png" alt="icon 05"/>
					<h2><?php esc_html_e( 'List of associated products', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'Keep track of the products you have set up with the plugin, do not get crazy to find them in the list of all products created.%3$sThe premium version of the plugin adds a %1$stable%2$s where all configured products have been added and for each of them a comprehensive list with associated products.', 'yith-woocommerce-frequently-bought-together' ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="six section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/06-icon.png" alt="icon 06"/>
					<h2><?php esc_html_e( 'Integration with Wishlist', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'To make you fully enjoy all plugin functionalities we have also integrated it with %1$sYITH WooCommerce Wishlist%2$s.%3$sEnable both plugins, set their options appropriately and in each user will be able to see in his/her own Wishlist page a %1$sslider%2$s with all products associated at least at one of the items in wishlist.', 'yith-woocommerce-frequently-bought-together' ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/06.png"
					alt="Integration with Wishlist"/>
			</div>
		</div>
	</div>
	<div class="seven section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/07.png" alt="Social network"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/07-icon.png" alt="icon 07"/>
					<h2><?php esc_html_e( 'Products to add to cart', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'Among all suggested products, it can occur some are not in users\' plans and they wouldn\'t show interest in adding them all to cart. The premium version of the plugin goes beyond this problem by giving users the possibility to %1$sadd to cart only the products they prefer%2$s.', 'yith-woocommerce-frequently-bought-together' ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="eight section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/08-icon.png" alt="icon 08"/>
					<h2><?php esc_html_e( 'SET DISCOUNT FOR FREQUENTLY BOUGHT TOGETHER GROUP', 'yith-woocommerce-frequently-bought-together' ); ?></h2>
				</div>
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'You can either choose to apply a percentage discount or a fixed amount. The discount will be shown highlighted in the frequently bought together box. The discount will only be applied if the required number of products are selected.' ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WFBT_ASSETS_URL ); ?>/images/08.png"
					alt="Discount on frequently bought"/>
			</div>
		</div>
	</div>
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php // translators: placeholders are used for add HTML tags. ?>
					<?php echo sprintf( esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Frequently Bought Together%2$s to benefit from all features!', 'yith-woocommerce-frequently-bought-together' ), '<span class="highlight">', '</span>' ); ?>
				</p>
				<a href="<?php echo esc_url( $this->get_premium_landing_uri() ); ?>" target="_blank"
					class="premium-cta-button button btn">
					<span
						class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-frequently-bought-together' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-frequently-bought-together' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>
