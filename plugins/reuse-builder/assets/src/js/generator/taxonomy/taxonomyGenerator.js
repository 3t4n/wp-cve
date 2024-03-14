import React, { Component } from 'react';
import { render } from 'react-dom';
const ReuseForm = __REUSEFORM__;

const selectOptions = REUSEB_ADMIN.postTypes;

let fields = [
  {
    id: 'reuseb_taxonomy_post_select',
    type: 'select',
    label: REUSEB_ADMIN.LANG.POST_TYPE,
    param: 'reuseb_taxonomy_post_select_param',
    multiple: false,
    subtitle:
      REUSEB_ADMIN.LANG
        .PLEASE_SELECT_ANY_POST_TYPE_YOU_WANT_TO_ADD_THIS_TAXONOMY,
    options: selectOptions,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_taxonomy_hierarchy',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.ENABLE_HIERARCHY,
    param: 'reuseb_taxonomy_taxonomy_hierarchy_param',
    subtitle:
      REUSEB_ADMIN.LANG.IF_YOU_WANT_TO_ENABLE_THE_TAXONOMY_HIERARCHY_SET_TRUE,
    value: false, // eikhane changes
  },
  {
    id: 'reuseb_taxonomy_rest_api_support',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.REST_API,
    param: 'reuseb_taxonomy_taxonomy_hierarchy_param',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_REST_API_SUPPORT_FOR_THIS_POST,
    value: false, // eikhane changes
  },
];

export default class TexanomyGenerator extends Component {
  constructor(props) {
    super(props);
  }
  componentWillMount() {
    let values = {};
    try {
      values = JSON.parse(REUSEB_ADMIN.UPDATED_TAX);
    } catch (e) {}
    fields[0].value = values['reuseb_taxonomy_post_select'];
    fields[1].value = values['reuseb_taxonomy_hierarchy'];
    fields[2].value = values['reuseb_taxonomy_rest_api_support'];
  }
  render() {
    const getUpdatedFields = data => {
      const newData = {};
      fields.forEach(field => {
        const id = field.id.replace('TexanomySettings__', '');
        if (data[id] === undefined) {
          newData[id] = field.value;
        } else {
          newData[id] = data[id];
        }
      });
      document.getElementById('_reuseb_taxonomies_data').value = JSON.stringify(
        newData
      );
    };
    const reuseFormOption = {
      reuseFormId: 'TexanomySettings',
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

const documentRoot = document.getElementById('reuseb_taxonomy_metabox');
if (documentRoot) {
  render(<TexanomyGenerator />, documentRoot);
}
