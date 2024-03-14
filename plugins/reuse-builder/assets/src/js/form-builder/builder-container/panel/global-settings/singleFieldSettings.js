import React, { Component } from 'react';
import Collapse from 'react-collapse';
import clone from 'clone';
const Select = __REUSEFORM_COMPONENT__['selectbox'];
import Styles from './singleFields.less';

export default class SingleFieldSettings extends Component {
  constructor(props) {
    super(props);
    this.handlePostDestination = this.handlePostDestination.bind(this);
    this.createReuseForm = this.createReuseForm.bind(this);
    this.handleSaveKey = this.handleSaveKey.bind(this);
    this.handleCollapse = this.handleCollapse.bind(this);
    this.handleCustomType = this.handleCustomType.bind(this);
    this.state = {
      data: null,
      isOpened: false,
    };
  }
    handlePostDestination(val) {
      const { item, settingsData } = this.props;
      const value = val !== null ? val : '';
      let tempField = {};
      tempField.fieldKey = item.id;
      tempField.postDestination = value;
      tempField.saveKey = '';
      tempField.value='';
      this.props.updateData('update', tempField);
  }
  handleCollapse( type ,e) {
    e.preventDefault();
    this.setState({ isOpened: !type });
  }
  handleSaveKey(val) {
      const { item, settingsData } = this.props;
      const value = val !== null ? val : '';
      let tempField = {};
      tempField.fieldKey = item.id;
      tempField.postDestination = settingsData.postDestination;
      tempField.saveKey = value;
      tempField.value='';
      this.props.updateData('update', tempField);
  }
  handleCustomType(e) {
      const { item, settingsData } = this.props;
      let tempField = {};
      tempField.fieldKey = item.id;
      tempField.postDestination = settingsData.postDestination;
      tempField.saveKey = settingsData.saveKey;
      tempField.value= e.target.value;
      this.props.updateData('update', tempField);
  }
  createReuseForm(settingsData) {
    const { item, response } = this.props;
      const key = settingsData.postDestination;
      if (response.hasOwnProperty(key)) {
        const options = {};
        if (Array.isArray(response[key])) {
          for(let index=0; index < response[key].length; index++) {
            options[response[key][index]] = response[key][index];
          }
          if (key.search('meta') !== -1) {
            options['custom_type'] = 'custom_type';
          }
        } else {
          for (const singleKey in response[key]) {
            if (response[key].hasOwnProperty(singleKey)) {
              options[response[key][singleKey]] = singleKey;
            }
          }
        }
        return options;
      }
      return [];
  }
  render() {
    const { settingsData, item, response } = this.props;
    const postDestination = {};
    for (const key in response) {
      if (response.hasOwnProperty(key)) {
        postDestination[key] = key;
      }
    }
    const style = {
      width: '100%',
    }
    let optionSaveKey = [];
    if (settingsData.postDestination !== null) {
      optionSaveKey = this.createReuseForm(settingsData);
    }
    const selectPostDestination = {
      item: {
        id: 'postDestination',
        type: 'select',
        label: '',
        multiple: 'false',
        options: postDestination,
        value: settingsData.postDestination ? settingsData.postDestination : '',
      },
      updateData: (item, selectedData) => this.handlePostDestination(selectedData),
      allFieldValue:{},
    }
    const selectSaveKey = {
      item: {
        id: 'saveKey',
        type: 'select',
        label: '',
        multiple: 'false',
        options: optionSaveKey,
        value: settingsData.saveKey ? settingsData.saveKey : '',
      },
      updateData: (item, selectedData) => this.handleSaveKey(selectedData),
      allFieldValue:{},
    }
    return (<div className={Styles.scwpSingleSettingsFieldWrapper}>
      <button type="button" className={Styles.scwpSingleSettingsFieldBtn} onClick={item.type !== 'compoundbutton' ? this.handleCollapse.bind(this, this.state.isOpened) : null}>{item.label}</button>
      <Collapse isOpened={this.state.isOpened} className={`${Styles.scwpCollapsibleWrapper} scwpCollapsibleWrapper___`}>
        <div className={Styles.scwpSingleSettingsField}>
          <label className={Styles.scwpSingleSettingsFieldTitle}>Post Destination</label>
          <Select {...selectPostDestination} />
        </div>
        {optionSaveKey ? <div className={Styles.scwpSingleSettingsField}>
          <label className={Styles.scwpSingleSettingsFieldTitle}>SaveKey</label>
          <Select {...selectSaveKey} />
        </div> : null}
        {settingsData.saveKey === 'custom_type' ? <div className={Styles.scwpSingleSettingsField}><input className={Styles.scwpInputField} onChange={this.handleCustomType} value={settingsData.value ? settingsData.value: ''} /></div> : null}
      </Collapse>
    </div>);
  }
}
