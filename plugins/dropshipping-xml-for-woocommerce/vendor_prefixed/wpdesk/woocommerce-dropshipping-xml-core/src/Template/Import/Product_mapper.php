<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var string $title
 * @var bool $edit
 * @var string $mode
 * @var string $previous_step
 * @var string $format
 * @var int $items
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable $sidebar
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * @var string $mapper_img_assets
 * @var WPDesk\View\Renderer\Renderer $renderer
 */
$renderer->output_render('Header', ['title' => $title]);
?>

<?php 
if (!$edit) {
    ?>
	<h2>
	<?php 
    // TRANSLATORS: %s: page step.
    echo \esc_html(\sprintf(\__('Step %s', 'dropshipping-xml-for-woocommerce'), '3/4'));
    ?>
		</h2>
<?php 
}
?>
<p><?php 
echo \wp_kses_post(\__('Map the tags of the supplier\'s file with the WooCommerce product fields. To do this you can drag&drop the name of the tag from the preview into the selected product field. <a href="https://www.wpdesk.pl/wp-content/uploads/2020/09/dropshipping_draganddrop.gif" class="docs-url" target="_blank">See how</a>.', 'dropshipping-xml-for-woocommerce'));
?></p>
<p style="font-weight: bold;"><?php 
echo \wp_kses_post(\__('Read more in the <a href="https://wpde.sk/dropshipping-import-3" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce'));
?></p>
<hr>
<?php 
$form->form_start(['method' => 'POST', 'template' => 'form-start-no-table']);
?>

<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content" style="position: relative;">
			<div id="titlediv">
				<div id="titlewrap">
					<?php 
$form->show_field('title');
?>
				</div>
				<div id="postdivrich" data-id="content" class="postarea wp-editor-expand">
					<?php 
$form->show_field('content');
?>
				</div>
			</div>
		</div>

		<div id="postbox-container-1" class="postbox-container">
			<?php 
$sidebar->show();
?>
		</div>
		<div id="postbox-container-2" class="postbox-container">
			<div>
				<div id="woocommerce-product-data" class="postbox ">
					<h2 class="hndle ui-sortable-handle" >
						<span><?php 
echo \esc_html(\__('Product data', 'dropshipping-xml-for-woocommerce'));
?></span>
					</h2>
					<div class="inside">
						<div class="panel-wrap product_data">
							<ul class="product_data_tabs wc-tabs">
								<li class="general_options general_tab hide_if_grouped active">
									<a href="#general_product_data"><span><?php 
echo \esc_html(\__('General', 'dropshipping-xml-for-woocommerce'));
?></span></a>
								</li>
								<li class="inventory_options inventory_tab show_if_simple show_if_variable show_if_grouped show_if_external" style="display: block;">
									<a href="#inventory_product_data"><span><?php 
echo \esc_html(\__('Inventory', 'dropshipping-xml-for-woocommerce'));
?></span></a>
								</li>
								<li class="shipping_options shipping_tab hide_if_virtual hide_if_grouped hide_if_external">
									<a href="#shipping_product_data"><span><?php 
echo \esc_html(\__('Shipping', 'dropshipping-xml-for-woocommerce'));
?></span></a>
								</li>
								<li class="linked_product_options linked_product_tab " style="display: none">
									<a href="#linked_product_data"><span><?php 
echo \esc_html(\__('Related products', 'dropshipping-xml-for-woocommerce'));
?></span></a>
								</li>
								<li class="attribute_options attribute_tab">
									<a href="#product_attributes"><span><?php 
echo \esc_html(\__('Attributes', 'dropshipping-xml-for-woocommerce'));
?></span></a>
								</li>
								<li class="variations_options variations_tab variations_tab show_if_variable" style="display: none;">
									<a href="#variable_product_options"><span><?php 
echo \esc_html(\__('Variants', 'dropshipping-xml-for-woocommerce'));
?></span></a>
								</li>
							</ul>

							<div id="general_product_data" class="panel woocommerce_options_panel" style="display: block;">
							<div class="options_group show_if_external">
								<p class="form-field external_url">
									<?php 
$form->show_label('external_url', ['template' => 'label']);
?>
									<?php 
$form->show_field('external_url');
?>
									<span class="description"><?php 
$form->show_description('external_url');
?></span>
								</p>

								<p class="form-field button_text">
									<?php 
$form->show_label('button_text', ['template' => 'label']);
?>
									<?php 
