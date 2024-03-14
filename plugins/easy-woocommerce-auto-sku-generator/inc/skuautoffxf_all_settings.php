<?php
/**
 * Add settings to the specific section we created before
 */

add_filter('woocommerce_get_settings_products', 'skuautoffxf_all_settings', 10, 2);

/**
 *  All settings
 *
 * @param mixed $settings Setting SKU.
 * @param mixed $current_section .
 *
 * @return array
 */
function skuautoffxf_all_settings($settings, $current_section)
{


    wp_enqueue_script('ffxf_settings_script');

    if ('skuautoffxf' === $current_section) {

        $ffxf_settings_sku = array();

        $ffxf_settings_sku[] = array(
            'name' => __('General plugin settings', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'title',
            'desc' => __('On this page you can configure sku', 'easy-woocommerce-auto-sku-generator'),
            'id' => 'skuautoffxfid',
        );

        $ffxf_settings_sku[] = array(
            'title' => __('Characters', 'easy-woocommerce-auto-sku-generator'),
            'desc_tip' => __('SKU prefix and product ID are not counted', 'easy-woocommerce-auto-sku-generator'),
            'id' => 'skuautoffxf_auto_number',
            'type' => 'number',
            'custom_attributes' => array(
                'min' => 0,
                'step' => 1,
            ),
            'default' => '5',
            'class' => 'manage_stock_field',
            'css' => 'display:block; width:50px;',
            'desc' => __('Specify the number of characters in SKU', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Prefix SKU', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'text',
            'default' => '',
            'placeholder' => 'For example BN_',
            'desc_tip' => __('Characters in the prefix are not assigned to the total number of characters (field above)', 'easy-woocommerce-auto-sku-generator'),
            'id' => 'skuautoffxf_auto_prefix',
            'css' => 'width:100%;display:block',
            'desc' => __('Enter any prefix that will be displayed at the beginning of the SKU. <br>For example <span class="skuautoffxf_separator">BN_</span>893267', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Select SKU format', 'easy-woocommerce-auto-sku-generator'),
            'id' => 'skuautoffxf_letters_and_numbers',
            'type' => 'radio',
            'default' => 'ffxf_numbers',
            'css' => 'color:red',

            'options' => array(
                'ffxf_numbers' => __('Only numbers, for example - 893267', 'easy-woocommerce-auto-sku-generator'),
                'ffxf_letters' => __('Only letters, for example - KSZHGD', 'easy-woocommerce-auto-sku-generator'),
                'ffxf_landnum' => __('Letters and numbers, for example - 7SZ4G2', 'easy-woocommerce-auto-sku-generator'),
                'ffxf_slug' => __('Use product slug, for example - your-product', 'easy-woocommerce-auto-sku-generator'),
            ),

        );

        $ffxf_settings_sku[] = array(
            'name' => __('Add product ID', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'checkbox',
            'id' => 'skuautoffxf_auto_ID',
            'css' => 'min-width:300px;display:block',
            'desc' => __('If checked, product ID will be added to SKU', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Take previous product', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'checkbox',
            'id' => 'skuautoffxf_previous',
            'css' => 'min-width:300px;display:block',
            'desc' => __('Take into account the previous product', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Duplicate SKUs', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'checkbox',
            'id' => 'skuautoffxf_duplicate_sku',
            'css' => 'min-width:300px;display:block',
            'desc' => __('Allow identical SKUs. If enabled, some SKUs can be identical', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('SKU suffix', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'text',
            'id' => 'skuautoffxf_suffix',
            'css' => 'min-width:300px;display:block',
            'placeholder' => 'For example "_SUF_"',
            'desc_tip' => __('The suffix is set at the end of the SKU and can have different characters. We recommend using the suffix in combination with the "Additional number" option e.g. suffix "_" and additional number "001" then your SKU will have this format BN_893267<em>_001</em>.', 'easy-woocommerce-auto-sku-generator'),
            'desc' => __('Enter any suffix that will appear at the end of the SKU. <br>For example BN_893267<span class="skuautoffxf_separator">_SUF_</span>', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Additional number', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'number',
            'id' => 'skuautoffxf_number_dop',
            'css' => 'min-width:300px;display:block',
            'placeholder' => 'For example "001"',
            'desc_tip' => __('For example, you can set this field to 001 and then +1 will be added at the end of the SKU when the SKU is generated. In this way you can generate SKUs in order 001, 002, 003. We recommend using the suffix in combination with the "SKU suffix" option', 'easy-woocommerce-auto-sku-generator'),
            'desc' => __('This number is applied at the end of the SKU for the mass generator and adds +1 at each step.<br>For example BN_893267_SUF_<span class="skuautoffxf_separator">001</span><br><span class="skuautoffxf_warning">Incrementing by +1 only applies during bulk generation, while when creating a new product (during product editing), this field will simply append to the end of the SKU.</span>', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Format "Additional number"', 'easy-woocommerce-auto-sku-generator'),
            'id' => 'skuautoffxf_format_an',
            'type' => 'select',
            'default' => 'ffxf_format_an',
            'options' => array(
                'ffxf_format_an' => __('For example: 008 → 009 → 0010 → 0011', 'easy-woocommerce-auto-sku-generator'),
                'ffxf_format_an_up' => __('For example: 008 → 009 → 010 → 011', 'easy-woocommerce-auto-sku-generator'),
            ),
            'desc_tip' => __('This option is experimental, and specifying "0" at the beginning may lead to incorrect results. This functionality will be improved over time', 'easy-woocommerce-auto-sku-generator'),
            'desc' => __('In the previous field, you selected a value that starts with "0" so you can choose the generation format<br><span class="skuautoffxf_warning">Applies only during bulk generation.</span>', 'easy-woocommerce-auto-sku-generator'),

        );

        $ffxf_settings_sku[] = array(
            'name' => __('Enable variant settings', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'checkbox',
            'id' => 'skuautoffxf_variation_settings',
            'css' => 'min-width:300px;display:block',
            'desc' => __('If enabled, you can fine-tune the generation of variant SKU more precisely', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Variation Separator', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'text',
            'default' => '',
            'placeholder' => 'For example "-"',
            'id' => 'skuautoffxf_variation_separator',
            'css' => 'width:100%;display:block',
            'desc_tip' => __('This setting is responsible for the separator between the main SKU and the variation number.', 'easy-woocommerce-auto-sku-generator'),
            'desc' => __('You can use the characters "/", "\", "|", "-", "--", ".",  "&", "#", "$", "@" or another prefix "_var_". <br>For example BN_893267<span class="skuautoffxf_separator">--</span>01, BN_893267<span class="skuautoffxf_separator">--</span>02<br>Another example BN_893267<span class="skuautoffxf_separator">_var_</span>01, BN_893267<span class="skuautoffxf_separator">_var_</span>02', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array(
            'name' => __('Variable product', 'easy-woocommerce-auto-sku-generator'),
            'type' => 'checkbox',
            'id' => 'skuautoffxf_auto_variant',
            'css' => 'min-width:300px;display:block',
            'desc' => __('Turn off generation of variable product', 'easy-woocommerce-auto-sku-generator'),
        );

        $ffxf_settings_sku[] = array('type' => 'sectionend', 'id' => 'skuautoffxf');

        return $ffxf_settings_sku;

    } else {
        return $settings;
    }
}

add_action('admin_print_footer_scripts', 'ffxf_action_javascript', 998);
function ffxf_action_javascript()
{
    ?>
    <script type="text/javascript">

        function incrementWithLeadingZeros(number) {
            // Convert the number to a string
            number = number.toString();

            // Remove leading zeros (if any)
            var leadingZeros = number.match(/^0+/);
            if (leadingZeros) {
                leadingZeros = leadingZeros[0];
                number = number.substring(leadingZeros.length);
            }

            // Convert the string back to a number
            var parsedNumber = parseInt(number);

            // Get the format option from WooCommerce
            var formatOption = '<?php echo get_option("skuautoffxf_format_an"); ?>';

            // Check if the number is an integer or with a decimal point
            if (!isNaN(parsedNumber)) {
                // Determine the increment based on the format option
                var incrementValue = 1;

                // Increment the number without removing leading zeros
                parsedNumber += incrementValue;

                // Convert the number back to a string with leading zeros
                var formattedNumber = parsedNumber.toString();
                if (formatOption === 'ffxf_format_an_up') {
                    if (leadingZeros) {
                        while (formattedNumber.length < number.length) {
                            formattedNumber = '0' + formattedNumber;
                        }
                        formattedNumber = leadingZeros + formattedNumber;
                        if (formattedNumber.endsWith('010') || formattedNumber.endsWith('100')) {
                            formattedNumber = formattedNumber.substring(1);
                        }
                    } else {
                        formattedNumber = formattedNumber.padStart(number.length, '0');
                    }
                } else {
                    formattedNumber = formattedNumber.padStart(number.length, '0');
                }

                if (leadingZeros && formatOption !== 'ffxf_format_an_up') {
                    formattedNumber = leadingZeros + formattedNumber;
                }

                return formattedNumber;
            }

            // If the number is not recognized, return the original string without changes
            return number;
        }

        // Getting the value of the skuautoffxf_number_dop option
        var number_dop = '<?php echo get_option( 'skuautoffxf_number_dop' ); ?>';

        var paged = 1;
        var procent = <?php echo wp_count_posts('product')->publish; ?>;
        var procent_total = <?php echo wp_count_posts('product')->publish; ?>;
        jQuery(function ($) {
            $.function_product_update = function () {

                // If it is the first startup, leave the value
                if(paged === 1){
                    number_dop = '<?php echo get_option( 'skuautoffxf_number_dop' ); ?>';
                }else if (number_dop !== '') {
                    number_dop = incrementWithLeadingZeros(number_dop);
                } else {
                    // Otherwise, assign an empty string
                    number_dop = '';
                }

                var data = {
                    'action': 'load_posts_by_ajax',
                    'paged': paged,
                    'number_dop': number_dop,
                    'security': '<?php echo wp_create_nonce("load_more_posts"); ?>',
                    'class': 'load_more_posts',
                    'checked': $('#check_generate').prop('checked') ? 1 : 0,
                };

                $.get(ajaxurl, data, function (response) {
                    if (response != '') {

                        $('.modal_generate .my-posts').append(response);
                        paged++;

                        procent--;

                        var edenica = procent_total;
                        var celoe = procent;
                        var proc = 100;
                        var gotovo = proc - (celoe * proc / edenica);

                        $('.modal_generate .progress-pie-chart').attr('data-percent', gotovo.toFixed());

                        var $ppc = $('.modal_generate .progress-pie-chart'),
                            percent = gotovo.toFixed(),
                            deg = (360 * percent++) / 100;
                        if (percent > 50) {
                            $ppc.addClass('gt-50');
                        }
                        if (percent >= 100) {
                            percent = 100;
                            deg = 360;
                        }

                        if (procent === 0) {
                            percent = 100;
                            deg = 360;
                        }
                        $('.modal_generate .ppc-progress-fill').css('transform', 'rotate(' + deg + 'deg)');
                        $('.modal_generate .ppc-percents span').html(percent + '%');
                        $('.modal_generate label#check_gen').hide();


                        $('.modal_generate .ps').text('<?php echo __("Products left:", "easy-woocommerce-auto-sku-generator"); ?> ' + procent);
                        $.function_product_update();
                    } else {
                        $('.modal_generate .generate_button').hide();


                        $('.modal_generate .ps').text('<?php echo __("Product processing completed!", "easy-woocommerce-auto-sku-generator"); ?>');
                        $('.modal_generate #text_generate_modal').fadeOut().fadeIn().text('<?php echo __("Thanks for waiting! If the process did not work correctly, please refer to the user support forum.", "easy-woocommerce-auto-sku-generator"); ?>');
                    }
                });

            };

            $('body').on('click', '.generate_button', function () {
                $.function_product_update();
                $('.modal_generate .progress-pie-chart').fadeIn();
                $('.modal_generate .generate_button').fadeOut();
                $('.modal_generate #text_generate_modal').fadeOut().fadeIn().text(' <?php echo __("At the moment, the process of generating all articles is in progress. The process will take some time, please wait until the end!", "easy-woocommerce-auto-sku-generator"); ?>');
            });

        });
    </script>
    <?php
}


add_action('admin_print_footer_scripts', 'action_javascript_category', 999);
function action_javascript_category()
{
    ?>
    <script type="text/javascript">
        var paged = 1;
        //var procent = <?php //echo wp_count_posts('product')->publish; ?>;

        jQuery(function ($) {

            $.function_product_update_category = function () {

                // Если значение опции не пустое, выполнить прибавление с ведущими нулями
                if (number_dop !== '') {
                    number_dop = incrementWithLeadingZeros(number_dop);
                } else {
                    // В противном случае, присвоить пустую строку
                    number_dop = '';
                }

                var data = {
                    'action': 'load_posts_by_ajax_category',
                    'paged': paged,
                    'number_dop': number_dop,
                    'security': '<?php echo wp_create_nonce("load_more_posts_category"); ?>',
                    'class': 'load_more_posts_category',
                    'checked': $('.modal_generate_category #check_generate_category').prop('checked') ? 1 : 0,
                    'select_cat': $('.modal_generate_category #product_cat').val(),
                };

                $.get(ajaxurl, data, function (response) {
                    if (response != '') {

                        var select_cat = jQuery('.modal_generate_category .ps').text();
                        var num_category = parseInt(select_cat.replace(/\D+/g, ""));
                        var procent = num_category;
                        var procent_total = parseInt(jQuery('#product_cat option:selected').text().replace(/\D+/g, ""));

                        $('.modal_generate_category .my-posts').append(response);
                        paged++;
                        procent--;


                        var edenica = num_category;
                        var celoe = procent_total;
                        var proc = 100;
                        var gotovo = proc - (edenica * proc / celoe);

                        $('.modal_generate_category .progress-pie-chart').attr('data-percent', gotovo.toFixed());

                        var $ppc = $('.modal_generate_category .progress-pie-chart'),
                            percent = gotovo.toFixed(),
                            deg = (360 * percent++) / 100;

                        if (percent > 50) {
                            $ppc.addClass('gt-50');
                        }

                        if (percent >= 100) {
                            percent = 100;
                            deg = 360;
                        }

                        if (procent === 0) {
                            percent = 100;
                            deg = 360;
                        }

                        $('.modal_generate_category .ppc-progress-fill').css('transform', 'rotate(' + deg + 'deg)');
                        $('.modal_generate_category .ppc-percents span').html(percent + '%');
                        $('.modal_generate_category label#check_gen').hide();


                        $('.modal_generate_category .ps').text('<?php echo __("Products left:", "easy-woocommerce-auto-sku-generator"); ?> ' + procent);
                        $.function_product_update_category();
                    } else {
                        $('.modal_generate_category .generate_button_category').hide();


                        $('.modal_generate_category .ps').text('<?php echo __("Category processing completed!", "easy-woocommerce-auto-sku-generator"); ?>');
                        $('.modal_generate_category #text_generate_modal').fadeOut().fadeIn().text('<?php echo __("Thanks for waiting! If the process did not work correctly, please refer to the user support forum.", "easy-woocommerce-auto-sku-generator"); ?>');
                    }
                });

            };

            $('body').on('click', '.generate_button_category', function () {
                $.function_product_update_category();
                $('.modal_generate_category .progress-pie-chart').fadeIn();
                $('.modal_generate_category .generate_button_category').fadeOut();
                $('select#product_cat').fadeOut();

                $('.modal_generate_category #text_generate_modal').fadeOut().fadeIn().text(' <?php echo __("At the moment, the process of generating all articles is in progress. The process will take some time, please wait until the end!", "easy-woocommerce-auto-sku-generator"); ?>');
            });

        });
    </script>
    <?php
}