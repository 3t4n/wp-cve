// #region [Imports] ===================================================================================================

// Libraries
import { Button } from 'antd';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

// Components
import GoBackButton from '../GoBackButton';

// Types
import { IStore } from '../../../../types/store';

// Actions
import { CouponTemplatesActions } from '../../../../store/actions/couponTemplates';

// SCSS
import './index.scss';
import { ICreateCouponFromTemplateResponse } from '../../../../types/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

const { clearCreatedCouponResponseData } = CouponTemplatesActions;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  clearCreatedCouponResponseData: typeof clearCreatedCouponResponseData;
}

interface IProps {
  formResponse: ICreateCouponFromTemplateResponse | null;
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const SuccessPage = (props: IProps) => {
  const { formResponse, actions } = props;
  const { labels } = acfwAdminApp.coupon_templates_page;

  if (formResponse) {
    const { message, coupon_edit_url } = formResponse;

    return (
      <div className="coupon-template-success-page">
        <div className="inner">
          <div className="content-box">
            <h2>{message}</h2>
            <p>{labels.success_page_desc}</p>
            <ul>
              {formResponse.fields.map((field) => (
                <li key={field.label}>
                  <strong>{field.label}: </strong>
                  {field.value}
                </li>
              ))}
            </ul>
          </div>
          <div className="actions-box">
            <Button
              className="create-another-btn"
              type="primary"
              size="large"
              onClick={() => actions.clearCreatedCouponResponseData()}
            >
              <span>{labels.create_another_coupon}</span>
              <em>{labels.using_the_same_template}</em>
            </Button>
            <Button href={coupon_edit_url} size="large">
              {labels.edit_coupon}
            </Button>
            <GoBackButton
              text={labels.view_templates_list}
              size="large"
              onClick={() => actions.clearCreatedCouponResponseData()}
            />
          </div>
        </div>
      </div>
    );
  }

  return null;
};

const mapStateToProps = (state: IStore) => ({
  formResponse: state.couponTemplates?.formResponse ?? null,
});

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators({ clearCreatedCouponResponseData }, dispatch),
});

export default connect(mapStateToProps, mapDispatchToProps)(SuccessPage);

// #endregion [Component]
