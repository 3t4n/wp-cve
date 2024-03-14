<?php
use AliNext_Lite\Aliexpress;
use AliNext_Lite\Attachment;
use AliNext_Lite\Helper;
use AliNext_Lite\ImportAjaxController;
use AliNext_Lite\Override;
use AliNext_Lite\ProductChange;
use AliNext_Lite\ProductImport;
use AliNext_Lite\Review;
use AliNext_Lite\Woocommerce;
use function DI\create;
use function DI\get;

return [
    /* models */
    'AliNext_Lite\Attachment' => create(Attachment::class),
    'AliNext_Lite\Helper' => create(Helper::class),
    'AliNext_Lite\ProductChange' => create(ProductChange::class),
    'AliNext_Lite\ProductImport' => create(ProductImport::class),
    'AliNext_Lite\Woocommerce' => create(Woocommerce::class)
        ->constructor(
            get(Attachment::class), get(Helper::class), get(ProductChange::class)
        ),
    'AliNext_Lite\Review' => create(Review::class),
    'AliNext_Lite\Override' => create(Override::class),
    'AliNext_Lite\Aliexpress' => create(Aliexpress::class),

    /* controllers */
    'AliNext_Lite\ImportAjaxController' => create(ImportAjaxController::class)
        ->constructor(
            get(ProductImport::class), get(Woocommerce::class), get(Review::class),
            get(Override::class), get(Aliexpress::class)
        ),
];
