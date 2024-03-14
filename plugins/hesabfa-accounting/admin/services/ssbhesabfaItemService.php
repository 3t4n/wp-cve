<?php

class ssbhesabfaItemService
{
    public static function mapProduct($product, $id, $new = true) {
        $wpFaService = new HesabfaWpFaService();

        $categories = $product->get_category_ids();
        $code = $new ? null : $wpFaService->getProductCodeByWpId($id) ;
        $price = $product->get_regular_price() ? $product->get_regular_price() : $product->get_price();

        $hesabfaItem = array(
            'Code' => $code,
            'Name' => Ssbhesabfa_Validation::itemNameValidation($product->get_title()),
            'PurchasesTitle' => Ssbhesabfa_Validation::itemNameValidation($product->get_title()),
            'SalesTitle' => Ssbhesabfa_Validation::itemNameValidation($product->get_title()),
            'ItemType' => $product->is_virtual() == 1 ? 1 : 0,
            'Tag' => json_encode(array('id_product' => $id, 'id_attribute' => 0))
        );

        if(!$code || get_option("ssbhesabfa_do_not_update_product_price_in_hesabfa", "no") === "no")
            $hesabfaItem["SellPrice"] = self::getPriceInHesabfaDefaultCurrency($price);
        if(get_option("ssbhesabfa_do_not_update_product_barcode_in_hesabfa", "no") === "no")
            $hesabfaItem["Barcode"] = Ssbhesabfa_Validation::itemBarcodeValidation($product->get_sku());
		if(get_option("ssbhesabfa_do_not_update_product_category_in_hesabfa", "no") === "no")
            if($categories) $hesabfaItem["NodeFamily"] = self::getCategoryPath($categories[0]);
        if(get_option("ssbhesabfa_do_not_update_product_product_code_in_hesabfa", "no") === "no")
            $hesabfaItem["ProductCode"] = $id;

		return $hesabfaItem;
    }
//===========================================================================================================
    public static function mapProductVariation($product, $variation, $id_product, $new = true) {
        $wpFaService = new HesabfaWpFaService();

        $id_attribute = $variation->get_id();
        $categories = $product->get_category_ids();
        $code = $new ? null : $wpFaService->getProductCodeByWpId($id_product, $id_attribute);

        $productName = $product->get_title();
        $variationName = $variation->get_attribute_summary();
        $fullName = Ssbhesabfa_Validation::itemNameValidation($productName . ' - ' . $variationName);
        $price = $variation->get_regular_price() ? $variation->get_regular_price() : $variation->get_price();

        $hesabfaItem = array(
            'Code' => $code,
            'Name' => $fullName,
            'PurchasesTitle' => $fullName,
            'SalesTitle' => $fullName,
            'ItemType' => $variation->is_virtual() == 1 ? 1 : 0,
            'Tag' => json_encode(array(
                'id_product' => $id_product,
                'id_attribute' => $id_attribute
            )),
        );

        if(!$code || get_option("ssbhesabfa_do_not_update_product_price_in_hesabfa", "no") === "no")    $hesabfaItem["SellPrice"] = self::getPriceInHesabfaDefaultCurrency($price);
        if(get_option("ssbhesabfa_do_not_update_product_barcode_in_hesabfa", "no") === "no")            $hesabfaItem["Barcode"] = Ssbhesabfa_Validation::itemBarcodeValidation($variation->get_sku());
		if(get_option("ssbhesabfa_do_not_update_product_category_in_hesabfa", "no") === "no")           $hesabfaItem["NodeFamily"] = self::getCategoryPath($categories[0]);
        if(get_option("ssbhesabfa_do_not_update_product_product_code_in_hesabfa", "no") === "no")       $hesabfaItem["ProductCode"] = $id_attribute;

        return $hesabfaItem;
    }
//===========================================================================================================
    public static function getPriceInHesabfaDefaultCurrency($price)
    {
        if (!isset($price)) return false;

        $woocommerce_currency = get_woocommerce_currency();
        $hesabfa_currency = get_option('ssbhesabfa_hesabfa_default_currency');

        if (!is_numeric($price)) $price = intval($price);

        if ($hesabfa_currency == 'IRR' && $woocommerce_currency == 'IRT') $price *= 10;

        if ($hesabfa_currency == 'IRT' && $woocommerce_currency == 'IRR') $price /= 10;

        return $price;
    }
//===========================================================================================================
    public static function getCategoryPath($id_category)
    {
        if (!isset($id_category)) return '';

        $path = get_term_parents_list($id_category, 'product_cat', array(
            'format' => 'name',
            'separator' => ':',
            'link' => false,
            'inclusive' => true,
        ));

        return substr('products: ' . $path, 0, -1);
    }
//===========================================================================================================
}