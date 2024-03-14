<?php
/**
 * Author: Vitaly Kukin
 * Date: 18.09.2018
 * Time: 13:17
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate();
?>

<div class="row">
    <a href="javascript:;" class="close-detail-block d-block d-sm-none js-details" data-action="hide"></a>
    <!-- gallery begin -->
    <div class="col-lg-3 col-md-4 col-sm-5">
        <div id="slider-{{id}}" class="carousel slide importer-gallery m-0 mx-auto" data-ride="carousel">
            <ol class="carousel-indicators">
                {{#imgs}}
                <li data-target="#slider-{{../id}}" data-slide-to="{{@index}}" class="{{#if @first}}active{{/if}}"></li>
                {{/imgs}}
            </ol>
            <div class="carousel-inner" role="listbox">
                {{#each imgs}}
                <div class="carousel-item {{#if @first}}active{{/if}}">
                    <a href="{{urlFull}}" data-fancybox="gallery-{{../id}}">
                        <img src="{{urlFull}}" alt="{{alt}}" class="img-fluid">
                    </a>
                </div>
                {{/each}}
            </div>
            <a class="carousel-control-prev" href="#slider-{{id}}" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#slider-{{id}}" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <!-- gallery end -->

    <!-- summary begin -->
    <div class="col">
        <h3 class="product-title-more">{{product_title}}</h3>

        <div class="rate-info">
            <div class="d-flex flex-row mb-1">
                <div class="stars-box d-flex align-items-center mr-2">
                    <div class="stars">
                        <div class="stars-line" style="width:{{ratePercent}}%"></div>
                    </div>
                </div>
                <div class="product-rate d-flex align-items-center">
                    <strong>({{productRate}})</strong><span class="separate">|</span> <?php _e( 'Orders', 'dm' ) ?>:&#8194;<strong>{{purchaseVolume}}</strong>
                </div>
                {{#if free}}
                <div class="has-free">
                    <i class="fa fa-plane color-green"></i> <?php _e( 'Free shipping', 'dm' ) ?>
                </div>
                {{/if}}
            </div>
        </div>

        <div class="clearfix summary">
            <div class="row params-list">
                <div class="col-lg-3 col-5"><?php _e( 'Supplier price', 'dm' ) ?>:</div>
                <div class="col">
					<span class="supplier-price color-orange">
						{{format_price origPrices.origSalePrice}}
					</span>
                </div>
            </div>
            <div class="row params-list">
                <div class="col-lg-3 col-5"><?php _e( 'Recommended', 'dm' ) ?>:</div>
                <div class="col">
					<span class="recommended-price">
						{{format_price prices.salePrice}}
					</span>
                </div>
            </div>
            <div class="row params-list">
                <div class="col-lg-3 col-5"><?php _e( 'Your profit', 'dm' ) ?>:</div>
                <div class="col">
					<span class="profit-price">
						{{math_format prices.salePrice '-' origPrices.origSalePrice}}
					</span>
                </div>
            </div>
            <div class="row params-list">
                <div class="col-lg-3 col-5"><?php _e( 'Quantity', 'dm' ) ?>:</div>
                <div class="col">
                    <span class="quantity">{{quantity}}</span> / <small><?php _e( 'pieces available', 'dm' ) ?></small>
                </div>
            </div>
            {{#if sku}}
            <a href="javascript:;"
               data-toggle="collapse"
               data-target="#dm_collapseVariants-{{id}}"
               aria-controls="dm_collapseVariants-{{id}}"
               aria-expanded="false" class="btn variants-btn d-sm-none">
                <?php _e( 'VARIANTS', 'dm' ) ?>
            </a>
            {{/if}}
            <div class="params-list-sku" id="dm_collapseVariants-{{id}}">
                {{#each sku}}
                <div class="row sku params-list no-gutters">
                    <div class="col-lg-3">{{sku-title}}:</div>
                    <div class="col">
                        <ul class="sku-list">
                            {{#each sku-attr}}
                            <li>
                                {{#if img.length}}
                                <span class="sku-wrap-img">
                                            <a href="{{imgFull}}" data-fancybox="sku-{{../../id}}">
                                                <img class="img-fluid" src="{{image img 1}}" title="{{title}}">
                                            </a>
                                        </span>
                                {{else}}
                                <span class="sku-text">{{title}}</span>
                                {{/if}}
                            </li>
                            {{/each}}
                        </ul>
                    </div>
                </div>
                {{/each}}
            </div>
        </div>
    </div>

    <!-- action begin -->
    <div class="col-lg-3 d-none d-sm-block">
        <div class="text-right action-buttons">
            <button class="btn btn-green ads-no js-import-product" {{#ifCond already "==" "1" }}disabled="disabled"{{/ifCond}}>
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
            <!--<button class="btn btn-green-transparent ads-no js-details" data-action="hide">
				<?php _e( 'Hide Details', 'dm' ) ?>
			</button>-->
            <a href="javascript:;" class="details_burger ads-no js-details"  data-action="hide"></a>
            <div class="report-this py-3">
                <a href="javascript:;" data-id="{{id}}" class="color-blue"><?php _e( 'Report this product', 'dm' ) ?></a>
            </div>
        </div>
    </div>
    <!-- action end -->

</div>

<!-- description tabs begin -->
<div class="row description-row">
    <div class="col">
        <ul class="nav nav-tabs mobile_dest d-none d-sm-flex" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="description-{{id}}" data-toggle="tab"
                   href="#description-tab-{{id}}" role="tab" aria-selected="true">
                    <?php _e( 'Description', 'dm' ) ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="shipping-{{id}}" data-toggle="tab" data-shipping="{{productId}}"
                   href="#shipping-tab-{{id}}" role="tab" aria-selected="false">
                    <?php _e( 'Shipping', 'dm' ) ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="reviews-{{id}}" data-toggle="tab" data-url="{{feedbackUrl}}"
                   href="#reviews-tab-{{id}}" role="tab" aria-selected="false">
                    <?php _e( 'Reviews', 'dm' ) ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="analysis-{{id}}" data-toggle="tab" data-analysis="{{productId}}"
                   data-sub_title="<?php _e( 'Orders', 'dm' ) ?>" data-time="<?php _e( 'Date', 'dm' ) ?>"
                   href="#analysis-tab-{{id}}" role="tab" aria-selected="false">
                    <?php _e( 'AliExpress', 'dm' ) ?>
                </a>
            </li>
        </ul>
        <div class="d-sm-none d-block desktop_dest">
            <a class="desc-btn nav-link" id="description-{{id}}">
                <?php _e( 'Description', 'dm' ) ?>
            </a>
            <a class="shipping-btn nav-link" id="shipping-{{id}}" data-shipping="{{productId}}"
               href="#shipping-tab-{{id}}">
                <?php _e( 'Shipping', 'dm' ) ?>
            </a>
            <a class="reviews-btn nav-link" id="reviews-{{id}}" data-url="{{feedbackUrl}}"
               href="#reviews-tab-{{id}}">
                <?php _e( 'Reviews', 'dm' ) ?>
            </a>
            <a class="analysis-btn nav-link" id="analysis-{{id}}" data-analysis="{{productId}}"
               data-sub_title="<?php _e( 'Orders', 'dm' ) ?>" data-time="<?php _e( 'Date', 'dm' ) ?>"
               href="#analysis-tab-{{id}}">
                <?php _e( 'Aliexpress', 'dm' ) ?>
            </a>
        </div>
        <div class="tab-content">
            <div class="tab-pane description-tab active show" id="description-tab-{{id}}" role="tabpanel">
                {{{product_content}}}
            </div>
            <div class="tab-pane shipping-list-tab" data-shipping="{{productId}}" id="shipping-tab-{{id}}" role="tabpanel">

                <?php
                echo $tmpl->select( [
                    'value'  => '',
                    'icon'   => true,
                    'values' => dm_list_countries(),
                    'id'     => 'ship_country_{{productId}}',
                    'label'  => __( 'Shipping to:', 'dm' )
                ] )
                ?>
                <div id="list-shipping-{{productId}}" class="py-2"></div>
            </div>
            <div class="tab-pane" id="reviews-tab-{{id}}" role="tabpanel"></div>
            <div class="tab-pane" id="analysis-tab-{{id}}" role="tabpanel">
                <div class="logistic-box"></div>
                <div class="auth"></div>
                <div id="chart-{{productId}}" class="my-4 mr-2"></div>
            </div>
        </div>
    </div>
</div>
<!-- description tabs end -->
<!-- action begin -->
<div class="col-lg-3 d-block d-sm-none">
    <div class="text-center row action-buttons d-flex justify-content-center">
        <button class="mr-3 btn btn-green ads-no js-import-product" {{#ifCond already "==" "1" }}disabled="disabled"{{/ifCond}}>
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
        <!--<button class="col ml-3 btn btn-green-transparent ads-no js-details" data-action="hide">
				<?php _e( 'Hide Details', 'dm' ) ?>
			</button>-->
        <a href="javascript:;" class="details_burger ads-no js-details" style="top: 4px;height: 42px;" data-action="hide"></a>
        <div class="report-this py-3 pl-0 text-left col-12">
            <a href="javascript:;" data-id="{{id}}" class="color-blue"><?php _e( 'Report this product', 'dm' ) ?></a>
        </div>
    </div>
</div>
<!-- action end -->