// #region [Imports] ===================================================================================================

// Libraries
import React, { useEffect } from 'react';
import { bindActionCreators, Dispatch } from 'redux';
import { connect } from 'react-redux';
import { useHistory, useLocation } from 'react-router-dom';
import { Tabs } from 'antd';
import type { TabsProps } from 'antd';

// Components
import AdminHeader from '../../components/AdminHeader';
import PluginLicense from './PluginLicense';

// Helpers
import { getPathPrefix } from '../../helpers/utils';

// Actions
import { PageActions } from '../../store/actions/page';

// Styles
import './index.scss';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

const { setStorePage } = PageActions;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  setStorePage: typeof setStorePage;
}

interface IProps {
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const License = (props: IProps) => {
  const { actions } = props;
  const history = useHistory();
  const urlParams = new URLSearchParams(useLocation().search);
  const defaultTab = urlParams.get('tab') ?? 'ACFW';
  const [currentTab, setCurrentTab] = React.useState(defaultTab);
  const { license_tabs: tabs } = acfwAdminApp;

  const handleTabClick = (key: string) => {
    history.push(`${getPathPrefix()}admin.php?page=acfw-license&tab=${key}`);
    setCurrentTab(key);
    actions.setStorePage({ data: `acfw-license/${key}` });
  };

  useEffect(() => {
    if ('ACFW' !== defaultTab) {
      actions.setStorePage({ data: `acfw-license/${defaultTab}` });
    }
  }, []);

  return (
    <div className="license-page">
      <AdminHeader className="license-header" hideUpgrade={true} />
      <Tabs defaultActiveKey={defaultTab} items={tabs} onTabClick={handleTabClick} />
      {currentTab === 'ACFW' && <PluginLicense />}
    </div>
  );
};

const mapDispatchToProps = (dispatch: Dispatch) => ({
  actions: bindActionCreators({ setStorePage }, dispatch),
});

export default connect(null, mapDispatchToProps)(License);

// #endregion [Component]
