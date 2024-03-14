// #region [Imports] ===================================================================================================

// Libraries
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { useHistory } from 'react-router-dom';
import { CloseOutlined } from '@ant-design/icons';

// Types
import { ICouponTemplateListItem } from '../../types/couponTemplates';

// Actions
import { CouponTemplatesActions } from '../../store/actions/couponTemplates';

// Helpers
import { getPathPrefix } from '../../helpers/utils';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;
declare var acfwpElements: any;

const { togglePremiumModal, deleteRecentCouponTemplate, unsetRecentCouponTemplate } = CouponTemplatesActions;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  togglePremiumModal: typeof togglePremiumModal;
  deleteRecentCouponTemplate: typeof deleteRecentCouponTemplate;
  unsetRecentCouponTemplate: typeof unsetRecentCouponTemplate;
}

interface IProps {
  template: ICouponTemplateListItem;
  isReview?: boolean;
  showClose?: boolean;
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const CouponTemplateCard = (props: IProps) => {
  const { labels } = acfwAdminApp.coupon_templates_page;
  const history = useHistory();
  const { template, isReview, showClose, actions } = props;

  const handleTemplateClick = () => {
    if (template.license_type === 'premium' && !parseInt(acfwpElements.is_acfwp_active)) {
      actions.togglePremiumModal({ show: true });
      return;
    }
    const pathArgs = isReview ? `id=${template.id}&is_review=true` : `id=${template.id}`;
    history.push(`${getPathPrefix()}admin.php?page=acfw-coupon-templates&${pathArgs}`);
  };

  const handleDeleteTemplate = () => {
    actions.deleteRecentCouponTemplate({ id: template.id });
    actions.unsetRecentCouponTemplate({ id: template.id });
  };

  return (
    <div className="coupon-template-card">
      <div className="template-image" style={{ backgroundColor: template.image_bg_color }}>
        {showClose && (
          <span className="close-icon" onClick={handleDeleteTemplate}>
            <CloseOutlined />
          </span>
        )}
        <span
          className="image-svg"
          onClick={handleTemplateClick}
          dangerouslySetInnerHTML={{ __html: template.image_svg }}
        />
        {template.license_type === 'premium' && <span className="premium-badge">{labels.premium}</span>}
      </div>
      <div className="template-content">
        <h3 onClick={handleTemplateClick}>{template.title}</h3>
        <p>{template.description}</p>
      </div>
    </div>
  );
};

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators({ togglePremiumModal, deleteRecentCouponTemplate, unsetRecentCouponTemplate }, dispatch),
});

export default connect(null, mapDispatchToProps)(CouponTemplateCard);

// #endregion [Component]
