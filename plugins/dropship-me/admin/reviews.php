<?php
/**
 * Author: Vitaly Kukin
 * Date: 25.07.2018
 * Time: 21:36
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate();

$tmpl->addItem( 'hidden', [
	'name'  => 'current_item',
	'id'    => 'current_item',
	'value' => "0"
] );
$tmpl->addItem( 'hidden', [
	'name'  => 'total_item',
	'id'    => 'total_item',
	'value' => "0"
] );
$tmpl->addItem( 'select', [ 'name' => 'min_star', 'class' => 'd-block w-100' ] );
$tmpl->addItem( 'select', [
	'name' => 'count_review',
	'class' => 'd-block w-100',
	'help' => __( 'The number of imported reviews per product.', 'dm' )
] );
$tmpl->addItem( 'custom', [ 'class'=> 'mb-1','value' => __('Apply to', 'dm') ] );
$tmpl->addItem( 'select', [ 'name' => 'prod_type', 'class' => 'd-block w-100' ] );
$tmpl->addItem( 'select', [ 'name' => 'product_cat', 'class' => 'd-block w-100', 'multiple' => true ] );

$tmpl->addItem( 'switcher', [
	'name'  => 'apply_empty',
	'value' => 1,
	'label' => __( 'Products that have reviews less than', 'dm' )
] );

$tmpl->addItem( 'text', ['name'  => 'apply_min'] );
$tmpl->addItem( 'switcher', [
	'name'  => 'approved',
	'value' => 1,
	'label' => __('Send reviews to draft', 'dm')
] );
$tmpl->addItem( 'switcher', [
	'name'  => 'onlyFromMyCountry',
	'value' => 1,
	'label' => __( 'Import reviews from my country only', 'dm' )
] );
$tmpl->addItem( 'switcher', [
	'name'  => 'switchTranslate',
	'value' => 1,
	'label' => __( 'Translate reviews into my language', 'dm' )
] );
$tmpl->addItem( 'switcher', [
	'name'  => 'ignoreImages',
	'value' => 1,
	'label' => __( 'Ignore images in reviews', 'dm' )
] );
$tmpl->addItem( 'switcher', [
	'name'  => 'withImage',
	'value' => 1,
	'label' => __( 'Import reviews with images only', 'dm' )
] );
$tmpl->addItem( 'switcher', [
	'name'  => 'uploadImage',
	'value' => 1,
	'label' => __( 'Upload images to server', 'dm' )
] );

$tmpl->addItem( 'button', [
	'value' => __( 'Import', 'dm' ),
	'id'    => 'js-reviewImport',
	'class' => 'btn btn-green ads-no'
] );

$tmpl->template( 'ali-review', $tmpl->renderItems() );

$layout = ! empty( $current ) ?
	$tmpl->button( [
		'value' => __( 'Continue', 'dm' ),
		'id'    => 'js-reviewNext',
		'class' => 'btn btn-green-transparent ads-no margin-top-15'
	] ) : '';
?>
	<div class="wrap">
		
		<div class="row">
			<div class="col-md-4 col-lg-3">
				<?php
				$tmpl->renderPanel( [
					'panel_title'   => __( 'Settings', 'dm' ),
					'panel_class'   => 'success w-100',
					'panel_help'    => 'https://help.dropship.me/products/importing-reviews',
					'panel_content' => '<div id="dm_review-form"></div>'
				] );
				?>
			</div>
			<div class="col-md-8 col-lg-9">
				<?php
				$tmpl->renderPanel( [
					'panel_title'   => __( 'Product List', 'dm' ),
					'panel_class'   => 'info w-100',
					'panel_content' => $tmpl->progressbar( [ 'id' => 'activity-list' ] ) . $layout .
					                   '<div id="dm_activities-list" class="margin-top-10"></div>'
				] );
				?>
			</div>
		</div>
	</div>

<?php
$template = sprintf(
	'<div class="row table-item review-item" data-post_id="{{post_id}}" data-product_id="{{product_id}}" data-feedbackUrl="{{feedbackUrl}}">
        <div class="col-lg-2 text-center"><img src="{{imageUrl}}" class="img-fluid"></div>
        <div class="col-lg-9 col-lg-offset-1">
            <h4 class="margin-top-0">{{post_title}}</h4>
            <i class="fa fa-comment color-blue"></i> %s: <strong class="count-reviews">0</strong>
        </div>
    </div>',
	__( 'Reviews', 'dm' )
);
$tmpl->template( 'item-review-template', $template );