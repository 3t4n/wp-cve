// #region [Imports] ===================================================================================================

// Components
import PluginInstallerButton from '../../PluginInstallerButton';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface INoticeData {
  classname: string;
  title: string;
  description: string;
  button_text: string;
  image: string;
  nonce: string;
  success_message: string;
}

interface IProps {
  noticeData: INoticeData;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const FunnelKitUpsell = (props: IProps) => {
  const { classname, title: noticeTitle, description, button_text, image, nonce, success_message } = props.noticeData;

  return (
    <p className={`acfw-dyk-notice acfw-funnelkit-upsell-notice ${classname}`}>
      {!!image && <img src={image} />}
      <span className="title">{noticeTitle}</span>
      <br />
      <span className="text" dangerouslySetInnerHTML={{ __html: description }} />
      <span className="button-wrap">
        <PluginInstallerButton
          pluginSlug="funnel-builder"
          className="install-funnelkit-btn"
          type="primary"
          size="middle"
          text={button_text}
          nonce={nonce}
          successMessage={success_message}
          afterInstall={() => (window.location.href = `${acfwAdminApp.admin_url}admin.php?page=bwf`)} // Redirect to FunnelKit dashboard after install/activate.
        />
      </span>
    </p>
  );
};

export default FunnelKitUpsell;

// #endregion [Component]
