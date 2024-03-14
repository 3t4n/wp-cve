<?php
/**
 * Woocommece tab content view.
 */
namespace Hurrytimer;

use Hurrytimer\Utils\Form;
use Hurrytimer\Utils\Helpers;
?>

<div id="hurrytimer-tabcontent-woocommerce" class="hurrytimer-tabcontent">
    <?php
    if (Helpers::isWcActive()): ?>
        <table class="form-table">
            <tr class="form-field">
                <td>
                    <label for="hurrytimer-wc-enable">
                        <?php _e("Display on product page", "hurrytimer") ?>
                    </label>
                </td>
                <td>
                    <?php Form::toggle(
                        'wc_enable',
                        $campaign->wcEnable,
                        'hurrytimer-wc-enable') ?>
                    <p class="description">
                        <?php _e('Display the timer on the product pages that match the following settings.', "hurrytimer") ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field hurrytimer-field-wc-products-selection-type">
                <td>
                    <label for="hurrytimer-wc-products-selection-type">
                        <?php _e("Products", "hurrytimer") ?>
                    </label>
                </td>
                <td>
                    <select name="wc_products_selection_type"
                            id="hurrytimer-wc-products-selection-type" class="hurryt-w-full">
                        <option value="<?php echo C::WC_PS_TYPE_ALL ?>"
                            <?php echo selected($campaign->wcProductsSelectionType,
                                C::WC_PS_TYPE_ALL) ?>>
                            <?php _e("All products", "hurrytimer") ?>
                        </option>
                        <option data-show-autocomplete="true"
                                value="<?php echo C::WC_PS_TYPE_INCLUDE_PRODUCTS ?>"
                            <?php echo selected($campaign->wcProductsSelectionType,
                                C::WC_PS_TYPE_INCLUDE_PRODUCTS) ?>>
                            <?php _e("Specific products...", "hurrytimer") ?>
                        </option>
                        <option data-show-autocomplete="true"
                                value="<?php echo C::WC_PS_TYPE_EXCLUDE_PRODUCTS ?>"
                            <?php echo selected($campaign->wcProductsSelectionType,
                                C::WC_PS_TYPE_EXCLUDE_PRODUCTS) ?>>
                            <?php _e("All products, except...", "hurrytimer") ?>
                        </option>
                        <option data-show-autocomplete="true"
                                value="<?php echo C::WC_PS_TYPE_INCLUDE_CATEGORIES ?>"
                            <?php echo selected($campaign->wcProductsSelectionType,
                                C::WC_PS_TYPE_INCLUDE_CATEGORIES) ?>>
                            <?php _e("Specific categories...", "hurrytimer") ?>
                        </option>
                        <option data-show-autocomplete="true"
                                value="<?php echo C::WC_PS_TYPE_EXCLUDE_CATEGORIES ?>"
                            <?php echo selected($campaign->wcProductsSelectionType,
                                C::WC_PS_TYPE_EXCLUDE_CATEGORIES) ?>>
                            <?php _e("All categories, except...", "hurrytimer") ?>
                        </option>
                    </select>
                </td>
            </tr>
            <tr class="form-field hurrytimer-field-wc-products-selection hidden">
                <td><label class="hurrytimer-products-selection-type-label"
                           for="hurrytimer-wc-products-selection">
                        <?php _e("All products", "hurrytimer") ?>
                    </label>
                </td>
                <td>
                    <select id="hurrytimer-wc-products-selection"
                            name="wc_products_selection[]"
                            class="hurrytimer-wc-products-selection hurryt-w-full"
                            multiple="multiple">
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['id'] ?>"
                                    selected="selected"><?php echo $product['text'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="form-field hurrytimer-field-wc-position">
                <td>
                    <label for="hurrytimer-wc-position"><?php _e('Position',
                            "hurrytimer") ?></label>
                </td>
                <td>
                    <select name="wc_position" id="hurrytimer-wc-position" class="hurryt-w-full">
                        <option
                                value="<?php echo C::WC_POSITION_ABOVE_TITLE ?>"
                            <?php echo selected($campaign->wcPosition,
                                C::WC_POSITION_ABOVE_TITLE) ?>>
                            <?php _e('Above title', "hurrytimer") ?>
                        </option>
                        <option
                                value="<?php echo C::WC_POSITION_BELOW_TITLE ?>"
                            <?php echo selected($campaign->wcPosition,
                                C::WC_POSITION_BELOW_TITLE) ?>>
                            <?php _e('Below title', "hurrytimer") ?>
                        </option>
                        <option
                                value="<?php echo C::WC_POSITON_BELOW_REVIEW_RATING ?>"
                            <?php echo selected($campaign->wcPosition,
                                C::WC_POSITON_BELOW_REVIEW_RATING) ?>>
                            <?php _e('Below Review rating', "hurrytimer") ?>
                        </option>
                        <option
                                value="<?php echo C::WC_POSITION_BELOW_PRICE ?>"
                            <?php echo selected($campaign->wcPosition,
                                C::WC_POSITION_BELOW_PRICE) ?>>
                            <?php _e('Below price', "hurrytimer") ?>
                        </option>
                        <option
                                value="<?php echo C::WC_POSITION_ABOVE_ATC_BUTTON ?>"
                            <?php echo selected($campaign->wcPosition,
                                C::WC_POSITION_ABOVE_ATC_BUTTON) ?>>
                            <?php _e('Above "Add to cart" button', "hurrytimer") ?>
                        </option>
                        <option
                                value="<?php echo C::WC_POSITION_BELOW_ATC_BUTTON ?>"
                            <?php echo selected($campaign->wcPosition,
                                C::WC_POSITION_BELOW_ATC_BUTTON) ?>>
                            <?php _e('Below "Add to cart" button', "hurrytimer") ?>
                        </option>

                    </select>
                    <p class="description">
                        <?php _e('Position on product page.', "hurrytimer") ?>
                    </p>
                </td>
            </tr>
            <tr class="form-field hurrytimer-field-wc-position">
                <td>
                    <label for="hurrytimer-wc-position"><?php _e("Display if") ?>
                    </label>
                    <span
                            title="Run this campaign on the selected products pages if these conditions are met."
                            class="hurryt-icon" data-icon="help">
                </td>
                <td>
                <?php if(!empty($campaign->wcConditions)): 
                     foreach($campaign->wcConditions as $groupId => $conditions):
                        include HURRYT_DIR . 'admin/templates/wc-condition-group.php';
                    ?>  
                     <?php endforeach; endif; ?>
                    <button type="button" class="button button-default hurryt-add-wc-condition-group" >Add condition group</button><span class="spinner hurryt-spinner"></span>
                </td>
            </tr>
        </table>

    <?php else: ?>
        <h3 style="text-align:center; margin-bottom:.2em"><?php _e("WooCommerce is not installed.",
                "hurrytimer") ?></h3>
        <p style="text-align:center; margin-top:0; color:#767676"><?php _e("Install WooCommerce to run the campaign on product page.",
                "hurrytimer") ?></p>
    <?php endif; ?>
</div>