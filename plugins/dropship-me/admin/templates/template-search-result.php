<?php
/**
 * Author: Vitaly Kukin
 * Date: 14.09.2018
 * Time: 14:52
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate();
?>

{{#each products}}
<!-- item result begin-->
<div class="product-item-list box-shadow position-relative" data-id="{{id}}" data-already="{{already}}">
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="check-box">
        <?php
        echo $tmpl->checkbox( [
	        'value' => '{{@index}}',
	        'id'    => 'check-item-{{@index}}'
        ] )
        ?>
    </div>
    <div class="row no-gutters align-items-center" id="product-{{id}}">
        <div class="col">
            <div class="d-flex align-items-center">
                <div class="product-image">
                    {{#if imageUrl}}
                    <a href="javascript:;" data-action="show" class="js-details">
						<div class="image_hover"></div>
                        <img src="{{imageUrl}}" class="img-fluid">
                    </a>
                    {{/if}}
                </div>
                <div class="product-title">
                    <h3 data-action="show" class="js-details">{{productTitle}}</h3>
					{{#if free}}
						<div class="has-free">
							<i class="fa fa-plane color-green"></i> <?php _e( 'Free shipping', 'dm' ) ?>
						</div>
					{{/if}}
                    <a href="javascript:;" data-supplier="{{supplier_id}}" class="js-set_supplier">
                        by {{supplier.storeName}}
                    </a>

                    <div class="rate-block-small d-none d-sm-block">
                        <div class="stars-box d-inline-block" style="margin-top: 5px;">
                            <div class="stars">
                                <div class="stars-line" style="width:{{ratePercent}}%"></div>
                            </div>
                        </div>
                        <strong>({{productRate}})</strong> <span class="separate"></span> <?php _e( 'Orders', 'dm' ) ?>: <strong> {{purchaseVolume}}</strong>
                        {{#if free}}
                             <br><div class="mt-1 d-inline-block"><i class="fa fa-plane color-green"></i> <?php _e( 'Free shipping', 'dm' ) ?></div>
                        {{/if}}
					</div>
                    {{#if date_import}}<div class="pt-1 import-time"><?php _e( 'Imported' ) ?>: <strong>{{date_import}}</strong></div>{{/if}}
                </div>
            </div>
            <div class="d-block d-sm-none">
                 <div class="stars-box d-inline-block mt-3">
                     <div class="stars">
                         <div class="stars-line" style="width:{{ratePercent}}%"></div>
                     </div>
                 </div>
                 <strong class='strong600'>({{productRate}})</strong> <span class="separate"></span> <?php _e( 'Orders', 'dm' ) ?> : <strong class='strong600'> {{purchaseVolume}}</strong>
                        {{#if free}}
                             <span class="separate"></span> <br><i class="fa fa-plane color-green mt-2"></i> <?php _e( 'Free shipping', 'dm' ) ?>
                        {{/if}}
            </div>
            <div class="d-flex d-lg-none flex-column flex-sm-row align-items-left align-items-sm-center justify-content-around product-mobile-friendly pt-3">
                <div class="supplier-price midd text-sm-center">
                    <h5 class="text-sm-center"><?php _e( 'Supplier price', 'dm' ) ?>:</h5>
                    <h4 class="color-orange">{{format_price origPrices.origSalePrice}}</h4>
                </div>
                <div class="recommended-price d-block text-sm-center">
                    <h5 class="text-sm-center"><?php _e( 'Recommended', 'dm' ) ?>:</h5>
                    <h4>{{format_price prices.salePrice}}</h4>
                </div>
                <div class="profit-price d-block text-sm-center">
                    <h5 class="text-sm-center"><?php _e( 'Your profit', 'dm' ) ?>:</h5>
                    <h4>{{math_format prices.salePrice '-' origPrices.origSalePrice}}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 text-center rate-block-large">
            <div class="star-rate">
                <div class="stars-box d-inline-block">
                    <div class="stars">
                        <div class="stars-line" style="width:{{ratePercent}}%"></div>
                    </div>
                </div>
                <strong>({{productRate}})</strong>
            </div>
            <div class="product-rate">
                <?php _e( 'Orders', 'dm' ) ?>: <strong> {{purchaseVolume}}</strong>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 text-center d-none d-lg-flex flex-row align-items-center justify-content-around product-mobile-friendly">
            <div class="col-xl-6 col-lg-6 supplier-block">
                <div class="supplier-price">
                    <h5><?php _e( 'Supplier price', 'dm' ) ?>:</h5>
                    <h4 class="color-orange">{{format_price origPrices.origSalePrice}}</h4>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 price-block">
				<div class="supplier-price midd">
                    <h5><?php _e( 'Supplier price', 'dm' ) ?>:</h5>
                    <h4 class="color-orange">{{format_price origPrices.origSalePrice}}</h4>
                </div>
                <div class="recommended-price">
                    <h5><?php _e( 'Recommended', 'dm' ) ?>:</h5>
                    <h4>{{format_price prices.salePrice}}</h4>
                </div>
                <div class="profit-price">
                    <h5><?php _e( 'Your profit', 'dm' ) ?>:</h5>
                    <h4>{{math_format prices.salePrice '-' origPrices.origSalePrice}}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3 col-lg-3 text-center text-md-right action-buttons pt-2 pt-md-0 d-none d-sm-block">
            <button class="btn btn-green ads-no first-btn js-import-product" {{#ifCond already "==" "1" }}disabled="disabled"{{/ifCond}}>
                {{#ifCond already "==" "1" }}
                    <?php _e( 'Imported', 'dm' ) ?>
                {{else}}
                    <i class="icon-plus-svg"></i>
                    {{#ifCond imported "==" "1" }}
                        <?php _e( 'Re-import', 'dm' ) ?>
                    {{else}}
                        <?php _e( 'Import', 'dm' ) ?>
                    {{/ifCond}}
                {{/ifCond}}
            </button>
            <!--<button class="btn btn-green-transparent ads-no js-details" data-action="show">
                <?php _e( 'View details', 'dm' ) ?>
            </button>-->
			<a href="javascript:;" class="details_burger ads-no js-details" data-action="show"></a>
        </div>
		<div class="col-lg-3 d-block d-sm-none">
			<div class="text-center row action-buttons d-flex justify-content-center">
				<button class="mr-2 ml-3 btn btn-green ads-no js-import-product" {{#ifCond already "==" "1" }}disabled="disabled"{{/ifCond}}>
					{{#ifCond already "==" "1" }}
						<?php _e( 'Imported', 'dm' ) ?>
					{{else}}
						<i class="icon-plus-svg"></i>
						{{#ifCond imported "==" "1" }}
							<?php _e( 'Re-import', 'dm' ) ?>
						{{else}}
							<?php _e( 'Import', 'dm' ) ?>
						{{/ifCond}}
					{{/ifCond}}
				</button>
				<!--<button class="col ml-2 mr-3 btn btn-green-transparent ads-no js-details" data-action="show">
					<?php _e( 'View details', 'dm' ) ?>
				</button>-->
				<a href="javascript:;" class="ml-2 mr-3 details_burger ads-no js-details" style="top: 4px;height: 42px; width: 42px;" data-action="show"></a>
			</div>
		</div>
    </div>
    <div class="product-info" id="supplier-{{id}}"></div>
</div>
<!-- item result end-->
{{/each}}

<div class="row py-3">
    <div class="col-12 col-lg-6 justify-content-lg-start d-flex align-items-center justify-content-center" id="currency_converter">
        <span class="pull-left">
            {{{currency_converter}}}
        </span>
    </div>
    <div class="col text-lg-right text-center">
        <div class="tab-nav-elements tab-nav-last d-inline-flex">
            <div class="pagination-menu jqpagination mr-0 mr-sm-3"></div>
        </div>
    </div>
</div>
