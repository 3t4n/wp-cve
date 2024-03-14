SKU Error Fixer for WooCommerce Plugin
=============================================

This plugin fixes a unique SKU error of WooCommerce products.


## What is the old variations?

These are former variations of variable products when it type have been changed to Simple or another type of product not variable. WooCommerce does not remove these variations to the case you decide to change the product type on variable again. For this case variables remain intact with all fields filled in, including the SKU field.

## What's the problem of old variations?

#### Clogging the database of unnecessary data

Old variation of products is invisible. You can change the product type with variable to another, and after some time to remove this product and forget about it, but these variations will not be removed. They are stored in the database and it can take a lot of space.

#### Unique SKU problem

A known problem of the uniqueness of the SKU number of the product. WooCommers allows you to assign only unique SKU for product. If you used any SKU for the product variation, and then changed the product type to another, you will not be able to use the same SKU for a different product. You will receive an error "Product SKU must be unique". WooCommerce SKU Error Fixer plugin eliminates this problem.

## How do I use this plugin?

After installing the plugin you will need to go to the plugin settings page Woocommerce > SKU Error Fixer, where you can to scan your site for presence of any old variations, clean them SKU fields or remove them completely. You can also setup automatic checking and fixing not unique SKU problem when you edit a product.