$form->show_field('button_text');
?>
									<span class="description"><?php 
$form->show_description('button_text');
?></span>
								</p>
								</div>
								<div class="options_group pricing show_if_simple show_if_variable show_if_external hidden" style="display: block;">
									<p class="form-field _price_field">
										<?php 
$form->show_label('price', ['template' => 'label']);
?>
										<?php 
$form->show_field('price');
?>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('If you want to have prices in WooCommerce higher that in the file, you can modify the prices on the fly by adding a certain amount or percentage.', 'dropshipping-xml-for-woocommerce')));
?>
									</p>

									<p class="form-field _sale_price_field ">
										<?php 
$form->show_label('sale_price', ['template' => 'label']);
?>
										<?php 
$form->show_field('sale_price');
?>
									</p>
								</div>

								<div class="options_group show_if_simple show_if_external show_if_variable" style="display: block;">
									<p class="form-field _tax_status_field">
										<?php 
$form->show_label('tax_status', ['template' => 'label']);
?>
										<?php 
$form->show_field('tax_status');
?>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('Define whether or not the entire product is taxable, or just the cost of shipping it.', 'woocommerce')));
// phpcs:ignore
?>
									</p>

									<div id="woocommerce-product-tax-class">
										<b><p class="form-field-custom"><?php 
echo \esc_html(\__('Tax Class', 'dropshipping-xml-for-woocommerce'));
?></p></b>
										<div class="inside" style="padding: 0 10px">
											<p class="form-field-custom">
												<label>
													<?php 
$form->show_field('product_tax_class_single');
$form->show_label('product_tax_class_single');
?>
												</label>
											</p>
											<div class="data-wrapper no-label" data-id="product_categories_single_id">
												<p class="form-field-custom">
													<label>
														<?php 
$form->show_label('tax_class');
?> <?php 
$form->show_field('tax_class');
?>
													</label>
													<?php 
echo \wp_kses_post(\wc_help_tip(\__('Choose a tax class for this product. Tax classes are used to apply different tax rates specific to certain types of product.', 'woocommerce')));
// phpcs:ignore
?>
												</p>
											</div>
											<p class="form-field-custom">
												<label>
													<?php 
$form->show_field('product_tax_class_mapped');
$form->show_label('product_tax_class_mapped');
?>
												</label>
											</p>
											<div class="data-wrapper no-label" data-id="product_tax_class_multi_map_id">
												<p class="form-field-custom">
													<label>
														<?php 
$form->show_label('tax_class_mapper_field');
?>
														<?php 
echo \wp_kses_post(\wc_help_tip(\__('If you want to add multiple tax classes to mapper just separate dragged fields by comma.', 'dropshipping-xml-for-woocommerce')));
?>
														<?php 
$form->show_field('tax_class_mapper_field');
?>
													</label>
												</p>

												<p class="form-field-custom">
													<label style="font-weight:bold;">
														<?php 
$form->show_label('tax_class_map');
?>
													</label>
												</p>
												<div class="tax_class_field" id="tax-class-wrapper">
													<?php 
$form->show_field('tax_class_map');
?>
												</div>
												<div class="toolbar">
													<a href="#" id="add_tax_class"><?php 
echo \esc_html(\__('Add +', 'dropshipping-xml-for-woocommerce'));
?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="inventory_product_data" class="panel woocommerce_options_panel hidden" style="display: none;">

								<div class="options_group">
									<p class="form-field _sku_field ">
										<?php 
$form->show_label('SKU', ['template' => 'label']);
?>
										<?php 
$form->show_field('SKU');
?>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'woocommerce')));
// phpcs:ignore
?>
									</p>
									<p class="form-field _manage_stock_field show_if_simple show_if_variable" style="display: block;">
										<?php 
$form->show_label('_manage_stock', ['template' => 'label']);
?>
										<?php 
$form->show_field('_manage_stock');
?>
										<?php 
echo \wp_kses_post($form->show_description('_manage_stock', ['template' => 'description']));
?>
									</p>
									<div class="stock_fields show_if_simple show_if_variable" style="display: none;">
										<p class="form-field _stock_field ">
											<?php 
$form->show_label('_stock', ['template' => 'label']);
?>
											<?php 
$form->show_field('_stock');
?>
											<?php 
