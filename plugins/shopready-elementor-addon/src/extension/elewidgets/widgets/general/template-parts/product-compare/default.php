<?php
if (!defined('ABSPATH')) {
    exit;
}
// Product Compare Layout One


$compare_settings = $settings['wready_product_compare'];

$options = [];

foreach ($compare_settings as $value) {

    switch ($value['product_compare_type']) {
        case $value['product_compare_type']:
            $options[$value['product_compare_type']][$value['product_compare_type'] . '_exist'] = true;
            $options[$value['product_compare_type']]['title'] = $value['product_compare_text'];
            break;
        default:
            break;
    }

}


?>

<div class="woo-ready-productcompare">
    <table>

        <?php if ($options['title']['title_exist'] || $options['price']['price_exist']): ?>
            <tr>

                <th class="emepty-border">
                    <?php echo esc_html($options['title']['title']) ?>
                </th>

                <?php foreach ($products as $product):

                    $product_price = wc_price($product->get_price());
                    $product_name = $product->get_name();
                    ?>

                    <td>
                        <div class="woo-ready-product-title">
                            <span class="woo-ready-product-name">
                                <?php echo esc_html($product_name); ?>
                            </span>
                            <p>
                                <?php echo wp_kses_post($product_price); ?>
                            </p>
                        </div>
                    </td>

                <?php endforeach ?>


            </tr>

        <?php endif;
        if ($options['image']['image_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['image']['title']); ?>
                </th>

                <?php foreach ($products as $product):

                    $product_image = $product->get_image();

                    ?>

                    <td>
                        <div class="woo-ready-thumb">
                            <?php echo wp_kses_post($product_image); ?>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif;
        if ($options['avalability']['avalability_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['avalability']['title']); ?>
                </th>

                <?php foreach ($products as $product):


                    $stockStatus = ucwords($product->get_stock_status());

                    ?>

                    <td>
                        <div class="woo-ready-stock">
                            <span>
                                <?php echo wp_kses_post($stockStatus); ?>
                            </span>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif;
        if ($options['description']['description_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['description']['title']) ?>
                </th>

                <?php foreach ($products as $product):
                    $description = $product->get_description() ? $product->get_description() : __('N/A', 'shopready-elementor-addon');
                    ?>

                    <td class="description">
                        <?php echo wp_kses_post($description); ?>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif;
        if ($options['color']['color_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['color']['title']); ?>
                </th>

                <?php foreach ($products as $product):
                    $color = $product->get_attribute('color') ? $product->get_attribute('color') : __('N/A', 'shopready-elementor-addon');
                    ?>

                    <td>
                        <div class="woo-ready-color">
                            <span>
                                <?php echo wp_kses_post($color); ?>
                            </span>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif;
        if ($options['sku']['sku_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['sku']['title']); ?>
                </th>

                <?php foreach ($products as $product):
                    $sku = $product->get_sku() ? $product->get_sku() : esc_html__('N/A', 'shopready-elementor-addon');
                    ?>

                    <td>
                        <div class="woo-ready-sku">
                            <span>
                                <?php echo wp_kses_post($sku) ?>
                            </span>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif;
        if ($options['weight']['weight_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['weight']['title']); ?>
                </th>

                <?php foreach ($products as $product):
                    $weight = $product->get_weight() ? $product->get_weight() : esc_html__('N/A', 'shopready-elementor-addon');
                    ?>

                    <td>
                        <div class="woo-ready-weight">
                            <span>
                                <?php echo wp_kses_post($weight); ?>
                            </span>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif;
        if ($options['dimention']['dimention_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post(options['dimention']['title']); ?>
                </th>

                <?php foreach ($products as $product):
                    $dimensions = wc_format_dimensions($product->get_dimensions(false));
                    ?>

                    <td>
                        <div class="woo-ready-dimention">
                            <span>
                                <?php echo wp_kses_post($dimensions); ?>
                            </span>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif;
        if ($options['size']['size_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['size']['title']); ?>
                </th>

                <?php foreach ($products as $product):
                    $size = $product->get_attribute('size') ? $product->get_attribute('size') : esc_html__('N/A', 'shopready-elementor-addon');
                    ?>

                    <td>
                        <div class="woo-ready-size">
                            <span>
                                <?php echo wp_kses_post($size); ?>
                            </span>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>


        <?php endif;
        if ($options['addtocart']['addtocart_exist']): ?>

            <tr>

                <th>
                    <?php echo wp_kses_post($options['addtocart']['title']); ?>
                </th>

                <?php foreach ($products as $product):
                    $addToCartUrl = $product->add_to_cart_url();
                    ?>

                    <td>
                        <div class="woo-ready-button">
                            <a href="<?php echo esc_url($addToCartUrl); ?>"><?php echo esc_html($options['addtocart']['title']); ?></a>
                        </div>
                    </td>

                <?php endforeach ?>

            </tr>

        <?php endif; ?>

    </table>
</div>