
const Style = ({ settings, cssHelper }) => {

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb, .shopengine-breadcrumbs i', settings.shopengine_breadcrumbs_text_color, (val) => (`
            color: ${val};
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb a', settings.shopengine_breadcrumbs_link_color, (val) => (`
            color: ${val};
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb a:hover', settings.shopengine_breadcrumbs_link_hover_color, (val) => (`
            color: ${val};
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_text_typography_font_family, (val) => (`
            font-family: ${val.family};
     `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_text_typography_font_size, (val) => (`
            font-size: ${val}px;
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_text_typography_font_weight, (val) => (`
            font-weight: ${val};
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_text_typography_text_transform, (val) => (`
            text-transform: ${val};
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_text_typography_text_style, (val) => (`
            font-style: ${val};
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_text_typography_line_height, (val) => (`
            line-height: ${val}px;
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_text_typography_word_spacing, (val) => (`
            word-spacing: ${val}px;
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_alignment, (val) => (`
   
   justify-content: ${val};
        `));

     cssHelper.add('.shopengine-breadcrumbs i, .shopengine-breadcrumbs .divider,.shopengine-breadcrumbs .delimeter', settings.shopengine_breadcrumbs_icon_size, (val) => (`
   font-size: ${val}px;
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb i', settings.shopengine_breadcrumbs_space_between, (val) => (`
   margin: 0 ${val}px;
        `));

     cssHelper.add('.shopengine-breadcrumbs .woocommerce-breadcrumb', settings.shopengine_breadcrumbs_space_between, (val) => (`
   margin: 0;
        `));

     return cssHelper.get()
}


export { Style };




