
const Style = ({settings, breakpoints, cssHelper})=>{

   const getObjectValues = (obj) => {
       return [...Object.values(obj)].toString();
   }
   const getShadowValues = (obj) => {
       const position = obj.position ==='inset' ? obj.position : ''
       let propSet = getObjectValues(Object.fromEntries(Object.entries(obj).slice(1, Object.keys(obj).length - 1)));
       return `${position} ${propSet.split(',').join('px ')+'px'} rgba(${getObjectValues(obj.color.rgb).split(' ').join(',')})`;
   }

   cssHelper.add('.woocommerce-tabs ul.tabs', settings.shopengine_nav_padding, (val)=>(`         
       padding: ${getObjectValues(val).split(',').join(' ')};
    `))
    .add('.woocommerce-tabs ul.tabs', settings.shopengine_nav_border_color, (val)=>(`         
       border-color: ${val.hex};
    `))
    .add('.woocommerce-tabs ul.tabs', settings.shopengine_nav_border_width, (val)=>(`         
       border-width: ${getObjectValues(val)};
    `))
    .add('.woocommerce-tabs ul.tabs', settings.shopengine_nav_border_type, (val)=>(`         
       border-style: ${val};
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_font_size, (val)=>(`         
       font-size: ${val}px;
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_line_height, (val)=>(`         
       line-height: ${val}px;
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_letter_spacing, (val)=>(`         
       letter-spacing: ${val}px;
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_word_spacing, (val)=>(`         
       word-spacing: ${val}px;
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_transform, (val)=>(`         
       text-transform: ${val}px;
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_font_weight, (val)=>(`         
       font-weight: ${val};
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_menu_color, (val)=>(`         
       color: ${val.hex};
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_menu_bg_color, (val)=>(`         
       background: ${val.hex};
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_normal_border_color, (val)=>(`         
    border-color: ${val.hex};
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_normal_border_type, (val)=>(`         
       border-style: ${val};
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_normal_border_width, (val)=>(`         
       border-width: ${getObjectValues(val)};
    `))
    .add('.woocommerce-tabs ul.tabs li a',settings.shopengine_nav_item_padding, (val)=>(`         
       padding: ${getObjectValues(val).split(',').join(' ')};
    `))
    .add(`.woocommerce-tabs ul.tabs li.active a,
    .woocommerce-tabs ul.tabs li:hover a`,
    settings.shopengine_hover_menu_color, 
    (val)=>(`         
       color: ${val.hex};
    `))
    .add(`.woocommerce-tabs ul.tabs li.active a,
    .woocommerce-tabs ul.tabs li:hover a`,settings.shopengine_hover_background_color, (val)=>(`         
       background: ${val.hex};
    `))
    .add(`.woocommerce-tabs ul.tabs li.active a,
    .woocommerce-tabs ul.tabs li:hover a`,settings.shopengine_nav_hover_border_color, (val)=>(`         
       border-color: ${val.hex};
    `))
    .add('div.shopengine-product-tabs div.woocommerce-tabs .wc-tabs .shopengine-tabs-line',settings.shopengine_nav_indicator_color, (val)=>(`         
       border-color: ${val.hex};
    `))
    .add('div.shopengine-product-tabs div.woocommerce-tabs .wc-tabs .shopengine-tabs-line',settings.shopengine_nav_indicator_control, (val)=>(`         
       border-top-width: ${val.top};
       border-bottom-width: ${val.bottom};
       height: calc(100% + ${val.top} + ${val.bottom});
       top: -${val.top};
    `))
    .add('.woocommerce-tabs ul.tabs li a', settings.shopengine_nav_menu_spacing, (val)=>(`         
       margin-right: ${val ? val : 25}px;
    `))
    .add(`div.shopengine-product-tabs .woocommerce-Tabs-panel > h2:first-child,
         div.shopengine-product-tabs .woocommerce-Tabs-panel .comment-reply-title`,settings.shopengine_tab_show_title, (val)=>(`         
       display: ${!val && "none"};
    `))
    .add(`div.shopengine-product-tabs .woocommerce-Tabs-panel > h2:first-child,
         div.shopengine-product-tabs .woocommerce-Tabs-panel .comment-reply-title`,settings.shopengine_tab_show_title, (val)=>(`         
       display: ${!val && "none"};
    `))
    .add(`div.shopengine-product-tabs .woocommerce-Tabs-panel > h2:first-child,
         div.shopengine-product-tabs .woocommerce-Tabs-panel .comment-reply-title`,settings.shopengine_tab_content_color, (val)=>(`         
       color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs .woocommerce-Tabs-panel > h2:first-child,
         div.shopengine-product-tabs .woocommerce-Tabs-panel .comment-reply-title`,settings.shopengine_tab_title_padding, (val)=>(`         
         padding: ${getObjectValues(val).split(',').join(' ')};
    `))
    .add(`.shopengine-product-tabs .woocommerce-Tabs-panel`,settings.shopengine_tab_content_padding, (val)=>(`         
         padding: ${getObjectValues(val).split(',').join(' ')};
    `))
    .add(`.shopengine-product-tabs .woocommerce-Tabs-panel`,settings.shopengine_tab_content_padding, (val)=>(`         
         padding: ${getObjectValues(val).split(',').join(' ')};
    `))
    .add(`
    .shopengine-product-tabs tr td,
    .shopengine-product-tabs tr th,
    .shopengine-product-tabs tr p
    `,settings.shopengine_addi_info_font_size, (val)=>(`         
       font-size: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs tr td,
    .shopengine-product-tabs tr th,
    .shopengine-product-tabs tr p
    `,settings.shopengine_addi_info_line_height, (val)=>(`  
       line-height: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs tr td,
    .shopengine-product-tabs tr th,
    .shopengine-product-tabs tr p
    `,settings.shopengine_addi_info_letter_spacing, (val)=>(`
       letter-spacing: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs tr td,
    .shopengine-product-tabs tr th,
    .shopengine-product-tabs tr p)
    `,settings.shopengine_addi_info_word_spacing, (val)=>(`
       word-spacing: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs tr td,
    .shopengine-product-tabs tr th,
    .shopengine-product-tabs tr p
    `,settings.shopengine_addi_info_transform, (val)=>(`
       text-transform: ${val};
    `))
    .add(`
    .shopengine-product-tabs tr td,
    .shopengine-product-tabs tr th,
    .shopengine-product-tabs tr p
    `,settings.shopengine_addi_info_font_weight, (val)=>(`
       font-weight: ${val};
    `))
    .add(`.shopengine-product-tabs tr th`,settings.shopengine_cell_divider_color, (val)=>(`
        border-color: ${val.hex};
    `))
    .add(`
    .shopengine-product-tabs tr th,
    .shopengine-product-tabs tr td
    `,settings.shopengine_cell_padding, (val)=>(`
        padding: ${getObjectValues(val).split(',').join(' ')};
    `))
    .add(`.shopengine-product-tabs tr th`,settings.shopengine_label_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs tr th`,settings.shopengine_label_bg_color, (val)=>(`
        background-color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs tr th`,settings.shopengine_label_width, (val)=>(`
        width: ${val}%;
    `))
    .add(`.shopengine-product-tabs tr td,.shopengine-product-tabs tr td p`,settings.shopengine_value_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs tr td,.shopengine-product-tabs tr td p`,settings.shopengine_value_bg_color, (val)=>(`
        background-color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs #reviews .se-rating-container h2`,settings.shopengine_title_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs #reviews .se-rating-container h2`,settings.shopengine_title_font_size, (val)=>(`
    font-size: ${val}px;
    `))
    .add(`.shopengine-product-tabs #reviews .se-rating-container h2`,settings.shopengine_title_line_height, (val)=>(`
     line-height: ${val}px;
    `))
    .add(`.shopengine-product-tabs #reviews .se-rating-container h2`,settings.shopengine_title_word_spacing, (val)=>(`
    word-spacing: ${val}px;
    `))
    .add(`.shopengine-product-tabs #reviews .se-rating-container h2`,settings.shopengine_title_font_weight, (val)=>(`
    font-weight: ${val};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-rating`,settings.shopengine_total_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-rating`,settings.shopengine_total_font_size, (val)=>(`
    font-size: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-rating`,settings.shopengine_total_line_height, (val)=>(`
     line-height: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-rating`,settings.shopengine_total_word_spacing, (val)=>(`
    word-spacing: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-rating`,settings.shopengine_total_font_weight, (val)=>(`
    font-weight: ${val};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-count`,settings.shopengine_count_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-count`,settings.shopengine_count_font_size, (val)=>(`
    font-size: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-count`,settings.shopengine_count_line_height, (val)=>(`
     line-height: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-count`,settings.shopengine_count_word_spacing, (val)=>(`
    word-spacing: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-avg-count`,settings.shopengine_count_font_weight, (val)=>(`
    font-weight: ${val};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat span`,settings.shopengine_rating_ave_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat span`,settings.shopengine_rating_ave_font_size, (val)=>(`
    font-size: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat span`,settings.shopengine_rating_ave_line_height, (val)=>(`
     line-height: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat span`,settings.shopengine_rating_ave_word_spacing, (val)=>(`
    word-spacing: ${val}px;
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat span`,settings.shopengine_rating_ave_word_spacing, (val)=>(`
        text-transform: ${val};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat span`,settings.shopengine_rating_ave_font_weight, (val)=>(`
        font-weight: ${val};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat-cont`,settings.shopengine_rating_bg_color, (val)=>(`
    background-color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs .se-rating-container .se-ind-rat-cont span`,settings.shopengine_rating_active_bg_color, (val)=>(`
        background-color: ${val.hex};
    `))
    .add(`.shopengine-product-tabs #reviews .se-rating-container .se-ind-rat .se-ind-rat-cont`,settings.shopengine_rating_bar_width, (val)=>(`
        width: ${val}px;
        display: inline-block;
    `))
    .add(`
    .shopengine-product-tabs #reviews .se-rating-container .se-ind-rat .se-ind-rat-cont,
    .shopengine-product-tabs #reviews .se-rating-container .se-ind-rat .se-ind-rat-cont span`,settings.shopengine_rating_bar_height, (val)=>(`
    height: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_font_size, (val)=>(`
    font-size: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_line_height, (val)=>(`
     line-height: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_letter_spacing, (val)=>(`
    letter-spacing: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_word_spacing, (val)=>(`
    word-spacing: ${val}px;
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_transform, (val)=>(`
        text-transform: ${val};
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_font_weight, (val)=>(`
        font-weight: ${val};
    `))
    .add(`
    .shopengine-product-tabs .woocommerce-Reviews-title,
    .shopengine-product-tabs #review_form .comment-reply-title`,settings.shopengine_review_heading_margin, (val)=>(`
        margin: ${getObjectValues(val).split(',').join(' ')};
    `))
    .add(`
      div.shopengine-product-tabs #reviews .star-rating,
      div.shopengine-product-tabs #reviews .star-rating span,
      div.shopengine-product-tabs #reviews .star-rating span::before,
      div.shopengine-product-tabs #reviews .star-rating span::before,
      div.shopengine-product-tabs #reviews .star-rating::before,
      div.shopengine-product-tabs #reviews p.stars a,
      div.shopengine-product-tabs #reviews p.stars.selected a,
      div.shopengine-product-tabs #reviews p.stars:hover a,
      div.shopengine-product-tabs #reviews p.stars a::before,
      div.shopengine-product-tabs #reviews p.stars a.active~a::before,
      div.shopengine-product-tabs #reviews .se-rating-container .star-rating span,
      div.shopengine-product-tabs #reviews .se-rating-container .star-rating::before
    `
    ,settings.shopengine_rating_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`
     div.shopengine-product-tabs #reviews .commentlist > li .woocommerce-review__published-date, 
     div.shopengine-product-tabs #reviews .commentlist > li .description p, .woocommerce-review__author, 
     div.shopengine-product-tabs #reviews .commentlist > li .woocommerce-review__verified, 
     div.shopengine-product-tabs #reviews .commentlist > li .woocommerce-review__dash
    `
    ,settings.shopengine_date_author_des_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs #reviews #comments .commentlist li`,settings.shopengine_comment_sep_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs .woocommerce-review__author`,settings.shopengine_author_font_size, (val)=>(`
        font-size: ${val}px;
    `))
    .add(`div.shopengine-product-tabs .woocommerce-review__author`,settings.shopengine_author_word_spacing, (val)=>(`
    word-spacing: ${val}px;
    `))
    .add(`div.shopengine-product-tabs .woocommerce-review__author`,settings.shopengine_author_font_weight, (val)=>(`
    font-weight: ${val};
    `))
    .add(`div.shopengine-product-tabs .description p, 
    `,settings.shopengine_description_font_size, (val)=>(`
        font-size: ${val}px;
    `))
    .add(`div.shopengine-product-tabs .description p, 
    `,settings.shopengine_description_line_height, (val)=>(`
    line-height: ${val}px;
    `))
    .add(`div.shopengine-product-tabs .description p, 
    `,settings.shopengine_description_word_spacing, (val)=>(`
        word-spacing: ${val}px;
    `))
    .add(`div.shopengine-product-tabs .description p, 
    `,settings.shopengine_description_font_weight, (val)=>(`
       font-weight: ${val};
    `))
    .add(`div.shopengine-product-tabs #reviews #comments .commentlist li:not(:last-child),
          div.shopengine-product-tabs #reviews #comments .commentlist li:last-child`,settings.shopengine_review_spacing, (val)=>(`
          margin-bottom: ${val};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form label,
          div.shopengine-product-tabs #review_form #respond .comment-form .comment-notes`,settings.shopengine_input_label_color, (val)=>(`
          color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form label,
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-notes`,settings.shopengine_form_label_font_size, (val)=>(`
     font-size: ${val}px;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form label,
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-notes`,settings.shopengine_form_label_line_height, (val)=>(`
     line-height: ${val}px;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form label,
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-notes`,settings.shopengine_form_label_font_weight, (val)=>(`
       font-weight: ${val};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .required`,settings.shopengine_required_color, (val)=>(`
    color: ${val.hex};
    `))
    .add(` div.shopengine-product-tabs #review_form #respond .comment-form input:not([type=checkbox]),
    div.shopengine-product-tabs #review_form #respond .comment-form  textarea`,settings.shopengine_input_color, (val)=>(`
          color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form input:not([type=checkbox]),
    div.shopengine-product-tabs #review_form #respond .comment-form  textarea`,settings.shopengine_input_font_size, (val)=>(`
     font-size: ${val}px;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form input:not([type=checkbox]),
    div.shopengine-product-tabs #review_form #respond .comment-form  textarea`,settings.shopengine_input_line_height, (val)=>(`
     line-height: ${val}px;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form input:not([type=checkbox]),
    div.shopengine-product-tabs #review_form #respond .comment-form  textarea`,settings.shopengine_input_word_spacing, (val)=>(`
     word-spacing: ${val}px;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form input:not([type=checkbox]),
    div.shopengine-product-tabs #review_form #respond .comment-form  textarea`,settings.shopengine_input_font_weight, (val)=>(`
       font-weight: ${val};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form input:not([type=checkbox]),
    div.shopengine-product-tabs #review_form #respond .comment-form  textarea`,settings.shopengine_input_font_weight, (val)=>(`
       font-weight: ${val};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form textarea,
    div.shopengine-product-tabs #review_form #respond .comment-form input:not(.submit)`,settings.shopengine_border_color, (val)=>(`
    border-color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form textarea:focus,
    div.shopengine-product-tabs #review_form #respond .comment-form input:focus,
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-form-cookies-consent input::after
    `,settings.shopengine_focus_color, (val)=>(`
       border-color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond :is(.comment-form)`,settings.shopengine_focus_color, (val)=>(`
       margin: 0;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .comment-notes,
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-form-rating, 
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-form-comment, 
    div.shopengine-product-tabs #review_form #respond .comment-form.comment-form-author,
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-form-email,
    div.shopengine-product-tabs #review_form #respond .comment-form .comment-form-cookies-consent
    `,settings.shopengine_field_spacing, (val)=>(`
       margin: 0 0 ${val}px 0;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form textarea,
    div.shopengine-product-tabs #review_form #respond .comment-form input
    `,settings.shopengine_border_radius, (val)=>(`
       border-radius: ${val}px;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form textarea,
       div.shopengine-product-tabs #review_form #respond .comment-form input:not(#wp-comment-cookies-consent),
       div.shopengine-product-tabs #review_form #respond .comment-form .submit
    `,settings.shopengine_input_padding, (val)=>(`
       padding:  ${getObjectValues(val).split(',').join(' ')};
    `))
    cssHelper.add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit`,settings.shopengine_button_align, (val)=>(`
        text-align: ${val}  !important;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_font_size, (val)=>(`
     font-size: ${val}px !important;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_line_height, (val)=>(`
     line-height: ${val}px !important;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_word_spacing, (val)=>(`
     word-spacing: ${val}px ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_transform, (val)=>(`
     text-transform: ${val} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_font_weight, (val)=>(`
     font-weight: ${val} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_color, (val)=>(`
        color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_bg_color, (val)=>(`
        background-color: ${val.hex};
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_border_color, (val)=>(`
     border-color: ${val.hex} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_padding, (val)=>(`
       padding:  ${getObjectValues(val).split(',').join(' ')} ;
    `))     .
    add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_margin, (val)=>(`
       margin:  ${getObjectValues(val).split(',').join(' ')} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_border_width, (val)=>(`
       border-width:  ${getObjectValues(val).split(',').join(' ')} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_border_radius, (val)=>(`
       border-radius:  ${getObjectValues(val).split(',').join(' ')} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit`,settings.shopengine_button_border_type, (val)=>(`
       border-style:  ${val} ;
       float:none;
       cursor: pointer;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit:hover`,settings.shopengine_hover_button_color, (val)=>(`
       color: ${val.hex} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit:hover`,settings.shopengine_hover_button_bg_color, (val)=>(`
       background-color: ${val.hex} ;
    `))
    .add(`div.shopengine-product-tabs #review_form #respond .comment-form .form-submit input#submit:hover`,settings.shopengine_hover_button_border_color, (val)=>(`
       border-color: ${val.hex} ;
    `))



    cssHelper.add('.shopengine-product-tabs', settings.shopengine_product_tabs_font_family, (val) => {
     return `
      font-family: ${val.family};
     `
    } );
    
    cssHelper.add(' .shopengine-product-tabs a, .shopengine-product-tabs h2, .shopengine-product-tabs p, .shopengine-product-tabs input, .shopengine-product-tabs tr, .shopengine-product-tabs th, .shopengine-product-tabs td, .shopengine-product-tabs .woocommerce-Tabs-panel, .shopengine-product-tabs .comment-reply-title,', settings.shopengine_product_tabs_font_family, (val) => {
     return `
      font-family: ${val.family};
     `
    } );
   
   


   return cssHelper.get()
}

export {Style}