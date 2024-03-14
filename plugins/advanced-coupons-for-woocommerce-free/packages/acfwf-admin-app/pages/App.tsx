// #region [Imports] ===================================================================================================

// Libraries
import React, { useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { bindActionCreators, Dispatch } from 'redux';
import { connect } from 'react-redux';

// Pages
import Dashboard from './Dashboard';
import StoreCredits from './StoreCredits';
import Settings from './Settings';
import License from './License';
import About from './About';
import PremiumUpsell from './Premium';
import Help from './Help';
import LoyaltyProgram from './LoyaltyProgram';
import AdvancedGiftCards from './AdvancedGiftCards';
import CouponTemplates from './CouponTemplates';

// Actions
import { PageActions } from '../store/actions/page';
import { AdminNoticesActions } from '../store/actions/adminNotices';

// Types
import { IStore } from '../types/store';

// Helpers
import axiosInstance from '../helpers/axios';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwpElements: any;

const is_lpfw_active = parseInt(acfwpElements.is_lpfw_active);
const is_agc_active = parseInt(acfwpElements.is_agc_active);
const { setStorePage } = PageActions;
const { readAdminNotices } = AdminNoticesActions;

acfwpElements.axiosInstance = axiosInstance;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  setStorePage: typeof setStorePage;
  readAdminNotices: typeof readAdminNotices;
}

interface IProps {
  page: string;
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const App = (props: IProps) => {
  const { page, actions } = props;

  const urlParams = new URLSearchParams(useLocation().search);
  const appPage = urlParams.get('page');

  acfwpElements.appPage = appPage;

  useEffect(() => {
    actions.setStorePage({ data: appPage ? appPage : '' });
  }, [appPage, actions]);

  /**
   * Load notices data on init.
   */
  useEffect(() => {
    actions.readAdminNotices({});
  }, []);

  return (
    <div className="app">
      {page === 'acfw-dashboard' ? <Dashboard /> : null}

      {page === 'acfw-store-credits' ? <StoreCredits /> : null}

      {page === 'acfw-coupon-templates' ? <CouponTemplates /> : null}

      {page === 'acfw-settings' ? <Settings /> : null}

      {page.includes('acfw-license') ? <License /> : null}

      {page === 'acfw-premium' ? <PremiumUpsell /> : null}

      {page === 'acfw-help' ? <Help /> : null}

      {page === 'acfw-about' ? <About /> : null}

      {page === 'acfw-loyalty-program' && !is_lpfw_active ? <LoyaltyProgram /> : null}

      {page === 'acfw-advanced-gift-cards' && !is_agc_active ? <AdvancedGiftCards /> : null}
    </div>
  );
};

const mapStateToProps = (store: IStore) => ({ page: store.page });

const mapDispatchToProps = (dispatch: Dispatch) => ({
  actions: bindActionCreators({ setStorePage, readAdminNotices }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(App);

// #endregion [Component]
