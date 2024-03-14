<?php namespace Premmerce\WoocommerceMulticurrency\Compatibility;

class WPMLCompatibility
{
    /**
     * WPML constructor.
     */
    public function __construct()
    {
        add_action('wcml_update_extra_fields', array($this, 'syncTranslatedProductCurrencyFields'), 10, 2);

        add_action('wcml_before_sync_product_data', array($this, 'syncVariationPricesInProcuctCurrencies'));
    }

    /**
     * Synchronize currency fields of original product and all it's variations with translated product
     *
     * @param $originalProductId
     * @param $duplicatedProductId
     */
    public function syncTranslatedProductCurrencyFields($originalProductId, $duplicatedProductId)
    {
        $originalProduct = wc_get_product($originalProductId);
        $duplicatedProduct = wc_get_product($duplicatedProductId);

        $this->duplicateFields($originalProduct, $duplicatedProduct);
    }

    /**
     * Copy currency fields from original variation to translated variation
     */
    public function syncVariationPricesInProcuctCurrencies()
    {
        global $iclTranslationManagement;

        $fields = array('_product_currency_regular_price', '_product_currency_sale_price');

        foreach ($fields as $field) {
            $iclTranslationManagement->settings['custom_fields_translation'][$field] = 1;
        }
    }

    /**
     * Copy currency fields from original product to duplicated
     *
     * @param \WC_Product $originalProduct
     * @param \WC_Product $duplicatedProduct
     */
    private function duplicateFields(\WC_Product $originalProduct, \WC_Product $duplicatedProduct)
    {
        $fieldsToCopy = array(
            '_product_currency',
            '_product_currency_regular_price',
            '_product_currency_sale_price'
        );


        foreach ($fieldsToCopy as $field) {
            if ($originalProduct->meta_exists($field)) {
                $duplicatedProduct->add_meta_data($field, $originalProduct->get_meta($field), true);
            }
        }

        $duplicatedProduct->save();
    }
}
