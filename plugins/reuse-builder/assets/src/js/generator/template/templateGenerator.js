import React, { Component } from 'react';
import { render } from 'react-dom';
const ReuseForm = __REUSEFORM__;

const selectOptions = REUSEB_ADMIN.postTypes;

let fields = [
  {
    id: 'reuseb_template_select_type',
    type: 'select',
    label: 'Select Template Type',
    param: 'reuseb_template_select_type',
    multiple: false,
    options: {
      archive: 'Archive Page',
      single: 'Single Page',
    },
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_template_post_select',
    type: 'select',
    label: REUSEB_ADMIN.LANG.POST_TYPE,
    param: 'reuseb_template_post_select_param',
    multiple: false,
    subtitle: REUSEB_ADMIN.LANG.PLEASE_SELECT_ANY_POST_TYPE_YOU_WANT_TO_ADD_THIS_TAXONOMY,
    options: selectOptions,
    value: null, // eikhane changes
  },
];

export default class TeplateGenerator extends Component {
  constructor(props) {
    super(props);
    let preValue = {};
    try {
      preValue = REUSEB_ADMIN.UPDATED_TEMPLATE ? JSON.parse(REUSEB_ADMIN.UPDATED_TEMPLATE) : {};
    } catch (e) {
    }
    this.state = {
      preValue,
    };
  }
  render() {
    const { preValue } = this.state;
    let changedData = null;
    const getUpdatedFields = (data) => {
      const newData = {};
      fields.forEach(field => {
        const id = field.id.replace('TemplateSettings__', '');
        if (data[id] === undefined) {
          newData[id] = field.value;
        } else {
          newData[id] = data[id];
        }
      });
      changedData = data;
      document.getElementById('_reuseb_template_data').value = JSON.stringify(newData);
    }

    const reuseFormOption = {
      reuseFormId: 'TeplateSettings',
      fields,
      preValue,
      // conditions: allLogicBlock,
      getUpdatedFields,
      errorMessages: {},
    };
    return (<div>
      <ReuseForm {...reuseFormOption} />
    </div>);
  }
}

const documentRoot = document.getElementById('reuseb_template_metabox');
if (documentRoot) {
  render(<TeplateGenerator />, documentRoot);
}
