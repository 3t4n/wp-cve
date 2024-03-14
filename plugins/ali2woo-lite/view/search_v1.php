<div class="a2wl-content">
    <div class="page-main">
        <div class="_a2wfo a2wl-info"><div>You are using AliNext (Lite version) Lite. If you want to unlock all features and get premium support, purchase the full version of the plugin.</div><a href="https://ali2woo.com/pricing/?utm_source=lite&utm_medium=lite&utm_campaign=alinext-lite" target="_blank" class="btn">GET FULL VERSOIN</a></div>
        <?php include_once A2WL()->plugin_path() . '/view/chrome_notify.php';?>
        

        <form class="search-panel" method="GET" id="a2wl-search-form">
            <input type="hidden" name="page" id="page" value="<?php echo esc_attr(((isset($_GET['page'])) ? $_GET['page'] : '')); ?>" />
            <input type="hidden" name="cur_page" id="cur_page" value="<?php echo esc_attr(((isset($_GET['cur_page'])) ? $_GET['cur_page'] : '')); ?>" />
            <input type="hidden" name="a2wl_sort" id="a2wl_sort" value="<?php echo $filter['sort']; ?>" />
            <input type="hidden" name="a2wl_search" id="a2wl_search" value="1" />
            <input type="hidden" id="a2wl_locale" value="<?php echo $locale; ?>" />
            <input type="hidden" id="a2wl_currency" value="<?php echo $currency; ?>" />
            <input type="hidden" id="a2wl_chrome_ext_import" value="<?php echo a2wl_check_defined('A2WL_CHROME_EXT_IMPORT'); ?>" />
            <input type="hidden" id="a2wl_chrome_url" value="<?php echo A2WL()->chrome_url; ?>" />

            <div class="search-panel-header">
                <h3 class="search-panel-title"><?php _e('Search for products', 'ali2woo');?></h3>
                <button class="btn btn-default to-right modal-search-open" type="button"><?php _e('Import product by URL or ID', 'ali2woo');?></button>
            </div>
            <div class="search-panel-body">
                <div class="search-panel-simple">
                    <div class="row">
                        <div class="col-lg-9 col-sm-9">
                            <div class="input-group">
                                <input class="form-control" type="text" name="a2wl_keywords" id="a2wl_keywords" placeholder="<?php _e('Enter Keywords', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['keywords']) ? $filter['keywords'] : ""); ?>">
                                <select id="a2wl_category" class="form-control" name="a2wl_category" aria-invalid="false">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php if (isset($filter['category']) && $filter['category'] == $cat['id']): ?>selected="selected"<?php endif;?>><?php if (intval($cat['level']) > 1): ?> - <?php endif;?><?php echo $cat['name']; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <div class="search-panel-buttons">
                                <button class="btn btn-info no-outline" id="a2wl-do-filter" type="button"><?php _ex('Search', 'Button', 'ali2woo');?></button>
                                <button class="btn btn-link no-outline" id="search-trigger" type="button"><?php _ex('Advance', 'Button', 'ali2woo');?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="search-panel-advanced" <?php if ($adv_search): ?>style="display: block;"<?php endif;?>>
                    <div class="search-panel-row">
                        <div class="search-panel-col">
                            <label><?php _e('Price', 'ali2woo');?></label>
                            <input type="text" class="form-control" name="a2wl_min_price" placeholder="<?php _e('Price from', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['min_price']) ? $filter['min_price'] : ""); ?>">
                            <input type="text" class="form-control" name="a2wl_max_price" placeholder="<?php _e('Price to', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['max_price']) ? $filter['max_price'] : ""); ?>">
                        </div>
                        <div class="search-panel-col">
                            <label><?php _e("Seller's Feedback score", 'ali2woo');?></label>
                            <input type="text" class="form-control" name="a2wl_min_feedback" placeholder="<?php _e('Score from 0', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['min_feedback']) ? $filter['min_feedback'] : ""); ?>">
                            <input type="text" class="form-control" name="a2wl_max_feedback" placeholder="<?php _e('Score to 400 000+', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['max_feedback']) ? $filter['max_feedback'] : ""); ?>">
                        </div>
                        <div class="search-panel-col">
                            <label><?php _e('Sold in 30 days', 'ali2woo');?></label>
                            <input type="text" class="form-control" name="a2wl_volume_from" placeholder="<?php _e('Orders count from', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['volume_from']) ? $filter['volume_from'] : ""); ?>">
                            <input type="text" class="form-control" name="a2wl_volume_to" placeholder="<?php _e('Orders count to', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['volume_to']) ? $filter['volume_to'] : ""); ?>">
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="modal-overlay modal-search">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><?php _e('Import product by URL or ID', 'ali2woo');?></h3>
                        <a class="modal-btn-close" href="#"></a>
                    </div>
                    <div class="modal-body">
                        <label><?php _e('Product URL', 'ali2woo');?></label>
                        <input class="form-control" type="text" id="url_value">
                        <div class="separator"><?php _e('or', 'ali2woo');?></div>
                        <label><?php _e('Product ID', 'ali2woo');?></label>
                        <input class="form-control" type="text" id="id_value">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default modal-close" type="button"><?php _e('Cancel');?></button>
                        <button id="import-by-id-url-btn" class="btn btn-success" type="button">
                            <div class="btn-icon-wrap cssload-container"><div class="cssload-speeding-wheel"></div></div>
                            <?php _e('Import', 'ali2woo');?>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div>
            <div class="import-all-panel">
                <button type="button" class="btn btn-success no-outline btn-icon-left import_all"><div class="btn-loader-wrap"><div class="e2w-loader"></div></div><span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span><?php _e('Add all to import list', 'ali2woo');?></button>
            </div>
            <div class="sort-panel">
                <label for="a2wl-sort-selector"><?php _e('Sort by:', 'ali2woo');?></label>
                <select class="form-control" id="a2wl-sort-selector">
                    <option value="bestMatch" <?php if ($filter['sort'] == 'bestMatch'): ?>selected="selected"<?php endif;?>><?php _ex('Best Match', 'sort by', 'ali2woo');?></option>
                    <option value="orignalPriceUp" <?php if ($filter['sort'] == 'orignalPriceUp'): ?>selected="selected"<?php endif;?>><?php _ex('Lowest price', 'sort by', 'ali2woo');?></option>
                    <option value="orignalPriceDown" <?php if ($filter['sort'] == 'orignalPriceDown'): ?>selected="selected"<?php endif;?>><?php _ex('Highest price', 'sort by', 'ali2woo');?></option>
                    <!--
                    <option value="sellerRateDown" <?php if ($filter['sort'] == 'sellerRateDown'): ?>selected="selected"<?php endif;?>><?php _ex("Seller's feedback score", 'sort by', 'ali2woo');?></option>
                    <option value="commissionRateUp" <?php if ($filter['sort'] == 'commissionRateUp'): ?>selected="selected"<?php endif;?>><?php _ex('Lowest commission rate', 'sort by', 'ali2woo');?></option>
                    <option value="commissionRateDown" <?php if ($filter['sort'] == 'commissionRateDown'): ?>selected="selected"<?php endif;?>><?php _ex('Highest commission rate', 'sort by', 'ali2woo');?></option>
                    -->
                    <option value="volumeDown" <?php if ($filter['sort'] == 'volumeDown'): ?>selected="selected"<?php endif;?>><?php _ex('Orders count', 'sort by', 'ali2woo');?></option>
                    <option value="validTimeUp" <?php if ($filter['sort'] == 'validTimeUp'): ?>selected="selected"<?php endif;?>><?php _ex('Lowest valid time', 'sort by', 'ali2woo');?></option>
                    <option value="validTimeDown" <?php if ($filter['sort'] == 'validTimeDown'): ?>selected="selected"<?php endif;?>><?php _ex('Highest valid time', 'sort by', 'ali2woo');?></option>
                </select>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="search-result">
            <div class="messages"><?php settings_errors('a2wl_products_list');?></div>
            <?php $localizator = AliNext_Lite\AliexpressLocalizator::getInstance();?>
            <?php $out_curr = $localizator->getLocaleCurr($localizator->isCustomCurrency() ? 'USD' : $localizator->currency);?>
            <?php if ($load_products_result['state'] != 'error'): ?>
                <?php if (!$load_products_result['total']): ?>
                    <p>products not found</p>
                <?php else: ?>
                    <?php $row_ind = 0;?>
                    <?php foreach ($load_products_result['products'] as $product): ?>
                        <?php
