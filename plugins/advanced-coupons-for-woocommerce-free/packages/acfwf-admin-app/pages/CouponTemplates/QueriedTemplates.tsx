// #region [Imports] ===================================================================================================

// Libraries
import { useEffect } from 'react';
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

const { readCouponTemplates, setCouponTemplatesLoading } = CouponTemplatesActions;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  readCouponTemplates: typeof readCouponTemplates;
  setCouponTemplatesLoading: typeof setCouponTemplatesLoading;
}

interface IProps {
  templates: ICouponTemplateListItem[];
  loading: boolean;
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const QueriedTemplates = (props: IProps) => {
  const { templates, loading, actions } = props;

  useEffect(() => {
    actions.setCouponTemplatesLoading({ loading: true });
    actions.readCouponTemplates({ successCB: () => actions.setCouponTemplatesLoading({ loading: false }) });
  }, []);

  if (loading) {
    return <TemplatesSkeleton className="queried-templates-list" />;
  }

  return <TemplatesList templates={templates} />;
};

const mapStateToProps = (state: IStore) => ({
  templates: state.couponTemplates?.templates ?? [],
  loading: state.couponTemplates?.loading ?? false,
});

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators({ ...CouponTemplatesActions }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(QueriedTemplates);

// #endregion [Component]