echo \wp_kses_post(\wc_help_tip(\__('Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'woocommerce')));
// phpcs:ignore
?>
										</p>
										<p class=" form-field _backorders_field">
											<?php 
$form->show_label('_backorders', ['template' => 'label']);
?>
											<?php 
$form->show_field('_backorders');
?>
											<?php 
echo \wp_kses_post(\wc_help_tip(\__('If managing stock, this controls whether or not backorders are allowed. If enabled, stock quantity can go below 0.', 'woocommerce')));
// phpcs:ignore
?>
										</p>
										<p class="form-field _low_stock_amount_field ">
											<?php 
$form->show_label('_low_stock_amount', ['template' => 'label']);
?>
											<?php 
$form->show_field('_low_stock_amount');
?>
											<?php 
echo \wp_kses_post(\wc_help_tip(\__('When product stock reaches this amount you will be notified via email.', 'woocommerce')));
// phpcs:ignore
?>
										</p>
									</div>
									<p class="stock_status_field hide_if_variable hide_if_external hide_if_grouped form-field _stock_status_field">
										<?php 
$form->show_label('_stock_status', ['template' => 'label']);
?>
										<?php 
$form->show_field('_stock_status');
?>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'woocommerce')));
// phpcs:ignore
?>
									</p>
								</div>

								<div class="options_group show_if_simple show_if_variable" style="display: block;">
									<p class="form-field _sold_individually_field show_if_simple show_if_variable" style="display: block;">
										<?php 
$form->show_label('_sold_individually', ['template' => 'label']);
?>
										<?php 
$form->show_field('_sold_individually');
?>
										<?php 
echo \wp_kses_post($form->show_description('_sold_individually'));
?>
									</p>
								</div>

							</div>
							<div id="shipping_product_data" class="panel woocommerce_options_panel hidden" style="display: none;">
								<div class="options_group">
									<p class="form-field _weight_field ">
										<?php 
$form->show_label('_weight', ['template' => 'label']);
?>
										<?php 
$form->show_field('_weight');
?>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('Weight in decimal form', 'woocommerce')));
// phpcs:ignore
?>
									</p>
									<p class="form-field dimensions_field">
										<?php 
$form->show_label('product_length', ['template' => 'label']);
?>
										<span class="wrap">
											<?php 
$form->show_field('product_length');
?>
											<?php 
$form->show_field('product_width');
?>
											<?php 
$form->show_field('product_height');
?>
										</span>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('LxWxH in decimal form', 'woocommerce')));
// phpcs:ignore
?>

									</p>
								</div>

								<div class="options_group">
									<p class="form-field shipping_class_field">
										<?php 
$form->show_label('product_shipping_class', ['template' => 'label']);
?>
										<?php 
$form->show_field('product_shipping_class');
?>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('Shipping classes are used by certain shipping methods to group similar products.', 'woocommerce')));
// phpcs:ignore
?>

									</p>
								</div>
							</div>
							<div id="product_attributes" class="panel woocommerce_options_panel hidden" style="display: none;">
								<div class="options_group">
									<p class="form-field two_cols_field" id="attributes-wrapper" style="position: relative;">
										<?php 
$form->show_label('attribute', ['template' => 'label']);
?>
										<?php 
$form->show_field('attribute');
?>
										<span style="position:absolute; right:30px; top:8px">
											<?php 
echo \wp_kses_post(\wc_help_tip(\__('Add attributes to the products by mapping their names and / or values from the file.', 'dropshipping-xml-for-woocommerce')));
?>
										</span>
									</p>
									<div class="toolbar" style="display: block; width:100%; height:40px">
										<a href="#" style="margin-right:25px;" id="add_attribute"><?php 
echo \esc_html(\__('Add +', 'dropshipping-xml-for-woocommerce'));
?></a>
									</div>
								</div>
								<div class="options_group">
									<p class="form-field">
										<?php 
$form->show_label('attribute_as_taxonomy', ['template' => 'label']);
?>
										<?php 
$form->show_field('attribute_as_taxonomy');
?>
										<?php 
echo \wp_kses_post(\wc_help_tip(\__('Attributes added as taxonomy are available globally to the entire site.', 'dropshipping-xml-for-woocommerce')));
?>
									</p>
								</div>

							</div>
							<div id="variable_product_options" class="panel wc-metaboxes-wrapper hidden" style="display: block;">
								<div class="block" style="padding-left:15px; padding-right:15px">
									<h3>
										<?php 
