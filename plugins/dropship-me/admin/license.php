<?php
/**
 * Author: Vitaly Kukin
 * Date: 16.06.2016
 * Time: 11:22
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$view = dm_check_hash();

$tmpl = new \dm\dmTemplate();

$tmpl->addItem( 'nonce', [
	'name'  => 'dm_package',
	'value' => 'dm_setting_action'
] );
$tmpl->addItem( 'text', [
	'label' => __( 'Enter your package code', 'dm' ),
	'name' => 'dm_packagekey'
] );
$tmpl->addItem( 'button', [
	'class' => 'ads-button btn btn-blue ads-no js-activate-package',
	'value' => __( 'Activate', 'dm' )
] );

$tmpl->template( 'dm-form', $tmpl->renderItems() );

$tmpl->addItem( 'nonce', [
	'name'  => 'dm_license',
	'value' => 'dm_setting_action'
] );
$tmpl->addItem( 'text', [
	'label' => __( 'Enter your API key', 'dm' ),
	'name' => 'dm_licensekey'
	] );
$tmpl->addItem( 'button', [
    'class' => 'ads-button btn btn-blue ads-no js-activate',
    'value' => __( 'Activate', 'dm' )
] );

$tmpl->template( 'dm-license-form', $tmpl->renderItems() ); ?>

<div class="wrap">
    <?php if( $view ) : ?>
        <div class="row pt-3">
            <div class="col">
                <a href="javascript:;"
                   data-toggle="collapse"
                   data-target="#collapseSettings"
                   aria-controls="collapseSettings"
                   aria-expanded="false" class="btn btn-green">
                    <?php _e( 'API key', 'dm' ) ?>
                </a>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div <?php echo $view ? 'class="col-md-6 collapse" id="collapseSettings"' : 'class="col-md-6"' ?>>
            <?php
            $tmpl->renderPanel( [
                'panel_title'   => __( 'API key', 'dm' ),
                'panel_class'   => 'success',
                'panel_content' => '<div id="license-form"></div>',
                'panel_help' => 'https://help.dropship.me/installation/api-activation'
            ] );
            ?>
        </div>
		<div <?php echo $view ? 'class="col-md-6 collapse" id="collapseSettings"' : 'class="col-md-6"' ?>>
            <?php
            $tmpl->renderPanel( [
                'panel_title'   => __( 'How to get API key', 'dm' ),
                'panel_class'   => 'success activate-desc text-left',
                'panel_content' => '<div>The plugin provides the access to the product database via API. To activate the plugin, visit <a href="https://dropship.me/plugin/" target="_blank">DropshipMe website</a>, enter your email and click ‘Get my plugin now’ to get your free API key. After the activation you immediately get 50 free products ready to import to your store.</div>
				<br><div>Please note that your API key can be activated only once and used only on one domain. If you activate another API key on the same domain, no more free product imports will be given.</div>'
            ] );
            ?>
        </div>
    </div>
    <div class="card-deck">
        <?php
            $tmpl->renderPanel( [
                'panel_title'   => __( 'Get more products', 'dm' ),
                'panel_class'   => 'success w-100',
                'panel_help'    => 'https://help.dropship.me/products/product-packages',
                'panel_content' => '<div id="package-form"></div>'
            ] );
        
            $tmpl->renderPanel( [
                'panel_title'   => false,
                'panel_class'   => 'success w-100',
                'panel_content' =>
                    '<div id="package-count" class="text-center">
                        <h1 class="count text-center">0</h1>' . __( 'Product imports available', 'dm' ) .
                    '</div>'
            ] );
        ?>
    </div>
</div>