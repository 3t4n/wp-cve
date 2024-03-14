<?php

class HelcimLineItemService
{

    public function buildLineItemsFromCart(array $items): array
    {
        $helcimLineItems = [];
        foreach ($items as $item) {
            if (!isset($item['data']) || !$item['data'] instanceof WC_Product) {
                continue;
            }
            $product = $item['data'];
            $helcimLineItems[] = (new HelcimLineItem())
                ->setSku($product->get_sku('') ?: 'NoSKU')
                ->setDescription($product->get_name(''))
                ->setQuantity($item['quantity'] ?? 0)
                ->setPrice((float)$product->get_sale_price())
                ->setTotal($item['line_subtotal'] ?? 0);
        }
        return $helcimLineItems;
    }
}