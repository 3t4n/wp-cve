const Style = ({ settings, breakpoints, cssHelper }) => {


  cssHelper.add(
    ".shopengine-account-dashboard p",
    settings.shopengine_account_dashboard_text_color,
    (val) => `
            margin:0 !important;
            color: ${val};
        `
  );
  cssHelper.add(
    `.shopengine-account-dashboard p,
    .shopengine-account-dashboard a`,
    settings.shopengine_account_dashboard_text_link_font_weight,
    (val) => `
       font-weight: ${val};
        `
  );

  cssHelper.add(
   ".shopengine-account-dashboard p strong",
   settings.shopengine_account_dashboard_user_color,
   (val) => `
           color: ${val};
       `
  );
  cssHelper.add(
   `.shopengine-account-dashboard p strong`,
   settings.shopengine_account_dashboard_user_font_weight,
   (val) => `
      font-weight: ${val};
       `
  );


  cssHelper.add(
   ".shopengine-account-dashboard p a",
   settings.shopengine_account_dashboard_link_color,
   (val) => `
           color: ${val};
       `
  );

  cssHelper.add(
   ".shopengine-account-dashboard p a:hover",
   settings.shopengine_account_dashboard_link_hover_color,
   (val) => `
           color: ${val};
       `
  );

  cssHelper.add(
   ".shopengine-account-dashboard p a",
   settings.shopengine_account_dashboard_link_text_decoration,
   (val) => `
      text-decoration: ${val};
       `
  );




      cssHelper.add(
         `.shopengine-account-dashboard p`,
         settings.shopengine_account_dashboard_font_family,
         (val) => `
            font-family: ${val.family};

         `
      );

      cssHelper.add(
         `.shopengine-account-dashboard p`,
         settings.shopengine_account_dashboard_font_size,
         (val) => `
            font-size: ${val};
         `
      );
      cssHelper.add(
         `.shopengine-account-dashboard p`,
         settings.shopengine_account_dashboard_text_transform,
         (val) => `
            text-transform: ${val};
         `
      );
      cssHelper.add(
         `.shopengine-account-dashboard p`,
         settings.shopengine_account_dashboard_line_height,
         (val) => `
            line-height: ${val}px;
         `
      );
      cssHelper.add(
         `.shopengine-account-dashboard p`,
         settings.shopengine_account_dashboard_letter_spacing,
         (val) => `
            letter-spacing: ${val}px;
         `
      );
      cssHelper.add(
         `.shopengine-account-dashboard p`,
         settings.shopengine_account_dashboard_wordspace,
         (val) => `
            word-spacing: ${val}px;
         `
      );

      cssHelper.add(
         `.shopengine-account-dashboard p:first-child`,
         settings.shopengine_account_dashboard_spacing,
         (val) => `
           margin-bottom: ${val} !important;
         `
      );


  return cssHelper.get();
};

export { Style };
