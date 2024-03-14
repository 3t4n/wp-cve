// #region [Imports] ===================================================================================================

import { useEffect, useState } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

// Types
import { ICouponTemplateListItem } from '../../types/couponTemplates';
import { IStore } from '../../types/store';

// Actions
import { CouponTemplatesActions } from '../../store/actions/couponTemplates';

// Components
import TemplatesSkeleton from './TemplatesSkeleton';
import TemplatesList from './TemplatesList';

// #endregion [Imports]

// #region [Variables] =================================================================================================

const { readRecentCouponTemplates } = CouponTemplatesActions;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  readRecentCouponTemplates: typeof readRecentCouponTemplates;
}

interface IProps {
  templates: ICouponTemplateListItem[];
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const RecentTemplates = (props: IProps) => {
  const { templates, actions } = props;
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    setLoading(true);
    actions.readRecentCouponTemplates({ successCB: () => setLoading(false) });
  }, []);

  if (loading) {
    return <TemplatesSkeleton className="coupon-templates-list" />;
  }

  return <TemplatesList templates={templates} showClose={true} />;
};

const mapStateToProps = (state: IStore) => ({
  templates: state.couponTemplates?.recent ?? [],
});

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators({ ...CouponTemplatesActions }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(RecentTemplates);

// #endregion [Component]
