const Style = ({ cssHelper, settings }) => {
  cssHelper
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_orders_header_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table thead",
      settings?.shopengine_orders_header_background,
      (val) => {
        return `background-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_orders_header_font_family,
      (val) => {
        return `font-family: ${val?.family};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_orders_header_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_orders_header_font_weight,
      (val) => {
        return `font-weight: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_order_header_text_transform,
      (val) => {
        return `text-transform: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_order_header_text_style,
      (val) => {
        return `font-style: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_order_header_line_height,
      (val) => {
        return `line-height: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads thead th",
      settings?.shopengine_order_header_word_spacing,
      (val) => {
        return `word-spacing: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody",
      settings?.shopengine_download_body_bg_color,
      (val) => {
        return `background-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_download_body_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr .download-product a",
      settings?.shopengine_download_body_link_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr .download-product a:hover",
      settings?.shopengine_download_link_hover_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_orders_body_font_family,
      (val) => {
        return `font-family: ${val?.family};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_orders_body_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_orders_body_font_weight,
      (val) => {
        return `font-weight: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_order_body_text_transform,
      (val) => {
        return `text-transform: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_order_body_text_style,
      (val) => {
        return `font-style: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_order_body_line_height,
      (val) => {
        return `line-height: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td",
      settings?.shopengine_order_body_word_spacing,
      (val) => {
        return `word-spacing: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr:not(:last-child)",
      settings?.shopengine_order_body_border_type,
      (val) => {
        return `border-style: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr:not(:last-child)",
      settings?.shopengine_order_body_border_width,
      (val) => {
        return `border-width: 0px 0px ${val} 0px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr:not(:last-child)",
      settings?.shopengine_order_body_border_color,
      (val) => {
        return `border-color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_download_button_padding,
      (val) => {
        return `padding: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_download_button_radius,
      (val) => {
        return `border-radius: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_orders_download_button_font_family,
      (val) => {
        return `font-family: ${val?.family}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_orders_download_button_font_size,
      (val) => {
        return `font-size: ${val}px`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_orders_download_button_font_weight,
      (val) => {
        return `font-weight: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_order_download_button_text_transform,
      (val) => {
        return `text-transform: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_order_download_button_word_spacing,
      (val) => {
        return `word-spacing: ${val}px`;
      }
    );

  const horizontal =
    settings?.shopengine_account_downloads_button_box_shadow_horizontal.desktop;
  const vertical =
    settings?.shopengine_account_downloads_button_box_shadow_vertical.desktop;
  const blur =
    settings?.shopengine_account_downloads_button_box_shadow_blur.desktop;
  const spread =
    settings.shopengine_account_downloads_button_box_shadow_spread.desktop;
  const position =
    settings?.shopengine_account_downloads_button_box_shadow_position.desktop;
  const shadowColor =
    settings?.shopengine_account_downloads_button_box_shadow_color.desktop.rgb;

  cssHelper
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      {},
      (val) => {
        return `box-shadow: ${horizontal}px ${vertical}px ${blur}px ${spread}px rgba(${shadowColor?.r},${shadowColor?.g}, ${shadowColor?.b}, ${shadowColor?.a}) ${position};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_download_button_tab_clr,
      (val) => {
        return `color: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a",
      settings.shopengine_download_button_tab_bg,
      (val) => {
        return `background-color: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a:hover",
      settings.shopengine_download_button_tab_hover_clr,
      (val) => {
        return `color: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-downloads .woocommerce-order-downloads .woocommerce-table--order-downloads tbody tr td.download-file a:hover",
      settings.shopengine_download_button_tab_hover_bg,
      (val) => {
        return `background-color: ${val}`;
      }
    );

  return cssHelper.get();
};

export { Style };
