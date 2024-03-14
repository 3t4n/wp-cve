const Style = ({ settings, cssHelper }) => {

  cssHelper.add('.shopengine-tags, .product-tags-dummy', settings.shopengine_alignment, (val) => (`
    text-align: ${val};
    display: block;
    `)).add('.shopengine-tags', settings.shopengine_font_family, (val) => (`
    font-family: ${val.family};
    `)).add('.shopengine-tags', settings.shopengine_font_size, (val) => (`
    font-size: ${val}px;
    `)).add('.shopengine-tags', settings.shopengine_font_weight, (val) => (`
    font-weight: ${val};
    `)).add('.shopengine-tags', settings.shopengine_transform, (val) => (`
    text-transform: ${val};
    `)).add('.shopengine-tags', settings.shopengine_line_height, (val) => (`
    line-height: ${val}px;
    `)).add('.shopengine-tags', settings.shopengine_word_spacing, (val) => (`
    word-spacing: ${val}px;
    `));

  cssHelper.add('.shopengine-tags .product-tags-label, .shopengine-tags', settings.shopengine_label_color, (val) => (`
    color: ${val};
   `)).add('.shopengine-tags .product-tags-label, .shopengine-tags', settings.shopengine_label_decoration, (val) => (`
   text-decoration: ${val};
  `));

  cssHelper.add('.shopengine-tags .product-tags-links', settings.shopengine_link_color, (val) => (`
    color: ${val};
   `)).add('.shopengine-tags .product-tags-links a', settings.shopengine_link_color, (val) => (`
   color: ${val};
  `));

  cssHelper.add('.shopengine-tags .product-tags-links a:hover', settings.shopengine_link_hover_color, (val) => (`
    color: ${val};
   `));

  return cssHelper.get()
}


export { Style };
