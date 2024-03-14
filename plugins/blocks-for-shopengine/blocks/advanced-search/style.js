
const Style = ({ settings, cssHelper }) => {
   const getObjectValues = (obj) => {
      return [...Object.values(obj)].toString();
   }

   cssHelper.add('.shopengine-advanced-search .shopengine-search-product', settings.shopengine_column_number, (val) => (`
        grid-template-columns: repeat(${val}, 1fr);
    `))
      .add('.shopengine-advanced-search .shopengine-category-select-wraper', settings.shopengine_display_type, (val) => (`
        display: ${val}
        `))
      .add('.shopengine-advanced-search :is(.shopengine-search-product__item--image)', settings.shopengine_display_image, (val) => (`         
        display: ${val ? "none" : "inline-block"}
     `))
      .add(`.shopengine-advanced-search .search-input-group button,
      .shopengine-advanced-search .search-input-group input, 
      .shopengine-advanced-search .search-input-group select 
      `, settings.shopengine_form_height, (val) => (`         
            height: ${val}px;
      `))
      .add('.shopengine-advanced-search .search-input-group', settings.shopengine_form_border_radius, (val) => (`         
      border-radius: ${getObjectValues(val).split(',').join(' ')};
      overflow:hidden;
      `))
      .add('.shopengine-advanced-search .search-input-group', settings.shopengine_form_input_border_type, (val) => (`         
      border-style: ${val}
      `))
      .add('.shopengine-advanced-search :is( .search-input-group )', settings.shopengine_form_input_border_width, (val) => (`         
      border-width: ${getObjectValues(val).split(',').join(' ')};
      border-color: #e6e6e6;
      `))
      .add(`.shopengine-advanced-search .search-input-group input,
       .shopengine-advanced-search .search-input-group input::placeholder
      `, settings.shopengine_form_txt_color, (val) => (`         
            color: ${val.hex};
      `))
      .add(`.shopengine-advanced-search .search-input-group :is(  input )`, settings.shopengine_form_bg_color, (val) => (`         
            background-color: ${val.hex};
      `))

      .add(`.shopengine-advanced-search .search-input-group input,
       .shopengine-advanced-search .search-input-group input::placeholder
      `, settings.shopengine_form_font_size, (val) => (`         
          font-size: ${val}px;
       `))
      .add(`.shopengine-advanced-search .search-input-group input,
       .shopengine-advanced-search .search-input-group input::placeholder
      `, settings.shopengine_form_word_spacing, (val) => (`         
          word-spacing: ${val}px;
       `))
      .add(`.shopengine-advanced-search .search-input-group input,
       .shopengine-advanced-search .search-input-group input::placeholder
      `, settings.shopengine_form_transform, (val) => (`         
          text-transform: ${val};
       `))
      .add(`.shopengine-advanced-search .search-input-group input,
       .shopengine-advanced-search .search-input-group input::placeholder
      `, settings.shopengine_form_font_weight, (val) => (`         
          font-weight: ${val};
       `))
      .add(`.shopengine-advanced-search .search-input-group :is(  button ) i,
       .shopengine-search-text`, settings.shopengine_search_txt_color, (val) => (`         
          color: ${val.hex};
       `))
      .add(`.shopengine-advanced-search .search-input-group button,
       .shopengine-advanced-search .search-input-group`, settings.shopengine_search_txt_bg_color, (val) => (`         
          background-color: ${val.hex};
       `))
      .add(`.shopengine-advanced-search .search-input-group button:hover,
       .shopengine-advanced-search .search-input-group:hover`, settings.shopengine_hover_search_text_bg_color, (val) => (`         
          background-color: ${val.hex};
       `))
      .add(`.shopengine-advanced-search .search-input-group :is(  button ):hover *, 
       .shopengine-search-text:hover`, settings.shopengine_hover_search_text_color, (val) => (`         
          color: ${val.hex};
       `))
      .add(`.shopengine-advanced-search .search-input-group :is(  button ) i, 
       .shopengine-search-text`, settings.shopengine_search_txt_font_size, (val) => (`         
          font-size: ${val}px;
       `))
      .add(`.shopengine-advanced-search .search-input-group :is(  button ) i, 
       .shopengine-search-text`, settings.shopengine_search_txt_font_size, (val) => (`         
          width: ${val}px;
       `))
      .add(`.shopengine-advanced-search .search-input-group :is(  button )`, settings.shopengine_search_button_width, (val) => (`         
            flex: 0 0 ${val}px;
       `))
      .add(`.shopengine-search-text`, settings.shopengine_search_text_gap, (val) => (`         
            margin-left: ${val}px;
       `))
      .add('.shopengine-advanced-search .search-input-group :is( select )', settings.shopengine_category_color, (val) => (`         
           color: ${val.hex};
        `))
      .add('.shopengine-category-select-wraper', settings.shopengine_category_bg_color, (val) => (`         
           background-color: ${val.hex};
        `))
      .add('.shopengine-ele-nav-search-select', settings.shopengine_category_bg_color, (val) => (`         
           background-color: ${val.hex};
        `))
      .add(`.shopengine-advanced-search .search-input-group :is( select )
       `, settings.shopengine_category_font_size, (val) => (`         
           font-size: ${val}px;
        `))
      .add(`.shopengine-advanced-search .search-input-group :is( select )
       `, settings.shopengine_category_line_height, (val) => (`         
           line-height: ${val}px;
        `))
      .add(`.shopengine-advanced-search .search-input-group :is( select )
       `, settings.shopengine_category_word_spacing, (val) => (`         
           word-spacing: ${val}px;
        `))
      .add(`.shopengine-advanced-search .search-input-group :is( select )
       `, settings.shopengine_category_transform, (val) => (`         
           text-transform: ${val};
        `))
      .add(`.shopengine-advanced-search .search-input-group :is( select )
       `, settings.shopengine_category_font_weight, (val) => (`         
           font-weight: ${val};
        `))
      .add('.shopengine-ele-nav-search-select', settings.shopengine_category_width, (val) => (`         
           width: ${val}px;
        `))
      .add('.shopengine-ele-nav-search-select > option', settings.shopengine_drpdown_color, (val) => (`         
           color: ${val.hex};
        `))
      .add('.shopengine-ele-nav-search-select > option', settings.shopengine_drpdown_bg_color, (val) => (`         
           background-color: ${val};
        `))
      .add('.shopengine-category-select-wraper:before', settings.shopengine_sep_position, (val) => (`         
           left: ${val === "left" ? 0 : "auto"};
           right: ${val === "left" ? "auto" : "0"};
        `))
      .add('.shopengine-category-select-wraper:before', settings.shopengine_sep_width, (val) => (`         
        border-width: ${val}px;
        `))
      .add('.shopengine-category-select-wraper:before', settings.shopengine_sep_height, (val) => (`         
        height: ${val}%;
        `))
      .add('.shopengine-category-select-wraper:before', settings.shopengine_sep_color, (val) => (`         
        border-color: ${val.hex};
        `))
      .add('.shopengine-search-result-container', settings.shopengine_left_space, (val) => (`         
        left: ${val}px;
        width: calc(100% - ${val}px);
        top: calc(100% - 1px);
        `))
      .add('.shopengine-advanced-search .shopengine-search-product__item--title a', settings.shopengine_title_color, (val) => (`         
            color: ${val.hex};
        `))
      .add('.shopengine-advanced-search .shopengine-search-product__item--title a:hover', settings.shopengine_title_hover_color, (val) => (`         
            color: ${val.hex};
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--title a
       `, settings.shopengine_title_font_size, (val) => (`         
           font-size: ${val}px;
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--title a
       `, settings.shopengine_title_line_height, (val) => (`         
           line-height: ${val}px;
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--title a
       `, settings.shopengine_title_word_spacing, (val) => (`         
           word-spacing: ${val}px;
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--title a
       `, settings.shopengine_title_transform, (val) => (`         
           text-transform: ${val};
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--title a
       `, settings.shopengine_title_font_weight, (val) => (`         
           font-weight: ${val};
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--price ins .amount`, settings.shopengine_reg_price_color, (val) => (`         
           color: ${val.hex};
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--price del .amount`, settings.shopengine_sale_price_color, (val) => (`         
           color: ${val.hex};
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--price .amount
       `, settings.shopengine_price_font_size, (val) => (`         
           font-size: ${val}px;
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--price .amount
        `, settings.shopengine_price_word_spacing, (val) => (`         
        word-spacing: ${val}px;
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item--price .amount
        `, settings.shopengine_price_font_weight, (val) => (`         
        font-weight: ${val};
        `))
      .add(`.shopengine-advanced-search .shopengine-product-rating .star-rating,
         .shopengine-advanced-search .shopengine-product-rating .rating-count
       `, settings.shopengine_rating_font_size, (val) => (`         
           font-size: ${val}px;
        `))
      .add(`.shopengine-product-rating .star-rating::before
        `, settings.shopengine_star_color, (val) => (`         
           color: ${val.hex};
        `))
      .add(`.shopengine-product-rating .rating-count
        `, settings.shopengine_counter_color, (val) => (`         
           color: ${val.hex};
        `))
      .add(`.shopengine-search-more-btn
        `, settings.shopengine_icon_color, (val) => (`         
           color: ${val.hex};
        `))

      .add(`.shopengine-widget .shopengine-advanced-search .shopengine-search-product__item:hover .shopengine-search-more-btn
        `, {}, (val) => (`         
           color: #fff;
        `))
      .add(`.shopengine-advanced-search .shopengine-search-more-btn
        `, settings.shopengine_icon_bg_color, (val) => (`         
           background-color: ${val.hex};
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item:hover .shopengine-search-more-btn
        `, settings.shopengine_icon_hover_bg_color, (val) => (`         
           background-color: ${val.hex};
        `))
      .add(`.shopengine-advanced-search .shopengine-search-product__item
        `, settings.shopengine_wrapper_padding, (val) => (`         
            padding: ${getObjectValues(val).split(',').join(' ')};
        `))
      .add(`.shopengine-advanced-search .shopengine-product-search-result,
        .shopengine-advanced-search .shopengine-search-product__item`, settings.shopengine_item_border_color, (val) => (`         
            border-color: ${val.hex};
        `))
      .add(`.shopengine-advanced-search .shopengine-product-search-result,
        .shopengine-advanced-search .shopengine-search-product__item`, settings.shopengine_item_border_type, (val) => (`         
            border-style: ${val}
        `))
      .add(`.shopengine-advanced-search .shopengine-product-search-result,
        .shopengine-advanced-search .shopengine-search-product__item`, settings.shopengine_item_border_width, (val) => (`         
            border-width: ${getObjectValues(val).split(',').join(' ')};
        `))
      .add(`.shopengine-search-more-products`, settings.shopengine_more_font_size, (val) => (`         
            font-size: ${val}px;
        `))
      .add(`.shopengine-search-more-products`, settings.shopengine_more_button_color, (val) => (`         
            color: ${val.hex};
        `))
      .add(`.shopengine-search-more-products:hover`, settings.shopengine_more_button_hover_color, (val) => (`         
            color: ${val.hex};
        `));


   cssHelper.add(`.shopengine-advanced-search .search-input-group input, 
                .shopengine-advanced-search .search-input-group input::placeholder,
                .shopengine-advanced-search .shopengine-search-text,
                .shopengine-advanced-search .shopengine-product-rating .rating-count,
                .shopengine-advanced-search .search-input-group select,
                .shopengine-advanced-search .shopengine-search-product__item--title,
                .shopengine-advanced-search .shopengine-search-product__item--price`, settings.shopengine_global_font_family, (val) => (`
        font-family: ${val.family};
        `))


   return cssHelper.get()
}


export { Style }