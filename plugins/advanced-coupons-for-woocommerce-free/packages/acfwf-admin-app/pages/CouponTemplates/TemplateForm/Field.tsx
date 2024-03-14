// #region [Imports] ===================================================================================================

// Libraries
import { Popover } from 'antd';
import { QuestionCircleOutlined } from '@ant-design/icons';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

// Components
import getFieldComponent from './Fields';

// Actions
import { CouponTemplatesActions } from '../../../store/actions/couponTemplates';

// Types
import { ICouponTemplateField } from '../../../types/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================

const { setEditCouponTemplateFieldValue } = CouponTemplatesActions;
// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IActions {
  setFieldValue: typeof setEditCouponTemplateFieldValue;
}

interface IProps {
  templateField: ICouponTemplateField;
  actions: IActions;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const Field = (props: IProps) => {
  const { templateField, actions } = props;
  const { field, value, field_value, fixtures } = templateField;
  const fieldId = `input-field-${field}`;
  const FieldInput = getFieldComponent(fixtures.type);

  const handleFieldChange = (value: any) => {
    actions.setFieldValue({ field: templateField.field, value });
  };

  return (
    <div className="template-field">
      <div className="template-field-inner">
        <label htmlFor={fieldId}>{fixtures.label}</label>
        <div className="field-input-wrap">
          <FieldInput
            defaultValue={value}
            editable={'readonly' !== field_value}
            fixtures={fixtures}
            onChange={handleFieldChange}
          />
          {fixtures.tooltip && (
            <Popover className="acfw-field-tooltip" placement="right" content={fixtures.tooltip} trigger="click">
              <QuestionCircleOutlined />
            </Popover>
          )}
        </div>
        {!!templateField?.error && <div className="field-error">{templateField.error}</div>}
      </div>
    </div>
  );
};

const mapDispatchToProps = (dispatch: any) => ({
  actions: bindActionCreators({ setFieldValue: setEditCouponTemplateFieldValue }, dispatch),
});

export default connect(null, mapDispatchToProps)(Field);

// #endregion [Component]
