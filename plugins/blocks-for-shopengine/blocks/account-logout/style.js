const Style = ({ settings, cssHelper }) => {
  cssHelper
    .add(
      ".shopengine-widget .shopengine-account-logout",
      settings.shopengine_logout_title_align,
      (val) => {
        return `text-align: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout a",
      settings.shopengine_logout_title_color,
      (val) => {
        return `color: ${val}; margin:0px`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout a:hover",
      settings.shopengine_logout_title_hover_color,
      (val) => {
        return `color: ${val};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout a span",
      settings.shopengine_logout_title_font_family,
      (val) => {
        return `font-family: ${val.family};`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout a",
      settings.shopengine_logout_title_font_size,
      (val) => {
        return `font-size: ${val}px`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout a",
      settings.shopengine_logout_title_font_weight,
      (val) => {
        return `font-weight: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout a",
      settings.shopengine_logout_title_text_transform,
      (val) => {
        return `text-transform: ${val}`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout",
      settings.shopengine_logout_title_line_height,
      (val) => {
        return `line-height: ${val}px`;
      }
    )
    .add(
      ".shopengine-widget .shopengine-account-logout a",
      settings.shopengine_logout_title_letter_spacing,
      (val) => {
        return `letter-spacing: ${val}px`;
      }
    );

  return cssHelper.get();
};

export { Style };
