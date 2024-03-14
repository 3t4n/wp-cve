import React, { Component } from 'react';
import { render } from 'react-dom';
const ReuseForm = __REUSEFORM__;
const $ = jQuery;
const { findIndex } = _;

export default class MetaboxPreview extends Component {
  constructor(props) {
    super(props);
    this.getUpdatedFields = this.getUpdatedFields.bind(this);
    const { tplId } = this.props;
    this.state = {
      preValue: InitialMetaboxPreview[tplId].metaValues
        ? JSON.parse(InitialMetaboxPreview[tplId].metaValues)
        : {},
    };
  }
  componentWillMount() {
    const dynamicInputId =
      InitialMetaboxPreview[this.props.tplId].dynamicInputId;
    document.getElementById(dynamicInputId).value = InitialMetaboxPreview[
      this.props.tplId
    ].metaValues
      ? InitialMetaboxPreview[this.props.tplId].metaValues
      : null;
  }
  getUpdatedFields(data) {
    const dynamicInputId =
      InitialMetaboxPreview[this.props.tplId].dynamicInputId;
    document.getElementById(dynamicInputId).value = JSON.stringify(data);
  }
  render() {
    const { tplId } = this.props;
    const fields = JSON.parse(InitialMetaboxPreview[tplId].fields);
    const conditions = InitialMetaboxPreview[tplId].conditions;
    const reuseFormOption = {
      reuseFormId: tplId,
      fields,
      getUpdatedFields: this.getUpdatedFields,
      errorMessages: {},
      conditions,
      preValue: this.state.preValue,
    };
    return (
      <div>
        <ReuseForm {...reuseFormOption} />
      </div>
    );
  }
}

const allDivs = $('div[id^="reuseb_dynamic_preview_"]');
allDivs.filter(function() {
  const dest = document.getElementById(this.id);
  const tplId = $('#' + this.id).data('tpl');
  render(<MetaboxPreview id={this.id} tplId={tplId} />, dest);
});
