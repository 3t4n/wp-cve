import React, { Component } from 'react';
import { render } from 'react-dom';
const ReuseForm = __REUSEFORM__;

export default class TermMetaGeneratorPreview extends Component {
  constructor(props) {
    super(props);
    let preValue = {};
    this.outputDiv = '_reuseb_term_meta_data';
    try {
      preValue = JSON.parse(REUSEB_ADMIN.UPDATED_TERM_META);
      document.getElementById(this.outputDivoutputDiv).value = REUSEB_ADMIN.UPDATED_TERM_META;
    }catch(e){}
    this.state = {
      preValue,
    };
  }
  render() {
    const { fields } = REUSEB_ADMIN.INITIAL_TERM_META;
    const getUpdatedFields = (data) => {
      document.getElementById(this.outputDiv).value = JSON.stringify(data);
    }
    const reuseFormOption = {
      reuseFormId: 'TermMetaSettings',
      fields,
      getUpdatedFields,
      errorMessages: {},
      preValue: this.state.preValue,
    };
    return (<div>
      <ReuseForm {...reuseFormOption} />
    </div>);
  }
}

const documentRoot = document.getElementById('reuseb_term_metabox');
if (documentRoot) {
  render(<TermMetaGeneratorPreview />, documentRoot);
}
