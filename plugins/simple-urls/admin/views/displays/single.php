<?php
use LassoLite\Classes\Affiliate_Link;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Lasso_URL;

$lasso_lite_url = Affiliate_Link::get_lasso_url( $post->ID );

// ? custom attributes
$title_type_start = '';
$title_type_end = '';
if ( '' !== $title_type ) {
	$title_type       =  esc_html( $title_type );
	$title_type_start = '<' . $title_type . '>';
	$title_type_end   = '</' . $title_type . '>';
}
$title_url = '' !== $title_url ? $title_url : $lasso_lite_url->public_link;
$title_name = $lasso_lite_url->name;
$enable_brag_mode = 'true' === $brag ? true : false;

if ( ! empty( $title ) ) {
	$title_name = $title;
	$title_name = 'hide' === $title ? '' : $title_name;
}
if ( ! empty( $description ) ) {
	$lasso_lite_url->description = 'hide' === $description ? '' : $description;
}
if ( ! empty( $badge ) ) {
	$lasso_lite_url->display->badge_text = 'hide' === $badge ? '' : $badge;
}
if ( ! empty( $price ) ) {
	$lasso_lite_url->price = 'hide' === $price ? '' : $price;
}
if ( ! empty( $primary_url ) ) {
	$lasso_lite_url->public_link = $primary_url;
}
if ( ! empty( $primary_text ) ) {
	$lasso_lite_url->display->primary_button_text = $primary_text;
}
if ( ! empty( $image_url ) ) {
	$lasso_lite_url->image_src = $image_url;
}
// ? custom attributes - end

$lasso_url_obj            = new Lasso_URL( $lasso_lite_url );
$public_link              = $lasso_lite_url->public_link;
$html_attribute           = $lasso_lite_url->html_attribute;
$badge_display_class      = $lasso_lite_url->display->badge_text ? '' : 'lasso-none';
$show_price_class         = $lasso_lite_url->display->show_price ? '' : 'lasso-none';
$price_display_class      = $lasso_lite_url->price ? '' : 'lasso-none';
$disclosure_display_class = $lasso_lite_url->show_disclosure ? '' : 'lasso-none';
$image_alt                = $lasso_lite_url->name;
?>

<div id="<?php echo esc_attr( $anchor_id ); ?>" class="lasso-container lasso-lite">
	<!-- LASSO DISPLAY BOX (https://getlasso.co) -->
	<div class="lasso-display lasso-cactus">
		<!-- BADGE -->
		<div class="lasso-badge <?php echo esc_attr( $badge_display_class ) ?>">
			<?php echo esc_html( $lasso_lite_url->display->badge_text ); ?>
		</div>

		<div class="lasso-box-1">
			<!-- LASSO TITLE -->
			<?php echo $title_type_start; ?>
			<a class="lasso-title" <?php echo $lasso_url_obj->render_attributes(); ?>><?php echo esc_html( $title_name ); ?></a>
			<?php echo $title_type_end; ?>
			<div class="clear"></div>

			<!-- LASSO PRICE -->
			<div class="lasso-price <?php echo esc_attr( $show_price_class ) ?>">
				<div class="lasso-price-value <?php echo esc_attr( $price_display_class ) ?>">
					<span class="latest-price"><?php echo esc_html( $lasso_lite_url->price ); ?></span>
				</div>
			</div>
			<div class="clear"></div>

			<!-- LASSO DESCRIPTION -->
			<?php if ( ! empty( $lasso_lite_url->description ) ) : ?>
				<div class="lasso-lite-description"><?php echo Helper::sanitize_script( $lasso_lite_url->description ); ?></div>
			<?php endif; ?>
		</div>

		<!-- LASSO IMAGE -->
		<div class="lasso-box-2">
			<a class="lasso-image" <?php echo $lasso_url_obj->render_attributes(); ?>>
				<img src="<?php echo esc_url( $lasso_lite_url->image_src ); ?>" height="500" width="500" <?php echo Helper::build_img_lazyload_attributes() ?> alt="<?php echo esc_attr( $image_alt ) ?>" />
			</a>
		</div>

		<!-- BUTTONS -->
		<div class="lasso-box-3">
			<a class="lasso-button-1" <?php echo $lasso_url_obj->render_attributes(); ?>><?php echo esc_html( $lasso_lite_url->display->primary_button_text ); ?></a>
		</div>

		<div class="lasso-box-4"></div>

		<!-- DISCLOSURE -->
		<div class="lasso-box-5">
			<div class="lasso-disclosure <?php echo esc_attr( $disclosure_display_class ) ?>">
				<span><?php echo esc_html( $lasso_lite_url->display->disclosure_text ); ?></span>
			</div>
		</div>

		<!-- DATE -->
		<div class="lasso-box-6">
			<div class="lasso-date">
				<?php
				if ( empty( $show_price_class ) && empty( $price_display_class ) && ! empty( $lasso_lite_url->price ) ) {
					// phpcs:ignore
					echo esc_html( $lasso_lite_url->display->last_updated ) . ' <i class="lasso-amazon-info" data-tooltip="Price and availability are accurate as of the date and time indicated and are subject to change."></i>';
				}
				?>
			</div>
		</div>

		<!-- BRAG -->
		<div class="lasso-single-brag">
			<?php echo Helper::get_brag_icon( $enable_brag_mode ); ?>
		</div>
	</div>
</div>
