// #region [Imports] ===================================================================================================

// Libraries
import { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { Row, Col, Skeleton, Spin } from 'antd';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

// Components
import Field from './Field';
import GoBackButton from './GoBackButton';
import CreateCouponButton from './CreateCouponButton';
import SuccessPage from './SuccessPage';

// Types
import { SkeletonParagraphProps } from 'antd/lib/skeleton/Paragraph';
import { IStore } from '../../../types/store';
import { ICouponTemplate, ICreateCouponFromTemplateResponse } from '../../../types/couponTemplates';

// Actions
import { CouponTemplatesActions } from '../../../store/actions/couponTemplates';

// SCSS
import './index.scss';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;

const { readCouponTemplate, setEditCouponTemplate, validateEditCouponTemplateData, setCreatedCouponResponseData } =
  CouponTemplatesActions;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  readCouponTemplate: typeof readCouponTemplate;
  setEditCouponTemplate: typeof setEditCouponTemplate;
  validateEditCouponTemplateData: typeof validateEditCouponTemplateData;
  setCreatedCouponResponseData: typeof setCreatedCouponResponseData;
}

interface IProps {
  template: ICouponTemplate | null;
  formResponse: ICreateCouponFromTemplateResponse | null;
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const TemplateForm = (props: IProps) => {
  const { template, formResponse, actions } = props;
  const [loading, setLoading] = useState(true);
  const { labels } = acfwAdminApp.coupon_templates_page;
  const urlParams = new URLSearchParams(useLocation().search);
  const editId = urlParams.get('id') ?? '0';
  const isReview = urlParams.get('is_review') ?? false;
  const paragraphProps = { rows: 2, width: 100 } as SkeletonParagraphProps;

  useEffect(() => {
    if (template && template?.fields) {
      return;
    }

    actions.readCouponTemplate({
      id: parseInt(editId),
      isReview: !!isReview,
      successCB: (response: any) => {
        setLoading(false);
        actions.setEditCouponTemplate({ data: response.data as ICouponTemplate });
      },
      failCB: () => setLoading(false),
    });
  }, []);

  if (!template?.fields) {
    return (
      <div className="acfw-coupon-template-form">
        <Row gutter={16}>
          <Col span={18}>
            <div className="template-form-main template-loading">
              {loading ? <Spin /> : <p className="template-not-found">{labels.template_not_found}</p>}
            </div>
          </Col>
          <Col span={6}>
            <div className="template-actions">
              <GoBackButton
                text={labels.go_back}
                onClick={() => actions.setEditCouponTemplate({ data: null })}
                size="large"
              />
            </div>
          </Col>
        </Row>
      </div>
    );
  }

  if (formResponse) {
    return <SuccessPage />;
  }

  return (
    <div className="acfw-coupon-template-form">
      <Row gutter={16}>
        <Col span={18}>
          <div className="template-form-main">
            {!!template ? (
              <>
                <div className="template-heading">
                  <h2>{template.title}</h2>
                  <p>{template.description}</p>
                </div>
                <div className="form-instruction">
                  <em>{labels.form_instruction}</em>
                </div>
              </>
            ) : (
              <Skeleton active paragraph={paragraphProps} />
            )}

            <div className="template-fields">
              {template.fields.map((field, index) => (
                <Field key={`${field.field}-${field?.error ?? ''}`} templateField={field} />
              ))}
            </div>
          </div>
        </Col>
        <Col span={6}>
          <div className="template-actions">
            <CreateCouponButton text={labels.create_coupon} size="large" />
            <GoBackButton
              text={labels.cancel}
              onClick={() => actions.setEditCouponTemplate({ data: null })}
              size="large"
            />
          </div>
        </Col>
      </Row>
    </div>
  );
};

const mapStateToProps = (state: IStore) => ({
  template: state.couponTemplates?.edit ?? null,
  formResponse: state.couponTemplates?.formResponse ?? null,
});

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators(
    { readCouponTemplate, setEditCouponTemplate, validateEditCouponTemplateData, setCreatedCouponResponseData },
    dispatch
  ),
});

export default connect(mapStateToProps, mapDispatchToProps)(TemplateForm);

// #endregion [Component]