/* TRANSLATORS: %s: url to docs */
echo \wp_kses_post(\__('Read how to import variable products in <a href="https://wpde.sk/dropshipping-variants" class="docs-url" target="_blank" style="text-decoration:none">plugins docs →</a>', 'dropshipping-xml-for-woocommerce'));
?>
									</h3>
								</div>
								<div id="variable_product_options_container">
									<div class="variable-row mapper-selector-container">
										<div>
											<label>
												<?php 
$form->show_field('variation_type_title');
$form->show_label('variation_type_title');
?>
												<?php 
echo \wp_kses_post(\wc_help_tip(\__('Select this option if each product variant has the same name in the product feed.', 'dropshipping-xml-for-woocommerce')));
?>

											</label>
										</div>
										<div class="variable-row-options" style="margin-left:20px">
											<div>
												<label>
													<?php 
$form->show_field('variation_title_parent_exists');
$form->show_label('variation_title_parent_exists');
?>
													<?php 
echo \wp_kses_post(\wc_help_tip(\__('There is an entry in the product feed containing the parent product.', 'dropshipping-xml-for-woocommerce')));
?>
												</label>
											</div>
											<div class="image">
												<img src="<?php 
echo \esc_url($mapper_img_assets) . 'title_mapper.png';
?>" alt="">
											</div>
											<div class="image_parent">
												<img src="<?php 
echo \esc_url($mapper_img_assets) . 'title_mapper_parent.png';
?>" alt="">
											</div>
										</div>
									</div>
									<div class="variable-row mapper-selector-container">
										<div>
											<label>
												<?php 
$form->show_field('variation_type_sku');
$form->show_label('variation_type_sku');
?>
												<?php 
echo \wp_kses_post(\wc_help_tip(\__('Select this option if each product variant has the same SKU in the product feed.', 'dropshipping-xml-for-woocommerce')));
?>
											</label>
										</div>
										<div class="variable-row-options" style="margin-left:20px">
											<div>
												<?php 
$form->show_field('variation_sku_parent_xpath');
?>
											</div>
											<div class="image">
												<img src="<?php 
echo \esc_url($mapper_img_assets) . 'sku_mapper.png';
?>" alt="">
											</div>
										</div>
									</div>
									<div class="variable-row mapper-selector-container">
										<div>
											<label>
												<?php 
$form->show_field('variation_type_custom');
$form->show_label('variation_type_custom');
?>
												<?php 
echo \wp_kses_post(\wc_help_tip(\__('Select this option if each product variation has the same ID in the product feed but different xpath to ID.', 'dropshipping-xml-for-woocommerce')));
?>
											</label>
										</div>
										<div class="variable-row-options" style="margin-left:20px">
											<div style="display:flex; flex-wrap: nowrap; justify-content: space-between">
												<div class="width-50" style="padding-right:5px">
													<?php 
$form->show_field('variation_custom_xpath');
?>
												</div>
												<div class="width-50" style="padding-left:5px">
													<?php 
$form->show_field('variation_custom_parent_xpath');
?>
												</div>
											</div>
											<div class="image">
												<img src="<?php 
echo \esc_url($mapper_img_assets) . 'custom_id_mapper.png';
?>" alt="">
											</div>
										</div>
									</div>
									<div class="variable-row mapper-selector-container">
										<div>
											<label>
												<?php 
$form->show_field('variation_type_group');
$form->show_label('variation_type_group');
?>
												<?php 
echo \wp_kses_post(\wc_help_tip(\__('Select this option if the product feed contains a field that identifies variants.', 'dropshipping-xml-for-woocommerce')));
?>
											</label>
										</div>
										<div class="variable-row-options" style="margin-left:20px">
											<div>
												<?php 
$form->show_field('variation_group_xpath');
?>
											</div>
											<div>
												<label>
													<?php 
$form->show_field('variation_group_parent_exists');
$form->show_label('variation_group_parent_exists');
?>
													<?php 
echo \wp_kses_post(\wc_help_tip(\__('There is an entry in the product feed containing the parent product.', 'dropshipping-xml-for-woocommerce')));
?>
												</label>
											</div>
											<div class="image">
												<img src="<?php 
echo \esc_url($mapper_img_assets) . 'group_mapper.png';
?>" alt="">
											</div>
											<div class="image_parent">
												<img src="<?php 
echo \esc_url($mapper_img_assets) . 'group_mapper_parent.png';
?>" alt="">
											</div>
										</div>
									</div>

									<div class="variable-row mapper-selector-container" style="<?php 
echo 'xml' === $format ? '' : 'display:none!important';
?>" >
										<div>
											<label>
												<?php 
