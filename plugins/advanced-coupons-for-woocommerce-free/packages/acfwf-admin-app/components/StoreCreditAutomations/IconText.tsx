// #region [Imports] ===================================================================================================

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IProps {
  slug: string;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const IconText = (props: IProps) => {
  const { slug } = props;
  const { img_path } = acfwAdminApp.uncanny_automator;
  let iconPath,
    logoName = "";

  switch (slug) {
    case "woocommerce":
      iconPath = "woo-logo.png";
      logoName = "WooCommerce";
      break;
    case "twilio":
      iconPath = "twilio-logo.png";
      logoName = "Twilio";
      break;
    case "google_sheets":
      iconPath = "gsheets-logo.png";
      logoName = "Google Sheets";
      break;
    default:
      iconPath = "Advanced-Coupons-Icon-WC-Marketing.png";
      logoName = "Advanced Coupon";
      break;
  }

  return (
    <div className={`icon-text ${slug}`}>
      <img src={`${img_path}${iconPath}`} />
      <span>{logoName}</span>
    </div>
  );
};

export default IconText;

// #endregion [Component]
