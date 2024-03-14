const Style = ({ settings, breakpoints, cssHelper }) => {
  
   
   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_heading_color, (val)=>{
      return(`
         color: ${val};
      `)
   });

   //Reviews-title Typography
   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_heading_font_size, (val) => {
      return(`
         font-size: ${val}px;
      `)
   });

   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_heading_font_weight, (val) => {
      return(`
         font-weight: ${val};
      `)
   });
   
   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_heading_text_transform, (val) => {
      return(`
         text-transform: ${val};
      `)
   });

   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_heading_Line_height, (val) => {
      return(`
         line-height: ${val}px;
      `)
   });
   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_heading_letter_spacing, (val) => {
      return(`
         letter-spacing: ${val}px;
      `)
   });
   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_heading_word_spacing, (val) => {
      return(`
         word-spacing: ${val}px;
      `)
   });

   cssHelper.add(`
   .shopengine-product-review #reviews #comments .woocommerce-Reviews-title, .shopengine-product-review #review_form .comment-reply-title
   `, settings.shopengine_product_review_title_margin,(val) => {
      return (`
            margin: ${val.top} ${val.right} ${val.bottom} ${val.left};
            padding: 0;
        `) 
   });

  //Color

   cssHelper.add(`
   div.shopengine-product-review #reviews .star-rating,
   div.shopengine-product-review #reviews .star-rating span,
   div.shopengine-product-review #reviews .star-rating span::before,
   div.shopengine-product-review #reviews .star-rating::before,
   div.shopengine-product-review #reviews p.stars a,
   div.shopengine-product-review #reviews p.stars.selected a,
   div.shopengine-product-review #reviews p.stars:hover a,
   div.shopengine-product-review #reviews p.stars a::before,
   div.shopengine-product-review #reviews p.stars a.active~a::before,
   div.shopengine-product-review #reviews .se-rating-container .star-rating span,
   div.shopengine-product-review #reviews .se-rating-container .star-rating::before
   `, settings.shopengine_product_review_rating_color, (val)=>{
      return(`
         color: ${val};
      `)
   });
   
   cssHelper.add(`
   div.shopengine-product-review .woocommerce-review__published-date,
   div.shopengine-product-review .description p,
   div.shopengine-product-review .woocommerce-review__author,
   div.shopengine-product-review .woocommerce-review__verified,
   div.shopengine-product-review .woocommerce-review__dash
   `, settings.shopengine_product_review_date_color,(val) => {
      return (`
         color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #reviews #comments .commentlist li:not(:last-child)
   `, settings.shopengine_product_review_separator_color,(val) => {
      return (`
         border-color: ${val};
      `) 
   });

   //Author Typography
   cssHelper.add(`
   div.shopengine-product-review .woocommerce-review__author
   `, settings.shopengine_product_review_author_font_size,(val) => {
      return (`
         font-size: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review .woocommerce-review__author
   `, settings.shopengine_product_review_author_font_weight,(val) => {
      return (`
         font-weight: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review .woocommerce-review__author
   `, settings.shopengine_product_review_author_word_spacing,(val) => {
      return (`
         word-spacing: ${val}px;
      `) 
   });

   //Date Typography
   cssHelper.add(`
   div.shopengine-product-review .woocommerce-review__published-date,
   div.shopengine-product-review .woocommerce-review__dash,
   div.shopengine-product-review .woocommerce-review__verified
   `, settings.shopengine_product_review_date_font_size,(val) => {
      return (`
         font-size: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review .woocommerce-review__published-date,
   div.shopengine-product-review .woocommerce-review__dash,
   div.shopengine-product-review .woocommerce-review__verified
   `, settings.shopengine_product_review_date_font_weight,(val) => {
      return (`
         font-weight: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review .woocommerce-review__published-date,
   div.shopengine-product-review .woocommerce-review__dash,
   div.shopengine-product-review .woocommerce-review__verified
   `, settings.shopengine_product_review_date_word_spacing,(val) => {
      return (`
         word-spacing: ${val}px;
      `) 
   });
   
   //Description Typography
   cssHelper.add(`
   div.shopengine-product-review .description p
   `, settings.shopengine_product_review_description_font_size,(val) => {
      return (`
         font-size: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review .description p
   `, settings.shopengine_product_review_description_font_weight,(val) => {
      return (`
         font-weight: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review .description p
   `, settings.shopengine_product_review_description_Line_height,(val) => {
      return (`
         line-height: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review .description p
   `, settings.shopengine_product_review_description_word_spacing,(val) => {
      return (`
         word-spacing: ${val}px;
      `) 
   });
   //Single Space
   cssHelper.add(`
   div.shopengine-product-review #reviews #comments .commentlist li:not(:last-child)
   `, settings.shopengine_product_review_single_spacing,(val) => {
      return (`
         margin-bottom: ${val}px;
         padding-bottom: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #reviews #comments .commentlist li:last-child
   `, settings.shopengine_product_review_single_spacing,(val) => {
      return (`
         margin-bottom: ${val}px;
         border: none;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form label,
   div.shopengine-product-review #review_form #respond .comment-form .comment-notes
   `, settings.shopengine_product_review_label_clr,(val) => {
      return (`
         color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .required
   `, settings.shopengine_product_review_label_required,(val) => {
      return (`
         color: ${val};
      `) 
   });

   //Review label typography
   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form label,
   div.shopengine-product-review #review_form #respond .comment-form .comment-notes
   `, settings.shopengine_product_review_label_font_size,(val) => {
      return (`
         font-size: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form label,
   div.shopengine-product-review #review_form #respond .comment-form .comment-notes
   `, settings.shopengine_product_review_label_font_weight,(val) => {
      return (`
         font-weight: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form label,
   div.shopengine-product-review #review_form #respond .comment-form .comment-notes
   `, settings.shopengine_product_review_label_Line_height,(val) => {
      return (`
         line-height: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form label,
   div.shopengine-product-review #review_form #respond .comment-form .comment-notes
   `, settings.shopengine_product_review_label_letter_spacing,(val) => {
      return (`
         letter-spacing: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form label,
   div.shopengine-product-review #review_form #respond .comment-form .comment-notes
   `, settings.shopengine_product_review_label_word_spacing,(val) => {
      return (`
         word-spacing: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form input:not([type=checkbox]),
   div.shopengine-product-review #review_form #respond .comment-form textarea
   `, settings.shopengine_product_review_input_clr,(val) => {
      return (`
         color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form input:not(.submit),
   div.shopengine-product-review #review_form #respond .comment-form textarea
   `, settings.shopengine_product_review_input_border_clr,(val) => {
      return (`
         border-color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form textarea:focus,
   div.shopengine-product-review #review_form #respond .comment-form input:focus,
   div.shopengine-product-review #review_form #respond .comment-form .comment-form-cookies-consent input::after
   `, settings.shopengine_product_review_input_border_focus_clr,(val) => {
      return (`
         border-color: ${val};
      `) 
   });
   
   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form input:not([type=checkbox]),
   div.shopengine-product-review #review_form #respond .comment-form textarea
   `, settings.shopengine_review_label_input_font_size,(val) => {
      return (`
         font-size: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form input:not([type=checkbox]),
   div.shopengine-product-review #review_form #respond .comment-form textarea
   `, settings.shopengine_review_label_input_font_weight,(val) => {
      return (`
         font-weight: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form input:not([type=checkbox]),
   div.shopengine-product-review #review_form #respond .comment-form textarea
   `, settings.shopengine_review_label_input_Line_height,(val) => {
      return (`
         line-height: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form input:not([type=checkbox]),
   div.shopengine-product-review #review_form #respond .comment-form textarea
   `, settings.shopengine_review_label_input_word_spacing,(val) => {
      return (`
         word-spacing: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond :is(.comment-form)
   `, settings.shopengine_product_review_field_spacing,(val) => {
      return (`
         margin: 0;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .comment-notes,
   div.shopengine-product-review #review_form #respond .comment-form .comment-form-rating,
   div.shopengine-product-review #review_form #respond .comment-form .comment-form-comment,
   div.shopengine-product-review #review_form #respond .comment-form .comment-form-author,
   div.shopengine-product-review #review_form #respond .comment-form .comment-form-email,
   div.shopengine-product-review #review_form #respond .comment-form .comment-form-cookies-consent
   `, settings.shopengine_product_review_field_spacing,(val) => {
      return (`
         margin: 0 0 ${val}px 0;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form textarea,
   div.shopengine-product-review #review_form #respond .comment-form input
   `, settings.shopengine_product_review_input_border_radius,(val) => {
      return (`
         border-radius: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form textarea,
   div.shopengine-product-review #review_form #respond .comment-form input:not(#wp-comment-cookies-consent)
   div.shopengine-product-review #review_form #respond .comment-form input:not(.submit)
   `, settings.shopengine_product_review_input_padding,(val) => {
      return (`
         padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `) 
   });

   // Button Typograpy
   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_font_size,(val) => {
      return (`
         font-size: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_font_weight,(val) => {
      return (`
         font-weight: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_text_transform,(val) => {
      return (`
         text-transform: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_Line_height,(val) => {
      return (`
         line-height: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_word_spacing,(val) => {
      return (`
         word-spacing: ${val}px;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit,
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_alignment,(val) => {
      return (`
         text-align: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_color,(val) => {
      return (`
         color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_bg,(val) => {
      return (`
         background-color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit:hover
   `, settings.shopengine_product_review_submit_button_hover_color,(val) => {
      return (`
         color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit:hover
   `, settings.shopengine_product_review_submit_button_hover_bg,(val) => {
      return (`
         background-color: ${val};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit:hover
   `, settings.shopengine_product_review_submit_button_hover_border_color,(val) => {
      return (`
         border-color: ${val} !important;
      `) 
   });
   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_border_type,(val) => {
      return (`
         border-style: ${val} !important;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_border_width,(val) => {
      return (`
         border-width: ${val.top} ${val.right} ${val.bottom} ${val.left} !important;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_border_color,(val) => {
      return (`
         border-color: ${val} !important;
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_border_radius,(val) => {
      return (`
         border-radius: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `) 
   });

   cssHelper.add(`
   div.shopengine-product-review #review_form #respond .comment-form .form-submit input#submit
   `, settings.shopengine_product_review_submit_button_padding,(val) => {
      return (`
         padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
      `) 
   });

   cssHelper.add(`
   .shopengine-product-review,
   .shopengine-product-review #reviews a,
   .shopengine-product-review #reviews h2,
   .shopengine-product-review #reviews p,
   .shopengine-product-review #reviews input,
   .shopengine-product-review #reviews .meta,
   .shopengine-product-review #reviews span,
   .shopengine-product-review #reviews em,
   .shopengine-product-review #reviews time,
   .shopengine-product-review #reviews .submit,
   .shopengine-product-review #reviews .woocommerce-Reviews-title,
   .shopengine-product-review #reviews .comment-reply-title
   `, settings.shopengine_product_review_font_family,(val) =>{
      return (`
         font-family: ${val.family};
      `) 
   });

  return cssHelper.get();
};

export { Style };