$form->show_field('variation_type_embedded');
$form->show_label('variation_type_embedded');
?>
												<?php 
echo \wp_kses_post(\wc_help_tip(\__('Select this option if the product has embedded variants.', 'dropshipping-xml-for-woocommerce')));
?>
											</label>
										</div>
										<div class="variable-row-options" style="margin-left:20px">
											<?php 
$form->show_field('variation_embedded');
?>
										</div>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>

				<div id="woocommerce-product-price-conditions" class="postbox group-switch">
					<h2 class="group-switcher"><span><?php 
echo \esc_html(\__('Price modifier', 'dropshipping-xml-for-woocommerce'));
?></span>  <span class="group-arrow dashicons dashicons-arrow-down"></span></h2>
					<div class="inside" style="padding: 0 10px">
						<p style="font-weight: bold;"><?php 
echo \wp_kses_post(\__('Read more in the <a href="https://wpde.sk/dropshipping-conditional-logic" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce'));
?></p>
						<?php 
$form->show_field('price_mod_conditions');
?>
					</div>
				</div>

				<div class="postbox group-switch">
					<h2 class="group-switcher"><span><?php 
echo \esc_html(\__('Short product description', 'dropshipping-xml-for-woocommerce'));
?></span> <span class="group-arrow dashicons dashicons-arrow-down"></span></h2>
					<div class="inside">
						<div id="postexcerpt" data-id="excerpt">
							<?php 
$form->show_field('excerpt');
?>
						</div>
					</div>
				</div>

				<div id="woocommerce-product-images" class="postbox group-switch">
					<h2 class="group-switcher"><span><?php 
echo \esc_html(\__('Images', 'dropshipping-xml-for-woocommerce'));
?></span>  <span class="group-arrow dashicons dashicons-arrow-down"></span></h2>
					<div class="inside" style="padding: 10px">
						<p><?php 
echo \wp_kses_post(\__('Image URLs detected below will be downloaded to the WordPress library and product\'s gallery. <b>Read more in the</b> <a href="https://wpde.sk/dropshipping-images-mapping" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce'));
?></p>
						<p class="form-field">
							<label>
								<?php 
$form->show_label('images_separator');
$form->show_field('images_separator');
?>
							</label>
							<?php 
echo \wp_kses_post(\wc_help_tip(\__('Type in a separator character which will be used in the field below to separate image URLs.', 'dropshipping-xml-for-woocommerce')));
?>
						</p>
						<p class="form-field"><?php 
$form->show_field('product-images');
?></p>
						<p class="form-field">
							<label>
								<?php 
$form->show_field('images_scan');
?>
								<?php 
$form->show_label('images_scan');
?>
							</label>
							<?php 
echo \wp_kses_post(\wc_help_tip(\__('Enable this option to automatically convert the HTML tags () to URLs, if they appear.', 'dropshipping-xml-for-woocommerce')));
?>
						</p>
						<p class="form-field">
							<label>
								<?php 
$form->show_field('images_featured_not_in_gallery');
?>
								<?php 
$form->show_label('images_featured_not_in_gallery');
?>
							</label>
							<?php 
echo \wp_kses_post(\wc_help_tip(\__('Enabling this option will disable adding first image to the Product Gallery. First image will only be set as Product Image and won\'t be added again to the gallery.', 'dropshipping-xml-for-woocommerce')));
?>
						</p>
						<p class="form-field">
							<label>
								<?php 
$form->show_field('images_append_to_existing');
?>
								<?php 
$form->show_label('images_append_to_existing');
?>
							</label>
							<?php 
echo \wp_kses_post(\wc_help_tip(\__('If you select this option, the import will add new images to the existing ones. By default, the import will replace the images with the ones from the feed.', 'dropshipping-xml-for-woocommerce')));
?>
						</p>
					</div>
				</div>

				<div id="woocommerce-product-categories" class="postbox group-switch">
					<h2 class="group-switcher"><span><?php 
echo \esc_html(\__('Categories', 'dropshipping-xml-for-woocommerce'));
?></span> <span class="group-arrow dashicons dashicons-arrow-down"></span></h2>
					<div class="inside" style="padding: 10px">
						<p style="font-weight: bold;"><?php 
echo \wp_kses_post(\__('Read more in the <a href="https://wpde.sk/dropshipping-categories-mapping" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce'));
?></p>
						<p class="form-field">
							<label>
								<?php 
