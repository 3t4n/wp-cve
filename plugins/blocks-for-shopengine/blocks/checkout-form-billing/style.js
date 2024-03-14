
const Style = ({settings, breakpoints, cssHelper})=>{
    const {blockId, shopengine_form_title_color, shopengine_form_title_margin, shopengine_form_title_font_size, shopengine_form_container_color,shopengine_form_container_alignment, shopengine_form_container_padding, shopengine_label_color, shopengine_label_required_color, shopengine_label_font_size, shopengine_label_margin, shopengine_input_color, shopengine_input_font_size, shopengine_input_bg, shopengine_input_border_style,shopengine_input_border_width,shopengine_input_margin, shopengine_input_border_color,shopengine_input_padding, shopengine_input_focus_color, shopengine_input_focus_bg,shopengine_input_focus_border_style, font_family, shopengine_form_label_font_weight, shopengine_form_label_word_spacing, shopengine_form_label_text_transform, font_family2, shopengine_form_label_font_weight2, shopengine_form_label_word_spacing2, shopengine_form_label_text_transform2} = settings;

    const shopengine_form_title_show = settings.shopengine_form_title_show.desktop === true ? "block" : "none";
    const shopengine_first_name_hide = settings.shopengine_first_name_hide.desktop === false ? "block" : "none";
    const shopengine_last_name_hide = settings.shopengine_last_name_hide.desktop === false ? "block" : "none";
    const shopengine_company_name_hide = settings.shopengine_company_name_hide.desktop === false ? "block" : "none";
    const shopengine_country_name_hide = settings.shopengine_country_name_hide.desktop === false ? "block" : "none";
    const shopengine_street_address1_hide = settings.shopengine_street_address1_hide.desktop === false ? "block" : "none";
    const shopengine_street_address2_hide = settings.shopengine_street_address2_hide.desktop === false ? "block" : "none";
    const shopengine_town_hide = settings.shopengine_town_hide.desktop === false ? "block" : "none";
    const shopengine_state_hide = settings.shopengine_state_hide.desktop === false ? "block" : "none";
    const shopengine_zip_hide = settings.shopengine_zip_hide.desktop === false ? "block" : "none";
    const shopengine_phone_hide = settings.shopengine_phone_hide.desktop === false ? "block" : "none";
    const shopengine_email_hide = settings.shopengine_email_hide.desktop === false ? "block" : "none";


    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > h3',{},(val) => {
        return `
            display: ${shopengine_form_title_show};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > h3',shopengine_form_title_color,(val) => {
        return `
            color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > h3',shopengine_form_title_margin,(val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > h3',shopengine_form_label_font_weight,(val) => {
        return `
            font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > h3',font_family,(val) => {
        return `
            font-family: ${val.family};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > h3',shopengine_form_label_word_spacing,(val) => {
        return `
        word-spacing: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > h3',shopengine_form_label_text_transform,(val) => {
        return `
        text-transform: ${val};
        `
    } );

    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields > .shopengine-billing-address-header',shopengine_form_title_font_size,(val) => {
        return `
            font-size: ${val}px !important;
        `
    } );

    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row .select2-selection',font_family2, (val) => {
        return `
            font-family: ${val.family};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row .select2-selection',shopengine_form_label_font_weight2, (val) => {
        return `
        font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row .select2-selection',shopengine_form_label_word_spacing2, (val) => {
        return `
            word-spacing: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row .select2-selection',shopengine_form_label_text_transform2, (val) => {
        return `
            text-transform: ${val};
        `
    } );
    
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_first_name_field',{},(val) => {
        return `
            display: ${shopengine_first_name_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_last_name_field',{},(val) => {
        return `
            display: ${shopengine_last_name_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_company_field',{},(val) => {
        return `
            display: ${shopengine_company_name_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_country_field',{},(val) => {
        return `
            display: ${shopengine_country_name_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_address_1_field',{},(val) => {
        return `
            display: ${shopengine_street_address1_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_address_2_field',{},(val) => {
        return `
            display: ${shopengine_street_address2_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_city_field',{},(val) => {
        return `
            display: ${shopengine_town_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_state_field',{},(val) => {
        return `
            display: ${shopengine_state_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_postcode_field',{},(val) => {
        return `
            display: ${shopengine_zip_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_phone_field',{},(val) => {
        return `
            display: ${shopengine_phone_hide};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields #billing_email_field',{},(val) => {
        return `
            display: ${shopengine_email_hide};
        `
    } );

    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper', shopengine_form_container_color, (val) => {
        return `
            background-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper', shopengine_form_container_alignment, (val) => {
        return `
            text-align: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper', shopengine_form_container_padding, (val) => {
        return(`
            padding: ${val.top}  ${val.right}  ${val.bottom}  ${val.left};
        `)
    } );


    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label', shopengine_label_margin, (val) => {
        return `
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label', shopengine_label_font_size, (val) => {
        return `
            font-size: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label', shopengine_label_color, (val) => {
        return `
            color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label',font_family, (val) => {
        return `
            font-family: ${val.family};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label',shopengine_form_label_font_weight, (val) => {
        return `
        font-weight: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label',shopengine_form_label_word_spacing, (val) => {
        return `
            word-spacing: ${val}px;
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label',shopengine_form_label_text_transform, (val) => {
        return `
            text-transform: ${val};
        `
    } );

    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .form-row label abbr', shopengine_label_required_color, (val) => {
        return `
            color: ${val};
        `
    } );


    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select', shopengine_input_color, (val) => {
        return `
            color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select', shopengine_input_bg, (val) => {
        return `
            background-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select', shopengine_input_font_size, (val) => {
        return `
            font-size: ${val}px !important;
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select', shopengine_input_padding, (val) => {
        return `
            padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );

    cssHelper.add(`
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input,
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea,
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper .select2-selection,
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select
    `, settings.shopengine_checkout_form_billing_input_border_radius, (val) => {
        return `
            border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );

    cssHelper.add(`
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input::placeholder,
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea::placeholder,
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper .select2-selection::placeholder,
    .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select::placeholder
    `, settings.shopengine_input_color_placeholder, (val) => {
        return `
           color: ${val};
        `
    } );


    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper .select2-selection,.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select', shopengine_input_border_style, (val) => {
        return `
            border-style: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper .select2-selection,.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select', shopengine_input_border_width, (val) => {
        return `
            border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
        `
    } );

    cssHelper.add('.shopengine-widget .shopengine-checkout-form-billing .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper .form-row', shopengine_input_margin, (val) => {
        return `
            margin-bottom: ${val.bottom} !important;
        `
    } );

    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper .select2-selection,.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select', shopengine_input_border_color, (val) => {
        return `
        border-color: ${val} !important;
        `
    } );

    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select:focus', shopengine_input_focus_bg, (val) => {
        return `
            background-color: ${val};
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select:focus', shopengine_input_focus_border_style, (val) => {
        return `
            border-style: ${val};
        `
    } );

    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select:focus', settings.shopengine_input_focus_border_color, (val) => {
        return `
            border-color: ${val} !important;
        `
    } );
    cssHelper.add('.shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper input:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper textarea:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper .select2-selection:focus, .shopengine-checkout-form-billing .woocommerce-billing-fields__field-wrapper select:focus', shopengine_input_focus_color, (val) => {
        return `
            color: ${val};
        `
    } );

    

    

    
    

    return cssHelper.get()
}


export {Style}