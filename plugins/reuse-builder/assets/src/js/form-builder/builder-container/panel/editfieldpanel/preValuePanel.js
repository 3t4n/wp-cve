import React from 'react';
const ReuseForm = __REUSEFORM__;
// import 'reuse-form/elements/less/common.less';

export default function preValuePanel(props) {
  const { field, getUpdatedFields } = props;
  let formError = {};
  const reuseOption = {
    reuseFormId: `builder-update-preValue-${field.id}`,
    fields: [ { ...field, validation: undefined } ],
    getUpdatedFields,
    errorMessages: {},
    icons: props.icons,
  };
  return (<ReuseForm {...reuseOption} />);
}
