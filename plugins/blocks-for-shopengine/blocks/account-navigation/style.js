const Style = ({ settings, cssHelper }) => {
  const { shopengine_product_navigation_container_padding } = settings;

  cssHelper
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a",
      settings.shopengine_navigation_list_color,
      (val) => {
        return `color: ${val}; margin: 0`;
      }
    )

  cssHelper
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a:before",
      settings.shopengine_navigation_list_color,
      (val) => {
        return `background-color: ${val} !important;`;
      }
    )


    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a:hover",
      settings.shopengine_navigation_list_hover_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a:hover",
      settings.shopengine_navigation_list_hover_active_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      `.shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link.is-active a
      `,
      settings.shopengine_navigation_list_hover_active_color,
      (val) => {
        return `color: ${val};`;
      }
    )

    .add(
      `.shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link.is-active a:before
      `,
      settings.shopengine_navigation_list_hover_active_color,
      (val) => {
        return `background-color : ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .is-active",
      settings.shopengine_navigation_list_active_background_color,
      (val) => {
        return `background-color: ${val} !important;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a",
      settings.shopengine_navigation_list_font_family,
      (val) => {
        return `font-family: ${val.family};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a",
      settings.shopengine_navigation_list_font_size,
      (val) => {
        return `font-size: ${val}px;`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link",
      settings.shopengine_navigation_list_font_weight,
      (val) => {
        return `font-weight: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link",
      settings.shopengine_navigation_list_text_transform,
      (val) => {
        return `text-transform: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link ",
      settings.shopengine_navigation_list_line_height,
      (val) => {
        return `line-height: ${val}px !important`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link a",
      settings.shopengine_navigation_list_word_spacing,
      (val) => {
        return `word-spacing: ${val}px;`;
      }
    );

  let horizontal =
    settings.shopengine_navigation_list_box_shadow_horizontal.desktop + "px";
  let vertical =
    settings.shopengine_navigation_list_box_shadow_vertical.desktop + "px";
  let blur =
    settings?.shopengine_navigation_list_box_shadow_blur.desktop + "px";
  let spread =
    settings?.shopengine_navigation_list_box_shadow_spread.desktop + "px";

  let color = settings?.shopengine_navigation_list_box_shadow_color.desktop.rgb;

  cssHelper.add(
    ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link.is-active",
    {},
    () => {
      return `box-shadow: ${horizontal} ${vertical} ${blur} ${spread} rgba(${color?.r},${color?.g}, ${color?.b}, ${color?.a}) !important`;
    }
  );
  cssHelper.add(
    ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation ul",
    settings.shopengine_navigation_container_border_color,
    (val) => {
      return `border: 1px solid ${val};`;
    }
  );

  cssHelper.add(
    ".shopengine-widget .shopengine-account-navigation .woocommerce-MyAccount-navigation ul li",
    shopengine_product_navigation_container_padding,
    (val) => {
      return `padding: ${val?.top} ${val?.right} ${val?.bottom} ${val?.left}`;
    }
  );
  return cssHelper.get();
};

export { Style };