if ($row_ind == 0) {
    echo '<div class="search-result__row">';
}
?>
                        <article class="product-card<?php if ($product['post_id'] || $product['import_id']): ?> product-card--added<?php endif;?>" data-id="<?php echo $product['id'] ?>">
                            <div class="product-card__img"><a href="<?php echo $product['affiliate_url'] ?>" target="_blank"><img src="<?php echo A2WL()->plugin_url() . '/assets/img/blank_image.png'; ?>" class="lazy" data-original="<?php echo !empty($product['thumb']) ? $product['thumb'] : ""; ?>" alt="#"></a>
                                <div class="product-card__marked-corner">
                                    <svg class="product-card__marked-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-selected"></use></svg>
                                </div>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__meta">
                                    <div class="product-card__title"><a href="<?php echo $product['affiliate_url'] ?>" target="_blank"><?php echo $product['title']; ?></a></div>
                                </div>
                                <div class="product-card__price-wrapper">
                                    <h4><span class="product-card__price"><?php echo $out_curr; ?><?php echo $product['local_price']; ?></span><span class="product-card__discount"><?php echo $out_curr; ?><?php echo $product['local_regular_price']; ?></span></h4>
                                </div>
                                
                                <div class="product-card__meta-wrapper">
                                    <div class="product-card__rating">
                                        <?php for ($i = 0; $i < round($product['evaluateScore']); $i++): ?>
                                            <svg class="icon-star"><use xlink:href="#icon-star"></use></svg>
                                        <?php endfor;?>
                                        <?php for ($i = round($product['evaluateScore']); $i < 5; $i++): ?>
                                            <svg class="icon-empty-star"><use xlink:href="#icon-star"></use></svg>
                                        <?php endfor;?>
                                    </div>
                                    <div class="product-card__supplier">
                                        <div class="product-card__orderscount"><?php echo $product['volume']; ?> <span>Orders</span></div><img class="supplier-icon" src="<?php echo A2WL()->plugin_url() . '/assets/img/icons/supplier_ali_2x.png'; ?>" width="16" height="16">
                                    </div>
                                </div>
                                <div class="product-card__actions">
                                    <button class="btn <?php echo ($product['post_id'] || $product['import_id']) ? 'btn-default' : 'btn-success'; ?> no-outline btn-icon-left"><span class="title"><?php if ($product['post_id'] || $product['import_id']): ?><?php _e('Remove from import list', 'ali2woo');?><?php else: ?><?php _e('Add to import list', 'ali2woo');?><?php endif;?></span>
                                        <div class="btn-loader-wrap"><div class="a2wl-loader"></div></div>
                                        <span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span>
                                        <span class="btn-icon-wrap remove"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-cross"></use></svg></span>
                                    </button>
                                </div>
                            </div>
                        </article>
                        <?php $row_ind++;?>
                        <?php
