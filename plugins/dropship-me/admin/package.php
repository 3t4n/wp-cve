<?php
/**
 * Author: Vitaly Kukin
 * Date: 05.06.2018
 * Time: 9:04
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate(); ?>

<div class="wrap">
	<div class="card-deck">
        <?php
        $tmpl->renderPanel( [
            'panel_title'   => 'Get even more best sellers into your store!',
            'panel_class'   => 'success',
            'panel_help'    => 'https://help.dropship.me/products/how-to-get-more-products',
            'panel_content' =>
                '<div id="package-panel">
                    <p>
                        Time flies when you\'re working hard on growing your business. Unfortunately, so do the
                        product imports. In order to have more trending and winning products in your store, you can
                        order DropshipMe packages.
                    </p>
                    <p>
                        Avoid the extra time, hassle and expense of going back to guesswork of what to sell and
                        product info manual editing. Go ahead and choose a package below, activate it and fill your
                        store with selected, profit-oriented products.
                    </p>
                </div>'
        ] );
        ?>
	</div>
	<div class="card-deck">
        <?php
        $tmpl->renderPanel( [
            'panel_title'   => false,
            'panel_class'   => 'success w-100',
            'panel_content' =>
                '<div id="package-item-100" class="package-item text-center">
                    <h1 class="text-center">100 products import</h1>
                    <div class="price h1 text-center">$29</div>
                    <div class="small text-center">One time payment</div>
                    <div class="text-center text-bonus">
                        +10 bonus products
                    </div>
                    <p>The core you need for a growing business</p>
                    <a class="btn btn-lg btn-green" target="_blank"
                        href="https://dropship.me/purchase/?product=package&type_custom=basic">Order Now</a>
                </div>'
        ] );
        
        $tmpl->renderPanel( [
            'panel_title'   => false,
            'panel_class'   => 'success w-100',
            'panel_content' =>
                '<div id="package-item-100" class="package-item text-center">
                    <h1 class="text-center">500 products import</h1>
                    <div class="price h1 text-center">$119</div>
                    <div class="small text-center">One time payment</div>
                    <div class="text-center text-bonus">
                        +200 bonus products
                    </div>
                    <p>Smart way to scale your store to a degree</p>
                    <a class="btn btn-lg btn-green" target="_blank"
                        href="https://dropship.me/purchase/?product=package&type_custom=advanced">Order Now</a>
                </div>'
        ] );
        
        $tmpl->renderPanel( [
            'panel_title'   => false,
            'panel_class'   => 'success w-100',
            'panel_content' =>
                '<div id="package-item-100" class="package-item text-center">
                    <h1 class="text-center">1000 products import</h1>
                    <div class="price h1 text-center">$199</div>
                    <div class="small text-center">One time payment</div>
                    <div class="text-center text-bonus">
                        +500 bonus products
                    </div>
                    <p>Power up and meet all your customers’ needs</p>
                    <a class="btn btn-lg btn-green" target="_blank"
                        href="https://dropship.me/purchase/?product=package&type_custom=ultimate">Order Now</a>
                </div>'
        ] );
        ?>
	</div>
</div>