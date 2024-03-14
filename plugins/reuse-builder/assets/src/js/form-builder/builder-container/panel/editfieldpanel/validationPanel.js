import React from 'react';
const ReuseForm = __REUSEFORM__;
import { validationFields } from 'form-builder/formConfig/errorConfig';
//import 'reuse-form/elements/less/common.less';

export default function preValuePanel(props) {
  let preValue = {};
  if (props.validation) {
    preValue = props.validation;
  } else {
    preValue['require'] = 'noValidation';
    preValue['message'] = '';
  }
  const validationField = props.validationRequire ? props.validationRequire : validationFields;
  const reuseOption = {
    reuseFormId: `builder-validation`,
    fields: validationField,
    getUpdatedFields: (data) => {
      if (data.require) {
        if (data.require === 'noValidation') {
          props.getUpdatedFields(undefined);
        } else {
          props.getUpdatedFields(data);
        }
      }
    },
    preValue,
    errorMessages: {},
  };
  return (<ReuseForm {...reuseOption} />);
}
