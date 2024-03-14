   <?php
/**
 * Premium vs Free version
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin/partial
 * @author     Ohidul Islam <wahid@webappick.com>
 * @version    1.0.2
 */
if ( ! function_exists( 'add_action' ) ) die();

$features = array(
	/*array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-extended-product-title-thumbnail.svg',
		'title'       => 'Extended Product Title',
		'description' => 'The first impression becomes the last impression. Products with optimized Titles increase 250% conversion rate. You must highlight the product title with relevant information so the customer can decide if the product is worth clicking.',
        'more-link'   => '#',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-merge-attributes-thumbnail.svg',
		'title'       => 'Attribute Mapping',
		'description' => 'Merge multiple attribute within one. To meet multiple channel requirements, you may require diverse custom product information to represent your products.',
        'more-link'   => '#',
	),*/
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-smart-filter-thumbnail.svg',
		'title'       => 'Smart Filter',
		'description' => 'The smart filter option helps you exclude products that are missing descriptions, images, or prices. Also, you can exclude products that you don’t want to advertise or products that do not have all the information required by your marketing channel. ',
		'more-link'   => 'https://www.youtube.com/watch?v=ldquLykm1oM&list=PLapCcXJAoEemw__TnMPlVOvdGGdZce_Ka',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-advanced-filter-thumbnail.svg',
		'title'       => 'Advance Filter',
		'description' => 'Exclude the non-profitable or out-of-season products you don’t want to advertise or Include only the Profitable or Seasonal products. Its high filtering options help filter products conditionally according to product titles, price, availability of stocks, user rating, and other product attributes.',
		'more-link'   => 'https://youtu.be/-1w2_wNJgDc',
    ),
    array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-exclude-include-categories-thumbnail.svg',
		'title'       => 'Exclude/Include Categories',
		'description' => "With category filtering feature, you can include or exclude specific category products from the product feed. This filtering option enables you to include or exclude categories based on your product preference or current market demand.",
		'more-link'   => 'https://webappick.com/docs/woo-feed/filter-products/how-to-make-feed-for-specific-categories/',
    ),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-exclude-include-products-thumbnail.svg',
		'title'       => 'Exclude/Include Specific Products',
		'description' => 'With the product filtering feature, you can include or exclude specific products from the product feed by product ID. ',
		'more-link'   => 'https://webappick.com/docs/woo-feed/filter-products/how-to-make-feed-for-specific-product-type-simple-parent-and-child/',
    ),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-out-of-stock-thumbnail.svg',
		'title'       => 'Out of Stock',
		'description' => 'Browsing lots of out-of-stock items get discouraging for Customers. The filter section comes with a handy feature to exclude all of the out-of-stock products from your product feed in just a click.',
		'more-link'   => 'https://youtu.be/FeuvVCshFgU',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-variations-thumbnail.svg',
		'title'       => 'Variations',
		'description' => 'Deal with your product variations with more power. There are flexible options to choose only the variable products (Parent of the variations), default, or first or last product variations. Additionally, you can also choose a combination of both.',
		'more-link'   => 'https://youtu.be/LcubgxVxISc',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-auto-update-interval-thumbnail.svg',
		'title'       => 'Auto Update',
		'description' => 'This plugin automatically updates your product data feed according to your given schedule. Save you time and money by automating the process of updating your product data on multiple channels. ',
		'more-link'   => 'https://youtu.be/fafHqr9A81A',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-string-replace-thumbnail.svg',
		'title'       => 'String Replace',
		'description' => 'String replace is a life-saving feature when you are advertising your product to multiple channels. Google Shopping requires availability value as in stock or out of stock but bestPrice requires availability value as Yes or No.',
		'more-link'   => 'https://youtu.be/CfsXYtGxvW4',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-multi-language-support-thumbnail.svg',
		'title'       => 'Multi Language',
		'description' => 'With CTX Feed Pro you can create a multilingual product feed that will allow you to reach customers of any language and country. It’s easy to use and is compatible with the most popular multilingual plugin WPML, PolyLang & TranslatePress.',
		'more-link'   => 'https://youtu.be/JzPVOVLtzVY',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-multi-currency-thumbnail.svg',
		'title'       => 'Multi Currency',
		'description' => 'Suppose your WooCommerce store already has a multi-currency feature, or you are thinking about it. In that case, This product feed manager plugin will allow you to create a product feed by specific currency effortlessly.',
		'more-link'   => 'https://youtu.be/6lCRT9H0kY0',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-dynamic-attributes-thumbnail.svg',
		'title'       => 'Dynamic Attribute (IFTTT)',
		'description' => 'To meet multiple channel requirements, you may require diverse custom product information to represent your products. Dynamic Attribute features help you quickly meet your channel requirements while your products are missing some information required by your channel or dynamically enhances product information.',
		'more-link'   => 'https://youtu.be/tp8Axi60ThU',
	),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-dynamic-pricing-thumbnail.svg',
		'title'       => 'Dynamic Pricing',
		'description' => 'Sometime you may need to increase or decrease product price for a specific marketing channel. By making a Dynamic Attribute you can conditionally increase/decrease product price effortlessly.',
		'more-link'   => 'https://youtu.be/elYbtW882vw',
	),
    array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-multi-vendors-thumbnail.svg',
		'title'       => 'Multi Vendor Compatibility',
		'description' => 'CTX Feed pro allows you to create a product feed for multiple or a specific vendor. Generating product feed for numerous vendors is straightforward with this plugin. Choose single or multiple vendors while creating product feeds. Support all the popular multi-vendor plugins.',
		'more-link'   => 'https://youtu.be/es0qCA-_2jo',
    ),
	array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-custom-template-2-thumbnail.svg',
		'title'       => 'Custom Template 2',
		'description' => 'Make any kind of complex XML product feed using the Custom Template 2 (XML).',
		'more-link'   => 'https://webappick.com/docs/woo-feed/feed-configuration/custom-template-2-php-pattern/',
	),
    array(
		'thumb'       => esc_url( WOO_FEED_PLUGIN_URL ) . 'admin/images/feature-number-format-thumbnail.svg',
		'title'       => 'Number Format',
		'description' => 'Format the product prices according to your preferred number format. It also allows you to define the number according to thousand separators or decimal separators. Different merchant centers require different price or number formats. So, you can define the number format according to any merchant’s requirements. 
',
		'more-link'   => 'https://youtu.be/Os2zOKvhV2Q',
    ),
);
$allowedHtml = array(
	'br'   => array(),
	'code' => array(),
	'sub'  => array(),
	'sup'  => array(),
	'span' => array(),
	'a'    => array(
		'href'   => array(),
		'target' => array(),
	),
);
ob_start(); ?>
<div class="wrap wapk-admin wapk-feed-pro-upgrade">
	<div class="wapk-section wapk-feed-banner">
		<div class="wapk-banner">
			<a href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=freePlugin&utm_medium=go_premium&utm_campaign=free_to_pro&utm_term=wooFeed" target="_blank">
				<img class="wapk-banner__graphics" src="<?php echo esc_url( WOO_FEED_PLUGIN_URL ); ?>admin/images/woo-feed-pro-banner.png" alt="<?php esc_attr_e( 'Upgrade to WooFeed Pro to unlock more powerful features.', 'woo-feed' ); ?>">
			</a>
		</div>
	</div>
	<div class="clear"></div>
	<div class="wapk-section wapk-feed-features">
		<div class="wapk-feed-feature__list">
			<?php foreach ( $features as $feature ) { ?>
			<div class="wapk-feed-feature__item">
				<div class="wapk-feed-feature__thumb">
					<img src="<?php echo esc_url( $feature['thumb'] ); ?>" alt="<?php echo esc_attr( $feature['title'] ); ?>" title="<?php echo esc_attr( $feature['title'] ); ?>">
				</div>
				<div class="wapk-feed-feature__description">
					<h3><?php echo wp_kses( $feature['title'], $allowedHtml ); ?></h3>
					<p><?php echo wp_kses( $feature['description'], $allowedHtml ); ?></p>
				</div>
                <div class="wapk-feed-feature__links">
                    <a target="_blank" rel="noreferrer" href="<?php echo esc_url( $feature['more-link'] ); ?>"><?php esc_html_e('Learn More', 'woo-feed')?></a>
                </div>
			</div>
			<?php } ?>
		</div>
	</div>

    <div class="wapk-feed-buy-now">
        <div class="wapk-feed-buy-now-container fixed">
            <div class="wapk-feed-buy-now-wrapper">
                <div class="wapk-feed-buy-now-product-container">
                    <div class="wapk-feed-buy-now-thumbnail">
                        <img src="<?php echo WOO_FEED_FREE_ADMIN_URL; ?>images/woo-feed-icon.svg" alt="CTX Feed Pro" />
                    </div>
                    <div class="wapk-feed-buy-now-title">
                        <div class="wapk-feed-buy-now-product-name"><?php esc_attr_e('CTX Feed Pro', 'woo-feed');?></div>
                        <div class="wapk-feed-buy-now-price">
                            <span class="from"><?php esc_html_e('From: ', 'woo-feed');?></span>
                            <span class="woocommerce-Price-amount amount">
                        <bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e('$ ', 'woo-feed');?></span><?php esc_attr_e('119.00', 'woo-feed');?></bdi>
                    </span>
                            <span class="subscription-details"><?php esc_html_e('/ year', 'woo-feed');?></span>
                        </div>
                    </div>
                    <div class="wapk-feed-buy-now-product-description"><?php esc_attr_e('CTX Feed Pro is the most optimized &amp; error-free WooCommerce product feed manager that makes your product listing approved faster, [...]', 'woo-feed');?></div>
                </div>
                <div class="wapk-feed-buy-now-product-meta">
                    <div class="wapk-feed-buy-now-btn">
                        <a href="https://webappick.com/plugin/woocommerce-product-feed-pro/"><?php esc_html_e('Buy Now', 'woo-feed');?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	<div class="clear"></div>

	<div class="wapk-section wapk-feed-cta">
		<div class="wapk-cta">
			<div class="wapk-cta-icon">
				<span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
			</div>
			<div class="wapk-cta-content">
				<h2><?php esc_html_e( "Still need help?", "woo-feed" ); ?></h2>
				<p><?php _e( "Have we not answered your question?<br>Don't worry, you can contact us for more information...", "woo-feed") ?></p>
			</div>
			<div class="wapk-cta-action">
				<a href="https://wordpress.org/support/plugin/webappick-product-feed-for-woocommerce/#new-topic-0" class="wapk-button wapk-button-primary woo-feed-btn-bg-gradient-blue" target="_blank"><?php esc_html_e( 'Get Support', 'woo-feed' ); ?></a>
			</div>
		</div>
	</div>
</div>