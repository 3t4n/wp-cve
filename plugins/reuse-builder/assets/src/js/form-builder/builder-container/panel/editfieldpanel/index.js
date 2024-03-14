import React, { Component } from 'react';
import renderEditPanelHeader from 'form-builder/builder-components/edit-panel-view-change-component';
import AppearancePanel from './appearancePanel';
import ValidationPanel from './validationPanel';
import PreValuePanel from './preValuePanel';
import DataPanel from './dataPanel';
import configFields from 'form-builder/formConfig';
import clone from 'clone';
import { StickyContainer, Sticky } from 'react-sticky';

function checkFormVlidation(formErrors) {
  const keys = Object.keys(formErrors);
  let message = '';
  keys.forEach(key => {
    if (formErrors[key].errorExists) {
      message = `${key}: ${formErrors[key].message}`;
      return;
    }
  });
  return message;
}

export default class EditPanel extends Component {
  constructor(props) {
    super(props);
    this.state = this.getInitState(this.props.tempField);
    this._handleEnter = this._handleEnter.bind(this);
    this._handleLeave = this._handleLeave.bind(this);
  }
  componentWillReceiveProps(nextProps) {
    if (nextProps.tempField.id !== this.props.tempField.id) {
      this.setState(this.getInitState(nextProps.tempField));
    }
  }
  _handleEnter() {
    this.setState({scrollClass : true})
  }
  _handleLeave() {
    this.setState({scrollClass : false})
  }
  getInitState(tempField) {
    const configIndex = _.findIndex(configFields, { type: tempField.type });
    const configField = clone(configFields[configIndex]);
    if (tempField.parentId.length > 0) {
      configField.validationRequire = false;
    }
    return({
      selectedPanel: 'appearance',
      field: tempField,
      currentId: tempField.id,
      allErrors: {},
      validation: tempField.validation,
      value: tempField.value,
      type: tempField.type,
      errorMessage: '',
      configField,
      scrollClass: false,
    });
  }
  updateOption(configFields, field) {
    configFields.forEach((configField, index) => {
      const { id, value } = configField;
      const fValue = field[id];
      if (typeof fValue === 'undefined') {
        field[id] = value;
      } else {
        configFields[index].value = fValue;
      }
    });
    return {
      newField: field,
      newConfigFields: configFields,
    }
  }
  render() {
    const { selectedPanel, field, type, allErrors, currentId, value, validation, configField } = this.state;
    const { changeView, updateField, editField } = this.props;
    let changedField = field,
      changedErrors = allErrors,
      changedValidation = validation,
      changedValue = value;
    const onCancel = (event) => {
      changeView('preview');
      editField(null);
    };
    const onSave = (event) => {
      let errorMessage = changedErrors ? checkFormVlidation(changedErrors) : '';
      if (errorMessage.length == 0) {
        const updatedField = {
          ...changedField,
          type,
          value: changedValue,
          validation: changedValidation,
        };
        updateField(currentId, updatedField);
        changeView('preview');
      } else {
        this.setState({
          field: {...changedField, type},
          value: changedValue,
          allErrors: changedErrors,
          validation: changedValidation,
          errorMessage,
        });
      }
    };

    const changeSelectedPanel = (selectedPanelName) => {
      if (selectedPanelName === 'preValue') {
        let errorMessage = changedErrors ? checkFormVlidation(changedErrors) : '';
        if (errorMessage.length > 0) {
          this.setState({
            field: {...changedField, type},
            value: changedValue,
            allErrors: changedErrors,
            validation: changedValidation,
            errorMessage,
          });
          return;
        }
      }

      if (selectedPanel !==selectedPanelName) {
        this.setState({
          selectedPanel: selectedPanelName,
          field: {...changedField, type},
          value: changedValue,
          allErrors: changedErrors,
          validation: changedValidation,
        });
      }
    };
    const getUpdatedFields = (data, selectedReuseFormId, allErrors) => {
      changedField = {...changedField, ...data};
      changedErrors = allErrors;
    }
    const getUpdatedFieldsValue = (data, selectedReuseFormId, allErrors) => {
      if (data[field.id])
        changedValue = data[field.id];
    }
    const getUpdatedFieldsValidation = (data) => {
        changedValidation = data;
    }
    const getUpdatedFieldsData = (data) => {
      if (data)
      changedField = { ...changedField, ...data };
    }
    const renderPanelBody = () => {
      switch(selectedPanel) {
        case 'appearance':
          const { newField, newConfigFields } = this.updateOption(configField.fields, changedField);
          return <AppearancePanel configFields={newConfigFields} field={newField} getUpdatedFields={getUpdatedFields}/>;
        case 'data':
          const dataPanelOPtions= {
            getUpdatedFields: getUpdatedFieldsData,
            field: changedField,
            type: type,
            globalSettings: this.props.globalSettings,
            changeView,
            showManual: configField.data !== 'Load',
          };
          return <DataPanel {...dataPanelOPtions}/>;
        case 'preValue':
          return <PreValuePanel field={{...field, value}} getUpdatedFields={getUpdatedFieldsValue} value={value} icons={this.props.icons} />;
        case 'validation':
          return <ValidationPanel validation={changedValidation} getUpdatedFields={getUpdatedFieldsValidation} validationRequire={configField.validationRequire}/>;
        default:
          return <div>{selectedPanel}</div>;
      }
    }

    return(<div className="scwpFormSettingsWrapper">
      <div className="scwpSettingsPannelWrapper">
        <StickyContainer>
          <Sticky topOffset={-32}>
            {
              ({ isSticky, wasSticky, style, distanceFromTop, distanceFromBottom, calculatedHeight }) => {
                return (
                  <div className="scwpActionBtnsWrap" style={{
                    ...style,
                    backgroundColor: '#fafafa',
                    marginTop: 32,
                    zIndex: 10,
                  }}>
                    <div className="scwpSettingActionBtnWrapper" style={{
                      marginTop: '0px',
                      marginBottom: '20px',
                    }}>
                      <button type="button" className="scwpCancelSettingsbtn" onClick={onCancel}>Cancel</button>
                      <button type="button" className="scwpSaveSettingsbtn" onClick={onSave}>Save</button>
                    </div>
                    <h1 className="scwpFieldTypeTitle">Field type: <span>{`${field.type}`}</span> </h1>
                    {renderEditPanelHeader(selectedPanel, changeSelectedPanel, configField)}
                  </div>
                )
              }
            }
          </Sticky>
          {renderPanelBody()}
          <span>{this.state.errorMessage}</span>
        </StickyContainer>
      </div>
    </div>);
  }
}
