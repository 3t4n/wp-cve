import React from 'react';
const ReuseForm = __REUSEFORM__;

export default function appearancePanel(props) {
  const { field, getUpdatedFields, configFields } = props;
  const reuseOption = {
    reuseFormId: `builder-update-${field.id}`,
    fields: configFields,
    getUpdatedFields,
    preValue: field,
    errorMessages: {},
  };
  return (<ReuseForm {...reuseOption} />);
}
