import React, { Component } from 'react';
import { render } from 'react-dom';
import Button from './settingsSubmitButton';
const ReuseForm = __REUSEFORM__;

let fields = REUSEB_ADMIN.fields;

export default class PostTypeBuilder extends Component {
  constructor(props) {
    super(props);
    this.state = {
      preValue: REUSEB_ADMIN.REUSEB_SETTINGS
        ? JSON.parse(REUSEB_ADMIN.REUSEB_SETTINGS)
        : {},
    };
  }
  render() {
    const getUpdatedFields = data => {
      const newData = {};
      fields.forEach(field => {
        const id = field.id.replace('reusebSettings__', '');
        if (data[id] === undefined) {
          newData[id] = field.value;
        } else {
          newData[id] = data[id];
        }
      });
      document.getElementById('_reuse_builder_settings').value = JSON.stringify(
        newData
      );
    };
    const reuseFormOption = {
      reuseFormId: 'reusebSettings',
      fields,
      getUpdatedFields,
      errorMessages: {},
      preValue: this.state.preValue,
    };
    return (
      <div>
        <ReuseForm {...reuseFormOption} />
        <Button />
      </div>
    );
  }
}

const documentRoot = document.getElementById('reuse_builder_settings');
if (documentRoot) {
  render(<PostTypeBuilder />, documentRoot);
}