if ($row_ind == 4) {
    echo '</div>';
    $row_ind = 0;
}
?>
                    <?php endforeach;?>
                    <?php
if (0 < $row_ind && $row_ind < 4) {
    echo '</div>';
}
?>
                    <?php if (isset($filter['country'])): ?>
                        <script>
                            (function ($) {
                                $(function () {
                                    chech_products_view();
                                    $(window).scroll(function () {
                                        chech_products_view();
                                    });
                                });
                            })(jQuery);
                        </script>
                    <?php endif;?>
                <?php endif;?>
            <?php endif;?>

        </div>
        <?php if ($load_products_result['state'] != 'error' && $load_products_result['total_pages'] > 0): ?>
            <div id="a2wl-search-pagination" class="pagination">
                <div class="pagination__wrapper">
                    <ul class="pagination__list">
                        <li <?php if (1 == $load_products_result['page']): ?>class="disabled"<?php endif;?>><a href="#" rel="<?php echo $load_products_result['page'] - 1; ?>">«</a></li>
                        <?php foreach ($load_products_result['pages_list'] as $p): ?>
                            <?php if ($p): ?>
                                <?php if ($p == $load_products_result['page']): ?>
                                    <li class="active"><span><?php echo $p; ?></span></li>
                                <?php else: ?>
                                    <li><a href="#" rel="<?php echo $p; ?>"><?php echo $p; ?></a></li>
                                <?php endif;?>
                            <?php else: ?>
                                <li class="disabled"><span>...</span></li>
                            <?php endif;?>
                        <?php endforeach;?>
                        <li <?php if ($load_products_result['total_pages'] == $load_products_result['page']): ?>class="disabled"<?php endif;?>><a href="#" rel="<?php echo $load_products_result['page'] + 1; ?>">»</a></li>
                    </ul>
                </div>
            </div>
        <?php endif;?>

        <?php include_once 'includes/confirm_modal.php';?>
        <?php include_once 'includes/shipping_modal.php';?>
    </div>
</div>
