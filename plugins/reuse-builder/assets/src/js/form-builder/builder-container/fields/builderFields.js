import React, { Component } from 'react';
import { connect } from 'react-redux';
import FieldsComponent from 'form-builder/builder-components/fields';
import {
  createTempField,
  deleteField,
  editField,
  setFields,
  updateField,
  moveFields,
} from '../../builder-modules/builderModule';
import { changeView } from 'form-builder/builder-modules/viewModule';
import ConditionPanel from '../panel/logic/conditionBar/conditionIndex';

class BuilderFields extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    const { panelMode } = this.props;
    const addField = parentId => {
      changeView('add_field');
      editField({ parentId });
    };
    const fieldsComponentOptions = {
      ...this.props,
      parentId: [],
    };
    return (
      <div className="scwpFormFieldsMainWrapper">
        {panelMode !== 'logic' ? (
          <div style={{ height: '100%' }}>
            <div className="scwpPanelHeadingWrapper">
              <h1>Fields</h1>
            </div>
            <FieldsComponent {...fieldsComponentOptions} />
          </div>
        ) : null}
        {panelMode === 'logic' ? <ConditionPanel /> : null}
      </div>
    );
  }
}

function mapStateToProps(state) {
  const builder = state.builder;
  return {
    fields: builder.fields,
    tempField: builder.tempField,
    formBuilderPanel: builder.formBuilderPanel,
    viewMode: state.view.viewMode,
  };
}
export default connect(
  mapStateToProps,
  {
    createTempField,
    deleteField,
    editField,
    setFields,
    changeView,
    updateField,
    moveFields,
  }
)(BuilderFields);
