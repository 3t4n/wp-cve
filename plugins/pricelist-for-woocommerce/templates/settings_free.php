<div class="wrap">
    <h2>PriceList Settings</h2>
    <form method="post" id="pricelist_settings">
        <?php @settings_fields('wp_plugin_template-group'); ?>
        <?php @do_settings_sections('section_options_page_type') ?>
        
        <table class="form-table two-columns">
            <tr valign="top">
                <th scope="row"><label for="pricelist_company">Company Name</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Company Name" data-tipso="The name of your company to be displayed in the PDF output."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <input size="50" type="text" name="pricelist_company" id="pricelist_company" value="<?=$pricelist_company?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_name">Price List Name</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Price List Name" data-tipso="The name of the price list to be displayed above the HTML price list table and in the top right corner of each PDF page."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <input size="50" type="text" name="pricelist_name" id="pricelist_name" value="<?=$pricelist_name?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_page">Page Name</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Page Name" data-tipso="The page name indicator at the bottom of each page in the PDF."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <input  size="50" type="text" name="pricelist_page" id="pricelist_page" value="<?=$pricelist_page?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_output">Shortcode Output</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Shortcode Output" data-tipso="Which type of output the shortcode will display. When it's on HTML it will display the output directly to the page, when it's on PDF Display it will create a button for the visitor to generate and view a PDF price list in their browser, and when set to PDF Download that price list PDF will be downloaded."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <select name="pricelist_output" id="pricelist_output">
                        <option <?=($pricelist_output == 'html') ? 'selected="selected"' : null ?> value="html">HTML</option>
                        <option <?=($pricelist_output == 'pdf') ? 'selected="selected"' : null ?> value="pdf">PDF Display</option>
                        <option <?=($pricelist_output == 'dl') ? 'selected="selected"' : null ?> value="dl">PDF Download</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_table_header_color">Table Header Color</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Table Header Color" data-tipso="The color for the first row in the table (Which is only displayed when the product is in a sub category of some kind)"><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <input  size="5" maxlength="7" type="text" class="jscolor" name="pricelist_table_header_color" id="pricelist_table_header_color" value="<?=$pricelist_table_header_color;?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_table_color">Table Color</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Table Color" data-tipso="The color for the rest of the entire table."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <input  size="5" maxlength="7" type="text" class="jscolor" name="pricelist_table_color" id="pricelist_table_color" value="<?=$pricelist_table_color?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_description">Product Description</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Product Description" data-tipso="Whether the full WooCommerce description should be displayed in the table."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <select name="pricelist_description" id="pricelist_description">
                        <option <?=$pricelist_description ? 'selected="selected"' : null?> value="true">Show</option>
                        <option <?=!$pricelist_description ? 'selected="selected"' : null?> value="false">Hide</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_short_description">Product Short Description</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Product Short Description" data-tipso="Whether the short WooCommerce description should be displayed in the table."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <select name="pricelist_short_description" id="pricelist_short_description">
                        <option <?=$pricelist_short_description ? 'selected="selected"' : null?> value="true">Show</option>
                        <option <?=!$pricelist_short_description ? 'selected="selected"' : null?> value="false">Hide</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_product_image">Product Image</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Product Image" data-tipso="Whether the WooCommerce product image should be displayed in the table for each product."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <select name="pricelist_product_image" id="pricelist_product_image">
                        <option <?=$pricelist_product_image ? 'selected="selected"' : null?> value="true">Show</option>
                        <option <?=!$pricelist_product_image ? 'selected="selected"' : null?> value="false">Hide</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_category_description">Category Description</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Category Description" data-tipso="Whether the WooCommerce category description should be displayed underneath the table for each category."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <select name="pricelist_category_description" id="pricelist_category_description">
                        <option <?=$pricelist_category_description ? 'selected="selected"' : null?> value="true">Show</option>
                        <option <?=!$pricelist_category_description ? 'selected="selected"' : null?> value="false">Hide</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_category_image">Category Image</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="Category Image" data-tipso="Whether the WooCommerce category image should be displayed in front of the category name."><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <select name="pricelist_category_image" id="pricelist_category_image">
                        <option <?=$pricelist_category_image ? 'selected="selected"' : null?> value="true">Show</option>
                        <option <?=!$pricelist_category_image ? 'selected="selected"' : null?> value="false">Hide</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pricelist_date1">PDF Date</label></th>
                <td class="tooltip-column"><span style="border-bottom:none;" class="tooltip" data-tipso-title="PDF Date" data-tipso="How the date should be displayed in the top right corner of the PDF"><i class="fa fa-info-circle"></i></span></td>
                <td>
                    <select name="pricelist_date1" id="pricelist_date1">
                        <option <?=($pricelist_date1 == 0) ? 'selected="selected"' : null ?> value="0">Hide</option>
                        <option <?=($pricelist_date1 == 1) ? 'selected="selected"' : null ?> value="1">Day</option>
                        <option <?=($pricelist_date1 == 2) ? 'selected="selected"' : null ?> value="2">Month</option>
                        <option <?=($pricelist_date1 == 3) ? 'selected="selected"' : null ?> value="3">Year</option>
                    </select>
                    <select name="pricelist_date2" id="pricelist_date2">
                        <option <?=($pricelist_date2 == 0) ? 'selected="selected"' : null ?> value="0">Hide</option>
                        <option <?=($pricelist_date2 == 1) ? 'selected="selected"' : null ?> value="1">Day</option>
                        <option <?=($pricelist_date2 == 2) ? 'selected="selected"' : null ?> value="2">Month</option>
                        <option <?=($pricelist_date2 == 3) ? 'selected="selected"' : null ?> value="3">Year</option>
                    </select>
                    <select name="pricelist_date3" id="pricelist_date3">
                        <option <?=($pricelist_date3 == 0) ? 'selected="selected"' : null ?> value="0">Hide</option>
                        <option <?=($pricelist_date3 == 1) ? 'selected="selected"' : null ?> value="1">Day</option>
                        <option <?=($pricelist_date3 == 2) ? 'selected="selected"' : null ?> value="2">Month</option>
                        <option <?=($pricelist_date3 == 3) ? 'selected="selected"' : null ?> value="3">Year</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php wp_nonce_field( 'pricelist_wc_option_page_check_action' ); ?>
        <input id="save-button" type="submit" value="Save Settings" class="button button-primary button-large">
    </form>
</div>