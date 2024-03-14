// #region [Imports] ===================================================================================================

// SCSS
import './index.scss';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IProps {
  hideUpgrade?: boolean;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const Logo = (props: IProps) => {
  const { hideUpgrade } = props;
  const { app_pages, premium_page } = acfwAdminApp;
  const [premiumPage] = app_pages.filter((p: any) => 'acfw-premium' === p.slug);

  return (
    <div className='acfw-logo-div'>
      {premiumPage ? (
        <>
          <a
            href='https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&amp;utm_medium=upsell&amp;utm_campaign=logo'
            target='_blank'
            rel='noreferrer'
          >
            <img className='acfw-logo' src={acfwAdminApp.logo} alt='acfw logo' />
          </a>
          {!hideUpgrade && (
            <a
              className='acfw-header-upgrade-btn'
              href='https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&amp;utm_medium=upsell&amp;utm_campaign=upgrade'
              target='_blank'
              rel='noreferrer'
            >
              {premium_page.upgrade}
            </a>
          )}
        </>
      ) : (
        <img className='acfw-logo' src={acfwAdminApp.logo} alt='acfw logo' />
      )}
    </div>
  );
};

export default Logo;

// #endregion [Component]
