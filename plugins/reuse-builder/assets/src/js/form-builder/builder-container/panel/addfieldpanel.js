import React, { Component } from 'react';
import configFields from 'form-builder/formConfig';
import Styles from './panel.less';
import clone from 'clone';
import { randomNumber } from 'utility/helper';

function filterConfigField(configFields, length) {
  let removeItems = ['bundle'];
  if (length > 0) {
    removeItems = ['bundle', 'countdown', 'text-repeat', 'compoundbutton', 'recaptcha', 'password'];
  }
  let newConfigFields = [];
  configFields.forEach(configField => {
    let visible = true;
    removeItems.forEach(removeItem => {
      if (configField.type === removeItem) {
        visible =false;
      }
    });
    if (visible) {
      newConfigFields.push(configField);
    }
  });
  return newConfigFields;
}
export default class AddFieldpanel  extends Component{
  constructor(props) {
    super(props);
    const pLength = this.props.tempField.parentId.length;
    const filterConfigFields = filterConfigField(configFields, pLength);
    this.state = {
      searchText: '',
      pLength,
      filterConfigFields,
      allFields: filterConfigFields,
    };
  }
  componentWillReceiveProps(newProps) {
    if (this.state.pLength !== newProps.tempField.parentId.length) {
      const pLength = newProps.tempField.parentId.length;
      const filterConfigFields = filterConfigField(configFields, pLength);
      this.setState({
        searchText: '',
        pLength,
        filterConfigFields,
        allFields: filterConfigFields,
      });
    }
  }
  filterConfigFieldsSearch(allFields, searchText) {
    if (searchText.length > 0) {
      const newFields = [];
      allFields.forEach((field) =>{
        if(field.label.toLowerCase().indexOf(searchText.toLowerCase()) !== -1) {
          newFields.push(field);
        }
      });
      return newFields;
    }
    return allFields;
  }
  render() {
    const { changeView, editField, tempField }= this.props;
    const { allFields, searchText, filterConfigFields } = this.state;
    const onCancel = () => {
      this.props.changeView('preview');
      this.props.editField(null);
    };
    const onChangeSearch = (event) => {
      const newSearchText = event.target.value;
      this.setState({
        searchText: newSearchText,
        filterConfigFields: this.filterConfigFieldsSearch(allFields, newSearchText),
      });
    }
    const onClickCross = () => {
      const newSearchText = '';
      this.setState({
        searchText: newSearchText,
        filterConfigFields: this.filterConfigFieldsSearch(allFields, newSearchText),
      });
    }
    return(<div>
      <div className="scwpSearchComponent">
        <div className="scwpSearchBar">
          <input type="text" value={searchText} onChange={onChangeSearch} placeholder="Search component"/>
          <button type="button" onClick={onClickCross}></button>
        </div>
      </div>
      <div className="scwpFormSettingsWrapper">
        <div className={Styles.scwpSettingsPannelWrapper}>
          <div className={Styles.scwpFieldsbtnWrapper}>
            {filterConfigFields.length > 0 ? filterConfigFields.map((field, index) => {
              const createTempField = () => {
                changeView('edit_field');
                const id = `${field.type}_${randomNumber(10, 99)}`;
                editField({
                    id,
                    label: id,
                    type: field.type,
                    parentId: tempField.parentId,
                  });
              };
              return(<button
                type="button"
                className={Styles.scwpFieldsbtn}
                onClick={createTempField}
                key={`fields_form_${index}`}
              >
                {field.label}
              </button>);
            }) :
          <h1>No field found</h1>}
          </div>

          <div className={Styles.scwpCancelBtnWrapper}>
            <button
              type="button"
              className={Styles.scwpCancelSettingsbtn}
              onClick={onCancel}
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>);
  }
}
