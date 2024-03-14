jQuery.fn.viwcuf_get_variations = function (params) {
    new viwcuf_get_variation(this, params);
    return this;
};
var viwcuf_get_variation = function (form, params) {
    let self = this;
    self.wc_ajax_url = params.wc_ajax_url || '';
    self.form = form;
    self.product_id = parseInt(form.data('product_id'));
    self.variations = form.data('product_variations') || '';
    self.is_ajax = !self.variations;
    self.xhr = false;
    self.img_product = self.get_product_img(form);
    self.img_src = self.img_product.attr('data-src') || self.img_product.attr('src') || self.img_product.attr('content') || self.img_product.attr('srcset') || '';
    self.current_attr = params['current_attr'] || {};
    form.on('viwcuf_check_variations',{ viwcuf_get_variation: self },self.find_variation);
    form.on('change','select',{ viwcuf_get_variation: self }, self.onChange);
    setTimeout(function (viwcuf_get_variation) {
        let selected = 0;
        if (form.closest('.vi-wcuf-product-wrap-checked').length) {
            let variation = form.closest('.vi-wcuf-product-wrap-checked').data('variation') || {};
            form.find('select').each(function () {
                let attr_name = jQuery(this).attr('name');
                let val = variation[attr_name]||'';
                viwcuf_get_variation.current_attr[jQuery(this).data('attribute_name')] = val;
                jQuery(this).val(val).trigger('change');
            });
        } else {
            form.find('select').each(function () {
                let val = jQuery(this).val();
                viwcuf_get_variation.current_attr[jQuery(this).data('attribute_name')] = val;
                if (val) {
                    selected++;
                    jQuery(this).val(val);
                } else if (jQuery(this).closest('.vi-wcuf-swatches-value').data('selected')) {
                    viwcuf_get_variation.current_attr[jQuery(this).data('attribute_name')] = jQuery(this).closest('.vi-wcuf-swatches-value').data('selected');
                    jQuery(this).val(jQuery(this).closest('.vi-wcuf-swatches-value').data('selected')).addClass('viwcuf-attribute-options-selected').trigger('change');
                } else {
                    jQuery(this).closest('.vi-wcuf-swatches-value').find('.vi-wpvs-option-wrap.vi-wpvs-option-wrap-selected').removeClass('vi-wpvs-option-wrap-selected');
                }
            });
        }
        if (selected) {
            form.trigger('viwcuf_check_variations');
        }
    }, 100, self);
};
viwcuf_get_variation.prototype.onChange = function(event){
    let self = event.data.viwcuf_get_variation;
    let form = self.form;
    form.find( 'input[name="variation_id"], input.variation_id' ).val( '' ).trigger( 'change' );
    let val = jQuery(this).val() || '';
    if (jQuery(this).hasClass('viwcuf-attribute-options-selected')) {
        val = jQuery(this).closest('.vi-wcuf-swatches-value').data('selected');
        jQuery(this).removeClass('viwcuf-attribute-options-selected');
    }
    self.current_attr[jQuery(this).data('attribute_name')] = val;
    if (Object.keys(self.current_attr).length < form.find('.viwcuf-attribute-options').length) {
        return false;
    }
    if (self.is_ajax){
        form.trigger('viwcuf_check_variations');
    }else {
        form.trigger('woocommerce_variation_select_change');
        form.trigger('viwcuf_check_variations');
    }
    form.trigger('woocommerce_variation_has_changed');
};
viwcuf_get_variation.prototype.find_variation = function (event) {
    let self = event.data.viwcuf_get_variation, variation=null, is_stop = false;
    let attrs = self.current_attr,
        form = self.form,
        variations = self.variations;
    if (!form.hasClass('vi-wcuf-cart-form-variable')) {
        if (form.closest('.vi-wcuf-product-wrap-checked').length) {
            form.find('select').each(function () {
                let val = jQuery(this).val();
                jQuery(this).find('option').each(function () {
                    if (jQuery(this).attr('value') != val) {
                        jQuery(this).addClass('vi-wcuf-option-disabled');
                    }
                });
            });
            return false;
        }
        jQuery.each(attrs, function (k, v) {
            if (!v) {
                is_stop = true;
                return false;
            }
        });
        if (!is_stop) {
            form.find('.vi-wcuf-us-product-bt-atc, vi-wcuf-swatches-control-footer-bt-ok').removeClass('vi-wcuf-button-swatches-disable');
            form.closest('.vi-wcuf-ob-product-wrap').find('.vi-wcuf-ob-checkbox').removeClass('vi-wcuf-ob-checkbox-swatches-disable');
        } else {
            form.find('.vi-wcuf-us-product-bt-atc, vi-wcuf-swatches-control-footer-bt-ok').addClass('vi-wcuf-button-swatches-disable');
            form.closest('.vi-wcuf-ob-product-wrap').find('.vi-wcuf-ob-checkbox').addClass('vi-wcuf-ob-checkbox-swatches-disable');
        }
        return false;
    }
    jQuery.each(attrs, function (k, v) {
        if (!v) {
            is_stop = true;
            return false;
        }
    });
    if (is_stop) {
        self.update_attributes(attrs, variations, form, self);
        self.show_variation(self, null, form);
        return false;
    }
    if (self.is_ajax) {
        if (self.xhr) {
            self.xhr.abort();
        }
        if (variations) {
            jQuery.each(variations, function (key, value) {
                if (self.check_is_equal(attrs, value.attributes)) {
                    variation = value;
                    return false;
                }
            });
            if (variation) {
                self.show_variation(self, variation, form);
            } else {
                if (variations.length < parseInt(form.data('variation_count') || 0)) {
                    self.call_ajax(attrs, variations, form, self);
                } else {
                    self.show_variation(self, null, form);
                }
            }
        } else {
            variations = [];
            self.call_ajax(attrs, variations, form, self);
        }
    } else {
        jQuery.each(variations, function (key, value) {
            if (self.check_is_equal(attrs, value.attributes)) {
                variation = value;
                return false;
            }
        });
        if (!variation){
            form.find('select').each(function (k,v) {
                jQuery(v).val('');
                attrs[jQuery(v).data('attribute_name')] = '';
            });
        }
        self.update_attributes(attrs, variations, form, self);
        self.show_variation(self, variation, form);
    }
};
viwcuf_get_variation.prototype.update_attributes = function (attrs, variations, form, self) {
    if (self.is_ajax) {
        return false;
    }
    form.find('select').each(function (k, v) {
        let current_select = jQuery(v);
        let current_name = current_select.data('attribute_name') || current_select.attr('name'),
            show_option_none = current_select.data('show_option_none'),
            current_val = current_select.val() || '',
            current_val_valid = true,
            new_select = jQuery('<select/>'),
            attached_options_count,
            option_gt_filter = ':gt(0)';

        // Reference options set at first.
        if (!current_select.data('attribute_html')) {
            let refSelect = current_select.clone();
            refSelect.find('option').removeAttr('disabled attached selected');
            // Legacy data attribute.
            current_select.data('attribute_options', refSelect.find('option' + option_gt_filter).get());
            current_select.data('attribute_html', refSelect.html());
        }

        new_select.html(current_select.data('attribute_html'));

        // The attribute of this select field should not be taken into account when calculating its matching variations:
        // The constraints of this attribute are shaped by the values of the other attributes.
        let checkAttributes = jQuery.extend(true, {}, attrs);
        checkAttributes[current_name] = '';
        let match_variations = [];
        for (let i = 0; i < variations.length; i++) {
            let match = variations[i];
            if (self.check_is_equal(checkAttributes, match.attributes)) {
                match_variations.push(match);
            }
        }
        // Loop through variations.
        for (let num in match_variations) {
            if (typeof (match_variations[num]) === 'undefined') {
                continue;
            }
            let variationAttributes = match_variations[num].attributes;

            for (let attr_name in variationAttributes) {
                if (!variationAttributes.hasOwnProperty(attr_name)) {
                    continue;
                }
                let attr_val = variationAttributes[attr_name],
                    variation_active = '';

                if (attr_name === current_name) {
                    if (match_variations[num].variation_is_active) {
                        variation_active = 'enabled';
                    }
                    if (attr_val) {
                        // Decode entities.
                        attr_val = jQuery('<div/>').html(attr_val).text();
                        // Attach to matching options by value. This is done to compare
                        // TEXT values rather than any HTML entities.
                        let $option_elements = new_select.find('option');
                        if ($option_elements.length) {
                            for (let i = 0, len = $option_elements.length; i < len; i++) {
                                let $option_element = jQuery($option_elements[i]);
                                let option_value = $option_element.val();

                                if (attr_val === option_value) {
                                    $option_element.addClass('attached ' + variation_active);
                                    break;
                                }
                            }
                        }
                    } else {
                        // Attach all apart from placeholder.
                        new_select.find('option:gt(0)').addClass('attached ' + variation_active);
                    }
                }

            }

        }

        // Count available options.
        attached_options_count = new_select.find('option.attached').length;
        // Check if current selection is in attached options.
        if (current_val) {
            current_val_valid = false;

            if (0 !== attached_options_count) {
                new_select.find('option.attached.enabled').each(function () {
                    var option_value = jQuery(this).val();

                    if (current_val === option_value) {
                        current_val_valid = true;
                        return false; // break.
                    }
                });
            }
        }

        // Detach the placeholder if:
        // - Valid options exist.
        // - The current selection is non-empty.
        // - The current selection is valid.
        // - Placeholders are not set to be permanently visible.
        if (attached_options_count > 0 && current_val && current_val_valid && ('no' === show_option_none)) {
            new_select.find('option:first').remove();
            option_gt_filter = '';
        }

        // Detach unattached.
        new_select.find('option' + option_gt_filter + ':not(.attached)').remove();

        // Finally, copy to DOM and set value.
        current_select.html(new_select.html());
        current_select.find('option' + option_gt_filter + ':not(.enabled)').prop('disabled', true);

        // Choose selected value.
        if (current_val) {
            // If the previously selected value is no longer available, fall back to the placeholder (it's going to be there).
            if (current_val_valid) {
                current_select.val(current_val);
            } else {
                current_select.val('').change();
            }
        } else {
            current_select.val(''); // No change event to prevent infinite loop.
        }
    });
    // Custom event for when variations have been updated.
    form.trigger( 'woocommerce_update_variation_values' );
};
viwcuf_get_variation.prototype.call_ajax = function (attrs, variations, form, self) {
    attrs.product_id = self.product_id;
    if (form.hasClass('vi-wcuf-ob-cart-form')) {
        attrs.wcuf_pd_type = 'order_bump';
        attrs.discount_type = form.find('.viwcuf_ob_discount_type').val();
        attrs.discount_amount = form.find('.viwcuf_ob_discount_amount').val();
    } else {
        attrs.wcuf_pd_type = 'upsell_funnel';
    }
    attrs.viwcuf_nonce = viwcuf_frontend_us_params && viwcuf_frontend_us_params.nonce ? viwcuf_frontend_us_params.nonce : (viwcuf_frontend_ob_params && viwcuf_frontend_ob_params.nonce ? viwcuf_frontend_ob_params.nonce :'');
    self.xhr = jQuery.ajax({
        url: self.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcuf_get_variation'),
        type: 'POST',
        data: attrs,
        beforeSend: function () {
            form.addClass('vi-wcuf-product-loading').closest('.vi-wcuf-product').addClass('vi-wcuf-product-loading');
        },
        success: function (result) {
            self.show_variation(self, result, form);
            if (result) {
                variations[variations.length || 0] = result;
            }
            delete attrs.product_id;
            delete attrs.wcuf_pd_type;
            delete attrs.discount_type;
            delete attrs.discount_amount;
            delete attrs.viwcuf_nonce;
        },
        complete: function () {
            form.removeClass('vi-wcuf-product-loading').closest('.vi-wcuf-product').removeClass('vi-wcuf-product-loading');
        }
    });
};
viwcuf_get_variation.prototype.show_variation = function (self, variation, form) {
    let img_product = self.img_product;
    if (variation) {
        let purchasable = true;
        if ( ! variation.is_purchasable || ! variation.is_in_stock || ! variation.variation_is_visible ) {
            purchasable = false;
        }
        let price_html = variation.viwcuf_price_html || variation.price_html||'';
        if (price_html) {
            form.find('.single_variation').removeClass('vi-wcuf-disable').html('<div class ="woocommerce-variation-price" >' + price_html + '</div>');
            form.find('.vi-wcuf-swatches-control-content-price').removeClass('vi-wcuf-disable');
        }
        if (img_product && jQuery(img_product).length) {
            if (jQuery(img_product).parent().is('picture')) {
                jQuery(img_product).parent().find('source').each(function (k, v) {
                    jQuery(v).attr({'srcset': variation.image.thumb_src});
                })
            }
            img_product.attr({'src': variation.image.thumb_src, 'srcset': variation.image.thumb_src});
        }
        if (purchasable) {
            self.set_add_to_cart(variation.variation_id, form);
            form.find('.vi-wcuf-us-product-bt-atc, vi-wcuf-swatches-control-footer-bt-ok').removeClass('vi-wcuf-button-swatches-disable');
            form.closest('.vi-wcuf-ob-product-wrap').find('.vi-wcuf-ob-checkbox').removeClass('vi-wcuf-ob-checkbox-swatches-disable');
            if (form.closest('.vi-wcuf-product-wrap-checked').length) {
                form.find('select').each(function () {
                    let val = jQuery(this).val();
                    jQuery(this).find('option').each(function () {
                        if (jQuery(this).attr('value') != val) {
                            jQuery(this).addClass('vi-wcuf-option-disabled');
                        }
                    });
                    jQuery(this).closest('.vi-wpvs-variation-wrap-wrap').find('.vi-wpvs-option-wrap').each(function () {
                        if (jQuery(this).data('attribute_value') == val) {
                            jQuery(this).removeClass('vi-wpvs-option-wrap-default').addClass('vi-wpvs-option-wrap-selected');
                        } else {
                            jQuery(this).removeClass('vi-wpvs-option-wrap-selected vi-wpvs-option-wrap-default').addClass('vi-wpvs-option-wrap-disable');
                        }
                    });
                });
            }
        } else {
            self.set_add_to_cart('', form);
            form.find('.vi-wcuf-us-product-bt-atc, vi-wcuf-swatches-control-footer-bt-ok').addClass('vi-wcuf-button-swatches-disable');
            form.closest('.vi-wcuf-ob-product-wrap').find('.vi-wcuf-ob-checkbox').addClass('vi-wcuf-ob-checkbox-swatches-disable');
        }
        form.trigger('viwpvs_show_variation',[ variation, purchasable ]);
    } else {
        if (img_product && jQuery(img_product).length) {
            if (jQuery(img_product).parent().is('picture')) {
                jQuery(img_product).parent().find('source').each(function (k, v) {
                    jQuery(v).attr({'srcset': self.img_src});
                })
            }
            img_product.attr({'src': self.img_src, 'srcset': self.img_src});
        }
        self.set_add_to_cart('', form);
        form.find('.single_variation').addClass('vi-wcuf-disable');
        form.find('.vi-wcuf-swatches-control-content-price').addClass('vi-wcuf-disable');
        form.find('.vi-wcuf-us-product-bt-atc, vi-wcuf-swatches-control-footer-bt-ok').addClass('vi-wcuf-button-swatches-disable');
        form.closest('.vi-wcuf-ob-product-wrap').find('.vi-wcuf-ob-checkbox').addClass('vi-wcuf-ob-checkbox-swatches-disable');
        form.trigger('viwpvs_hide_variation');
    }
};
viwcuf_get_variation.prototype.set_add_to_cart = function (variation_id, form) {
    variation_id = variation_id || 0;
    form.find('.variation_id').val(variation_id);
};
viwcuf_get_variation.prototype.get_product_img = function (form) {
    let product = form.closest('.vi-wcuf-product'), product_img = false;
    if (product && product.find('img')) {
        product_img = product.find('img').first();
    }
    return product_img;
};
viwcuf_get_variation.prototype.check_is_equal = function (attrs1, attrs2) {
    let i, aProps = Object.getOwnPropertyNames(attrs1),
        bProps = Object.getOwnPropertyNames(attrs2);
    if (aProps.length !== bProps.length) {
        return false;
    }
    for (let i = 0; i < aProps.length; i++) {
        let attr_name = aProps[i];
        let val1 = attrs1[attr_name];
        let val2 = attrs2[attr_name];
        if (val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
            return false;
        }
    }
    return true;
};