$form->show_field('product_categories_single_id');
$form->show_label('product_categories_single_id');
?>
							</label>
						</p>
						<div class="data-wrapper" data-id="product_categories_single_id">
							<label>
								<?php 
$form->show_label('category_single_id');
?> <?php 
$form->show_field('category_single_id');
?>
							</label>
						</div>
						<p class="form-field">
							<label>
								<?php 
$form->show_field('product_categories_multi_map_id');
$form->show_label('product_categories_multi_map_id');
?>
							</label>
						</p>
						<div class="data-wrapper">
							<p class="form-field">
								<label>
									<?php 
$form->show_field('category_map_import_id');
$form->show_label('category_map_import_id');
?>
								</label>
								<?php 
echo \wp_kses_post(\wc_help_tip(\__('Only the products from the feed that are assigned to the categories mapped below, will be imported. Checking will disable the option "Create or select categories automatically".', 'dropshipping-xml-for-woocommerce')));
?>
							</p>
							<p class="form-field" id="form-field-auto-create-category">
								<label>
									<?php 
$form->show_field('category_map_import_auto_create');
$form->show_label('category_map_import_auto_create');
?>
								</label>
								<?php 
echo \wp_kses_post(\wc_help_tip(\__('Check to import categories from the file based on the values completed in the "Product category field".', 'dropshipping-xml-for-woocommerce')));
?>
							</p>
						</div>
						<div class="data-wrapper" data-id="product_categories_multi_map_id">
							<p class="form-field">
								<label>
									<?php 
$form->show_label('category_field');
?>
									<?php 
echo \wp_kses_post(\wc_help_tip(\__('If you want to add multiple categories to mapper just separate dragged fields by comma.', 'dropshipping-xml-for-woocommerce')));
?>
									<?php 
$form->show_field('category_field');
?>
								</label>
							</p>

							<p class="form-field">
								<label style="font-weight:bold;">
									<?php 
$form->show_label('category_map');
?>
								</label>
							</p>
							<div class="categories_field" id="categories-wrapper">
								<?php 
$form->show_field('category_map');
?>
							</div>
							<div class="toolbar">
								<a href="#" id="add_category"><?php 
echo \esc_html(\__('Add +', 'dropshipping-xml-for-woocommerce'));
?></a>
							</div>
						</div>
						<p class="form-field">
							<label>
								<?php 
$form->show_field('product_categories_tree_id');
$form->show_label('product_categories_tree_id');
?>
							</label>
						</p>
						<div class="data-wrapper">
							<p class="form-field">
								<label>
									<?php 
$form->show_label('category_tree_field');
?>
									<?php 
echo \wp_kses_post(\wc_help_tip(\__('If you want to add multiple categories, just separate dragged fields by comma.', 'dropshipping-xml-for-woocommerce')));
?>
									<?php 
$form->show_field('category_tree_field');
?>
								</label>
							</p>
							<p class="form-field">
							<label>
								<?php 
$form->show_field('category_tree_add_all');
$form->show_label('category_tree_add_all');
?>
							</label>
							<?php 
echo \wp_kses_post(\wc_help_tip(\__('If you check this option, each product will be added to all subcategories in the category tree.', 'dropshipping-xml-for-woocommerce')));
?>
							</p>
							<p class="form-field">
								<label>
									<?php 
$form->show_label('category_tree_separator');
?>
									<?php 
echo \wp_kses_post(\wc_help_tip(\__('Add a separator character to separate categories.', 'dropshipping-xml-for-woocommerce')));
?>
								</label>
								<div style="display:block">
									<?php 
$form->show_field('category_tree_separator');
?>
								</div>
							</p>
						</div>

					</div>
				</div>


			</div><!-- /post-body -->
			<br class="clear">
			<?php 
$renderer->output_render('Steps', ['edit' => $edit, 'mode' => $mode, 'form' => $form, 'previous_step' => $previous_step]);
?>
		</div><!-- /poststuff -->

		<span class="type_box hidden"> &mdash;
			<label for="product-type">
				<?php 
$form->show_field('product-type');
?>
			</label>
			<label for="_virtual" class="show_if_simple">
				<?php 
echo \esc_html(\__('Virtual', 'dropshipping-xml-for-woocommerce'));
?>
				<?php 
$form->show_field('_virtual');
?>
			</label>
		</span>


	</div>
</div>
<?php 
$form->form_fields_complete();
$form->form_end(['template' => 'form-end-no-table']);
$renderer->output_render('Footer');
