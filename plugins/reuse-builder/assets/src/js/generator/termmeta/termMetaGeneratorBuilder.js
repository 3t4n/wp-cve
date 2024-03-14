import React, { Component } from 'react';
import { render } from 'react-dom';
const ReuseForm = __REUSEFORM__;
import { setOutputIntoDiv, visibleDiv } from 'utility/helper';

const selectOptions = REUSEB_ADMIN.taxonomies;

let fields = [
  {
    id: 'reuseb_taxonomy_select',
    type: 'select',
    label: REUSEB_ADMIN.LANG.TAXONOMY,
    param: 'reuseb_taxonomy_select_param',
    multiple: false,
    subtitle: REUSEB_ADMIN.LANG.PLEASE_SELECT_ANY_TAXONOMT_YOU_WANT_TO_ADD_THIS_TERM_META,
    options: selectOptions,
    value : null,
  },
];

export default class TermMetaGeneratorBuilder extends Component {
  constructor(props) {
    super(props);
  }
  componentWillMount() {
    let value = '';
    try {
      const values = JSON.parse(REUSEB_ADMIN.UPDATED_TERM_META);
      value = values.reuseb_taxonomy_select ? values.reuseb_taxonomy_select : '';
    } catch (e) {}

    setOutputIntoDiv('reuseb_term_meta_builder_metabox_output', 'reuseb_taxonomy_select', value);
    visibleDiv('reuseb_term_meta_builder_metabox_form_builder', value.length > 0);

    fields[0].value = value;
  }
  render() {
    const getUpdatedFields = (data) => {

      const { reuseb_taxonomy_select } = data;
      if (typeof reuseb_taxonomy_select !==  'undefined') {
        setOutputIntoDiv('reuseb_term_meta_builder_metabox_output', 'reuseb_taxonomy_select', reuseb_taxonomy_select);
        visibleDiv('reuseb_term_meta_builder_metabox_form_builder', reuseb_taxonomy_select.length > 0);
     }
    }
    const reuseFormOption = {
      reuseFormId: 'TermMetaSettings',
      fields,
      getUpdatedFields,
      errorMessages: {},
    };
    return (<div>
      <ReuseForm {...reuseFormOption} />
    </div>);
  }
}

const documentRoot = document.getElementById('reuseb_term_meta_builder_metabox_reuse_form');
if (documentRoot) {
  render(<TermMetaGeneratorBuilder />, documentRoot);
}
