const { __ } = wp.i18n;

const ITEM_COUNT = 1;

const flipboxArray = [];
const defaultIcons = ["accusoft", "acquisitions-incorporated", "ad", "air-freshener"];
const defaultBackIcons = ["address-book", "address-card", "adjust", "adversal"];
for (var i = 1; i <= ITEM_COUNT; i++) {
  flipboxArray.push({
    title: __("Front Title ", "responsive-block-editor-addons") + i,
    subtitle: __("Front Subtitle ", "responsive-block-editor-addons") + i,
    back_title: __("Back Title ", "responsive-block-editor-addons") + i,
    back_subtitle: __("Back Subtitle ", "responsive-block-editor-addons") + i,
    icon: defaultIcons[i - 1],
    back_icon: defaultBackIcons[i - 1],
    front_button: __("Button", "responsive-block-editor-addons") + i,
    front_buttonURL: "",
    back_button: __("Button", "responsive-block-editor-addons") + i,
    back_buttonURL: "",
  });
}

const attributes = {
  block_id: {
    type: "string",
  },
  flipboxArray: {
    type: "array",
    default: flipboxArray,
  },
  count: {
    type: "number",
    default: ITEM_COUNT,
  },
  height: {
    type: "number",
    default: 420,
  },
  iconSize: {
    type: "number",
    default: 50,
  },
  backIconSize: {
    type: "number",
    default: 50,
  },
  transitionSpeed: {
    type: "number",
    default: 8,
  },
  backgroundImage: {
    type: "string",
  },
  backgroundPosition: {
    type: "string",
    default: "center center",
  },
  backgroundAttachment: {
    type: "string",
    default: "scroll",
  },
  backgroundRepeat: {
    type: "string",
    default: "no-repeat",
  },
  backgroundSize: {
    type: "string",
    default: "cover",
  },
  backBackgroundImage: {
    type: "string",
  },
  backBackgroundPosition: {
    type: "string",
    default: "center center",
  },
  backBackgroundAttachment: {
    type: "string",
    default: "scroll",
  },
  backBackgroundRepeat: {
    type: "string",
    default: "no-repeat",
  },
  backBackgroundSize: {
    type: "string",
    default: "cover",
  },
  colorOpacity: {
    type: "number",
    default: 30,
  },
  backColorOpacity: {
    type: "number",
    default: 30,
  },
  imageOpacity: {
    type: "number",
    default: 30,
  },
  backImageOpacity: {
    type: "number",
    default: 30,
  },
  buttonbackgroundType: {
    type: "string",
    default: "none",
  },
  buttoncolorLocation1: {
    type: "number",
    default: 0,
  },
  buttoncolorLocation2: {
    type: "number",
    default: 100,
  },
  buttongradientDirection: {
    type: "number",
    default: 90,
  },
  buttonbackgroundColor1: {
    type: "string",
  },
  buttonbackgroundColor2: {
    type: "string",
    default: "#fff",
  },
  buttonHTextColor: {
    type: "string",
  },
  buttonHColor: {
    type: "string",
  },
  buttonopacity: {
    type: "number",
    default: 100,
  },
  buttonHopacity: {
    type: "number",
    default: 100,
  },
  buttonHbackgroundType: {
    type: "string",
    default: "none",
  },
  buttonHcolorLocation1: {
    type: "number",
    default: 0,
  },
  buttonHcolorLocation2: {
    type: "number",
    default: 100,
  },
  buttonHgradientDirection: {
    type: "number",
    default: 90,
  },
  buttonHbackgroundColor1: {
    type: "string",
  },
  buttonHbackgroundColor2: {
    type: "string",
    default: "#fff",
  },
  buttonBorderRadius: {
    type: "number",
    default: 0,
  },
  ctaHpadding: {
    type: "number",
    default: 20,
  },
  ctaVpadding: {
    type: "number",
    default: 10,
  },
  buttonHPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  buttonVPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  iconSelected: {
    type: "string",
    default: "editor-textcolor",
  },
  flipStyleSelected: {
    type: "string",
    default: "LTR",
  },
  align: {
    type: "string",
    default: "wide",
  },
  gutter: {
    type: "string",
    default: "medium",
  },
  contentAlign: {
    type: "string",
    default: "center",
  },
  frontTextColor: {
    type: "string",
  },
  frontBackgroundColor: {
    type: "string",
  },
  backTextColor: {
    type: "string",
  },
  backBackgroundColor: {
    type: "string",
  },
  buttonColor: {
    type: "string",
  },
  buttonTextColor: {
    type: "string",
  },
  iconColor: {
    type: "string",
  },
  backIconColor: {
    type: "string",
  },
  blockBorderStyle: {
    type: "string",
    default: "none",
  },
  blockBorderWidth: {
    type: "number",
    default: 2,
  },
  blockBorderRadius: {
    type: "number",
  },
  blockBorderColor: {
    type: "string",
  },
  borderStyle: {  // For compatibility with v1.3.2.
    type: "string",
    default: "none",
  },
  borderWidth: {  // For compatibility with v1.3.2.
    type: "number",
    default: 2,
  },
  borderRadius: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  borderColor: {  // For compatibility with v1.3.2.
    type: "string",
    default: 'empty'
  },
  boxShadowColor: {
    type: "string",
  },
  boxShadowHOffset: {
    type: "number",
    default: 0,
  },
  boxShadowVOffset: {
    type: "number",
    default: 0,
  },
  boxShadowBlur: {
    type: "number",
    default: 0,
  },
  boxShadowSpread: {
    type: "number",
    default: 0,
  },
  boxShadowPosition: {
    type: "string",
    default: "outset",
  },
  showFrontIcon: {
    type: "boolean",
    default: true,
  },
  showFrontTitle: {
    type: "boolean",
    default: true,
  },
  showFrontSubtitle: {
    type: "boolean",
    default: true,
  },
  showBackIcon: {
    type: "boolean",
    default: true,
  },
  showBackTitle: {
    type: "boolean",
    default: true,
  },
  showBackSubtitle: {
    type: "boolean",
    default: true,
  },
  showBackButton: {
    type: "boolean",
    default: true,
  },
  colorButtonSelected: {
    type: "string",
  },
  topMargin: {
    type: "number",
    default: 0,
  },
  bottomMargin: {
    type: "number",
    default: 0,
  },
  topMarginMobile: {
    type: "number",
    default: 0,
  },
  bottomMarginMobile: {
    type: "number",
    default: 0,
  },
  topMarginTablet: {
    type: "number",
    default: 0,
  },
  bottomMarginTablet: {
    type: "number",
    default: 0,
  },
  frontTopPadding: {
    type: "number",
    default: 0,
  },
  frontBottomPadding: {
    type: "number",
    default: 0,
  },
  frontLeftPadding: {
    type: "number",
    default: 0,
  },
  frontRightPadding: {
    type: "number",
    default: 0,
  },
  topPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  bottomPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  leftPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  rightPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  backTopPadding: {
    type: "number",
    default: 0,
  },
  backBottomPadding: {
    type: "number",
    default: 0,
  },
  backLeftPadding: {
    type: "number",
    default: 0,
  },
  backRightPadding: {
    type: "number",
    default: 0,
  },
  backtopPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  backbottomPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  backleftPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  backrightPadding: {  // For compatibility with v1.3.2.
    type: "number",
    default: 999,
  },
  frontTopPaddingMobile: {
    type: "number",
    default: 0,
  },
  frontBottomPaddingMobile: {
    type: "number",
    default: 0,
  },
  frontLeftPaddingMobile: {
    type: "number",
    default: 0,
  },
  frontRightPaddingMobile: {
    type: "number",
    default: 0,
  },
  backTopPaddingMobile: {
    type: "number",
    default: 0,
  },
  backBottomPaddingMobile: {
    type: "number",
    default: 0,
  },
  backLeftPaddingMobile: {
    type: "number",
    default: 0,
  },
  backRightPaddingMobile: {
    type: "number",
    default: 0,
  },
  frontTopPaddingTablet: {
    type: "number",
    default: 0,
  },
  frontBottomPaddingTablet: {
    type: "number",
    default: 0,
  },
  frontLeftPaddingTablet: {
    type: "number",
    default: 0,
  },
  frontRightPaddingTablet: {
    type: "number",
    default: 0,
  },
  backTopPaddingTablet: {
    type: "number",
    default: 0,
  },
  backBottomPaddingTablet: {
    type: "number",
    default: 0,
  },
  backLeftPaddingTablet: {
    type: "number",
    default: 0,
  },
  backRightPaddingTablet: {
    type: "number",
    default: 0,
  },
  flipBoxGutterGap: {
    type: "number",
    default: 10,
  },
  stack: {
    type: "string",
    default: "mobile",
  },
  frontTitleFontFamily: {
    type: "string",
  },
  frontTitleFontWeight: {
    type: "string",
    default: "100"
  },
  frontTitleFontSize: {
    type: "number",
    default: 16
  },
  frontTitleFontSizeMobile: {
    type: "number",
    default: 16
  },
  frontTitleFontSizeTablet: {
    type: "number",
    default: 16
  },
  frontTitleLineHeight: {
    type: "number",
    default: 1
  },
  frontSubtitleFontFamily: {
    type: "string",
  },
  frontSubtitleFontWeight: {
    type: "string",
    default: "100"
  },
  frontSubtitleFontSize: {
    type: "number",
    default: 16
  },
  frontSubtitleFontSizeMobile: {
    type: "number",
    default: 16
  },
  frontSubtitleFontSizeTablet: {
    type: "number",
    default: 16
  },
  frontSubtitleLineHeight: {
    type: "number",
    default: 1
  },
  backTitleFontFamily: {
    type: "string",
  },
  backTitleFontWeight: {
    type: "string",
    default: "100"
  },
  backTitleFontSize: {
    type: "number",
    default: 16
  },
  backTitleFontSizeMobile: {
    type: "number",
    default: 16
  },
  backTitleFontSizeTablet: {
    type: "number",
    default: 16
  },
  backTitleLineHeight: {
    type: "number",
    default: 1
  },
  backSubtitleFontFamily: {
    type: "string",
  },
  backSubtitleFontWeight: {
    type: "string",
    default: "100"
  },
  backSubtitleFontSize: {
    type: "number",
    default: 16
  },
  backSubtitleFontSizeMobile: {
    type: "number",
    default: 16
  },
  backSubtitleFontSizeTablet: {
    type: "number",
    default: 16
  },
  backSubtitleLineHeight: {
    type: "number",
    default: 1
  },
  backButtonFontFamily: {
    type: "string",
  },
  backButtonFontWeight: {
    type: "string",
    default: "100"
  },
  backButtonFontSize: {
    type: "number",
    default: 16
  },
  backButtonFontSizeMobile: {
    type: "number",
    default: 16
  },
  backButtonFontSizeTablet: {
    type: "number",
    default: 16
  },
  backButtonLineHeight: {
    type: "number",
    default: 1
  },
  ctaHpaddingTablet: {
    type: "number",
    default: 30,
  },
  ctaHpaddingMobile: {
    type: "number",
    default: 30,
  },
  ctaVpaddingTablet: {
    type: "number",
    default: 15,
  },
  ctaVpaddingMobile: {
    type: "number",
    default: 15,
  },
  ctaBorderStyle: {
    type: "string",
    default: "none",
  },
  ctaBorderRadius: {
    type: "number",
    default: 0,
  },
  ctaBorderWidth: {
    type: "number",
    default: 2,
  },
  ctaColor: {
    type: "string",
    default: "#ffffff",
  },
  ctaBackColor: {
    type: "string",
    default: "#333",
  },
  ctaHoverColor: {
    type: "string",
    default: "#ffffff",
  },
  ctaHoverBackColor: {
    type: "string",
    default: "#3f46ae",
  },
  ctaBorderColor: {
    type: "string",
    default: "#333",
  },
  ctaHoverBorderColor: {
    type: "string",
    defaulr: "#333",
  },
  buttonTarget: {
    type: "boolean",
    default: false,
  },
};

export default attributes;
