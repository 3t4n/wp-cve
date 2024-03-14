import React, { Component } from 'react';
import { render } from 'react-dom';
const ReuseForm = __REUSEFORM__;
import { setOutputIntoDiv, visibleDiv } from 'utility/helper';

const selectOptions = REUSEB_ADMIN.postTypes;

let fields = [
  {
    id: 'reuseb_post_type_select',
    type: 'select',
    label: REUSEB_ADMIN.LANG.POST_TYPE,
    param: 'reuseb_post_type_select_param',
    multiple: 'false',
    subtitle:
      REUSEB_ADMIN.LANG
        .PLEASE_SELECT_ANY_POST_TYPE_YOU_WANT_TO_ADD_THIS_TAXONOMY,
    options: selectOptions,
    value: null,
  },
];

export default class MetaboxBuilder extends Component {
  constructor(props) {
    super(props);
  }
  componentWillMount() {
    const selectOptions = REUSEB_ADMIN.postTypes;
    let value = '';
    try {
      const values = JSON.parse(REUSEB_ADMIN.UPDATED_METABOX);
      value = values.reuseb_post_type_select
        ? values.reuseb_post_type_select
        : '';
    } catch (e) {}
    setOutputIntoDiv(
      'reuseb_metabox_builder_output',
      'reuseb_post_type_select',
      value
    );
    visibleDiv('reuseb_metabox_builder_form_builder', value.length > 0);
    fields[0].value = value;
  }
  render() {
    const getUpdatedFields = data => {
      const { reuseb_post_type_select } = data;
      if (typeof reuseb_post_type_select !== 'undefined') {
        setOutputIntoDiv(
          'reuseb_metabox_builder_output',
          'reuseb_post_type_select',
          reuseb_post_type_select
        );
        visibleDiv(
          'reuseb_metabox_builder_form_builder',
          reuseb_post_type_select.length > 0
        );
      }
    };
    const reuseFormOption = {
      reuseFormId: 'MetaboxBuilder',
      fields,
      getUpdatedFields,
      errorMessages: {},
    };
    return (
      <div>
        <ReuseForm {...reuseFormOption} />
      </div>
    );
  }
}

const documentRoot = document.getElementById(
  'reuseb_metabox_builder_reuse_form'
);
if (documentRoot) {
  render(<MetaboxBuilder />, documentRoot);
}
