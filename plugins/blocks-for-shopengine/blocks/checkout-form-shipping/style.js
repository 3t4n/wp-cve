
const Style = ({ settings, breakpoints, cssHelper }) => {

    cssHelper.add('.shopengine-checkout-form-shipping #ship-to-different-address > label > span', settings.shopengine_title_color, (val) => {

        return (
            `
               color : ${val};
            `
        )
    })

    cssHelper.add('.shopengine-checkout-form-shipping #ship-to-different-address > label > span', settings.shopengine_title_font_size, (val) => {

        return (
            `
               font-size : ${val}px;
            `
        )
    })


    cssHelper.add('.shopengine-checkout-form-shipping #ship-to-different-address > label', settings.shopengine_title_margin, (val) => {
        return (
            `
               margin : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
        )
    })

    if (settings.shopengine_hide_shipping_first_name_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_first_name_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_last_name_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_last_name_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_company_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_company_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_country_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_country_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_address_1_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_address_1_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_address_2_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_address_2_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_city_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_city_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_state_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_state_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }

    if (settings.shopengine_hide_shipping_postcode_field.desktop == 1) {
        cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields #shipping_postcode_field', {}, (val) => {
            return (
                `
                   display : none;
                `
            )
        })
    }


    cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper', settings.shopengine_container_alignment, (val) => {
        return (
            `
               text-align : ${val};
            `
        )
    })

    cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper', settings.shopengine_form_container_background, (val) => {
        return (
            `
               background-color : ${val};
            `
        )
    })

    cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper', settings.shopengine_form_container_padding, (val) => {
        return (
            `
               padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
        )
    })

    cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label', settings.shopengine_input_label_color, (val) => {
        return (
            `
               color : ${val};
            `
        )
    })

    cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label abbr', settings.shopengine_input_required_indicator_color, (val) => {
        return (
            `
               color : ${val};
               font-size: 14px;
            `
        )
    })

    cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label', settings.shopengine_label_font_size, (val) => {
        return (
            `
            font-size : ${val}px;
            `
        )
    })

    cssHelper.add('.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label', settings.shopengine_input_label_margin, (val) => {
        return (
            `
            margin : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
        )
    })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input::placeholder,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea::placeholder,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .woocommerce-input-wrapper,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select`
        , settings.shopengine_input_color, (val) => {
            return (
                `
            color : ${val};
            `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select`
        , settings.shopengine_input_background, (val) => {
            return (
                `
            background-color : ${val};
            `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select`
        , settings.shopengine_input_border_type, (val) => {
            return (
                `
            border-style : ${val};
            `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select`
        , settings.shopengine_input_border_width, (val) => {
            return (
                `
            border-width : ${val.top} ${val.right} ${val.bottom} ${val.left};
            `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select`
        , settings.shopengine_input_border_color, (val) => {
            return (
            `
                border-color : ${val};
            `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input:focus, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input::placeholder:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea::placeholder:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .woocommerce-input-wrapper:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select:focus`
        , settings.shopengine_input_color_focus, (val) => {
            return (
                `
                color : ${val};
                `
            )
        })


    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input:focus, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input::placeholder:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea::placeholder:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .woocommerce-input-wrapper:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select:focus`
        , settings.shopengine_input_background_focus, (val) => {
            return (
                `
                    background-color : ${val};
                `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input:focus, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select:focus`
        , settings.shopengine_input_border_focus_type, (val) => {
            return (
                `
                border-style : ${val};
                `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input:focus, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select:focus`
        , settings.shopengine_input_border_focus_width, (val) => {
            return (
                `
                border-width : ${val};
                `
            )
        })

    cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input:focus, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection:focus,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select:focus`
        , settings.shopengine_input_border_focus_border_color, (val) => {
            return (
                `
                border-color: ${val};
                `
            )
        })


        cssHelper.add(
        `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
                .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input::placeholder,
                .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea::placeholder,
                .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .woocommerce-input-wrapper,
                .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
                .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select`
        , settings.shopengine_input_font_size, (val) => {
            return (
                `
                    font-size : ${val}px;
                    `
            )
        })

        cssHelper.add(`
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
        .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select
        `, settings.shopengine_input_border_radius, (val) => {
            return (`
                border-radius: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
            `)
        })

        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper input, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper textarea,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .select2-selection,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper select`
            , settings.shopengine_input_padding, (val) => {
                return (
                    `
                padding : ${val.top} ${val.right} ${val.bottom} ${val.left};
                `
                )
            })

        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields > h3 label, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label`
            , settings.typography_primary_font_family, (val) => {
                return (
                    `
                font-family : ${val.family};
                `
                )
        })
        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields > h3 label, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label`
            , settings.typography_primary_font_weight, (val) => {
                return (
                    `
                font-weight : ${val};
                `
                )
        })
        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields > h3 label, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label`
            , settings.typography_primary_text_transform, (val) => {
                return (
                    `
                text-transform : ${val};
                `
                )
        })

        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields > h3 label, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row label`
            , settings.typography_primary_wordspace, (val) => {
                return (
                `
                word-spacing : ${val}px;
                `
                )
        })

        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row input, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row textarea,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row .select2-selection
            `
            , settings.typography_secondary_font_family, (val) => {
                return (
                    `
                font-family : ${val.family};
                `
                )
        })
        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row input, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row textarea,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row .select2-selection
            `
            , settings.typography_secondary_font_weight, (val) => {
                return (
                    `
                font-weight : ${val};
                `
                )
        })

        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row input, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row textarea,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row .select2-selection
            `
            , settings.typography_secondary_text_transform, (val) => {
                return (
                    `
                text-transform : ${val};
                `
                )
        })

        cssHelper.add(
            `.shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row input, 
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row textarea,
            .shopengine-checkout-form-shipping .woocommerce-shipping-fields__field-wrapper .form-row .select2-selection
            `
            , settings.typography_secondary_wordspace, (val) => {
                return (
                    `
                word-spacing : ${val}px;
                `
                )
        })





























    // cssHelper.add(`
    //     #${settings.blockId} .class-a{
    //         color: #ab0;
    //     }
    //     #${settings.blockId} .class-b{
    //         color: ${settings.color};
    //     }
    // `)

    return cssHelper.get()
}

/*
const Style = ({settings, breakpoints})=>{
    // cssHelper.media(`(min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px)`)
    // .add('.class-a', 'color', '#000')
    // .add('.class-b', 'color', settings.autocontrol1)

    var cssOutput = '';

    var cssOutput = `
        #${settings.blockId} .class-a{
            color: #ab0;
        }
        #${settings.blockId} .class-b{
            color: ${settings.color};
        }
    `

    // we can apply conditional styles and concat them
    cssOutput += `

    @media (min-width: ${breakpoints.small}px) and (max-width: ${(breakpoints.large - 1)}px){
        #${settings.blockId} .class-a{
            color: #000;
        }
        #${settings.blockId} .class-b{
            color: ${settings.autocontrol1};
        }
    }
    `

    return cssOutput
}
*/

export { Style }